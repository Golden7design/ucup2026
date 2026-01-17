import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import gsap from 'gsap';

export function PageTransition() {
    const page = usePage();

    useEffect(() => {
        // Animation de sortie au changement de page
        const handleBeforeNavigate = () => {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'page-transition';
            loadingOverlay.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(255, 255, 255, 0.9);
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(loadingOverlay);

            gsap.to(loadingOverlay, {
                opacity: 0,
                duration: 0.3,
                delay: 0.1,
                onComplete: () => {
                    loadingOverlay.remove();
                },
            });
        };

        // Animation d'entrée après changement de page
        const timer = setTimeout(() => {
            const mainContent = document.querySelector('main') || document.body;
            gsap.fromTo(
                mainContent,
                { opacity: 0, y: 10 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.4,
                    ease: 'power2.out',
                }
            );
        }, 100);

        return () => clearTimeout(timer);
    }, [page.url]);

    return null;
}
