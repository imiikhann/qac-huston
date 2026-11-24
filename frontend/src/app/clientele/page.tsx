'use client'

import { useState } from 'react'

const tabs = [
  { id: 'individual', label: 'Individual', content: 'We perform all types of scheduled maintenance including oil changes, brake checks, A/C services and more. Emergency repairs, such as alternator replacement or troubleshooting engine overheating are also available. We also provide multi-point Pre-Purchase Inspection service.' },
  { id: 'fleet', label: 'Fleet', content: 'We provide regular services to fleet companies involved in delivery services, logistics, and health services. Our high standards of quality and attention to detail help prevent breakdowns during deliveries, making us a trusted partner for fleet customers.' },
  { id: 'warranty', label: 'Warranty/Insurance', content: 'We provide repair services for customers whose vehicles are covered by auto insurance after accidents or damage.' },
  { id: 'commercial', label: 'Commercial', content: 'We service a variety of commercial clients, including buses, box trucks, food trucks, forklifts, and work vans (including 15-seaters). With the necessary tools and extensive experience, we deliver exceptional service tailored to the needs of our commercial customers.' },
  { id: 'luxury', label: 'Luxury', content: 'We offer specialized repairs for premium brands, including Alfa Romeo, BMW, Ferrari, Maserati, Mercedes, Porsche, Range Rover, and Volvo. We have extensive experience with custom modifications and high-performance tuning to keep your luxury vehicle in top condition.' },
  { id: 'diy', label: 'DIY Enthusiasts', content: 'QAC is fully equipped to assist DIY enthusiasts with tasks such as key fob programming, initialization, calibration, and battery registration. We also provide engine diagnostics using proprietary equipment for more advanced repairs.' },
  { id: 'walkin', label: 'Walk-In', content: 'Although we primarily work by appointment, we do welcome walk-in customers. We aim to do whatever we can to help.' },
  { id: 'repeat', label: 'Repeat/Loyal', content: 'Over 70% of our customers are loyal and regular clients who trust QAC for all their vehicle needs. They appreciate the unmatched service quality, competitive pricing, and attention to detail.' },
  { id: 'referral', label: 'Referral', content: 'We proudly welcome new clients referred by our loyal customers. At Quality Automotive Center, we take pride in delivering a 5-star experience to every customer.' },
  { id: 'specialized', label: 'Specialized', content: 'We specialize in precision calibration for Advanced Driver Assistance Systems (ADAS), ensuring optimal safety and performance. We also offer electric and hybrid vehicle repairs, and multi-point Pre-Purchase Inspection services.' },
]

export default function ClientelePage() {
  const [activeTab, setActiveTab] = useState('individual')

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Our Clientele</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300">We serve everyone from individual owners to large fleets.</p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-wrap justify-center gap-2 mb-10">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`px-4 sm:px-6 lg:px-8 py-2 rounded-lg font-medium text-sm transition-all ${
                  activeTab === tab.id ? 'bg-qac-900 text-white' : 'bg-qac-100 dark:bg-qac-800/60 text-qac-600 dark:text-qac-300 hover:bg-qac-200'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>

          <div className="bg-white dark:bg-qac-900/60 rounded-2xl p-8 border border-qac-100 dark:border-qac-800 shadow-sm">
            <p className="text-qac-700 dark:text-qac-200 leading-relaxed text-lg">
              {tabs.find((t) => t.id === activeTab)?.content}
            </p>
          </div>
        </div>
      </section>
    </>
  )
}
