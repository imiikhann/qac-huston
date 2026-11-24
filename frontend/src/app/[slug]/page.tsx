import { api } from '@/lib/api'
import { notFound } from 'next/navigation'
import type { Metadata } from 'next'

interface Props {
  params: { slug: string }
}

async function getPage(slug: string) {
  try {
    const res = await api.cms.page(slug)
    return res.data
  } catch {
    return null
  }
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const page = await getPage(params.slug)
  if (!page) return {}
  return {
    title: page.meta_title || page.title,
    description: page.meta_description || undefined,
  }
}

export default async function CMSPage({ params }: Props) {
  const page = await getPage(params.slug)
  if (!page) notFound()

  return (
    <section className="py-16 lg:py-20">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-8">{page.title}</h1>
        <div
          className="prose prose-lg max-w-none prose-headings:text-qac-900 dark:text-white prose-a:text-amber-600"
          dangerouslySetInnerHTML={{ __html: page.content || '' }}
        />
      </div>
    </section>
  )
}
