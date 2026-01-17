import { useState, useEffect } from 'react'
import { router } from '@inertiajs/react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import { Label } from '@/components/ui/label'
import { useAppearance } from '@/hooks/use-appearance'

export function ThemeSettings() {
  const { appearance, setAppearance } = useAppearance()
  const [currentTheme, setCurrentTheme] = useState(appearance)
  const [isSaving, setIsSaving] = useState(false)
  const [saveStatus, setSaveStatus] = useState<{ success: boolean; message: string } | null>(null)

  useEffect(() => {
    setCurrentTheme(appearance)
  }, [appearance])

  const handleThemeChange = (theme: string) => {
    setCurrentTheme(theme as 'light' | 'dark' | 'system')
  }

  const saveThemePreference = async () => {
    setIsSaving(true)
    setSaveStatus(null)
    
    try {
      await router.post('/theme', { theme: currentTheme }, {
        preserveScroll: true,
        preserveState: true,
      })
      
      setAppearance(currentTheme)
      setSaveStatus({ success: true, message: 'Préférences de thème sauvegardées avec succès !' })
    } catch (error) {
      console.error('Failed to save theme preference:', error)
      setSaveStatus({ success: false, message: 'Échec de la sauvegarde des préférences' })
    } finally {
      setIsSaving(false)
    }
  }

  const themeOptions = [
    { 
      value: 'light', 
      label: 'Mode Clair',
      description: 'Utiliser toujours le thème clair'
    },
    { 
      value: 'dark', 
      label: 'Mode Sombre',
      description: 'Utiliser toujours le thème sombre'
    },
    { 
      value: 'system', 
      label: 'Préférences Système',
      description: 'Utiliser les paramètres de préférence de couleur du système'
    }
  ]

  return (
    <Card className="w-full max-w-md">
      <CardHeader>
        <CardTitle>Préférences d\'apparence</CardTitle>
        <CardDescription>
          Choisissez le thème qui vous convient le mieux pour votre expérience utilisateur.
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-6">
        <RadioGroup 
          value={currentTheme} 
          onValueChange={handleThemeChange}
          className="grid grid-cols-1 gap-4"
        >
          {themeOptions.map((option) => (
            <div key={option.value} className="flex items-center space-x-3">
              <RadioGroupItem value={option.value} id={option.value} />
              <div className="flex flex-col">
                <Label htmlFor={option.value} className="cursor-pointer font-medium">
                  {option.label}
                </Label>
                <p className="text-sm text-muted-foreground">
                  {option.description}
                </p>
              </div>
            </div>
          ))}
        </RadioGroup>

        {saveStatus && (
          <div 
            className={`p-3 rounded-md text-sm ${
              saveStatus.success 
                ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400'
                : 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400'
            }`}
          >
            {saveStatus.message}
          </div>
        )}

        <Button 
          onClick={saveThemePreference} 
          disabled={isSaving || currentTheme === appearance}
          className="w-full"
        >
          {isSaving ? 'Sauvegarde...' : 'Sauvegarder les préférences'}
        </Button>
      </CardContent>
    </Card>
  )
}