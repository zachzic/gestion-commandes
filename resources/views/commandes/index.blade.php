@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="container">
    <h1 style="font-size: 28px; margin-bottom: 20px; color: #333;">Gestion des Commandes</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="margin-bottom: 20px;">
        <button onclick="openModal('addOrderModal')" class="btn btn-green">Ajouter une commande</button>
        <button onclick="openModal('productListModal')" class="btn btn-purple">Liste des produits</button>
        <a href="{{ route('commandes.index') }}" class="btn {{ $filter === null ? 'btn-blue' : 'btn-gray' }}">Toutes</a>
        <a href="{{ route('commandes.index', ['filter' => 'paid']) }}" class="btn {{ $filter === 'paid' ? 'btn-blue' : 'btn-gray' }}">Payées</a>
        <a href="{{ route('commandes.index', ['filter' => 'unpaid']) }}" class="btn {{ $filter === 'unpaid' ? 'btn-blue' : 'btn-gray' }}">Non payées</a>
    </div>

    <div style="margin-bottom: 20px;">
        <input type="text" id="searchClient" placeholder="Rechercher par client" style="padding: 5px; margin-right: 10px;">
        <input type="text" id="searchCommande" placeholder="Rechercher par numéro de commande" style="padding: 5px; margin-right: 10px;">
        <button onclick="searchCommandes()" class="btn btn-blue">Rechercher</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="commandesTableBody">
        @foreach($commandes as $commande)
        <tr data-id="{{ $commande->id }}"
            data-client="{{ $commande->client }}"
            data-montant="{{ $commande->montant }}"
            data-paye="{{ $commande->paye }}"
            data-produits="{{ json_encode($commande->produits) }}">
            <td>{{ $commande->id }}</td>
            <td>{{ $commande->client }}</td>
            <td>
                <ul>
                    @foreach($commande->produits->produit as $produit)
                        <li>
                            Produit ID: {{ $produit->id }} - Quantité: {{ $produit->quantite }}
                        </li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $commande->montant }} €</td>
            <td>
                <span class="badge {{ $commande->paye == 'oui' ? 'badge-green' : 'badge-red' }}">
                    {{ $commande->paye == 'oui' ? 'Payée' : 'Non payée' }}
                </span>
            </td>
            <td>
                @if($commande->paye == 'non')
                    <form action="{{ route('commandes.confirmer', $commande->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-green" onclick="return confirm('Êtes-vous sûr de vouloir confirmer cette commande ?')">Confirmer</button>
                    </form>
                @endif

                <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>

<!-- Modal Ajouter une commande -->
<div id="addOrderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addOrderModal')">&times;</span>
        <h2 style="margin-bottom: 20px;">Ajouter une nouvelle commande</h2>
        <form action="{{ route('commandes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" id="client" name="client" required>
            </div>
            <div class="form-group">
                <label for="produits">Produits</label>
                <select id="produits" name="produits[]" multiple required style="height: 150px;" onchange="updateQuantiteFields()">
                    @foreach($produits as $produit)
                        <option value="{{ $produit->id }}">{{ $produit->libelle }} - {{ $produit->prix }} €</option>
                    @endforeach
                </select>
            </div>
            <div id="quantite-container" class="form-group">
                <!-- Les champs de quantité seront générés ici -->
            </div>
            <button type="submit" class="btn btn-green">Ajouter</button>
        </form>
    </div>
</div>

<!-- Modal Liste des produits -->
<div id="productListModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('productListModal')">&times;</span>
        <h2 style="margin-bottom: 20px;">Liste des produits</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">Libellé</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">Prix</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">Type</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">Description</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">Disponibilité</th>
                    <th style="border: 1px solid #ddd; padding: 12px; background-color: #f2f2f2; text-align: left;">SKU</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produits as $produit)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->libelle }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->prix }} </td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->type }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->description }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->disponibilite }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">{{ $produit->sku }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@section('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = "none";
        }
    }

    function updateQuantiteFields() {
        const produitsSelect = document.getElementById('produits');
        const quantiteContainer = document.getElementById('quantite-container');
        
        quantiteContainer.innerHTML = '';

        for (let option of produitsSelect.selectedOptions) {
            const produitId = option.value;
            const produitLabel = option.text;

            const label = document.createElement('label');
            label.innerHTML = 'Quantité pour ' + produitLabel;

            const input = document.createElement('input');
            input.type = 'number';
            input.name = 'quantites[' + produitId + ']';
            input.min = '1';
            input.value = '1';
            input.required = true;

            const div = document.createElement('div');
            div.className = 'form-group';
            div.appendChild(label);
            div.appendChild(input);

            quantiteContainer.appendChild(div);
        }
    }


    function searchCommandes() {
        const searchClient = document.getElementById('searchClient').value.toLowerCase();
        const searchCommande = document.getElementById('searchCommande').value.toLowerCase();
        const rows = document.querySelectorAll('#commandesTableBody tr');

        rows.forEach(row => {
            const client = row.getAttribute('data-client').toLowerCase();
            const commandeId = row.getAttribute('data-id').toLowerCase();
            const matchClient = client.includes(searchClient);
            const matchCommande = commandeId.includes(searchCommande);

            if ((searchClient === '' || matchClient) && (searchCommande === '' || matchCommande)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
@endsection