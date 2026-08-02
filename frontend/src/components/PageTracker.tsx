'use client'

import { usePathname } from 'next/navigation'
import { useEffect, useRef } from 'react'
import { api } from '@/lib/api'

export default function PageTracker() {
  const pathname = usePathname()
  const prevPath = useRef('')

  useEffect(() => {
    if (!pathname) return
    if (prevPath.current === '') {
      prevPath.current = pathname
      return
    }
    if (pathname !== prevPath.current) {
      prevPath.current = pathname
      api.track(pathname, document.referrer)
    }
  }, [pathname])

  return null
}
