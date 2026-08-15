/**
 * Dedicated 3D Animated WebGL Circle Ring for Award Displays.
 * Features exact Three.js WebGL MeshPhysicalMaterial 3D Torus ring with emissive chase lighting.
 * Uses IntersectionObserver & WebGL Context Management for zero context loss errors.
 */
import { useEffect, useRef, useState } from 'react'
import * as THREE from 'three'
import gsap from 'gsap'

const PINK   = 0xe8005a
const PURPLE = 0x7b2fbe
const BLUE   = 0x1b3fb5
const TEAL   = 0x00b4b4

const GAP = 0.08
const ARC = Math.PI / 2 - GAP

const SEGS = [
  { hex: PINK,   rotZ: GAP / 2 },
  { hex: PURPLE, rotZ: Math.PI * 1.5 + GAP / 2 },
  { hex: BLUE,   rotZ: Math.PI + GAP / 2 },
  { hex: TEAL,   rotZ: Math.PI * 0.5 + GAP / 2 },
]

interface Props {
  size?: number
  className?: string
}

export default function AwardCircleWebGL({ size = 320, className = '' }: Props) {
  const mountRef = useRef<HTMLDivElement>(null)
  const [isVisible, setIsVisible] = useState(false)
  const [hasContextError, setHasContextError] = useState(false)

  // IntersectionObserver — only instantiate WebGL when visible in viewport
  useEffect(() => {
    const mount = mountRef.current
    if (!mount) return

    const observer = new IntersectionObserver(
      ([entry]) => {
        setIsVisible(entry.isIntersecting)
      },
      { threshold: 0.02 }
    )

    observer.observe(mount)
    return () => observer.disconnect()
  }, [])

  // WebGL Three.js Scene Lifecycle
  useEffect(() => {
    const mount = mountRef.current
    if (!mount || !isVisible || hasContextError) return

    let renderer: THREE.WebGLRenderer | null = null
    let rafId: number = 0
    let gsapTweens: gsap.core.Timeline | null = null

    try {
      renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' })
      renderer.setSize(size, size)
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
      renderer.toneMapping = THREE.ACESFilmicToneMapping
      renderer.toneMappingExposure = 1.4
      renderer.outputColorSpace = THREE.SRGBColorSpace

      // Handle WebGL context lost gracefully
      const canvas = renderer.domElement
      const handleContextLost = (e: Event) => {
        e.preventDefault()
        cancelAnimationFrame(rafId)
        if (gsapTweens) gsapTweens.kill()
        setHasContextError(true)
      }
      canvas.addEventListener('webglcontextlost', handleContextLost, false)

      mount.appendChild(canvas)

      const scene = new THREE.Scene()
      const camera = new THREE.PerspectiveCamera(38, 1, 0.1, 50)
      camera.position.z = 5.6

      // Lights
      scene.add(new THREE.AmbientLight(0xffffff, 0.3))
      const key = new THREE.DirectionalLight(0xffffff, 5.0)
      key.position.set(2, 4, 5)
      scene.add(key)
      const fill = new THREE.DirectionalLight(0xffffff, 1.8)
      fill.position.set(-3, -2, 3)
      scene.add(fill)
      const rim = new THREE.DirectionalLight(0xffffff, 3.0)
      rim.position.set(0, -5, -4)
      scene.add(rim)

      const group = new THREE.Group()
      group.rotation.x = 0 // Face-on front facing
      group.rotation.y = 0 // Never turn sideways
      scene.add(group)

      const materials: THREE.MeshPhysicalMaterial[] = []

      // TorusGeometry(radius: 1.6, tube: 0.20) gives a wide hollow center hole for trophy images!
      SEGS.forEach(({ hex, rotZ }) => {
        const geo = new THREE.TorusGeometry(1.6, 0.20, 64, 200, ARC)
        const mat = new THREE.MeshPhysicalMaterial({
          color: hex,
          metalness: 0.4,
          roughness: 0.10,
          emissive: hex,
          emissiveIntensity: 0.5,
          clearcoat: 1,
          clearcoatRoughness: 0.06,
        })
        const mesh = new THREE.Mesh(geo, mat)
        mesh.rotation.z = rotZ
        group.add(mesh)
        materials.push(mat)
      })

      // Chase-light loop
      const runChase = () => {
        gsapTweens = gsap.timeline({ onComplete: () => setTimeout(runChase, 1600) })
        materials.forEach((mat, i) => {
          gsapTweens?.to(mat, { emissiveIntensity: 1.4, duration: 0.14, ease: 'power2.out' }, i * 0.12)
          gsapTweens?.to(mat, { emissiveIntensity: 0.5, duration: 0.45, ease: 'power2.in'  }, i * 0.12 + 0.14)
        })
        gsapTweens?.to(materials, { emissiveIntensity: 1.8, duration: 0.12, ease: 'power2.out' }, '+=0.12')
        gsapTweens?.to(materials, { emissiveIntensity: 0.5, duration: 0.55, ease: 'power2.in'  })
      }
      runChase()

      // RAF loop — gentle clockwise rotation around the Z-axis facing the user
      const animate = () => {
        rafId = requestAnimationFrame(animate)
        group.rotation.z += 0.005 // Continuous halo spin facing user
        renderer?.render(scene, camera)
      }
      animate()

      return () => {
        cancelAnimationFrame(rafId)
        if (gsapTweens) gsapTweens.kill()
        gsap.killTweensOf(materials)
        canvas.removeEventListener('webglcontextlost', handleContextLost)
        if (renderer) {
          renderer.dispose()
          if (canvas.parentNode === mount) {
            mount.removeChild(canvas)
          }
        }
      }
    } catch {
      setHasContextError(true)
    }
  }, [size, isVisible, hasContextError])

  return (
    <div
      ref={mountRef}
      className={`relative flex items-center justify-center select-none ${className}`}
      style={{ width: size, height: size, flexShrink: 0 }}
    >
      {/* Ambient background glow ring */}
      <div
        className="absolute inset-4 rounded-full opacity-40 blur-2xl pointer-events-none"
        style={{
          background: 'conic-gradient(from 0deg, #E8005A, #7B2FBE, #1B3FB5, #00B4B4, #E8005A)',
        }}
      />
    </div>
  )
}

