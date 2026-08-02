import type { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'Why Choose QAC?',
  description: 'Discover why QAC Houston is the trusted choice for auto repair. Convenient pickup & drop-off, honest recommendations, and community support.',
}

const reasons = [
  { title: 'Convenient Pickup & Drop-Off', desc: 'We offer pickup and drop-off services for our customers.' },
  { title: 'Towing Expense Reimbursement', desc: 'We reimburse towing expenses for qualifying repairs.' },
  { title: 'Empowering Customers with Knowledge', desc: 'We provide valuable insights through our YouTube channel.' },
  { title: 'Honest and Transparent Repairs', desc: 'We prioritize transparency in all repair recommendations.' },
  { title: 'Affordable, High-Quality Solutions', desc: 'We deliver cost-effective and high-quality repairs.' },
  { title: 'Exceptional Customer Service', desc: 'We strive to provide the best customer experience.' },
  { title: 'Supporting Our Community', desc: 'We actively give back to the community.' },
  { title: 'Privacy, Safety, and Cleanliness', desc: 'We ensure the safety of our customers\' vehicles.' },
]

export default function WhyQACPage() {
  return (
    <>
      <section className="bg-qac-50 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 mb-4">Why Choose QAC?</h1>
          <p className="text-xl text-qac-500 max-w-2xl mx-auto">
            We bring transparency, trust, and quality to every repair.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {reasons.map((reason, i) => (
              <div key={i} className="card">
                <div className="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center mb-4">
                  <span className="text-amber-600 font-bold">{String(i + 1).padStart(2, '0')}</span>
                </div>
                <h3 className="font-semibold text-qac-900 mb-2">{reason.title}</h3>
                <p className="text-sm text-qac-500">{reason.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  )
}
