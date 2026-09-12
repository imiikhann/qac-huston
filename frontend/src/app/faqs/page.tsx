'use client'

import { useState, useEffect } from 'react'
import { api, FAQ } from '@/lib/api'
import { ChevronDownIcon } from '@heroicons/react/24/outline'

export default function FAQsPage() {
  const [faqs, setFaqs] = useState<FAQ[]>([])
  const [openId, setOpenId] = useState<number | null>(null)

  useEffect(() => {
    api.faqs.list().then((res) => setFaqs(res.data)).catch(() => {})
  }, [])

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Frequently Asked Questions</h1>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Static FAQs from site */}
          <div className="space-y-3 mb-12">
            <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-6">
              <h3 className="font-semibold text-qac-900 dark:text-white mb-2">📍 Location</h3>
              <p className="text-qac-600 dark:text-qac-300">Conveniently located in Jersey Village area on Jones road near HW 290.</p>
              <p className="text-qac-600 dark:text-qac-300">Address: 9934 Jones Road, Unit B5, Houston 77065</p>
            </div>
            <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-6">
              <h3 className="font-semibold text-qac-900 dark:text-white mb-2">🕒 Operating Hours</h3>
              <p className="text-qac-600 dark:text-qac-300">Mon-Thu: 9am to 6pm</p>
              <p className="text-qac-600 dark:text-qac-300">Fri-Sat: 9am to 1pm</p>
              <p className="text-qac-600 dark:text-qac-300">Sun: Closed</p>
            </div>
          </div>

          <h2 className="text-2xl font-bold text-qac-900 dark:text-white mb-6">More Questions</h2>
          <div className="space-y-2">
            {faqs.map((faq) => (
              <div key={faq.id} className="border border-qac-200 dark:border-qac-800 rounded-xl overflow-hidden">
                <button
                  onClick={() => setOpenId(openId === faq.id ? null : faq.id)}
                  className="w-full flex items-center justify-between p-5 text-left hover:bg-qac-50 dark:hover:bg-qac-900/40 transition-colors"
                >
                  <span className="font-medium text-qac-900 dark:text-white pr-4">{faq.question}</span>
                  <ChevronDownIcon className={`w-5 h-5 text-qac-400 dark:text-qac-400 shrink-0 transition-transform ${openId === faq.id ? 'rotate-180' : ''}`} />
                </button>
                {openId === faq.id && (
                  <div className="px-5 pb-5">
                    <p className="text-qac-600 dark:text-qac-300 leading-relaxed">{faq.answer}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  )
}
