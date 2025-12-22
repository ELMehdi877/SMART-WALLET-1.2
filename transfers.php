<?php
// Simulation de session pour le design, à adapter avec votre logique PHP
session_start();
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sender_button"])){
    if (isset($_POST["card_recep_id"]) && isset($_POST["sender_email"]) && isset($_POST["sender_amount"]) && isset($_POST["sender_description"])) {
        $stmt = $pdo->prepare("INSERT INTO cards (balance)
        SELECT u.id, ?
        FROM users u
        JOIN cards c ON c.user_id = u.id
        WHERE u.email = ?
        AND c.card_principale = 1");
        }
}
?>



<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferts - Smart Wallet Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], mono: ['Space Grotesk', 'monospace'] },
                    colors: {
                        dark: { 900: '#050505', 800: '#0A0A0A', card: 'rgba(20, 20, 20, 0.6)' },
                        gold: { 400: '#FACC15', 500: '#EAB308', glow: 'rgba(250, 204, 21, 0.15)' }
                    },
                    animation: {
                        'shimmer': 'shimmer 3s linear infinite',
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        shimmer: { '0%': { transform: 'translateX(-150%) skewX(-15deg)' }, '100%': { transform: 'translateX(150%) skewX(-15deg)' } },
                        slideUp: { 'from': { opacity: '0', transform: 'translateY(40px)' }, 'to': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #050505; color: #ffffff; background-image: radial-gradient(circle at 10% 10%, rgba(250, 204, 21, 0.05) 0%, transparent 40%); }
        .text-gradient-gold { background: linear-gradient(to right, #FACC15, #EAB308, #FFF7ED, #EAB308); -webkit-background-clip: text; color: transparent; background-size: 200% auto; animation: gold-shine 5s linear infinite; }
        @keyframes gold-shine { 0% { background-position: 200% center; } 100% { background-position: -200% center; } }
        
        .btn-shine-anim { position: relative; overflow: hidden; }
        .btn-shine-anim::after { content: ''; position: absolute; top: 0; left: 0; width: 50%; height: 100%; background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.3), transparent); transform: skewX(-25deg) translateX(-200%); animation: shine-move 4s infinite; }
        @keyframes shine-move { 0% { transform: skewX(-25deg) translateX(-200%); } 100% { transform: skewX(-25deg) translateX(200%); } }

        .card-selected { border: 2px solid #FACC15 !important; box-shadow: 0 0 20px rgba(250, 204, 21, 0.2); }
        .primary-badge { display: none; }
        .card-selected .primary-badge { display: block; }

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
        /* Texte doré premium */
        .bg_gradient_gold {
            background: linear-gradient(to right, #FACC15, #f1d582, #d0882f, #EAB308);
            background-size: 200% auto;
            /* color: transparent; */
            /* -webkit-background-clip: initial; */
            /* background-clip: text; */
            animation: gold-shine 5s linear infinite;
        }
    </style>
</head>
<body class="font-sans">

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
                <a href="history.php" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Historique</a>
                <a href="transfers.php" class="px-5 py-2 rounded-full bg-gold-500 text-black font-bold text-sm shadow-lg shadow-gold-500/20 transition">Transfers</a>
            </div>
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

    <main class="pt-28 pb-12 px-4 max-w-7xl mx-auto space-y-10">
        
        <!-- HEADER -->
        <header class="animate-slide-up">
            <p class="text-gold-500 text-xs font-mono uppercase tracking-[0.3em] mb-2">Service de transfert élite</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white">Envoyez & <span class="text-gradient-gold">Recevez</span></h1>
        </header>

        <form action="transfers.php" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
            <!-- 1️⃣ COLONNE GAUCHE: CHOIX CARTE PRINCIPALE -->
            <div class="lg:col-span-1 space-y-6 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="fa-solid fa-star text-gold-400"></i> Carte de réception
                    </h2>
                </div>
                
                <div class="space-y-4">
                    <?php
                    $stmt = $pdo->prepare("SELECT id,user_id,bank_name,card_name,balance,last_4 FROM cards WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($cards as $card){
                        
                        echo '
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="card_recep_id" value="'.$card["id"].'" class="peer hidden" checked>
                                
                                <!-- Conteneur de la carte (Design) -->
                                <div class="glass-panel p-5 rounded-2xl transition-all hover:bg-white/5 border border-transparent 
                                            peer-checked:border-gold-500 peer-checked:ring-1 peer-checked:ring-gold-500 peer-checked:shadow-[0_0_20px_rgba(250,204,21,0.2)]">
                                    
                                    <!-- Badge (Affiche uniquement si coché) -->
                                    <div class="hidden peer-checked:block absolute -top-2 -right-2 bg-gold-500 text-black text-[10px] font-black px-2 py-1 rounded-md shadow-lg z-10">
                                        PRINCIPALE
                                    </div>
        
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-10 h-7 bg-gradient-to-br from-orange-400 to-red-600 rounded-md"></div>
                                        <i class="fa-brands fa-cc-visa text-2xl text-white/50"></i>
                                    </div>
                                    <p class="font-mono text-lg tracking-wider mb-1">**** '.$card["last_4"].'</p>
                                    <p class="text-[10px] text-slate-400 uppercase">'.$card["bank_name"].' - '.$card["card_name"].'</p>
                                </div>
                            </label>
                        ';
                    }
                    ?>
                    <!-- CARTE 1 (Cochée par défaut) -->
                </div>
                
                <p class="text-[10px] text-slate-500 italic">La carte principale sera utilisée par défaut pour recevoir les fonds de vos contacts via Email ou ID.</p>
            </div>

            

            <!-- 2️⃣ COLONNE DROITE: TRANSFERT & HISTORIQUE -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- FORMULAIRE (Manuel) -->
                <section class="glass-panel rounded-[2rem] p-8 animate-slide-up" style="animation-delay: 0.2s;">
                    <h3 class="text-xl font-bold mb-8 flex items-center gap-3">
                        <div class="w-1 h-6 bg-gold-500 rounded-full"></div> Nouveau Transfert
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 uppercase font-bold ml-1">Destinataire</label>
                                <div class="relative">
                                    <i class="fa-solid fa-at absolute left-4 top-1/2 -translate-y-1/2 text-gold-500"></i>
                                    <input type="text" name="sender_email" placeholder="Email ou ID unique" 
                                        class="w-full bg-black/40 border border-white/10 rounded-xl py-4 pl-12 pr-4 text-white focus:border-gold-500 outline-none transition-all">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs text-slate-400 uppercase font-bold ml-1">Montant (DH)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-coins absolute left-4 top-1/2 -translate-y-1/2 text-gold-500"></i>
                                    <input type="number" name="sender_amount" placeholder="0.00" 
                                        class="w-full bg-black/40 border border-white/10 rounded-xl py-4 pl-12 pr-4 text-white focus:border-gold-500 outline-none transition-all font-mono text-lg">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs text-slate-400 uppercase font-bold ml-1">Note (Optionnel)</label>
                            <input type="text" name="sender_description" placeholder="Ex: Remboursement dîner..." 
                                class="w-full bg-black/40 border border-white/10 rounded-xl py-4 px-4 text-white focus:border-gold-500 outline-none transition-all">
                        </div>

                        <button type="submit" name="sender_button" class="btn-shine-anim w-full bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-black font-black py-4 rounded-xl shadow-xl shadow-gold-500/10 transform transition active:scale-95">
                            CONFIRMER L'ENVOI
                        </button>
                    </div>
                </section>
                
                </section>

                <!-- HISTORIQUE (Rempli manuellement) -->
                <section class="glass-panel rounded-[2rem] p-8 animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <div class="w-1 h-6 bg-gold-500 rounded-full"></div> Flux de Transferts
                        </h3>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-white/5 rounded-full text-[10px] text-emerald-400 border border-emerald-500/20">Reçus</span>
                            <span class="px-3 py-1 bg-white/5 rounded-full text-[10px] text-gold-400 border border-gold-500/20">Envoyés</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Transaction 1 : Reçu -->
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 shadow-inner">
                                    <i class="fa-solid fa-arrow-down-long"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Yassine El Amrani</p>
                                    <p class="text-xs text-slate-500">ID: #8829 • 20 Déc, 14:20</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-emerald-400">+ 1,200.00 DH</p>
                                <p class="text-[9px] text-slate-600 uppercase tracking-tighter">Vers CIH Principale</p>
                            </div>
                        </div>

                        <!-- Transaction 2 : Envoyé -->
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gold-500/10 flex items-center justify-center text-gold-500 shadow-inner">
                                    <i class="fa-solid fa-arrow-up-long"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Sanae Bennani</p>
                                    <p class="text-xs text-slate-500">sanae@email.com • 18 Déc, 09:15</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-white">- 450.00 DH</p>
                                <p class="text-[9px] text-slate-600 uppercase tracking-tighter">Depuis BMCE</p>
                            </div>
                        </div>

                        <!-- Transaction 3 : Envoyé -->
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gold-500/10 flex items-center justify-center text-gold-500 shadow-inner">
                                    <i class="fa-solid fa-arrow-up-long"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Reda Mansouri</p>
                                    <p class="text-xs text-slate-500">reda.m@work.ma • 15 Déc, 18:40</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-white">- 2,500.00 DH</p>
                                <p class="text-[9px] text-slate-600 uppercase tracking-tighter">Paiement Loyer</p>
                            </div>
                        </div>

                        <!-- Transaction 4 : Reçu -->
                        <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 shadow-inner">
                                    <i class="fa-solid fa-arrow-down-long"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Netflix Premium</p>
                                    <p class="text-xs text-slate-500">Refund #9901 • 12 Déc, 11:00</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-emerald-400">+ 125.00 DH</p>
                                <p class="text-[9px] text-slate-600 uppercase tracking-tighter">Remboursement</p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
                </form>
    </main>

    <script>
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
    </script>
</body>
</html>


