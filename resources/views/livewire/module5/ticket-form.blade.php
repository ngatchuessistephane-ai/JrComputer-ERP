<div class="ticket-form-wrap">
    <style>
        /* ============================================================
           TICKET FORM — SENIOR EDITION
           Structure claire, logique métier, expérience fluide
           ============================================================ */

        .ticket-form-wrap {
            zoom: 0.90;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ─── HEADER ─── */
        .tf-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .tf-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .tf-header-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 17px;
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 12px rgba(26,122,60,0.28);
        }
        .tf-header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ─── BOUTONS ─── */
        .tf-btn {
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }
        .tf-btn-ghost {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        .tf-btn-ghost:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-xlight);
        }
        .tf-btn-primary {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            color: white;
            box-shadow: 0 4px 12px rgba(26,122,60,0.25);
        }
        .tf-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(26,122,60,0.35);
            color: white;
        }
        .tf-btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .tf-btn-success {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            box-shadow: 0 4px 12px rgba(16,185,129,0.25);
        }
        .tf-btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16,185,129,0.35);
            color: white;
        }
        .tf-btn-danger {
            background: transparent;
            border: 1px solid rgba(220,38,38,0.3);
            color: #dc2626;
        }
        .tf-btn-danger:hover {
            background: rgba(220,38,38,0.08);
            border-color: #dc2626;
        }

        /* ─── FLASH MESSAGE ─── */
        .tf-flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            border: 1px solid;
            animation: tfFadeSlide 0.3s ease;
        }
        .tf-flash.success {
            background: rgba(26,122,60,0.08);
            border-color: rgba(26,122,60,0.2);
            color: var(--brand-green);
        }
        @keyframes tfFadeSlide {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── CARD ─── */
        .tf-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        /* ─── FORM GROUP ─── */
        .tf-group {
            margin-bottom: 16px;
        }
        .tf-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 5px;
        }
        .tf-group label .required {
            color: #dc2626;
            margin-left: 2px;
        }
        .tf-control {
            width: 100%;
            padding: 9px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-page);
            color: var(--text-primary);
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .tf-control:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .tf-control.is-invalid {
            border-color: #dc2626;
        }
        .tf-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }
        .tf-error {
            font-size: 11px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
        .tf-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .tf-hint i {
            font-size: 12px;
        }

        /* ─── GRID ─── */
        .tf-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 20px;
        }
        .tf-grid-full {
            grid-column: 1 / -1;
        }
        @media (max-width: 768px) {
            .tf-grid {
                grid-template-columns: 1fr;
            }
            .tf-grid-full {
                grid-column: 1;
            }
        }

        /* ─── DIVIDER ─── */
        .tf-divider {
            border: 0;
            border-top: 1.5px solid var(--border-color);
            margin: 20px 0;
        }

        /* ─── SECTION HEADER ─── */
        .tf-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin: 8px 0 12px 0;
        }
        .tf-section-header h5 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tf-section-header h5 i {
            color: var(--brand-orange);
        }

        /* ─── TABLE ─── */
        .tf-table-wrap {
            overflow-x: auto;
            margin-top: 8px;
        }
        .tf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .tf-table th {
            background: var(--bg-page);
            padding: 10px 14px;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
        }
        .tf-table td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
        }
        .tf-table tr:last-child td {
            border-bottom: none;
        }
        .tf-table tr:hover td {
            background: var(--bg-hover);
        }
        .tf-table .text-right {
            text-align: right;
        }
        .tf-table .text-center {
            text-align: center;
        }
        .tf-empty {
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12.5px;
            background: var(--bg-page);
            border-radius: 10px;
        }

        /* ─── INVOICE SECTION ─── */
        .tf-invoice-section {
            background: var(--bg-page);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 18px 20px;
            margin-top: 2px;
            border-left: 4px solid var(--brand-orange);
        }
        .tf-invoice-section .tf-section-header h5 i {
            color: var(--brand-orange);
        }
        .tf-invoice-alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 12px;
        }
        .tf-invoice-alert.warning {
            background: rgba(240,125,0,0.08);
            border: 1px solid rgba(240,125,0,0.2);
            color: #b45309;
        }
        .tf-invoice-alert.success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            color: #065f46;
        }

        /* ─── BADGE ─── */
        .tf-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .tf-badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .tf-badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .tf-badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ─── MODAL ─── */
        .tf-modal .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            overflow: hidden;
        }
        .tf-modal .modal-header {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            border-bottom: none;
            padding: 16px 22px;
        }
        .tf-modal .modal-title {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .tf-modal .btn-close {
            filter: brightness(0) invert(1);
        }
        .tf-modal .modal-body {
            padding: 22px 24px;
        }
        .tf-modal .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 576px) {
            .tf-card {
                padding: 16px;
            }
            .tf-header h2 {
                font-size: 17px;
            }
            .tf-header-icon {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }

        /* ─── DARK MODE ─── */
        [data-theme="dark"] .tf-invoice-section {
            background: rgba(26,122,60,0.06);
        }
        [data-theme="dark"] .tf-invoice-alert.warning {
            background: rgba(240,125,0,0.12);
            color: #fdba74;
        }
        [data-theme="dark"] .tf-invoice-alert.success {
            background: rgba(16,185,129,0.12);
            color: #6ee7b7;
        }
        [data-theme="dark"] .tf-badge-warning {
            background: rgba(251,191,36,0.18);
            color: #fcd34d;
        }
        [data-theme="dark"] .tf-badge-success {
            background: rgba(16,185,129,0.18);
            color: #6ee7b7;
        }
        [data-theme="dark"] .tf-badge-info {
            background: rgba(59,130,246,0.18);
            color: #93c5fd;
        }
    </style>

    {{-- ─── FLASH ─── --}}
    @if(session()->has('message'))
        <div class="tf-flash success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('message') }}
        </div>
    @endif

    {{-- ─── HEADER ─── --}}
    <div class="tf-header">
        <h2>
            <span class="tf-header-icon"><i class="bi bi-tools"></i></span>
            
            {{-- ✅ Condition d'affichage du titre : "Clôturer le ticket" si le statut n'est pas "restituted" --}}
            @if($ticketId && $status !== 'restituted')
                Clôturer le ticket
            @elseif($ticketId && $status === 'restituted')
                Ticket restitué
            @else
                Nouveau ticket SAV
            @endif

            @if($ticketId)
                <span class="tf-badge tf-badge-info">#{{ $ticket->ticket_number ?? $ticketId }}</span>
            @endif
        </h2>
        <div class="tf-header-actions">
            <a href="{{ route('module5.tickets.index') }}" class="tf-btn tf-btn-ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    {{-- ─── FORMULAIRE ─── --}}
    <div class="tf-card">
        <form wire:submit.prevent="save">
         {{-- ✅ Message informatif si le ticket est déjà restitué --}}
            @if($status === 'restituted')
                <div style="margin-top: 12px; padding: 12px 16px; background: rgba(7, 148, 45, 0.08); border: 1px solid rgba(16,185,129,0.2); border-radius: 10px; color: #dea90a; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-check-circle-fill" style="font-size: 16px;"></i>
                    <span>Ce ticket a déjà été <strong style="color:green">restitué</strong>. Aucune modification supplémentaire n'est requise.</span>
                </div>
            @endif

             {{-- SECTION FACTURE (Hors garantie + Terminé) --}}
            @if($ticketId && !$is_warranty && $status === 'completed')
                <div class="tf-invoice-section">
                    <div class="tf-section-header">
                        <h5><i class="bi bi-receipt"></i> Génération de facture automatiquement</h5>
                        <span class="tf-badge tf-badge-warning">Hors garantie</span>
                    </div>

                    <div class="tf-invoice-alert warning">
                        <i class="bi bi-info-circle"></i>
                        <strong>Ticket hors garantie en état "Terminé"</strong>
                        <br>Saisissez les frais et passez le statut à <strong>"Restitué"</strong> pour générer la facture.
                    </div>

                    <div class="tf-grid">
                        <div class="tf-group">
                            <label>Frais de diagnostic</label>
                            <input type="number" wire:model="diagnostic_fee" class="tf-control" step="1000" min="0">
                            <span class="tf-hint">Forfait : 10 000 FCFA</span>
                        </div>

                        <div class="tf-group">
                            <label>Main d'œuvre</label>
                            <input type="number" wire:model="labor_cost" class="tf-control" step="1000" min="0" placeholder="Veillez remplir le montant de la main d'œuvre Ex: 15 000">
                            <span class="tf-hint">Saisir le montant total</span>
                        </div>
                    </div>

                    <div class="tf-grid">
                        <div class="tf-group">
                            <label>Statut final</label>
                            <select wire:model="status" class="tf-control" style="border-color: var(--brand-orange);">
                                <option value="completed"> Terminé</option>
                                <option value="restituted" style="font-weight:700;color:var(--brand-green);">✅📦 Restitué </option>
                            </select>
                            <span class="tf-hint">Changer en "Restitué" pour générer la facture</span>
                        </div>
                    </div>

                    {{-- @if($generate_invoice_on_update)
                        <div class="tf-invoice-alert success" style="margin-top: 12px;">
                            <i class="bi bi-check-circle-fill"></i>
                            <strong>✅ Une facture sera générée automatiquement</strong>
                            <br><small>La facture apparaîtra dans la liste des factures (Module 3).</small>
                        </div>
                    @endif--}}
                </div>
            @endif 
               <br>

            {{-- ROW 1 : Client & Produit --}}
            <div class="tf-grid">
                <div class="tf-group">
                    <label>Client <span class="required">*</span></label>
                    <select wire:model="customer_id" class="tf-control @error('customer_id') is-invalid @enderror">
                        <option value="">— Sélectionner un client —</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} {{ $c->phone ? '· '.$c->phone : '' }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="tf-error">{{ $message }}</span> @enderror
                </div>

                <div class="tf-group">
                    <label>Produit associé</label>
                    <select wire:model="product_id" class="tf-control">
                        <option value="">— Non renseigné —</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} <span style="color:var(--text-muted);font-size:11px;">({{ $p->reference }})</span></option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ROW 2 : Série, Modèle, Priorité --}}
            <div class="tf-grid">
                <div class="tf-group">
                    <label>Numéro de série</label>
                    <input type="text" wire:model="serial_number" class="tf-control" placeholder="Ex: SN-2024-001">
                </div>

                <div class="tf-group">
                    <label>Modèle de l'appareil</label>
                    <input type="text" wire:model="device_model" class="tf-control" placeholder="Ex: HP EliteBook 840 G7">
                </div>
            </div>

            {{-- ROW 3 : Priorité & Technicien --}}
            <div class="tf-grid">
                <div class="tf-group">
                    <label>Priorité</label>
                    <select wire:model="priority" class="tf-control">
                        <option value="low">🟢 Basse</option>
                        <option value="medium">🟡 Moyenne</option>
                        <option value="high">🟠 Haute</option>
                        <option value="critical">🔴 Critique</option>
                    </select>
                </div>

                <div class="tf-group">
                    <label>Assigner à un technicien</label>
                    <select wire:model="assigned_to" class="tf-control">
                        <option value="">— Non assigné —</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ROW 4 : Statut (conditionnel) --}}
            @if(!$ticketId || ($ticketId && $is_warranty))
                <div class="tf-grid">
                    <div class="tf-group">
                        <label>Statut</label>
                        <select wire:model="status" class="tf-control">
                            <option value="pending"> En attente</option>
                            <option value="assigned"> Assigné</option>
                            <option value="diagnosing"> Diagnostic</option>
                            <option value="repairing"> En réparation</option>
                            <option value="completed"> Terminé</option>
                            <option value="restituted"> Restitué</option>
                        </select>
                        <span class="tf-hint">
                            <i class="bi bi-shield-check" style="color:var(--brand-green);"></i>
                            Appareil sous garantie
                        </span>
                    </div>
                </div>
            @endif

           

            {{-- ROW 5 : Description (pleine largeur) --}}
            <div class="tf-group" style="margin-top: 8px;">
                <label>Description de la panne <span class="required">*</span></label>
                <textarea wire:model="description_failure" rows="4" class="tf-control @error('description_failure') is-invalid @enderror" placeholder="Décrivez précisément la panne constatée..."></textarea>
                @error('description_failure') <span class="tf-error">{{ $message }}</span> @enderror
            </div>

            {{-- ─── DIVIDER ─── --}}
            <hr class="tf-divider">

            {{-- ─── PIÈCES DÉTACHÉES ─── --}}
            <div class="tf-section-header">
                <h5><i class="bi bi-puzzle"></i> Pièces détachées utilisées</h5>
                <button type="button" class="tf-btn tf-btn-primary" data-bs-toggle="modal" data-bs-target="#addPartModal">
                    <i class="bi bi-plus-lg"></i> Ajouter une pièce
                </button>
            </div>

            <div class="tf-table-wrap">
                @if(count($parts) > 0)
                    <table class="tf-table">
                        <thead>
                            <tr>
                                <th>Pièce</th>
                                <th class="text-right" style="width:60px;">Qté</th>
                                <th class="text-right" style="width:120px;">Prix unitaire</th>
                                <th class="text-right" style="width:120px;">Total</th>
                                <th style="width:40px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parts as $index => $p)
                            <tr>
                                <td>{{ $p['spare_part_name'] }}</td>
                                <td class="text-right"><strong>{{ $p['quantity'] }}</strong></td>
                                <td class="text-right">{{ number_format($p['unit_price'], 0, ',', ' ') }} FCFA</td>
                                <td class="text-right" style="font-weight:600;color:var(--brand-green);">{{ number_format($p['total'], 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <button type="button" wire:click="removePart({{ $index }})" class="tf-btn tf-btn-danger" style="padding:4px 8px;font-size:12px;border-radius:6px;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="tf-empty">
                        <i class="bi bi-box-seam" style="font-size:20px;display:block;margin-bottom:6px;opacity:0.4;"></i>
                        Aucune pièce ajoutée pour le moment.
                    </div>
                @endif
            </div>

            {{-- ─── BOUTON VALIDATION ─── --}}
            <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
                {{-- ✅ Condition d'affichage du bouton principal : cacher si le statut est "restituted" --}}
                @if($status !== 'restituted')
                    <button type="submit" class="tf-btn tf-btn-primary" style="padding: 10px 28px; font-size: 14px;">
                        <i class="bi {{ $ticketId ? 'bi-pencil' : 'bi-plus-circle' }}"></i>
                        {{ $ticketId ? 'Clôturer le ticket' : 'Créer le ticket' }}
                    </button>
                @endif

                @if($ticketId)
                    <a href="{{ route('module5.tickets.show', $ticketId) }}" class="tf-btn tf-btn-ghost">
                        <i class="bi bi-eye"></i> Voir le ticket
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- ─── MODAL AJOUT PIÈCE ─── --}}
    <div class="modal fade tf-modal" id="addPartModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-puzzle" style="margin-right:8px;"></i> Ajouter une pièce détachée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tf-group">
                        <label>Pièce <span class="required">*</span></label>
                        <select wire:model.live="selectedPart" class="tf-control @error('selectedPart') is-invalid @enderror">
                            <option value="">— Sélectionner —</option>
                            @foreach($spareParts as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }}
                                    <span style="color:var(--text-muted);font-size:11px;">
                                        (Stock: {{ $p->quantity_in_stock }})
                                    </span>
                                    — {{ number_format($p->selling_price, 0, ',', ' ') }} FCFA
                                </option>
                            @endforeach
                        </select>
                        @error('selectedPart') <span class="tf-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="tf-group">
                        <label>Quantité <span class="required">*</span></label>
                        <input type="number" wire:model="partQuantity" class="tf-control @error('partQuantity') is-invalid @enderror" min="1" value="1">
                        @error('partQuantity') <span class="tf-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="tf-hint" style="margin-top:4px;">
                        <i class="bi bi-info-circle"></i>
                        Le prix unitaire se remplit automatiquement
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="tf-btn tf-btn-ghost" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" wire:click="addPart" class="tf-btn tf-btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg"></i> Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── SCRIPTS ─── --}}
    @push('scripts')
    <script>
        document.addEventListener('livewire:init', function () {
            // Fermeture du modal après ajout
            Livewire.on('part-added', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addPartModal'));
                if (modal) modal.hide();
            });

            // Scroll vers le haut après validation
            Livewire.on('scroll-to-top', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>
    @endpush
</div>