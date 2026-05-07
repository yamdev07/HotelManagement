<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} — Cactus</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            background: #fff;
            font-size: 13px;
        }
        .page {
            max-width: 680px;
            margin: 30px auto;
            padding: 35px 40px;
            border: 1px solid #ddd;
        }

        /* ── Header ── */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 18px;
            border-bottom: 3px solid #1a472a;
            margin-bottom: 22px;
        }
        .hotel-name { font-size: 1.6rem; font-weight: 800; color: #1a472a; letter-spacing: 2px; }
        .hotel-sub  { font-size: 0.72rem; color: #777; margin-top: 3px; }
        .inv-ref    { text-align: right; }
        .inv-ref h2 { font-size: 1.25rem; font-weight: 700; color: #1a472a; }
        .inv-ref p  { font-size: 0.77rem; color: #666; margin-top: 2px; }

        /* ── Meta Grid ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 22px;
        }
        .meta-box {
            background: #f8f8f8;
            border-radius: 6px;
            padding: 12px 14px;
        }
        .meta-box h4 {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin-bottom: 9px;
        }
        .meta-box table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        .meta-box td { padding: 3px 0; vertical-align: top; }
        .meta-box td:first-child { color: #666; width: 42%; }

        /* ── Items Table ── */
        .items-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 16px; }
        .items-table thead tr { background: #1a472a; color: #fff; }
        .items-table th { padding: 9px 8px; text-align: left; font-weight: 600; }
        .items-table td { padding: 9px 8px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .items-table tfoot tr { background: #f1f1f1; }
        .items-table tfoot th { padding: 11px 8px; font-size: 0.9rem; }

        /* ── Notes ── */
        .notes-block {
            margin-top: 14px;
            padding: 10px 13px;
            background: #f9f9f9;
            border-left: 3px solid #1a472a;
            font-size: 0.82rem;
            color: #444;
        }

        /* ── Footer ── */
        .inv-footer {
            text-align: center;
            padding-top: 16px;
            border-top: 1px dashed #ccc;
            font-size: 0.74rem;
            color: #888;
            margin-top: 16px;
        }

        /* ── Actions screen only ── */
        .actions {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin: 28px auto 0;
        }
        .btn-close {
            padding: 10px 36px;
            background: transparent;
            color: #1a472a;
            border: 2px solid #1a472a;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            font-family: Arial, sans-serif;
            text-decoration: none;
        }
        .btn-close:hover { background: #f0f7f3; }

        /* ── Print styles ── */
        @media print {
            /* margin:0 supprime les en-têtes/pieds navigateur (URL, pagination) */
            @page { size: A5 portrait; margin: 0; }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 10mm; /* compense le margin:0 de @page */
            }
            .page  { border: none; margin: 0; padding: 0; max-width: 100%; }
            .actions { display: none !important; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- En-tête --}}
    <div class="inv-header">
        <div>
            <div class="hotel-name">CACTUS</div>
            <div class="hotel-sub">Restaurant</div>
        </div>
        <div class="inv-ref">
            <h2>FACTURE</h2>
            <p>N° {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Méta --}}
    <div class="meta-grid">
        <div class="meta-box">
            <h4>Client</h4>
            <table>
                <tr>
                    <td>Nom</td>
                    <td><strong id="customer-name-display">{{ $order->customer_name ?? 'Client' }}</strong></td>
                </tr>
                @if($order->room_number)
                <tr>
                    <td>Chambre</td>
                    <td><strong>{{ $order->room_number }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
        <div class="meta-box">
            <h4>Commande</h4>
            <table>
                <tr>
                    <td>Référence</td>
                    <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Paiement</td>
                    <td>
                        @php
                            $methods = [
                                'cash'        => 'Espèces',
                                'card'        => 'Carte Bancaire',
                                'mobile_money'=> 'Mobile Money',
                                'fedapay'     => 'Fedapay',
                                'transfer'    => 'Virement',
                                'check'       => 'Chèque',
                                'room_charge' => 'Facture de la chambre',
                            ];
                        @endphp
                        {{ $methods[$order->payment_method] ?? $order->payment_method ?? 'Non spécifié' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Articles --}}
    <table class="items-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th style="text-align:center;">Qté</th>
                <th style="text-align:right;">Prix unit.</th>
                <th style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->menu->name ?? 'Plat' }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                <td style="text-align:right; font-weight:700;">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right; padding:11px 8px;">TOTAL À RÉGLER</th>
                <th style="text-align:right; padding:11px 8px; font-size:1rem; color:#1a472a;">
                    {{ number_format($order->total, 0, ',', ' ') }} FCFA
                </th>
            </tr>
        </tfoot>
    </table>

    {{-- Notes --}}
    @if($order->notes)
    <div class="notes-block">
        <strong>Notes :</strong> {{ $order->notes }}
    </div>
    @endif

    {{-- Pied de page --}}
    <div class="inv-footer">
        <p>Merci pour votre commande — Restaurant Cactus</p>
        <p style="margin-top:4px;">Imprimé le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="actions">
        <button class="btn-close" onclick="window.close()">✕&nbsp; Fermer</button>
    </div>

    <script>
        window.onload = function () {
            // Demander le nom si vide ou générique
            let currentName = "{{ $order->customer_name }}";
            let namePrompted = false;

            if (!currentName || currentName === "" || currentName.toLowerCase().includes('client table') || currentName.toLowerCase().includes('room service')) {
                let name = prompt("Veuillez saisir le nom du client pour cette facture (Laisser vide pour ignorer) :", "");
                if (name && name.trim() !== "") {
                    document.getElementById('customer-name-display').innerText = name;
                    namePrompted = true;
                    
                    // Sauvegarder dans la commande via API
                    fetch("{{ route('restaurant.orders.update-customer-name', $order->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ customer_name: name })
                    }).then(() => {
                         // Déclencher l'impression après la sauvegarde (ou presque)
                         setTimeout(triggerPrint, 300);
                    });
                }
            }

            if (!namePrompted) {
                triggerPrint();
            }

            function triggerPrint() {
                setTimeout(function () {
                    document.execCommand('print', false, null) || print();
                }, 300);
            }
        };
    </script>

</div>
</body>
</html>
