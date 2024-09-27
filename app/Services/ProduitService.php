<?php

namespace App\Services;

use SimpleXMLElement;

class ProduitService
{
    private $filePath;

    public function __construct()
    {
        // Chemin vers le fichier XML des produits
        $this->filePath = storage_path('app/produits.xml');
    }

    // Récupérer tous les produits
    public function getAllProduits()
    {
        $xml = simplexml_load_file($this->filePath);
        return $xml->produit;
    }
}
