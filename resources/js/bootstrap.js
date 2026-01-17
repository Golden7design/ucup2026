import Echo from 'laravel-echo';
import * as Pusher from 'pusher-js'; // 🚨 Utilisation de l'import standard pour la compatibilité Vite

// Importation de JQuery et attachement à la fenêtre globale
import $ from 'jquery'; 
window.$ = window.jQuery = $;

// Définir Pusher globalement AVANT d'initialiser Echo
window.Pusher = Pusher.default || Pusher; // Gérer les imports par défaut et nommés

// 🚨 Placer l'initialisation d'Echo en dehors de DOMContentLoaded est souvent plus simple,
// car Echo n'a pas besoin du DOM pour s'initialiser, seulement pour les écouteurs.

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, 
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, 
    forceTLS: true
});


// ℹ️ Ajoutez ce bloc de vérification pour le débogage (maintenant après l'initialisation)
if (window.Echo) {
    console.log('[Chrono Debug] Laravel Echo est disponible. La connexion est en cours.');
} else {
    console.error('[Chrono Debug] Laravel Echo n\'est pas disponible. Vérifiez la configuration PUSHER.');
}