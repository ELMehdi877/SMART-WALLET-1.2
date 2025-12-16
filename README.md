# Smart Wallet – Tableau de Bord Financier

Smart Wallet est une application web simple et intuitive permettant aux utilisateurs de gérer leurs **revenus**, **dépenses** et d’obtenir une vision claire et instantanée de leur situation financière. Cette première version a été développée pour une startup locale souhaitant un outil léger, basé sur **PHP & MySQL**, avec un tableau de bord clair et fonctionnel.

L’objectif principal est d’offrir une solution simple, accessible, facile à utiliser et techniquement solide pour préparer une future version plus avancée.

---

## 🚀 Fonctionnalités principales

### 🟢 Gestion des revenus (Incomes)

* Affichage de la liste complète des revenus.
* Ajout d’un revenu via un formulaire dédié.
* Modification d’un revenu existant.
* Suppression d’un revenu.
* Validation des données (montant, date, description…).

### 🟢 Gestion des dépenses (Expenses)

* Affichage de la liste complète des dépenses.
* Création d’une nouvelle dépense.
* Modification d’une dépense existante.
* Suppression d’une dépense.
* Validation des données avec filtres de cohérence.

### 🟢 Dashboard financier

* Calcul et affichage du total des revenus.
* Calcul et affichage du total des dépenses.
* Calcul du solde actuel (revenus – dépenses).
* Affichage des revenus et dépenses du mois en cours.
* Intégration d’un graphique simple (Chart.js / ApexCharts) pour visualiser les finances — optionnel.

### 🟢 Base de données SQL complète

* Création d’une base de données dédiée.
* Création des tables `incomes` et `expenses`.
* Ajout de clés primaires.
* Types SQL adaptés : DECIMAL, DATE, TEXT...
* Contraintes : NOT NULL, DEFAULT, etc.
* Fichier complet regroupé dans **database.sql**.


---

## 📌 User Stories (Principales)

### 🗄️ SQL – Création de la base de données

* En tant que développeur Back-End, je vais créer la base de données du projet.
* En tant que développeur Back-End, je vais créer les tables **incomes** et **expenses**.
* En tant que développeur Back-End, je vais définir les types SQL adaptés.
* En tant que développeur Back-End, je vais ajouter les clés primaires nécessaires.
* En tant que développeur Back-End, je vais ajouter les contraintes logiques (NOT NULL, DEFAULT…).
* En tant que développeur Back-End, je vais regrouper tout dans `database.sql`.

### 💰 Incomes – CRUD

* Afficher la liste des revenus.
* Ajouter un nouveau revenu.
* Enregistrer un revenu via INSERT.
* Modifier un revenu via UPDATE.
* Supprimer un revenu via DELETE.
* Valider les données avant insertion.
* Filtrage par catégorie.
* Filtrage par date.
* Export PDF des données.

### 💸 Expenses – CRUD

* Afficher la liste des dépenses.
* Ajouter une dépense.
* Enregistrer via INSERT.
* Modifier une dépense via UPDATE.
* Supprimer via DELETE.
* Valider les données.
* Filtrage par catégorie.
* Filtrage par date.
* Export PDF des données.

### 📊 Dashboard

* Afficher total revenus.
* Afficher total dépenses.
* Calculer solde.
* Afficher revenus/dépenses du mois actuel.
* Graphique mensuel (ApexCharts…).

---

## 🛠️ Technologies utilisées

* **PHP 8+**
* **MySQL**
* **HTML5**
* **CSS3 / TailwindCSS**
* **JavaScript (ES6+)**
* **ApexCharts** 
* **FPDF** pour export PDF

