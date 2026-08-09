'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import { api, type NavigationMenuItem, type SiteSetting } from '@/lib/api'
/* eslint-disable @next/next/no-img-element */

const defaultLinks = [
  { name: 'Home', href: '/' },
  { name: 'About Us', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Fleet', href: '/fleet' },
  { name: 'Pre-Purchase Inspection', href: '/pre-purchase-inspection' },
  { name: 'Vendors', href: '/vendors' },
  { name: 'Contact', href: '/contact' },
  { name: 'Why QAC?', href: '/why-qac' },
  { name: 'Reviews', href: '/reviews' },
  { name: 'FAQs', href: '/faqs' },
  { name: 'Clientele', href: '/clientele' },
]

export default function Footer() {
  const [settings, setSettings] = useState<SiteSetting | null>(null)

  useEffect(() => {
    api.cms.settings().then(d => setSettings(d.data)).catch(() => {})
  }, [])
  return (
    <footer className="bg-qac-900 dark:bg-black text-qac-200 relative overflow-hidden">
      {/* Subtle gradient overlay */}
      <div className="absolute inset-0 bg-gradient-to-b from-transparent via-amber-900/5 to-transparent pointer-events-none" />
      <div className="absolute top-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl" />
      <div className="absolute bottom-0 right-1/4 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl" />

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
          <div>
            <div className="mb-4">
              <img src="/qac.png" alt="QAC Houston" className="h-12 w-auto brightness-0 invert" />
            </div>
            <p className="text-qac-400 text-sm leading-relaxed">
              {settings?.footer_text || 'Two friends, one vision — bringing transparency, trust, and a human touch back to auto repair.'}
            </p>
          </div>

          <div>
            <h3 className="font-semibold text-white mb-4">Quick Links</h3>
            <ul className="space-y-2">
              {defaultLinks.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-qac-400 hover:text-amber-400 transition-colors">
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-semibold text-white mb-4">Contact</h3>
            <div className="space-y-3 text-sm glass-card-dark p-5">
              <div>
                <p className="text-qac-500 text-xs uppercase tracking-wider">Address</p>
                <p className="text-white">{settings?.address || '9934 Jones Rd B5, Houston, TX 77065'}</p>
              </div>
              <div>
                <p className="text-qac-500 text-xs uppercase tracking-wider">Phone</p>
                <a href={`tel:${settings?.contact_phone?.replace(/\D/g, '') || '+18328963865'}`} className="text-white hover:text-amber-400 transition-colors">
                  {settings?.contact_phone || '(832) 896-3865'}
                </a>
              </div>
              <div>
                <p className="text-qac-500 text-xs uppercase tracking-wider">Email</p>
                <a href={`mailto:${settings?.contact_email || 'contact@qachouston.com'}`} className="text-white hover:text-amber-400 transition-colors">
                  {settings?.contact_email || 'contact@qachouston.com'}
                </a>
              </div>
              <div>
                <p className="text-qac-500 text-xs uppercase tracking-wider">Hours</p>
                <p className="text-white">Mon-Thu: 9am-6pm</p>
                <p className="text-white">Fri-Sat: 9am-1pm</p>
                <p className="text-white">Sun: Closed</p>
              </div>
            </div>
          </div>
        </div>

        <div className="border-t border-qac-800 mt-10 pt-8 text-center text-sm text-qac-500">
          <p>&copy; {new Date().getFullYear()} Quality Automotive Center (QAC) Houston. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}
