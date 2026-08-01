{{--
    Pop-ups modernes (SweetAlert2) + helpers window.confirmAction / window.promptAction.
    À inclure dans les layouts qui n'héritent PAS de template.master
    (ex. platform.layout, pages d'impression autonomes) pour bénéficier des mêmes
    jolies pop-ups que le back-office.
--}}
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    var isEn = (document.documentElement.lang || 'fr').slice(0, 2) === 'en';
    function brand() {
        var c = getComputedStyle(document.documentElement).getPropertyValue('--hotel-primary');
        return (c && c.trim()) || '#2e8540';
    }

    // alert() natif -> pop-up (icône déduite du texte)
    if (window.Swal) {
        var nativeAlert = window.alert.bind(window);
        window.alert = function (msg) {
            try {
                var s = String(msg == null ? '' : msg).trim();
                var icon = 'info';
                if (/^[❌⛔🚫]/.test(s) || /(erreur|error|échec|echec|invalide|impossible|refus)/i.test(s)) icon = 'error';
                else if (/^[✅🎉👍]/.test(s) || /(succ[eè]s|success|enregistr|cré[eé]|ajout|supprim|envoy|termin)/i.test(s)) icon = 'success';
                else if (/^[⚠]/.test(s)) icon = 'warning';
                var clean = s.replace(/^([❌⛔🚫✅🎉👍⚠]️?\s*)+/, '').trim();
                if (icon === 'success') {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: clean || s,
                        showConfirmButton: false, timer: 3200, timerProgressBar: true, heightAuto: false });
                } else {
                    Swal.fire({ icon: icon, text: clean || s, confirmButtonText: 'OK', confirmButtonColor: brand(), heightAuto: false });
                }
            } catch (e) { nativeAlert(msg); }
        };
    }

    // confirm() en attribut inline "return confirm('…')" -> pop-up de confirmation
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.Swal) return;
        var re = /^\s*return\s+confirm\(\s*(['"`])([\s\S]*?)\1\s*\)\s*;?\s*$/;
        ['onsubmit', 'onclick'].forEach(function (attr) {
            document.querySelectorAll('[' + attr + ']').forEach(function (el) {
                var m = (el.getAttribute(attr) || '').match(re);
                if (!m) return;
                var message = m[2];
                var isForm = el.tagName === 'FORM' || attr === 'onsubmit';
                el.removeAttribute(attr);
                el.addEventListener(isForm ? 'submit' : 'click', function (e) {
                    if (el.__swalOK) { el.__swalOK = false; return; }
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning', title: isEn ? 'Please confirm' : 'Confirmation', text: message,
                        showCancelButton: true, confirmButtonText: isEn ? 'Yes' : 'Oui',
                        cancelButtonText: isEn ? 'Cancel' : 'Annuler', confirmButtonColor: brand(),
                        cancelButtonColor: '#94a3b8', heightAuto: false
                    }).then(function (r) {
                        if (!r.isConfirmed) return;
                        if (isForm) { (el.tagName === 'FORM' ? el : el.closest('form')).submit(); }
                        else if (el.tagName === 'A' && el.getAttribute('href')) { window.location.href = el.href; }
                        else if (el.form) { el.form.submit(); }
                        else { el.__swalOK = true; el.click(); }
                    });
                });
            });
        });
    });

    // Confirmation JS à callback (remplace confirm() dans le code)
    window.confirmAction = function (message, onConfirm, opts) {
        opts = opts || {};
        if (!window.Swal) { if (window.confirm(message)) { if (typeof onConfirm === 'function') onConfirm(); } return; }
        Swal.fire({
            icon: opts.icon || 'warning', title: opts.title || (isEn ? 'Please confirm' : 'Confirmation'), text: message,
            showCancelButton: true, confirmButtonText: opts.confirmText || (isEn ? 'Yes' : 'Oui'),
            cancelButtonText: opts.cancelText || (isEn ? 'Cancel' : 'Annuler'),
            confirmButtonColor: opts.danger ? '#dc2626' : brand(), cancelButtonColor: '#94a3b8',
            reverseButtons: true, heightAuto: false
        }).then(function (r) {
            if (r.isConfirmed) { if (typeof onConfirm === 'function') onConfirm(); }
            else if (typeof opts.onCancel === 'function') opts.onCancel();
        });
    };

    // Saisie de texte (remplace prompt())
    window.promptAction = function (message, onValue, opts) {
        opts = opts || {};
        if (!window.Swal) { var v = window.prompt(message, opts.default || ''); if (typeof onValue === 'function') onValue(v); return; }
        Swal.fire({
            title: opts.title || message, input: opts.input || 'text', inputValue: opts.default || '',
            inputPlaceholder: opts.placeholder || '', showCancelButton: true,
            confirmButtonText: opts.confirmText || (isEn ? 'OK' : 'Valider'),
            cancelButtonText: opts.cancelText || (isEn ? 'Cancel' : 'Annuler'),
            confirmButtonColor: brand(), cancelButtonColor: '#94a3b8', reverseButtons: true, heightAuto: false,
            inputValidator: opts.required ? function (val) {
                if (!val) return (isEn ? 'This field is required' : 'Ce champ est requis');
            } : undefined
        }).then(function (r) {
            if (typeof onValue === 'function') onValue(r.isConfirmed ? r.value : null);
        });
    };
})();
</script>
