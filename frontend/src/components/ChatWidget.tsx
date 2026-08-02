'use client'

import { useState } from 'react'
import { ChatBubbleLeftRightIcon, XMarkIcon } from '@heroicons/react/24/outline'

/**
 * AI Chat Widget Placeholder
 * 
 * This is a UI shell for the future AI chat agent integration.
 * When the Python AI chat agent is built, wire it up here.
 * 
 * TODO: Integrate with the AI chat agent backend
 */
export default function ChatWidget() {
  const [isOpen, setIsOpen] = useState(false)

  return (
    <>
      {isOpen && (
        <div className="fixed bottom-20 right-4 sm:right-6 z-50 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-qac-100 overflow-hidden">
          <div className="bg-qac-900 p-4 flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                <ChatBubbleLeftRightIcon className="w-4 h-4 text-white" />
              </div>
              <div>
                <p className="text-white font-semibold text-sm">QAC Assistant</p>
                <p className="text-qac-400 text-xs">AI Chat</p>
              </div>
            </div>
            <button onClick={() => setIsOpen(false)} className="text-qac-400 hover:text-white transition-colors">
              <XMarkIcon className="w-5 h-5" />
            </button>
          </div>
          <div className="h-96 flex items-center justify-center bg-qac-50">
            <p className="text-qac-400 text-sm">Chat widget coming soon</p>
          </div>
        </div>
      )}

      <button
        onClick={() => setIsOpen(!isOpen)}
        className="fixed bottom-4 right-4 sm:right-6 z-50 w-14 h-14 bg-amber-500 text-white rounded-full shadow-lg
                   hover:bg-amber-600 hover:shadow-xl transition-all duration-200 flex items-center justify-center"
        aria-label="Open chat"
      >
        <ChatBubbleLeftRightIcon className="w-6 h-6" />
      </button>
    </>
  )
}
