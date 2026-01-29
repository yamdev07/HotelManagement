import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Version pour développement avec Pusher.com
window.Echo = new Echo({
    broadcaster: "pusher", // ⚠️ CHANGEZ "reverb" par "pusher"
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'eu',
    forceTLS: true, // ⚠️ IMPORTANT : true pour Pusher.com
    enabledTransports: ["ws", "wss"],
    
    // Options supplémentaires pour debug
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        }
    }
});

console.log('✅ Pusher configuré avec succès', {
    key: import.meta.env.VITE_PUSHER_APP_KEY?.substring(0, 10) + '...',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER
});