<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommandeService;
use App\Services\ProduitService;
use Exception;
use Illuminate\Validation\ValidationException;

class CommandeController extends Controller
{
    protected $commandeService;
    protected $produitService;

    public function __construct(CommandeService $commandeService, ProduitService $produitService)
    {
        $this->commandeService = $commandeService;
        $this->produitService = $produitService;
    }

    public function index($filter = null)
    {
        try {
            // Gestion des filtres pour les commandes payées ou non payées
            if ($filter === 'paid') {
                $commandes = $this->commandeService->getCommandesByPaiement('oui');
            } elseif ($filter === 'unpaid') {
                $commandes = $this->commandeService->getCommandesByPaiement('non');
            } else {
                $commandes = $this->commandeService->getAllCommandes();
            }

            // Charger les produits disponibles
            $produits = $this->produitService->getAllProduits();

            return view('commandes.index', compact('commandes', 'produits', 'filter'));
        } catch (Exception $e) {
            // Gérer l'erreur et afficher un message à l'utilisateur
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors du chargement des commandes. Veuillez réessayer.');
        }
    }

    public function store(Request $request)
    {
        // Validation des données d'entrée
        try {
            $data = $request->validate([
                'client' => 'required|string',
                'produits' => 'required|array',
                'produits.*' => 'required|string', // Chaque produit doit être une chaîne valide (l'ID du produit)
                'quantites' => 'required|array',
                'quantites.*' => 'required|integer|min:1', // Quantité minimale de 1 pour chaque produit
            ], [
                'client.required' => 'Le champ client est obligatoire.',
                'produits.required' => 'Vous devez sélectionner au moins un produit.',
                'quantites.required' => 'Vous devez fournir une quantité pour chaque produit.',
                'quantites.*.min' => 'Chaque quantité doit être au moins de 1.',
            ]);
        } catch (ValidationException $e) {
            // En cas d'erreur de validation
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Ajout de la commande
        try {
            $this->commandeService->ajouterCommande($data);
            return redirect()->route('commandes.index')->with('success', 'Commande ajoutée avec succès');
        } catch (Exception $e) {
            // Gestion des erreurs lors de l'ajout de la commande
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de l\'ajout de la commande. Veuillez réessayer.');
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            // Vérifier si la commande existe avant de tenter de la supprimer
            if (!$this->commandeService->commandeExiste($id)) {
                return redirect()->route('commandes.index')->with('error', 'La commande que vous essayez de supprimer n\'existe pas.');
            }
            // dd('yo');
            // Supprimer la commande
            $this->commandeService->supprimerCommande($id);
            // dd('yo');
            return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->route('commandes.index')->with('error', 'Une erreur s\'est produite lors de la suppression de la commande. Veuillez réessayer.');
        }
    }

}
