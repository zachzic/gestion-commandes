@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('styles')
<style>
    .btn { display: inline-block; padding: 10px 15px; margin: 5px; text-decoration: none; color: white; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; }
    .btn-green { background-color: #4CAF50; }
    .btn-purple { background-color: #9C27B0; }
    .btn-gray { background-color: #9E9E9E; color: black; }
    .btn-blue { background-color: #2196F3; }
    .btn-red { background-color: #F44336; }
    .btn:hover { opacity: 0.8; }
    .table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); }
    .table th, .table td { border: none; padding: 12px; text-align: left; }
    .table th { background-color: #f2f2f2; font-weight: bold; }
    .table tr:nth-child(even) { background-color: #f8f8f8; }
    .table tr:hover { background-color: #e8e8e8; }
    .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-green { background-color: #E8F5E9; color: #4CAF50; }
    .badge-red { background-color: #FFEBEE; color: #F44336; }
    .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
    .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
    .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: black; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
    .active-filter { background-color: #2196F3; color: white; }
    .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-success { background-color: #dff0d8; border-color: #d6e9c6; color: #3c763d; }
    .alert-danger { background-color: #f2dede; border-color: #ebccd1; color: #a94442; }
</style>
@endsection
@section('content')
<style>
    body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; }
    .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .btn { display: inline-block; padding: 10px 15px; margin: 5px; text-decoration: none; color: white; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; }
    .btn-green { background-color: #4CAF50; }
    .btn-purple { background-color: #9C27B0; }
    .btn-gray { background-color: #9E9E9E; color: black; }
    .btn-blue { background-color: #2196F3; }
    .btn-red { background-color: #F44336; }
    .btn:hover { opacity: 0.8; }
    .table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); }
    .table th, .table td { border: none; padding: 12px; text-align: left; }
    .table th { background-color: #f2f2f2; font-weight: bold; }
    .table tr:nth-child(even) { background-color: #f8f8f8; }
    .table tr:hover { background-color: #e8e8e8; }
    .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-green { background-color: #E8F5E9; color: #4CAF50; }
    .badge-red { background-color: #FFEBEE; color: #F44336; }
    .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
    .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
    .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: black; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
    .active-filter { background-color: #2196F3; color: white; }
    .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-success { background-color: #dff0d8; border-color: #d6e9c6; color: #3c763d; }
    .alert-danger { background-color: #f2dede; border-color: #ebccd1; color: #a94442; }
</style>

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
        <tbody>
        @foreach($commandes as $commande)
        <tr>
            <td>{{ $commande->id }}</td>
            <td>{{ $commande->client }}</td>
            <td>
                <button onclick="openCommandeDetailsModal('{{ $commande->id }}')" class="btn btn-blue">Voir détails</button>
            </td>
            <td>{{ $commande->montant }} €</td>
            <td>
                <span class="badge {{ $commande->paye == 'oui' ? 'badge-green' : 'badge-red' }}">
                    {{ $commande->paye == 'oui' ? 'Payée' : 'Non payée' }}
                </span>
            </td>
            <td>
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
        <ul style="list-style-type: none; padding-left: 0;">
            @foreach($produits as $produit)
                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                    <strong>{{ $produit->libelle }}</strong> - {{ $produit->prix }} €
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Modal Détails de la commande -->
<div id="commandeDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('commandeDetailsModal')">&times;</span>
        <h2 style="margin-bottom: 20px;">Détails de la commande</h2>
        <div id="commandeDetailsContent">
            <!-- Le contenu sera chargé dynamiquement ici -->
        </div>
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

    function openCommandeDetailsModal(commandeId) {
        const modal = document.getElementById('commandeDetailsModal');
        const content = document.getElementById('commandeDetailsContent');
        
        // Ici, vous devriez faire une requête AJAX pour obtenir les détails de la commande
        // Pour cet exemple, nous allons simuler le contenu
        content.innerHTML = `
            <p><strong>ID de la commande:</strong> ${commandeId}</p>
            <p><strong>Client:</strong> Nom du client</p>
            <p><strong>Produits:</strong></p>
            <ul>
                <li>Produit 1 - Quantité: 2</li>
                <li>Produit 2 - Quantité: 1</li>
            </ul>
            <p><strong>Montant total:</strong> XX.XX €</p>
            <p><strong>Statut:</strong> Payée/Non payée</p>
        `;
        
        modal.style.display = "block";
    }
</script>
@endsection
@endsection