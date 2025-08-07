// Script de debug à injecter dans la console du navigateur
// Copiez-collez ce code dans la console F12 de votre navigateur

console.log('=== DIAGNOSTIC COMPLET DU FORMULAIRE ===');

// 1. Vérifier les éléments
const form = document.getElementById('actualiteForm');
const button = document.querySelector('button[type="submit"]');

console.log('Form trouvé:', !!form);
console.log('Button trouvé:', !!button);

if (form) {
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
}

if (button) {
    console.log('Button text:', button.textContent);
    console.log('Button disabled:', button.disabled);
    console.log('Button style display:', getComputedStyle(button).display);
    console.log('Button style pointer-events:', getComputedStyle(button).pointerEvents);
    
    // Test de clic direct
    console.log('=== TEST CLIC DIRECT ===');
    button.addEventListener('click', function(e) {
        console.log('CLIC DÉTECTÉ !', e);
    });
    
    // Forcer un clic après 2 secondes
    setTimeout(() => {
        console.log('=== CLIC FORCÉ ===');
        button.click();
    }, 2000);
}

// 2. Vérifier les erreurs JavaScript
window.addEventListener('error', function(e) {
    console.error('ERREUR JS DÉTECTÉE:', e.error);
});

// 3. Vérifier les styles CSS qui pourraient bloquer
if (button) {
    const rect = button.getBoundingClientRect();
    console.log('Position du bouton:', rect);
    console.log('Le bouton est visible:', rect.width > 0 && rect.height > 0);
}

console.log('=== FIN DIAGNOSTIC ===');
