'use client'

import { useState, useEffect } from 'react'
import { api, Service, ServiceCategory } from '@/lib/api'
import { WrenchIcon, BoltIcon, SparklesIcon } from '@heroicons/react/24/outline'

const categoryIcons: Record<string, any> = { electrical: BoltIcon, mechanical: WrenchIcon, special: SparklesIcon }

const defaultCategories: ServiceCategory[] = [
  { id: 1, name: 'Electrical', slug: 'electrical', is_active: true, sort_order: 1 },
  { id: 2, name: 'Mechanical', slug: 'mechanical', is_active: true, sort_order: 2 },
  { id: 3, name: 'Special', slug: 'special', is_active: true, sort_order: 3 },
]

export default function ServicesPage() {
  const [services, setServices] = useState<Service[]>([])
  const [categories, setCategories] = useState<ServiceCategory[]>(defaultCategories)
  const [activeCategory, setActiveCategory] = useState('electrical')

  useEffect(() => {
    api.services.list().then((res) => setServices(res.data)).catch(() => {})
    api.serviceCategories.list().then((res) => {
      if (res.data.length > 0) setCategories(res.data)
    }).catch(() => {})
  }, [])

  const filteredServices = services.filter((s) => s.category === activeCategory)

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Our Services</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300 max-w-2xl mx-auto">
            We specialize in electrical, mechanical, and special services on domestic and foreign vehicles, fleet trucks, motor homes, and food trucks.
          </p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-center gap-2 mb-10 flex-wrap">
            {categories.map((cat) => (
              <button
                key={cat.slug}
                onClick={() => setActiveCategory(cat.slug)}
                className={`px-6 py-3 rounded-lg font-medium text-sm capitalize transition-all ${
                  activeCategory === cat.slug
                    ? 'bg-qac-900 text-white shadow-lg'
                    : 'bg-qac-100 dark:bg-qac-800/60 text-qac-600 dark:text-qac-300 hover:bg-qac-200'
                }`}
              >
                {cat.name}
              </button>
            ))}
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredServices.map((service) => {
              const Icon = categoryIcons[service.category] || WrenchIcon
              return (
                <div key={service.id} className="card group hover:border-amber-200">
                  <div className="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-4 group-hover:bg-amber-100 transition-colors">
                    <Icon className="w-6 h-6 text-amber-600" />
                  </div>
                  <h3 className="font-semibold text-qac-900 dark:text-white text-lg mb-2">{service.name}</h3>
                  <p className="text-sm text-qac-500 dark:text-qac-300 mb-4">{service.description}</p>
                  {service.price_range && (
                    <span className="tag-amber text-xs">{service.price_range}</span>
                  )}
                </div>
              )
            })}
          </div>
        </div>
      </section>
    </>
  )
}
