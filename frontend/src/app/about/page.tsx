import type { Metadata } from 'next'
import Link from 'next/link'

export const metadata: Metadata = {
  title: 'About Us',
  description: 'Two friends, one vision — bringing transparency, trust, and a human touch back to auto repair at Quality Automotive Center in Houston.',
}

export default function AboutPage() {
  return (
    <>
      <section className="bg-qac-50 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 mb-4">Two Friends. One Vision.</h1>
          <p className="text-xl text-qac-500 max-w-2xl mx-auto">
            From the moment we met, we recognized our shared passion for cars and community.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
          <div className="grid md:grid-cols-2 gap-8 items-center">
            <div>
              <h2 className="text-2xl font-bold text-qac-900 mb-4">Trust Through Transparency</h2>
              <p className="text-qac-600 leading-relaxed">
                At Quality Automotive Center, trust isn&apos;t assumed — it&apos;s earned. Every interaction is rooted in
                transparency. We explain repairs in clear, straightforward terms and provide precise risk assessments
                with every recommendation. No surprises. No inflated costs.
              </p>
            </div>
            <div className="bg-qac-100 rounded-2xl p-8">
              <p className="text-qac-700 text-lg italic">
                &ldquo;We don&apos;t upsell unnecessary services — just the essential repairs to keep you safe on the road.&rdquo;
              </p>
            </div>
          </div>

          <div className="grid md:grid-cols-2 gap-8 items-center">
            <div className="order-2 md:order-1 bg-amber-50 rounded-2xl p-8">
              <p className="text-amber-800 text-lg italic">
                &ldquo;We&apos;re not just mechanics — we&apos;re your neighbors, friends, and fellow community members.&rdquo;
              </p>
            </div>
            <div className="order-1 md:order-2">
              <h2 className="text-2xl font-bold text-qac-900 mb-4">More Than a Repair Shop</h2>
              <p className="text-qac-600 leading-relaxed">
                Our business isn&apos;t built on transactions but on relationships. We don&apos;t just fix cars; we support the
                people behind the wheel. Honesty is at the core of everything we do, and our commitment to the
                community runs deep.
              </p>
            </div>
          </div>

          <div className="bg-qac-900 rounded-2xl p-8 md:p-12 text-center">
            <h2 className="text-2xl font-bold text-white mb-4">Driven by Passion, Built on Trust</h2>
            <p className="text-qac-300 max-w-2xl mx-auto mb-8">
              Quality Automotive Center is more than a business — it&apos;s the realization of a lifelong dream. One repair
              shop. Countless connections.
            </p>
            <Link href="/book" className="btn-primary">
              Book an Appointment
            </Link>
          </div>
        </div>
      </section>
    </>
  )
}
