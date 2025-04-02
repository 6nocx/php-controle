# Projet de Gestion des Employés en PHP

Ce projet est une application web développée en PHP permettant de gérer une base de données d'employés. Il intègre les fonctionnalités CRUD (Créer, Lire, Mettre à jour, Supprimer) ainsi qu'une authentification pour sécuriser l'accès aux données. De plus, une recherche globale a été implémentée pour faciliter la navigation parmi les enregistrements.

## Fonctionnalités

- **Authentification :**  
  Seuls les utilisateurs authentifiés peuvent accéder à l'application. La page de connexion (`login.php`) vérifie les identifiants et démarre une session sécurisée.

- **Gestion des employés :**  
  - Affichage de la liste complète des employés.  
  - Ajout d'un nouvel employé via un formulaire.  
  - Modification des informations existantes d'un employé.  
  - Suppression d'un employé avec confirmation.

- **Recherche Globale :**  
  Une barre de recherche permet de filtrer les employés par nom, prénom, email ou poste.

- **Interface Moderne :**  
  Le design de l'application est épuré et professionnel, utilisant des tons clairs et une mise en page responsive pour une meilleure expérience utilisateur.

## Structure du Projet

- **config.php :**  
  Contient la configuration et l'initialisation de la connexion à la base de données via PDO.

- **login.php :**  
  Gère l'authentification de l'utilisateur.

- **gestion_employes.php :**  
  Fichier central qui regroupe toutes les opérations liées à la gestion des employés, y compris l'affichage, l'ajout, la modification, la suppression et la recherche globale.

- **logout.php :**  
  Permet à l'utilisateur de se déconnecter et de terminer la session.

## Installation

1. **Clonage du dépôt :**  
   Clonez ce dépôt sur votre machine locale avec :
   ```bash
   git clone https://github.com/votre-utilisateur/votre-depot.git
