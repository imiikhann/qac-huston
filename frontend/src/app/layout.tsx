import type { Metadata } from 'next'
import './globals.css'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import ChatWidget from '@/components/ChatWidget'
import ThemeProvider from '@/components/ThemeProvider'
import PageTracker from '@/components/PageTracker'

export const metadata: Metadata = {
  title: {
    default: 'Quality Automotive Center - QAC Houston',
    template: '%s | QAC Houston',
  },
  description: 'Expert auto repair and service in Houston. Electrical, mechanical, and special services for domestic and foreign vehicles. Family-owned since 2015.',
  keywords: ['auto repair', 'car service', 'Houston', 'QAC', 'Quality Automotive Center', 'mechanical', 'electrical', 'fleet service'],
  openGraph: {
    title: 'Quality Automotive Center - QAC Houston',
    description: 'Expert auto repair and service in Houston.',
    url: 'https://qachouston.com',
    siteName: 'QAC Houston',
    locale: 'en_US',
    type: 'website',
  },
  robots: {
    index: true,
    follow: true,
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body className="min-h-screen flex flex-col">
        <ThemeProvider>
          <Header />
          <PageTracker />
          <main className="flex-1">{children}</main>
          <Footer />
          <ChatWidget />
        </ThemeProvider>
      </body>
    </html>
  )
}
