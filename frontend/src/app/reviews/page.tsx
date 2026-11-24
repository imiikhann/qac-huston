'use client'

import { useState, useEffect } from 'react'
import { api, Review } from '@/lib/api'
import { StarIcon } from '@heroicons/react/24/outline'

export default function ReviewsPage() {
  const [reviews, setReviews] = useState<Review[]>([])

  useEffect(() => {
    api.reviews.list().then((res) => setReviews(res.data.filter(r => r.is_approved))).catch(() => {})
  }, [])

  return (
    <>
      <section className="bg-qac-50 dark:bg-qac-900/30 py-16 lg:py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl lg:text-5xl font-bold text-qac-900 dark:text-white mb-4">Customer Reviews</h1>
          <p className="text-xl text-qac-500 dark:text-qac-300">Rating: 5.0 based on Google reviews</p>
        </div>
      </section>

      <section className="py-16 lg:py-20">
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          {reviews.length === 0 ? (
            <p className="text-center text-qac-400">No reviews yet.</p>
          ) : (
            <div className="grid sm:grid-cols-2 gap-6">
              {reviews.map((review) => (
                <div key={review.id} className="card p-6">
                  <div className="flex items-center gap-1 mb-3">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <StarIcon
                        key={i}
                        className={`w-5 h-5 ${i < review.rating ? 'text-amber-400 fill-amber-400' : 'text-qac-200'}`}
                      />
                    ))}
                  </div>
                  {review.title && <h3 className="font-semibold text-qac-900 dark:text-white mb-2">{review.title}</h3>}
                  <p className="text-qac-600 dark:text-qac-300 mb-4 leading-relaxed">&ldquo;{review.comment}&rdquo;</p>
                  <p className="font-semibold text-qac-900 dark:text-white">- {review.customer_name}</p>
                </div>
              ))}
            </div>
          )}

          <div className="text-center mt-10">
            <a
              href="https://search.google.com/local/writereview?placeid=ChIJsbVw5J7RQIYRGK3jJfimU8g"
              target="_blank"
              rel="noopener noreferrer"
              className="btn-primary"
            >
              Write a Review
            </a>
          </div>
        </div>
      </section>
    </>
  )
}
