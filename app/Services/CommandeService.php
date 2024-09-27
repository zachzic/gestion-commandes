<?php

namespace App\Services;

use SimpleXMLElement;

class CommandeService
{
    private $filePath;

    public function __construct()
    {
        // Chemin vers le fichier XML des commandes
        $this->filePath = storage_path('app/commandes.xml');
    }

    // Récupérer toutes les commandes
    public function getAllCommandes()
    {
        // Vérifier si le fichier existe et n'est pas vide
        if (!file_exists($this->filePath) || filesize($this->filePath) === 0) {
            // Si le fichier n'existe pas ou est vide, retourner une liste vide
            return [];
        }

        // Charger le contenu du fichier
        $xml = @simplexml_load_file($this->filePath);

        // Vérifier si le chargement a échoué (fichier mal formé ou vide)
        if ($xml === false) {
            // Retourner une liste vide si le XML est invalide
            return [];
        }

        // Retourner les commandes si elles existent, sinon retourner une liste vide
        return $xml->commande ?? [];
    }

    // Ajouter une commande
    public function ajouterCommande($data)
    {
        // Vérifier si le fichier XML existe, sinon le créer avec une structure de base
        if (!file_exists($this->filePath)) {
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?><commandes></commandes>';
            file_put_contents($this->filePath, $xmlContent);
        }

        // Charger le fichier XML
        $commandes = simplexml_load_file($this->filePath);

        // Ajouter la nouvelle commande
        $nouvelleCommande = $commandes->addChild('commande');
        $nouvelleCommande->addChild('id', uniqid());
        $nouvelleCommande->addChild('client', $data['client']);
        $nouvelleCommande->addChild('produit', $data['produit']);
        $nouvelleCommande->addChild('montant', $data['montant']);
        $nouvelleCommande->addChild('paye', 'non');

        // Sauvegarder les modifications dans le fichier XML
        $commandes->asXML($this->filePath);
    }


    // Supprimer une commande par ID
    public function supprimerCommande($id)
    {
        $commandes = simplexml_load_file($this->filePath);

        // Rechercher la commande par ID
        foreach ($commandes->commande as $index => $commande) {
            if ((string)$commande->id === $id) {
                unset($commandes->commande[$index]);
                $commandes->asXML($this->filePath);
                return true;
            }
        }
        return false;
    }

    // Filtrer les commandes payées/non payées
    public function getCommandesByPaiement($statut)
    {
        $commandes = simplexml_load_file($this->filePath);
        $result = [];

        foreach ($commandes->commande as $commande) {
            if ((string)$commande->paye === $statut) {
                $result[] = $commande;
            }
        }

        return $result;
    }
}
