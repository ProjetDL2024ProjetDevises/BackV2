# Api Projet Devise

## Description
Cette partie de projet est le backend. Il consiste à faire les interactions entre la base de données et l'utilisateur via du Symfony.
Les differante interaction sont :
   - Récupérer la liste de toutes les monnaies disponibles.  
   - Récupérer toutes les données de taux de change.  
   - Récupérer les données de taux de change pour une monnaie spécifique.  
   - Ajouter des données de taux de change à partir d'un fichier CSV.  

## Endpoints

### 1. **GET /api/monnaie**  
   Récupère la liste de toutes les monnaies disponibles.  

   #### **Description :**  
   Cet endpoint retourne toutes les monnaies enregistrées dans la base de données.  

   #### **Réponses :**  
   - `200` : OK  
     Retourne un tableau contenant les noms des monnaies disponibles.  
   - `500` : Erreur interne du serveur  
     Se produit si la connexion à la base de données échoue.

---

### 2. **GET /api/donnee**  
   Récupère toutes les données de taux de change.  

   #### **Description :**  
   Cet endpoint retourne toutes les données disponibles, incluant la monnaie, la date, et le taux de change associé.  

   #### **Réponses :**  
   - `200` : OK  
     Retourne un tableau d'objets avec les informations de toutes les données (monnaie, date, taux).  
   - `500` : Erreur interne du serveur  
     Se produit si la connexion à la base de données échoue.

---

### 3. **GET /api/donnee/{monnaie}**  
   Récupère toutes les données de taux de change pour une monnaie spécifique.  

   #### **Description :**  
   Cet endpoint retourne les données de taux de change associées à une monnaie spécifique.  

   #### **Paramètres d'URL :**  
   - `monnaie` : (string) Nom de la monnaie, requis.  

   #### **Réponses :**  
   - `200` : OK  
     Retourne un tableau d'objets contenant les informations de taux pour la monnaie spécifiée.  
   - `500` : Erreur interne du serveur  
     Se produit si la connexion à la base de données échoue.

---

### 4. **POST /api/donnee**  
   Insère des données de taux de change à partir d'un fichier CSV.  

   #### **Description :**  
   Cet endpoint accepte un fichier CSV contenant des données de taux de change, avec une première colonne pour les dates et les colonnes suivantes pour les taux par monnaie. Les nouvelles monnaies sont automatiquement ajoutées à la base de données si elles n'existent pas.  

   #### **Paramètres du corps :**  
   - `csvData` : (fichier) Fichier CSV contenant les données, requis.  

   #### **Réponses :**  
   - `200` : Succès  
     Retourne les données extraites et insérées à partir du fichier CSV.  
   - `400` : Requête invalide  
     Se produit si le fichier CSV est invalide ou manquant.  
   - `500` : Erreur interne du serveur  
     Se produit si la connexion à la base de données échoue ou si une contrainte de clé étrangère est violée.

## Versions
Projet : V1.0.0
Symfony : 5.4.47
PHP : 8.3.0

## Authors
Antoine Provain
Maxime Albert
Hugo Chaperon