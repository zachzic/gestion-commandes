@extends('layouts.app')

@section('content')
    <h1>Liste des commandes</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Produit</th>
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
                    <td>{{ $commande->produit }}</td>
                    <td>{{ $commande->montant }}</td>
                    <td>{{ $commande->paye }}</td>
                    <td>
                        <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
