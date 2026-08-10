import { useState, useEffect, useRef } from 'react'
import { Outlet, useLocation } from 'react-router-dom'
import Navbar from '@/components/layout/Navbar'
import Footer from '@/components/layout/Footer'
import LoadingScreen from '@/components/ui/LoadingScreen'
import CustomCursor from '@/components/ui/CustomCursor'
import WhatsAppFloat from '@/components/ui/WhatsAppFloat'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export default function App() {
  const [loading, setLoading] = useState(true)
  const location = useLocation()

  // Disable browser automatic scroll restoration
  useEffect(() => {
    if ('scrollRestoration' in window.history) {
      window.history.scrollRestoration = 'manual'
    }
  }, [])

  // Force instant scroll to top on route change
  useEffect(() => {
    if (loading) return

    // Kill stale ScrollTriggers from previous routes to prevent scroll jumping
    ScrollTrigger.getAll().forEach(t => t.kill())

    const resetScroll = () => {
      window.scrollTo({ top: 0, left: 0, behavior: 'instant' })
      document.documentElement.scrollTop = 0
      document.body.scrollTop = 0
    }

    resetScroll()

    const rAF = requestAnimationFrame(resetScroll)
    const t1 = setTimeout(resetScroll, 50)
    const t2 = setTimeout(resetScroll, 200)

    return () => {
      cancelAnimationFrame(rAF)
      clearTimeout(t1)
      clearTimeout(t2)
    }
  }, [location.pathname, loading])

  if (loading) {
    return <LoadingScreen onComplete={() => setLoading(false)} />
  }

  return (
    <>
      <CustomCursor />
      <Navbar />
      <main key={location.pathname}>
        <Outlet />
      </main>
      <WhatsAppFloat />
      <Footer />
    </>
  )
}

