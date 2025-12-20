<?php
session_start();
// Simulation ID User (à remplacer par votre session réelle)
$user_id = isset($_SESSION["user_existe"]) ? $_SESSION["user_existe"][0] : 1;
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

// ==========================================
// 🔄 PARTIE D: GÉNÉRATION AUTOMATIQUE (Logic)
// ==========================================
// Se lance à chaque chargement de page (en prod, idéalement un Cron Job)
$current_month_str = date('Y-m');
$day_of_month = date('d');

// On génère seulement si on est le 1er du mois (ou on force pour la démo)
if ($day_of_month == "01" || true) { // '|| true' pour tester immédiatement, à retirer en prod
    
    $stmt = $pdo->prepare("SELECT * FROM recurring_transactions WHERE user_id = ? AND (last_generated_month IS NULL OR last_generated_month != ?)");
    $stmt->execute([$user_id, $current_month_str]);
    $recurrings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($recurrings as $rec) {
        if ($rec['type'] == 'expense') {
            // Insérer dans expenses
            $ins = $pdo->prepare("INSERT INTO expenses (user_id, categorie, montants, description, date) VALUES (?, ?, ?, ?, NOW())");
            $ins->execute([$user_id, $rec['category'], $rec['amount'], $rec['description'] . " (Auto)"]);
        } else {
            // Insérer dans incomes
            $ins = $pdo->prepare("INSERT INTO incomes (user_id, categorie, montants, description, date) VALUES (?, ?, ?, ?, NOW())");
            $ins->execute([$user_id, $rec['category'], $rec['amount'], $rec['description'] . " (Auto)"]);
        }

        // Marquer comme généré pour ce mois
        $upd = $pdo->prepare("UPDATE recurring_transactions SET last_generated_month = ? WHERE id = ?");
        $upd->execute([$current_month_str, $rec['id']]);
    }
}

// ==========================================
// 🛡️ TRAITEMENT FORMULAIRES
// ==========================================
$message = "";
$msg_type = ""; // success, error, warning

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. DÉFINIR UNE LIMITE
    if (isset($_POST['set_limit'])) {
        $cat = $_POST['limit_cat'];
        $amt = $_POST['limit_amount'];
        $stmt = $pdo->prepare("INSERT INTO category_limits (user_id, category, limit_amount) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE limit_amount = ?");
        $stmt->execute([$user_id, $cat, $amt, $amt]);
        $message = "Plafond mis à jour pour $cat."; $msg_type = "success";
    }

    // 2. AJOUTER TRANSACTION (AVEC BLOCAGE & RECURRENCE)
    if (isset($_POST['add_transaction'])) {
        $type = $_POST['type']; // income ou expense
        $cat = $_POST['category'];
        $amount = $_POST['amount'];
        $desc = $_POST['description'];
        $is_recurring = isset($_POST['is_recurring']) ? 1 : 0;

        $blocked = false;

        // 🚫 LOGIQUE DE BLOCAGE (Seulement pour les dépenses)
        if ($type == 'expense') {
            // Vérifier la limite
            $stmt = $pdo->prepare("SELECT limit_amount FROM category_limits WHERE user_id = ? AND category = ?");
            $stmt->execute([$user_id, $cat]);
            $limit = $stmt->fetch();

            if ($limit) {
                // Calculer dépenses actuelles du mois
                $stmt2 = $pdo->prepare("SELECT SUM(montants) as total FROM expenses WHERE user_id = ? AND categorie = ? AND MONTH(date) = MONTH(CURRENT_DATE())");
                $stmt2->execute([$user_id, $cat]);
                $current_spent = $stmt2->fetch()['total'] ?? 0;

                // Vérification Dépassement
                if (($current_spent + $amount) > $limit['limit_amount']) {
                    $blocked = true;
                    $message = "🚫 BLOCAGE : Vous dépassez votre budget de " . $limit['limit_amount'] . " DH pour $cat !";
                    $msg_type = "error";
                } 
                // ⭐ BONUS : Notification 80%
                elseif (($current_spent + $amount) >= ($limit['limit_amount'] * 0.8)) {
                    // Simulation envoi email
                    // mail($user_email, "Attention Budget", "Vous avez atteint 80% de votre budget $cat");
                    $message = "⚠️ Attention : Vous avez atteint 80% de votre budget $cat.";
                    $msg_type = "warning";
                }
            }
        }

        // Si pas bloqué, on insère
        if (!$blocked) {
            if ($type == 'expense') {
                $sql = "INSERT INTO expenses (user_id, categorie, montants, description, date) VALUES (?, ?, ?, ?, NOW())";
            } else {
                $sql = "INSERT INTO incomes (user_id, categorie, montants, description, date) VALUES (?, ?, ?, ?, NOW())";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $cat, $amount, $desc]);

            // Si Récurrent coché -> Ajouter dans la table recurring
            if ($is_recurring) {
                $stmt_rec = $pdo->prepare("INSERT INTO recurring_transactions (user_id, type, category, amount, description) VALUES (?, ?, ?, ?, ?)");
                $stmt_rec->execute([$user_id, $type, $cat, $amount, $desc]);
                $message .= " (Et programmé en récurrent 🔄)";
            }
            
            if(empty($message)) { $message = "Transaction ajoutée avec succès."; $msg_type = "success"; }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Budget Control - Smart Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], heading: ['Outfit', 'sans-serif'] },
                    colors: {
                        dark: { 900: '#020617', 800: '#0F172A' },
                        gold: { 400: '#FACC15', 500: '#EAB308' }
                    },
                    animation: {
                        'pulse-border': 'pulseBorder 2s infinite',
                    },
                    keyframes: {
                        pulseBorder: {
                            '0%, 100%': { borderColor: 'rgba(239, 68, 68, 0.2)' },
                            '50%': { borderColor: 'rgba(239, 68, 68, 0.8)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        /* Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #FACC15;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #FACC15;
        }
    </style>
</head>
<body class="bg-dark-900 text-white min-h-screen p-6 font-sans bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">

    <div class="max-w-6xl mx-auto">
        
        <!-- HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-heading font-bold text-white">Contrôle <span class="text-gold-400">Budgétaire</span></h1>
                <p class="text-slate-400 text-sm">Gérez vos limites et automatisez vos finances.</p>
            </div>
            
            <!-- Message de feedback (Toast) -->
            <?php if($message): ?>
                <div class="px-6 py-3 rounded-xl border flex items-center gap-3 animate-bounce 
                    <?= $msg_type == 'error' ? 'bg-red-500/10 border-red-500 text-red-400' : 
                       ($msg_type == 'warning' ? 'bg-orange-500/10 border-orange-500 text-orange-400' : 
                       'bg-emerald-500/10 border-emerald-500 text-emerald-400') ?>">
                    <i class="fa-solid <?= $msg_type == 'error' ? 'fa-circle-xmark' : 'fa-circle-check' ?>"></i>
                    <span class="font-bold text-sm"><?= $message ?></span>
                </div>
            <?php endif; ?>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- 1️⃣ COLONNE GAUCHE : AJOUT TRANSACTION (Intelligent) -->
            <div class="lg:col-span-1 glass-panel rounded-3xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 blur-[60px]"></div>
                
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-gold-400"></i> Nouvelle Opération
                </h2>

                <form action="" method="POST" class="space-y-5">
                    
                    <!-- Type Switch -->
                    <div class="flex bg-dark-900 p-1 rounded-xl border border-white/10">
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="type" value="expense" class="peer sr-only" checked onclick="toggleType('expense')">
                            <div class="py-2 rounded-lg text-sm font-bold text-slate-400 peer-checked:bg-red-500 peer-checked:text-white transition">Dépense</div>
                        </label>
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="type" value="income" class="peer sr-only" onclick="toggleType('income')">
                            <div class="py-2 rounded-lg text-sm font-bold text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white transition">Revenu</div>
                        </label>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="text-xs text-slate-400 font-bold uppercase ml-1">Catégorie</label>
                        <select name="category" required class="w-full mt-1 bg-white/5 border border-white/10 rounded-xl p-3 text-white outline-none focus:border-gold-400 transition">
                            <optgroup label="Dépenses" id="opt-expense">
                                <option value="Nourriture">Nourriture</option>
                                <option value="Transport">Transport</option>
                                <option value="Loyer">Loyer</option>
                                <option value="Loisirs">Loisirs</option>
                            </optgroup>
                            <optgroup label="Revenus" id="opt-income" hidden>
                                <option value="Salaire">Salaire</option>
                                <option value="Freelance">Freelance</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="text-xs text-slate-400 font-bold uppercase ml-1">Montant</label>
                        <div class="relative">
                            <input type="number" name="amount" step="0.01" required placeholder="0.00" class="w-full mt-1 bg-white/5 border border-white/10 rounded-xl p-3 pl-10 text-white font-mono text-lg outline-none focus:border-gold-400 transition">
                            <span class="absolute left-4 top-4 text-slate-500">DH</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <input type="text" name="description" placeholder="Description (ex: Courses Carrefour)" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white text-sm outline-none focus:border-gold-400">

                    <!-- Toggle Récurrent -->
                    <div class="flex items-center justify-between bg-gold-500/5 border border-gold-500/20 p-3 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gold-500/20 text-gold-400 flex items-center justify-center"><i class="fa-solid fa-rotate"></i></div>
                            <div>
                                <p class="text-sm font-bold text-gold-400">Récurrent ?</p>
                                <p class="text-[10px] text-slate-400">Répéter chaque mois</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_recurring" class="sr-only peer">
                            <div class="w-11 h-6 bg-dark-900 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
                        </label>
                    </div>

                    <button type="submit" name="add_transaction" class="w-full py-4 rounded-xl bg-white text-black font-bold hover:bg-gold-400 hover:shadow-[0_0_20px_rgba(250,204,21,0.4)] transition transform hover:-translate-y-1">
                        Valider Transaction
                    </button>
                </form>
            </div>

            <!-- 2️⃣ COLONNE DROITE : VISUALISATION BUDGETS (Jauges) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Liste des Budgets -->
                <div class="glass-panel rounded-3xl p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">État des Budgets</h2>
                        <button onclick="document.getElementById('setLimitModal').classList.remove('hidden')" class="text-xs text-gold-400 border border-gold-400/30 px-3 py-1.5 rounded-lg hover:bg-gold-400 hover:text-black transition">
                            <i class="fa-solid fa-gear"></i> Gérer Limites
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        // Récupérer les budgets
                        $sql_budget = "SELECT cl.category, cl.limit_amount, COALESCE(SUM(e.montants), 0) as spent 
                                       FROM category_limits cl 
                                       LEFT JOIN expenses e ON cl.category = e.categorie AND cl.user_id = e.user_id AND MONTH(e.date) = MONTH(CURRENT_DATE())
                                       WHERE cl.user_id = ? GROUP BY cl.category, cl.limit_amount";
                        $stmt_b = $pdo->prepare($sql_budget);
                        $stmt_b->execute([$user_id]);
                        $budgets = $stmt_b->fetchAll();

                        if(!$budgets) echo "<p class='text-slate-500 text-sm'>Aucun budget défini. Configurez-en un !</p>";

                        foreach($budgets as $b):
                            $percent = ($b['spent'] / $b['limit_amount']) * 100;
                            $status_color = $percent >= 100 ? 'bg-red-500 shadow-red-500/50' : ($percent >= 80 ? 'bg-orange-500 shadow-orange-500/50' : 'bg-emerald-500 shadow-emerald-500/50');
                            $border_alert = $percent >= 100 ? 'border-red-500 animate-pulse-border' : 'border-white/5';
                        ?>
                        <div class="bg-dark-900/50 p-4 rounded-2xl border <?= $border_alert ?> relative overflow-hidden">
                            <div class="flex justify-between mb-2 relative z-10">
                                <span class="font-bold text-sm text-white"><?= htmlspecialchars($b['category']) ?></span>
                                <span class="text-xs font-mono text-slate-400"><?= number_format($b['spent']) ?> / <?= number_format($b['limit_amount']) ?></span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full h-2 bg-black rounded-full overflow-hidden relative z-10">
                                <div class="h-full rounded-full shadow-[0_0_10px] <?= $status_color ?> transition-all duration-1000" style="width: <?= min($percent, 100) ?>%"></div>
                            </div>

                            <!-- Alert Icon if blocked -->
                            <?php if($percent >= 100): ?>
                                <div class="mt-2 flex items-center gap-2 text-red-400 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-lock"></i> Bloqué
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Liste Récurrents -->
                <div class="glass-panel rounded-3xl p-8">
                    <h2 class="text-xl font-bold text-white mb-4">🔁 Transactions Automatiques</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="text-xs uppercase border-b border-white/10">
                                <tr>
                                    <th class="pb-2">Nom</th>
                                    <th class="pb-2">Montant</th>
                                    <th class="pb-2">Type</th>
                                    <th class="pb-2 text-right">État</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php
                                $stmt_rec = $pdo->prepare("SELECT * FROM recurring_transactions WHERE user_id = ?");
                                $stmt_rec->execute([$user_id]);
                                while($row = $stmt_rec->fetch()):
                                ?>
                                <tr>
                                    <td class="py-3 text-white font-medium"><?= htmlspecialchars($row['category']) ?></td>
                                    <td class="py-3 font-mono"><?= number_format($row['amount'], 2) ?> DH</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs <?= $row['type']=='income' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' ?>">
                                            <?= $row['type'] == 'income' ? 'Revenu' : 'Dépense' ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="text-gold-400 text-xs"><i class="fa-solid fa-check-circle"></i> Actif</span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL : SET LIMIT -->
    <div id="setLimitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 relative">
            <button onclick="document.getElementById('setLimitModal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="text-xl font-bold text-white mb-6">Définir un plafond</h3>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Catégorie</label>
                    <select name="limit_cat" class="w-full bg-dark-900 border border-white/10 rounded-lg p-3 text-white outline-none">
                        <option value="Nourriture">Nourriture</option>
                        <option value="Transport">Transport</option>
                        <option value="Loyer">Loyer</option>
                        <option value="Loisirs">Loisirs</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Limite Mensuelle (DH)</label>
                    <input type="number" name="limit_amount" class="w-full bg-dark-900 border border-white/10 rounded-lg p-3 text-white outline-none font-mono" placeholder="Ex: 2000">
                </div>
                <button type="submit" name="set_limit" class="w-full bg-gold-500 text-black font-bold py-3 rounded-lg hover:bg-gold-400">Sauvegarder</button>
            </form>
        </div>
    </div>

    <script>
        function toggleType(type) {
            const expenseOpts = document.getElementById('opt-expense');
            const incomeOpts = document.getElementById('opt-income');
            if(type === 'expense') {
                expenseOpts.hidden = false;
                incomeOpts.hidden = true;
            } else {
                expenseOpts.hidden = true;
                incomeOpts.hidden = false;
            }
        }
    </script>
</body>
</html>