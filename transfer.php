

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transfert P2P - Smart Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], heading: ['Outfit', 'sans-serif'] },
                    colors: {
                        dark: { 900: '#020617', 800: '#0F172A', card: 'rgba(20, 20, 20, 0.6)' },
                        gold: { 400: '#FACC15', 500: '#EAB308', glow: 'rgba(250, 204, 21, 0.15)' }
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 2s infinite',
                        'slide-up': 'slideUp 0.5s ease-out forwards'
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 5px rgba(250, 204, 21, 0.2)' },
                            '50%': { boxShadow: '0 0 20px rgba(250, 204, 21, 0.6)' },
                        },
                        slideUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #020617; background-image: radial-gradient(circle at 50% 0%, rgba(59, 130, 246, 0.1) 0%, transparent 50%); color: white; }
        .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        /* Card Selection Style */
        .radio-card:checked + div {
            border-color: #FACC15; background: rgba(250, 204, 21, 0.1);
        }
        .radio-card:checked + div .check-icon { opacity: 1; transform: scale(1); }
        
        /* Smooth Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-10 px-4">

    <!-- NAVBAR RETOUR -->
    <nav class="w-full max-w-6xl mb-8 flex justify-between items-center animate-slide-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-paper-plane text-white"></i>
            </div>
            <h1 class="text-2xl font-bold font-heading">Transfert <span class="text-blue-400">Express</span></h1>
        </div>
        <a href="index.php" class="text-slate-400 hover:text-white transition flex items-center gap-2 text-sm font-bold bg-white/5 px-4 py-2 rounded-lg border border-white/5 hover:border-white/20">
            <i class="fa-solid fa-house"></i> Accueil
        </a>
    </nav>

    <!-- NOTIFICATION TOAST -->
    <?php if($message): ?>
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-full flex items-center gap-3 shadow-2xl animate-pulse-glow <?= $msg_type == 'error' ? 'bg-red-500/20 border border-red-500 text-red-400' : 'bg-emerald-500/20 border border-emerald-500 text-emerald-400' ?>">
            <i class="fa-solid <?= $msg_type == 'error' ? 'fa-circle-xmark' : 'fa-circle-check' ?>"></i>
            <span class="font-bold text-sm"><?= $message ?></span>
        </div>
    <?php endif; ?>

    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- GAUCHE : PARAMÈTRES RÉCEPTION (4 cols) -->
        <div class="lg:col-span-4 space-y-6 animate-slide-up" style="animation-delay: 0.1s;">
            
            <!-- Carte Principale Config -->
            <div class="glass-panel rounded-3xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 blur-[50px]"></div>
                
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-blue-400"></i> Réception
                </h2>
                <p class="text-xs text-slate-400 mb-6">Choisissez la carte qui recevra les fonds envoyés par vos amis.</p>

                <form action="" method="POST" class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                    <?php foreach($my_cards as $card): ?>
                    <label class="cursor-pointer block relative group">
                        <input type="radio" name="primary_card_id" value="<?= $card['id'] ?>" class="peer sr-only radio-card" <?= $card['is_primary'] ? 'checked' : '' ?> onchange="this.form.submit()">
                        <input type="hidden" name="set_primary" value="1">
                        
                        <div class="p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition flex items-center justify-between group-hover:border-white/20">
                            <div class="flex items-center gap-3">
                                <!-- Icone Banque -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold 
                                    <?= strpos(strtolower($card['bank_name']), 'cih') !== false ? 'bg-orange-500/20 text-orange-500' : 'bg-yellow-500/20 text-yellow-500' ?>">
                                    <?= substr($card['bank_name'], 0, 2) ?>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-white"><?= htmlspecialchars($card['card_name']) ?></div>
                                    <div class="text-[10px] text-slate-400 font-mono">**** <?= htmlspecialchars($card['last_4']) ?></div>
                                </div>
                            </div>
                            <!-- Check Icon -->
                            <div class="check-icon w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-black text-xs opacity-0 transition transform scale-50">
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </form>
            </div>

            <!-- Mon ID (Pour recevoir) -->
            <div class="glass-panel rounded-2xl p-6 text-center">
                <p class="text-xs text-slate-400 uppercase tracking-widest mb-2">Mon ID Unique</p>
                <div class="bg-black/30 border border-white/10 rounded-lg p-3 flex justify-between items-center">
                    <span class="font-mono text-xl text-gold-400 tracking-wider">#USER-<?= $user_id ?></span>
                    <button class="text-slate-500 hover:text-white" onclick="navigator.clipboard.writeText('#USER-<?= $user_id ?>')"><i class="fa-regular fa-copy"></i></button>
                </div>
            </div>

        </div>

        <!-- CENTRE : ENVOYER (8 cols) -->
        <div class="lg:col-span-8 space-y-8 animate-slide-up" style="animation-delay: 0.2s;">
            
            <!-- FORMULAIRE ENVOI -->
            <div class="glass-panel rounded-3xl p-8 border-t-4 border-t-gold-400 relative">
                <h2 class="text-2xl font-bold font-heading mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-gold-500/20 flex items-center justify-center text-gold-400"><i class="fa-solid fa-paper-plane"></i></span>
                    Envoyer de l'argent
                </h2>

                <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Destinataire -->
                    <div class="md:col-span-2">
                        <label class="text-xs text-slate-400 uppercase font-bold ml-1 mb-1 block">Destinataire</label>
                        <div class="relative group">
                            <i class="fa-solid fa-user absolute left-4 top-4 text-slate-500 group-focus-within:text-gold-400 transition"></i>
                            <input type="text" name="recipient" required placeholder="Email ou ID (ex: 45)" class="w-full bg-dark-900 border border-white/10 rounded-xl p-3.5 pl-12 text-white outline-none focus:border-gold-400 transition placeholder-slate-600">
                        </div>
                    </div>

                    <!-- Montant -->
                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold ml-1 mb-1 block">Montant à envoyer</label>
                        <div class="relative group">
                            <input type="number" name="amount" step="0.01" required placeholder="0.00" class="w-full bg-dark-900 border border-white/10 rounded-xl p-3.5 pl-4 text-white font-mono text-lg outline-none focus:border-gold-400 transition">
                            <span class="absolute right-4 top-4 text-slate-500 font-bold">DH</span>
                        </div>
                    </div>

                    <!-- Source (Ma Carte) -->
                    <div>
                        <label class="text-xs text-slate-400 uppercase font-bold ml-1 mb-1 block">Débiter depuis</label>
                        <select name="source_card" class="w-full bg-dark-900 border border-white/10 rounded-xl p-3.5 text-slate-300 outline-none focus:border-gold-400 transition cursor-pointer">
                            <?php foreach($my_cards as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['bank_name'] ?> (<?= number_format($c['balance']) ?> DH)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="md:col-span-2 pt-4">
                        <button type="submit" name="send_money" class="w-full py-4 bg-gradient-to-r from-gold-400 to-amber-500 text-black font-bold text-sm uppercase tracking-widest rounded-xl hover:shadow-[0_0_25px_rgba(250,204,21,0.4)] transition transform hover:-translate-y-1">
                            Confirmer le transfert
                        </button>
                    </div>

                </form>
            </div>

            <!-- HISTORIQUE TIMELINE -->
            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-lg font-bold text-white mb-6">Flux Récents</h3>
                
                <div class="relative pl-4 border-l border-white/10 space-y-8">
                    <?php if(empty($history)) echo "<p class='text-slate-500 text-sm'>Aucun transfert pour le moment.</p>"; ?>

                    <?php foreach($history as $tx): 
                        $is_sender = ($tx['sender_id'] == $user_id);
                        $color = $is_sender ? 'red' : 'emerald';
                        $sign = $is_sender ? '-' : '+';
                        $icon = $is_sender ? 'fa-arrow-up-right-from-square' : 'fa-arrow-down-left-to-square';
                        $name = $is_sender ? "À : " . $tx['receiver_prenom'] . " " . $tx['receiver_name'] : "De : " . $tx['sender_prenom'] . " " . $tx['sender_name'];
                    ?>
                    <div class="relative group">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-<?= $color ?>-500 border-2 border-dark-900 shadow-[0_0_10px_rgba(var(--color-<?= $color ?>-500))]"></div>
                        
                        <div class="flex justify-between items-start p-4 rounded-xl bg-white/5 hover:bg-white/10 transition border border-transparent hover:border-white/10 cursor-default">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-<?= $color ?>-500/10 flex items-center justify-center text-<?= $color ?>-400 border border-<?= $color ?>-500/20">
                                    <i class="fa-solid <?= $icon ?>"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-white text-sm"><?= htmlspecialchars($name) ?></div>
                                    <div class="text-[10px] text-slate-500"><?= date('d M Y à H:i', strtotime($tx['created_at'])) ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold text-lg text-<?= $color ?>-400"><?= $sign ?><?= number_format($tx['amount'], 2) ?> DH</div>
                                <span class="text-[10px] uppercase tracking-wider text-slate-500 border border-slate-700 px-2 py-0.5 rounded"><?= $tx['status'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</body>
</html>