const siteKey = (import.meta.env.VITE_RECAPTCHA_SITE_KEY as string | undefined)?.trim()
 
export function initRecaptcha(): void {
  if (!siteKey || typeof window === 'undefined') return
  if (document.querySelector('script[src*="recaptcha/api.js"]')) return

  const script = document.createElement('script')
  script.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(siteKey)}`
  script.async = true
  document.head.appendChild(script)
}

// Automatically preload reCAPTCHA script in the browser when key is provided via .env
if (typeof window !== 'undefined' && siteKey) {
  initRecaptcha()
}

export function getRecaptchaToken(action: string): Promise<string | null> {
  if (!siteKey) return Promise.resolve(null)

  return new Promise((resolve) => {
    if (typeof window === 'undefined') return resolve(null)

    const execute = () => {
      const grecaptcha = (window as any).grecaptcha
      if (grecaptcha && grecaptcha.execute) {
        grecaptcha.ready(() => {
          grecaptcha.execute(siteKey, { action })
            .then((token: string) => resolve(token))
            .catch((err: any) => {
              console.warn('reCAPTCHA v3 execution error:', err)
              resolve(null)
            })
        })
      } else {
        resolve(null)
      }
    }

    const grecaptcha = (window as any).grecaptcha
    if (!grecaptcha) {
      let script = document.querySelector<HTMLScriptElement>('script[src*="recaptcha/api.js"]')
      if (!script) {
        script = document.createElement('script')
        script.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(siteKey)}`
        script.async = true
        document.head.appendChild(script)
      }
      script.addEventListener('load', execute, { once: true })
      script.addEventListener('error', () => resolve(null), { once: true })
    } else {
      execute()
    }
  })
}

