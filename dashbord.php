<?php session_start(); 

$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

$incomeData = array_fill(0, 12, 0);
$expenseData = array_fill(0, 12, 0);

$stmt = $pdo->query("SELECT MONTH(date) AS mois, SUM(montants) AS total FROM incomes  GROUP BY MONTH(date)");
$revenus = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($revenus as $row) {
    $incomeData[$row['mois'] - 1] = (float)$row['total'];
}

$stmt = $pdo->query("SELECT MONTH(date) AS mois, SUM(montants) AS total FROM expenses GROUP BY MONTH(date)");
$depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($depenses as $row) {
    $expenseData[$row['mois'] - 1] = (float)$row['total'];
}


?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navbar Responsive</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.1/apexcharts.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }

        .animate-scale-in {
            animation: scaleIn 0.4s ease-out;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-backdrop {
            backdrop-filter: blur(4px);
            animation: fadeIn 0.2s ease-out;
        }
    </style>

    <body class="bg-gray-300 space-y-3 lg:p-5 p-1 ">
        <header>
            <!-- Navbar -->
            <nav class="bg-white relative rounded-xl ">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo et Menu Principal -->
                        <div class="flex items-center space-x-20">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <img src="image/download.png" alt="logo" width="60px" class=" rounded-full">
                            </div>

                            <!-- Menu Desktop -->
                            <div class="hidden md:flex space-x-4">
                                <a href="#"
                                    class="text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Dashboard</a>
                                <a href="#"
                                    class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">Transaction</a>
                                <a href="#"
                                    class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">Payments</a>
                                <a href="#"
                                    class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">Exchange</a>
                                <a href="#"
                                    class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">Support</a>
                            </div>
                        </div>

                        <!-- Icônes à droite -->
                        <div class="flex items-center space-x-4">
                            <!-- Notification -->
                            <button class="relative text-gray-600 hover:text-gray-900 transition">
                                <i class="fas fa-bell text-xl"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                            </button>

                            <!-- Settings -->
                            <button class="text-gray-600 hover:text-gray-900 transition">
                                <i class="fas fa-cog text-xl"></i>
                            </button>

                            <!-- Image Profil -->
                            <form action="login.php">
                                <button type="submit">
                                    <img  src="image/mehdi.png" alt="Profile"
                                    class="w-12 rounded-full border-2 border-gray-300 hover:border-blue-500 transition cursor-pointer">
                                </button>
                            </form>

                            <!-- Bouton Menu Mobile -->
                            <button id="mobile-menu-button"
                                class="md:hidden w-6 text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-bars text-2xl"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu Mobile -->
                <div id="mobile-menu"
                    class="hidden absolute right-0 w-[50%] h-[80vh] overflow-auto [scrollbar-width:none] md:hidden bg-white border-t border-gray-200 transition-transform duration-300 ease-in-out transform translate-x-full">
                    <div class="px-2 pt-2 pb-3 flex flex-col items-center space-y-1">
                        <a href="#" class="block text-gray-900 bg-gray-100 px-3 py-2 rounded-md text-base font-medium opacity-0 translate-x-4 transition-all duration-300 menu-item" style="transition-delay: 0ms;">Dashboard</a>
                        <a href="#" class="block text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium opacity-0 translate-x-4 transition-all duration-300 menu-item" style="transition-delay: 50ms;">Transaction</a>
                        <a href="#" class="block text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium opacity-0 translate-x-4 transition-all duration-300 menu-item" style="transition-delay: 100ms;">Payments</a>
                        <a href="#" class="block text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium opacity-0 translate-x-4 transition-all duration-300 menu-item" style="transition-delay: 150ms;">Exchange</a>
                        <a href="#" class="block text-gray-600 hover:bg-gray-100 hover:text-gray-900 px-3 py-2 rounded-md text-base font-medium opacity-0 translate-x-4 transition-all duration-300 menu-item" style="transition-delay: 200ms;">Support</a>
                    </div>
                </div>
            </nav>
        </header>
        <main class="flex flex-col gap-4">
            <section class="flex flex-col gap-2 lg:flex-row">
                <div id="monthlyChart" class="lg:w-[50%] flex justify-center items-center order-2 lg:order-1 w-[100%] card-hover animate-slide-in h-[400px] rounded-xl bg-white">
                </div>
                <!-- Stats Cards -->
                <div class="lg:w-[50%] w-[100%] order-1 lg:order-2 grid grid-cols-2 grid-rows-2 gap-1">
                    <!-- Total Revenus -->
                    <div class="bg-white rounded-xl col-span-1 row-span-1 shadow-lg p-6 card-hover animate-slide-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 font-medium mb-1">Total Revenus</p>
                                <?php 
                                $stmt = $pdo->query("SELECT SUM(montants) AS total_revenu FROM incomes");
                                $results = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total_revenu = $results['total_revenu'] ?? 0 ;
                                echo "<p id='totalIncome' class='text-3xl font-bold text-green-500'> {$total_revenu} MAD</p>";
                                ?>
                            </div>
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-green-100 to-emerald-200 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Dépenses -->
                    <div class="bg-white rounded-xl col-span-1 row-span-1 shadow-lg p-6 card-hover animate-slide-in"
                        style="animation-delay: 0.1s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 font-medium mb-1">Total Dépenses</p>
                                <?php 
                                $stmt = $pdo->query("SELECT SUM(montants) AS total_depense FROM expenses");
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total_depense= $result['total_depense'] ?? 0;
                                echo "<p id='totalExpense' class='text-3xl font-bold text-red-500'>{$total_depense} MAD</p>";
                                ?>
                            </div>
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-red-100 to-rose-200 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Solde Actuel -->
                    <div class="bg-white w-[100%] col-span-2 row-span-1 rounded-xl shadow-lg p-6 card-hover animate-slide-in"
                        style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600 font-medium mb-1">Solde Actuel</p>
                                <?php
                                $stmt1 = $pdo->query("SELECT SUM(montants) AS total_revenu FROM incomes");
                                $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                                $total_revenu = $result1['total_revenu'];

                                $stmt2 = $pdo->query("SELECT SUM(montants) AS total_depense FROM expenses");
                                $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                                $total_depense = $result2['total_depense'];

                                $total= ($total_revenu-$total_depense) ?? 0;
                                $color = ($total >= 0) ? "green" : "red";
                                echo "<p id='balance' class='text-3xl font-bold text-{$color}-700'>{$total} MAD</p>
                            </div>
                            <div
                                class='w-14 h-14 bg-gradient-to-br from-{$color}-100 to-indigo-100 rounded-full flex items-center justify-center'>
                                <svg class='w-7 h-7 text-{$color}-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                    d='M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z' />
                                </svg>
                            </div>";
                                    ?>
                        </div>
                    </div>
                </div>
            </section>
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <!-- Revenus -->
                <div class="bg-white rounded-2xl lg:rounded-3xl shadow-xl p-4 sm:p-6 lg:p-8 animate-fade-in" style="animation-delay: 0.4s;">
                    <div class="space-y-4 mb-6 pb-4 border-b-2 border-gray-100">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="text-xl sm:text-2xl">💵</span>
                            <h2 class=" text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Revenus</h2>
                            <form action="database.php" method="GET" class="ml-30 lg:ml-101">
                                <input name="incomes_pdf"
                                class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500"
                                value="Export PDF" type="submit" name="incomes_pdf"
                                >
                            </form>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-center ">
                            <div class="flex items-center w-full gap-2">
                                <select id="incomeCategory_filtre"  required
                                class="flex-1 min-w-[140px] text-xs sm:text-sm font-semibold px-2 sm:px-3 py-2 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                    <option value="" disabled selected>Choisir une catégorie</option>
                                    <option value="ALL">ALL</option>
                                    <option value="Salaire">Salaire</option>
                                    <option value="Prime">Prime</option>
                                    <option value="Bonus">Bonus</option>
                                    <option value="Revenus freelancing">Revenus freelancing</option>
                                </select>    
                                <input type="date" id="incomeDate_filtre" class="flex-1 min-w-[120px] text-xs sm:text-sm font-semibold py-2 px-2 sm:px-3 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            
                            <button onclick="openModal('incomeModal')"
                            class="w-full sm:w-auto bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold text-xs sm:text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                                +Ajouter
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="space-x-100 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                                    <th
                                        class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                                        categorie</th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                        Montant</th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Description
                                    </th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date
                                    </th>
                                    <th
                                        class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="incomesBody" class="divide-y divide-gray-200">
                                
                                <?php 
                                $pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
                                $select = $pdo->prepare("SELECT * FROM incomes");
                                $select->execute();
                                $results = $select->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (empty($results)) {
                                    echo "
            
                                        <tr>
                                            <td colspan='5' class='px-4 py-16 text-center'>
                                                <div class='text-6xl mb-4 opacity-50'>💰</div>
                                                <p class='text-gray-400'>Aucun revenu enregistré</p>
                                            </td>
                                        </tr>
                                        ";
                                }
                                else {
                                    foreach($results as $index => $income){
                                        echo "
                                        <tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>{$income['categorie']}</td>
                                            <td class='w-[20%] px-4 py-4'>
                                                <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-800'>
                                                    {$income['montants']} DH
                                                </span>
                                            </td>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>{$income['description']}</td>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>{$income['date']}</td>
                                            <td class='w-[20%] px-4 py-4'>
                                            <form action='database.php' method='POST'>
                                                <button type='button' data-id='{$income['id']}' data-categorie='{$income['categorie']}' data-montants='{$income['montants']}' data-description = '{$income['description']}' data-date = '{$income['date']}' 
                                                class='incomeModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                    </svg>
                                                </button>
                                                <button type='submit' name='incomeDelete' value={$income['id']} class='text-red-600 hover:text-red-800 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                    </svg>
                                                </button>
                                                </form>
                                            </td>
                                        </tr>
                                                ";
                                    }
                                }

                                ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Dépenses -->
                <div class="bg-white rounded-2xl lg:rounded-3xl shadow-xl p-4 sm:p-6 lg:p-8 animate-fade-in" style="animation-delay: 0.5s;">
                    <div class="space-y-4 mb-6 pb-4 border-b-2 border-gray-100">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="text-2xl">💳</span>
                            <h2 class="Text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Dépenses</h2>
                            <form action="database.php" method="GET" class="ml-30 lg:ml-98">
                                <input name="expenses_pdf"
                                class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500"
                                value="Export PDF" type="submit" 
                                >
                            </form>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-center">
                            <div class="flex items-center w-full gap-2">
                                <select id="expenseCategory_filtre" required
                                    class="flex-1 min-w-[140px] text-xs sm:text-sm font-semibold px-2 sm:px-3 py-2 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">

                                    <option value="" disabled selected>Choisir une catégorie</option>
                                    <option value="ALL">ALL</option>

                                    <optgroup label="🏠 Logement & Charges">
                                        <option value="Loyer">Loyer</option>
                                        <option value="Crédit immobilier">Crédit immobilier</option>
                                        <option value="Électricité">Électricité</option>
                                        <option value="Eau">Eau</option>
                                        <option value="Gaz">Gaz</option>
                                        <option value="Internet">Internet</option>
                                        <option value="Chauffage">Chauffage</option>
                                        <option value="Charges de copropriété">Charges de copropriété</option>
                                    </optgroup>

                                    <optgroup label="🚗 Transport">
                                        <option value="Carburant">Carburant</option>
                                        <option value="Transport public">Transport public</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Réparations véhicule">Réparations véhicule</option>
                                        <option value="Entretien véhicule">Entretien véhicule</option>
                                        <option value="Stationnement">Stationnement</option>
                                        <option value="Taxes routières">Taxes routières</option>
                                    </optgroup>

                                    <optgroup label="🍔 Nourriture & Nécessités">
                                        <option value="Courses alimentaires">Courses alimentaires</option>
                                        <option value="Eau potable">Eau potable</option>
                                        <option value="Produits d’hygiène">Produits d’hygiène</option>
                                        <option value="Produits de nettoyage">Produits de nettoyage</option>
                                    </optgroup>

                                    <optgroup label="❤️ Santé">
                                        <option value="Médicaments">Médicaments</option>
                                        <option value="Consultations médicales">Consultations médicales</option>
                                        <option value="Analyses et examens">Analyses et examens</option>
                                        <option value="Lunettes / Lentilles">Lunettes / Lentilles</option>
                                        <option value="Assurance santé">Assurance santé</option>
                                    </optgroup>

                                    <optgroup label="🎓 Éducation">
                                        <option value="Frais de scolarité">Frais de scolarité</option>
                                        <option value="Livres">Livres</option>
                                        <option value="Fournitures scolaires">Fournitures scolaires</option>
                                        <option value="Formations / Cours">Formations / Cours</option>
                                        <option value="Transport scolaire">Transport scolaire</option>
                                    </optgroup>

                                    <optgroup label="📡 Communication">
                                        <option value="Téléphone mobile">Téléphone mobile</option>
                                        <option value="Internet mobile">Internet mobile</option>
                                        <option value="Recharges">Recharges</option>
                                    </optgroup>

                                    <optgroup label="🧾 Impôts & Taxes">
                                        <option value="Impôt sur le revenu">Impôt sur le revenu</option>
                                        <option value="Taxe d’habitation">Taxe d’habitation</option>
                                        <option value="Amendes">Amendes</option>
                                    </optgroup>

                                    <optgroup label="🛡️ Assurances">
                                        <option value="Assurance habitation">Assurance habitation</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Assurance vie">Assurance vie</option>
                                    </optgroup>

                                    <optgroup label="💳 Dettes & Crédits">
                                        <option value="Remboursement crédit">Remboursement crédit</option>
                                        <option value="Remboursement prêt">Remboursement prêt</option>
                                        <option value="Intérêts bancaires">Intérêts bancaires</option>
                                    </optgroup>
                                </select>   
                                <input type="date" id="expenseDate_filtre" class="flex-1 min-w-[120px] text-xs sm:text-sm font-semibold py-2 px-2 sm:px-3 border-2 border-gray-200 rounded-lg sm:rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">                    
                            </div>
                            <button onclick="openModal('expenseModal')"
                                class="w-full sm:w-auto bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold text-xs sm:text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
                                +Ajouter
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full ">
                            <thead>
                                <tr class="space-x-100 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                                    <th
                                        class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider rounded-tl-xl">
                                        categorie</th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                        Montant</th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Description
                                    </th>
                                    <th class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date
                                    </th>
                                    <th
                                        class="w-[20%] px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider rounded-tr-xl">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="expensesBody" class="divide-y divide-gray-200">
                                <?php 
                                $pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
                                $select = $pdo->prepare("SELECT * FROM expenses");
                                $select->execute();
                                $results = $select->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($results)) {
                                    echo "<tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>🛒</div>
                                        <p class='text-gray-400'>Aucune dépense enregistrée</p>
                                    </td>
                                </tr>";
                                }
                                else {
                                    foreach($results as $index => $expense){
                                        echo "
                                        <tr class='hover:bg-gray-50 transition-colors'>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>{$expense['categorie']}</td>
                                            <td class='w-[20%] px-4 py-4'>
                                                <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-800'>
                                                    {$expense['montants']} DH
                                                </span>
                                            </td>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>{$expense['description']}</td>
                                            <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>{$expense['date']}</td>
                                            <td class='w-[20%] px-4 py-4'>
                                            <form action='database.php' method='POST'>
                                                <button type='button'  data-id='{$expense['id']}' data-categorie='{$expense['categorie']}' data-montants='{$expense['montants']}' data-description = '{$expense['description']}' data-date = '{$expense['date']}'
                                                class='expenseModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                    </svg>
                                                </button>
                                                <button type='submit' name='expenseDelete' value={$expense['id']} class='text-red-600 hover:text-red-800 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                    </svg>
                                                </button>
                                                </form>
                                            </td>
                                        </tr>
                                        ";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal insert Revenu -->
                <div id="incomeModal"
                    class="hidden flex items-center justify-center px-4 fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
                    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 animate-slide-up">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">💵</span>
                                <h3 class="text-2xl font-bold text-gray-800">Ajouter un Revenu</h3>
                            </div>
                            <button onclick="closeModal('incomeModal')"
                                class="text-gray-400 hover:text-red-600 text-3xl transition-colors">
                                &times;
                            </button>
                        </div>
                        <form action="database.php" method="POST" class="space-y-6">
                    
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categorie</label>
                                <select name="incomeCategory"  required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                                    <option value="" disabled selected>Choisir une catégorie</option>
                                    <option value="Salaire">Salaire</option>
                                    <option value="Prime">Prime</option>
                                    <option value="Bonus">Bonus</option>
                                    <option value="Revenus freelancing">Revenus freelancing</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant (DH)</label>
                                <input type="number" name="incomeAmount" step="0.01" placeholder="0.00" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <input type="text" name="incomeDesc" placeholder="Description (<=35 caractaire)" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">date</label>
                                <input type="date" name="incomeDate"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal modifie Revenu -->
                <div id="incomeModalModifie"
                    class="hidden flex items-center justify-center px-4 fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
                    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 animate-slide-up">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">💵</span>
                                <h3 class="text-2xl font-bold text-gray-800">Ajouter un Revenu</h3>
                            </div>
                            <button onclick="closeModal('incomeModalModifie')"
                                class="text-gray-400 hover:text-red-600 text-3xl transition-colors">
                                &times;
                            </button>
                        </div>
                        <form action="database.php" method="POST" class="space-y-6">
                    
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categorie</label>
                                <select id="incomeUpdateCategorie" name="incomeUpdateCategory"  required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                                    <option value="" disabled selected>Choisir une catégorie</option>
                                    <option value="Salaire">Salaire</option>
                                    <option value="Prime">Prime</option>
                                    <option value="Bonus">Bonus</option>
                                    <option value="Revenus freelancing">Revenus freelancing</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant (DH)</label>
                                <input id="incomeUpdateMontants" type="number" name="incomeUpdateAmount" step="0.01" placeholder="0.00" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <input id="incomeUpdateDescription" type="text" name="incomeUpdateDesc" placeholder="Description (<=35 caractaire)" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">date</label>
                                <input id="incomeUpdateDate" type="date" name="incomeUpdateDate"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <input id="incomeUpdateid" type="hidden" name="incomeUpdateid">
                            <button  type="submit" 
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                Modifie
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Modal insert Dépense -->
                <div id="expenseModal"
                    class="hidden flex items-center justify-center px-4 fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
                    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 animate-slide-up">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">💳</span>
                                <h3 class="text-2xl font-bold text-gray-800">Ajouter une Dépense</h3>
                            </div>
                            <button onclick="closeModal('expenseModal')"
                                class="text-gray-400 hover:text-red-600 text-3xl transition-colors">
                                &times;
                            </button>
                        </div>
                        <form action="database.php" method="POST" class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categorie</label>
                                <select name="expenseCategory" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">

                                    <option value="" disabled selected>Choisir une catégorie</option>

                                    <optgroup label="🏠 Logement & Charges">
                                        <option value="Loyer">Loyer</option>
                                        <option value="Crédit immobilier">Crédit immobilier</option>
                                        <option value="Électricité">Électricité</option>
                                        <option value="Eau">Eau</option>
                                        <option value="Gaz">Gaz</option>
                                        <option value="Internet">Internet</option>
                                        <option value="Chauffage">Chauffage</option>
                                        <option value="Charges de copropriété">Charges de copropriété</option>
                                    </optgroup>

                                    <optgroup label="🚗 Transport">
                                        <option value="Carburant">Carburant</option>
                                        <option value="Transport public">Transport public</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Réparations véhicule">Réparations véhicule</option>
                                        <option value="Entretien véhicule">Entretien véhicule</option>
                                        <option value="Stationnement">Stationnement</option>
                                        <option value="Taxes routières">Taxes routières</option>
                                    </optgroup>

                                    <optgroup label="🍔 Nourriture & Nécessités">
                                        <option value="Courses alimentaires">Courses alimentaires</option>
                                        <option value="Eau potable">Eau potable</option>
                                        <option value="Produits d’hygiène">Produits d’hygiène</option>
                                        <option value="Produits de nettoyage">Produits de nettoyage</option>
                                    </optgroup>

                                    <optgroup label="❤️ Santé">
                                        <option value="Médicaments">Médicaments</option>
                                        <option value="Consultations médicales">Consultations médicales</option>
                                        <option value="Analyses et examens">Analyses et examens</option>
                                        <option value="Lunettes / Lentilles">Lunettes / Lentilles</option>
                                        <option value="Assurance santé">Assurance santé</option>
                                    </optgroup>

                                    <optgroup label="🎓 Éducation">
                                        <option value="Frais de scolarité">Frais de scolarité</option>
                                        <option value="Livres">Livres</option>
                                        <option value="Fournitures scolaires">Fournitures scolaires</option>
                                        <option value="Formations / Cours">Formations / Cours</option>
                                        <option value="Transport scolaire">Transport scolaire</option>
                                    </optgroup>

                                    <optgroup label="📡 Communication">
                                        <option value="Téléphone mobile">Téléphone mobile</option>
                                        <option value="Internet mobile">Internet mobile</option>
                                        <option value="Recharges">Recharges</option>
                                    </optgroup>

                                    <optgroup label="🧾 Impôts & Taxes">
                                        <option value="Impôt sur le revenu">Impôt sur le revenu</option>
                                        <option value="Taxe d’habitation">Taxe d’habitation</option>
                                        <option value="Amendes">Amendes</option>
                                    </optgroup>

                                    <optgroup label="🛡️ Assurances">
                                        <option value="Assurance habitation">Assurance habitation</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Assurance vie">Assurance vie</option>
                                    </optgroup>

                                    <optgroup label="💳 Dettes & Crédits">
                                        <option value="Remboursement crédit">Remboursement crédit</option>
                                        <option value="Remboursement prêt">Remboursement prêt</option>
                                        <option value="Intérêts bancaires">Intérêts bancaires</option>
                                    </optgroup>

                                </select>

                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant (DH)</label>
                                <input type="number" name="expenseAmount" step="0.01" placeholder="0.00" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <input type="text" name="expenseDesc" placeholder="Description (<=35 caractaire)" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">date</label>
                                <input type="date" name="expenseDate"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white py-4 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Modal modifie Dépense -->
                <div id="expenseModalModifie"
                    class="hidden flex items-center justify-center px-4 fixed inset-0 z-50 bg-black/50 backdrop-blur-sm overflow-y-auto">
                    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 animate-slide-up">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">💳</span>
                                <h3 class="text-2xl font-bold text-gray-800">Ajouter une Dépense</h3>
                            </div>
                            <button onclick="closeModal('expenseModalModifie')"
                                class="text-gray-400 hover:text-red-600 text-3xl transition-colors">
                                &times;
                            </button>
                        </div>
                        <form action="database.php" method="POST" class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categorie</label>
                                <select id="expenseUpdateCategorie" name="expenseUpdateCategory" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">

                                    <option value="" disabled selected>Choisir une catégorie</option>

                                    <optgroup label="🏠 Logement & Charges">
                                        <option value="Loyer">Loyer</option>
                                        <option value="Crédit immobilier">Crédit immobilier</option>
                                        <option value="Électricité">Électricité</option>
                                        <option value="Eau">Eau</option>
                                        <option value="Gaz">Gaz</option>
                                        <option value="Internet">Internet</option>
                                        <option value="Chauffage">Chauffage</option>
                                        <option value="Charges de copropriété">Charges de copropriété</option>
                                    </optgroup>

                                    <optgroup label="🚗 Transport">
                                        <option value="Carburant">Carburant</option>
                                        <option value="Transport public">Transport public</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Réparations véhicule">Réparations véhicule</option>
                                        <option value="Entretien véhicule">Entretien véhicule</option>
                                        <option value="Stationnement">Stationnement</option>
                                        <option value="Taxes routières">Taxes routières</option>
                                    </optgroup>

                                    <optgroup label="🍔 Nourriture & Nécessités">
                                        <option value="Courses alimentaires">Courses alimentaires</option>
                                        <option value="Eau potable">Eau potable</option>
                                        <option value="Produits d’hygiène">Produits d’hygiène</option>
                                        <option value="Produits de nettoyage">Produits de nettoyage</option>
                                    </optgroup>

                                    <optgroup label="❤️ Santé">
                                        <option value="Médicaments">Médicaments</option>
                                        <option value="Consultations médicales">Consultations médicales</option>
                                        <option value="Analyses et examens">Analyses et examens</option>
                                        <option value="Lunettes / Lentilles">Lunettes / Lentilles</option>
                                        <option value="Assurance santé">Assurance santé</option>
                                    </optgroup>

                                    <optgroup label="🎓 Éducation">
                                        <option value="Frais de scolarité">Frais de scolarité</option>
                                        <option value="Livres">Livres</option>
                                        <option value="Fournitures scolaires">Fournitures scolaires</option>
                                        <option value="Formations / Cours">Formations / Cours</option>
                                        <option value="Transport scolaire">Transport scolaire</option>
                                    </optgroup>

                                    <optgroup label="📡 Communication">
                                        <option value="Téléphone mobile">Téléphone mobile</option>
                                        <option value="Internet mobile">Internet mobile</option>
                                        <option value="Recharges">Recharges</option>
                                    </optgroup>

                                    <optgroup label="🧾 Impôts & Taxes">
                                        <option value="Impôt sur le revenu">Impôt sur le revenu</option>
                                        <option value="Taxe d’habitation">Taxe d’habitation</option>
                                        <option value="Amendes">Amendes</option>
                                    </optgroup>

                                    <optgroup label="🛡️ Assurances">
                                        <option value="Assurance habitation">Assurance habitation</option>
                                        <option value="Assurance auto">Assurance auto</option>
                                        <option value="Assurance vie">Assurance vie</option>
                                    </optgroup>

                                    <optgroup label="💳 Dettes & Crédits">
                                        <option value="Remboursement crédit">Remboursement crédit</option>
                                        <option value="Remboursement prêt">Remboursement prêt</option>
                                        <option value="Intérêts bancaires">Intérêts bancaires</option>
                                    </optgroup>

                                </select>

                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Montant (DH)</label>
                                <input type="number" id="expenseUpdateMontants" name="expenseUpdateAmount" step="0.01" placeholder="0.00" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <input type="text" id="expenseUpdateDescription" name="expenseUpdateDesc" placeholder="Description (<=35 caractaire)" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">date</label>
                                <input type="date" id="expenseUpdateDate" name="expenseUpdateDate" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <input id="expenseUpdateid" type="hidden" name="expenseUpdateid">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white py-4 rounded-xl font-bold text-sm uppercase tracking-wide shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                Modifie
                            </button>
                        </form>
                    </div>
                </div>
                
            </section>



        </main>




        <script>
            // Toggle Mobile Menu
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuItems = document.querySelectorAll('.menu-item');

            mobileMenuButton.addEventListener('click', () => {
                const icon = mobileMenuButton.querySelector('i');

                if (mobileMenu.classList.contains('hidden')) {
                    // Ouvrir le menu
                    mobileMenu.classList.remove('hidden');
                    // Forcer un reflow pour que la transition fonctionne
                    mobileMenu.offsetHeight;
                    mobileMenu.classList.remove('translate-x-full');

                    // Animer les items du menu
                    setTimeout(() => {
                        menuItems.forEach(item => {
                            item.classList.remove('opacity-0', 'translate-x-4');
                        });
                    }, 50);

                    // Changer l'icône
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    // Fermer le menu
                    mobileMenu.classList.add('translate-x-full');

                    // Animer les items en sens inverse
                    menuItems.forEach(item => {
                        item.classList.add('opacity-0', 'translate-x-4');
                    });

                    // Cacher complètement après l'animation
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);

                    // Changer l'icône
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Gestion des modals
            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
        
            //Affiche modale de modification incomes
            function modifie_incomes(){
                let incomeModifie = document.querySelectorAll('.incomeModifie');
                incomeModifie.forEach(btn => {
                    btn.addEventListener('click' , (e) => {
                        e.preventDefault();
                        document.getElementById('incomeUpdateid').value = btn.dataset.id;
                        document.getElementById('incomeUpdateCategorie').value = btn.dataset.categorie;
                        document.getElementById('incomeUpdateMontants').value = btn.dataset.montants;
                        document.getElementById('incomeUpdateDescription').value = btn.dataset.description;
                        document.getElementById('incomeUpdateDate').value = btn.dataset.date;
                        openModal('incomeModalModifie');
                    })
                }); 
            } ;      
            modifie_incomes();
            //Affiche modale de modification expenses
            function modifie_expenses(){
                let expenseModifie = document.querySelectorAll('.expenseModifie');
                expenseModifie.forEach(btn => {
                    btn.addEventListener('click' , (e) => {
                        e.preventDefault();
                        document.getElementById('expenseUpdateid').value = btn.dataset.id;
                        document.getElementById('expenseUpdateCategorie').value = btn.dataset.categorie;
                        document.getElementById('expenseUpdateMontants').value = btn.dataset.montants;
                        document.getElementById('expenseUpdateDescription').value = btn.dataset.description;
                        document.getElementById('expenseUpdateDate').value = btn.dataset.date;
                        openModal('expenseModalModifie');
                    })
                });
            }
            modifie_expenses();

            function updateChart() {

                const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                // البيانات القادمة من PHP
                const incomeData = <?php echo json_encode($incomeData); ?>;
                const expenseData = <?php echo json_encode($expenseData); ?>;

                // إنشاء الرسم البياني
                const options = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false }
                    },
                    series: [
                        { name: "Revenus", data: incomeData },
                        { name: "Dépenses", data: expenseData }
                    ],
                    xaxis: { 
                        categories: months,
                        labels: {
                            style: {
                                fontSize: '12px',
                                fontWeight: 600
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(0) + ' DH';
                            }
                        }
                    },
                    plotOptions: { 
                        bar: { 
                            borderRadius: 8, 
                            columnWidth: "45%",
                            dataLabels: {
                                position: 'top'
                            }
                        } 
                    },
                    dataLabels: { 
                        enabled: false 
                    },
                    colors: ["#10B981", "#EF4444"],
                    legend: { 
                        position: "top", 
                        fontSize: "14px", 
                        fontWeight: 600,
                        markers: {
                            width: 12,
                            height: 12,
                            radius: 6
                        }
                    },
                    grid: { 
                        borderColor: "rgba(0,0,0,0.05)",
                        strokeDashArray: 4
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2) + " DH";
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#monthlyChart"), options);
                chart.render();
            }



            // Initialiser le graphique
            updateChart();

            //filtrage incomes par categorie
            document.getElementById("incomeCategory_filtre").addEventListener('change' , function(e) {e.preventDefault();
                let c = this.value;

                // نطلبو البيانات من filter.php
                fetch("database.php?categorie_income=" + c)
                    .then(res => res.json())
                    .then(data => {
                        let txt = "";

                        if (data.length === 0) {
                            txt = `
                                <tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>💰</div>
                                        <p class='text-gray-400'>Aucun revenu trouvé pour cette catégorie</p>
                                    </td>
                                </tr>
                            `;
                        }
                        else {
                        
                        
                            data.forEach(row => {
                                txt += `
                                <tr class='hover:bg-gray-50 transition-colors'>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>${row.categorie}</td>
                                    <td class='w-[20%] px-4 py-4'>
                                        <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-800'>
                                            ${row.montants} DH
                                        </span>
                                    </td>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.description}</td>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.date}</td>
                                    <td class='w-[20%] px-4 py-4'>
                                        <form action='database.php' method='POST'>
                                            <button type='button'
                                                data-id='${row.id}'
                                                data-categorie='${row.categorie}'
                                                data-montants='${row.montants}'
                                                data-description='${row.description}'
                                                data-date='${row.date}'
                                                class='incomeModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                                        d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                </svg>
                                            </button>
                                            <button type='submit' name='incomeDelete' value='${row.id}' class='text-red-600 hover:text-red-800 transition-colors'>
                                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                                        d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                        document.getElementById("incomesBody").innerHTML = txt;
                        modifie_incomes();
                    });
                });
    
            //filtrage incomes par date
            document.getElementById("incomeDate_filtre").addEventListener('change' , function(e) {
                e.preventDefault();
                let c = this.value;

                // نطلبو البيانات من filter.php
                fetch("database.php?date_income=" + c)
                    .then(res => res.json())
                    .then(data => {
                        let txt = "";

                        if (data.length === 0) {
                            txt = `
                                <tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>💰</div>
                                        <p class='text-gray-400'>Aucun revenu trouvé pour cette date</p>
                                    </td>
                                </tr>
                            `;
                        }
                        else {
                        
                        
                            data.forEach(row => {
                                txt += `
                                <tr class='hover:bg-gray-50 transition-colors'>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>${row.categorie}</td>
                                    <td class='w-[20%] px-4 py-4'>
                                        <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-800'>
                                            ${row.montants} DH
                                        </span>
                                    </td>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.description}</td>
                                    <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.date}</td>
                                    <td class='w-[20%] px-4 py-4'>
                                        <form action='database.php' method='POST'>
                                            <button type='button'
                                                data-id='${row.id}'
                                                data-categorie='${row.categorie}'
                                                data-montants='${row.montants}'
                                                data-description='${row.description}'
                                                data-date='${row.date}'
                                                class='incomeModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                                        d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                </svg>
                                            </button>
                                            <button type='submit' name='incomeDelete' value='${row.id}' class='text-red-600 hover:text-red-800 transition-colors'>
                                                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                                                        d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                `;
                            });
                        }
                        document.getElementById("incomesBody").innerHTML = txt;
                        modifie_incomes();
                    });
                });
    
        


            //filtrage expenses categorie
            document.getElementById("expenseDate_filtre").addEventListener('change' , function(e) {
                e.preventDefault();
                let c = this.value;

                // نطلبو البيانات من filter.php
                fetch("database.php?date_expense=" + c)
                    .then(res => res.json())
                    .then(data => {
                        let txt = "";

                        if (data.length === 0) {
                            txt = `
                                <tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>🛒</div>
                                        <p class='text-gray-400'>Aucun revenu trouvé pour cette catégorie</p>
                                    </td>
                                </tr>
                            `;
                        }
                        else {
                        
                        
                            data.forEach(row => {
                                txt += `
                                    <tr class='hover:bg-gray-50 transition-colors'>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>${row.categorie}</td>
                                        <td class='w-[20%] px-4 py-4'>
                                            <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-800'>
                                                ${row.montants} DH
                                            </span>
                                        </td>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.description}</td>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.date}</td>
                                        <td class='w-[20%] px-4 py-4'>
                                            <form action='database.php' method='POST'>
                                                <button type='button' 
                                                    data-id='${row.id}' 
                                                    data-categorie='${row.categorie}' 
                                                    data-montants='${row.montants}' 
                                                    data-description='${row.description}' 
                                                    data-date='${row.date}'
                                                    class='expenseModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' 
                                                            d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                    </svg>
                                                </button>
                                                <button type='submit' name='expenseDelete' value='${row.id}' class='text-red-600 hover:text-red-800 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' 
                                                            d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                `;
                            });

                        }
                        document.getElementById("expensesBody").innerHTML = txt;
                        modifie_expenses();
                    });
                });

                    //filtrage incomes par date
                    document.getElementById("expenseCategory_filtre").addEventListener('change' , function(e) {
                e.preventDefault();
                let c = this.value;

                // نطلبو البيانات من filter.php
                fetch("database.php?categorie_expense=" + c)
                    .then(res => res.json())
                    .then(data => {
                        let txt = "";

                        if (data.length === 0) {
                            txt = `
                                <tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>🛒</div>
                                        <p class='text-gray-400'>Aucun revenu trouvé pour cette catégorie</p>
                                    </td>
                                </tr>
                            `;
                        }
                        else {
                        
                        
                            data.forEach(row => {
                                txt += `
                                    <tr class='hover:bg-gray-50 transition-colors'>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-800'>${row.categorie}</td>
                                        <td class='w-[20%] px-4 py-4'>
                                            <span class='inline-block px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-800'>
                                                ${row.montants} DH
                                            </span>
                                        </td>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.description}</td>
                                        <td class='w-[20%] px-4 py-4 text-sm text-gray-600'>${row.date}</td>
                                        <td class='w-[20%] px-4 py-4'>
                                            <form action='database.php' method='POST'>
                                                <button type='button' 
                                                    data-id='${row.id}' 
                                                    data-categorie='${row.categorie}' 
                                                    data-montants='${row.montants}' 
                                                    data-description='${row.description}' 
                                                    data-date='${row.date}'
                                                    class='expenseModifie text-blue-600 hover:text-blue-800 mr-3 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' 
                                                            d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'/>
                                                    </svg>
                                                </button>
                                                <button type='submit' name='expenseDelete' value='${row.id}' class='text-red-600 hover:text-red-800 transition-colors'>
                                                    <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' 
                                                            d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                `;
                            });

                        }
                        document.getElementById("expensesBody").innerHTML = txt;
                        modifie_expenses();
                    });
                });
        </script>
    </body>
</html>