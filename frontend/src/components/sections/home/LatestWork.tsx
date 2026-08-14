import { useRef, useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import { api, PortfolioItem, resolveImageUrl } from '@/lib/api'
import { motion } from 'framer-motion'

gsap.registerPlugin(ScrollTrigger)

export default function LatestWork() {
  const scrollContainerRef = useRef<HTMLDivElement>(null)
  const [items, setItems] = useState<PortfolioItem[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.portfolio.list({ featured: true })
      .then(res => {
        if (res && res.data) {
          setItems(res.data)
        }
        setLoading(false)
      })
      .catch(err => {
        console.error('Failed to fetch featured portfolio items:', err)
        setLoading(false)
      })
  }, [])

  // Capture wheel events ONLY when user's cursor is over the cards container
  useEffect(() => {
    const container = scrollContainerRef.current
    if (!container || loading || !items.length) return

    const handleWheel = (e: WheelEvent) => {
      // Only handle vertical scroll gestures on mouse wheel
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        const maxScrollLeft = container.scrollWidth - container.clientWidth
        const currentScrollLeft = container.scrollLeft

        // Check if we can scroll horizontally in the requested direction
        const canScrollRight = e.deltaY > 0 && currentScrollLeft < maxScrollLeft - 3
        const canScrollLeft  = e.deltaY < 0 && currentScrollLeft > 3

        if (canScrollRight || canScrollLeft) {
          e.preventDefault()
          container.scrollLeft += e.deltaY * 1.15
        }
      }
    }

    container.addEventListener('wheel', handleWheel, { passive: false })
    return () => container.removeEventListener('wheel', handleWheel)
  }, [loading, items])

  if (loading || !items.length) {
    return (
      <section className="bg-white py-24 flex items-center justify-center">
        <div className="text-brand-dark/40 label animate-pulse">Loading Latest Work...</div>
      </section>
    )
  }

  return (
    <section className="bg-white overflow-hidden pt-12 pb-4 md:pt-16 md:pb-6">
      <div className="w-full">
        <div className="section-padding pb-4 flex items-end justify-between">
          <div>
            <p className="text-brand-dark font-display font-bold text-xl md:text-2xl tracking-tight mb-4">Latest Work</p>
            <h2 className="heading-xl fluid-xl text-brand-dark">
              Real campaigns.<br />Real results.
            </h2>
          </div>
          <Link
            to="/work"
            className="hidden md:inline-flex items-center gap-2 label text-brand-dark/50 hover:text-brand-dark transition-colors duration-200 group"
          >
            View All Work
            <svg className="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </Link>
        </div>

        {/* Horizontal scroll track (scrollable on hover) */}
        <div
          ref={scrollContainerRef}
          className="flex gap-6 px-6 md:px-12 lg:px-20 xl:px-32 pb-2 pt-2 overflow-x-auto select-none"
          style={{
            scrollbarWidth: 'none',
            msOverflowStyle: 'none',
          }}
        >
          {items.map((item) => {
            const rawThumb = item.thumbnail_url || item.hero_url || '/images/default.jpg'
            const thumbUrl = resolveImageUrl(rawThumb)
            const isClickable = item.is_clickable !== false
            const resultText = item.result

            const cardContent = (
              <>
                {/* Full-bleed Header Image */}
                <div className="relative overflow-hidden w-full aspect-[16/10] bg-black/60 flex items-center justify-center">
                  <img
                    src={thumbUrl}
                    alt={item.title}
                    className={`w-full h-full object-cover transition-transform duration-700 ${isClickable ? 'group-hover:scale-105' : ''}`}
                    style={{
                      objectFit: item.image_fit?.startsWith('contain') ? 'contain' : 'cover',
                      objectPosition: item.image_position || 'center',
                      padding: item.image_fit === 'contain-pad' ? '12px' : undefined,
                    }}
                    onError={(e) => {
                      e.currentTarget.onerror = null
                      const fallback = resolveImageUrl('/images/default.jpg')
                      if (e.currentTarget.src !== fallback) {
                        e.currentTarget.src = fallback
                      }
                    }}
                  />
                </div>

                {/* Content */}
                <div className="p-6 flex flex-col flex-grow justify-between">
                  <div>
                    <div className="flex items-center justify-between mb-3">
                      <p className="text-[0.68rem] font-mono font-bold tracking-widest text-white/60 uppercase">{item.client}</p>
                      {item.show_year && <p className="text-[0.68rem] font-mono text-cyan-400/90 font-medium">{item.year}</p>}
                    </div>
                    <h3 className="font-display font-semibold text-white text-lg md:text-xl leading-tight mb-2.5 transition-colors line-clamp-2 group-hover:text-brand-pink">
                      {item.title}
                    </h3>
                    {item.insight && (
                      <p className="text-white/50 text-sm leading-relaxed line-clamp-2 mb-4 font-normal">
                        {item.insight}
                      </p>
                    )}
                  </div>

                  {resultText && (
                    <div className="mt-auto p-3.5 rounded-xl bg-black/50 border border-white/10 flex items-start gap-2.5 text-xs text-white/80 leading-relaxed font-sans font-medium">
                      <span className="text-emerald-400 font-bold shrink-0 mt-0.5">
                        {resultText.trim().startsWith('↑') || resultText.trim().startsWith('•') ? '' : '• '}
                      </span>
                      <span className="text-slate-200 text-xs leading-relaxed font-sans font-medium line-clamp-2">
                        {resultText}
                      </span>
                    </div>
                  )}
                </div>
              </>
            )

            return (
              <div key={item.id} className="flex-shrink-0 flex flex-col" style={{ width: 'clamp(300px, 32vw, 420px)' }}>
                {isClickable ? (
                  <Link
                    to={item.slug ? `/work/${item.slug}` : '/work'}
                    className="group flex flex-col h-full rounded-2xl md:rounded-3xl overflow-hidden bg-brand-dark border border-white/10 hover:border-white/25 transition-all duration-500 shadow-xl hover:shadow-[0_20px_50px_rgba(0,0,0,0.6)] hover:-translate-y-1"
                  >
                    {cardContent}
                  </Link>
                ) : (
                  <div className="flex flex-col h-full rounded-2xl md:rounded-3xl overflow-hidden bg-brand-dark border border-white/10 shadow-xl cursor-default">
                    {cardContent}
                  </div>
                )}
              </div>
            )
          })}

          {/* View all card */}
          <Link
            to="/work"
            className="flex-shrink-0 rounded-2xl md:rounded-3xl border-2 border-dashed border-brand-dark/20 flex flex-col items-center justify-center gap-4 hover:border-brand-pink hover:bg-brand-pink/5 transition-all duration-300 group p-6 h-full min-h-[360px]"
            style={{ width: 'clamp(200px, 20vw, 280px)' }}
          >
            <div className="w-12 h-12 rounded-full border border-brand-dark/20 group-hover:border-brand-pink flex items-center justify-center transition-colors">
              <svg className="w-5 h-5 text-brand-dark/40 group-hover:text-brand-pink transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
            <span className="label text-brand-dark/40 group-hover:text-brand-pink transition-colors text-center">
              View All<br/>Work
            </span>
          </Link>
        </div>
      </div>
    </section>
  )
}
