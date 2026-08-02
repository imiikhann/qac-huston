'use client'

import { useState, useEffect, Suspense } from 'react'
import { useSearchParams } from 'next/navigation'
import { api, MembershipProgram } from '@/lib/api'
import { CheckIcon } from '@heroicons/react/24/outline'
import { PaperClipIcon } from '@heroicons/react/24/outline'

const ALLOWED_TYPES = ['.pdf', '.jpg', '.jpeg', '.doc', '.docx', '.png']
const MAX_SIZE_MB = 5

function EnrollForm() {
  const searchParams = useSearchParams()
  const selectedProgramId = searchParams.get('program')

  const [programs, setPrograms] = useState<MembershipProgram[]>([])
  const [loading, setLoading] = useState(false)
  const [submitted, setSubmitted] = useState(false)
  const [proof, setProof] = useState<File | null>(null)
  const [proofError, setProofError] = useState('')

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
    form.customer.phone &&
    !!proof

  const handleProofChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0] || null
    setProofError('')
    if (!file) {
      setProof(null)
      return
    }
    const ext = '.' + file.name.split('.').pop()?.toLowerCase()
    if (!ALLOWED_TYPES.includes(ext)) {
      setProof(null)
      setProofError('Only PDF, JPG, JPEG, DOC, DOCX, or PNG files are allowed.')
      return
    }
    if (file.size > MAX_SIZE_MB * 1024 * 1024) {
      setProof(null)
      setProofError(`File must be ${MAX_SIZE_MB}MB or smaller.`)
      return
    }
    setProof(file)
  }

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
      if (proof) fd.append('payment_proof', proof)
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
            payment proof and will confirm your membership shortly. You can also call us at (832) 896-3865.
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

              <div className="border-t border-qac-200 pt-6 mt-2">
                <h3 className="text-base font-bold text-qac-900 mb-2">Payment</h3>
                <p className="text-sm text-qac-500 mb-4">
                  Make your payment via online bank transfer to the account below, then upload your payment
                  receipt/proof as an attachment.
                </p>
                <div className="rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-800 p-5">
                  <div className="flex items-center gap-2 mb-3">
                    <span className="w-2 h-2 rounded-full bg-amber-500 animate-pulse" />
                    <span className="text-xs font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">
                      Online Transfer — Bank Details
                    </span>
                  </div>
                  <dl className="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                      <dt className="text-qac-500 text-xs">Bank Name</dt>
                      <dd className="font-semibold text-qac-900 dark:text-white">Bank of America</dd>
                    </div>
                    <div>
                      <dt className="text-qac-500 text-xs">Account Name</dt>
                      <dd className="font-semibold text-qac-900 dark:text-white">QAC Houston LLC</dd>
                    </div>
                    <div>
                      <dt className="text-qac-500 text-xs">Account Number</dt>
                      <dd className="font-mono font-bold text-amber-700 dark:text-amber-300 tracking-wider">1234 5678 9012</dd>
                    </div>
                    <div>
                      <dt className="text-qac-500 text-xs">Routing Number</dt>
                      <dd className="font-mono font-bold text-qac-900 dark:text-white">026009593</dd>
                    </div>
                  </dl>
                  <p className="mt-3 text-xs text-qac-500">
                    Note: Please use your full name as the payment reference so we can match your enrollment.
                  </p>
                </div>

                <div className="mt-5">
                  <label className="label-field">Upload Payment Proof *</label>
                  <label
                    className={`flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl p-6 cursor-pointer transition-colors ${
                      proof
                        ? 'border-green-400 bg-green-50 dark:bg-green-900/20'
                        : 'border-qac-300 bg-qac-50 dark:bg-qac-900/20 hover:border-amber-400'
                    }`}
                  >
                    <PaperClipIcon className="w-8 h-8 text-qac-400" />
                    {proof ? (
                      <span className="text-sm font-semibold text-qac-900 dark:text-white">{proof.name}</span>
                    ) : (
                      <span className="text-sm text-qac-500">
                        Click to upload — <span className="font-semibold text-qac-700 dark:text-qac-300">PDF, JPG, JPEG, DOC, DOCX, PNG</span> (max 5MB)
                      </span>
                    )}
                    <input type="file" accept=".pdf,.jpg,.jpeg,.doc,.docx,.png" className="hidden"
                      onChange={handleProofChange} />
                  </label>
                  {proofError && <p className="mt-2 text-sm text-red-600">{proofError}</p>}
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
