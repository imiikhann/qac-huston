'use client'

import { useState, useEffect, useRef } from 'react'
import Link from 'next/link'
/* eslint-disable @next/next/no-img-element */
import { api, Service, Review, Section, MembershipProgram, ServiceCategory, FAQ } from '@/lib/api'
import {
  WrenchIcon, BoltIcon, SparklesIcon, StarIcon, ChevronRightIcon,
  ArrowRightIcon, PhoneIcon, ClockIcon, MapPinIcon, ShieldCheckIcon,
  UsersIcon, TrophyIcon, HeartIcon, TruckIcon,
  Battery100Icon, CpuChipIcon, KeyIcon, LightBulbIcon, SunIcon,
  AdjustmentsHorizontalIcon, Cog6ToothIcon, VariableIcon,
  FireIcon, ExclamationTriangleIcon, DocumentMagnifyingGlassIcon,
  BeakerIcon, TruckIcon as TruckSolid, ArrowsRightLeftIcon,
  ArrowPathIcon, SignalIcon, PaintBrushIcon, WrenchScrewdriverIcon,
  SparklesIcon as SparklesSolid, CircleStackIcon, ChevronDownIcon
} from '@heroicons/react/24/outline'

const serviceIcons: Record<string, any> = {
  'Electrical Diagnostics': BoltIcon,
  'Alternator & Battery': Battery100Icon,
  'Calibrations': AdjustmentsHorizontalIcon,
  'Electric Parking Brakes': ShieldCheckIcon,
  'Key Fob Programming': KeyIcon,
  'Ignition System': SparklesIcon,
  'Interior Lights': LightBulbIcon,
  'Head & Tail Lights': SunIcon,
  'Axle Shaft': Cog6ToothIcon,
  'Brakes': ExclamationTriangleIcon,
  'Catalytic Converters': BeakerIcon,
  'CV Axles': VariableIcon,
  'Engine Swaps': WrenchIcon,
  'Filters': WrenchScrewdriverIcon,
  'Fuel Injection Systems': FireIcon,
  'Engine Tune-Ups': SparklesSolid,
  'Engine Mounts': Cog6ToothIcon,
  'Oil Changes': BeakerIcon,
  'Heater Core & Evaporator': SunIcon,
  'Oxygen Sensors': CpuChipIcon,
  'Radiator': CircleStackIcon,
  'Suspension': AdjustmentsHorizontalIcon,
  'Timing Belt/Chain': ArrowPathIcon,
  'U-Joints': ArrowsRightLeftIcon,
  'Water Pump': CircleStackIcon,
  'Transfer Case Services & Swaps': TruckSolid,
  'Transmission Services & Swaps': Cog6ToothIcon,
  'Differential Services & Swaps': Cog6ToothIcon,
  'A/C System Recharge': SparklesIcon,
  'Mechanical Diagnostics': DocumentMagnifyingGlassIcon,
  'Recalibration': AdjustmentsHorizontalIcon,
  'Relearning': ArrowPathIcon,
  'Reprogramming': CpuChipIcon,
  'Battery Registration': Battery100Icon,
  'Transmission Adaptation': ArrowsRightLeftIcon,
  'Wheel Alignments': ArrowPathIcon,
  'A/C & Heater Repairs & Services': SunIcon,
  'Advanced Driver Assist Systems (ADAS)': SignalIcon,
  'Multi-Point Pre-Purchase Inspection': DocumentMagnifyingGlassIcon,
  'State Inspection including Emissions': DocumentMagnifyingGlassIcon,
}

const defaultClienteleTabs = [
  { id: 'individual', label: 'Individual', content: 'We perform all types of scheduled maintenance including oil changes, brake checks, A/C services and more. Emergency repairs, such as alternator replacement or troubleshooting engine overheating are also available. We also provide multi-point Pre-Purchase Inspection service.', icon: UsersIcon },
  { id: 'fleet', label: 'Fleet', content: 'We provide regular services to fleet companies involved in delivery services, logistics, and health services. Our high standards of quality and attention to detail help prevent breakdowns during deliveries.', icon: TruckIcon },
  { id: 'commercial', label: 'Commercial', content: 'We service a variety of commercial clients, including buses, box trucks, food trucks, forklifts, and work vans (including 15-seaters).', icon: ShieldCheckIcon },
  { id: 'luxury', label: 'Luxury', content: 'We offer specialized repairs for premium brands, including Alfa Romeo, BMW, Ferrari, Maserati, Mercedes, Porsche, Range Rover, and Volvo.', icon: TrophyIcon },
  { id: 'walkin', label: 'Walk-In', content: 'Although we primarily work by appointment, we do welcome walk-in customers. We aim to do whatever we can to help.', icon: HeartIcon },
]

const defaultCategories: ServiceCategory[] = [
  { id: 1, name: 'Electrical', slug: 'electrical', is_active: true, sort_order: 1 },
  { id: 2, name: 'Mechanical', slug: 'mechanical', is_active: true, sort_order: 2 },
  { id: 3, name: 'Special', slug: 'special', is_active: true, sort_order: 3 },
]

export default function HomePage() {
  const [services, setServices] = useState<Service[]>([])
  const [reviews, setReviews] = useState<Review[]>([])
  const [heroBanners, setHeroBanners] = useState<any[]>([])
  const [sections, setSections] = useState<Section[]>([])
  const [membershipPrograms, setMembershipPrograms] = useState<MembershipProgram[]>([])
  const [faqs, setFaqs] = useState<FAQ[]>([])
  const [openFaqId, setOpenFaqId] = useState<number | null>(null)
  const [categories, setCategories] = useState<ServiceCategory[]>(defaultCategories)
  const [activeCategory, setActiveCategory] = useState('electrical')
  const [activeClientele, setActiveClientele] = useState('individual')
  const [activeIndex, setActiveIndex] = useState(0)
  const [reviewIndex, setReviewIndex] = useState(0)
  const [visibleSections, setVisibleSections] = useState<Set<string>>(new Set())
  const sectionRefs = useRef<Map<string, HTMLElement>>(new Map())

  useEffect(() => {
    api.services.list().then((res) => setServices(res.data)).catch(() => {})
    api.serviceCategories.list().then((res) => {
      if (res.data.length > 0) setCategories(res.data)
    }).catch(() => {})
    api.reviews.list().then((res) => setReviews(res.data.filter(r => r.is_approved).slice(0, 6))).catch(() => {})
    api.cms.heroBanners().then((res) => setHeroBanners(res.data)).catch(() => {})
    api.cms.sections().then((res) => setSections(res.data)).catch(() => {})
    api.cms.membershipPrograms().then((res) => setMembershipPrograms(res.data)).catch(() => {})
    api.faqs.list().then((res) => setFaqs(res.data)).catch(() => {})
  }, [])

  const byType = (type: string) => sections.find(s => s.type === type)

  const heroSlides = heroBanners.length > 0
    ? heroBanners.map(b => ({
        bg: b.image || 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=1600&q=80',
        title: b.title,
        subtitle: b.subtitle,
        cta_text: b.cta_text,
        cta_link: b.cta_link,
      }))
    : [
        { bg: 'https://qachouston.com/wp-content/uploads/2025/02/456B13C3-C5BF-448D-BA70-2A633D929A0B.jpg', title: 'Over 10 Years Of Quality Auto Service', subtitle: 'Service', cta_text: 'BOOK NOW', cta_link: '/book' },
        { bg: 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_2.jpg', title: 'Over 10 Years Of Quality Auto Service', subtitle: 'Service', cta_text: 'BOOK NOW', cta_link: '/book' },
        { bg: 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_4.jpg', title: 'Over 10 Years Of Quality Auto Service', subtitle: 'Service', cta_text: 'BOOK NOW', cta_link: '/book' },
        { bg: 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_5.jpg', title: 'Over 10 Years Of Quality Auto Service', subtitle: 'Service', cta_text: 'BOOK NOW', cta_link: '/book' },
        { bg: 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_6.jpg', title: 'Over 10 Years Of Quality Auto Service', subtitle: 'Service', cta_text: 'BOOK NOW', cta_link: '/book' },
      ]

  const statsSection = byType('stats')
  const stats = statsSection?.content?.items ?? [
    { label: 'Years Experience', value: '10+' },
    { label: 'Happy Customers', value: '500+' },
    { label: 'Google Rating', value: '5.0' },
    { label: 'On-Time Service', value: '100%' },
  ]

  const servicesSection = byType('services')
  const clienteleSection = byType('clientele')
  const clienteleTabs = (clienteleSection?.content?.tabs && Array.isArray(clienteleSection.content.tabs) && clienteleSection.content.tabs.length > 0)
    ? clienteleSection.content.tabs
    : defaultClienteleTabs
  const activeClienteleTab = clienteleTabs.find((t) => t.id === activeClientele) ?? clienteleTabs[0]
  const clienteleCardTitle = clienteleSection?.content?.card_title || 'Join Our Family'
  const clienteleCardText = clienteleSection?.content?.card_text || 'Over 70% of our customers are loyal regulars'
  const membershipSection = byType('membership')
  const reviewsSection = byType('reviews')
  const ctaSection = byType('cta')
  const imageBreaks = sections.filter(s => s.type === 'image_break')

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            setVisibleSections(prev => new Set(prev).add(entry.target.id))
          }
        })
      },
      { threshold: 0.1 }
    )
    sectionRefs.current.forEach(ref => observer.observe(ref))
    return () => observer.disconnect()
  }, [])

  useEffect(() => {
    const timer = setInterval(() => {
      setActiveIndex(i => (i + 1) % heroSlides.length)
    }, 5000)
    return () => clearInterval(timer)
  }, [heroSlides.length])

  useEffect(() => {
    const total = reviews.length + 1
    const timer = setInterval(() => {
      setReviewIndex(i => (i + 1) % Math.max(1, total))
    }, 5000)
    return () => clearInterval(timer)
  }, [reviews.length])

  useEffect(() => {
    setReviewIndex(i => Math.min(i, Math.max(0, reviews.length)))
  }, [reviews.length])

  useEffect(() => {
    setActiveIndex(i => Math.min(i, heroSlides.length - 1))
  }, [heroSlides.length])

  const setSectionRef = (id: string) => (el: HTMLElement | null) => {
    if (el) sectionRefs.current.set(id, el)
  }

  const allServices = services.filter((s) => s.category === activeCategory)

  const schema = {
    '@context': 'https://schema.org',
    '@type': 'AutoRepair',
    name: 'Quality Automotive Center (QAC) Houston',
    image: 'https://qachouston.com/wp-content/uploads/2025/01/QAC-Logo-Web-PNG.png',
    address: { '@type': 'PostalAddress', streetAddress: '9934 Jones Rd B5', addressLocality: 'Houston', addressRegion: 'TX', postalCode: '77065', addressCountry: 'US' },
    telephone: '(832) 896-3865',
    email: 'contact@qachouston.com',
    openingHours: ['Mo-Th 09:00-18:00', 'Fr-Sa 09:00-13:00'],
    priceRange: '$$',
    aggregateRating: { '@type': 'AggregateRating', ratingValue: '5.0', reviewCount: '10' },
  }

  const programs = membershipPrograms.length > 0 ? membershipPrograms : [
    { name: 'Preventive Maintenance', price: 'Free*', features: ['Buy 2, Get 1 Free Oil Change', 'Discounted Diagnostics', 'Earn Loyalty Points'], is_popular: false, button_text: 'Enroll Now', button_link: '/contact' },
    { name: 'Discounts + Rewards', price: '$99/yr', features: ['10-20% Off Labor and Parts', 'Free Diagnostics', 'Earn Loyalty Points'], is_popular: true, button_text: 'Enroll Now', button_link: '/contact' },
    { name: 'Fleet Maintenance', price: 'Free*', features: ['Special Rebates', 'Priority Service', 'Flexible Appointments'], is_popular: false, button_text: 'Enroll Now', button_link: '/contact' },
    { name: 'VIP Program', price: '$429/yr', features: ['Complimentary Services', 'Premium Appointments', 'Free Diagnostics', 'Free Pick & Drop'], is_popular: false, button_text: 'Enroll Now', button_link: '/contact' },
  ]

  const ctaContent = ctaSection?.content || {}
  const ctaTitle = ctaSection?.title || 'Ready to Service Your Vehicle?'
  const ctaSubtitle = ctaSection?.subtitle || 'Book an appointment today and experience the QAC difference. Family-owned and trusted since 2015.'
  const ctaPhone = ctaContent?.phone || '(832) 896-3865'

  return (
    <>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(schema) }} />

      {/* Hero - Full screen carousel */}
      <section className="relative h-[90vh] min-h-[600px] overflow-hidden">
        {heroSlides.map((slide, i) => (
          <div
            key={i}
            className={`absolute inset-0 transition-all duration-1000 ease-out ${
              i === activeIndex ? 'opacity-100 scale-100' : 'opacity-0 scale-105'
            }`}
          >
            <img
              src={slide.bg}
              alt=""
              className="absolute inset-0 w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-gradient-to-r from-qac-900/90 via-qac-900/70 to-transparent" />
          </div>
        ))}
        <div className="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
          <div className="max-w-2xl">
            <h1 className="text-5xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
              {heroSlides[activeIndex]?.title || 'Quality Auto Service'}{' '}
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-300">You Can Trust</span>
            </h1>
            <div className="flex flex-wrap gap-4">
              <Link href={heroSlides[activeIndex]?.cta_link || '/book'} className="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-qac-900 dark:text-white bg-amber-gradient shadow-xl shadow-amber-500/30 hover:shadow-2xl hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 text-lg">
                {heroSlides[activeIndex]?.cta_text || 'Book Appointment'}
                <ArrowRightIcon className="w-5 h-5" />
              </Link>
              <a href="tel:+18328963865" className="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md hover:bg-white/20 hover:border-white/50 transition-all duration-200 text-lg">
                <PhoneIcon className="w-5 h-5" />
                (832) 896-3865
              </a>
            </div>
          </div>
        </div>
        <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
          {heroSlides.map((_, i) => (
            <button key={i} onClick={() => setActiveIndex(i)} className={`w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === activeIndex ? 'bg-amber-400 w-8' : 'bg-white/40 hover:bg-white/60'}`} />
          ))}
        </div>
      </section>

      {/* Stats Bar */}
      <section className="relative -mt-16 z-10 max-w-6xl mx-auto px-4">
        <div className="glass-card divide-x divide-qac-100 dark:divide-white/10 grid grid-cols-2 md:grid-cols-4 overflow-hidden">
          {stats.map((stat: { value: string; label: string }) => (
            <div key={stat.label} className="p-6 text-center">
              <p className="text-3xl font-bold text-transparent bg-clip-text bg-amber-gradient">{stat.value}</p>
              <p className="text-sm text-qac-500 dark:text-qac-300 mt-1">{stat.label}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Services */}
      <section id="services" ref={setSectionRef('services')} className="py-28 section-gradient">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className={`text-center mb-16 transition-all duration-700 ${visibleSections.has('services') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
            {servicesSection?.content?.badge && (
              <span className="inline-block px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-medium mb-4">{servicesSection.content.badge}</span>
            )}
            <h2 className="section-title">{servicesSection?.title || 'Our Services'}</h2>
            <p className="section-subtitle">{servicesSection?.subtitle || 'We specialize in electrical, mechanical, and special services on all vehicles.'}</p>
          </div>

          <div className="flex justify-center gap-2 mb-12 flex-wrap">
            {categories.map((cat) => (
              <button
                key={cat.slug}
                onClick={() => setActiveCategory(cat.slug)}
                className={`px-6 py-3 rounded-xl font-medium text-sm capitalize transition-all duration-300 ${
                  activeCategory === cat.slug
                    ? 'bg-qac-900 dark:bg-white text-white dark:text-qac-900 shadow-lg shadow-qac-900/20'
                    : 'glass-card text-qac-600 dark:text-qac-300 hover:bg-white/90 dark:hover:bg-qac-800/50'
                }`}
              >
                {cat.name} Services
              </button>
            ))}
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {allServices.map((service, i) => {
              const Icon = serviceIcons[service.name] || WrenchIcon
              return (
                <div key={service.id} className="glass-card-hover group p-6" style={{ animationDelay: `${i * 80}ms` }}>
                  <div className="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-amber-200/50 dark:shadow-amber-900/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <Icon className="w-7 h-7 text-white" />
                  </div>
                  <h3 className="font-semibold text-qac-900 dark:text-white text-lg mb-2">{service.name}</h3>
                  <p className="text-sm text-qac-500 dark:text-qac-300 mb-4 line-clamp-2 leading-relaxed">{service.description}</p>
                  {service.price_range && (
                    <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                      {service.price_range}
                    </span>
                  )}
                  <div className="absolute inset-0 rounded-2xl bg-gradient-to-br from-transparent via-transparent to-amber-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none" />
                </div>
              )
            })}
          </div>

          <div className="text-center mt-12">
            <Link href="/services" className="inline-flex items-center gap-2 btn-outline group">
              View All Services
              <ArrowRightIcon className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>
        </div>
      </section>

      {/* Image Breaks */}
      {imageBreaks.map((imgBrk, idx) => {
        const imgContent = imgBrk.content || {}
        return (
          <section key={imgBrk.id} className={`relative h-[50vh] min-h-[400px] overflow-hidden ${idx > 0 ? '' : ''}`}>
            <img
              src={imgBrk.image || 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=1600&q=80'}
              alt=""
              className="absolute inset-0 w-full h-full object-cover"
            />
            {!imgContent.hide_overlay && (
              <div className={`absolute inset-0 ${imgContent.overlay === 'left' ? 'bg-gradient-to-r' : 'bg-gradient-to-l'} from-qac-900/80 to-qac-900/40`} />
            )}
            {!imgContent.hide_content && (
              <div className={`relative h-full max-w-7xl mx-auto px-4 flex items-center ${imgContent.align === 'right' ? 'justify-end' : ''}`}>
                <div className={imgContent.align === 'right' ? 'max-w-xl text-right' : 'max-w-xl'}>
                  <h2 className="text-4xl font-bold text-white mb-4">{imgBrk.title || 'Precision Service for Every Vehicle'}</h2>
                  <p className="text-white/90 text-lg mb-6">{imgBrk.subtitle || 'From luxury imports to heavy-duty fleet trucks, our certified technicians deliver exceptional care.'}</p>
                  {imgContent.button_text && (
                    <Link href={imgContent.button_link || '/services'} className="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white dark:bg-qac-900/60 text-qac-900 dark:text-white hover:bg-amber-50 transition-all duration-200">
                      {imgContent.button_text}
                      <ArrowRightIcon className="w-4 h-4" />
                    </Link>
                  )}
                </div>
              </div>
            )}
          </section>
        )
      })}

      {/* Clientele */}
      <section id="clientele" ref={setSectionRef('clientele')} className="py-28 hero-gradient-bg">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className={`text-center mb-16 transition-all duration-700 ${visibleSections.has('clientele') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
            {clienteleSection?.content?.badge && (
              <span className="inline-block px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-medium mb-4">{clienteleSection.content.badge}</span>
            )}
            <h2 className="section-title">{clienteleSection?.title || 'Clientele'}</h2>
            <p className="section-subtitle">{clienteleSection?.subtitle || 'We serve a diverse range of customers with excellence.'}</p>
          </div>

          <div className="flex flex-wrap justify-center gap-2 mb-10">
            {clienteleTabs.map((tab) => {
              const TabIcon = (tab as any).icon || UsersIcon
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveClientele(tab.id)}
                  className={`inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm transition-all duration-300 ${
                    activeClientele === tab.id
                      ? 'bg-amber-gradient text-white shadow-lg shadow-amber-500/20'
                      : 'glass-card text-qac-600 dark:text-qac-300 hover:bg-white/90 dark:hover:bg-qac-800/50'
                  }`}
                >
                  <TabIcon className="w-4 h-4" />
                  {tab.label}
                </button>
              )
            })}
          </div>

          <div className="max-w-4xl mx-auto grid md:grid-cols-5 gap-4">
            <div className="md:col-span-3 glass-card p-8 md:p-10">
              <span className="text-6xl text-amber-300/30 dark:text-amber-500/30 font-serif leading-none">&ldquo;</span>
              <p className="text-qac-700 dark:text-qac-300 leading-relaxed text-lg relative -mt-4">
                {activeClienteleTab?.content}
              </p>
            </div>
            <div className="md:col-span-2 glass-card p-6 flex flex-col items-center justify-center text-center">
              <div className="w-16 h-16 bg-amber-gradient rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <UsersIcon className="w-8 h-8 text-white" />
              </div>
              <h3 className="font-semibold text-qac-900 dark:text-white mb-1">{clienteleCardTitle}</h3>
              <p className="text-sm text-qac-500 dark:text-qac-300">{clienteleCardText}</p>
            </div>
          </div>
        </div>
      </section>

      {/* Membership Programs */}
      <section id="membership" ref={setSectionRef('membership')} className="py-28 section-gradient">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className={`text-center mb-16 transition-all duration-700 ${visibleSections.has('membership') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
            {membershipSection?.content?.badge && (
              <span className="inline-block px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-medium mb-4">{membershipSection.content.badge}</span>
            )}
            <h2 className="section-title">{membershipSection?.title || 'Membership Programs'}</h2>
            <p className="section-subtitle">{membershipSection?.subtitle || 'Exclusive benefits for our loyal customers.'}</p>
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {(programs as any[]).map((tier: any, i: number) => {
              const features = Array.isArray(tier.features)
                ? tier.features.map((f: any) => typeof f === 'string' ? f : (f.feature || ''))
                : []
              return (
                <div
                  key={tier.name || i}
                  className={`glass-card-hover p-6 relative text-center flex flex-col ${tier.is_popular ? 'border-amber-400 shadow-xl shadow-amber-500/10 ring-1 ring-amber-400/50' : ''}`}
                  style={{ animationDelay: `${i * 100}ms` }}
                >
                  {tier.is_popular && (
                    <span className="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-amber-gradient text-white text-xs font-semibold rounded-full shadow-lg whitespace-nowrap">
                      Most Popular
                    </span>
                  )}
                  <h3 className="font-semibold text-qac-900 dark:text-white text-lg mb-3">{tier.name}</h3>
                  <p className="text-4xl font-bold text-transparent bg-clip-text bg-amber-gradient mb-6">{tier.price}</p>
                  <ul className="space-y-3 text-left flex-1">
                    {features.map((f: string, j: number) => (
                      <li key={j} className="text-sm text-qac-600 dark:text-qac-400 flex items-start gap-3">
                        <span className="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0 mt-0.5">
                          <ChevronRightIcon className="w-3 h-3 text-amber-600 dark:text-amber-400" />
                        </span>
                        {f}
                      </li>
                    ))}
                  </ul>
                  <Link
                    href={tier.id ? `/enroll?program=${tier.id}` : (tier.button_link || '/contact')}
                    className="mt-6 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm bg-amber-gradient text-white shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200"
                  >
                    {tier.button_text || 'Enroll Now'}
                    <ArrowRightIcon className="w-4 h-4" />
                  </Link>
                </div>
              )
            })}
          </div>
        </div>
      </section>

      {/* Reviews */}
      <section id="reviews" ref={setSectionRef('reviews')} className="py-28 hero-gradient-bg">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className={`text-center mb-16 transition-all duration-700 ${visibleSections.has('reviews') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
            {reviewsSection?.content?.badge && (
              <span className="inline-block px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-medium mb-4">{reviewsSection.content.badge}</span>
            )}
            <h2 className="section-title">{reviewsSection?.title || 'What Our Customers Say'}</h2>
            <p className="section-subtitle">{reviewsSection?.subtitle || ''}</p>
            <div className="flex items-center justify-center gap-3 mt-4">
              <div className="flex items-center gap-0.5">
                {Array.from({ length: 5 }).map((_, i) => (
                  <StarIcon key={i} className="w-6 h-6 text-amber-400 fill-amber-400" />
                ))}
              </div>
              <span className="text-3xl font-bold text-qac-900 dark:text-white">5.0</span>
              <span className="text-qac-500 dark:text-qac-300">(10 reviews)</span>
            </div>
          </div>

          <div className="relative max-w-3xl mx-auto">
            <div className="overflow-hidden rounded-2xl">
              <div
                className="flex transition-transform duration-700 ease-in-out"
                style={{ transform: `translateX(-${reviewIndex * 100}%)` }}
              >
                {reviews.map((review) => (
                  <div key={review.id} className="w-full shrink-0 px-1">
                    <div className="glass-card p-8 mx-2">
                      <div className="flex items-center justify-center gap-1 mb-4">
                        {Array.from({ length: 5 }).map((_, j) => (
                          <StarIcon key={j} className={`w-5 h-5 ${j < review.rating ? 'text-amber-400 fill-amber-400' : 'text-qac-200 dark:text-qac-700'}`} />
                        ))}
                      </div>
                      <p className="text-center text-qac-700 dark:text-qac-300 mb-6 leading-relaxed">&ldquo;{review.comment}&rdquo;</p>
                      <div className="flex items-center justify-center gap-3">
                        <div className="w-10 h-10 rounded-full bg-amber-gradient flex items-center justify-center text-white text-sm font-bold">
                          {review.customer_name.charAt(0)}
                        </div>
                        <p className="font-semibold text-qac-900 dark:text-white text-sm">{review.customer_name}</p>
                      </div>
                    </div>
                  </div>
                ))}
                <div className="w-full shrink-0 px-1">
                  <div className="glass-card p-8 mx-2 flex flex-col items-center justify-center text-center min-h-full">
                    <div className="w-16 h-16 bg-gradient-to-br from-qac-100 to-qac-200 dark:from-qac-800 dark:to-qac-700 rounded-2xl flex items-center justify-center mb-4">
                      <StarIcon className="w-8 h-8 text-amber-500" />
                    </div>
                    <p className="text-qac-500 dark:text-qac-300 text-sm mb-4">Share your experience</p>
                    <a href="https://search.google.com/local/writereview?placeid=ChIJsbVw5J7RQIYRGK3jJfimU8g" target="_blank" rel="noopener noreferrer" className="btn-primary text-sm">
                      Write a Review
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <button
              type="button"
              onClick={() => setReviewIndex(i => (i - 1 + Math.max(1, reviews.length + 1)) % Math.max(1, reviews.length + 1))}
              className="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-white dark:bg-qac-800 shadow-lg flex items-center justify-center text-qac-700 dark:text-qac-300 hover:bg-amber-gradient hover:text-white transition-colors"
              aria-label="Previous review"
            >
              <ChevronRightIcon className="w-5 h-5 rotate-180" />
            </button>
            <button
              type="button"
              onClick={() => setReviewIndex(i => (i + 1) % Math.max(1, reviews.length + 1))}
              className="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-10 h-10 rounded-full bg-white dark:bg-qac-800 shadow-lg flex items-center justify-center text-qac-700 dark:text-qac-300 hover:bg-amber-gradient hover:text-white transition-colors"
              aria-label="Next review"
            >
              <ChevronRightIcon className="w-5 h-5" />
            </button>

            <div className="flex items-center justify-center gap-2 mt-8">
              {Array.from({ length: reviews.length + 1 }).map((_, i) => (
                <button
                  key={i}
                  type="button"
                  onClick={() => setReviewIndex(i)}
                  className={`h-2 rounded-full transition-all duration-300 ${i === reviewIndex ? 'w-8 bg-amber-gradient' : 'w-2 bg-qac-200 dark:bg-qac-700 hover:bg-qac-300 dark:hover:bg-qac-600'}`}
                  aria-label={`Go to slide ${i + 1}`}
                />
              ))}
            </div>
          </div>

          <div className="text-center mt-10">
            <Link href="/reviews" className="inline-flex items-center gap-2 btn-outline group">
              View All Reviews
              <ArrowRightIcon className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-28 bg-qac-900 dark:bg-black relative overflow-hidden">
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-amber-500/8 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute inset-0 bg-gradient-to-t from-amber-900/10 to-transparent pointer-events-none" />
        <div className="relative max-w-3xl mx-auto px-4 text-center">
          <div className="w-20 h-20 bg-amber-gradient rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-amber-500/30">
            <WrenchIcon className="w-10 h-10 text-white" />
          </div>
          <h2 className="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
            {ctaTitle}
          </h2>
          <p className="text-qac-400 mb-10 text-lg max-w-xl mx-auto">
            {ctaSubtitle}
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Link href={ctaContent?.book_link || '/book'} className="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-qac-900 dark:text-white bg-amber-gradient shadow-xl shadow-amber-500/30 hover:shadow-2xl hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 text-lg">
              {ctaContent?.book_text || 'Book Appointment'}
              <ArrowRightIcon className="w-5 h-5" />
            </Link>
            <a href={`tel:+1${ctaPhone.replace(/\D/g, '')}`} className="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white border-2 border-white/20 bg-white/5 backdrop-blur-sm hover:bg-white/10 hover:border-white/30 transition-all duration-200 text-lg">
              <PhoneIcon className="w-5 h-5" />
              Call {ctaPhone}
            </a>
          </div>
          <div className="flex flex-wrap justify-center gap-6 mt-10 text-sm text-qac-500 dark:text-qac-300">
            <span className="inline-flex items-center gap-2"><MapPinIcon className="w-4 h-4 text-amber-500" /> 9934 Jones Rd B5, Houston, TX</span>
            <span className="inline-flex items-center gap-2"><ClockIcon className="w-4 h-4 text-amber-500" /> Mon-Thu 9-6 | Fri-Sat 9-1</span>
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section id="faqs" ref={setSectionRef('faqs')} className="py-28 hero-gradient-bg">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className={`text-center mb-16 transition-all duration-700 ${visibleSections.has('faqs') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
            <h2 className="section-title">Frequently Asked Questions</h2>
            <p className="section-subtitle">Everything you need to know about our services, hours, and policies.</p>
          </div>

          <div className="max-w-3xl mx-auto">
            {faqs.length === 0 ? (
              <p className="text-center text-qac-500 dark:text-qac-300">No FAQs yet.</p>
            ) : (
              <div className="space-y-3">
                {faqs.map((faq) => (
                  <div key={faq.id} className="glass-card-hover overflow-hidden" style={{ animationDelay: `${faqs.indexOf(faq) * 80}ms` }}>
                    <button
                      onClick={() => setOpenFaqId(openFaqId === faq.id ? null : faq.id)}
                      className="w-full flex items-center justify-between gap-4 p-5 text-left hover:bg-white/50 dark:hover:bg-white/5 transition-colors"
                    >
                      <span className="font-semibold text-qac-900 dark:text-white">{faq.question}</span>
                      <ChevronDownIcon className={`w-5 h-5 text-amber-500 shrink-0 transition-transform duration-300 ${openFaqId === faq.id ? 'rotate-180' : ''}`} />
                    </button>
                    {openFaqId === faq.id && (
                      <div className="px-5 pb-5 -mt-1">
                        <p className="text-qac-600 dark:text-qac-400 leading-relaxed">{faq.answer}</p>
                      </div>
                    )}
                  </div>
                ))}
              </div>
            )}

            <div className="text-center mt-12">
              <Link href="/faqs" className="inline-flex items-center gap-2 btn-outline group">
                View All FAQs
                <ArrowRightIcon className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}
