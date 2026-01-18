import { Head, Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { AppLayout } from '@/layouts/app-layout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

export default function SettingsIndex() {
    const settingsSections = [
        {
            title: 'Apparence',
            description: 'Personnalisez le thème et l\'apparence de l\'interface',
            route: 'settings.appearance',
            icon: '🎨',
        },
    ];

    return (
        <AppLayout
            breadcrumbs={[
                { title: 'Paramètres', href: '/settings' },
            ]}
        >
            <Head title="Paramètres" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <h1 className="text-2xl font-bold mb-6">Paramètres du compte</h1>

                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {settingsSections.map((section) => (
                                    <Card key={section.route} className="hover:shadow-lg transition-shadow duration-200">
                                        <CardHeader>
                                            <div className="flex items-center space-x-2">
                                                <span className="text-2xl">{section.icon}</span>
                                                <CardTitle>{section.title}</CardTitle>
                                            </div>
                                        </CardHeader>
                                        <CardContent>
                                            <CardDescription className="mb-4">
                                                {section.description}
                                            </CardDescription>
                                            <Button asChild className="w-full">
                                                <Link href={route(section.route)} method="get">Gérer</Link>
                                            </Button>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}