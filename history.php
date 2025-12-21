<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Smart Wallet Elite</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&family=Rajdhani:wght@500;600;700&family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Outfit', 'sans-serif'], 
                        tech: ['Rajdhani', 'sans-serif'],
                        mono: ['Space Grotesk', 'monospace']
                    },
                    colors: {
                        void: '#050508',
                        panel: '#0f0f13',
                        gold: { 400: '#FACC15', 500: '#EAB308', dim: 'rgba(250, 204, 21, 0.1)' },
                        emerald: { 400: '#34D399', glow: 'rgba(52, 211, 153, 0.2)' },
                        rose: { 400: '#FB7185', glow: 'rgba(251, 113, 133, 0.2)' }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)', filter: 'blur(5px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)', filter: 'blur(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050508;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(250, 204, 21, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.03) 0%, transparent 50%);
            color: white;
        }

        .glass-panel {
            background: rgba(18, 18, 24, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .tx-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            opacity: 0; /* Nécessaire pour l'animation fade-in-up */
        }
        
        .tx-row:hover {
            background: rgba(250, 204, 21, 0.03);
            transform: translateX(5px);
            border-color: rgba(250, 204, 21, 0.2);
        }

        .filter-btn.active {
            background: rgba(250, 204, 21, 0.1);
            border-color: #FACC15;
            color: #FACC15;
        }

         /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(25, 25, 25, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .glass-panel:hover {
            border-color: rgba(234, 179, 8, 0.3);
            background: rgba(15, 23, 42, 0.8);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans">

    <!-- NAVBAR -->
    <nav class="w-full h-20 border-b border-white/5 bg-panel/80 backdrop-blur-md flex items-center justify-between px-8 fixed top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center text-gold-400 border border-gold-500/30">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="font-tech text-xl font-bold tracking-widest uppercase">Historique Manuel</span>
        </div>
        <a href="#" class="text-slate-400 hover:text-white transition flex items-center gap-2 text-sm">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-1 pt-28 pb-12 px-4 lg:px-8 max-w-7xl mx-auto w-full">

        <!-- 1. STATS HEADER (Remplissage Manuel) -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up">
                <div class="relative z-10 flex justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Total Revenus</p>
                        <h2 class="text-3xl font-mono text-emerald-400 font-bold mt-1">+ 24,500.00 DH</h2>
                    </div>
                    <i class="fa-solid fa-circle-chevron-up text-emerald-500/20 text-4xl"></i>
                </div>
                <canvas id="incomeChart" class="absolute bottom-0 left-0 w-full h-16 opacity-40"></canvas>
            </div>

            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="relative z-10 flex justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Total Dépenses</p>
                        <h2 class="text-3xl font-mono text-rose-400 font-bold mt-1">- 8,240.00 DH</h2>
                    </div>
                    <i class="fa-solid fa-circle-chevron-down text-rose-500/20 text-4xl"></i>
                </div>
                <canvas id="expenseChart" class="absolute bottom-0 left-0 w-full h-16 opacity-40"></canvas>
            </div>
        </section>

        <section class="flex flex-col lg:flex-row gap-2">
            <!-- Historique des mouvements revenus-->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-6 w-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Dernières Affectations</h3>
                    <button class="text-xs text-gold-400 hover:text-white">Voir tout</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-slate-500 uppercase border-b border-white/10">
                            <tr>
                                <th class="pb-3 pl-2 font-bold text-white">Date</th>
                                <th class="pb-3 font-bold text-white">Vers Carte</th>
                                <th class="pb-3 font-bold text-white">Montant</th>
                                <th class="pb-3 text-right font-bold text-white pr-2">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <!-- Ligne 1 -->
                                <?php
                                $stmt = $pdo->prepare("SELECT i.income_date,i.amount,c.bank_name,c.card_name
                                FROM incomes i 
                                LEFT JOIN cards c ON i.card_id = c.id
                                WHERE i.user_id = ? ORDER BY i.created_at DESC limit 5 
                                ");
                                $stmt->execute([$user_id]);
                                $last_affect = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($last_affect)) {
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
                                foreach($last_affect as $income){
                                    echo '
                                        <tr class="group hover:bg-white/5 transition">
                                            <td class="py-4 pl-2">
                                                <div class="text-[10px] text-slate-300">'.$income["income_date"].'</div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="px-2 py-1 rounded bg-orange-500 text-white">CIH</div>
                                                    <span class="text-slate-300">'.$income["bank_name"].'</span>
                                                </div>
                                            </td>
                                            <td class="py-4 font-mono font-bold text-emerald-400">+ '.$income["amount"].' DH</td>
                                            <td class="py-4 text-right pr-2">
                                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-xs border border-emerald-500/20">Reçu</span>
                                            </td>
                                        </tr>
                                    ';
                                }
                                }
                                ?>
                            
                            <!-- Ligne 2 -->
                            <!-- <tr class="group hover:bg-white/5 transition">
                                <td class="py-4 pl-2">
                                    <div class="font-bold text-white">Freelance Mission</div>
                                    <div class="text-[10px] text-slate-400">20/01/2025</div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-4 rounded bg-amber-700"></div>
                                        <span class="text-slate-300">B. Populaire</span>
                                    </div>
                                </td>
                                <td class="py-4 font-mono font-bold text-emerald-400">+ 2,500 DH</td>
                                <td class="py-4 text-right pr-2">
                                    <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-xs border border-emerald-500/20">Reçu</span>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Historique des mouvements dépenses -->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-6 w-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Dernières Affectations</h3>
                    <button class="text-xs text-gold-400 hover:text-white">Voir tout</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-slate-500 uppercase border-b border-white/10">
                            <tr>
                                <th class="pb-3 pl-2 font-bold text-white">Date</th>
                                <th class="pb-3 font-bold text-white">Vers Carte</th>
                                <th class="pb-3 font-bold text-white">Montant</th>
                                <th class="pb-3 text-right font-bold text-white pr-2">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <!-- Ligne 1 -->
                            <?php
                                $stmt = $pdo->prepare("SELECT e.expense_date,e.amount,c.bank_name,c.card_name
                                FROM expenses e 
                                LEFT JOIN cards c ON e.card_id = c.id
                                WHERE c.user_id = ? ORDER BY e.created_at DESC limit 5 
                                ");
                                $stmt->execute([$user_id]);
                                $last_affect = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? NULL;
                                if (empty($last_affect)) {
                                echo "<tr>
                                    <td colspan='5' class='px-4 py-16 text-center'>
                                        <div class='text-6xl mb-4 opacity-50'>🛒</div>
                                        <p class='text-gray-400'>Aucune dépense enregistrée</p>
                                    </td>
                                </tr>";;
                                }
                                else {
                                foreach($last_affect as $expense){
                                    echo '
                                        <tr class="group hover:bg-white/5 transition">
                                            <td class="py-4 pl-2">
                                                <div class="text-[10px] text-slate-300">'.$expense["expense_date"].'</div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-4 rounded bg-orange-500"></div>
                                                    <span class="text-slate-300">'.$expense["bank_name"].'</span>
                                                </div>
                                            </td>
                                            <td class="py-4 font-mono font-bold text-emerald-400">+ '.$expense["amount"].' DH</td>
                                            <td class="py-4 text-right pr-2">
                                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-xs border border-emerald-500/20">Reçu</span>
                                            </td>
                                        </tr>
                                    ';
                                }
                                }
                                ?>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        

    </main>

    <script>
        // On garde juste une petite fonction pour dessiner les graphiques d'ambiance
        function drawMiniChart(id, color) {
            const c = document.getElementById(id);
            const ctx = c.getContext('2d');
            ctx.strokeStyle = color;
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, 50);
            for(let i=0; i<10; i++) {
                ctx.lineTo(i * 40, Math.random() * 40 + 10);
            }
            ctx.stroke();
        }

        window.onload = () => {
            drawMiniChart('incomeChart', '#34D399');
            drawMiniChart('expenseChart', '#FB7185');
        };
    </script>
</body>
</html>