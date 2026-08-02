import type { Metadata } from 'next'
import Link from 'next/link'

export const metadata: Metadata = {
  title: 'Pre-Purchase Inspection',
  description: 'Professional multi-point Pre-Purchase Inspection (PPI) service in Houston. Basic, Standard, and Comprehensive tiers available.',
}

const tiers = [
  { name: 'Basic', price: '$99', points: 25, desc: 'Ideal for quick evaluations of key systems and overall condition.' },
  { name: 'Standard', price: '$199', points: 50, desc: 'Balanced assessment of the vehicle\'s primary and secondary systems.', popular: true },
  { name: 'Comprehensive', price: '$299', points: 100, desc: 'Deep dive into all major and minor systems for maximum assurance.' },
]

const categories = [
  { name: 'Body', items: 'Exterior condition, paintwork, structural integrity, and signs of previous repairs' },
  { name: 'Chassis', items: 'Suspension, alignment, tires, and undercarriage condition' },
  { name: 'Electrical', items: 'Battery health, wiring, lighting, and electronic systems including DTCs' },
  { name: 'Mechanical', items: 'Engine, transmission, brakes, fuel system, and drivetrain performance' },
]

export default function PPIPage() {
  return (
    <>
      <section className="bg-qac-50 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 mb-4">Pre-Purchase Inspection</h1>
          <p className="text-xl text-qac-500 max-w-2xl mx-auto">
            Professional multi-point inspection to give you confidence when buying a used vehicle.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid sm:grid-cols-3 gap-6 mb-16">
            {tiers.map((tier) => (
              <div key={tier.name} className={`card text-center relative ${tier.popular ? 'border-amber-500 ring-2 ring-amber-500' : ''}`}>
                {tier.popular && (
                  <span className="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                    Recommended
                  </span>
                )}
                <h3 className="text-xl font-bold text-qac-900 mb-2">{tier.name}</h3>
                <p className="text-3xl font-bold text-amber-500 mb-4">{tier.price}</p>
                <p className="text-sm text-qac-500 mb-4">{tier.desc}</p>
                <p className="text-qac-600 font-semibold">{tier.points} Inspection Points</p>
              </div>
            ))}
          </div>

          <h2 className="text-2xl font-bold text-qac-900 text-center mb-8">What&apos;s Included</h2>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {categories.map((cat) => (
              <div key={cat.name} className="card">
                <h3 className="font-semibold text-qac-900 mb-2">{cat.name}</h3>
                <p className="text-sm text-qac-500">{cat.items}</p>
              </div>
            ))}
          </div>

          <div className="mt-12 bg-amber-50 rounded-2xl p-8 md:p-12 text-center">
            <h2 className="text-2xl font-bold text-qac-900 mb-4">Schedule Your PPI Today</h2>
            <p className="text-qac-600 mb-8 max-w-xl mx-auto">
              We recommend asking the seller to bring the vehicle to QAC for an unbiased, professional evaluation.
            </p>
            <Link href="/book" className="btn-primary">Schedule Inspection</Link>
          </div>
        </div>
      </section>
    </>
  )
}
