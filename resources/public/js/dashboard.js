// dashboard.js - Version corrigée
(function() {
    'use strict';
    
    var chartInitialized = false;
    
    function initDashboard() {
        console.log('🔄 Initialisation du dashboard...');
        
        // Initialiser le graphique des clients
        createGuestsChart();
        
        // Autres initialisations...
    }
    
    function createGuestsChart() {
        const ctx = document.getElementById('guestsChart');
        
        if (!ctx) {
            console.warn('⚠ Élément #guestsChart non trouvé');
            return;
        }
        
        // Vérifier que c'est bien un élément canvas
        if (ctx.tagName !== 'CANVAS') {
            console.error('❌ #guestsChart n\'est pas un élément canvas');
            return;
        }
        
        // Vérifier que Chart.js est chargé
        if (typeof Chart === 'undefined') {
            console.error('❌ Chart.js non chargé');
            // Charger Chart.js manuellement si nécessaire
            loadChartJS();
            return;
        }
        
        // Vérifier que le contexte 2D peut être obtenu
        if (!ctx.getContext) {
            console.error('❌ Canvas context non disponible');
            return;
        }
        
        try {
            var context = ctx.getContext('2d');
            if (!context) {
                console.error('❌ Impossible d\'obtenir le contexte canvas');
                return;
            }
            
            // Détruire le chart existant
            if (window.guestsChart && window.guestsChart.destroy) {
                window.guestsChart.destroy();
            }
            
            console.log('📊 Création du graphique...');
            
            // Créer le graphique
            window.guestsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                    datasets: [{
                        label: 'Clients',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 5
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            chartInitialized = true;
            console.log('✅ Graphique créé avec succès');
            
        } catch (error) {
            console.error('❌ Erreur création chart:', error);
        }
    }
    
    function loadChartJS() {
        if (window.chartScriptLoading) return;
        
        window.chartScriptLoading = true;
        console.log('📦 Chargement de Chart.js...');
        
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = function() {
            console.log('✅ Chart.js chargé');
            createGuestsChart();
        };
        script.onerror = function() {
            console.error('❌ Erreur chargement Chart.js');
        };
        document.head.appendChild(script);
    }
    
    function ajaxGetdailyGuestPerMonthData() {
        // Votre code AJAX existant...
        // Assurez-vous d'appeler createGuestsChart() après avoir reçu les données
    }
    
    // Démarrer quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboard);
    } else {
        initDashboard();
    }
    
})();