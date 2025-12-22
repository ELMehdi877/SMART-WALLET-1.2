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
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cartes - Smart Wallet Elite</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['Space Grotesk', 'monospace'],
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
                        'card-enter': 'cardEnter 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                        'shimmer': 'shimmer 3s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        cardEnter: {
                            '0%': { opacity: '0', transform: 'translateY(50px) scale(0.9)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-150%) skewX(-15deg)' },
                            '100%': { transform: 'translateX(150%) skewX(-15deg)' },
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
                radial-gradient(circle at 90% 90%, rgba(59, 130, 246, 0.05) 0%, transparent 40%);
            overflow-x: hidden;
        }

        /* --- ANIMATIONS KEYFRAMES --- */

        /* 1. Flottement majestueux */
        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(1deg);
            }
        }

        /* 2. Éclat doré (Shine sur le texte) */
        @keyframes gold-shine {
            0% {
                background-position: 200% center;
            }

            100% {
                background-position: -200% center;
            }
        }

        /* 3. Apparition progressive */
        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- CLASSES UTILITAIRES --- */
        .animate-float {
            animation: float-slow 8s ease-in-out infinite;
        }

        .animate-slide-up {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        /* Texte doré premium */
        .text-gradient-gold {
            background: linear-gradient(to right, #FACC15, #EAB308, #FFF7ED, #EAB308);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: gold-shine 5s linear infinite;
        }

        /* Texte doré premium */
        .bg_gradient_gold {
            background: linear-gradient(to right, #FACC15, #f1d582, #d0882f, #EAB308);
            background-size: 200% auto;
            /* color: transparent; */
            /* -webkit-background-clip: initial; */
            /* background-clip: text; */
            animation: gold-shine 5s linear infinite;
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


        /* --- CREDIT CARD 3D STYLES --- */
        .credit-card {
            width: 100%;
            aspect-ratio: 1.586;
            border-radius: 20px;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
            cursor: pointer;
        }

        .credit-card:hover {
            transform: translateY(-10px) rotateY(5deg);
        }

        .credit-card.flipped {
            transform: rotateY(180deg);
        }

        /* Texture de bruit pour effet plastique */
        .card-texture {
            position: absolute;
            inset: 0;
            opacity: 0.15;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            mix-blend-mode: overlay;
            border-radius: 20px;
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Styles Spécifiques aux Banques */
        .card-cih { background: linear-gradient(135deg, #f97316 0%, #c2410c 100%); }
        .card-bp { background: linear-gradient(135deg, #a16207 0%, #713f12 100%); }
        .card-attijari { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); }
        .card-bmce { background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); }
        .card-black { background: linear-gradient(135deg, #1f2937 0%, #000000 100%); }

        .card-back {
            transform: rotateY(180deg);
            background: #1f2937;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Bande magnétique */
        .magnetic-strip {
            height: 40px;
            background: #000;
            width: 100%;
            margin-bottom: 20px;
        }

        /* Puce Holographique */
        .chip {
            width: 50px;
            height: 35px;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .chip::after {
            content:'';
            position: absolute;
            top:0; left:0; width:100%; height:100%;
            background: linear-gradient(transparent 40%, rgba(255,255,255,0.4) 50%, transparent 60%);
            animation: shimmer 3s infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #FACC15; }

        /* --- ANIMATIONS MENU MOBILE (C'est ici qu'il manquait les keyframes) --- */
        
        /* 1. Révélation circulaire */
        @keyframes menu-reveal {
            0% { opacity: 0; clip-path: circle(0% at calc(100% - 40px) 40px); }
            100% { opacity: 1; clip-path: circle(150% at calc(100% - 40px) 40px); }
        }

        /* 2. Cascade des liens */
        @keyframes link-stagger {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Styles du bouton Hamburger */
        .menu-toggle-btn { width: 40px; height: 40px; position: relative; z-index: 50; border: none; background: transparent; cursor: pointer; display: flex; justify-content: center; align-items: center; }
        .hamburger-line { position: absolute; width: 24px; height: 2px; background-color: #FACC15; border-radius: 2px; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .line-1 { transform: translateY(-8px); width: 24px; }
        .line-2 { transform: translateY(0); width: 16px; margin-left: 8px; }
        .line-3 { transform: translateY(8px); width: 10px; margin-left: 14px; }
        
        /* État Active (Croix) */
        .menu-toggle-btn.active .line-1 { transform: translateY(0) rotate(45deg); background-color: #FFF; }
        .menu-toggle-btn.active .line-2 { opacity: 0; transform: translateX(-20px); }
        .menu-toggle-btn.active .line-3 { transform: translateY(0) rotate(-45deg); width: 24px; margin-left: 0; background-color: #FFF; }

        /* Styles de l'Overlay (Menu plein écran) */
        .mobile-menu-overlay { 
            position: fixed; inset: 0; 
            background-color: rgba(27, 26, 26, 0.95); /* Fond très sombre */
            backdrop-filter: blur(20px); 
            z-index: 40; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; 
            pointer-events: none; opacity: 0; transition: opacity 0.3s ease; 
        }
        
        /* Quand le menu est ouvert (Ajout de la classe .open via JS) */
        .mobile-menu-overlay.open { 
            pointer-events: auto; 
            animation: menu-reveal 0.6s cubic-bezier(0.77, 0, 0.175, 1) forwards; 
        }
        
        /* Animation des liens quand le menu est ouvert */
        .mobile-menu-overlay.open .mobile-link { 
            animation: link-stagger 0.5s ease forwards; 
        }
        
        /* Bouton Shine Animation */
        @keyframes shine-move {
            0% { transform: skewX(-25deg) translateX(-200%); }
            100% { transform: skewX(-25deg) translateX(200%); }
        }
        @keyframes btn-pulse-gold {
            0% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(234, 179, 8, 0); }
            100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0); }
        }
        .btn-shine-anim { position: relative; overflow: hidden; animation: btn-pulse-gold 2s infinite; }
        .btn-shine-anim::after { content: ''; position: absolute; top: 0; left: 0; width: 50%; height: 100%; background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent); transform: skewX(-25deg) translateX(-200%); animation: shine-move 4s infinite ease-in-out; pointer-events: none; }
        .btn-hover-effect { transition: all 0.3s ease; }
        .btn-hover-effect:hover { transform: scale(1.05); }
    </style>
</head>

<body class="selection:bg-gold-500 selection:text-black font-sans">

    <!-- NAVBAR SIMPLIFIÉE (Réutilisation) -->
    <nav class="fixed w-full z-50 px-4 py-4 ">
        <div class="glass-panel max-w-7xl mx-auto rounded-2xl px-6 py-3 flex justify-between items-center">
            <!-- LOGO SMART WALLET PREMIUM -->
                <a href="#" class="flex items-center gap-3 group">

                    <!-- Conteneur de l'icône -->
                    <div class="relative w-12 h-12 flex items-center justify-center">

                        <!-- 1. Lueur d'arrière-plan (Glow Effect) -->
                        <div
                            class="absolute inset-0 bg-gold-500/30 rounded-full blur-lg group-hover:bg-gold-500/50 transition duration-500">
                        </div>

                        <!-- 2. Le Cercle de fond (Orange/Or comme l'original mais dégradé) -->
                        <div
                            class="absolute  inset-0 bg_gradient_gold rounded-xl rotate-3 group-hover:rotate-6 transition duration-300 shadow-lg">
                        </div>

                        <!-- 3. L'icône Portefeuille (SVG Custom) -->
                        <div
                            class="relative z-10 text-red-800 transform -rotate-3 group-hover:rotate-0 transition duration-300">
                            <!-- Icone SVG Portefeuille minimaliste -->
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21 7V5.8C21 4.11984 21 3.27976 20.673 2.63803C20.3854 2.07354 19.9265 1.6146 19.362 1.32698C18.7202 1 17.8802 1 16.2 1H7.8C6.11984 1 5.27976 1 4.63803 1.32698C4.07354 1.6146 3.6146 2.07354 3.32698 2.63803C3 3.27976 3 4.11984 3 5.8V17M21 11V18.2C21 19.8802 21 20.7202 20.673 21.362C20.3854 21.9265 19.9265 22.3854 19.362 22.673C18.7202 23 17.8802 23 16.2 23H7.8C6.11984 23 5.27976 23 4.63803 22.673C4.07354 22.3854 3.6146 21.9265 3.32698 21.362C3 20.7202 3 19.8802 3 18.2V9.8C3 8.11984 3 7.27976 3.32698 6.63803C3.6146 6.07354 4.07354 5.6146 4.63803 5.32698C5.27976 5 6.11984 5 7.8 5H16.2C17.8802 5 18.7202 5 19.362 5.32698C19.9265 5.6146 20.3854 6.07354 20.673 6.63803C20.8993 7.08229 20.9755 7.70054 20.9946 8.80005"
                                    stroke="#020617" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <circle cx="16.5" cy="14.5" r="1.5" fill="#020617" />
                            </svg>
                        </div>
                    </div>

                    <!-- Texte du Logo -->
                    <div class="flex flex-col">
                        <span class="font-heading font-extrabold text-2xl tracking-tight text-white leading-none">
                            Smart<span
                                class="text-gradient-gold">Wallet</span>
                        </span>
                        <span
                            class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-medium group-hover:text-gold-400 transition">Finance
                            App</span>
                    </div>
                </a>
            <!-- Menu Links -->
            <div class="hidden md:flex items-center gap-1 bg-white/5 rounded-full p-1 border border-white/5">
                <a href="index.php" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Home</a>
                <a href="cards.php" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Mes Cartes</a>
                <a href="history.php" class="px-5 py-2 rounded-full bg-gold-500 text-black font-bold text-sm shadow-lg shadow-gold-500/20 transition">Historique</a>
                <a href="transfers.php" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Transfers</a>
            </div>
            <!-- <form action="logout.php" method="POST" class="hidden md:block">
                <button type="submit" name="logout"
                style="animation-delay: 0.3s; opacity: 1;" class="px-8 py-4 bg-red-500 text-white font-bold rounded-full shadow-xl"
                >
                déconnexion
             </button>
            </form> -->
            <form action="logout.php" method="POST" class="mobile-link" style="animation-delay: 0.5s">
                <button type="submit" name="logout" class="px-5 py-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 rounded-full text-xs font-bold transition">Déconnexion</button>
            </form>
            <div class="w-10 h-10 rounded-full border border-gold-500/50 p-0.5 cursor-pointer">
                <img src="image/mehdi.png" class="w-full h-full rounded-full object-cover">
            </div>
            
             <!-- BOUTON TOGGLE MOBILE (Visible sur mobile uniquement) -->
                <button class="menu-toggle-btn md:hidden" id="menuToggle" aria-label="Menu">
                    <span class="hamburger-line line-1"></span>
                    <span class="hamburger-line line-2"></span>
                    <span class="hamburger-line line-3"></span>
                </button>
        </div>
    </nav>

    <!-- 📱 MENU MOBILE OVERLAY (Code HTML du menu plein écran) -->
    <div class="mobile-menu-overlay lg:hidden md:hidden" id="mobileMenu">
        <!-- Fond décoratif -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-600/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-600/10 rounded-full blur-[80px]"></div>

        <div class="flex flex-col items-center gap-8 text-center z-10">
            <a href="index.php"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.1s; opacity: 0;">Home</a>
            <a href="cards.php"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.2s; opacity: 0;">Mes Cartes</a>
            <a href="history.php"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.3s; opacity: 0;">Historique</a>
            <a href="transfers.php"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.3s; opacity: 0;">Transfers</a>

            <div class="w-12 h-1 bg-white/10 rounded-full my-4 mobile-link" style="animation-delay: 0.4s; opacity: 0;">
            </div>

            
            <form action="logout.php" method="POST" class="mobile-link" style="animation-delay: 0.5s">
                <button type="submit" name="logout" class="px-5 py-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 rounded-full text-xs font-bold transition">Déconnexion</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="pt-28 pb-12 px-4 max-w-7xl mx-auto space-y-8">

        <!-- HEADER SECTION -->
        <header class="flex flex-col  items-center gap-4 animate-card-enter">
            <h1 class="btn-shine-anim text-6xl font-bold text-white">Histo<span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 to-amber-600">Rique</span></h1>
            
            
        </header>
        <!-- 1. STATS HEADER (Remplissage Manuel) -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up">
                <div class="relative z-10 flex justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Total Revenus</p>
                        <?php 
                        $stmt = $pdo->prepare("SELECT SUM(amount) AS total_revenu FROM incomes WHERE user_id = ?");
                        $stmt->execute([$user_id]);
                        $total = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($total["total_revenu"] == 0) {
                            echo '<h2 class="text-3xl font-mono text-emerald-400 font-bold mt-1">+ 0.00 DH</h2>';
                        }
                        else {
                            echo '<h2 class="text-3xl font-mono text-emerald-400 font-bold mt-1">+ '.$total["total_revenu"].' DH</h2>';
                        }
                        ?>
                    </div>
                    <i class="fa-solid fa-circle-chevron-up text-emerald-500/20 text-4xl"></i>
                </div>
                <canvas id="incomeChart" class="absolute bottom-0 left-0 w-full h-16 opacity-40"></canvas>
            </div>

            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="relative z-10 flex justify-between">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Total Dépenses</p>
                        <?php 
                        $stmt = $pdo->prepare("SELECT SUM(amount) AS total_depense FROM expenses WHERE user_id = ?");
                        $stmt->execute([$user_id]);
                        $total = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($total["total_depense"] == 0) {
                            echo '<h2 class="text-3xl font-mono text-rose-400 font-bold mt-1">- 0.00 DH</h2>';
                        }
                        else {
                            echo '<h2 class="text-3xl font-mono text-rose-400 font-bold mt-1">- '.$total["total_depense"].' DH</h2>';
                        }
                        ?>
                        
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
                                <th class="pb-3 text-right font-bold text-white pr-2">Categorie</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <!-- Ligne 1 -->
                            <?php
                                $stmt = $pdo->prepare("SELECT e.expense_date,e.amount,c.bank_name,c.card_name,cat.category_name
                                FROM expenses e 
                                LEFT JOIN cards c ON e.card_id = c.id
                                LEFT JOIN category cat ON cat.id = e.category_id
                                WHERE c.user_id = ? ORDER BY e.created_at DESC 
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
                                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-xs border border-emerald-500/20">'.$expense["category_name"].'</span>
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

        //menu mobile

        const menuBtn = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        menuBtn.addEventListener('click', () => {
            // Basculer l'état du bouton (Hamburger <-> Croix)
            menuBtn.classList.toggle('active');

            // Basculer l'état du menu (Ouvert <-> Fermé)
            mobileMenu.classList.toggle('open');

            // Empêcher le scroll du body quand le menu est ouvert
            if (mobileMenu.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });

        // Fermer le menu si on clique sur un lien
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                menuBtn.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.style.overflow = 'auto';
            });
        });

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
            drawMiniChart('incomeChart', '#247456ff');
            drawMiniChart('expenseChart', '#fd3e5bff');
        };
    </script>
</body>
</html>