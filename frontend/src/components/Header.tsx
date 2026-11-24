'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import { Bars3Icon, XMarkIcon, SunIcon, MoonIcon } from '@heroicons/react/24/outline'
import { useTheme } from '@/components/ThemeProvider'
import { api, type NavigationMenuItem } from '@/lib/api'
/* eslint-disable @next/next/no-img-element */

export default function Header() {
  const [mobileOpen, setMobileOpen] = useState(false)
  const { theme, toggleTheme } = useTheme()
  const [navItems, setNavItems] = useState<NavigationMenuItem[]>([])

  useEffect(() => {
    api.cms.navigation('header').then(d => setNavItems(d.data))
  }, [])

  return (
    <header className="sticky top-0 z-50 glass-card rounded-none border-x-0 border-t-0">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 lg:h-20">
          <Link href="/" className="flex items-center group">
            <img src="/qac-logo.png" alt="QAC Houston" className="h-12 w-auto" />
          </Link>

          <div className="flex items-center gap-2">
            <nav className="hidden lg:flex items-center gap-1">
              {navItems.map((item) => (
                <Link
                  key={item.url}
                  href={item.url ?? '/'}
                  className="px-3 py-2 text-sm font-medium text-qac-600 dark:text-qac-300 hover:text-qac-900 dark:hover:text-white hover:bg-qac-50 dark:hover:bg-white/5 rounded-lg transition-all"
                >
                  {item.label}
                </Link>
              ))}
              <Link href="/book" className="ml-4 btn-primary text-sm !px-5 !py-2.5">
                Book Now
              </Link>
            </nav>

            <button
              onClick={toggleTheme}
              className="p-2.5 rounded-xl text-qac-500 dark:text-qac-300 hover:bg-qac-100 dark:hover:bg-white/10 transition-all"
              aria-label="Toggle dark mode"
            >
              {theme === 'dark' ? <SunIcon className="w-5 h-5" /> : <MoonIcon className="w-5 h-5" />}
            </button>

            <button
              onClick={() => setMobileOpen(!mobileOpen)}
              className="lg:hidden p-2.5 rounded-xl text-qac-500 dark:text-qac-300 hover:bg-qac-100 dark:hover:bg-white/10"
            >
              {mobileOpen ? <XMarkIcon className="w-5 h-5" /> : <Bars3Icon className="w-5 h-5" />}
            </button>
          </div>
        </div>
      </div>

      {mobileOpen && (
        <div className="lg:hidden border-t border-qac-100 dark:border-white/10 bg-white/95 dark:bg-qac-900/95 backdrop-blur-xl">
          <div className="px-4 py-3 space-y-1">
            {navItems.map((item) => (
              <Link
                key={item.url}
                href={item.url ?? '/'}
                onClick={() => setMobileOpen(false)}
                className="block px-3 py-2.5 text-sm font-medium text-qac-600 dark:text-qac-300 hover:text-qac-900 dark:hover:text-white hover:bg-qac-50 dark:hover:bg-white/5 rounded-lg"
              >
                {item.label}
              </Link>
            ))}
            <Link
              href="/book"
              onClick={() => setMobileOpen(false)}
              className="block mt-3 btn-primary text-sm text-center"
            >
              Book Now
            </Link>
          </div>
        </div>
      )}
    </header>
  )
}
