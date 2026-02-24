<?php
    // Récupérer la session active si l'utilisateur est connecté
    $activeSession = null;
    if (auth()->check()) {
        $activeSession = \App\Models\CashierSession::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
    }
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <link rel="icon" href="<?php echo e(asset('img/logo/sip.png')); ?>">
    
    
    <?php echo app('Illuminate\Foundation\Vite')('resources/sass/app.scss'); ?>

    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <title><?php echo $__env->yieldContent('title'); ?> - Hotel Admin</title>
    
    <?php echo $__env->yieldContent('head'); ?>
</head>

<body class="sidebar-layout">
    <main>
        <!-- Enhanced Modal -->
        <div class="modal fade" id="main-modal" tabindex="-1" aria-labelledby="main-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                    <div class="modal-header bg-light border-0">
                        <h1 class="modal-title fs-5 fw-bold" id="main-modalLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button id="btn-modal-close" type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button id="btn-modal-save" type="button" class="btn btn-hotel-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Header -->
        <?php echo $__env->make('template.include._mobile-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="d-flex vh-100" id="wrapper">
            <!-- Desktop Sidebar -->
            <?php echo $__env->make('template.include._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Content -->
            <div id="page-content-wrapper" class="flex-fill">
                <div class="p-3 h-100">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap CDN (backup - garantit le chargement avant les scripts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite / App JS -->
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Initialize Tooltips and Components -->
    <script>
        (function() {
            'use strict';
            
            var bootstrapReady = false;
            var maxAttempts = 10;
            var attempt = 0;
            
            function checkBootstrap() {
                attempt++;
                
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip && bootstrap.Collapse && bootstrap.Offcanvas) {
                    console.log('✓ Bootstrap chargé après ' + attempt + ' tentative(s)');
                    bootstrapReady = true;
                    initComponents();
                    return;
                }
                
                if (attempt >= maxAttempts) {
                    console.warn('⚠ Bootstrap non trouvé après ' + maxAttempts + ' tentatives');
                    loadBootstrapManually();
                    return;
                }
                
                setTimeout(checkBootstrap, 100);
            }
            
            function loadBootstrapManually() {
                console.log('📦 Chargement manuel de Bootstrap...');
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
                script.onload = function() {
                    console.log('✓ Bootstrap chargé manuellement');
                    bootstrapReady = true;
                    initComponents();
                };
                script.onerror = function() {
                    console.error('❌ Erreur chargement Bootstrap');
                };
                document.head.appendChild(script);
            }
            
            function initComponents() {
                if (!bootstrapReady) {
                    console.warn('Bootstrap pas encore prêt');
                    return;
                }
                
                // 1. Initialize Bootstrap tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    try {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    } catch (e) {
                        console.warn('⚠ Erreur tooltip:', e);
                        return null;
                    }
                }).filter(function(tooltip) {
                    return tooltip !== null;
                });
                
                console.log('🎯 ' + tooltipList.length + ' tooltip(s) initialisé(s)');
                
                // 2. Initialize Bootstrap toasts if any
                var toastElList = [].slice.call(document.querySelectorAll('.toast'));
                if (toastElList.length > 0) {
                    var toastList = toastElList.map(function(toastEl) {
                        try {
                            return new bootstrap.Toast(toastEl);
                        } catch (e) {
                            console.warn('⚠ Erreur toast:', e);
                            return null;
                        }
                    }).filter(function(toast) {
                        return toast !== null;
                    });
                    console.log('🍞 ' + toastList.length + ' toast(s) initialisé(s)');
                }
                
                // 3. Sidebar toggle functionality for mobile
                const toggleBtn = document.getElementById('sidebar-toggle');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        document.getElementById('sidebar-wrapper').classList.toggle('collapsed');
                    });
                }

                // 4. Mobile dropdown functionality for offcanvas sidebar
                const mobileDropdownToggles = document.querySelectorAll('#mobileOffcanvas .nav-toggle[data-bs-toggle="collapse"]');

                mobileDropdownToggles.forEach(toggle => {
                    const targetId = toggle.getAttribute('data-bs-target');
                    const targetElement = document.querySelector(targetId);
                    const arrow = toggle.querySelector('.nav-arrow');

                    if (!targetElement) {
                        console.warn('❌ Élément cible non trouvé:', targetId);
                        return;
                    }

                    // Set initial state based on whether submenu is shown (server-side rendered)
                    if (targetElement.classList.contains('show')) {
                        toggle.setAttribute('aria-expanded', 'true');
                        if (arrow) {
                            arrow.style.transform = 'rotate(180deg)';
                        }
                    } else {
                        toggle.setAttribute('aria-expanded', 'false');
                        if (arrow) {
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    }

                    // Handle click events for manual toggle
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Get or create Bootstrap Collapse instance
                        try {
                            const collapse = bootstrap.Collapse.getOrCreateInstance(targetElement, {
                                toggle: false
                            });

                            // Toggle the collapse
                            collapse.toggle();
                        } catch (e) {
                            console.error('❌ Erreur collapse:', e);
                        }
                    });

                    // Listen to Bootstrap collapse events to update aria and arrow
                    targetElement.addEventListener('shown.bs.collapse', function() {
                        toggle.setAttribute('aria-expanded', 'true');
                        if (arrow) {
                            arrow.style.transform = 'rotate(180deg)';
                        }
                    });

                    targetElement.addEventListener('hidden.bs.collapse', function() {
                        toggle.setAttribute('aria-expanded', 'false');
                        if (arrow) {
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    });
                });

                // 5. Auto-close mobile menu when clicking nav links
                const mobileNavLinks = document.querySelectorAll('#mobileOffcanvas .nav-item:not(.dropdown-nav), #mobileOffcanvas .nav-subitem');
                const offcanvasElement = document.getElementById('mobileOffcanvas');

                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (offcanvasElement && window.innerWidth <= 768) {
                            try {
                                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                                if (offcanvas) {
                                    offcanvas.hide();
                                }
                            } catch (e) {
                                console.warn('⚠ Erreur fermeture offcanvas:', e);
                            }
                        }
                    });
                });
                
                // 6. Initialize modals if any
                var modalElList = [].slice.call(document.querySelectorAll('.modal'));
                if (modalElList.length > 0) {
                    console.log('📱 ' + modalElList.length + ' modal(s) détecté(s)');
                }
                
                console.log('✅ Initialisation complète terminée');
            }
            
            // Start initialization when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('📄 DOM chargé, vérification Bootstrap...');
                    checkBootstrap();
                });
            } else {
                // DOM already loaded
                console.log('📄 DOM déjà chargé, vérification Bootstrap...');
                checkBootstrap();
            }
            
            // Expose bootstrap globally for debugging
            window.debugBootstrap = function() {
                console.log('🔍 DEBUG Bootstrap:');
                console.log('- Type:', typeof bootstrap);
                console.log('- Tooltip:', bootstrap && bootstrap.Tooltip ? '✓' : '✗');
                console.log('- Collapse:', bootstrap && bootstrap.Collapse ? '✓' : '✗');
                console.log('- Offcanvas:', bootstrap && bootstrap.Offcanvas ? '✓' : '✗');
                console.log('- Modal:', bootstrap && bootstrap.Modal ? '✓' : '✗');
                console.log('- Toast:', bootstrap && bootstrap.Toast ? '✓' : '✗');
            };
            
        })();
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    
   <script>
    (function() {
        'use strict';
        
        // Protection contre la déconnexion avec session active
        <?php if($activeSession): ?>
        console.log('🔒 Session active détectée #<?php echo e($activeSession->id); ?> - Déconnexion bloquée');
        
        // Bloquer tous les formulaires de déconnexion
        document.addEventListener('click', function(e) {
            const isLogoutButton = e.target.closest('form[action*="logout"] button, a[href*="logout"], .nav-link:has(i.fa-sign-out-alt)');
            
            if (isLogoutButton) {
                e.preventDefault();
                e.stopPropagation();
                
                Swal.fire({
                    title: '⚠️ Session Active',
                    html: 'Vous avez une session active <strong>#<?php echo e($activeSession->id); ?></strong>.<br><br>' +
                          'Veuillez la clôturer avant de vous déconnecter.',
                    icon: 'warning',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Compris',
                    showCancelButton: true,
                    cancelButtonText: 'Aller à la session',
                    cancelButtonColor: '#3b82f6'
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = '<?php echo e(route("cashier.sessions.show", $activeSession)); ?>';
                    }
                });
                
                return false;
            }
        }, true);
        
        // ❌ SUPPRIMER COMPLÈTEMENT LE beforeunload
        window.onbeforeunload = null;
        
        // Désactiver visuellement les liens de déconnexion
        document.addEventListener('DOMContentLoaded', function() {
            const logoutElements = document.querySelectorAll('form[action*="logout"], a[href*="logout"], .nav-link:has(i.fa-sign-out-alt)');
            logoutElements.forEach(el => {
                el.style.opacity = '0.6';
                el.style.pointerEvents = 'none';
                el.title = 'Session active - Déconnexion impossible';
            });
        });
        
        <?php else: ?>
        // ✅ PAS de session active : navigation normale
        window.onbeforeunload = null;
        console.log('✅ Aucune session active - navigation normale');
        <?php endif; ?>
    })();
</script>

    <?php echo $__env->yieldContent('footer'); ?>
</body>

</html><?php /**PATH C:\Users\HP ELITEBOOK\Desktop\dev\Laravel-Hotel-main\resources\views/template/master.blade.php ENDPATH**/ ?>