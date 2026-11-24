'use client'

import { useState, useEffect } from 'react'
import { api, Service } from '@/lib/api'
import { ChevronLeftIcon, CheckIcon } from '@heroicons/react/24/outline'

const steps = ['Service', 'Vehicle', 'Date & Time', 'Contact', 'Confirm']

export default function BookPage() {
  const [step, setStep] = useState(0)
  const [services, setServices] = useState<Service[]>([])
  const [loading, setLoading] = useState(false)
  const [submitted, setSubmitted] = useState(false)

  const [form, setForm] = useState({
    service_id: '',
    customer: { name: '', email: '', phone: '' },
    vehicle: { make: '', model: '', year: '', vin: '' },
    requested_date: '',
    requested_time: '',
    notes: '',
  })

  useEffect(() => {
    api.services.list().then((res) => setServices(res.data)).catch(() => {})
  }, [])

  const canProceed = () => {
    switch (step) {
      case 0: return !!form.service_id
      case 1: return form.vehicle.make && form.vehicle.model
      case 2: return form.requested_date && form.requested_time
      case 3: return form.customer.name && form.customer.email && form.customer.phone
      case 4: return true
      default: return false
    }
  }

  const handleSubmit = async () => {
    setLoading(true)
    try {
      const payload = {
        service_id: Number(form.service_id),
        requested_date: form.requested_date,
        requested_time: form.requested_time,
        notes: form.notes,
        customer: form.customer,
        vehicle: form.vehicle,
      }
      await api.appointments.create(payload)
      setSubmitted(true)
    } catch (err: any) {
      alert(err.message || 'Booking failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (submitted) {
    return (
      <section className="py-20">
        <div className="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="w-16 h-16 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
            <CheckIcon className="w-8 h-8 text-green-600 dark:text-green-400" />
          </div>
          <h2 className="text-2xl font-bold text-qac-900 dark:text-white mb-2">Booking Confirmed!</h2>
          <p className="text-qac-500 dark:text-qac-300 mb-6">We&apos;ll send you a confirmation shortly. You can also call us at (832) 896-3865.</p>
          <a href="/" className="btn-primary">Back to Home</a>
        </div>
      </section>
    )
  }

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Book an Appointment</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300">Schedule your service in a few simple steps.</p>
        </div>
      </section>

      <section className="py-10 lg:py-16">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Progress */}
          <div className="flex items-center justify-between mb-10">
            {steps.map((s, i) => (
              <div key={s} className="flex items-center">
                <div className={`w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold ${
                  i <= step ? 'bg-amber-500 text-white' : 'bg-qac-100 dark:bg-qac-800 text-qac-400 dark:text-qac-400'
                }`}>
                  {i < step ? <CheckIcon className="w-4 h-4" /> : i + 1}
                </div>
                <span className={`ml-2 text-sm hidden sm:block ${i <= step ? 'text-qac-900 dark:text-white font-medium' : 'text-qac-400 dark:text-qac-400'}`}>{s}</span>
                {i < steps.length - 1 && <div className={`h-0.5 w-8 sm:w-16 mx-2 ${i < step ? 'bg-amber-500' : 'bg-qac-200 dark:bg-qac-700'}`} />}
              </div>
            ))}
          </div>

          {/* Step content */}
          <div className="card p-8">
            {step > 0 && (
              <button onClick={() => setStep(step - 1)} className="flex items-center gap-1 text-sm text-qac-500 dark:text-qac-300 hover:text-qac-900 dark:hover:text-white mb-6">
                <ChevronLeftIcon className="w-4 h-4" /> Back
              </button>
            )}

            {step === 0 && (
              <div>
                <h2 className="text-xl font-bold text-qac-900 dark:text-white mb-4">Select a Service</h2>
                <div className="space-y-3 max-h-96 overflow-y-auto pr-2">
                  {services.filter(s => s.is_active).map((service) => (
                    <button
                      key={service.id}
                      onClick={() => setForm({ ...form, service_id: String(service.id) })}
                      className={`w-full text-left p-4 rounded-xl border-2 transition-all ${
                        Number(form.service_id) === service.id
                          ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/30'
                          : 'border-qac-200 dark:border-qac-700 hover:border-qac-300 dark:hover:border-qac-500'
                      }`}
                    >
                      <div className="flex items-center justify-between">
                        <div>
                          <p className="font-semibold text-qac-900 dark:text-white">{service.name}</p>
                          <p className="text-sm text-qac-500 dark:text-qac-300 mt-1">{service.description}</p>
                        </div>
                        <div className="text-right ml-4">
                          {service.price_range && (
                            <span className="tag-amber text-xs whitespace-nowrap">{service.price_range}</span>
                          )}
                        </div>
                      </div>
                    </button>
                  ))}
                </div>
              </div>
            )}

            {step === 1 && (
              <div>
                <h2 className="text-xl font-bold text-qac-900 dark:text-white mb-4">Vehicle Details</h2>
                <div className="grid sm:grid-cols-2 gap-4">
                  <div className="sm:col-span-2">
                    <label className="label-field">Make *</label>
                    <input className="input-field" placeholder="e.g. Honda, BMW, Ford" value={form.vehicle.make}
                      onChange={(e) => setForm({ ...form, vehicle: { ...form.vehicle, make: e.target.value } })} />
                  </div>
                  <div className="sm:col-span-2">
                    <label className="label-field">Model *</label>
                    <input className="input-field" placeholder="e.g. Accord, X5, F-150" value={form.vehicle.model}
                      onChange={(e) => setForm({ ...form, vehicle: { ...form.vehicle, model: e.target.value } })} />
                  </div>
                  <div>
                    <label className="label-field">Year</label>
                    <input className="input-field" placeholder="2024" value={form.vehicle.year}
                      onChange={(e) => setForm({ ...form, vehicle: { ...form.vehicle, year: e.target.value } })} />
                  </div>
                  <div>
                    <label className="label-field">VIN</label>
                    <input className="input-field" placeholder="1HGCM82633A123456" value={form.vehicle.vin}
                      onChange={(e) => setForm({ ...form, vehicle: { ...form.vehicle, vin: e.target.value } })} />
                  </div>
                </div>
              </div>
            )}

            {step === 2 && (
              <div>
                <h2 className="text-xl font-bold text-qac-900 dark:text-white mb-4">Preferred Date & Time</h2>
                <div className="grid sm:grid-cols-2 gap-4">
                  <div>
                    <label className="label-field">Date *</label>
                    <input type="date" className="input-field" value={form.requested_date}
                      onChange={(e) => setForm({ ...form, requested_date: e.target.value })}
                      min={new Date().toISOString().split('T')[0]} />
                  </div>
                  <div>
                    <label className="label-field">Time *</label>
                    <input type="time" className="input-field" value={form.requested_time}
                      onChange={(e) => setForm({ ...form, requested_time: e.target.value })} />
                  </div>
                </div>
                <div className="mt-4">
                  <label className="label-field">Notes (optional)</label>
                  <textarea className="input-field" rows={3} value={form.notes}
                    onChange={(e) => setForm({ ...form, notes: e.target.value })} placeholder="Describe any issues..." />
                </div>
              </div>
            )}

            {step === 3 && (
              <div>
                <h2 className="text-xl font-bold text-qac-900 dark:text-white mb-4">Contact Information</h2>
                <div className="space-y-4">
                  <div>
                    <label className="label-field">Full Name *</label>
                    <input className="input-field" value={form.customer.name}
                      onChange={(e) => setForm({ ...form, customer: { ...form.customer, name: e.target.value } })} />
                  </div>
                  <div>
                    <label className="label-field">Email *</label>
                    <input type="email" className="input-field" value={form.customer.email}
                      onChange={(e) => setForm({ ...form, customer: { ...form.customer, email: e.target.value } })} />
                  </div>
                  <div>
                    <label className="label-field">Phone *</label>
                    <input type="tel" className="input-field" value={form.customer.phone}
                      onChange={(e) => setForm({ ...form, customer: { ...form.customer, phone: e.target.value } })} />
                  </div>
                </div>
              </div>
            )}

            {step === 4 && (
              <div>
                <h2 className="text-xl font-bold text-qac-900 dark:text-white mb-6">Confirm Your Booking</h2>
                <div className="space-y-4">
                  <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-4">
                    <p className="text-sm text-qac-500 dark:text-qac-300">Service</p>
                    <p className="font-semibold text-qac-900 dark:text-white">{services.find(s => s.id === Number(form.service_id))?.name}</p>
                  </div>
                  <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-4">
                    <p className="text-sm text-qac-500 dark:text-qac-300">Vehicle</p>
                    <p className="font-semibold text-qac-900 dark:text-white">{form.vehicle.make} {form.vehicle.model} {form.vehicle.year}</p>
                  </div>
                  <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-4">
                    <p className="text-sm text-qac-500 dark:text-qac-300">Date & Time</p>
                    <p className="font-semibold text-qac-900 dark:text-white">{form.requested_date} at {form.requested_time}</p>
                  </div>
                  <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-4">
                    <p className="text-sm text-qac-500 dark:text-qac-300">Contact</p>
                    <p className="font-semibold text-qac-900 dark:text-white">{form.customer.name}</p>
                    <p className="text-sm text-qac-500 dark:text-qac-300">{form.customer.email} | {form.customer.phone}</p>
                  </div>
                  {form.notes && (
                    <div className="bg-qac-50 dark:bg-qac-900/40 rounded-xl p-4">
                      <p className="text-sm text-qac-500 dark:text-qac-300">Notes</p>
                      <p className="text-qac-700 dark:text-qac-300">{form.notes}</p>
                    </div>
                  )}
                </div>
              </div>
            )}

            <div className="mt-8 flex justify-end">
              {step < 4 ? (
                <button onClick={() => setStep(step + 1)} disabled={!canProceed()} className="btn-primary">
                  Continue
                </button>
              ) : (
                <button onClick={handleSubmit} disabled={loading} className="btn-primary">
                  {loading ? 'Booking...' : 'Confirm Booking'}
                </button>
              )}
            </div>
          </div>
        </div>
      </section>
    </>
  )
}
