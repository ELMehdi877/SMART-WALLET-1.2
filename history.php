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
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                        'pulse-soft': 'pulseSoft 3s infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: 1 },
                            '50%': { opacity: 0.7 },
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
            overflow-x: hidden;
        }

        /* Glass Panel */
        .glass-panel {
            background: rgba(18, 18, 24, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        /* Transaction Row Hover */
        .tx-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .tx-row:hover {
            background: rgba(255, 255, 255, 0.03);
            transform: scale(1.005);
            border-color: rgba(255,255,255,0.1);
            z-index: 10;
        }

        /* Filter Active State */
        .filter-btn.active {
            background: rgba(250, 204, 21, 0.1);
            border-color: #FACC15;
            color: #FACC15;
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.15);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #050508; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #FACC15; }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans">

    <!-- NAVBAR (Simplifiée pour l'exemple) -->
    <nav class="w-full h-20 border-b border-white/5 bg-panel/80 backdrop-blur-md flex items-center justify-between px-8 fixed top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center text-gold-400 border border-gold-500/30">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="font-tech text-xl font-bold tracking-widest uppercase">Historique</span>
        </div>
        <a href="index.php" class="text-slate-400 hover:text-white transition flex items-center gap-2 text-sm">
            <i class="fa-solid fa-arrow-left"></i> Retour Dashboard
        </a>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-1 pt-28 pb-12 px-4 lg:px-8 max-w-7xl mx-auto w-full">

        <!-- 1. STATS HEADER (Sparklines) -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- INCOME STATS -->
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up">
                <div class="flex justify-between items-start mb-2 relative z-10">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Entrées (Mois)</p>
                        <h2 class="text-3xl font-mono text-emerald-400 font-bold mt-1">+ 24,500.00</h2>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
                <!-- Canvas pour le graphe vert -->
                <canvas id="incomeChart" class="absolute bottom-0 left-0 w-full h-24 opacity-30"></canvas>
            </div>

            <!-- EXPENSE STATS -->
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex justify-between items-start mb-2 relative z-10">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Sorties (Mois)</p>
                        <h2 class="text-3xl font-mono text-rose-400 font-bold mt-1">- 8,240.00</h2>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center text-rose-400">
                        <i class="fa-solid fa-arrow-trend-down"></i>
                    </div>
                </div>
                <!-- Canvas pour le graphe rouge -->
                <canvas id="expenseChart" class="absolute bottom-0 left-0 w-full h-24 opacity-30"></canvas>
            </div>

        </section>

        <!-- 2. CONTROLS & FILTERS -->
        <section class="glass-panel rounded-2xl p-4 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 animate-fade-in-up" style="animation-delay: 0.2s">
            
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <i class="fa-solid fa-search absolute left-4 top-3.5 text-slate-500 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Rechercher une transaction..." class="w-full bg-void border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm text-white outline-none focus:border-gold-400 transition placeholder-slate-600" onkeyup="filterTable()">
            </div>

            <!-- Filters -->
            <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                <button onclick="filterType('all')" class="filter-btn active px-6 py-2.5 rounded-xl border border-white/10 text-sm font-bold text-slate-400 hover:text-white transition whitespace-nowrap" id="btn-all">Tout</button>
                <button onclick="filterType('income')" class="filter-btn px-6 py-2.5 rounded-xl border border-white/10 text-sm font-bold text-slate-400 hover:text-white transition whitespace-nowrap" id="btn-income">Revenus</button>
                <button onclick="filterType('expense')" class="filter-btn px-6 py-2.5 rounded-xl border border-white/10 text-sm font-bold text-slate-400 hover:text-white transition whitespace-nowrap" id="btn-expense">Dépenses</button>
            </div>

            <!-- Export -->
            <button class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-gold-400 hover:bg-gold-400 hover:text-black transition font-bold text-sm flex items-center gap-2">
                <i class="fa-solid fa-download"></i> <span class="hidden lg:inline">Export CSV</span>
            </button>
        </section>

        <!-- 3. TRANSACTION TABLE -->
        <section class="glass-panel rounded-3xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
            
            <!-- Table Header -->
            <div class="grid grid-cols-12 gap-4 p-5 border-b border-white/10 bg-white/5 text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                <div class="col-span-1 text-center">#</div>
                <div class="col-span-4 md:col-span-3">Désignation / Catégorie</div>
                <div class="col-span-3 md:col-span-2 hidden md:block">Date</div>
                <div class="col-span-2 hidden lg:block">Méthode</div>
                <div class="col-span-4 md:col-span-3 lg:col-span-2 text-right">Montant</div>
                <div class="col-span-3 md:col-span-2 text-center">Status</div>
            </div>

            <!-- Liste Dynamique (Container) -->
            <div id="transactionList" class="divide-y divide-white/5">
                <!-- Les lignes seront injectées ici par JS -->
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-white/10 flex justify-center gap-2">
                <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-gold-400 hover:text-black flex items-center justify-center transition text-slate-400"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-8 h-8 rounded-lg bg-gold-400 text-black font-bold flex items-center justify-center">1</button>
                <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-gold-400 hover:text-black flex items-center justify-center transition text-slate-400">2</button>
                <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-gold-400 hover:text-black flex items-center justify-center transition text-slate-400">3</button>
                <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-gold-400 hover:text-black flex items-center justify-center transition text-slate-400"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

        </section>

    </main>

    <!-- JAVASCRIPT (DATA & LOGIC) -->
    <script>
        // --- 1. DONNÉES FICTIVES (MOCK DATA) ---
        const transactions = [
            { id: '#TX-8821', title: 'Carrefour Market', category: 'Nourriture', date: '21 Déc 2025', time: '14:30', method: 'CIH Bank', amount: -450.00, type: 'expense', status: 'Validé' },
            { id: '#TX-8822', title: 'Salaire Mensuel', category: 'Salaire', date: '25 Déc 2025', time: '09:00', method: 'Virement', amount: 15000.00, type: 'income', status: 'Reçu' },
            { id: '#TX-8823', title: 'Station Afriquia', category: 'Transport', date: '26 Déc 2025', time: '18:15', method: 'Espèces', amount: -300.00, type: 'expense', status: 'Validé' },
            { id: '#TX-8824', title: 'Netflix Subscription', category: 'Loisirs', date: '28 Déc 2025', time: '10:00', method: 'BP Card', amount: -65.00, type: 'expense', status: 'Auto' },
            { id: '#TX-8825', title: 'Freelance Mission', category: 'Revenu Extra', date: '29 Déc 2025', time: '16:45', method: 'PayPal', amount: 2400.00, type: 'income', status: 'En attente' },
            { id: '#TX-8826', title: 'Zara Store', category: 'Shopping', date: '30 Déc 2025', time: '12:20', method: 'CIH Bank', amount: -890.00, type: 'expense', status: 'Validé' },
        ];

        // --- 2. RENDER TABLE ---
        const listContainer = document.getElementById('transactionList');

        function renderTransactions(data) {
            listContainer.innerHTML = '';
            
            if(data.length === 0) {
                listContainer.innerHTML = '<div class="p-10 text-center text-slate-500">Aucune transaction trouvée.</div>';
                return;
            }

            data.forEach((tx, index) => {
                const isIncome = tx.type === 'income';
                const amountColor = isIncome ? 'text-emerald-400' : 'text-white';
                const iconBg = isIncome ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-white/5 text-slate-400 border-white/10';
                const iconClass = isIncome ? 'fa-arrow-down' : 'fa-arrow-up rotate-45';
                const sign = isIncome ? '+' : '';
                
                // Status badge logic
                let statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if(tx.status === 'En attente') statusClass = 'text-gold-400 bg-gold-500/10 border-gold-500/20';
                
                const html = `
                <div class="tx-row grid grid-cols-12 gap-4 p-4 items-center group cursor-pointer animate-fade-in-up" style="animation-delay: ${index * 0.05}s">
                    
                    <!-- Icon -->
                    <div class="col-span-1 flex justify-center">
                        <div class="w-8 h-8 rounded-lg border ${iconBg} flex items-center justify-center text-xs">
                            <i class="fa-solid ${iconClass}"></i>
                        </div>
                    </div>

                    <!-- Title & Category -->
                    <div class="col-span-4 md:col-span-3">
                        <div class="font-bold text-white text-sm group-hover:text-gold-400 transition">${tx.title}</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-wider">${tx.category}</div>
                    </div>

                    <!-- Date -->
                    <div class="col-span-3 md:col-span-2 hidden md:block">
                        <div class="text-xs text-slate-300">${tx.date}</div>
                        <div class="text-[10px] text-slate-600">${tx.time}</div>
                    </div>

                    <!-- Method -->
                    <div class="col-span-2 hidden lg:block">
                        <div class="text-xs text-slate-400 flex items-center gap-2">
                            <i class="fa-regular fa-credit-card"></i> ${tx.method}
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="col-span-4 md:col-span-3 lg:col-span-2 text-right">
                        <div class="font-mono font-bold ${amountColor} text-base">${sign}${tx.amount.toFixed(2)} DH</div>
                    </div>

                    <!-- Status -->
                    <div class="col-span-3 md:col-span-2 flex justify-center">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border ${statusClass}">
                            ${tx.status}
                        </span>
                    </div>
                </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', html);
            });
        }

        // Initial Render
        renderTransactions(transactions);

        // --- 3. FILTERS LOGIC ---
        let currentType = 'all';

        function filterType(type) {
            currentType = type;
            // Update buttons
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`btn-${type}`).classList.add('active');
            
            applyFilters();
        }

        function filterTable() {
            applyFilters();
        }

        function applyFilters() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            
            const filtered = transactions.filter(tx => {
                const matchesType = currentType === 'all' || tx.type === currentType;
                const matchesSearch = tx.title.toLowerCase().includes(searchText) || tx.category.toLowerCase().includes(searchText);
                return matchesType && matchesSearch;
            });

            renderTransactions(filtered);
        }

        // --- 4. SPARKLINES CHART (Canvas Animation) ---
        function drawSparkline(canvasId, color, dataPoints) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            
            // Resize canvas resolution
            const dpr = window.devicePixelRatio || 1;
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);

            // Mock Data if not provided
            const data = dataPoints || [10, 25, 20, 40, 30, 50, 45, 60, 55, 80];
            const width = rect.width;
            const height = rect.height;
            const step = width / (data.length - 1);

            // Gradient Fill
            const gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(0, color.replace('1)', '0.5)')); // Opacity top
            gradient.addColorStop(1, 'transparent'); // Opacity bottom

            ctx.beginPath();
            ctx.moveTo(0, height - (data[0] / 100 * height));

            data.forEach((point, i) => {
                const x = i * step;
                const y = height - (point / 100 * height);
                // Bezier curve for smooth lines
                if (i === 0) ctx.moveTo(x, y);
                else {
                    const prevX = (i - 1) * step;
                    const prevY = height - (data[i - 1] / 100 * height);
                    const cp1x = prevX + (x - prevX) / 2;
                    const cp1y = prevY;
                    const cp2x = prevX + (x - prevX) / 2;
                    const cp2y = y;
                    ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, x, y);
                }
            });

            // Stroke
            ctx.strokeStyle = color;
            ctx.lineWidth = 2;
            ctx.stroke();

            // Fill area
            ctx.lineTo(width, height);
            ctx.lineTo(0, height);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();
        }

        // Draw charts on load
        window.addEventListener('load', () => {
            drawSparkline('incomeChart', 'rgba(16, 185, 129, 1)', [20, 30, 25, 50, 40, 70, 60, 90]);
            drawSparkline('expenseChart', 'rgba(251, 113, 133, 1)', [10, 40, 30, 20, 50, 20, 40, 30]);
        });
        
    </script>
</body>
</html>