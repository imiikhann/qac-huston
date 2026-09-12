'use client'

import { useState, useEffect, Suspense } from 'react'
import { useSearchParams } from 'next/navigation'
import { api, MembershipProgram } from '@/lib/api'
import { CheckIcon } from '@heroicons/react/24/outline'

function EnrollForm() {
  const searchParams = useSearchParams()
  const selectedProgramId = searchParams.get('program')

  const [programs, setPrograms] = useState<MembershipProgram[]>([])
  const [loading, setLoading] = useState(false)
  const [submitted, setSubmitted] = useState(false)

  const [form, setForm] = useState({
    program_id: '',
    customer: { name: '', email: '', phone: '' },
    vehicle: { make: '', model: '', year: '', vin: '' },
  })

  useEffect(() => {
    api.cms.membershipPrograms()
      .then((res) => {
        setPrograms(res.data)
        if (selectedProgramId) {
          setForm((f) => ({ ...f, program_id: selectedProgramId }))
        }
      })
      .catch(() => {})
  }, [selectedProgramId])

  const selected = programs.find((p) => p.id === Number(form.program_id))

  const canSubmit = () =>
    !!form.program_id &&
    form.customer.name &&
    form.customer.email &&
    form.customer.phone

  const handleSubmit = async () => {
    setLoading(true)
    try {
      const fd = new FormData()
      fd.append('program_id', form.program_id)
      fd.append('customer[name]', form.customer.name)
      fd.append('customer[email]', form.customer.email)
      fd.append('customer[phone]', form.customer.phone)
      if (form.vehicle.make) fd.append('vehicle[make]', form.vehicle.make)
      if (form.vehicle.model) fd.append('vehicle[model]', form.vehicle.model)
      if (form.vehicle.year) fd.append('vehicle[year]', form.vehicle.year)
      if (form.vehicle.vin) fd.append('vehicle[vin]', form.vehicle.vin)
      await api.memberships.enroll(fd)
      setSubmitted(true)
    } catch (err: any) {
      alert(err.message || 'Enrollment failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (submitted) {
    return (
      <section className="py-20">
        <div className="max-w-lg mx-auto px-4 text-center">
          <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <CheckIcon className="w-8 h-8 text-green-600" />
          </div>
          <h2 className="text-2xl font-bold text-qac-900 mb-2">Enrollment Request Submitted!</h2>
          <p className="text-qac-500 mb-6">
            Thank you for choosing {selected?.name || 'our membership program'}. We&apos;ve received your
            request and will confirm your membership shortly. You can also call us at (832) 896-3865.
          </p>
          <a href="/" className="btn-primary">Back to Home</a>
        </div>
      </section>
    )
  }

  return (
    <>
      <section className="bg-qac-50 py-16 lg:py-20">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 mb-4">Enroll in a Membership</h1>
          <p className="text-xl text-qac-500">Join the QAC Houston family and start enjoying exclusive benefits.</p>
        </div>
      </section>

      <section className="py-10 lg:py-16">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="card p-8">
            <div className="space-y-4">
              <div>
                <label className="label-field">Membership Program *</label>
                <select
                  className="input-field"
                  value={form.program_id}
                  onChange={(e) => setForm({ ...form, program_id: e.target.value })}
                >
                  <option value="">Select a program...</option>
                  {programs.filter((p) => p.is_active).map((p) => (
                    <option key={p.id} value={p.id}>{p.name} — {p.price}</option>
                  ))}
                </select>
              </div>

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

              <div className="border-t border-qac-200 pt-6 mt-2">
                <h3 className="text-base font-bold text-qac-900 mb-4">Vehicle Details (optional)</h3>
                <div className="grid sm:grid-cols-2 gap-4">
                  <div>
                    <label className="label-field">Make</label>
                    <input className="input-field" placeholder="e.g. Honda, BMW" value={form.vehicle.make}
                      onChange={(e) => setForm({ ...form, vehicle: { ...form.vehicle, make: e.target.value } })} />
                  </div>
                  <div>
                    <label className="label-field">Model</label>
                    <input className="input-field" placeholder="e.g. Accord, X5" value={form.vehicle.model}
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
            </div>

            <div className="mt-8 flex justify-end">
              <button onClick={handleSubmit} disabled={!canSubmit() || loading} className="btn-primary">
                {loading ? 'Submitting...' : 'Submit Enrollment'}
              </button>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default function EnrollPage() {
  return (
    <Suspense fallback={<div className="py-40 text-center text-qac-500">Loading...</div>}>
      <EnrollForm />
    </Suspense>
  )
}
