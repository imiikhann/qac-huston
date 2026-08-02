import type { Metadata } from 'next'
import Link from 'next/link'

export const metadata: Metadata = {
  title: 'Fleet Services',
  description: 'QAC Houston provides fleet maintenance services for box trucks, fleet vans, food trucks, and more.',
}

const fleets = [
  { name: 'Fleet Trucks', desc: 'Box trucks, refrigerated & non-refrigerated' },
  { name: 'Food Trucks', desc: 'Full service for mobile food operations' },
  { name: 'Pickup Trucks', desc: 'Maintenance and repairs for work pickups' },
  { name: 'Motorhomes', desc: 'RV and motorhome service & repair' },
  { name: 'Fleet Vans', desc: 'Cargo and passenger van maintenance' },
]

export default function FleetPage() {
  return (
    <>
      <section className="bg-qac-50 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 mb-4">Fleet Services</h1>
          <p className="text-xl text-qac-500 max-w-2xl mx-auto">
            We proudly serve a variety of fleet clients with priority service and custom maintenance programs.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {fleets.map((fleet) => (
              <div key={fleet.name} className="card text-center">
                <div className="w-16 h-16 bg-qac-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-qac-500">{fleet.name.charAt(0)}</span>
                </div>
                <h3 className="font-semibold text-qac-900 text-lg mb-2">{fleet.name}</h3>
                <p className="text-sm text-qac-500">{fleet.desc}</p>
              </div>
            ))}
          </div>

          <div className="mt-16 bg-qac-900 rounded-2xl p-8 md:p-12 text-center">
            <h2 className="text-2xl font-bold text-white mb-4">Need Fleet Service?</h2>
            <p className="text-qac-300 mb-8 max-w-xl mx-auto">
              Contact us for custom fleet pricing and priority scheduling.
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Link href="/book" className="btn-primary">Book Fleet Service</Link>
              <Link href="/contact" className="btn-secondary bg-white text-qac-900 hover:bg-qac-100">
                Contact Us
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}
