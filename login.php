<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=smart_wallet","root","");
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if (isset($_POST["login_email"]) && isset($_POST["login_password"])) {
        $login_password = $_POST["login_password"];
        $login_email = trim($_POST["login_email"]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$login_email]);
        $user_existe = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user_existe && password_verify($login_password,$user_existe['password'])) {
            $_SESSION['user_existe'] = [$user_existe['id']];
            // echo "valider";
            header("Location: cards.php");
        }
        else {
            echo "non valider";   
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Smart Wallet Elite</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,600&family=Space+Grotesk:wght@300;500;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        mono: ['Space Grotesk', 'monospace'],
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
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
                        'text-shimmer': 'textShimmer 3s linear infinite',
                        'pulse-gold': 'pulseGold 3s infinite',
                        'spin-slow': 'spin 15s linear infinite',
                        'spin-reverse': 'spinReverse 20s linear infinite',
                        'spin-3d': 'spin3d 10s linear infinite',
                        'scan': 'scan 4s ease-in-out infinite',
                        'blink': 'blink 2s infinite',
                    },
                    keyframes: {
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
                        },
                        spinReverse: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(-360deg)' },
                        },
                        spin3d: {
                            '0%': { transform: 'rotateX(0deg) rotateY(0deg)' },
                            '100%': { transform: 'rotateX(360deg) rotateY(180deg)' },
                        },
                        scan: {
                            '0%, 100%': { top: '0%', opacity: '0' },
                            '10%': { opacity: '1' },
                            '50%': { top: '100%', opacity: '1' },
                            '90%': { opacity: '1' },
                        },
                        blink: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.3' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #02061713;
            overflow: hidden;
        }

        .bg-grid {
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* --- GLASSMORPHISM (DROITE) --- */
        .glass-panel {
            background: rgba(10, 10, 20, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(253, 224, 71, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(253, 224, 71, 0.02);
        }

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

        .custom-input:focus~label,
        .custom-input:not(:placeholder-shown)~label {
            transform: translateY(-28px) scale(0.9);
            color: #FACC15;
        }

        .text-gold-gradient {
            background: linear-gradient(to right, #FDE047, #EAB308, #FFF7ED, #EAB308, #FDE047);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: textShimmer 4s linear infinite;
        }

        .btn-shine-effect::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            transform: skewX(-20deg);
            animation: shinePass 3s infinite ease-in-out;
        }

        @keyframes shinePass {
            0% {
                left: -100%;
            }

            100% {
                left: 200%;
            }
        }

        /* --- STYLES SPÉCIAUX GAUCHE (GYROSCOPE) --- */
        .gyro-container {
            position: relative;
            perspective: 1000px;
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .gyro-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(250, 204, 21, 0.1);
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.05);
        }

        .ring-1 {
            width: 100%;
            height: 100%;
            border-top: 2px solid #FACC15;
            border-bottom: 2px solid #FACC15;
            animation: spin-slow 10s linear infinite;
        }

        .ring-2 {
            width: 80%;
            height: 80%;
            border-left: 2px solid #FACC15;
            border-right: 2px solid #FACC15;
            animation: spin-reverse 8s linear infinite;
        }

        .ring-3 {
            width: 60%;
            height: 60%;
            border: 1px dashed #FACC15;
            animation: spin-3d 12s linear infinite;
        }

        .core {
            width: 20px;
            height: 20px;
            background: #FACC15;
            border-radius: 50%;
            box-shadow: 0 0 30px #FACC15, 0 0 60px #FACC15;
            animation: pulse-gold 2s infinite;
        }

        .scanner-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #FACC15, transparent);
            box-shadow: 0 0 15px #FACC15;
            animation: scan 4s ease-in-out infinite;
            opacity: 0.8;
        }

        .data-stream {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(250, 204, 21, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }
    </style>
</head>

<body class="h-screen w-full flex overflow-hidden ">

    <!-- =============================================
         PARTIE GAUCHE (NOUVEAU DESIGN GYROSCOPE) - 50%
    ============================================= -->
    <div
        class="hidden lg:flex w-1/2 relative bg-dark-900 border-r border-white/5 items-center justify-center p-12 overflow-hidden">

        <!-- Fond Dégradé & Maillage -->
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-gold-900/20 via-dark-900 to-black">
        </div>
        <div class="absolute inset-0 bg-grid opacity-20"></div>

        <!-- Animation Centrale : Le Gyroscope Quantique -->
        <div class="relative z-10 flex flex-col items-center">

            <div class="gyro-container">
                <!-- Data Glow -->
                <div class="data-stream"></div>

                <!-- Anneaux Rotatifs -->
                <div class="gyro-ring ring-1"></div>
                <div class="gyro-ring ring-2"></div>
                <div class="gyro-ring ring-3"></div>

                <!-- Scanner -->
                <div class="scanner-line"></div>

                <!-- Coeur -->
                <div class="core"></div>
            </div>

            <!-- Texte Technique -->
            <div class="mt-12 text-center relative z-20 animate-slide-up">
                <!-- Indicateur System Ready -->
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-blink"></div>
                    <span class="text-gold-400 font-mono text-xs tracking-[0.3em] uppercase">Secure Access</span>
                </div>

                <!-- TITRE PRINCIPAL -->
                <h1 class="text-4xl lg:text-5xl font-serif text-white font-bold leading-tight mb-4">
                    Accédez à la <br>
                    <span class="text-gold-gradient font-sans italic">puissance.</span>
                </h1>

                <!-- SOUS-TITRE -->
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto">
                    Retrouvez votre tableau de bord et pilotez vos actifs avec une précision absolue.
                </p>

                <!-- Stats fictives (Décoration) -->
                <div class="grid grid-cols-3 gap-6 mt-8 border-t border-white/5 pt-6 mx-auto max-w-sm">
                    <div>
                        <div class="text-[10px] text-slate-500 font-mono uppercase tracking-wider mb-1">Uptime</div>
                        <div class="text-white font-bold text-sm">99.99%</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 font-mono uppercase tracking-wider mb-1">Cryptage</div>
                        <div class="text-gold-400 font-bold text-sm">TLS 1.3</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 font-mono uppercase tracking-wider mb-1">Réseau</div>
                        <div class="text-white font-bold text-sm flex items-center justify-center gap-2">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> 5G
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Particules flottantes (Décoration) -->
        <div class="absolute top-10 left-10 text-white/10 text-4xl animate-bounce" style="animation-duration: 3s;"><i
                class="fa-solid fa-cube"></i></div>
        <div class="absolute bottom-10 right-10 text-white/10 text-2xl animate-spin-slow"><i
                class="fa-solid fa-gear"></i></div>

    </div>

    <!-- =============================================
         PARTIE DROITE (FORMULAIRE) - 50%
    ============================================= -->
    <div class="w-full lg:w-1/2 relative bg-dark-900 bg-grid flex items-center justify-center  overflow-y-auto">

        <!-- Lumières d'ambiance -->
        <div class="fixed top-[-5%] right-[-5%] w-80 h-80 bg-gold-600/20 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
            class="fixed bottom-[-5%] left-[45%] w-80 h-80 bg-amber-900/20 rounded-full blur-[100px] pointer-events-none">
        </div>

        <!-- Bouton Retour -->
        <a href="index.php"
            class="absolute top-8 right-8 text-slate-400 hover:text-gold-300 transition flex items-center gap-2 z-50">
            <span class="text-sm font-medium">Accueil</span>
            <i class="fa-solid fa-house"></i>
        </a>

        <!-- CARTE LOGIN -->
        <div class="glass-panel rounded-3xl p-10 w-full max-w-[450px] animate-slide-up relative z-10">

            <!-- HEADER -->
            <div class="text-center mb-6">
                <div
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gold-400/10 border border-gold-400/20 mb-4 text-gold-400">
                    <i class="fa-solid fa-unlock-keyhole text-2xl"></i>
                </div>
                <h2 class="text-3xl text-white font-bold mb-2 tracking-tight">
                    Bon <span class="text-gold-gradient">Retour</span>
                </h2>
                <p class="text-slate-400 text-sm">Identifiez-vous pour accéder à votre compte.</p>
            </div>

            <!-- MESSAGE INFO OTP (Design Alert Gold) -->
            <div
                class="mb-6 p-3 rounded-xl bg-gold-900/20 border border-gold-500/20 flex items-start gap-4 backdrop-blur-sm">
                <div class="mt-1 text-gold-400 animate-blink">
                    <i class="fa-solid fa-shield-cat"></i>
                </div>
                <div>
                    <h3 class="text-gold-300 text-xs font-bold uppercase tracking-wide mb-1">Sécurité Avancée</h3>
                    <p class="text-xs text-gold-300/50 leading-relaxed">
                        Un code <strong>OTP</strong> sera requis par email si nous détectons une nouvelle connexion
                        inhabituelle.
                    </p>
                </div>
            </div>

            <!-- FORMULAIRE -->
            <form action="login.php" method="POST" class="space-y-5">

                <!-- Email -->
                <div class="relative">
                    <input type="email" name="login_email" id="email"
                        class="custom-input peer w-full h-12 px-4 rounded-xl outline-none text-white placeholder-transparent font-medium"
                        placeholder="Email">
                    <label for="email"
                        class="absolute left-4 top-3 text-slate-500 text-sm transition-all pointer-events-none flex items-center gap-2">
                        <i class="fa-regular fa-envelope"></i> Adresse Email
                    </label>
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <input type="password" name="login_password" id="password"
                        class="custom-input peer w-full h-12 px-4 rounded-xl outline-none text-white placeholder-transparent font-medium"
                        placeholder="Password">
                    <label for="password"
                        class="absolute left-4 top-3 text-slate-500 text-sm transition-all pointer-events-none flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> Mot de passe
                    </label>
                    <button type="button" onclick="togglePass()"
                        class="absolute right-4 top-3 text-slate-500 hover:text-gold-300 transition">
                        <i id="eye" class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <!-- Lien Mot de passe oublié -->
                <div class="flex justify-end mt-1">
                    <a href="#"
                        class="text-xs text-slate-400 hover:text-gold-400 transition hover:underline underline-offset-4 decoration-gold-400/50">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Bouton Connexion -->
                <button type="submit" name="login"
                    class="w-full py-4 rounded-xl font-bold text-black bg-gradient-to-r from-gold-300 via-gold-500 to-gold-400 shadow-[0_10px_30px_rgba(234,179,8,0.3)] hover:shadow-[0_15px_40px_rgba(234,179,8,0.5)] transform hover:-translate-y-1 transition duration-300 btn-shine-effect animate-pulse-gold tracking-wide uppercase text-sm relative overflow-hidden">
                    Se connecter
                </button>

            </form>

            <!-- Social Login -->
            <div class="mt-8">
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink-0 mx-4 text-xs text-slate-500 uppercase tracking-widest">Ou connexion
                        rapide</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button
                        class="flex justify-center items-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 hover:border-gold-400/50 hover:text-gold-300 transition text-sm text-slate-300 font-medium group">
                        <i class="fa-brands fa-google group-hover:scale-110 transition"></i> Google
                    </button>
                    <button
                        class="flex justify-center items-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 hover:border-gold-400/50 hover:text-gold-300 transition text-sm text-slate-300 font-medium group">
                        <i class="fa-brands fa-apple group-hover:scale-110 transition"></i> Apple
                    </button>
                </div>
            </div>

            <!-- Footer Link -->
            <p class="text-center mt-6 text-sm text-slate-400">
                Pas encore de compte ? <a href="sign_up.php"
                    class="text-gold-300 font-semibold hover:text-white transition">S'inscrire</a>
            </p>

        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye');
            if (input.type === 'password') {
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