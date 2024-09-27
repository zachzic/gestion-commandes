<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommandeService;

class CommandeController extends Controller
{
    protected $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    // Afficher toutes les commandes
    public function index()
    {
        $commandes = $this->commandeService->getAllCommandes();
        return view('commandes.index', compact('commandes'));
    }

    // Ajouter une nouvelle commande (formulaire)
    public function create()
    {
        return view('commandes.create');
    }

    // Enregistrer une nouvelle commande
    public function store(Request $request)
    {
        $this->commandeService->ajouterCommande($request->all());
        return redirect()->route('commandes.index')->with('success', 'Commande ajoutée');
    }

    // Supprimer une commande
    public function destroy($id)
    {
        $this->commandeService->supprimerCommande($id);
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée');
    }

    // Afficher les commandes payées
    public function showPaid()
    {
        $commandes = $this->commandeService->getCommandesByPaiement('oui');
        return view('commandes.paid', compact('commandes'));
    }

    // Afficher les commandes non payées
    public function showUnpaid()
    {
        $commandes = $this->commandeService->getCommandesByPaiement('non');
        return view('commandes.unpaid', compact('commandes'));
    }
}
