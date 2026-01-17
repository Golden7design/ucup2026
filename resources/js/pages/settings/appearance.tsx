import { Head } from '@inertiajs/react'
import { ThemeSettings } from '@/components/ThemeSettings'
import { AppLayout } from '@/layouts/app-layout'

export default function AppearanceSettings() {
  return (
    <AppLayout
      title="Préférences d\'apparence"
      breadcrumbs={[
        { label: 'Paramètres', href: '/settings' },
        { label: 'Apparence', href: '/settings/appearance' },
      ]}
    >
      <Head title="Préférences d\'apparence" />
      
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900 dark:text-gray-100">
              <h1 className="text-2xl font-bold mb-6">Préférences d\'apparence</h1>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                  <h2 className="text-xl font-semibold mb-4">Basculer entre les thèmes</h2>
                  <p className="text-gray-600 dark:text-gray-400 mb-4">
                    Vous pouvez rapidement basculer entre les thèmes clair, sombre et système en utilisant le bouton de basculement dans l\'en-tête.
                  </p>
                  <p className="text-gray-600 dark:text-gray-400 mb-4">
                    Le bouton de basculement cycle à travers les trois options :
                  </p>
                  <ul className="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2 ml-4">
                    <li><strong>Mode Clair</strong> : Interface lumineuse avec fond blanc</li>
                    <li><strong>Mode Sombre</strong> : Interface sombre avec fond gris foncé</li>
                    <li><strong>Préférences Système</strong> : Utilise les paramètres de votre système d\'exploitation</li>
                  </ul>
                </div>
                
                <div>
                  <h2 className="text-xl font-semibold mb-4">Paramètres avancés</h2>
                  <ThemeSettings />
                </div>
              </div>
              
              <div className="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h3 className="font-semibold text-blue-800 dark:text-blue-300 mb-2">
                  Synchronisation multi-appareils
                </h3>
                <p className="text-blue-700 dark:text-blue-200 text-sm">
                  Vos préférences de thème sont sauvegardées dans votre compte et synchronisées sur tous vos appareils lorsque vous êtes connecté.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  )
}