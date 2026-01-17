import { useState, useEffect } from 'react'
import { useAppearance } from '@/hooks/use-appearance'
import { router } from '@inertiajs/react'

interface ThemeToggleProps {
  className?: string
}

export function ThemeToggle({ className = '' }: ThemeToggleProps) {
  const { appearance, setAppearance } = useAppearance()
  const [isDarkMode, setIsDarkMode] = useState(appearance === 'dark')
  const [isLoading, setIsLoading] = useState(false)

  useEffect(() => {
    setIsDarkMode(appearance === 'dark')
  }, [appearance])

  const toggleTheme = async () => {
    setIsLoading(true)
    
    // Cycling through themes: dark -> light -> system -> dark
    const currentTheme = appearance
    let newTheme
    
    if (currentTheme === 'dark') {
      newTheme = 'light'
    } else if (currentTheme === 'light') {
      newTheme = 'system'
    } else {
      newTheme = 'dark'
    }
    
    try {
      // Save preference to server
      await router.post('/theme', { theme: newTheme }, {
        preserveScroll: true,
        preserveState: true,
      })
      
      // Update local state
      setAppearance(newTheme)
      setIsDarkMode(newTheme === 'dark')
    } catch (error) {
      console.error('Failed to save theme preference:', error)
    } finally {
      setIsLoading(false)
    }
  }

  // Get theme icon based on current theme
  const getThemeIcon = () => {
    if (isLoading) {
      return <span className="h-5 w-5 animate-spin rounded-full border-2 border-gray-400 border-t-transparent"></span>
    }
    
    switch (appearance) {
      case 'dark':
        return (
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        )
      case 'light':
        return (
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        )
      case 'system':
        return (
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        )
      default:
        return null
    }
  }

  // Get tooltip text based on next theme
  const getTooltipText = () => {
    const currentTheme = appearance
    
    if (currentTheme === 'dark') {
      return 'Passer au mode clair'
    } else if (currentTheme === 'light') {
      return 'Utiliser les préférences système'
    } else {
      return 'Passer au mode sombre'
    }
  }

  return (
    <button
      onClick={toggleTheme}
      disabled={isLoading}
      className={`p-2 rounded-full transition-colors duration-200 ${className} ${isLoading ? 'opacity-70 cursor-wait' : 'hover:bg-gray-100 dark:hover:bg-gray-700'}`}
      aria-label={getTooltipText()}
      title={getTooltipText()}
    >
      {getThemeIcon()}
    </button>
  )
}