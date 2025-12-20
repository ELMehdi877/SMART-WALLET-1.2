<?php
session_start();
if (!isset($_SESSION["user_existe"])) {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION["user_existe"][0];
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");

//add card

if ($_SERVER["REQUEST_METHOD"] === "POST" ) {
    if (isset($_POST["add_card"]) && isset($_POST["bank_name"]) && isset($_POST["card_name"]) && isset($_POST["last_4"]) && isset($_POST["balance"])) {
        $bank_name = $_POST["bank_name"];
        $card_name = $_POST["card_name"];
        $last_4 = $_POST["last_4"];
        $balance = $_POST["balance"];
        $stmt = $pdo->prepare("INSERT INTO cards(user_id,bank_name,card_name,last_4,balance) VALUES (?,?,?,?,?)");

        $stmt->execute([$user_id,$bank_name,$card_name,$last_4,$balance]);
        header("Location: cards.php");
        exit();
    }
    //delete card
    if (isset($_POST["delete_card_id"])) {
        $delete_card_id = $_POST["delete_card_id"];
        $stmt = $pdo->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->execute([$delete_card_id]);

        header("Location: cards.php");
        exit;
    }
}




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

    <!-- <script>
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
    </script> -->

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
                <a href="index.php" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Dashboard</a>
                <a href="#" class="px-5 py-2 rounded-full bg-gold-500 text-black font-bold text-sm shadow-lg shadow-gold-500/20 transition">Mes Cartes</a>
                <a href="#" class="px-5 py-2 rounded-full text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition">Revenus</a>
            </div>
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
            <a href="#benefits"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.1s; opacity: 0;">Dashboard</a>
            <a href="#security"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.2s; opacity: 0;">Mes Cartes</a>
            <a href="#business"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.3s; opacity: 0;">Revenus</a>

            <div class="w-12 h-1 bg-white/10 rounded-full my-4 mobile-link" style="animation-delay: 0.4s; opacity: 0;">
            </div>

            
            <form action="logout.php" method="POST">
                <button type="submit" name="logout"
                style="animation-delay: 0.3s; opacity: 0;" class="btn-hover-effect btn-shine-anim mobile-link btn-hover-effect btn-shine-anim px-8 py-4 bg-red-500/80 text-black font-bold rounded-full shadow-xl"
                >
                déconnexion
             </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="pt-28 pb-12 px-4 max-w-7xl mx-auto space-y-8">

        <!-- HEADER SECTION -->
        <header class="flex flex-col md:flex-row justify-between items-end gap-4 animate-card-enter">
            <div>
                <p class="text-slate-400 text-xs font-mono uppercase tracking-widest mb-1">Portefeuille Digital</p>
                <h1 class="text-4xl font-bold text-white">Gestion des <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-400 to-amber-600">Cartes</span></h1>
            </div>
            <button onclick="openModal('addCardModal')" class="bg-gradient-to-r from-gold-400 to-gold-600 hover:to-gold-500 text-black font-bold px-6 py-3 rounded-xl shadow-[0_0_20px_rgba(234,179,8,0.3)] transition transform hover:-translate-y-1 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Nouvelle Carte
            </button>
        </header>

        <!-- 1️⃣ SECTION CARTES (GRID) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- CARTE 1: CIH (Exemple) -->
             
            <?php
                $stmt = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach($cards as $card){
                    echo '
                <div class="group perspective animate-card-enter" style="animation-delay: 0.1s;">
                    <div class="credit-card card-cih" onclick="this.classList.toggle(\'flipped\')">
                        <div class="card-texture"></div>
                        
                        <!-- FRONT -->
                        <div class="card-front p-6 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <div class="chip"></div>
                                <i class="fa-solid fa-wifi rotate-90 text-white/70 text-2xl"></i>
                            </div>
                            
                            <div class="text-center">
                                <div class="text-white/80 text-xs uppercase tracking-widest mb-1">Solde Actuel</div>
                                <div class="text-3xl font-mono font-bold text-white drop-shadow-md">
                                    '.$card['balance'].' <span class="text-sm">DH</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-end">
                                <div>
                                    <div class="text-white/60 text-[10px] uppercase font-bold">BANK</div>
                                    <div class="text-white font-mono tracking-wider text-sm">'.$card["bank_name"].'</div>
                                    <div class="text-white/60 text-[10px] uppercase font-bold">NOM</div>
                                    <div class="text-white font-mono tracking-wider text-sm">'.$card["card_name"].'</div>
                                </div>
                                
                                <i class="fa-brands fa-cc-visa text-4xl text-white"></i>
                            </div>

                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/40 font-mono text-sm tracking-[0.2em]">
                                **** '.$card['last_4'].'
                            </div>
                        </div>

                        <!-- BACK -->
                        <div class="card-back">
                            <div class="magnetic-strip"></div>
                            <div class="px-6">
                                <div class="bg-white/20 h-8 w-full flex items-center justify-end px-2 text-black font-mono font-bold italic">
                                    123
                                </div>
                                <div class="mt-4 text-[10px] text-slate-400 text-center">
                                    CIH BANK - CODE 30
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions sous la carte -->
                    <form action="cards.php" method="POST" class="flex justify-center gap-4 mt-4 opacity-0 group-hover:opacity-100 transition duration-300 transform translate-y-2 group-hover:translate-y-0">
                        <button name="datail_card" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 bg-white/5 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-eye"></i> Détails
                        </button>
                        <button name="delete_card_id" value="'.$card['id'].'" class="text-xs text-slate-400 hover:text-red-400 flex items-center gap-1 bg-white/5 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-trash"></i> Suppr.
                        </button>
                    </form>
                </div>
                ';
                }
            ?>


            <!-- CARTE 3: Ajouter (Placeholder visuel) -->
            <div onclick="openModal('addCardModal')" class="border-2 border-dashed border-white/10 rounded-3xl flex flex-col items-center justify-center p-6 cursor-pointer hover:border-gold-500/50 hover:bg-gold-500/5 transition group h-full min-h-[220px]">
                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <i class="fa-solid fa-plus text-2xl text-slate-500 group-hover:text-gold-400"></i>
                </div>
                <h3 class="text-slate-400 font-bold group-hover:text-white">Ajouter une carte</h3>
                <p class="text-xs text-slate-600 mt-2 text-center">Lier une nouvelle banque pour séparer vos budgets.</p>
            </div>

        </div>

        <!-- 2️⃣ SECTION AFFECTATION REVENUS -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8 border-t border-white/5">
            
            <!-- Formulaire d'affectation -->
            <div class="lg:col-span-1 glass-panel rounded-3xl p-6 h-fit sticky top-28">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-gold-400 rounded-full"></span> Affecter un Revenu
                </h3>
                <form action="incomes.php" method="POST" class="space-y-4">
                    
                    <!-- <div>
                        <label class="text-xs text-slate-400 uppercase font-bold ml-1">Sélectionner un Revenu</label>
                        <select name="income_category" class="w-full bg-black/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 transition">
                            <option value="" disabled selected>Choisir une catégorie</option>
                            <option value="ALL">ALL</option>
                            <option value="Salaire">Salaire</option>
                            <option value="Prime">Prime</option>
                            <option value="Bonus">Bonus</option>
                            <option value="Revenus freelancing">Revenus freelancing</option>
                        </select>
                        
                    </div> -->
                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold">MONTANTS</label>
                        <input type="number" required name="income_amount" placeholder="0.00" class="w-full bg-gray-500/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                    </div>
                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold">description</label>
                        <input type="text" required name="income_description" placeholder="description" class="w-full bg-gray-500/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                    </div>
                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold">DATE</label>
                        <input type="date" name="income_date" class="w-full bg-gray-500/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                    </div>
                    <div class="flex justify-center my-2">
                        <i class="fa-solid fa-arrow-down text-gold-400 animate-bounce"></i>
                    </div>

                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold ml-1">Vers la Carte</label>
                        <div class="grid grid-cols-2 gap-3 mt-1">
                            <!-- Option Radio Stylisée -->

                            <label class="cursor-pointer">
                                <input type="radio" name="card_id" value="0" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-white/10 bg-white/5 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition flex flex-col items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                    <span class="text-xs font-bold">1er card</span>
                                </div>
                            </label>
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
                            $stmt->execute([$user_id]);
                            
                            $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($cards as $card){
                            echo '<label class="cursor-pointer">
                                    <input type="radio" name="card_id" value="'.$card['id'].'" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-white/10 bg-white/5 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition flex flex-col items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                        <span class="text-xs font-bold">'.$card['card_name'].'</span>
                                    </div>
                                </label>';
                                }
                            
                            ?>
                            <!-- <label class="cursor-pointer">
                                <input type="radio" name="card_target" value="cih" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-white/10 bg-white/5 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition flex flex-col items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                    <span class="text-xs font-bold">CIH Bank</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="card_target" value="bp" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-white/10 bg-white/5 peer-checked:border-gold-500 peer-checked:bg-gold-500/10 transition flex flex-col items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-700"></span>
                                    <span class="text-xs font-bold">B. Populaire</span>
                                </div>
                            </label> -->
                        </div>
                    </div>

                    <button type="submit" name="income_affect" class="w-full mt-4 bg-white text-black font-bold py-3 rounded-xl hover:bg-gold-400 transition shadow-lg">
                        Valider l'affectation
                    </button>
                </form>
            </div>

            <!-- Historique des mouvements -->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Dernières Affectations</h3>
                    <button class="text-xs text-gold-400 hover:text-white">Voir tout</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-slate-500 uppercase border-b border-white/10">
                            <tr>
                                <th class="pb-3 pl-2">Source</th>
                                <th class="pb-3">Vers Carte</th>
                                <th class="pb-3">Montant</th>
                                <th class="pb-3 text-right pr-2">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <!-- Ligne 1 -->
                            <tr class="group hover:bg-white/5 transition">
                                <td class="py-4 pl-2">
                                    <div class="font-bold text-white">Salaire Janvier</div>
                                    <div class="text-[10px] text-slate-400">25/01/2025</div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-4 rounded bg-orange-500"></div>
                                        <span class="text-slate-300">CIH Bank</span>
                                    </div>
                                </td>
                                <td class="py-4 font-mono font-bold text-emerald-400">+ 15,000 DH</td>
                                <td class="py-4 text-right pr-2">
                                    <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-xs border border-emerald-500/20">Reçu</span>
                                </td>
                            </tr>
                            <!-- Ligne 2 -->
                            <tr class="group hover:bg-white/5 transition">
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </main>

    <!-- 3️⃣ MODAL: AJOUTER UNE CARTE -->
    <div id="addCardModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="glass-panel w-full max-w-md rounded-2xl p-8 relative animate-card-enter">
            <button onclick="closeModal('addCardModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            
            <h3 class="text-2xl font-bold text-white mb-6">Ajouter une Carte</h3>
            
            <form action="cards.php" method="POST" class="space-y-5">
                
                <!-- Choix de la banque (Style Visuel) -->
                <!-- <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Banque</label>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_type" value="cih" class="peer sr-only" checked>
                            <div class="h-12 rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 peer-checked:ring-2 ring-white ring-offset-2 ring-offset-black opacity-50 peer-checked:opacity-100 flex items-center justify-center font-bold text-xs text-white">CIH</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_type" value="bp" class="peer sr-only">
                            <div class="h-12 rounded-lg bg-gradient-to-br from-amber-600 to-amber-900 peer-checked:ring-2 ring-white ring-offset-2 ring-offset-black opacity-50 peer-checked:opacity-100 flex items-center justify-center font-bold text-xs text-white">BP</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_type" value="attijari" class="peer sr-only">
                            <div class="h-12 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-700 peer-checked:ring-2 ring-white ring-offset-2 ring-offset-black opacity-50 peer-checked:opacity-100 flex items-center justify-center font-bold text-xs text-white">AWB</div>
                        </label>
                    </div>
                </div> -->
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Banque</label>
                    <input type="text" required name="bank_name" placeholder="Ex: CHI / BMCE .." class="w-full bg-black/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                </div>

                <!-- non de carte -->
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Nom</label>
                    <input type="text" required name="card_name" placeholder="Nom de la carte" class="w-full bg-black/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                </div>

                <!-- Numéro Carte -->
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">4 Derniers chiffres</label>
                    <input type="text" required name="last_4" maxlength="4" placeholder="Ex: 4291" class="w-full bg-black/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500 font-mono tracking-widest text-center text-xl">
                </div>

                <!-- Solde Initial -->
                <div>
                    <label class="text-xs text-slate-400 uppercase font-bold">Solde Initial</label>
                    <input type="number" required name="balance" step="0.01" placeholder="0.00" class="w-full bg-black/40 border border-white/10 rounded-xl p-3 mt-1 text-white outline-none focus:border-gold-500">
                </div>

                <button type="submit" name="add_card" class="w-full py-4 rounded-xl bg-gold-500 text-black font-bold hover:bg-gold-400 transition shadow-[0_0_20px_rgba(234,179,8,0.3)] mt-2">
                    Activer la carte
                </button>

            </form>
        </div>
    </div>

    

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
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
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

