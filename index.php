<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Wallet - L'Excellence Financière</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



    <style>
        /* --- BACKGROUND & AMBIANCE --- */
        body {
            background-color: #020617;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Fond maillé subtil */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
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

        /* Carte Glassmorphism Premium */
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .glass-panel:hover {
            border-color: rgba(234, 179, 8, 0.3);
            background: rgba(15, 23, 42, 0.8);
        }

        /* --- ANIMATION BOUTON (SHINE + PULSE) --- */
        @keyframes shine-move {
            0% {
                transform: skewX(-25deg) translateX(-200%);
            }

            20% {
                transform: skewX(-25deg) translateX(200%);
            }

            100% {
                transform: skewX(-25deg) translateX(200%);
            }
        }

        @keyframes btn-pulse-gold {
            0% {
                box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(234, 179, 8, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(234, 179, 8, 0);
            }
        }

        .btn-shine-anim {
            position: relative;
            overflow: hidden;
            animation: btn-pulse-gold 2s infinite;
        }

        /* Le reflet blanc qui traverse */
        .btn-shine-anim::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent);
            transform: skewX(-25deg) translateX(-200%);
            animation: shine-move 4s infinite ease-in-out;
            pointer-events: none;
        }

        /* --- ANIMATION AU SURVOL (HOVER) --- */

        /* 1. Animation des lettres et de la taille */
        @keyframes letter-expand {
            0% {
                letter-spacing: normal;
                transform: scale(1);
            }

            100% {
                letter-spacing: 1px;
                transform: scale(1.01);
            }
        }

        /* 2. Animation de la lueur intense (Super Glow) */
        @keyframes hover-super-glow {

            0%,
            100% {
                box-shadow: 0 10px 15px -3px rgba(234, 179, 8, 0.3);
                border-color: transparent;
            }

            50% {
                box-shadow: 0 25px 50px -12px rgba(234, 179, 8, 0.7), 0 0 20px rgba(255, 255, 255, 0.4);
                border-color: rgba(255, 255, 255, 0.5);
            }
        }

        /* Classe utilitaire à ajouter aux boutons */
        .btn-hover-effect {
            transition: all 0.3s ease;
        }

        .btn-hover-effect:hover {
            animation:
                letter-expand 0.4s forwards ease-out,
                hover-super-glow 1.5s infinite ease-in-out;
            cursor: pointer;
        }

        /* --- ANIMATION SPÉCIALE BOUTON VERT --- */

        /* 1. Pulsation verte permanente (Ronds qui partent) */
        @keyframes btn-pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            70% {
                box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
            }

            /* L'ombre s'écarte et disparaît */
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* 2. Lueur intense verte au SURVOL */
        @keyframes hover-green-super-glow {

            0%,
            100% {
                box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
                border-color: transparent;
            }

            50% {
                /* Grosse lueur émeraude + petit halo blanc */
                box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.8), 0 0 20px rgba(255, 255, 255, 0.3);
                border-color: rgba(255, 255, 255, 0.6);
            }
        }

        /* Classe 1 : Animation de base (Reflet blanc + Pulsation verte) */
        .btn-green-anim {
            position: relative;
            overflow: hidden;
            animation: btn-pulse-green 2s infinite;
            /* Pulsation verte */
        }

        /* On garde le reflet blanc qui traverse (c'est joli) */
        .btn-green-anim::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.6), transparent);
            transform: skewX(-25deg) translateX(-200%);
            animation: shine-move 4s infinite ease-in-out;
            pointer-events: none;
        }

        /* Classe 2 : Interaction au Hover (Vert) */
        .btn-hover-green {
            transition: all 0.3s ease;
        }

        .btn-hover-green:hover {
            animation:
                letter-expand 0.4s forwards ease-out,
                /* Lettres s'écartent */
                hover-green-super-glow 1.5s infinite ease-in-out;
            /* Super Glow Vert */
            cursor: pointer;
        }

        /* --- MOBILE MENU ANIMATIONS --- */

        /* 1. Animation d'ouverture (Révélation Circulaire) */
        @keyframes menu-reveal {
            0% {
                opacity: 0;
                clip-path: circle(0% at calc(100% - 40px) 40px);
                /* Part du bouton */
            }

            100% {
                opacity: 1;
                clip-path: circle(150% at calc(100% - 40px) 40px);
                /* Couvre tout l'écran */
            }
        }

        /* 2. Apparition des liens en cascade */
        @keyframes link-stagger {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Classes pour le menu mobile */
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(2, 6, 23, 0.95);
            /* Dark-900 très opaque */
            backdrop-filter: blur(20px);
            z-index: 40;
            /* Juste sous la navbar */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            /* État par défaut : caché */
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-menu-overlay.open {
            pointer-events: auto;
            animation: menu-reveal 0.6s cubic-bezier(0.77, 0, 0.175, 1) forwards;
        }

        .mobile-menu-overlay.open .mobile-link {
            animation: link-stagger 0.5s ease forwards;
        }

        /* --- ICÔNE HAMBURGER ANIMÉE --- */
        .menu-toggle-btn {
            width: 40px;
            height: 40px;
            position: relative;
            z-index: 50;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hamburger-line {
            position: absolute;
            width: 24px;
            height: 2px;
            background-color: #FACC15;
            /* Gold */
            border-radius: 2px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            /* Effet élastique */
        }

        /* Lignes individuelles */
        .line-1 {
            transform: translateY(-8px);
            width: 24px;
        }

        .line-2 {
            transform: translateY(0);
            width: 16px;
            margin-left: 8px;
            /* Décalé pour style */
        }

        .line-3 {
            transform: translateY(8px);
            width: 10px;
            margin-left: 14px;
            /* Décalé pour style */
        }

        /* État OUVERT (Transform en Croix) */
        .menu-toggle-btn.active .line-1 {
            transform: translateY(0) rotate(45deg);
            width: 24px;
            background-color: #FFF;
        }

        .menu-toggle-btn.active .line-2 {
            opacity: 0;
            transform: translateX(-20px);
        }

        .menu-toggle-btn.active .line-3 {
            transform: translateY(0) rotate(-45deg);
            width: 24px;
            margin-left: 0;
            background-color: #FFF;
        }
    </style>
</head>

<body class="bg-grid-pattern relative">

    <!-- Orbs de lumière (Background effects) -->
    <div
        class="fixed top-0 left-0 w-[600px] h-[600px] bg-blue-900/20 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2 pointer-events-none">
    </div>
    <div
        class="fixed bottom-0 right-0 w-[500px] h-[500px] bg-emerald-900/10 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3 pointer-events-none">
    </div>

    <!-- 1️⃣ NAVBAR LUXE -->
    <nav class="fixed w-full z-50 transition-all duration-300 backdrop-blur-md border-b border-white/5 bg-dark-900/80">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">

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

                <!-- Liens Desktop (Cachés sur mobile) -->
                <div class="hidden md:flex space-x-10">
                    <a href="#benefits"
                        class="text-sm font-medium text-slate-300 hover:text-white transition">Avantages</a>
                    <a href="#security"
                        class="text-sm font-medium text-slate-300 hover:text-white transition">Sécurité</a>
                    <a href="#business"
                        class="text-sm font-medium text-slate-300 hover:text-white transition">Business</a>
                </div>

                <!-- Auth Actions (Desktop) -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="login.php" class="text-sm font-medium text-slate-300 hover:text-gold-400 transition">Se
                        connecter</a>
                    <a href="sign_up.php"
                        class="btn-hover-effect btn-shine-anim px-6 py-3 rounded-full bg-white text-dark-900 font-bold text-sm hover:bg-gold-400 hover:text-black transition duration-300 shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                        Ouvrir un compte
                    </a>
                </div>

                <!-- BOUTON TOGGLE MOBILE (Visible sur mobile uniquement) -->
                <button class="menu-toggle-btn md:hidden" id="menuToggle" aria-label="Menu">
                    <span class="hamburger-line line-1"></span>
                    <span class="hamburger-line line-2"></span>
                    <span class="hamburger-line line-3"></span>
                </button>
            </div>
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
                style="animation-delay: 0.1s; opacity: 0;">Avantages</a>
            <a href="#security"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.2s; opacity: 0;">Sécurité</a>
            <a href="#business"
                class="mobile-link text-2xl font-heading font-bold text-white hover:text-gold-400 transition"
                style="animation-delay: 0.3s; opacity: 0;">Business</a>

            <div class="w-12 h-1 bg-white/10 rounded-full my-4 mobile-link" style="animation-delay: 0.4s; opacity: 0;">
            </div>

            <a href="login.php" class="mobile-link text-lg text-slate-300 hover:text-white"
                style="animation-delay: 0.5s; opacity: 0;">Se connecter</a>
            <a href="sign_up.php"
                class="mobile-link btn-hover-effect btn-shine-anim px-8 py-4 bg-gold-500 text-black font-bold rounded-full shadow-xl"
                style="animation-delay: 0.6s; opacity: 0;">
                Ouvrir un compte
            </a>
        </div>
    </div>

    <!-- 2️⃣ HERO SECTION -->
    <section class="relative pt-40 pb-20 lg:pt-52 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Contenu Texte -->
                <div class="text-center lg:text-left">
                    <div
                        class="animate-slide-up inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-gold-400 text-xs font-bold uppercase tracking-widest mb-8">
                        <i class="fa-solid fa-star text-[10px]"></i> Nouvelle Génération V2
                    </div>

                    <h1
                        class="animate-slide-up delay-1 font-heading text-5xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6">
                        The Future of <br>
                        <span class="text-gradient-gold">Your Money is Here.</span>
                    </h1>

                    <p
                        class="animate-slide-up delay-2 text-lg text-slate-400 mb-10 max-w-lg mx-auto lg:mx-0 font-light leading-relaxed">
                        L'alliance parfaite entre sécurité bancaire et conception intuitive. Contrôlez vos revenus, vos
                        dépenses et vos virements et fixez vos limites grâce à un tableau de bord exceptionnel.
                    </p>

                    <div
                        class="animate-slide-up delay-3 flex flex-col sm:flex-row gap-5 justify-center lg:justify-start">

                        <!-- APRÈS (Nouveau code avec animation verte) -->
                        <a href="sign_up.php"
                            class="btn-hover-green btn-green-anim flex justify-center items-center group relative px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-xl font-bold text-white transition transform hover:-translate-y-1 overflow-hidden">
                            <span class="relative z-10 flex justify-center items-center gap-2">
                                Commencer maintenant <i
                                    class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                            </span>
                        </a>


                        <a href="login.php"
                            class="px-8 py-4 rounded-xl font-semibold text-slate-300 border border-white/10 hover:bg-white/5 hover:text-white transition flex items-center gap-2 justify-center">
                            Se connecter
                        </a>
                    </div>

                    <!-- Stats Rapides -->
                    <div class="animate-slide-up delay-3 mt-12 pt-8 border-t border-white/5 grid grid-cols-3 gap-8">
                        <div>
                            <div class="text-2xl font-bold text-white">0%</div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider">Frais cachés</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white">Instant</div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider">Transferts</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white">256-bit</div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider">Chiffrement</div>
                        </div>
                    </div>
                </div>

                <!-- Visuel 3D CSS (Carte Premium) -->
                <div class="relative hidden lg:block h-[600px] perspective-1000 animate-slide-up delay-2">

                    <!-- Carte Arrière (Effet Glow Or) -->
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] h-[350px] bg-gold-600/20 rounded-full blur-[100px] animate-pulse">
                    </div>

                    <!-- La Carte Bancaire Principale -->
                    <div
                        class="absolute top-[15%] left-[10%] w-[420px] aspect-[1.586] rounded-3xl p-8 animate-float z-20 shadow-2xl border border-white/10 bg-gradient-to-bl from-slate-800 to-black overflow-hidden group">
                        <!-- Effet Brillant sur la carte -->
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-700">
                        </div>

                        <div class="flex justify-between items-start mb-12">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                                class="h-10 opacity-80" alt="Mastercard">
                            <i class="fa-solid fa-wifi text-slate-500 rotate-90 text-2xl"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="text-gold-400 text-xs uppercase tracking-[0.2em] mb-2">Solde Actuel</div>
                            <div class="text-4xl font-mono text-white tracking-widest mb-8 drop-shadow-lg">124,500 <span
                                    class="text-lg">MAD</span></div>
                        </div>

                        <div
                            class="flex justify-between items-end text-slate-400 font-mono text-sm uppercase tracking-widest">
                            <div>
                                <div class="text-[10px] text-slate-600 mb-1">Titulaire</div>
                                <span class="text-slate-200 font-bold">John Doe</span>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-600 mb-1">Valide</div>
                                <span class="text-slate-200 font-bold">09/28</span>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Flottante (Notification) -->
                    <div
                        class="absolute bottom-[25%] right-[0%] bg-white/10 backdrop-blur-xl border border-white/20 p-4 rounded-2xl flex items-center gap-4 shadow-xl animate-float delay-1 z-30 w-64">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/40">
                            <i class="fa-solid fa-arrow-down"></i>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">Revenu reçu</div>
                            <div class="font-bold text-emerald-400">+ 15,000 MAD</div>
                        </div>
                    </div>

                    <!-- Carte Flottante (Sécurité) -->
                    <div
                        class="absolute top-[5%] right-[5%] bg-dark-900 border border-gold-500/30 p-3 rounded-xl flex items-center gap-3 shadow-xl animate-float delay-2 z-10">
                        <i class="fa-solid fa-shield-halved text-gold-500"></i>
                        <span class="text-xs font-bold text-gold-100">Smart Guard Active</span>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- 3️⃣ CAROUSEL LOGOS (Social Proof) -->
    <section class="py-10 border-y border-white/5 bg-black/20">
        <div class="max-w-7xl mx-auto px-6 overflow-hidden">
            <p class="text-center text-slate-600 text-xs font-bold uppercase tracking-widest mb-6">Ils nous font
                confiance</p>
            <div
                class="flex justify-between items-center opacity-40 grayscale hover:grayscale-0 transition duration-500 flex-wrap gap-8">
                <i class="fa-brands fa-aws text-4xl hover:text-white transition"></i>
                <i class="fa-brands fa-stripe text-4xl hover:text-white transition"></i>
                <i class="fa-brands fa-google-pay text-4xl hover:text-white transition"></i>
                <i class="fa-brands fa-cc-visa text-4xl hover:text-white transition"></i>
                <i class="fa-solid fa-building-columns text-3xl hover:text-white transition"></i>
            </div>
        </div>
    </section>

    <!-- 4️⃣ FEATURES (BENTO GRID STYLE) -->
    <section id="benefits" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading text-3xl md:text-4xl font-bold mb-4">La Technologie au service de votre <span
                        class="text-emerald-400">Argent</span></h2>
                <p class="text-slate-400 max-w-2xl mx-auto">Une suite complète d'outils pour sécuriser, analyser et
                    faire croître votre patrimoine.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Box 1: Sécurité (Large) -->
                <div class="md:col-span-2 glass-panel p-8 rounded-3xl relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-emerald-600/10 rounded-full blur-3xl group-hover:bg-emerald-600/20 transition">
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/20">
                            <i class="fa-solid fa-fingerprint"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Authentification Biométrique & IP</h3>
                            <p class="text-slate-400 leading-relaxed">Nous protégeons chaque connexion. Si une nouvelle
                                adresse IP est détectée, un code OTP est immédiatement exigé. Vos données sont
                                inviolables.</p>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Vitesse -->
                <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 text-2xl border border-blue-500/20 mb-6">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Transferts Éclairs</h3>
                    <p class="text-slate-400 text-sm">Envoyez de l'argent à n'importe quel utilisateur Smart Wallet
                        instantanément, sans délai bancaire.</p>
                </div>

                <!-- Box 3: Budget -->
                <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group">
                    <div
                        class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 text-2xl border border-purple-500/20 mb-6">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Limites Intelligentes</h3>
                    <p class="text-slate-400 text-sm">Définissez des plafonds par catégorie (Loyer, Loisirs...).
                        L'application vous bloque avant le découvert.</p>
                </div>

                <!-- Box 4: Premium (Large) -->
                <div
                    class="md:col-span-2 glass-panel p-8 rounded-3xl relative overflow-hidden group border-gold-500/20">
                    <div class="absolute -right-10 -bottom-10 opacity-10 rotate-12">
                        <i class="fa-solid fa-wallet text-9xl text-gold-400"></i>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gold-500/10 flex items-center justify-center text-gold-400 text-3xl border border-gold-500/20">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Gestion Multi-Cartes</h3>
                            <p class="text-slate-400 leading-relaxed">Centralisez vos comptes (CIH, Attijari, Cash...)
                                en un seul endroit. Affectez vos revenus à des cartes spécifiques pour une clarté
                                totale.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5️⃣ CALL TO ACTION FINAL -->
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-6">
            <div
                class="relative rounded-[2rem] p-12 overflow-hidden text-center border border-white/10 bg-gradient-to-b from-dark-800 to-black shadow-2xl">
                <!-- Glow Effect -->
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-gold-400 to-transparent shadow-[0_0_20px_rgba(234,179,8,0.5)]">
                </div>

                <h2 class="font-heading text-4xl md:text-5xl font-bold mb-6 text-white">Rejoignez l'élite financière.
                </h2>
                <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto">Créez votre compte gratuitement aujourd'hui et
                    prenez le contrôle total de votre avenir financier.</p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="sign_up.php"
                        class="btn-hover-effect btn-shine-anim px-10 py-4 bg-gold-500 hover:bg-gold-400 text-black font-bold rounded-full transition transform hover:scale-105 shadow-lg shadow-gold-500/20">
                        Ouvrir mon compte
                    </a>
                    <a href="login.php"
                        class="px-10 py-4 bg-transparent border border-slate-700 text-white font-bold rounded-full hover:bg-white/5 transition">
                        Connexion
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 6️⃣ FOOTER -->
    <footer class="border-t border-white/5 bg-[#010409] pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
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
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-xs">
                        La plateforme de gestion financière sécurisée conçue pour ceux qui exigent le meilleur.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Produit</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-gold-400 transition">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition">Sécurité</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition">App Mobile</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Légal</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-gold-400 transition">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition">CGU</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition">Contact</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-600">
                <p>&copy; 2025 Smart Wallet Inc. Tous droits réservés.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <i class="fa-brands fa-twitter hover:text-white cursor-pointer transition"></i>
                    <i class="fa-brands fa-linkedin hover:text-white cursor-pointer transition"></i>
                    <i class="fa-brands fa-instagram hover:text-white cursor-pointer transition"></i>
                </div>
            </div>
        </div>
    </footer>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            400: '#FACC15',
                            500: '#EAB308',
                            600: '#CA8A04',
                        },
                        dark: {
                            900: '#020617', // Rich Black
                            800: '#0F172A', // Slate Dark
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }

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