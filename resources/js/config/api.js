// Configuration API pour le frontend
// U-Cup Tournament - Elmish Moukouanga

const API_CONFIG = {
    // URL de base de l'API (sera remplacée par Vercel)
    BASE_URL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api',
    
    // Endpoints principaux
    ENDPOINTS: {
        MATCHES: '/matches',
        TEAMS: '/teams',
        PLAYERS: '/players',
        STANDINGS: '/standings',
        GALLERY: '/gallery',
        AUTH: {
            LOGIN: '/login',
            LOGOUT: '/logout',
            ME: '/me'
        }
    },
    
    // Configuration des requêtes
    REQUEST_CONFIG: {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include' // Pour les cookies
    },
    
    // Méthodes HTTP
    METHODS: {
        GET: 'GET',
        POST: 'POST',
        PUT: 'PUT',
        PATCH: 'PATCH',
        DELETE: 'DELETE'
    },
    
    // Gestion des erreurs
    ERROR_HANDLING: {
        401: 'Non autorisé - Veuillez vous connecter',
        403: 'Accès interdit',
        404: 'Ressource non trouvée',
        500: 'Erreur serveur - Veuillez réessayer plus tard',
        NETWORK: 'Problème de connexion - Vérifiez votre connexion internet'
    },
    
    // Méthode pour construire l'URL complète
    buildUrl: (endpoint) => {
        return `${API_CONFIG.BASE_URL}${endpoint}`;
    },
    
    // Méthode pour gérer les requêtes
    request: async (endpoint, method = API_CONFIG.METHODS.GET, data = null) => {
        const url = API_CONFIG.buildUrl(endpoint);
        const config = {...API_CONFIG.REQUEST_CONFIG, method};
        
        if (data) {
            config.body = method === API_CONFIG.METHODS.GET 
                ? null 
                : JSON.stringify(data);
        }
        
        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(
                    errorData.message || 
                    API_CONFIG.ERROR_HANDLING[response.status] ||
                    `Erreur ${response.status}`
                );
            }
            
            return await response.json();
        } catch (error) {
            if (error.message.includes('Failed to fetch')) {
                throw new Error(API_CONFIG.ERROR_HANDLING.NETWORK);
            }
            throw error;
        }
    }
};

export default API_CONFIG;