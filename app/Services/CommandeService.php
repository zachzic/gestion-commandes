<?php

namespace App\Services;

use SimpleXMLElement;
use Exception;

class CommandeService
{
    private $filePath;
    private $produitService;

    public function __construct(ProduitService $produitService)
    {
        // Chemin vers le fichier XML des commandes
        $this->filePath = storage_path('app/commandes.xml');
        $this->produitService = $produitService;
    }

    // Récupérer toutes les commandes
    public function getAllCommandes()
    {
        try {
            // Vérifier si le fichier existe et n'est pas vide
            if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
                return []; // Si le fichier n'existe pas ou est vide, retourner une liste vide
            }

            // Charger le contenu du fichier
            $xml = @simplexml_load_file($this->filePath);

            // Vérifier si le chargement a échoué (fichier mal formé ou vide)
            if ($xml === false) {
                throw new Exception('Impossible de charger le fichier XML des commandes.');
            }

            // Retourner les commandes si elles existent, sinon retourner une liste vide
            return $xml->commande ?? [];
        } catch (Exception $e) {
            \Log::error("Erreur lors de la récupération des commandes : " . $e->getMessage());
            return [];
        }
    }

    // Ajouter une nouvelle commande
    public function ajouterCommande($data)
    {
        try {
            // Vérifier si le fichier XML des commandes existe, sinon le créer avec une structure de base
            if (!file_exists($this->filePath)) {
                $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><commandes></commandes>';
                if (!file_put_contents($this->filePath, $xmlContent)) {
                    throw new Exception('Impossible de créer le fichier des commandes.');
                }
            }

            // Charger le fichier XML des commandes
            $commandes = @simplexml_load_file($this->filePath);
            if ($commandes === false) {
                throw new Exception('Impossible de lire le fichier XML des commandes.');
            }

            // Calculer le montant total
            $montantTotal = 0;
            $produits = $this->produitService->getAllProduits(); // Charger tous les produits disponibles
            foreach ($data['produits'] as $index => $produitId) {
                $quantite = $data['quantites'][$index]; // Récupérer la quantité associée à ce produit
                $produitTrouve = false;

                foreach ($produits as $produit) {
                    if ((string)$produit->id === $produitId) {
                        // Multiplier le prix du produit par sa quantité
                        $montantTotal += (float)$produit->prix * $quantite;
                        $produitTrouve = true;
                        break;
                    }
                }

                if (!$produitTrouve) {
                    throw new Exception("Le produit avec l'ID {$produitId} n'existe pas.");
                }
            }

            // Ajouter la nouvelle commande
            $nouvelleCommande = $commandes->addChild('commande');
            $nouvelleCommande->addChild('id', uniqid());
            $nouvelleCommande->addChild('client', $data['client']);
            $nouvelleCommande->addChild('montant', $montantTotal);
            $nouvelleCommande->addChild('paye', 'non');

            // Ajouter les produits à la commande avec leur quantité respective
            $produitsCommande = $nouvelleCommande->addChild('produits');
            foreach ($data['produits'] as $index => $produitId) {
                $quantite = $data['quantites'][$index]; // Récupérer la quantité
                $produitCommande = $produitsCommande->addChild('produit');
                $produitCommande->addChild('id', $produitId);
                $produitCommande->addChild('quantite', $quantite);
            }

            // Sauvegarder les modifications dans le fichier XML
            if ($commandes->asXML($this->filePath) === false) {
                throw new Exception('Erreur lors de la sauvegarde de la commande.');
            }
        } catch (Exception $e) {
            \Log::error("Erreur lors de l'ajout de la commande : " . $e->getMessage());
            throw $e; // Relancer l'exception pour que le contrôleur puisse gérer l'erreur
        }
    }

    public function commandeExiste($id)
    {
        // Vérifier si le fichier existe et n'est pas vide
        if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
            return false;
        }

        // Charger le fichier XML
        $commandes = @simplexml_load_file($this->filePath);
        if ($commandes === false) {
            return false;
        }

        // Rechercher la commande par ID
        foreach ($commandes->commande as $commande) {
            if ((string)$commande->id === $id) {
                return true;
            }
        }

        // Retourner false si la commande n'existe pas
        return false;
    }

    // Supprimer une commande par ID
    public function supprimerCommande($id)
    {
        try {
            // Vérifier si le fichier existe
            if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
                throw new Exception("Le fichier des commandes est vide ou n'existe pas.");
            }

            // Charger le fichier XML
            $commandes = simplexml_load_file($this->filePath);
            if ($commandes === false) {
                throw new Exception("Impossible de lire le fichier XML des commandes.");
            }

            // Initialiser une variable pour stocker si on a trouvé la commande
            $commandeTrouvee = false;

            // Rechercher et filtrer les commandes sans celle avec l'ID correspondant
            $nouvelleListeCommandes = [];
            foreach ($commandes->commande as $commande) {
                // Vérifier si l'ID correspond
                if ((string)$commande->id === (string)$id) {
                    $commandeTrouvee = true; // Marquer comme trouvée
                } else {
                    // Ajouter la commande au tableau si l'ID ne correspond pas
                    $nouvelleListeCommandes[] = $commande;
                }
            }

            // Si la commande n'a pas été trouvée, on lance une exception
            if (!$commandeTrouvee) {
                throw new Exception("Commande avec l'ID {$id} non trouvée.");
            }

            // Créer un nouveau SimpleXMLElement et ajouter les commandes restantes
            $nouveauXML = new SimpleXMLElement('<commandes></commandes>');
            foreach ($nouvelleListeCommandes as $commande) {
                $commandeNode = $nouveauXML->addChild('commande');
                $commandeNode->addChild('id', (string)$commande->id);
                $commandeNode->addChild('client', (string)$commande->client);
                $commandeNode->addChild('montant', (string)$commande->montant);
                $commandeNode->addChild('paye', (string)$commande->paye);

                // Ajouter les produits
                $produitsNode = $commandeNode->addChild('produits');
                foreach ($commande->produits->produit as $produit) {
                    $produitNode = $produitsNode->addChild('produit');
                    $produitNode->addChild('id', (string)$produit->id);
                    $produitNode->addChild('quantite', (string)$produit->quantite);
                }
            }

            // Sauvegarder le nouveau fichier XML
            if ($nouveauXML->asXML($this->filePath) === false) {
                throw new Exception("Erreur lors de la sauvegarde après suppression.");
            }

            return true;

        } catch (Exception $e) {
            \Log::error("Erreur lors de la suppression de la commande : " . $e->getMessage());
            throw $e; // Relancer l'exception pour gestion dans le contrôleur
        }
    }


    // Filtrer les commandes payées/non payées
    public function getCommandesByPaiement($statut)
    {
        try {
            // Vérifier si le fichier existe et n'est pas vide
            if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
                return [];  // Retourner une liste vide si le fichier est absent ou vide
            }

            // Tenter de charger le fichier XML
            $commandes = @simplexml_load_file($this->filePath);

            // Si le chargement échoue (fichier mal formé), retourner une liste vide
            if ($commandes === false) {
                throw new Exception('Impossible de lire le fichier XML des commandes.');
            }

            // Filtrer les commandes selon le statut (paye/non paye)
            $result = [];
            foreach ($commandes->commande as $commande) {
                if ((string)$commande->paye === $statut) {
                    $result[] = $commande;
                }
            }

            return $result;
        } catch (Exception $e) {
            \Log::error("Erreur lors du filtrage des commandes par paiement : " . $e->getMessage());
            return []; // Retourner une liste vide en cas d'erreur
        }
    }
}
