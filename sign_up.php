<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if (isset($_POST["sign_up_email"]) && isset($_POST["sign_up_fullname"]) && isset($_POST["sign_up_password"])) {
        $_SESSION=[];

        $sign_up_fullname = trim($_POST["sign_up_fullname"]);
        $sign_up_email = trim($_POST["sign_up_email"]);
        $hashedPassword = password_hash($_POST["sign_up_password"], PASSWORD_DEFAULT);

        $_SESSION["user"]=[$sign_up_fullname,$sign_up_email,$hashedPassword];
        $stmt = $pdo->prepare("INSERT INTO users(fullname,email,password) VALUES (?,?,?)");
        $stmt->execute($_SESSION["user"]);
    } 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Smart Wallet Elite</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        gold: {
                            300: '#FDE047',
                            400: '#FACC15',
                            500: '#EAB308',
                            600: '#CA8A04',
                            glow: 'rgba(250, 204, 21, 0.5)' 
                        },
                        dark: {
                            900: '#020617',
                            800: '#0F172A',
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'float-reverse': 'floatReverse 10s ease-in-out infinite',
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                        'text-shimmer': 'textShimmer 3s linear infinite',
                        'pulse-gold': 'pulseGold 3s infinite',
                        'spin-slow': 'spin 15s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        floatReverse: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(20px) rotate(5deg)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(50px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        textShimmer: {
                            '0%': { backgroundPosition: '200% center' },
                            '100%': { backgroundPosition: '-200% center' },
                        },
                        pulseGold: {
                            '0%': { boxShadow: '0 0 0 0 rgba(250, 204, 21, 0.4)' },
                            '70%': { boxShadow: '0 0 0 15px rgba(250, 204, 21, 0)' },
                            '100%': { boxShadow: '0 0 0 0 rgba(250, 204, 21, 0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* --- STYLES GLOBAUX --- */
        body {
            background-color: #020617;
            overflow: hidden;
        }

        .bg-grid {
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* --- CÔTÉ DROIT : LE FORMULAIRE (Glassmorphism Original) --- */
        .glass-panel {
            background: rgba(10, 10, 20, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(253, 224, 71, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(253, 224, 71, 0.02);
        }

        /* Inputs Custom (Style original conservé) */
        .custom-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .custom-input:focus {
            background: rgba(253, 224, 71, 0.05);
            border-color: #FACC15;
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.2);
        }

        .custom-input:focus ~ label,
        .custom-input:not(:placeholder-shown) ~ label {
            transform: translateY(-28px) scale(0.9);
            color: #FACC15;
        }

        /* --- CÔTÉ GAUCHE : VISUEL 3D --- */
        .visual-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .circle-orbit {
            position: absolute;
            border: 1px dashed rgba(250, 204, 21, 0.3);
            border-radius: 50%;
            animation: spin 20s linear infinite;
        }

        /* Texte doré scintillant */
        .text-gold-gradient {
            background: linear-gradient(to right, #FDE047, #EAB308, #FFF7ED, #EAB308, #FDE047);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: textShimmer 4s linear infinite;
        }
        
        /* Bouton Shine */
        .btn-shine-effect::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            transform: skewX(-20deg);
            animation: shinePass 3s infinite ease-in-out;
        }
        @keyframes shinePass {
            0% { left: -100%; } 100% { left: 200%; }
        }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden">

    <!-- =============================================
         PARTIE GAUCHE (DESIGN & PHRASE) - 50%
    ============================================= -->
    <div class="hidden lg:flex w-1/2 relative bg-dark-900 border-r border-white/5 items-center justify-center p-12 overflow-hidden">
        
        <!-- Background Animé (Gauche) -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-gold-900/20 via-dark-900 to-dark-900"></div>
        <div class="circle-orbit w-[600px] h-[600px]"></div>
        <div class="circle-orbit w-[400px] h-[400px] border-gold-500/20" style="animation-duration: 15s; animation-direction: reverse;"></div>

        <!-- Contenu Visuel -->
        <div class="relative z-10 max-w-lg">
            
            <!-- Logo Flottant -->
            <div class="mb-8 flex items-center gap-3 animate-slide-up">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center shadow-lg shadow-gold-500/20">
                    <i class="fa-solid fa-wallet text-dark-900 text-2xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Smart Wallet</span>
            </div>

            <!-- PHRASE D'ACCROCHE -->
            <h1 class="text-5xl font-serif font-bold leading-tight mb-6 animate-slide-up" style="animation-delay: 0.1s;">
                L'art de maîtriser <br>
                <span class="text-gold-gradient font-sans italic">votre fortune.</span>
            </h1>
            
            <p class="text-slate-400 text-lg leading-relaxed mb-10 animate-slide-up" style="animation-delay: 0.2s;">
                Rejoignez une plateforme exclusive conçue pour l'élite financière. Sécurité maximale, design d'exception.
            </p>

            <!-- ÉLÉMENT 3D FLOTTANT (KEYFRAMES) -->
            <div class="relative h-64 w-full animate-float">
                <!-- Carte Arrière -->
                <div class="absolute top-4 left-8 w-64 h-40 bg-dark-800 rounded-2xl border border-white/10 opacity-60 rotate-[-5deg]"></div>
                
                <!-- Carte Principale (Gold) -->
                <div class="absolute top-0 left-0 w-80 h-48 bg-gradient-to-br from-gold-500 to-yellow-600 rounded-2xl shadow-2xl shadow-gold-500/30 p-6 flex flex-col justify-between transform rotate-3 hover:rotate-0 transition duration-500">
                    <div class="flex justify-between items-center">
                        <i class="fa-brands fa-cc-visa text-3xl text-white/90"></i>
                        <i class="fa-solid fa-wifi rotate-90 text-white/70"></i>
                    </div>
                    <div>
                        <div class="text-white/80 font-mono tracking-widest text-lg">**** **** **** 8842</div>
                        <div class="flex justify-between mt-2 text-xs uppercase text-white/60 font-bold">
                            <span>John Doe</span>
                            <span>Premium</span>
                        </div>
                    </div>
                    <!-- Shine sur la carte -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent rounded-2xl pointer-events-none"></div>
                </div>

                <!-- Badge Flottant -->
                <div class="absolute -right-4 bottom-10 bg-dark-900/90 backdrop-blur border border-gold-500/50 px-4 py-2 rounded-lg flex items-center gap-3 shadow-xl animate-float-reverse">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-xs font-bold text-white">Sécurisé 100%</span>
                </div>
            </div>

        </div>
    </div>

    <!-- =============================================
         PARTIE DROITE (FORMULAIRE) - 50%
    ============================================= -->
    <div class="w-full lg:w-1/2 relative bg-dark-900 bg-grid flex items-center justify-center p-6 overflow-y-auto">
        
        <!-- Lumières d'ambiance (pour le Glassmorphism) -->
        <div class="fixed top-10 right-10 w-64 h-64 bg-gold-600/20 rounded-full blur-[80px] pointer-events-none"></div>
        <div class="fixed bottom-10 left-[55%] w-64 h-64 bg-blue-900/20 rounded-full blur-[80px] pointer-events-none"></div>

        <!-- Bouton Retour -->
        <a href="index.php" class="absolute top-8 right-8 text-slate-400 hover:text-gold-300 transition flex items-center gap-2 z-50">
            <span class="text-sm font-medium">Accueil</span>
            <i class="fa-solid fa-house"></i>
        </a>

        <!-- CARTE FORMULAIRE (Code Original Conservé) -->
        <div class="glass-panel rounded-3xl p-10 w-full max-w-[450px] animate-slide-up relative z-10">
            
            <!-- HEADER FORM -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gold-400/10 border border-gold-400/20 mb-4 text-gold-400">
                    <i class="fa-solid fa-user-plus text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-2 tracking-tight">
                    <span class="text-gold-gradient">Créer un compte</span>
                </h2>
                <p class="text-slate-400 text-sm">Entrez vos informations pour commencer.</p>
            </div>

            <!-- FORMULAIRE -->
            <form action="sign_up.php" method="POST" class="space-y-6">

                <!-- Nom Complet -->
                <div class="relative">
                    <input type="text" required id="name" name="sign_up_fullname" class="custom-input peer w-full h-12 px-4 rounded-xl outline-none text-white placeholder-transparent font-medium" placeholder="Nom">
                    <label for="name" class="absolute left-4 top-3 text-slate-500 text-sm transition-all pointer-events-none flex items-center gap-2">
                        <i class="fa-regular fa-user"></i> Nom complet
                    </label>
                </div>

                <!-- Email -->
                <div class="relative">
                    <input type="email" required id="email" name="sign_up_email" class="custom-input peer w-full h-12 px-4 rounded-xl outline-none text-white placeholder-transparent font-medium" placeholder="Email">
                    <label for="email" class="absolute left-4 top-3 text-slate-500 text-sm transition-all pointer-events-none flex items-center gap-2">
                        <i class="fa-regular fa-envelope"></i> Adresse Email
                    </label>
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <input type="password" required id="password" name="sign_up_password" class="custom-input peer w-full h-12 px-4 rounded-xl outline-none text-white placeholder-transparent font-medium" placeholder="Password">
                    <label for="password" class="absolute left-4 top-3 text-slate-500 text-sm transition-all pointer-events-none flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> Mot de passe
                    </label>
                    <button type="button" onclick="togglePass()" class="absolute right-4 top-3 text-slate-500 hover:text-gold-300 transition">
                        <i id="eye" class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <!-- Checkbox -->
                <div class="flex items-start gap-3">
                    <div class="relative flex items-center mt-1">
                        <input type="checkbox" id="cgu" class="peer sr-only">
                        <div class="w-5 h-5 border border-slate-600 rounded bg-slate-900 peer-checked:bg-gold-500 peer-checked:border-gold-500 transition-all cursor-pointer"></div>
                        <i class="fa-solid fa-check text-black text-[10px] absolute left-1 top-1.5 opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                        <label for="cgu" class="absolute inset-0 cursor-pointer"></label>
                    </div>
                    <p class="text-xs text-slate-400">J'accepte les <a href="#" class="text-gold-300 underline hover:text-gold-400">Conditions</a> et la <a href="#" class="text-gold-300 underline hover:text-gold-400">Politique</a>.</p>
                </div>

                <!-- Bouton Principal (GOLD & YELLOW) -->
                <button type="submit" class="w-full py-4 rounded-xl font-bold text-black bg-gradient-to-r from-gold-300 via-gold-500 to-gold-400 shadow-[0_10px_30px_rgba(234,179,8,0.3)] hover:shadow-[0_15px_40px_rgba(234,179,8,0.5)] transform hover:-translate-y-1 transition duration-300 btn-shine-effect animate-pulse-gold tracking-wide uppercase text-sm relative overflow-hidden">
                    Créer mon compte
                </button>

            </form>

            <!-- Social Login -->
            <div class="mt-8">
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink-0 mx-4 text-xs text-slate-500 uppercase tracking-widest">Ou via</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button class="flex justify-center items-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 hover:border-gold-400/50 hover:text-gold-300 transition text-sm text-slate-300 font-medium group">
                        <i class="fa-brands fa-google group-hover:scale-110 transition"></i> Google
                    </button>
                    <button class="flex justify-center items-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 hover:border-gold-400/50 hover:text-gold-300 transition text-sm text-slate-300 font-medium group">
                        <i class="fa-brands fa-apple group-hover:scale-110 transition"></i> Apple
                    </button>
                </div>
            </div>

            <!-- Footer Link -->
            <p class="text-center mt-6 text-sm text-slate-400">
                Déjà membre ? <a href="login.php" class="text-gold-300 font-semibold hover:text-white transition">Connexion</a>
            </p>

        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye');
            if(input.type === 'password'){
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>


