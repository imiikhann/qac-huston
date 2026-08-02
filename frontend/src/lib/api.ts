const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1'

async function fetchAPI<T>(endpoint: string, options?: RequestInit): Promise<T> {
  const isFormData = options?.body instanceof FormData
  const res = await fetch(`${API_URL}${endpoint}`, {
    headers: {
      ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
      'Accept': 'application/json',
      ...options?.headers,
    },
    ...options,
  })

  if (!res.ok) {
    const error = await res.json().catch(() => ({ message: 'An error occurred' }))
    throw new Error(error.message || `API Error: ${res.status}`)
  }

  return res.json()
}

export interface Service {
  id: number
  name: string
  description: string
  category: 'electrical' | 'mechanical' | 'special'
  price_range: string
  is_active: boolean
  sort_order: number
}

export interface Review {
  id: number
  customer_name: string
  rating: number
  title: string
  comment: string
  is_approved: boolean
  created_at: string
}

export interface FAQ {
  id: number
  question: string
  answer: string
  category: string
  sort_order: number
}

export interface Vendor {
  id: number
  name: string
  logo: string
  website: string
  category: string
  description: string
}

export interface Testimonial {
  id: number
  customer_name: string
  content: string
  rating: number
  is_approved: boolean
}

export interface SiteSetting {
  id: number
  site_name: string
  logo: string | null
  favicon: string | null
  contact_email: string
  contact_phone: string
  address: string
  social_links: Record<string, string> | null
  footer_text: string
}

export interface HeroBanner {
  id: number
  title: string
  subtitle: string | null
  image: string | null
  cta_text: string | null
  cta_link: string | null
  sort_order: number
  is_active: boolean
}

export interface NavigationMenuItem {
  id: number
  label: string
  url: string | null
  icon: string | null
  parent_id: number | null
  sort_order: number
  location: string
  is_active: boolean
  children: NavigationMenuItem[]
}

export interface Page {
  id: number
  title: string
  slug: string
  content: string | null
  meta_title: string | null
  meta_description: string | null
  is_published: boolean
}

export interface Section {
  id: number
  type: string
  title: string | null
  subtitle: string | null
  content: Record<string, any> | null
  image: string | null
  is_active: boolean
  sort_order: number
}

export interface MembershipProgram {
  id: number
  name: string
  price: string
  features: string[] | { feature: string }[]
  is_popular: boolean
  description: string | null
  button_text: string | null
  button_link: string | null
  is_active: boolean
  sort_order: number
}

export const api = {
  services: {
    list: () => fetchAPI<{ data: Service[] }>('/services?per_page=100'),
    get: (id: number) => fetchAPI<{ data: Service }>(`/services/${id}`),
  },
  reviews: {
    list: () => fetchAPI<{ data: Review[] }>('/reviews'),
  },
  faqs: {
    list: () => fetchAPI<{ data: FAQ[] }>('/faqs'),
  },
  vendors: {
    list: () => fetchAPI<{ data: Vendor[] }>('/vendors'),
  },
  testimonials: {
    list: () => fetchAPI<{ data: Testimonial[] }>('/testimonials'),
  },
  appointments: {
    create: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/appointments', {
        method: 'POST',
        body: JSON.stringify(data),
      }),
  },
  memberships: {
    enroll: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/memberships', {
        method: 'POST',
        body: data instanceof FormData ? data : JSON.stringify(data),
      }),
  },
  leads: {
    create: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/leads', {
        method: 'POST',
        body: JSON.stringify(data),
      }),
  },
  fleetInquiries: {
    create: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/fleet-inquiries', {
        method: 'POST',
        body: JSON.stringify(data),
      }),
  },
  ppiRequests: {
    create: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/ppi-requests', {
        method: 'POST',
        body: JSON.stringify(data),
      }),
  },
  contact: {
    send: (data: any) =>
      fetchAPI<{ data: any; message: string }>('/contact', {
        method: 'POST',
        body: JSON.stringify(data),
      }),
  },

  track: (url: string, referrer?: string) => {
    fetchAPI('/track', {
      method: 'POST',
      body: JSON.stringify({ url, referrer: referrer || null }),
    }).catch(() => {})
  },

  // CMS endpoints
  cms: {
    settings: () => fetchAPI<{ data: SiteSetting }>('/settings'),
    heroBanners: () => fetchAPI<{ data: HeroBanner[] }>('/hero-banners'),
    navigation: (location = 'header') => fetchAPI<{ data: NavigationMenuItem[] }>(`/navigation/${location}`),
    pages: () => fetchAPI<{ data: Page[] }>('/pages'),
    page: (slug: string) => fetchAPI<{ data: Page }>(`/pages/${slug}`),
    sections: () => fetchAPI<{ data: Section[] }>('/sections'),
    membershipPrograms: () => fetchAPI<{ data: MembershipProgram[] }>('/membership-programs'),
  },
}
