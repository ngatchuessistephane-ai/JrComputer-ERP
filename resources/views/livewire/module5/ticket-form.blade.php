<div style="zoom:0.90;">
    <style>
        .module-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }
        .module-toolbar h2 {
            font-family: 'Syne', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .module-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        .module-icon.green {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            box-shadow: 0 4px 10px rgba(26,122,60,0.28); 
        }
        .btn-ghost, .btn-brand {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        .btn-ghost:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-xlight);
        }
        .btn-brand {
            background: linear-gradient(135deg, var(--brand-green), #22a352);
            color: white;
            border: none;
        }
        .btn-brand:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,122,60,0.3);
        }
        .flash-msg {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            border: 1px solid;
        }
        .flash-msg.success {
            background: rgba(26,122,60,0.08);
            border-color: rgba(26,122,60,0.2);
            color: var(--brand-green);
        }
        .card-jr {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
        }
        .fg {
            margin-bottom: 14px;
        }
        .fg label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            margin-bottom: 5px;
        }
        .fc {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            background: var(--bg-page);
            color: var(--text-primary);
            font-size: 13px;
            outline: none;
        }
        .fc:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(26,122,60,0.1);
        }
        .text-danger {
            font-size: 11px;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background: var(--bg-page);
            padding: 10px 14px;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            text-align: left;
        }
        .data-table td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border-color);
            font-size: 12px;
            vertical-align: middle;
        }
        .act-btn.del {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(220,38,38,0.25);
            background: rgba(220,38,38,0.06);
            color: #dc2626;
            cursor: pointer;
        }
        .act-btn.del:hover {
            background: rgba(220,38,38,0.15);
        }
        .modal-jr .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
        }
        .modal-jr .modal-header {
            background: linear-gradient(135deg, var(--brand-orange), #ff9c2a);
            border-bottom: none;
            padding: 18px 22px;
        }
        .modal-jr .modal-title {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .modal-jr .btn-close {
            filter: brightness(0) invert(1);
        }
        .modal-jr .modal-body {
            padding: 22px;
        }
        .modal-jr .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }
        hr {
            margin: 20px 0;
            border-color: var(--border-color);
        }
    </style>

    @if(session()->has('message'))
        <div class="flash-msg success">{{ session('message') }}</div>
    @endif

    <div class="module-toolbar">
        <h2><span class="module-icon green"><i class="bi bi-tools"></i></span> {{ $ticketId ? 'Modifier le ticket' : 'Nouveau ticket SAV' }}</h2>
        <a href="{{ route('module5.tickets.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <div class="card-jr">
        <form wire:submit.prevent="save">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="fg">
                        <label>Client *</label>
                        <select wire:model="customer_id" class="fc">
                            <option value="">— Sélectionner —</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? '—' }})</option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fg">
                        <label>Produit (optionnel)</label>
                        <select wire:model="product_id" class="fc">
                            <option value="">— Non renseigné —</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->reference }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fg">
                        <label>Numéro de série</label>
                        <input type="text" wire:model="serial_number" class="fc">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fg">
                        <label>Modèle appareil</label>
                        <input type="text" wire:model="device_model" class="fc">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fg">
                        <label>Priorité</label>
                        <select wire:model="priority" class="fc">
                            <option value="low">🟢 Basse</option>
                            <option value="medium">🟡 Moyenne</option>
                            <option value="high">🟠 Haute</option>
                            <option value="critical">🔴 Critique</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fg">
                        <label>Assigner à un technicien</label>
                        <select wire:model="assigned_to" class="fc">
                            <option value="">— Non assigné —</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fg">
                        <label>Statut</label>
                        <select wire:model="status" class="fc">
                            <option value="pending">En attente</option>
                            <option value="assigned">Assigné</option>
                            <option value="diagnosing">Diagnostic</option>
                            <option value="repairing">En réparation</option>
                            <option value="completed">Terminé</option>
                            <option value="restituted">Restitué</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="fg">
                        <label>Description de la panne *</label>
                        <textarea wire:model="description_failure" rows="3" class="fc"></textarea>
                        @error('description_failure') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mt-3">Pièces détachées utilisées</h5>
                <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#addPartModal">
                    <i class="bi bi-plus-lg"></i> Ajouter une pièce
                </button>
            </div>

            @if(count($parts))
                <div class="table-responsive mt-3">
                    <table class="data-table">
                        <thead>
                            <tr><th>Pièce</th><th>Qté</th><th>Prix unitaire</th><th>Total</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach($parts as $index => $p)
                            <tr>
                                <td>{{ $p['spare_part_name'] }}</td>
                                <td>{{ $p['quantity'] }}</td>
                                <td>{{ number_format($p['unit_price'], 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($p['total'], 0, ',', ' ') }} FCFA</td>
                                <td><button type="button" wire:click="removePart({{ $index }})" class="act-btn del"><i class="bi bi-trash3"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-light mt-2">Aucune pièce ajoutée pour le moment.</div>
            @endif

            <div class="mt-4">
                <button type="submit" class="btn-brand">{{ $ticketId ? 'Mettre à jour' : 'Créer le ticket' }}</button>
            </div>
        </form>
    </div>

    {{-- MODAL AJOUT PIÈCE --}}
    <div class="modal fade" id="addPartModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une pièce détachée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="fg">
                        <label>Pièce</label>
                        <select wire:model="selectedPart" class="fc">
                            <option value="">— Sélectionner —</option>
                            @foreach($spareParts as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->quantity_in_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label>Quantité</label>
                        <input type="number" wire:model="partQuantity" class="fc" min="1">
                    </div>
                    <div class="fg">
                        <label>Prix unitaire (FCFA)</label>
                        <input type="number" step="0.01" wire:model="partUnitPrice" class="fc">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" wire:click="addPart" class="btn-brand" data-bs-dismiss="modal">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</div>