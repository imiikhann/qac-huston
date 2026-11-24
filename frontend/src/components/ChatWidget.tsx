'use client'

import { useCallback, useEffect, useRef, useState } from 'react'
import {
  ChatBubbleLeftRightIcon,
  PaperAirplaneIcon,
  XMarkIcon,
} from '@heroicons/react/24/outline'
import Pusher from 'pusher-js'
import { api, Ticket, TicketMessage } from '@/lib/api'

interface StoredTicket {
  uuid: string
  key: string
  name: string
  email: string
}

const STORAGE_KEY = 'qac_support_ticket'

function loadStoredTicket(): StoredTicket | null {
  if (typeof window === 'undefined') return null
  try {
    const raw = window.localStorage.getItem(STORAGE_KEY)
    return raw ? (JSON.parse(raw) as StoredTicket) : null
  } catch {
    return null
  }
}

function formatTime(dateStr: string): string {
  const d = new Date(dateStr)
  if (Number.isNaN(d.getTime())) return ''
  return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })
}

function isNetworkError(err: unknown): boolean {
  return err instanceof Error && err.name === 'TypeError'
}

export default function ChatWidget() {
  const [isOpen, setIsOpen] = useState(false)
  const [stored, setStored] = useState<StoredTicket | null>(null)
  const [messages, setMessages] = useState<TicketMessage[]>([])
  const [status, setStatus] = useState<Ticket['status'] | null>(null)
  const [loading, setLoading] = useState(false)
  const [sending, setSending] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [firstMessage, setFirstMessage] = useState('')

  const [input, setInput] = useState('')

  const bottomRef = useRef<HTMLDivElement>(null)
  const wsConnectedRef = useRef(false)

  const isClosed = status === 'resolved' || status === 'closed'
  const showThread = !!stored

  const refresh = useCallback(async (t: StoredTicket) => {
    try {
      const res = await api.tickets.get(t.uuid, t.key)
      const msgs = res.data.messages ?? []
      setMessages(msgs)
      setStatus(res.data.status)
      setError(null)
      try { sessionStorage.setItem('qac_msgs_' + t.uuid, JSON.stringify(msgs)) } catch {}
    } catch {
      // Transient network error during polling — keep the current view.
    }
  }, [])

  useEffect(() => {
    const t = loadStoredTicket()
    if (!t) return
    setStored(t)
    try {
      const cached = sessionStorage.getItem('qac_msgs_' + t.uuid)
      if (cached) {
        const parsed = JSON.parse(cached)
        if (Array.isArray(parsed) && parsed.length > 0) setMessages(parsed)
      }
    } catch {}
    setLoading(true)
    refresh(t).finally(() => setLoading(false))
  }, [refresh])

  useEffect(() => {
    if (!isOpen || !stored) return
    let cancelled = false
    let timer: ReturnType<typeof setTimeout> | undefined

    const schedule = (delay: number) => {
      timer = setTimeout(async () => {
        if (cancelled) return
        if (!document.hidden && !wsConnectedRef.current) await refresh(stored)
        if (!cancelled) schedule(30000)
      }, delay)
    }

    schedule(0)

    const onVisible = () => {
      if (!document.hidden && !cancelled) {
        clearTimeout(timer)
        schedule(100)
      }
    }
    document.addEventListener('visibilitychange', onVisible)

    return () => {
      cancelled = true
      clearTimeout(timer)
      document.removeEventListener('visibilitychange', onVisible)
    }
  }, [isOpen, stored, refresh])

  useEffect(() => {
    if (!isOpen || !stored) return

    const usePusher = process.env.NEXT_PUBLIC_BROADCAST_DRIVER === 'pusher'
    const key = usePusher
      ? process.env.NEXT_PUBLIC_PUSHER_APP_KEY
      : process.env.NEXT_PUBLIC_REVERB_APP_KEY
    if (!key) return

    let cancelled = false
    let pusher: Pusher | null = null

    const connect = () => {
      if (cancelled) return

      if (usePusher) {
        pusher = new Pusher(key, {
          cluster: process.env.NEXT_PUBLIC_PUSHER_APP_CLUSTER || 'mt1',
          forceTLS: true,
          enabledTransports: ['ws', 'wss'],
          disabledTransports: ['xhr_polling', 'xhr_streaming'],
        })
      } else {
        const host = process.env.NEXT_PUBLIC_REVERB_HOST || 'localhost'
        const port = Number(process.env.NEXT_PUBLIC_REVERB_PORT || '8080')
        const scheme = process.env.NEXT_PUBLIC_REVERB_SCHEME || 'http'

        pusher = new Pusher(key, {
          cluster: 'mt1',
          wsHost: host,
          wsPort: port,
          wssPort: port,
          forceTLS: scheme === 'https',
          enabledTransports: ['ws', 'wss'],
          disabledTransports: ['xhr_polling', 'xhr_streaming'],
        })
      }

      pusher.connection.bind('state_change', (s: { current: string }) => {
        wsConnectedRef.current = s.current === 'connected'
      })
      if (pusher.connection.state === 'connected') wsConnectedRef.current = true

      const channel = pusher.subscribe(`ticket.${stored.uuid}`)
      channel.bind(
        'admin-reply',
        (data: {
          id: number
          sender: 'customer' | 'admin'
          message: string
          status: Ticket['status']
          created_at: string
        }) => {
          if (cancelled) return
          setMessages((prev) =>
            prev.some((m) => m.id === data.id) ? prev : [...prev, data],
          )
          setStatus(data.status)
        },
      )
    }

    connect()

    const recover = () => {
      if (cancelled || !pusher) return
      const state = pusher.connection.state
      wsConnectedRef.current = state === 'connected'
      if (state !== 'connected' && state !== 'connecting') {
        try {
          pusher.disconnect()
          pusher.connect()
        } catch {
          // ignore — next watchdog tick retries
        }
      }
    }
    const watchdog = setInterval(recover, 15000)
    const onVisible = () => {
      if (!document.hidden) recover()
    }
    document.addEventListener('visibilitychange', onVisible)

    return () => {
      cancelled = true
      wsConnectedRef.current = false
      clearInterval(watchdog)
      document.removeEventListener('visibilitychange', onVisible)
      pusher?.disconnect()
    }
  }, [isOpen, stored])

  useEffect(() => {
    if (!isOpen) return
    bottomRef.current?.scrollIntoView({ behavior: 'smooth', block: 'end' })
  }, [messages, isOpen, loading])

  async function startConversation(e: React.FormEvent) {
    e.preventDefault()
    if (sending) return
    setError(null)
    setSending(true)
    try {
      const res = await api.tickets.create({
        name: name.trim(),
        email: email.trim(),
        message: firstMessage.trim(),
      })
      const t: StoredTicket = {
        uuid: res.data.uuid,
        key: res.data.key as string,
        name: res.data.name,
        email: res.data.email,
      }
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(t))
      setStored(t)
      setMessages(res.data.messages ?? [])
      setStatus(res.data.status)
      setFirstMessage('')
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Something went wrong. Please try again.')
    } finally {
      setSending(false)
    }
  }

  async function sendReply(e: React.FormEvent) {
    e.preventDefault()
    if (!stored || sending || !input.trim()) return
    setError(null)
    setSending(true)
    try {
      const res = await api.tickets.reply(stored.uuid, stored.key, input.trim())
      setMessages((prev) => [...prev, res.data])
      setInput('')
      setStatus((prev) => (prev === 'resolved' || prev === 'closed' ? 'open' : prev))
    } catch (err) {
      setError(
        isNetworkError(err)
          ? 'Network error — please check your connection and try again.'
          : err instanceof Error
            ? err.message
            : 'Could not send your message. Please try again.',
      )
    } finally {
      setSending(false)
    }
  }

  function resetConversation() {
    if (stored) {
      try { sessionStorage.removeItem('qac_msgs_' + stored.uuid) } catch {}
    }
    window.localStorage.removeItem(STORAGE_KEY)
    setStored(null)
    setMessages([])
    setStatus(null)
    setError(null)
    setName('')
    setEmail('')
    setFirstMessage('')
    setInput('')
  }

  const subtitle = isClosed ? 'Conversation closed' : 'Online · typically replies in a few minutes'

  return (
    <>
      {isOpen && (
        <div className="fixed bottom-20 right-4 sm:right-6 z-50 w-80 sm:w-96 h-[30rem] sm:h-[34rem] bg-white dark:bg-qac-900/60 rounded-2xl shadow-2xl border border-qac-100 dark:border-qac-800 overflow-hidden flex flex-col">
          <div className="bg-qac-900 px-4 py-3.5 flex items-center justify-between shrink-0">
            <div className="flex items-center gap-3">
              <div className="w-9 h-9 bg-amber-gradient rounded-full flex items-center justify-center shadow-md">
                <ChatBubbleLeftRightIcon className="w-5 h-5 text-white" />
              </div>
              <div>
                <p className="text-white font-semibold text-sm">QAC Assistant</p>
                <p className="text-qac-400 text-[11px] flex items-center gap-1.5">
                  <span className={`w-1.5 h-1.5 rounded-full ${isClosed ? 'bg-qac-50 dark:bg-qac-900/300' : 'bg-green-400'}`} />
                  {subtitle}
                </p>
              </div>
            </div>
            <button
              onClick={() => setIsOpen(false)}
              className="text-qac-400 hover:text-white transition-colors"
              aria-label="Close chat"
            >
              <XMarkIcon className="w-5 h-5" />
            </button>
          </div>

          <div className="flex-1 overflow-y-auto bg-qac-50 dark:bg-qac-900/30 px-4 py-4 space-y-3">
            {!showThread && (
              <>
                <div className="flex items-start gap-2">
                  <div className="w-7 h-7 bg-amber-gradient rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <ChatBubbleLeftRightIcon className="w-3.5 h-3.5 text-white" />
                  </div>
                  <div className="bg-white dark:bg-qac-900/60 border border-qac-100 dark:border-qac-800 rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-qac-900 dark:text-white max-w-[85%] shadow-sm leading-relaxed">
                    Hi there 👋 Welcome to QAC Houston! Send us a message and our team will get back to you shortly.
                  </div>
                </div>

                <form
                  onSubmit={startConversation}
                  className="bg-white dark:bg-qac-900/60 border border-qac-100 dark:border-qac-800 rounded-xl p-4 shadow-sm space-y-3"
                >
                  <p className="text-xs font-bold text-qac-700 dark:text-qac-200 uppercase tracking-wide">
                    Start a conversation
                  </p>
                  <div>
                    <label htmlFor="chat-name" className="block text-xs font-semibold text-qac-600 dark:text-qac-300 mb-1">
                      Your name
                    </label>
                    <input
                      id="chat-name"
                      type="text"
                      required
                      maxLength={255}
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      placeholder="John Doe"
                      className="w-full rounded-lg border border-qac-200 dark:border-qac-700 bg-white dark:bg-qac-900/60 px-3 py-2 text-sm text-qac-900 dark:text-white placeholder:text-qac-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label htmlFor="chat-email" className="block text-xs font-semibold text-qac-600 dark:text-qac-300 mb-1">
                      Email address
                    </label>
                    <input
                      id="chat-email"
                      type="email"
                      required
                      maxLength={255}
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      placeholder="you@example.com"
                      className="w-full rounded-lg border border-qac-200 dark:border-qac-700 bg-white dark:bg-qac-900/60 px-3 py-2 text-sm text-qac-900 dark:text-white placeholder:text-qac-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label htmlFor="chat-first" className="block text-xs font-semibold text-qac-600 dark:text-qac-300 mb-1">
                      How can we help?
                    </label>
                    <textarea
                      id="chat-first"
                      required
                      maxLength={4000}
                      value={firstMessage}
                      onChange={(e) => setFirstMessage(e.target.value)}
                      placeholder="Type your message..."
                      rows={3}
                      className="w-full rounded-lg border border-qac-200 dark:border-qac-700 bg-white dark:bg-qac-900/60 px-3 py-2 text-sm text-qac-900 dark:text-white placeholder:text-qac-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"
                    />
                  </div>
                  <button
                    type="submit"
                    disabled={sending}
                    className="w-full bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white font-semibold text-sm rounded-lg py-2.5 transition-colors flex items-center justify-center gap-2"
                  >
                    {sending ? 'Starting…' : 'Start Conversation'}
                  </button>
                </form>
              </>
            )}

            {showThread && (
              <>
                {loading && messages.length === 0 && (
                  <div className="flex items-center justify-center h-full">
                    <div className="w-6 h-6 border-2 border-amber-500 border-t-transparent rounded-full animate-spin" />
                  </div>
                )}

                {isClosed && (
                  <div className="bg-qac-100 dark:bg-qac-800/60 border border-qac-200 dark:border-qac-700 rounded-xl px-4 py-3 text-center">
                    <p className="text-xs text-qac-600 dark:text-qac-300 mb-2">
                      This conversation has been closed by our team.
                    </p>
                    <button
                      onClick={resetConversation}
                      className="text-xs font-semibold text-amber-600 hover:text-amber-700 underline underline-offset-2"
                    >
                      Start a new conversation
                    </button>
                  </div>
                )}

                {messages.map((m, i) => (
                  <div
                    key={m.id ?? i}
                    className={`flex items-end gap-2 ${m.sender === 'admin' ? 'justify-start' : 'justify-end'}`}
                  >
                    {m.sender === 'admin' && (
                      <div className="w-6 h-6 bg-amber-gradient rounded-full flex items-center justify-center shrink-0">
                        <ChatBubbleLeftRightIcon className="w-3 h-3 text-white" />
                      </div>
                    )}
                    <div
                      className={`max-w-[80%] px-3.5 py-2.5 text-sm leading-relaxed shadow-sm ${
                        m.sender === 'admin'
                          ? 'bg-white dark:bg-qac-900/60 border border-qac-100 dark:border-qac-800 text-qac-900 dark:text-white rounded-2xl rounded-tl-sm'
                          : 'bg-amber-gradient text-white rounded-2xl rounded-tr-sm'
                      }`}
                    >
                      {m.sender === 'admin' && (
                        <p className="text-[10px] font-bold text-amber-600 uppercase tracking-wide mb-1">
                          QAC Support
                        </p>
                      )}
                      <p className="whitespace-pre-wrap break-words">{m.message}</p>
                      <p
                        className={`text-[10px] mt-1.5 text-right ${
                          m.sender === 'admin' ? 'text-qac-400' : 'text-white/70'
                        }`}
                      >
                        {formatTime(m.created_at)}
                      </p>
                    </div>
                  </div>
                ))}

                {sending && (
                  <div className="flex justify-end">
                    <div className="bg-amber-500/70 rounded-2xl rounded-tr-sm px-4 py-3 flex gap-1 items-center shadow-sm">
                      <span className="w-1.5 h-1.5 bg-white dark:bg-qac-900/60 rounded-full animate-bounce [animation-delay:0ms]" />
                      <span className="w-1.5 h-1.5 bg-white dark:bg-qac-900/60 rounded-full animate-bounce [animation-delay:150ms]" />
                      <span className="w-1.5 h-1.5 bg-white dark:bg-qac-900/60 rounded-full animate-bounce [animation-delay:300ms]" />
                    </div>
                  </div>
                )}

                <div ref={bottomRef} />
              </>
            )}
          </div>

          {showThread && error && (
            <div className="bg-red-50 border-t border-red-100 px-4 py-2 text-xs text-red-600 shrink-0">
              {error}
            </div>
          )}

          {showThread && (
            <form
              onSubmit={sendReply}
              className="border-t border-qac-100 dark:border-qac-800 bg-white dark:bg-qac-900/60 px-3 py-3 flex items-center gap-2 shrink-0"
            >
              <input
                type="text"
                value={input}
                onChange={(e) => { setInput(e.target.value); if (error) setError(null) }}
                disabled={isClosed}
                maxLength={4000}
                placeholder={isClosed ? 'Conversation closed' : 'Type a message…'}
                aria-label="Message"
                className="flex-1 rounded-full border border-qac-200 dark:border-qac-700 bg-qac-50 dark:bg-qac-900/30 px-4 py-2.5 text-sm text-qac-900 dark:text-white placeholder:text-qac-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent disabled:opacity-60"
              />
              <button
                type="submit"
                disabled={sending || !input.trim() || isClosed}
                aria-label="Send message"
                className="w-10 h-10 rounded-full bg-amber-gradient text-white flex items-center justify-center disabled:opacity-40 hover:opacity-90 transition shadow-md shrink-0"
              >
                <PaperAirplaneIcon className="w-5 h-5" />
              </button>
            </form>
          )}
        </div>
      )}

      <button
        onClick={() => {
          const next = !isOpen
          setIsOpen(next)
          if (next && stored) refresh(stored)
        }}
        className="fixed bottom-4 right-4 sm:right-6 z-50 w-14 h-14 bg-amber-gradient text-white rounded-full shadow-lg
                   hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center justify-center"
        aria-label={isOpen ? 'Close chat' : 'Open chat'}
      >
        {isOpen ? <XMarkIcon className="w-6 h-6" /> : <ChatBubbleLeftRightIcon className="w-6 h-6" />}
      </button>
    </>
  )
}