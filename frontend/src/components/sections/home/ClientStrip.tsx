import { useEffect, useState } from 'react'
import { api, Client, resolveImageUrl } from '@/lib/api'

import dialogLogo from '@/assets/clients/dialog.svg'
import masLogo from '@/assets/clients/mas.png'
import yamahaLogo from '@/assets/clients/yamaha.svg'
import pepsiLogo from '@/assets/clients/pepsi.png'
import hemasLogo from '@/assets/clients/hemas.svg'
import softlogicLogo from '@/assets/clients/softlogic.png'
import cargillsLogo from '@/assets/clients/cargills.png'
import commercialBankLogo from '@/assets/clients/commercial-bank.svg'
import keellsLogo from '@/assets/clients/keells.png'
import sampathLogo from '@/assets/clients/sampath-bank.png'
import elephantHouseLogo from '@/assets/clients/elephant-house.png'
import ceatLogo from '@/assets/clients/ceat.svg'
import havelockLogo from '@/assets/clients/havelock-city-mall.png'

const localClientLogos: Record<string, string> = {
  'havelock city mall': havelockLogo,
  'havelock city': havelockLogo,
  'havelock': havelockLogo,
  'havelock mall': havelockLogo,
  'yamaha': yamahaLogo,
  'yamaha motor': yamahaLogo,
  'pepsi': pepsiLogo,
  'pepsico': pepsiLogo,
  'mas': masLogo,
  'mas holdings': masLogo,
  'softlogic': softlogicLogo,
  'softlogic life': softlogicLogo,
  'softlogic holdings': softlogicLogo,
  'dialog': dialogLogo,
  'dialog axiata': dialogLogo,
  'hemas': hemasLogo,
  'commercial bank': commercialBankLogo,
  'combank': commercialBankLogo,
  'keells': keellsLogo,
  'john keells': keellsLogo,
  'john keells group': keellsLogo,
  'cargills': cargillsLogo,
  'sampath': sampathLogo,
  'sampath bank': sampathLogo,
  'elephant house': elephantHouseLogo,
  'elephant': elephantHouseLogo,
  'ceat': ceatLogo,
  'ceat tyres': ceatLogo,
}

const fallbackClients: Client[] = [
  { id: 1, name: 'Havelock City Mall', logo_url: havelockLogo },
  { id: 2, name: 'Dialog Axiata', logo_url: dialogLogo },
  { id: 3, name: 'MAS Holdings', logo_url: masLogo },
  { id: 4, name: 'Yamaha', logo_url: yamahaLogo },
  { id: 5, name: 'PepsiCo', logo_url: pepsiLogo },
  { id: 6, name: 'Elephant House', logo_url: elephantHouseLogo },
  { id: 7, name: 'CEAT', logo_url: ceatLogo },
  { id: 8, name: 'Hemas', logo_url: hemasLogo },
  { id: 9, name: 'Softlogic', logo_url: softlogicLogo },
  { id: 10, name: 'Cargills', logo_url: cargillsLogo },
  { id: 11, name: 'Commercial Bank', logo_url: commercialBankLogo },
  { id: 12, name: 'Keells', logo_url: keellsLogo },
  { id: 13, name: 'Sampath Bank', logo_url: sampathLogo },
]

function ClientLogo({ name, logo_url }: { name: string; logo_url?: string }) {
  const [failed, setFailed] = useState(false)
  const normName = name ? name.toLowerCase().trim() : ''
  const localUrl = localClientLogos[normName] || Object.entries(localClientLogos).find(([k]) => normName.includes(k))?.[1]
  
  const rawUrl = localUrl || logo_url || ''
  let targetUrl = resolveImageUrl(rawUrl)

  // Filter out low-res 16px google favicon URLs so tiny circle dots are never rendered
  if (targetUrl.includes('google.com/s2/favicons') || targetUrl.includes('favicon')) {
    targetUrl = ''
  }

  return (
    <div className="flex items-center justify-center shrink-0 h-16 md:h-20 w-48 sm:w-56 md:w-64 px-6 select-none group">
      {!failed && targetUrl ? (
        <img
          src={targetUrl}
          alt={name}
          className="max-h-11 md:max-h-14 w-auto max-w-[170px] md:max-w-[210px] object-contain grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300 ease-out cursor-pointer"
          onError={() => setFailed(true)}
          loading="eager"
        />
      ) : (
        <span className="text-neutral-500 group-hover:text-black transition-colors duration-300 font-display font-bold text-sm md:text-base uppercase tracking-wider text-center leading-tight whitespace-nowrap cursor-pointer">
          {name}
        </span>
      )}
    </div>
  )
}

export default function ClientStrip() {
  const [clients, setClients] = useState<Client[]>(fallbackClients)

  useEffect(() => {
    api.clients
      .list()
      .then(res => {
        if (res && res.data && res.data.length > 0) {
          const apiNames = new Set(res.data.map(c => c.name.toLowerCase()))
          const combined = [...res.data, ...fallbackClients.filter(c => !apiNames.has(c.name.toLowerCase()))]
          setClients(combined as Client[])
        }
      })
      .catch(() => {})
  }, [])

  // Duplicate 3x inside ONE single track container to prevent marquee collisions
  const marqueeClients = [...clients, ...clients, ...clients]

  return (
    <section className="bg-[#FAFAFA] border-y border-neutral-200/60 py-6 md:py-8 overflow-hidden">
      {/* Logos marquee title */}
      <div className="max-w-7xl mx-auto px-6 mb-4 md:mb-5 text-center">
        <p className="text-brand-dark font-display font-bold text-xl md:text-2xl tracking-tight mb-4">
          Trusted By Leading Brands
        </p>
      </div>

      {/* Seamless single-track infinite marquee */}
      <div className="relative overflow-hidden w-full flex select-none group/strip">
        <div className="absolute left-0 top-0 bottom-0 w-20 sm:w-32 md:w-48 bg-gradient-to-r from-[#FAFAFA] via-[#FAFAFA]/90 to-transparent z-10 pointer-events-none" />
        <div className="absolute right-0 top-0 bottom-0 w-20 sm:w-32 md:w-48 bg-gradient-to-l from-[#FAFAFA] via-[#FAFAFA]/90 to-transparent z-10 pointer-events-none" />

        <div className="marquee-track items-center py-3 will-change-transform transform-gpu group-hover/strip:[animation-play-state:paused]">
          {marqueeClients.map((client, i) => (
            <ClientLogo key={`m-${client.id || i}-${i}`} name={client.name} logo_url={client.logo_url} />
          ))}
        </div>
      </div>
    </section>
  )
}
