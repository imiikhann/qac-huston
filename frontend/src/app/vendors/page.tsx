'use client'

import { useState, useEffect } from 'react'
import { api, Vendor } from '@/lib/api'

const vendorCategories = [
  { id: 'parts', label: 'Auto Parts' },
  { id: 'tools', label: 'Tools & Equipment' },
  { id: 'software', label: 'Software' },
  { id: 'supplies', label: 'Supplies' },
]

export default function VendorsPage() {
  const [vendors, setVendors] = useState<Vendor[]>([])
  const [activeCategory, setActiveCategory] = useState('parts')

  useEffect(() => {
    api.vendors.list().then((res) => setVendors(res.data)).catch(() => {})
  }, [])

  const filtered = vendors.filter((v) => v.category === activeCategory)

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Our Vendors</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300 max-w-2xl mx-auto">
            We partner with trusted vendors to deliver the highest quality service.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-center gap-2 mb-10">
            {vendorCategories.map((cat) => (
              <button
                key={cat.id}
                onClick={() => setActiveCategory(cat.id)}
                className={`px-5 py-2.5 rounded-lg font-medium text-sm transition-all ${
                  activeCategory === cat.id
                    ? 'bg-qac-900 text-white'
                    : 'bg-qac-100 dark:bg-qac-800/60 text-qac-600 dark:text-qac-300 hover:bg-qac-200'
                }`}
              >
                {cat.label}
              </button>
            ))}
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filtered.map((vendor) => (
              <div key={vendor.id} className="card p-6">
                <h3 className="font-semibold text-qac-900 dark:text-white text-lg mb-2">{vendor.name}</h3>
                <p className="text-sm text-qac-500 dark:text-qac-300 mb-4">{vendor.description}</p>
                {vendor.website && (
                  <a
                    href={vendor.website}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-sm text-amber-600 hover:text-amber-700 font-medium"
                  >
                    Visit Website &rarr;
                  </a>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>
    </>
  )
}
