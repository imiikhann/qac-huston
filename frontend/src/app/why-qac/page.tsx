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
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Why Choose QAC?</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300 max-w-2xl mx-auto">
            We bring transparency, trust, and quality to every repair.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="relative">
            <div className="absolute left-5 sm:left-1/2 top-0 bottom-0 w-0.5 bg-amber-gradient -translate-x-1/2" aria-hidden="true" />
            <div className="space-y-8">
              {reasons.map((reason, i) => {
                const left = i % 2 === 0
                return (
                  <div key={i} className={`relative flex items-start gap-6 sm:gap-0 ${left ? 'sm:flex-row' : 'sm:flex-row-reverse'}`}>
                    <div className="absolute left-5 sm:left-1/2 -translate-x-1/2 top-8 sm:top-9 w-10 h-10 rounded-full bg-amber-gradient text-white font-bold flex items-center justify-center shadow-lg shadow-amber-500/20 z-10 ring-4 ring-white dark:ring-qac-900">
                      {i + 1}
                    </div>
                    <div className={`w-full sm:w-1/2 pl-14 sm:pl-0 ${left ? 'sm:pr-14' : 'sm:pl-14'}`}>
                      <div className="card hover:shadow-xl hover:shadow-qac-900/5 transition-shadow">
                        <h3 className="font-semibold text-qac-900 dark:text-white mb-2">{reason.title}</h3>
                        <p className="text-sm text-qac-500 dark:text-qac-300">{reason.desc}</p>
                      </div>
                    </div>
                  </div>
                )
              })}
            </div>
          </div>
        </div>
      </section>
    </>
  )
}
