<?php session_start(); 

$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

// Initialisation des tableaux
$incomeData = array_fill(0, 12, 0);
$expenseData = array_fill(0, 12, 0);

// Récupération Revenus
$stmt = $pdo->query("SELECT MONTH(date) AS mois, SUM(montants) AS total FROM incomes GROUP BY MONTH(date)");
$revenus = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($revenus as $row) {
    $incomeData[$row['mois'] - 1] = (float)$row['total'];
}

// Récupération Dépenses
$stmt = $pdo->query("SELECT MONTH(date) AS mois, SUM(montants) AS total FROM expenses GROUP BY MONTH(date)");
$depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($depenses as $row) {
    $expenseData[$row['mois'] - 1] = (float)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Wallet Elite</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    
    <!-- Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.1/apexcharts.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Config Tailwind Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            900: '#050505',
                            800: '#0A0A0A',
                            card: 'rgba(20, 20, 20, 0.6)'
                        },
                        gold: {
                            400: '#FACC15',
                            500: '#EAB308',
                            glow: 'rgba(250, 204, 21, 0.15)'
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulseSlow 4s infinite',
                        'entry': 'entry 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        pulseSlow: {
                            '0%, 100%': { boxShadow: '0 0 0 0 rgba(250, 204, 21, 0.2)' },
                            '70%': { boxShadow: '0 0 0 10px rgba(250, 204, 21, 0)' },
                            '100%': { boxShadow: '0 0 0 0 rgba(250, 204, 21, 0)' },
                        },
                        entry: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050505;
            color: #ffffff;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(250, 204, 21, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(16, 185, 129, 0.05) 0%, transparent 40%);
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(25, 25, 25, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .glass-panel:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(30, 30, 30, 0.7);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #FACC15; }

        /* Form Elements Override */
        input, select {
            color-scheme: dark;
        }
        
        /* Modal Backdrop Animation */
        .modal-enter { animation: entry 0.3s ease-out; }
    </style>
</head>

<body class="selection:bg-gold-500 selection:text-black">

    <!-- Navbar Flottante -->
    <nav class="fixed w-full z-50 px-4 py-4">
        <div class="glass-panel max-w-7xl mx-auto rounded-2xl px-6 py-3 flex justify-between items-center">
            
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-400 to-amber-600 flex items-center justify-center shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                    <img src="image/download.png" alt="logo" class="w-full h-full object-cover rounded-full opacity-90">
                </div>
                <span class="font-heading font-bold text-xl tracking-tight">Smart<span class="text-gold-400">Wallet</span></span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1 bg-white/5 rounded-full p-1 border border-white/5">
                <a href="#" class="px-5 py-2 rounded-full bg-gold-500 text-black font-bold text-sm shadow-lg shadow-gold-500/20 transition">Dashboard</a>
                <a href="#" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Transactions</a>
                <a href="#" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Exchange</a>
            </div>

            <!-- Actions Droite -->
            <div class="flex items-center gap-4">
                <button class="relative w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition border border-white/10">
                    <i class="fas fa-bell text-gold-400"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                </button>
                
                <div class="w-10 h-10 rounded-full border border-gold-500/50 p-0.5 cursor-pointer hover:scale-105 transition">
                    <img src="image/mehdi.png" class="w-full h-full rounded-full object-cover">
                </div>

                <!-- Mobile Toggle -->
                <button id="mobile-menu-button" class="md:hidden text-white text-xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden absolute top-20 right-4 w-64 glass-panel rounded-xl p-4 flex-col gap-2 z-50">
            <a href="#" class="block px-4 py-3 rounded-lg bg-white/10 text-gold-400">Dashboard</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-white">Transactions</a>
            <a href="#" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-white">Exchange</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28 pb-12 px-4 max-w-7xl mx-auto space-y-8">

        <!-- Section 1: Stats & Chart -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Chart (Main Focus) -->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-6 lg:p-8 animate-entry">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-heading font-bold text-white">Analyse Financière</h2>
                    <span class="px-3 py-1 rounded-full bg-white/5 text-xs text-slate-400 border border-white/10">Année 2025</span>
                </div>
                <!-- Chart Container -->
                <div id="monthlyChart" class="w-full h-[350px]"></div>
            </div>

            <!-- Cards (Right Side) -->
            <div class="grid grid-cols-1 gap-4 animate-entry" style="animation-delay: 0.1s;">
                
                <!-- Balance Card -->
                <div class="glass-panel rounded-3xl p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 rounded-full blur-[50px] group-hover:bg-indigo-500/30 transition"></div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Solde Actuel</p>
                    
                    <?php
                        $stmt1 = $pdo->query("SELECT SUM(montants) AS total_revenu FROM incomes");
                        $r1 = $stmt1->fetch(PDO::FETCH_ASSOC); $tr = $r1['total_revenu'] ?? 0;
                        $stmt2 = $pdo->query("SELECT SUM(montants) AS total_depense FROM expenses");
                        $r2 = $stmt2->fetch(PDO::FETCH_ASSOC); $td = $r2['total_depense'] ?? 0;
                        $total = $tr - $td;
                        $colorClass = ($total >= 0) ? "text-emerald-400" : "text-red-400";
                    ?>
                    
                    <h3 id="balance" class="text-4xl font-heading font-bold text-white mb-4"><?php echo number_format($total, 2); ?> <span class="text-lg text-gold-400">DH</span></h3>
                    
                    <div class="flex items-center gap-2 text-xs <?php echo $colorClass; ?> bg-white/5 w-fit px-3 py-1.5 rounded-lg border border-white/5">
                        <i class="fas fa-wallet"></i> Disponible
                    </div>
                </div>

                <!-- Income Card -->
                <div class="glass-panel rounded-3xl p-6 flex items-center justify-between group hover:border-emerald-500/30 transition">
                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Revenus Totaux</p>
                        <p id="totalIncome" class="text-2xl font-bold text-emerald-400"><?php echo number_format($tr, 2); ?> DH</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20 group-hover:scale-110 transition">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="glass-panel rounded-3xl p-6 flex items-center justify-between group hover:border-red-500/30 transition">
                    <div>
                        <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Dépenses Totales</p>
                        <p id="totalExpense" class="text-2xl font-bold text-red-400"><?php echo number_format($td, 2); ?> DH</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center text-red-400 border border-red-500/20 group-hover:scale-110 transition">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

            </div>
        </section>


        <!-- Section 2: Tables (Split Layout) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- REVENUS TABLE -->
            <div class="glass-panel rounded-3xl p-6 animate-entry" style="animation-delay: 0.2s;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Revenus</h3>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="openModal('incomeModal')" class="bg-emerald-500 hover:bg-emerald-600 text-black font-bold px-4 py-2 rounded-lg text-sm transition shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                        <form action="database.php" method="GET">
                            <button name="incomes_pdf" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-lg text-sm transition">
                                <i class="fas fa-file-pdf text-red-400"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex gap-2 mb-4">
                    <select id="incomeCategory_filtre" class="bg-black/40 text-sm text-slate-300 border border-white/10 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                        <option value="" disabled selected>Catégorie</option>
                        <option value="ALL">Tout</option>
                        <option value="Salaire">Salaire</option>
                        <option value="Prime">Prime</option>
                        <option value="Revenus freelancing">Freelance</option>
                    </select>
                    <input type="date" id="incomeDate_filtre" class="bg-black/40 text-sm text-slate-300 border border-white/10 rounded-lg px-3 py-2 outline-none focus:border-emerald-500">
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto rounded-xl border border-white/5">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white/5 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Catégorie</th>
                                <th class="px-4 py-3">Montant</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="incomesBody" class="divide-y divide-white/5">
                            <?php 
                                $stmt = $pdo->query("SELECT * FROM incomes ORDER BY date DESC LIMIT 5");
                                $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if(empty($incomes)) {
                                    echo "<tr><td colspan='4' class='text-center py-6 text-slate-500'>Aucune donnée</td></tr>";
                                } else {
                                    foreach($incomes as $inc) {
                                        echo "
                                        <tr class='hover:bg-white/5 transition'>
                                            <td class='px-4 py-3 text-white'>{$inc['categorie']}</td>
                                            <td class='px-4 py-3 font-mono text-emerald-400'>+{$inc['montants']}</td>
                                            <td class='px-4 py-3 text-slate-400'>{$inc['date']}</td>
                                            <td class='px-4 py-3 text-right'>
                                                <button data-id='{$inc['id']}' data-categorie='{$inc['categorie']}' data-montants='{$inc['montants']}' data-description='{$inc['description']}' data-date='{$inc['date']}' class='incomeModifie text-slate-400 hover:text-white mr-2'><i class='fas fa-pen'></i></button>
                                                <form action='database.php' method='POST' class='inline'>
                                                    <button type='submit' name='incomeDelete' value='{$inc['id']}' class='text-slate-400 hover:text-red-400'><i class='fas fa-trash'></i></button>
                                                </form>
                                            </td>
                                        </tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- DEPENSES TABLE -->
            <div class="glass-panel rounded-3xl p-6 animate-entry" style="animation-delay: 0.3s;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-400 border border-red-500/20">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">Dépenses</h3>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="openModal('expenseModal')" class="bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                        <form action="database.php" method="GET">
                            <button name="expenses_pdf" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-lg text-sm transition">
                                <i class="fas fa-file-pdf text-red-400"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex gap-2 mb-4">
                    <select id="expenseCategory_filtre" class="bg-black/40 text-sm text-slate-300 border border-white/10 rounded-lg px-3 py-2 outline-none focus:border-red-500">
                        <option value="" disabled selected>Catégorie</option>
                        <option value="ALL">Tout</option>
                        <optgroup label="🏠 Logement">
                            <option value="Loyer">Loyer</option>
                            <option value="Électricité">Électricité</option>
                        </optgroup>
                        <optgroup label="🍔 Nourriture">
                            <option value="Courses alimentaires">Courses</option>
                            <option value="Resto">Restaurant</option>
                        </optgroup>
                    </select>
                    <input type="date" id="expenseDate_filtre" class="bg-black/40 text-sm text-slate-300 border border-white/10 rounded-lg px-3 py-2 outline-none focus:border-red-500">
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto rounded-xl border border-white/5">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white/5 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Catégorie</th>
                                <th class="px-4 py-3">Montant</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="expensesBody" class="divide-y divide-white/5">
                            <?php 
                                $stmt = $pdo->query("SELECT * FROM expenses ORDER BY date DESC LIMIT 5");
                                $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if(empty($expenses)) {
                                    echo "<tr><td colspan='4' class='text-center py-6 text-slate-500'>Aucune donnée</td></tr>";
                                } else {
                                    foreach($expenses as $exp) {
                                        echo "
                                        <tr class='hover:bg-white/5 transition'>
                                            <td class='px-4 py-3 text-white'>{$exp['categorie']}</td>
                                            <td class='px-4 py-3 font-mono text-red-400'>-{$exp['montants']}</td>
                                            <td class='px-4 py-3 text-slate-400'>{$exp['date']}</td>
                                            <td class='px-4 py-3 text-right'>
                                                <button data-id='{$exp['id']}' data-categorie='{$exp['categorie']}' data-montants='{$exp['montants']}' data-description='{$exp['description']}' data-date='{$exp['date']}' class='expenseModifie text-slate-400 hover:text-white mr-2'><i class='fas fa-pen'></i></button>
                                                <form action='database.php' method='POST' class='inline'>
                                                    <button type='submit' name='expenseDelete' value='{$exp['id']}' class='text-slate-400 hover:text-red-400'><i class='fas fa-trash'></i></button>
                                                </form>
                                            </td>
                                        </tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </main>

    <!-- ================= MODALS (DARK THEME) ================= -->
    
    <!-- Modal Ajout Revenu -->
    <div id="incomeModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 modal-enter relative">
            <button onclick="closeModal('incomeModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><span class="text-emerald-400">●</span> Nouveau Revenu</h3>
            
            <form action="database.php" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs text-slate-400 uppercase">Catégorie</label>
                    <select name="incomeCategory" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-emerald-500">
                        <option value="Salaire">Salaire</option>
                        <option value="Prime">Prime</option>
                        <option value="Bonus">Bonus</option>
                        <option value="Revenus freelancing">Freelance</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Montant (DH)</label>
                    <input type="number" name="incomeAmount" step="0.01" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-emerald-500 font-mono" placeholder="0.00" required>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Description</label>
                    <input type="text" name="incomeDesc" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-emerald-500" required>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Date</label>
                    <input type="date" name="incomeDate" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-emerald-500" required>
                </div>
                <button type="submit" class="w-full py-3 rounded-lg bg-emerald-500 text-black font-bold hover:bg-emerald-400 transition shadow-lg shadow-emerald-500/20 mt-2">Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Modal Modif Revenu -->
    <div id="incomeModalModifie" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 modal-enter relative">
            <button onclick="closeModal('incomeModalModifie')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            <h3 class="text-xl font-bold text-white mb-6">Modifier Revenu</h3>
            <form action="database.php" method="POST" class="space-y-4">
                <input type="hidden" id="incomeUpdateid" name="incomeUpdateid">
                <!-- Mêmes inputs que ajout, avec IDs pour JS -->
                <select id="incomeUpdateCategorie" name="incomeUpdateCategory" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white"><option value="Salaire">Salaire</option><option value="Prime">Prime</option><option value="Revenus freelancing">Freelance</option></select>
                <input id="incomeUpdateMontants" type="number" name="incomeUpdateAmount" step="0.01" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <input id="incomeUpdateDescription" type="text" name="incomeUpdateDesc" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <input id="incomeUpdateDate" type="date" name="incomeUpdateDate" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <button type="submit" class="w-full py-3 rounded-lg bg-blue-500 text-white font-bold hover:bg-blue-400 transition">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Modal Ajout Dépense -->
    <div id="expenseModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 modal-enter relative">
            <button onclick="closeModal('expenseModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><span class="text-red-400">●</span> Nouvelle Dépense</h3>
            
            <form action="database.php" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs text-slate-400 uppercase">Catégorie</label>
                    <select name="expenseCategory" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-red-500">
                        <option value="Loyer">Loyer</option>
                        <option value="Courses alimentaires">Courses</option>
                        <option value="Carburant">Carburant</option>
                        <option value="Resto">Restaurant</option>
                        <!-- Ajoutez toutes vos options ici -->
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Montant (DH)</label>
                    <input type="number" name="expenseAmount" step="0.01" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-red-500 font-mono" placeholder="0.00" required>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Description</label>
                    <input type="text" name="expenseDesc" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-red-500" required>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase">Date</label>
                    <input type="date" name="expenseDate" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 mt-1 text-white outline-none focus:border-red-500" required>
                </div>
                <button type="submit" class="w-full py-3 rounded-lg bg-red-500 text-white font-bold hover:bg-red-400 transition shadow-lg shadow-red-500/20 mt-2">Débiter</button>
            </form>
        </div>
    </div>

    <!-- Modal Modif Dépense -->
    <div id="expenseModalModifie" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 modal-enter relative">
            <button onclick="closeModal('expenseModalModifie')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            <h3 class="text-xl font-bold text-white mb-6">Modifier Dépense</h3>
            <form action="database.php" method="POST" class="space-y-4">
                <input type="hidden" id="expenseUpdateid" name="expenseUpdateid">
                <select id="expenseUpdateCategorie" name="expenseUpdateCategory" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white"><option value="Loyer">Loyer</option><option value="Courses">Courses</option></select>
                <input id="expenseUpdateMontants" type="number" name="expenseUpdateAmount" step="0.01" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <input id="expenseUpdateDescription" type="text" name="expenseUpdateDesc" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <input id="expenseUpdateDate" type="date" name="expenseUpdateDate" class="w-full bg-black/40 border border-white/10 rounded-lg p-3 text-white">
                <button type="submit" class="w-full py-3 rounded-lg bg-blue-500 text-white font-bold hover:bg-blue-400 transition">Mettre à jour</button>
            </form>
        </div>
    </div>


    <!-- SCRIPTS -->
    <script>
        // -- Mobile Menu Toggle --
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => { menu.classList.toggle('hidden'); });

        // -- Modal Logic --
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        // -- Edit Logic Income --
        function modifie_incomes(){
            document.querySelectorAll('.incomeModifie').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById('incomeUpdateid').value = btn.dataset.id;
                    document.getElementById('incomeUpdateCategorie').value = btn.dataset.categorie;
                    document.getElementById('incomeUpdateMontants').value = btn.dataset.montants;
                    document.getElementById('incomeUpdateDescription').value = btn.dataset.description;
                    document.getElementById('incomeUpdateDate').value = btn.dataset.date;
                    openModal('incomeModalModifie');
                })
            });
        }
        modifie_incomes();

        // -- Edit Logic Expense --
        function modifie_expenses(){
            document.querySelectorAll('.expenseModifie').forEach(btn => {
                btn.addEventListener('click', (e) => {
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

        // -- Dark Mode Chart --
        function updateChart() {
            const incomeData = <?php echo json_encode($incomeData); ?>;
            const expenseData = <?php echo json_encode($expenseData); ?>;

            const options = {
                chart: { 
                    type: 'bar', 
                    height: 350, 
                    toolbar: { show: false },
                    background: 'transparent' // Transparent for glass effect
                },
                theme: { mode: 'dark' }, // Force Dark Mode text
                series: [
                    { name: "Revenus", data: incomeData },
                    { name: "Dépenses", data: expenseData }
                ],
                xaxis: { 
                    categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8' } }
                },
                yaxis: { 
                    labels: { style: { colors: '#94a3b8' }, formatter: (val) => val.toFixed(0) }
                },
                grid: { borderColor: 'rgba(255,255,255,0.05)' },
                colors: ["#10B981", "#EF4444"], // Green & Red
                plotOptions: { 
                    bar: { borderRadius: 4, columnWidth: "50%" } 
                },
                legend: { position: 'top', labels: { colors: '#fff' } },
                dataLabels: { enabled: false }
            };

            const chart = new ApexCharts(document.querySelector("#monthlyChart"), options);
            chart.render();
        }
        updateChart();

        // -- Filter AJAX (Updated Template for Dark Mode) --
        document.getElementById("incomeCategory_filtre").addEventListener('change', function(e) {
            e.preventDefault();
            fetch("database.php?categorie_income=" + this.value)
                .then(res => res.json())
                .then(data => {
                    let txt = "";
                    if(data.length === 0) {
                        txt = "<tr><td colspan='4' class='text-center py-6 text-slate-500'>Aucune donnée</td></tr>";
                    } else {
                        data.forEach(row => {
                            txt += `<tr class='hover:bg-white/5 transition'>
                                <td class='px-4 py-3 text-white'>${row.categorie}</td>
                                <td class='px-4 py-3 font-mono text-emerald-400'>+${row.montants}</td>
                                <td class='px-4 py-3 text-slate-400'>${row.date}</td>
                                <td class='px-4 py-3 text-right'>
                                    <button class='incomeModifie text-slate-400 hover:text-white mr-2' data-id='${row.id}' data-categorie='${row.categorie}' data-montants='${row.montants}' data-description='${row.description}' data-date='${row.date}'><i class='fas fa-pen'></i></button>
                                </td>
                            </tr>`;
                        });
                    }
                    document.getElementById("incomesBody").innerHTML = txt;
                    modifie_incomes();
                });
        });
        
        // (Copier la même logique pour les autres filtres expense/date si nécessaire, en utilisant le nouveau template HTML sombre)
    </script>
</body>
</html>