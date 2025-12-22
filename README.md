# Smart Wallet – Tableau de Bord Financier

Smart Wallet est une application web simple et intuitive permettant aux utilisateurs de gérer leurs **revenus**, **dépenses**, **cartes bancaires** et d’obtenir une vision claire et instantanée de leur situation financière. Cette version est développée pour une solution **PHP & MySQL**, sécurisée et fonctionnelle.

L’objectif principal est d’offrir une solution simple, accessible, facile à utiliser et techniquement solide pour préparer des évolutions futures.

---

## 🚀 Fonctionnalités principales

### 🟢 Gestion des revenus (Incomes)

* Affichage de la liste complète des revenus(historique).
* Ajout d’un revenu via un formulaire dédié.
* Validation des données (montant, date, description…).
* Association du revenu à une carte bancaire.

### 🟢 Gestion des dépenses (Expenses)


* Affichage de la liste complète des dépenses (historique).
* Création d’une nouvelle dépense.
* Association de la dépense à une carte bancaire.
* Blocage automatique si la dépense dépasse la limite mensuelle de la catégorie.

### 🟢 Gestion des cartes bancaires

* Ajout de cartes bancaires.
* Définition d’une carte principale.
* Consultation du solde actuel de chaque carte.
* Affectation des revenus et dépenses à une carte.

### 🟢 Limites mensuelles par catégorie

* Définir des limites mensuelles par catégorie.
* Vérification automatique avant l’ajout d’une dépense.
* Blocage de la dépense si la limite est dépassée.



### 🟢 Base de données SQL complète

* Création d’une base de données dédiée.
* Création des tables `users`, `cards`, `incomes`, `expenses`, `categories`, `limits`, `transactions`.
* Ajout de clés primaires et étrangères.
* Types SQL adaptés : DECIMAL, DATE, VARCHAR, TEXT…
* Contraintes : NOT NULL, DEFAULT, CHECK, ON DELETE CASCADE.

---


---

## 🛠️ Technologies utilisées

* **PHP 8+**
* **MySQL**
* **HTML5**
* **CSS3 / TailwindCSS**
* **JavaScript (ES6+)**

