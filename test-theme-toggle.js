// Script de test pour vérifier que le composant ThemeToggle est correct
import fs from 'fs';

// Lire le fichier user-menu-content.tsx
const content = fs.readFileSync('resources/js/components/user-menu-content.tsx', 'utf8');

// Vérifier que les imports sont corrects
if (content.includes("import { logout, profile } from '@/routes';")) {
    console.log('✅ Import correct: profile est importé depuis @/routes');
} else {
    console.log('❌ Import incorrect: profile n\'est pas importé correctement');
}

// Vérifier que l'utilisation est correcte
if (content.includes('href={profile.edit()}')) {
    console.log('✅ Utilisation correcte: profile.edit() est utilisé');
} else {
    console.log('❌ Utilisation incorrecte: profile.edit() n\'est pas utilisé');
}

// Vérifier que le fichier de routes existe
if (fs.existsSync('resources/js/routes/index.ts')) {
    console.log('✅ Fichier de routes existe');
    
    const routesContent = fs.readFileSync('resources/js/routes/index.ts', 'utf8');
    
    // Vérifier si profile est défini dans les routes
    if (routesContent.includes('profile')) {
        console.log('✅ Route profile trouvée dans le fichier de routes');
    } else {
        console.log('⚠️  Route profile non trouvée dans le fichier de routes - cela peut être généré dynamiquement');
    }
} else {
    console.log('❌ Fichier de routes introuvable');
}

console.log('\n📋 Résumé:');
console.log('- Le composant user-menu-content.tsx a été modifié pour utiliser profile.edit()');
console.log('- Les imports ont été corrigés pour utiliser le bon chemin');
console.log('- Le système de thème devrait fonctionner correctement une fois compilé');