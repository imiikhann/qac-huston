'use client'

import { useState } from 'react'
import { api } from '@/lib/api'

const serviceOptions = [
  'General Maintenance', 'Emergency Repairs', 'Fleet Services', 'Luxury Vehicle Services',
  'Insurance Claims', 'Specialized Services', 'Not Sure Yet',
]

export default function ContactPage() {
  const [form, setForm] = useState({
    name: '', email: '', phone: '', service: '', message: '', vehicle_make: '', vehicle_model: '', vehicle_year: '',
  })
  const [submitted, setSubmitted] = useState(false)
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    try {
      await api.contact.send(form)
      setSubmitted(true)
    } catch {
      alert('Failed to send message. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (submitted) {
    return (
      <section className="py-20">
        <div className="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="w-16 h-16 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg className="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" /></svg>
          </div>
          <h2 className="text-2xl font-bold text-qac-900 dark:text-white mb-2">Message Sent!</h2>
          <p className="text-qac-500 dark:text-qac-300">We&apos;ll get back to you shortly.</p>
        </div>
      </section>
    )
  }

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Get in Touch</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300">We&apos;d love to hear from you.</p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-12">
          <div>
            <h2 className="text-2xl font-bold text-qac-900 dark:text-white mb-6">Send Us a Message</h2>
            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="label-field">Full Name *</label>
                <input className="input-field" required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
              </div>
              <div>
                <label className="label-field">Email *</label>
                <input type="email" className="input-field" required value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
              </div>
              <div>
                <label className="label-field">Phone</label>
                <input className="input-field" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
              </div>
              <div>
                <label className="label-field">Service Interested In</label>
                <select className="input-field" value={form.service} onChange={(e) => setForm({ ...form, service: e.target.value })}>
                  <option value="">Select...</option>
                  {serviceOptions.map((s) => <option key={s} value={s}>{s}</option>)}
                </select>
              </div>
              <div>
                <label className="label-field">Vehicle Details</label>
                <div className="grid grid-cols-3 gap-3">
                  <input placeholder="Make" className="input-field" value={form.vehicle_make} onChange={(e) => setForm({ ...form, vehicle_make: e.target.value })} />
                  <input placeholder="Model" className="input-field" value={form.vehicle_model} onChange={(e) => setForm({ ...form, vehicle_model: e.target.value })} />
                  <input placeholder="Year" className="input-field" value={form.vehicle_year} onChange={(e) => setForm({ ...form, vehicle_year: e.target.value })} />
                </div>
              </div>
              <div>
                <label className="label-field">Message *</label>
                <textarea className="input-field" rows={4} required value={form.message} onChange={(e) => setForm({ ...form, message: e.target.value })} />
              </div>
              <button type="submit" disabled={loading} className="btn-primary w-full">
                {loading ? 'Sending...' : 'Send Message'}
              </button>
            </form>
          </div>

          <div>
            <h2 className="text-2xl font-bold text-qac-900 dark:text-white mb-6">Contact Information</h2>
            <div className="space-y-6">
              <div className="card p-6">
                <h3 className="font-semibold text-qac-900 dark:text-white mb-1">Address</h3>
                <p className="text-qac-600 dark:text-qac-400">9934 Jones Rd B5<br />Houston, TX 77065</p>
              </div>
              <div className="card p-6">
                <h3 className="font-semibold text-qac-900 dark:text-white mb-1">Phone</h3>
                <a href="tel:+18328963865" className="text-amber-600 dark:text-amber-400 hover:text-amber-700 font-medium">(832) 896-3865</a>
              </div>
              <div className="card p-6">
                <h3 className="font-semibold text-qac-900 dark:text-white mb-1">Email</h3>
                <a href="mailto:contact@qachouston.com" className="text-amber-600 dark:text-amber-400 hover:text-amber-700 font-medium">contact@qachouston.com</a>
              </div>
              <div className="card p-6">
                <h3 className="font-semibold text-qac-900 dark:text-white mb-1">Hours</h3>
                <p className="text-qac-600 dark:text-qac-400">Mon-Thu: 9am - 6pm</p>
                <p className="text-qac-600 dark:text-qac-400">Fri-Sat: 9am - 1pm</p>
                <p className="text-qac-600 dark:text-qac-400">Sun: Closed</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}
