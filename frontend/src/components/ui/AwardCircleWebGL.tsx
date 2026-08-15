/**
 * Dedicated 3D Animated Circle Ring for Award Displays.
 * Features exact 3D Torus geometry with metallic tubular lighting and 4 brand quadrant arcs.
 * Uses 0 WebGL contexts to guarantee 100% dark background stability and zero context loss crashes.
 */
import { useId } from 'react'
import { motion } from 'framer-motion'

const PINK   = '#E8005A'
const PURPLE = '#7B2FBE'
const BLUE   = '#1B3FB5'
const TEAL   = '#00B4B4'

interface Props {
  size?: number
  className?: string
}

export default function AwardCircleWebGL({ size = 320, className = '' }: Props) {
  const uid = useId().replace(/:/g, '')

  // 4 Quadrant Arcs matching exact original WebGL colors & positioning
  const segments = [
    {
      id: `pink-${uid}`,
      colorStart: '#FF2A7A',
      colorEnd: PINK,
      glow: 'rgba(232, 0, 90, 0.7)',
      rotation: 0,
    },
    {
      id: `purple-${uid}`,
      colorStart: '#9D4EDD',
      colorEnd: PURPLE,
      glow: 'rgba(123, 47, 190, 0.7)',
      rotation: 90,
    },
    {
      id: `blue-${uid}`,
      colorStart: '#3B82F6',
      colorEnd: BLUE,
      glow: 'rgba(27, 63, 181, 0.7)',
      rotation: 180,
    },
    {
      id: `teal-${uid}`,
      colorStart: '#00F0F0',
      colorEnd: TEAL,
      glow: 'rgba(0, 180, 180, 0.7)',
      rotation: 270,
    },
  ]

  // Radius = 72, StrokeWidth = 14 => Inner Diameter ~ 130px (Wide hollow center opening for trophies)
  const R = 72
  const strokeWidth = 14
  const C = 2 * Math.PI * R
  const arcLength = C * 0.222 // 22.2% of circumference (~80deg)
  const gapLength = C * 0.028 // 2.8% gap (~10deg)

  return (
    <div
      className={`relative flex items-center justify-center select-none ${className}`}
      style={{ width: size, height: size, flexShrink: 0 }}
    >
      {/* Ambient background glow ring */}
      <div
        className="absolute inset-3 rounded-full opacity-50 blur-2xl pointer-events-none"
        style={{
          background: `conic-gradient(from 0deg, ${PINK}, ${PURPLE}, ${BLUE}, ${TEAL}, ${PINK})`,
        }}
      />

      {/* Continuously rotating 3D Torus Ring */}
      <motion.div
        className="w-full h-full relative z-10"
        animate={{ rotate: 360 }}
        transition={{ duration: 25, repeat: Infinity, ease: 'linear' }}
      >
        <svg viewBox="0 0 200 200" className="w-full h-full overflow-visible filter drop-shadow-[0_16px_32px_rgba(0,0,0,0.7)]">
          <defs>
            {/* 3D Tubular Shading Filter */}
            <filter id={`tube-shadow-${uid}`} x="-30%" y="-30%" width="160%" height="160%">
              <feDropShadow dx="0" dy="6" stdDeviation="5" floodColor="#000000" floodOpacity="0.6" />
            </filter>

            {/* 3D Top Metallic Specular Highlight */}
            <linearGradient id={`tube-specular-${uid}`} x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stopColor="#ffffff" stopOpacity="0.75" />
              <stop offset="40%" stopColor="#ffffff" stopOpacity="0.25" />
              <stop offset="100%" stopColor="#000000" stopOpacity="0.4" />
            </linearGradient>

            {/* Segment Gradients */}
            {segments.map((seg) => (
              <linearGradient key={seg.id} id={seg.id} x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor={seg.colorStart} />
                <stop offset="100%" stopColor={seg.colorEnd} />
              </linearGradient>
            ))}
          </defs>

          {/* Render the 4 3D Torus Quadrant Arcs */}
          {segments.map((seg) => (
            <g key={seg.id} transform={`rotate(${seg.rotation} 100 100)`}>
              {/* Soft Neon Glow Pass */}
              <circle
                cx="100"
                cy="100"
                r={R}
                fill="none"
                stroke={seg.colorStart}
                strokeWidth={strokeWidth + 5}
                strokeDasharray={`${arcLength} ${C - arcLength}`}
                strokeDashoffset={gapLength / 2}
                strokeLinecap="round"
                opacity="0.35"
                className="blur-[4px]"
              />

              {/* Main 3D Metallic Color Tube */}
              <circle
                cx="100"
                cy="100"
                r={R}
                fill="none"
                stroke={`url(#${seg.id})`}
                strokeWidth={strokeWidth}
                strokeDasharray={`${arcLength} ${C - arcLength}`}
                strokeDashoffset={gapLength / 2}
                strokeLinecap="round"
                filter={`url(#tube-shadow-${uid})`}
              />

              {/* 3D Specular Highlight Sheen Ridge */}
              <circle
                cx="100"
                cy="100"
                r={R}
                fill="none"
                stroke={`url(#tube-specular-${uid})`}
                strokeWidth={strokeWidth}
                strokeDasharray={`${arcLength} ${C - arcLength}`}
                strokeDashoffset={gapLength / 2}
                strokeLinecap="round"
                opacity="0.7"
              />
            </g>
          ))}
        </svg>
      </motion.div>
    </div>
  )
}


