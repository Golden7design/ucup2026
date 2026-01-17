import React, { useState, useEffect } from 'react';

// Constantes de temps de jeu en secondes
const FIRST_HALF_SECONDS = 45 * 60;
const HALF_TIME_BREAK_SECONDS = 15 * 60; 
const FULL_GAME_SECONDS = 90 * 60;

interface LiveChronoProps {
    status: string;
    startTime: string | null; // Heure de début au format ISO (depuis le contrôleur Laravel)
}

// Fonction de formatage pour calculer le temps à afficher
const formatChrono = (status: string, startTime: string | null): string => {
    // Si le match est terminé, annulé ou n'a pas commencé, afficher le statut ou le placeholder
    if (!startTime || status === 'finished' || status === 'scheduled' || status === 'cancelled') {
        return status === 'halftime' ? 'MI-TEMPS' : ' - - ';
    }

    const startTimestamp = new Date(startTime).getTime();
    const nowTimestamp = new Date().getTime();
    
    // Temps réel écoulé depuis le coup d'envoi
    const totalElapsedSeconds = Math.floor((nowTimestamp - startTimestamp) / 1000);
    
    let displayString = ' - - ';

    // Le statut Halftime est géré par l'admin (Capture 4)
    if (status === 'halftime') {
        // Optionnellement, vous pouvez calculer si le temps de pause est écoulé ici,
        // mais le plus simple est de se fier au statut de l'admin.
        return 'MI-TEMPS';
    }

    // Le statut doit être 'live' pour que le chrono tourne
    if (status === 'live') {
        
        let gameSeconds = totalElapsedSeconds;

        // Si le match est en 2e mi-temps, nous devons soustraire la durée de la pause.
        // Si le temps réel (depuis le coup d'envoi) dépasse 45 min + 15 min de pause (60 min)
        if (totalElapsedSeconds >= FIRST_HALF_SECONDS + HALF_TIME_BREAK_SECONDS) {
            gameSeconds = totalElapsedSeconds - HALF_TIME_BREAK_SECONDS;
        }

        const currentMinute = Math.floor(gameSeconds / 60);

        if (gameSeconds < FIRST_HALF_SECONDS) {
             // 1. Première mi-temps (0' à 45')
            displayString = `${currentMinute + 1}'`;

        } else if (gameSeconds >= FIRST_HALF_SECONDS && gameSeconds < FULL_GAME_SECONDS) {
             // 2. Deuxième mi-temps (45' à 90')
             displayString = `${currentMinute + 1}'`;

        } else if (gameSeconds >= FULL_GAME_SECONDS) {
            // 3. Temps additionnel après 90'
            const addedTimeSeconds = gameSeconds - FULL_GAME_SECONDS;
            const addedMinutes = Math.floor(addedTimeSeconds / 60); 
            displayString = `90'+${addedMinutes + 1}`; 
        }
        
    }

    return displayString;
};

// Composant Chronomètre
const LiveChrono: React.FC<LiveChronoProps> = ({ status, startTime }) => {
    // État local qui force le rendu du composant chaque seconde
    const [currentTime, setCurrentTime] = useState(new Date());

    useEffect(() => {
        // Le chrono ne tourne que si le statut est 'live'
        if (status.toLowerCase() !== 'live' || !startTime) {
            return;
        }

        const intervalId = setInterval(() => {
            setCurrentTime(new Date()); // Met à jour l'état pour forcer le recalcul
        }, 1000);

        // Nettoyage de l'intervalle lorsque le composant se démonte
        return () => clearInterval(intervalId);
    }, [status, startTime]); 

    // Appel de la fonction de formatage pour obtenir la chaîne de temps
    const timeString = formatChrono(status.toLowerCase(), startTime);

    return (
        <span className="text-red-500 font-bold ml-1">
            {timeString}
        </span>
    );
};

export default LiveChrono;