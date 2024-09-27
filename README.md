
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Générateur de commandes clients

Ce projet est une application Laravel permettant de gérer les commandes de clients sans base de données. Toutes les informations sont stockées et manipulées dans des fichiers XML.

### Fonctionnalités

- **Ajouter une commande** : Permet d'ajouter une nouvelle commande avec un client, un produit, un montant, et son statut de paiement.
- **Afficher les commandes** : Affiche toutes les commandes dans une interface web.
- **Rechercher une commande par son numéro** : Recherche et affiche une commande spécifique à partir de son numéro de commande.
- **Rechercher les commandes d’un client** : Affiche toutes les commandes effectuées par un client spécifique.
- **Supprimer une commande (côté back)** : Permet de supprimer une commande directement via une action côté serveur.
- **Afficher les commandes payées** : Affiche les commandes dont le statut est marqué comme "payé".
- **Afficher les commandes non payées** : Affiche les commandes dont le statut est marqué comme "non payé".
- **Payer une commande** : Met à jour le statut de paiement d'une commande.
- **Lister les produits** : Les produits sont stockés dans un fichier XML à part, et ils peuvent être listés via l'interface web.

### Prérequis

- PHP >= 8.1
- Composer
- Un serveur local (ex. : XAMPP, WAMP ou Laravel Valet)
- Extentions PHP XML installées

### Installation

1. Clonez ce dépôt dans votre machine locale :
   ```bash
   git clone https://github.com/zachzic/gestion-commandes.git
   ```

2. Installez les dépendances du projet via Composer :
   ```bash
   cd gestion-commandes
   composer install
   ```

3. Configurez votre environnement en copiant le fichier `.env.example` vers `.env` :
   ```bash
   cp .env.example .env
   ```

4. Générez la clé d'application Laravel :
   ```bash
   php artisan key:generate
   ```

5. Démarrez le serveur de développement Laravel :
   ```bash
   php artisan serve
   ```

6. Accédez à l'application via votre navigateur à l'adresse :
   ```
   http://127.0.0.1:8000
   ```

### Utilisation des fichiers XML

Les informations sur les commandes et les produits sont stockées dans des fichiers XML, qui remplacent l'usage d'une base de données classique. Voici quelques raisons pour lesquelles nous utilisons les fichiers XML :

1. **Simplicité et légèreté** : Pour des petits projets ou des scénarios où l'installation et la maintenance d'une base de données complète (comme MySQL) ne sont pas nécessaires, les fichiers XML offrent une solution plus simple et plus légère.

2. **Portabilité** : Les fichiers XML peuvent être facilement déplacés, partagés ou importés dans d'autres systèmes, ce qui les rend adaptés à des environnements où la compatibilité est importante.

3. **Solution de secours** : Dans des cas où la connexion à une base de données serait temporairement perdue ou indisponible, les fichiers XML peuvent servir de solution de secours pour stocker et manipuler les données localement.

4. **Lecture humaine** : Les fichiers XML sont lisibles et modifiables par des humains, ce qui peut être pratique pour des corrections ou des mises à jour rapides sans passer par des outils spécifiques.

5. **Pas besoin d'un serveur de base de données** : Cette approche convient lorsque vous ne souhaitez pas configurer un serveur de base de données dédié. C'est idéal pour des prototypes, des petites applications ou des environnements de développement locaux.

Les fichiers XML utilisés sont les suivants :
- **Commandes** : `storage/app/commandes.xml` - Stocke toutes les commandes clients.
- **Produits** : `storage/app/produits.xml` - Contient la liste des produits.

Si ces fichiers n'existent pas, l'application les créera automatiquement lors de la première utilisation.

### Fonctionnalités principales

1. **Ajouter une commande** :
   - Accédez à l'URL `/commandes/create` pour ajouter une nouvelle commande.
   - Remplissez le formulaire avec le nom du client, le produit, et le montant.

2. **Liste des commandes** :
   - Accédez à `/commandes` pour voir la liste complète des commandes.
   
3. **Rechercher une commande** :
   - Un formulaire est disponible pour rechercher une commande spécifique via son numéro.

4. **Filtrer les commandes** :
   - `/commandes/paid` pour voir les commandes payées.
   - `/commandes/unpaid` pour voir les commandes non payées.

5. **Liste des produits** :
   - Les produits disponibles sont listés à `/produits`.

### Fichiers et Dossiers Importants

- **`app/Services/CommandeService.php`** : Ce fichier contient la logique pour gérer les commandes via les fichiers XML.
- **`app/Services/ProduitService.php`** : Ce fichier contient la logique pour gérer les produits.
- **`resources/views/`** : Dossier contenant les fichiers Blade pour l'interface utilisateur.

### Contributions

Si vous souhaitez contribuer à ce projet, vous pouvez soumettre une Pull Request ou signaler des problèmes via GitHub.

### Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).
