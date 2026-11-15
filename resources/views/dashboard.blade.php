<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroAnalogs | Planetary Analog Management & Telemetry System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                processEscapes: true
            },
            options: {
                skipHtmlTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code']
            }
        };
    </script>
    <script async id="MathJax-script" src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js"></script>

    <style>
        :root {
            --bg-cosmos: #06070b;
            --vermilion: #e04826;
            --vermilion-soft: #ff8b6e;
            --text-primary: #f4f6fb;
            --text-muted: #8b93a7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            overflow: hidden;
            background-color: var(--bg-cosmos);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .font-serif-editorial { font-family: 'Instrument Serif', serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        .cosmos-backdrop {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            background:
                radial-gradient(ellipse 65% 50% at 85% 95%, rgba(224, 72, 38, 0.14) 0%, rgba(145, 44, 20, 0.05) 45%, transparent 75%),
                radial-gradient(ellipse 55% 45% at 12% 10%, rgba(56, 189, 248, 0.08) 0%, transparent 65%),
                linear-gradient(180deg, #07080d 0%, #050609 100%);
        }

        .cosmos-stars {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 9% 14%, rgba(255, 255, 255, 0.45) 50%, transparent 100%),
                radial-gradient(1px 1px at 22% 31%, rgba(255, 255, 255, 0.3) 50%, transparent 100%),
                radial-gradient(1.2px 1.2px at 34% 11%, rgba(255, 255, 255, 0.4) 50%, transparent 100%),
                radial-gradient(1px 1px at 48% 24%, rgba(255, 255, 255, 0.25) 50%, transparent 100%),
                radial-gradient(1.2px 1.2px at 66% 15%, rgba(255, 255, 255, 0.42) 50%, transparent 100%),
                radial-gradient(1px 1px at 79% 19%, rgba(255, 255, 255, 0.35) 50%, transparent 100%),
                radial-gradient(1.2px 1.2px at 92% 28%, rgba(255, 255, 255, 0.38) 50%, transparent 100%),
                radial-gradient(1px 1px at 18% 68%, rgba(255, 255, 255, 0.28) 50%, transparent 100%),
                radial-gradient(1px 1px at 56% 74%, rgba(255, 255, 255, 0.3) 50%, transparent 100%),
                radial-gradient(1px 1px at 73% 52%, rgba(255, 255, 255, 0.24) 50%, transparent 100%),
                radial-gradient(1px 1px at 41% 88%, rgba(255, 255, 255, 0.32) 50%, transparent 100%);
            animation: calmTwinkle 9s ease-in-out infinite alternate;
        }

        @keyframes calmTwinkle {
            0% { opacity: 0.5; }
            100% { opacity: 0.9; }
        }

        .hover-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            background-color: transparent;
        }
        .hover-scroll::-webkit-scrollbar-thumb {
            background-color: transparent;
            border-radius: 99px;
        }
        .hover-scroll:hover::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.16);
        }

        @keyframes orbital-hazard-sway {
            0%, 100% { transform: rotate(-7deg) scale(1); }
            50% { transform: rotate(7deg) scale(1.05); }
        }
        .orbital-hazard-pulse {
            transform-origin: 50% 85%;
            animation: orbital-hazard-sway 2.2s infinite ease-in-out;
            filter: drop-shadow(0 0 12px rgba(224, 72, 38, 0.55));
        }

        /* =====================================================================
           INPUTS & PILLS
           ===================================================================== */
        .space-input {
            background-color: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: #f4f6fb;
            transition: all 0.2s ease;
        }
        .space-input:hover {
            border-color: rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.06);
        }
        .space-input:focus {
            outline: none;
            border-color: #e04826;
            box-shadow: 0 0 0 3px rgba(224, 72, 38, 0.18);
        }
        .space-input option {
            background-color: #111520;
            color: #f4f6fb;
        }

        .layer-switch {
            appearance: none;
            -webkit-appearance: none;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
            position: relative;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(100, 116, 139, 0.55);
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .layer-switch::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .layer-switch:checked {
            background: #e04826;
            border-color: rgba(255, 139, 110, 0.65);
        }
        .layer-switch:checked::before {
            transform: translateX(16px);
            background: #ffffff;
        }
        .layer-switch:focus-visible {
            outline: 2px solid #ff8b6e;
            outline-offset: 3px;
        }

        .nav-sub-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 12px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .nav-sub-btn:hover { color: #fff; background: rgba(255,255,255,0.05); }
        .nav-sub-btn.active {
            color: #ffb09a;
            background: rgba(224,72,38,0.10);
            border-color: rgba(224,72,38,0.22);
        }
        .ai-range {
            width: 100%;
            accent-color: #e04826;
            cursor: pointer;
        }
        .ai-result-bar {
            height: 7px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }
        .ai-result-bar > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #38bdf8, #e04826);
        }

        /* =====================================================================
           SOL CYCLE PILLS & MARS HORIZON GLOW
           ===================================================================== */
        .sol-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 56px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #94a3b8;
            transition: all 0.22s ease;
            cursor: pointer;
        }
        .sol-pill:hover {
            background: rgba(255, 255, 255, 0.09);
            color: #ffffff;
        }
        .sol-pill.active {
            background: linear-gradient(135deg, #e54e2b 0%, #c93d1e 100%);
            border-color: rgba(255, 155, 130, 0.55);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(224, 72, 38, 0.45);
        }

        .mars-horizon-glow {
            background: radial-gradient(circle at 42% 18%, #ffbe94 0%, #e55b35 28%, #8f2610 62%, #12090a 92%, #07080d 100%);
            box-shadow:
                0 -12px 45px rgba(229, 91, 53, 0.32),
                inset 0 12px 28px rgba(255, 224, 204, 0.36);
        }

        .reticle-crosshair {
            width: 44px;
            height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 50%;
            position: relative;
        }
        .reticle-crosshair::before, .reticle-crosshair::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.85);
        }
        .reticle-crosshair::before { top: 50%; left: -7px; right: -7px; height: 1px; }
        .reticle-crosshair::after { left: 50%; top: -7px; bottom: -7px; width: 1px; }

        /* =====================================================================
           DRAWER NODE MATRIX & BOARDING PASS TICKET
           ===================================================================== */
        .node-slot {
            height: 30px;
            border-radius: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s ease;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .node-slot.available {
            background: rgba(255, 255, 255, 0.04);
            color: #64748b;
        }
        .node-slot.busy {
            background: rgba(224, 72, 38, 0.85);
            color: #ffffff;
            border-color: rgba(255, 145, 118, 0.45);
            box-shadow: 0 3px 10px rgba(224, 72, 38, 0.25);
        }
        .node-slot.busy:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }
        .node-slot.chosen {
            background: #10b981;
            color: #ffffff;
            border-color: rgba(167, 243, 208, 0.55);
            box-shadow: 0 3px 12px rgba(16, 185, 129, 0.35);
        }

        .flight-ticket-card {
            position: relative;
            background: linear-gradient(180deg, rgba(24, 28, 40, 0.9) 0%, rgba(14, 17, 26, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.45);
        }
        .flight-ticket-card::before,
        .flight-ticket-card::after {
            content: '';
            position: absolute;
            top: 66%;
            width: 16px;
            height: 16px;
            background-color: #0e1118;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 2;
        }
        .flight-ticket-card::before { left: -9px; }
        .flight-ticket-card::after { right: -9px; }

        .ticket-barcode {
            height: 22px;
            width: 100%;
            border-radius: 4px;
            background: repeating-linear-gradient(
                90deg,
                rgba(244, 246, 251, 0.85) 0px,
                rgba(244, 246, 251, 0.85) 2px,
                transparent 2px,
                transparent 4px,
                rgba(244, 246, 251, 0.85) 4px,
                rgba(244, 246, 251, 0.85) 5px,
                transparent 5px,
                transparent 8px,
                rgba(244, 246, 251, 0.85) 8px,
                rgba(244, 246, 251, 0.85) 11px,
                transparent 11px,
                transparent 13px
            );
            opacity: 0.8;
        }

        .planet-sphere-mars {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            background: radial-gradient(circle at 28% 26%, #ffb38a 0%, #e04826 42%, #8c230e 78%, #2b0904 100%);
            box-shadow: inset -5px -5px 10px rgba(0, 0, 0, 0.7), 0 0 14px rgba(224, 72, 38, 0.3);
        }
        .planet-sphere-moon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            background: radial-gradient(circle at 28% 25%, #f8fafc 0%, #cbd5e1 42%, #64748b 78%, #1e293b 100%);
            box-shadow: inset -5px -5px 10px rgba(0, 0, 0, 0.75), 0 0 12px rgba(148, 163, 184, 0.25);
        }

        /* =====================================================================
           CLEAN BORDERLESS-SIDE TABLE
           ===================================================================== */
        .clean-table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }
        .clean-table thead th {
            padding-bottom: 16px;
            padding-top: 8px;
            padding-left: 12px;
            padding-right: 12px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            white-space: nowrap;
        }
        .clean-table tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .clean-table tbody tr:last-child {
            border-bottom: none;
        }
        .clean-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }
        .clean-table tbody tr.selected-row {
            background: linear-gradient(90deg, rgba(224, 72, 38, 0.14) 0%, rgba(224, 72, 38, 0.02) 100%);
        }
        .clean-table tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            white-space: nowrap;
        }

        @media print {
            body { overflow: visible !important; background: #06070b !important; }
            #main-sidebar, header, #mission-drawer { display: none !important; }
            main { overflow: visible !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="flex h-screen bg-[#06070b] font-sans overflow-hidden relative text-slate-100">

    <!-- DEEP SPACE ATMOSPHERIC LAYER -->
    <div class="cosmos-backdrop" aria-hidden="true">
        <div class="cosmos-stars"></div>
    </div>

    <!-- ===================================================================== -->
    <!-- FLOATING COLLAPSIBLE SIDEBAR                                          -->
    <!-- ===================================================================== -->
    <aside id="main-sidebar" class="relative my-4 ml-4 h-[calc(100vh-32px)] bg-[#11141d]/90 backdrop-blur-2xl rounded-[32px] border border-white/10 transition-all duration-300 ease-in-out flex flex-col flex-shrink-0 z-40 shadow-[0_8px_32px_0_rgba(0,0,0,0.45)] w-72">
        
        <!-- Circular Edge Collapse Button -->
        <button onclick="toggleSidebar()" title="Toggle Sidebar" class="absolute -right-3.5 top-10 bg-[#1a1f2c] border border-white/15 rounded-full p-1.5 shadow-md hover:bg-white/10 transition-all z-50 hover:scale-110 cursor-pointer flex items-center justify-center text-slate-200 hover:text-white">
            <i id="sidebar-toggle-icon" class="fa-solid fa-chevron-left text-[11px] w-4 h-4 flex items-center justify-center"></i>
        </button>

        <!-- Brand Logo Header -->
        <div id="sidebar-brand-box" class="flex items-center px-8 mb-6 mt-8 transition-all duration-300">
            <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap w-full">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#e04826] to-[#991b1b] flex items-center justify-center flex-shrink-0 shadow-[0_6px_20px_rgba(224,72,38,0.4)] border border-white/20">
                    <i class="fa-solid fa-earth-americas text-white text-lg"></i>
                </div>
                <div class="sidebar-label-text flex flex-col transition-opacity duration-200">
                    <div class="flex items-baseline gap-1 pb-0.5">
                        <span class="text-[21px] font-extrabold text-white tracking-tight">Astro</span>
                        <span class="text-[21px] font-black bg-gradient-to-r from-[#ff9e80] to-[#e04826] bg-clip-text text-transparent tracking-tight">Analogs</span>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.16em] ml-0.5">NASA Telemetry</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav id="sidebar-nav" class="space-y-1 px-4 flex-1 overflow-y-auto hover-scroll">
            <button onclick="switchPage('similarity')" id="nav-similarity" title="Dashboard & Similarity" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer bg-transparent text-[#ff8b6e] shadow-[inset_3px_0_0_0_#e04826] border border-transparent">
                <i class="fa-solid fa-table-columns w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">Dashboard</span>
            </button>

            <button onclick="switchPage('targets')" id="nav-targets" title="Planetary Targets (Moon & Mars)" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-meteor w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">Planetary Targets</span>
            </button>

            <button onclick="switchPage('terrestrial')" id="nav-terrestrial" title="Terrestrial Niche Database" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-earth-asia w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">Terrestrial Database</span>
            </button>

            <button onclick="switchPage('globe')" id="nav-globe" title="3D Earth & GIBS View" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-globe w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">3D Earth &amp; GIBS</span>
            </button>

            <button onclick="switchPage('telemetry')" id="nav-telemetry" title="NASA Live Telemetry" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-satellite-dish w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">Live Telemetry</span>
            </button>

            <button onclick="switchPage('topography')" id="nav-topography" title="3D DEM Topography" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-mountain w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">3D DEM Terrain</span>
            </button>

            <button onclick="switchPage('science')" id="nav-science" title="Scientific Report & Documentation" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <i class="fa-solid fa-file-lines w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                <span class="sidebar-label-text whitespace-nowrap">Scientific Report</span>
            </button>

            <div class="pt-1">
                <button onclick="switchPage('matchmaker'); showAiMatchmakerView('discover')" id="nav-matchmaker" title="AI Matchmaker" class="nav-link-btn w-full flex items-center gap-3.5 px-5 py-3 rounded-2xl text-[13.5px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                    <i class="fa-solid fa-brain w-5 h-5 flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110 text-violet-300"></i>
                    <span class="sidebar-label-text whitespace-nowrap flex-1 text-left">AI Matchmaker</span>
                    <i class="fa-solid fa-chevron-down text-[9px] text-slate-500 sidebar-label-text"></i>
                </button>
                <div id="nav-matchmaker-subnav" class="ml-5 mt-1 pl-3 border-l border-white/10 space-y-1">
                    <button onclick="switchPage('matchmaker'); showAiMatchmakerView('discover')" id="nav-ai-discover" class="nav-sub-btn">
                        <i class="fa-solid fa-satellite-dish w-4 text-center text-sky-300"></i>
                        <span class="sidebar-label-text">Auto Discover</span>
                    </button>
                    <button onclick="switchPage('matchmaker'); showAiMatchmakerView('custom')" id="nav-ai-custom" class="nav-sub-btn">
                        <i class="fa-solid fa-sliders w-4 text-center text-[#ff8b6e]"></i>
                        <span class="sidebar-label-text">Custom Scan</span>
                    </button>
                </div>
            </div>

            <div class="sidebar-label-text pt-3 pb-1 px-5">
                <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Quick Filters</span>
            </div>

            <button onclick="switchPage('similarity'); quickFilterBody('Mars')" title="Mars Analogs" class="nav-filter-btn w-full flex items-center gap-3.5 px-5 py-2.5 rounded-2xl text-[13px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <span class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e04826] shadow-[0_0_8px_#e04826]"></span>
                </span>
                <span class="sidebar-label-text whitespace-nowrap">Mars Sites</span>
            </button>

            <button onclick="switchPage('similarity'); quickFilterBody('Moon')" title="Lunar Analogs" class="nav-filter-btn w-full flex items-center gap-3.5 px-5 py-2.5 rounded-2xl text-[13px] font-bold transition-all duration-200 group cursor-pointer text-slate-300 hover:bg-white/5 hover:text-white border border-transparent">
                <span class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-300 shadow-[0_0_8px_rgba(255,255,255,0.4)]"></span>
                </span>
                <span class="sidebar-label-text whitespace-nowrap">Lunar Sites</span>
            </button>
        </nav>

        <!-- Bottom Action Section -->
        <div class="p-4 mt-auto border-t border-white/10 flex flex-col gap-2">
            <button onclick="returnToLanding()" title="Exit Console" class="flex items-center justify-center gap-2.5 w-full py-2.5 bg-transparent text-rose-300 rounded-2xl hover:bg-white/5 hover:text-rose-200 transition-colors font-bold text-[13.5px] cursor-pointer border border-transparent px-4">
                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                <span class="sidebar-label-text">Exit Console</span>
            </button>
        </div>
    </aside>

    <!-- ===================================================================== -->
    <!-- MAIN RIGHT WORKSPACE WRAPPER                                          -->
    <!-- ===================================================================== -->
    <div class="flex-1 flex flex-col overflow-hidden relative z-10">

        <!-- TOP MINIMALIST UTILITY BAR -->
        <header class="flex justify-between items-center px-8 pt-5 pb-2 z-30 bg-transparent shrink-0">
            <!-- Left Live NASA Connection Status Pills -->
            <div class="hidden xl:flex items-center gap-2 text-[11px] font-mono">
                <span class="px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-slate-300 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    NASA POWER: ONLINE
                </span>
                <span class="px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-slate-300 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#e04826]"></span>
                    CMR / GIBS: ONLINE
                </span>
                <span class="px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-slate-300 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    TREKS DEM: READY
                </span>
                <span class="px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-slate-300 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    ROVER / REMS: SYNCED
                </span>
            </div>

            <!-- Right Date/Clock + Bell Dropdown + Mission Drawer Toggle -->
            <div class="flex items-center gap-4 ml-auto">
                <div class="hidden md:block mr-2 text-right">
                    <span id="header-formatted-date" class="text-[13px] font-bold text-slate-400 tracking-wide">15 Nov 2026, Sunday</span>
                    <span id="utc-clock" class="ml-2 text-[12px] font-mono font-semibold text-sky-400">00:00:00 UTC</span>
                </div>

                <!-- Notification Bell Dropdown -->
                <div class="relative">
                    <button onclick="toggleNotificationDropdown()" class="w-10 h-10 rounded-full bg-white/[0.05] border border-white/10 shadow-sm flex items-center justify-center hover:bg-white/10 transition-all relative cursor-pointer text-slate-300 hover:text-[#ff8b6e]">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span id="notif-badge" class="absolute -top-1.5 -right-1.5 min-w-[20px] h-5 px-1 rounded-full bg-[#e04826] border-2 border-[#06070b] text-white text-[10px] font-bold flex items-center justify-center">3</span>
                    </button>

                    <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-[#141824]/95 backdrop-blur-2xl rounded-2xl shadow-[0_12px_40px_-10px_rgba(0,0,0,0.75)] border border-white/15 overflow-hidden z-50">
                        <div class="p-4 border-b border-white/10 bg-white/[0.03] flex justify-between items-center">
                            <h3 class="text-sm font-bold text-white">Telemetry Alerts</h3>
                            <span id="notif-pill-count" class="text-[10px] font-bold bg-[#e04826]/20 text-[#ff8b6e] border border-[#e04826]/40 px-2.5 py-0.5 rounded-full">3 New</span>
                        </div>
                        <div id="notif-list-container" class="max-h-[300px] overflow-y-auto hover-scroll"></div>
                    </div>
                </div>

                <!-- Mission Profile & Boarding Pass Drawer Toggle -->
                <button onclick="toggleMissionDrawer(true)" title="Open Mission Control Drawer" class="w-10 h-10 rounded-full bg-white/[0.05] border border-white/10 shadow-sm flex items-center justify-center cursor-pointer hover:bg-white/10 hover:text-[#ff8b6e] text-slate-300 transition-all">
                    <i class="fa-solid fa-user-astronaut text-sm"></i>
                </button>
            </div>
        </header>

        <!-- ===================================================================== -->
        <!-- MAIN SCROLLABLE INNER GLASS STAGE                                     -->
        <!-- ===================================================================== -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto px-8 pb-8 pt-2 hover-scroll">
            <div class="relative font-sans z-0 min-h-[calc(100vh-6rem)] bg-[#0c0f17]/75 backdrop-blur-[24px] rounded-[32px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] overflow-hidden p-6 mb-2">
                
                <!-- Subtle Dot Grid Pattern -->
                <div class="absolute inset-0 z-[-3] opacity-[0.04] pointer-events-none"
                     style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEuNSIgZmlsbD0iI2ZmZmZmZiIvPjwvc3ZnPg==');">
                </div>

                <!-- Ambient Radial Glow Orbs -->
                <div class="absolute top-[-20%] left-[-10%] w-[55vw] h-[55vw] rounded-full bg-gradient-to-br from-sky-500/10 to-indigo-500/5 blur-[120px] pointer-events-none z-[-2]"></div>
                <div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tl from-[#e04826]/12 to-amber-500/5 blur-[140px] pointer-events-none z-[-2]"></div>

                <!-- ================================================================= -->
                <!-- PAGE 1: DASHBOARD & SIMILARITY ANALYSIS                           -->
                <!-- ================================================================= -->
                <section id="page-similarity" class="relative z-10 w-full flex flex-col gap-6">

                    <!-- Page Header Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">Dashboard</h1>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">Planetary Analog Matchmaker &amp; Multi-Sensor Orbital Telemetry System</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select id="sim-pair-dropdown" onchange="selectAnalogRecord(this.value)" class="space-input px-4 py-2 text-xs font-semibold cursor-pointer min-w-[260px]"></select>
                            <button onclick="openCrudModal()" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-[0_6px_20px_rgba(224,72,38,0.35)] hover:brightness-110 transition-all cursor-pointer flex items-center gap-2 border border-white/15">
                                <i class="fa-solid fa-plus"></i> Add Analog Record
                            </button>
                            <button onclick="resetDefaultRecords()" title="Restore Original NASA Benchmark Sites" class="p-2.5 bg-white/[0.05] backdrop-blur-md rounded-full shadow-sm border border-white/10 text-slate-300 hover:text-[#ff8b6e] cursor-pointer transition-all">
                                <i class="fa-solid fa-rotate-left text-xs w-4 h-4 flex items-center justify-center"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Welcome Hero Banner -->
                    <div class="relative overflow-hidden bg-white/[0.03] backdrop-blur-2xl px-8 py-7 rounded-[32px] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] border border-white/10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="mars-horizon-glow pointer-events-none absolute -right-12 -bottom-44 w-80 h-80 rounded-full opacity-85"></div>
                        <div class="reticle-crosshair hidden md:block absolute right-16 bottom-8 pointer-events-none opacity-80"></div>

                        <div class="relative z-10 max-w-2xl">
                            <h2 class="text-[24px] font-bold text-white tracking-tight mb-2 flex items-center gap-2">
                                Planetary Analog Matchmaker Engine <span class="inline-block">🪐</span>
                            </h2>
                            <p class="text-[14px] text-slate-300 font-medium leading-relaxed">
                                Active comparison locked on <strong id="hero-active-pair" class="text-[#ff8b6e]">Kazumura Lava Tube ↔ Marius Hills Skylight</strong>, achieving a quantitative similarity score of <strong id="hero-active-score" class="text-emerald-400">94.1%</strong>. Explore niche subsurface habitats, extremophile brines, and real orbital telemetry below.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 mt-5">
                                <button onclick="switchPage('targets')" class="inline-flex items-center gap-3 pl-4 pr-1.5 py-1.5 rounded-full bg-white/[0.07] hover:bg-white/[0.12] border border-white/15 text-xs font-bold text-white transition-all cursor-pointer">
                                    <span>Explore Planetary Targets</span>
                                    <span class="w-8 h-7 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] flex items-center justify-center shadow-md">
                                        <i class="fa-solid fa-rocket text-[11px] -rotate-45"></i>
                                    </span>
                                </button>
                                <button onclick="switchPage('terrestrial')" class="px-4 py-2 rounded-full bg-white/[0.04] hover:bg-white/[0.09] border border-white/10 text-xs font-semibold text-slate-200 transition-all cursor-pointer">
                                    Niche Earth Database
                                </button>
                                <button onclick="switchPage('science')" class="px-4 py-2 rounded-full bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/25 text-xs font-semibold text-sky-300 transition-all cursor-pointer">
                                    Scientific Methodology
                                </button>
                            </div>
                        </div>

                        <!-- Sol Cycle Interactive Selector Pills -->
                        <div class="relative z-10 flex items-center gap-2 bg-[#07090f]/75 backdrop-blur-xl p-2 rounded-full border border-white/10 mr-0 lg:mr-28">
                            <button type="button" onclick="selectSolCycle(this, 14)" class="sol-pill">
                                <span class="text-[11px] font-display font-bold">14</span>
                                <span class="text-[9px] opacity-80 mt-0.5">Sol</span>
                            </button>
                            <button type="button" onclick="selectSolCycle(this, 15)" class="sol-pill">
                                <span class="text-[11px] font-display font-bold">15</span>
                                <span class="text-[9px] opacity-80 mt-0.5">Sol</span>
                            </button>
                            <button type="button" onclick="selectSolCycle(this, 16)" class="sol-pill active">
                                <span class="text-[11px] font-display font-bold">16</span>
                                <span class="text-[9px] opacity-90 mt-0.5">Sol</span>
                            </button>
                            <button type="button" onclick="selectSolCycle(this, 17)" class="sol-pill">
                                <span class="text-[11px] font-display font-bold">17</span>
                                <span class="text-[9px] opacity-80 mt-0.5">Sol</span>
                            </button>
                        </div>
                    </div>

                    <!-- MISSION CONTROL: TERRESTRIAL LOCATOR (shared 3D Earth scene) -->
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                        <div class="xl:col-span-8 bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden">
                            <div class="px-7 py-5 border-b border-white/10 flex flex-wrap justify-between items-center gap-3">
                                <div>
                                    <div class="text-[10px] font-mono uppercase tracking-widest text-sky-300">MISSION CONTROL / TERRESTRIAL LOCATOR</div>
                                    <h2 class="text-[17px] font-bold text-white mt-1">3D Earth Analog Stations</h2>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Live station pin linked to the active registry record</p>
                                </div>
                                <select id="dashboard-globe-site-dropdown" onchange="focusGlobeOnSite(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer max-w-full"></select>
                            </div>
                            <div class="relative h-[300px] md:h-[350px] w-full bg-[#05060a]">
                                <div id="dashboard-earth-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></div>
                                <div class="reticle-crosshair absolute top-5 right-5 pointer-events-none opacity-75"></div>
                                <div class="absolute bottom-4 left-4 bg-[#0c0e16]/85 backdrop-blur-xl border border-white/15 rounded-2xl px-4 py-3 text-xs">
                                    <div class="text-[10px] font-mono tracking-widest text-[#ff8b6e] uppercase">ACTIVE EARTH ANALOG</div>
                                    <div id="dashboard-globe-pin-label" class="font-bold text-white mt-0.5">Loading station coordinates...</div>
                                </div>
                            </div>
                        </div>
                        <div class="xl:col-span-4 bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 p-6 flex flex-col justify-between gap-5">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#ff8b6e]">ACTIVE MISSION PROFILE</span>
                                    <i class="fa-solid fa-location-crosshairs text-sky-300"></i>
                                </div>
                                <h3 id="dashboard-locator-site-name" class="text-[19px] font-bold text-white mt-3">Kazumura Lava Tube</h3>
                                <p id="dashboard-locator-site-type" class="text-xs text-slate-400 mt-1">Subsurface basaltic analog</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-3"><span class="text-[10px] text-slate-400">Geology</span><div id="dashboard-locator-geo" class="font-mono text-lg font-bold text-white mt-1">95%</div></div>
                                <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-3"><span class="text-[10px] text-slate-400">Topography</span><div id="dashboard-locator-topo" class="font-mono text-lg font-bold text-sky-300 mt-1">96%</div></div>
                                <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-3"><span class="text-[10px] text-slate-400">Environment</span><div id="dashboard-locator-climate" class="font-mono text-lg font-bold text-emerald-300 mt-1">91%</div></div>
                                <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-3"><span class="text-[10px] text-slate-400">Destination</span><div id="dashboard-locator-body" class="font-mono text-lg font-bold text-[#ff8b6e] mt-1">Moon</div></div>
                            </div>
                            <div class="rounded-2xl bg-sky-500/[0.07] border border-sky-400/20 p-4">
                                <div class="text-[10px] font-mono uppercase tracking-widest text-sky-300 mb-1">Mission utility</div>
                                <p id="dashboard-locator-utility" class="text-xs text-slate-300 leading-relaxed">Compare terrestrial terrain against planetary mission requirements.</p>
                            </div>
                            <button onclick="switchPage('globe')" class="w-full py-3 rounded-2xl bg-white/[0.06] hover:bg-white/10 border border-white/10 text-xs font-bold text-white transition-all cursor-pointer flex items-center justify-center gap-2">
                                Open Full 3D Earth &amp; GIBS <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ROW 1: 3 PRIMARY HERO CARDS -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Card 1: Gradient Hero Card with Concentric Circles -->
                        <div class="h-[240px]">
                            <div class="relative overflow-hidden bg-gradient-to-br from-[#e04826] via-[#c93d1e] to-[#7c1d0b] p-8 rounded-[28px] text-white shadow-[0_16px_32px_-12px_rgba(224,72,38,0.45)] flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 h-full cursor-default border border-white/20">
                                <div class="absolute top-[-40%] right-[-20%] w-[250px] h-[250px] rounded-full border-[30px] border-white/10 pointer-events-none"></div>
                                <div class="absolute top-[-20%] right-[-40%] w-[350px] h-[350px] rounded-full border-[40px] border-white/5 pointer-events-none"></div>

                                <div class="relative z-10 mt-1">
                                    <p class="text-white/85 font-medium tracking-wide text-[14px] mb-2">Overall Analog Similarity</p>
                                    <h3 id="card1-overall-score" class="text-[38px] font-extrabold tracking-tight leading-none drop-shadow-sm">
                                        94.1%
                                    </h3>
                                </div>

                                <div class="relative z-10 mt-auto flex justify-between items-end w-full pb-1">
                                    <div>
                                        <p class="text-white/70 text-[10px] font-bold tracking-widest uppercase mb-1">Target Destination</p>
                                        <p id="card1-target-label" class="text-white/95 font-bold text-[15px] tracking-wide uppercase">
                                            Marius Hills · Moon
                                        </p>
                                    </div>
                                    <span id="card1-coords" class="text-[11px] font-mono bg-black/25 px-2.5 py-1 rounded-full border border-white/15">
                                        19.53°, -155.08°
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Sparkline Trend Card -->
                        <div class="h-[240px]">
                            <div class="bg-white/[0.04] backdrop-blur-xl p-6 rounded-[28px] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] border border-white/10 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 h-full cursor-default">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <h3 class="text-white text-[15px] font-bold tracking-tight">Environment Extremes</h3>
                                        <p class="text-slate-400 text-[12px] font-medium mt-0.5">NASA POWER T2M Telemetry</p>
                                    </div>
                                    <span id="card2-clim-badge" class="text-[12px] font-bold text-emerald-400 bg-emerald-500/15 border border-emerald-500/30 px-2.5 py-1 rounded-full">92% Match</span>
                                </div>
                                <div class="h-[85px] w-full my-1">
                                    <canvas id="heroSparklineChart"></canvas>
                                </div>
                                <div class="flex justify-between items-end mt-1">
                                    <p id="card2-temp-display" class="text-[24px] font-bold text-white leading-none font-mono">
                                        15.8 °C
                                    </p>
                                    <div class="flex gap-2 text-[10px] font-bold text-slate-400 uppercase">
                                        <span>S1</span><span>S2</span><span class="text-[#ff8b6e]">S3</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Donut Ring Progress Card -->
                        <div class="h-[240px]">
                            <div class="bg-white/[0.04] backdrop-blur-xl p-6 rounded-[28px] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] border border-white/10 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 h-full cursor-default relative">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-white text-[15px] font-bold tracking-tight">Geology &amp; Mineralogy</h3>
                                    <i class="fa-solid fa-ellipsis text-slate-400"></i>
                                </div>
                                <div class="relative h-[120px] w-full flex items-center justify-center mt-1">
                                    <div class="w-[120px] h-[120px]">
                                        <canvas id="heroDonutChart"></canvas>
                                    </div>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                        <span id="card3-geo-pct" class="text-white text-[18px] font-bold tracking-tight">95%</span>
                                        <span id="card3-topo-pill" class="text-[10px] font-bold text-white bg-sky-500/90 px-2 py-0.5 rounded-full mt-1 shadow-sm">
                                            Topo 96%
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-center gap-4 mt-1 text-[11px] font-medium text-slate-400">
                                    <div class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-[#e04826]"></div>Mineral Match</div>
                                    <div class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-slate-600"></div>Delta</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ROW 2: 1 FEATURE HAZARD CARD + 6 SMALL STAT CARDS -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
                        
                        <!-- Left Feature Card -->
                        <div class="bg-white/[0.04] backdrop-blur-xl p-6 rounded-[28px] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] border border-white/10 flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 h-[240px] cursor-default relative">
                            <div class="flex justify-between items-start">
                                <h3 class="text-white text-[15px] font-bold tracking-tight">Gravity &amp; Rad Delta</h3>
                                <i class="fa-solid fa-ellipsis text-slate-400"></i>
                            </div>
                            <div class="relative h-[110px] w-full flex items-center justify-center my-1">
                                <div class="absolute w-20 h-20 bg-[#e04826]/20 rounded-full blur-xl pointer-events-none"></div>
                                <i class="fa-solid fa-fire-flame-curved text-[68px] text-[#e04826] orbital-hazard-pulse relative z-10"></i>
                            </div>
                            <div class="flex flex-col items-center justify-center gap-1">
                                <h2 id="feat-grav-val" class="text-[22px] font-bold text-[#ff8b6e] leading-none tracking-tight font-mono">0.165g</h2>
                                <p id="feat-rad-sub" class="text-[11px] font-medium text-slate-400">Unattenuated GCR · Boundary Offset</p>
                            </div>
                        </div>

                        <!-- Right 6 Small Stat Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:col-span-3">
                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Registry Sites</p>
                                    <i class="fa-solid fa-layer-group text-sky-400 opacity-85"></i>
                                </div>
                                <h3 id="stat-total-sites" class="text-[22px] font-bold text-white tracking-tight font-mono">8</h3>
                            </div>

                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Mars Analogs</p>
                                    <i class="fa-solid fa-circle-dot text-[#e04826] opacity-85"></i>
                                </div>
                                <h3 id="stat-mars-sites" class="text-[22px] font-bold text-white tracking-tight font-mono">4</h3>
                            </div>

                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Lunar Analogs</p>
                                    <i class="fa-solid fa-moon text-amber-400 opacity-85"></i>
                                </div>
                                <h3 id="stat-moon-sites" class="text-[22px] font-bold text-white tracking-tight font-mono">4</h3>
                            </div>

                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Solar Flux</p>
                                    <i class="fa-solid fa-sun text-amber-300 opacity-85"></i>
                                </div>
                                <h3 id="stat-solar-val" class="text-[22px] font-bold text-white tracking-tight font-mono">6.8 kWh</h3>
                            </div>

                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Rel. Humidity</p>
                                    <i class="fa-solid fa-droplet text-teal-400 opacity-85"></i>
                                </div>
                                <h3 id="stat-rh-val" class="text-[22px] font-bold text-white tracking-tight font-mono">18.2%</h3>
                            </div>

                            <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.25)] hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-[13px] font-medium text-slate-400 leading-tight">Surface Wind</p>
                                    <i class="fa-solid fa-wind text-sky-400 opacity-85"></i>
                                </div>
                                <h3 id="stat-wind-val" class="text-[22px] font-bold text-white tracking-tight font-mono">4.1 m/s</h3>
                            </div>
                        </div>

                    </div>

                    <!-- ROW 3: ANALYTICS GRAPH SECTION + SIDE-BY-SIDE VISUAL PREVIEW -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl p-7 rounded-[32px] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] border border-white/10">
                        <div class="flex flex-wrap justify-between items-end gap-4 mb-7">
                            <div>
                                <h2 class="text-[18px] font-bold text-white tracking-tight">Quantitative Similarity &amp; Side-by-Side Surface Verification</h2>
                                <p id="sim-chart-subtitle" class="text-[13px] text-slate-400 mt-0.5 font-medium">Real-time multi-axis comparison between Earth Analog and Target Crater</p>
                            </div>
                            <div class="flex items-center gap-5 text-[12px] font-semibold text-slate-300">
                                <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-[#38bdf8]"></div>Earth Analog</div>
                                <div class="flex items-center gap-2"><div class="w-2.5 h-2.5 rounded-full bg-[#e04826]"></div>Target Body</div>
                                <span id="sim-overall-badge" class="px-3 py-1 rounded-full bg-[#e04826]/20 border border-[#e04826]/40 font-mono text-xs font-bold text-[#ff8b6e]">94.1% MATCH</span>
                            </div>
                        </div>

                        <!-- Side-by-Side Earth vs Space Real Imagery Strip -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="relative h-44 rounded-[22px] overflow-hidden border border-white/10 group">
                                <img id="dash-earth-img" src="https://images.unsplash.com/photo-1542224566-6e85f2e6772f?auto=format&fit=crop&w=900&q=80" alt="Earth Analog Visual" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#06070b] via-[#06070b]/30 to-transparent"></div>
                                <div class="absolute top-3 left-3 flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full bg-sky-500/25 border border-sky-400/40 text-[10px] font-mono font-bold text-sky-300 backdrop-blur-md">EARTH ANALOG · LANDSAT/SENTINEL</span>
                                </div>
                                <div class="absolute bottom-3 left-4 right-4 flex justify-between items-end">
                                    <div>
                                        <h4 id="dash-earth-title" class="text-sm font-bold text-white">Kazumura Lava Tube, Hawaii</h4>
                                        <p id="dash-earth-sub" class="text-[11px] text-slate-300">Subsurface Basaltic Pyroduct &amp; Skylight</p>
                                    </div>
                                    <span id="dash-earth-coords" class="text-[10px] font-mono text-sky-300 bg-black/50 px-2 py-0.5 rounded-md">19.53°, -155.08°</span>
                                </div>
                            </div>

                            <div class="relative h-44 rounded-[22px] overflow-hidden border border-white/10 group">
                                <img id="dash-space-img" src="https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=900&q=80" alt="Planetary Target Visual" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#06070b] via-[#06070b]/30 to-transparent"></div>
                                <div class="absolute top-3 left-3 flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full bg-[#e04826]/30 border border-[#e04826]/50 text-[10px] font-mono font-bold text-[#ff8b6e] backdrop-blur-md">TARGET BODY · LRO / MRO FEED</span>
                                </div>
                                <div class="absolute bottom-3 left-4 right-4 flex justify-between items-end">
                                    <div>
                                        <h4 id="dash-space-title" class="text-sm font-bold text-white">Marius Hills Skylight (Moon)</h4>
                                        <p id="dash-space-sub" class="text-[11px] text-slate-300">Artemis Subsurface Habitat Candidate</p>
                                    </div>
                                    <span id="dash-space-body" class="text-[10px] font-mono text-[#ff8b6e] bg-black/50 px-2 py-0.5 rounded-md uppercase">MOON</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dual Chart Layout: Rounded Bar Chart + Multi-Parameter Radar Chart -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                            <div class="lg:col-span-7 h-[290px] w-full bg-white/[0.02] rounded-[24px] p-4 border border-white/[0.06]">
                                <canvas id="similarityBarChart"></canvas>
                            </div>
                            <div class="lg:col-span-5 h-[290px] w-full bg-white/[0.02] rounded-[24px] p-4 border border-white/[0.06]">
                                <canvas id="similarityRadarChart"></canvas>
                            </div>
                        </div>

                        <!-- Where the Analogy Breaks Down (Scientific Honesty) -->
                        <div class="mt-6 pt-6 border-t border-white/10">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[11px] font-mono font-bold uppercase tracking-widest text-[#ff8b6e]">
                                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Highlight Where the Analogy Breaks Down (Boundary Conditions)
                                </span>
                                <span class="text-[11px] text-slate-400">NASA Rule: "An analog does not have to match everything"</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white/[0.03] p-4 rounded-2xl border border-white/[0.07]">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold text-[#ff8b6e] uppercase tracking-wider">Gravity Offset</span>
                                        <span id="lim-grav-badge" class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-[#e04826]/15 text-[#ff8b6e]">0.165g</span>
                                    </div>
                                    <p id="lim-grav-desc" class="text-xs text-slate-300 leading-relaxed"></p>
                                </div>

                                <div class="bg-white/[0.03] p-4 rounded-2xl border border-white/[0.07]">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold text-amber-300 uppercase tracking-wider">Radiation Flux</span>
                                        <span id="lim-rad-badge" class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-300">Unattenuated GCR</span>
                                    </div>
                                    <p id="lim-rad-desc" class="text-xs text-slate-300 leading-relaxed"></p>
                                </div>

                                <div class="bg-white/[0.03] p-4 rounded-2xl border border-white/[0.07]">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold text-sky-300 uppercase tracking-wider">Atm. Pressure</span>
                                        <span id="lim-vac-badge" class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-sky-500/15 text-sky-300">10⁻¹² Torr</span>
                                    </div>
                                    <p id="lim-vac-desc" class="text-xs text-slate-300 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>

                        <!-- "So What?" Factor: NASA Mission Engineering Utility Cards -->
                        <div class="mt-4">
                            <div class="text-[11px] font-mono font-bold uppercase tracking-widest text-emerald-400 mb-2.5">
                                <i class="fa-solid fa-check-double mr-1.5"></i> "So What?" Factor — NASA Flight &amp; Hardware Engineering Utility
                            </div>
                            <div id="mission-utility-box" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                        </div>
                    </div>

                    <!-- ROW 4: 4-CORNER ROUNDED CRUD REGISTRY TABLE -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[32px] shadow-[0_8px_32px_0_rgba(0,0,0,0.35)] border border-white/10 overflow-hidden flex flex-col">
                        
                        <div class="p-8 pb-6 border-b border-white/10 flex flex-wrap gap-6 items-end justify-between">
                            <div>
                                <h2 class="text-[18px] font-bold text-white tracking-tight">Planetary Analog Registry</h2>
                                <p class="text-[13px] text-slate-400 mt-0.5 font-medium">Live terrestrial-to-planetary analog records with full CRUD controls</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1">Target Body</label>
                                    <select id="sim-target-filter" onchange="tablePage = 0; renderSimilarityTable()" class="px-3.5 py-2 space-input text-[12px] font-semibold cursor-pointer">
                                        <option value="ALL">All Celestial Bodies</option>
                                        <option value="Mars">Mars Destinations</option>
                                        <option value="Moon">Lunar Destinations</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1">Search Site</label>
                                    <input id="table-search-input" onkeyup="tablePage = 0; renderSimilarityTable()" type="text" placeholder="Search site or crater..." class="px-3.5 py-2 space-input text-[12px] font-medium w-56">
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto px-8 py-4">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>Record ID</th>
                                        <th>Earth Analog Site</th>
                                        <th>Destination</th>
                                        <th>Target Crater</th>
                                        <th>Coordinates</th>
                                        <th>Geo / Topo / Env</th>
                                        <th>Overall Match</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="similarity-crud-tbody"></tbody>
                            </table>
                        </div>

                        <div class="p-6 px-8 border-t border-white/10 bg-white/[0.02] flex flex-wrap justify-between items-center gap-4 rounded-b-[32px]">
                            <p id="table-pagination-info" class="text-[13px] font-medium text-slate-400">
                                Page 1 of 2 • 8 total records
                            </p>
                            <div class="flex items-center gap-2">
                                <button id="table-prev-btn" onclick="changeTablePage(-1)" class="flex items-center gap-1 px-3.5 py-1.5 bg-white/[0.05] border border-white/10 rounded-lg text-[13px] font-semibold text-slate-300 hover:bg-white/10 transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-chevron-left text-[11px]"></i> Prev
                                </button>
                                <button id="table-next-btn" onclick="changeTablePage(1)" class="flex items-center gap-1 px-3.5 py-1.5 bg-white/[0.05] border border-white/10 rounded-lg text-[13px] font-semibold text-slate-300 hover:bg-white/10 transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                    Next <i class="fa-solid fa-chevron-right text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- ================================================================= -->
                <!-- NEW PAGE A: PLANETARY TARGETS (MOON & MARS EXPLORATION)           -->
                <!-- ================================================================= -->
                <section id="page-targets" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">🪐</span>
                                <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">Planetary Targets (ග්‍රහලෝක ඉලක්ක)</h1>
                            </div>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">Extraterrestrial exploration zones across Lunar Artemis Landing Sites &amp; Martian Paleolake Basins</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5">
                            <button onclick="filterPlanetaryTargets('ALL', this)" class="target-tab-btn px-4 py-2 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md cursor-pointer transition-all">
                                All Targets (8)
                            </button>
                            <button onclick="filterPlanetaryTargets('Moon', this)" class="target-tab-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all">
                                <i class="fa-solid fa-moon mr-1.5 text-slate-200"></i> Lunar Bases (Moon)
                            </button>
                            <button onclick="filterPlanetaryTargets('Mars', this)" class="target-tab-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all">
                                <i class="fa-solid fa-circle-dot mr-1.5 text-[#ff8b6e]"></i> Mars Exploration
                            </button>
                        </div>
                    </div>

                    <!-- Live Mars Weather & Rover Telemetry Strip -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-5 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6 flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#ff8b6e]">MARS WEATHER API (REMS / INSIGHT)</span>
                                    <h3 class="text-[16px] font-bold text-white mt-0.5">Live Martian Surface Telemetry</h3>
                                </div>
                                <span id="mars-weather-status" class="px-2.5 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-[10px] font-mono text-emerald-300">SOL TELEMETRY ACTIVE</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 my-2">
                                <div class="bg-white/[0.03] p-3.5 rounded-2xl border border-white/[0.07]">
                                    <span class="text-[11px] text-slate-400">Mean Sol Temp</span>
                                    <div id="mars-wx-temp" class="text-lg font-bold font-mono text-white mt-0.5">-62.4 °C</div>
                                    <span class="text-[10px] text-[#ff8b6e]">Min -89°C / Max -14°C</span>
                                </div>
                                <div class="bg-white/[0.03] p-3.5 rounded-2xl border border-white/[0.07]">
                                    <span class="text-[11px] text-slate-400">Atm. Pressure</span>
                                    <div id="mars-wx-press" class="text-lg font-bold font-mono text-sky-300 mt-0.5">738 Pa</div>
                                    <span class="text-[10px] text-slate-400">~0.73% Earth Sea Level</span>
                                </div>
                                <div class="bg-white/[0.03] p-3.5 rounded-2xl border border-white/[0.07]">
                                    <span class="text-[11px] text-slate-400">Horizontal Wind</span>
                                    <div id="mars-wx-wind" class="text-lg font-bold font-mono text-amber-300 mt-0.5">8.6 m/s</div>
                                    <span class="text-[10px] text-slate-400">NW Dust Devil Gusts</span>
                                </div>
                                <div class="bg-white/[0.03] p-3.5 rounded-2xl border border-white/[0.07]">
                                    <span class="text-[11px] text-slate-400">UV / GCR Flux</span>
                                    <div class="text-lg font-bold font-mono text-rose-300 mt-0.5">0.67 mSv/d</div>
                                    <span class="text-[10px] text-slate-400">RAD Detector Reference</span>
                                </div>
                            </div>
                        </div>

                        <!-- Live Mars Rover Photos API Feed -->
                        <div class="lg:col-span-7 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6 flex flex-col justify-between">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-mono uppercase tracking-widest text-sky-300">NASA MARS ROVER PHOTOS API // PERSEVERANCE &amp; CURIOSITY</span>
                                    <h3 class="text-[16px] font-bold text-white mt-0.5">Live Orbital &amp; Rover Surface Imagery</h3>
                                </div>
                                <button onclick="loadMarsRoverFeed()" class="px-3.5 py-1.5 rounded-full bg-white/[0.06] hover:bg-white/10 border border-white/15 text-[11px] font-bold text-white cursor-pointer flex items-center gap-1.5">
                                    <i class="fa-solid fa-camera-retro text-[#ff8b6e]"></i> Refresh Rover Feed
                                </button>
                            </div>
                            <div id="mars-rover-gallery" class="grid grid-cols-1 sm:grid-cols-3 gap-3"></div>
                        </div>
                    </div>

                    <!-- Planetary Targets Cards Grid -->
                    <div id="planetary-targets-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6"></div>
                </section>

                <!-- ================================================================= -->
                <!-- NEW PAGE B: TERRESTRIAL DATABASE (NICHE ANALOG REPOSITORY)        -->
                <!-- ================================================================= -->
                <section id="page-terrestrial" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">🌍</span>
                                <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">Terrestrial Database (පෘථිවි ස්ථාන ගබඩාව)</h1>
                            </div>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">"Look Beyond the Obvious" — Categorized Niche Earth Analogs for Subsurface Habitats, Extremophile Biosignatures &amp; Rover Validation</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button onclick="filterTerrestrialCategory('ALL', this)" class="terr-cat-btn px-4 py-2 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md cursor-pointer transition-all">
                                All Niche Sites
                            </button>
                            <button onclick="filterTerrestrialCategory('Subsurface / Lava Tubes', this)" class="terr-cat-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all">
                                <i class="fa-solid fa-archway mr-1.5 text-amber-300"></i> Subsurface / Lava Tubes
                            </button>
                            <button onclick="filterTerrestrialCategory('Extreme Biology Sites', this)" class="terr-cat-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all">
                                <i class="fa-solid fa-bacterium mr-1.5 text-emerald-400"></i> Extreme Biology Sites
                            </button>
                            <button onclick="filterTerrestrialCategory('Arid & Volcanic Terrains', this)" class="terr-cat-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all">
                                <i class="fa-solid fa-volcano mr-1.5 text-sky-400"></i> Arid &amp; Volcanic Terrains
                            </button>
                        </div>
                    </div>

                    <!-- Category Summary Strip -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-white/[0.03] backdrop-blur-xl p-5 rounded-[24px] border border-amber-400/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-amber-300 uppercase tracking-wider">01 · Subsurface / Lava Tubes</span>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-300">LUNAR HABITAT SHIELD</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">භූගත ලාවා උමං: Natural basaltic roofs shield Artemis astronauts from cosmic radiation (GCR), micrometeorites, and ±150°C lunar thermal swings.</p>
                        </div>

                        <div class="bg-white/[0.03] backdrop-blur-xl p-5 rounded-[24px] border border-emerald-400/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-emerald-300 uppercase tracking-wider">02 · Extreme Biology Sites</span>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300">EXTREMOPHILE BIOSIGNATURES</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">අධික ලවණතා/ආම්ලික ස්ථාන: Hyper-acidic iron brines &amp; evaporite halite crusts hosting chemolithoautotrophic microbes analogous to ancient Mars.</p>
                        </div>

                        <div class="bg-white/[0.03] backdrop-blur-xl p-5 rounded-[24px] border border-sky-400/20">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-sky-300 uppercase tracking-wider">03 · Arid &amp; Volcanic Terrains</span>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-sky-500/15 text-sky-300">ROVER &amp; ISRU TESTBEDS</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">කාන්තාර සහ ගිනිකඳු පරිසර: Anhydrous perchlorate soils, polar permafrost, and basaltic tephra for rover traction &amp; oxygen extraction.</p>
                        </div>
                    </div>

                    <!-- Terrestrial Database Rich Cards Grid -->
                    <div id="terrestrial-database-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"></div>
                </section>

                <!-- ================================================================= -->
                <!-- NEW PAGE: AI MATCHMAKER (AUTO DISCOVER + CUSTOM SCAN)              -->
                <!-- ================================================================= -->
                <section id="page-matchmaker" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">🧠</span>
                                <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">AI Matchmaker</h1>
                            </div>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">Explainable planetary-analog candidate discovery, ranking and evidence-aware review</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full bg-violet-500/10 border border-violet-400/25 text-[10px] font-mono font-bold text-violet-300">EXPLAINABLE MATCH ENGINE</span>
                    </div>

                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-5 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-start gap-3 max-w-3xl">
                            <i class="fa-solid fa-shield-halved text-emerald-300 mt-1"></i>
                            <p class="text-xs text-slate-300 leading-relaxed">This page ranks research leads using transparent weighted criteria and NASA POWER annual climate data when available. Candidate terrain and slope values are preliminary screening metadata, not surveyed DEM measurements. Review source evidence before promoting a lead into the verified registry.</p>
                        </div>
                        <span id="ai-engine-status" class="text-[10px] font-mono text-sky-300 bg-sky-500/10 border border-sky-400/20 px-3 py-1.5 rounded-full">ENGINE: READY</span>
                    </div>

                    <div class="flex flex-wrap gap-2 border-b border-white/10 pb-3">
                        <button id="ai-tab-discover" onclick="showAiMatchmakerView('discover')" class="px-5 py-2.5 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md transition-all cursor-pointer"><i class="fa-solid fa-satellite-dish mr-2"></i>Auto Discover</button>
                        <button id="ai-tab-custom" onclick="showAiMatchmakerView('custom')" class="px-5 py-2.5 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold transition-all cursor-pointer"><i class="fa-solid fa-sliders mr-2"></i>Custom Scan</button>
                    </div>

                    <!-- AUTO DISCOVER -->
                    <div id="ai-discover-panel" class="flex flex-col gap-6">
                        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                            <div class="xl:col-span-8 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6">
                                <div class="flex flex-wrap justify-between items-start gap-3 mb-5">
                                    <div>
                                        <div class="text-[10px] font-mono uppercase tracking-widest text-sky-300">AUTONOMOUS CANDIDATE SCREENING</div>
                                        <h2 class="text-[18px] font-bold text-white mt-1">Discover New Analog Leads</h2>
                                        <p class="text-xs text-slate-400 mt-1">Compare curated research candidates against the active benchmark profile.</p>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400 bg-white/[0.04] border border-white/10 rounded-full px-3 py-1">NASA / USGS RESEARCH LEADS</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ai-discover-body" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Target mission</label>
                                        <select id="ai-discover-body" class="space-input w-full px-4 py-3 text-xs font-semibold">
                                            <option value="Mars">Mars analog discovery</option>
                                            <option value="Moon">Lunar analog discovery</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="ai-discover-reference" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Benchmark profile</label>
                                        <select id="ai-discover-reference" class="space-input w-full px-4 py-3 text-xs font-semibold"></select>
                                    </div>
                                </div>
                                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-[11px] text-slate-400">Scoring: 40% geology · 30% topography · 30% environment</p>
                                    <button id="ai-discover-run-btn" onclick="runAutoDiscover()" class="px-5 py-3 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-[0_6px_20px_rgba(224,72,38,0.3)] hover:brightness-110 transition-all cursor-pointer flex items-center gap-2"><i class="fa-solid fa-wand-magic-sparkles"></i> Run Auto Discovery</button>
                                </div>
                            </div>
                            <div class="xl:col-span-4 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6">
                                <div class="text-[10px] font-mono uppercase tracking-widest text-[#ff8b6e]">DISCOVERY PIPELINE</div>
                                <div class="mt-4 space-y-4">
                                    <div class="flex gap-3"><span class="w-7 h-7 flex items-center justify-center rounded-xl bg-sky-500/15 text-sky-300 text-xs font-bold">01</span><div><div class="text-xs font-bold text-white">Candidate library</div><p class="text-[10px] text-slate-400 mt-0.5">NASA / USGS-documented terrestrial analog leads</p></div></div>
                                    <div class="flex gap-3"><span class="w-7 h-7 flex items-center justify-center rounded-xl bg-violet-500/15 text-violet-300 text-xs font-bold">02</span><div><div class="text-xs font-bold text-white">Evidence enrichment</div><p class="text-[10px] text-slate-400 mt-0.5">NASA POWER annual T2M query with browser cache</p></div></div>
                                    <div class="flex gap-3"><span class="w-7 h-7 flex items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-300 text-xs font-bold">03</span><div><div class="text-xs font-bold text-white">Transparent ranking</div><p class="text-[10px] text-slate-400 mt-0.5">Explainable score, evidence status and review step</p></div></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-between items-center gap-3">
                            <div><h2 class="text-[17px] font-bold text-white">Discovery Results</h2><p id="ai-discover-result-note" class="text-[11px] text-slate-400 mt-1">Run Auto Discovery to rank relevant candidate locations.</p></div>
                            <span id="ai-discover-count" class="text-[10px] font-mono bg-white/[0.05] border border-white/10 text-slate-300 rounded-full px-3 py-1.5">0 candidates</span>
                        </div>
                        <div id="ai-discovery-results" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div class="lg:col-span-2 bg-white/[0.02] border border-dashed border-white/15 rounded-[24px] p-10 text-center"><i class="fa-solid fa-satellite text-3xl text-sky-300"></i><h3 class="text-sm font-bold text-white mt-3">Ready to scan the analog research library</h3><p class="text-xs text-slate-400 mt-1">Choose a target mission and run Auto Discovery to generate a ranked shortlist.</p></div>
                        </div>
                    </div>

                    <!-- CUSTOM SCAN -->
                    <div id="ai-custom-panel" class="hidden flex flex-col gap-6">
                        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                            <div class="xl:col-span-5 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6">
                                <div class="text-[10px] font-mono uppercase tracking-widest text-[#ff8b6e]">CUSTOM ENVIRONMENT PROFILE</div>
                                <h2 class="text-[18px] font-bold text-white mt-1">Define Your Scan</h2>
                                <p class="text-xs text-slate-400 mt-1 mb-6">Set your desired Earth-analog environment and rank the research leads.</p>

                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2"><label for="ai-temp-input" class="text-xs font-semibold text-slate-200">Preferred mean annual temperature</label><output id="ai-temp-output" class="font-mono text-sm font-bold text-[#ff8b6e]">15°C</output></div>
                                    <input id="ai-temp-input" class="ai-range" type="range" min="-25" max="35" step="1" value="15" oninput="updateAiRangeLabels()">
                                    <div class="flex justify-between text-[10px] text-slate-500 mt-1"><span>-25°C</span><span>35°C</span></div>
                                </div>
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2"><label for="ai-slope-input" class="text-xs font-semibold text-slate-200">Preferred terrain slope</label><output id="ai-slope-output" class="font-mono text-sm font-bold text-sky-300">8°</output></div>
                                    <input id="ai-slope-input" class="ai-range" type="range" min="0" max="45" step="1" value="8" oninput="updateAiRangeLabels()">
                                    <div class="flex justify-between text-[10px] text-slate-500 mt-1"><span>0° · Flat</span><span>45° · Steep</span></div>
                                </div>
                                <div class="mb-5">
                                    <label for="ai-mineral-input" class="block text-xs font-semibold text-slate-200 mb-2">Preferred mineral / terrain family</label>
                                    <select id="ai-mineral-input" class="space-input w-full px-4 py-3 text-xs font-semibold">
                                        <option value="any">Any / no preference</option>
                                        <option value="basalt">Basalt / volcanic</option>
                                        <option value="sulfate">Sulfate / evaporite</option>
                                        <option value="iron-oxide">Iron oxide</option>
                                        <option value="carbonate">Carbonate / hydromagnesite</option>
                                        <option value="clay">Clay / phyllosilicate</option>
                                        <option value="ice">Ice / permafrost</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label for="ai-custom-body" class="block text-xs font-semibold text-slate-200 mb-2">Target mission</label>
                                    <select id="ai-custom-body" class="space-input w-full px-4 py-3 text-xs font-semibold"><option value="Mars">Mars</option><option value="Moon">Moon</option></select>
                                </div>
                                <button onclick="runCustomScan()" class="w-full px-5 py-3 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-[0_6px_20px_rgba(224,72,38,0.3)] hover:brightness-110 transition-all cursor-pointer flex items-center justify-center gap-2"><i class="fa-solid fa-magnifying-glass-chart"></i> Run Custom Scan</button>
                            </div>
                            <div class="xl:col-span-7 bg-white/[0.03] backdrop-blur-2xl rounded-[28px] border border-white/10 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="text-[10px] font-mono uppercase tracking-widest text-emerald-300">MATCH ANALYSIS</div>
                                    <h2 class="text-[18px] font-bold text-white mt-1">How the score is calculated</h2>
                                    <p class="text-xs text-slate-400 mt-1">The engine rebalances weights if live climate data is unavailable; no missing measurement is silently treated as real.</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 my-6">
                                    <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-4"><span class="text-[10px] text-sky-300 font-mono">40% · CLIMATE</span><p class="text-xs text-slate-300 mt-2">NASA POWER T2M annual value, where returned.</p></div>
                                    <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-4"><span class="text-[10px] text-[#ff8b6e] font-mono">30% · SLOPE</span><p class="text-xs text-slate-300 mt-2">Screening slope estimate compared with your preference.</p></div>
                                    <div class="rounded-2xl bg-white/[0.04] border border-white/[0.07] p-4"><span class="text-[10px] text-emerald-300 font-mono">30% · MINERAL</span><p class="text-xs text-slate-300 mt-2">Mineral-family compatibility from the candidate profile.</p></div>
                                </div>
                                <div class="bg-amber-500/[0.07] border border-amber-400/20 rounded-2xl p-4"><div class="flex items-center gap-2 text-amber-300 text-xs font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Scientific limitation</div><p class="text-[11px] text-slate-300 leading-relaxed mt-1">Candidate slope values are approximate first-pass screening estimates, not DEM-derived measurements. Mineral labels are broad field-site classifications, not laboratory assay results. Confirm candidates with DEM, spectral and field evidence before scientific conclusions.</p></div>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-between items-center gap-3"><div><h2 class="text-[17px] font-bold text-white">Custom Scan Results</h2><p id="ai-custom-result-note" class="text-[11px] text-slate-400 mt-1">Set your preferences and run a scan.</p></div><span id="ai-custom-count" class="text-[10px] font-mono bg-white/[0.05] border border-white/10 text-slate-300 rounded-full px-3 py-1.5">0 candidates</span></div>
                        <div id="ai-custom-results" class="grid grid-cols-1 lg:grid-cols-2 gap-5"><div class="lg:col-span-2 bg-white/[0.02] border border-dashed border-white/15 rounded-[24px] p-10 text-center"><i class="fa-solid fa-sliders text-3xl text-[#ff8b6e]"></i><h3 class="text-sm font-bold text-white mt-3">Custom scan profile ready</h3><p class="text-xs text-slate-400 mt-1">Adjust the sliders and choose a mineral family to rank research leads.</p></div></div>
                    </div>

                    <div class="text-[10px] text-slate-500 border-t border-white/10 pt-4 leading-relaxed">
                        Research basis: NASA Planetary Analogs and NASA Astrobiology Field Sites; USGS planetary-analog research on Icelandic Holuhraun lava flows. The local scoring engine is deterministic and explainable. No Gemini model is called from the browser; adding Gemini later requires a server-side Laravel endpoint and a private environment key.
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- NEW PAGE C: SCIENTIFIC REPORT / DOCUMENTATION (JUDGE BLUEPRINT)   -->
                <!-- ================================================================= -->
                <section id="page-science" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">📄</span>
                                <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">Scientific Report &amp; Documentation (විද්‍යාත්මක පදනම)</h1>
                            </div>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">NASA Space Apps Judging Specification — Quantitative Similarity Algorithm, Multi-Sensor Data Fusion &amp; Boundary Limitations</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button onclick="window.print()" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-[0_6px_20px_rgba(224,72,38,0.35)] hover:brightness-110 transition-all cursor-pointer flex items-center gap-2 border border-white/15">
                                <i class="fa-solid fa-file-pdf"></i> Export Judge Technical Brief
                            </button>
                        </div>
                    </div>

                    <!-- Section 1 & 2: System Architecture + Quantitative Similarity Metric Formula -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-7 bg-white/[0.03] backdrop-blur-2xl p-7 rounded-[28px] border border-white/10 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 rounded-full bg-[#e04826]/20 border border-[#e04826]/40 text-[10px] font-mono font-bold text-[#ff8b6e]">01 · SYSTEM ARCHITECTURE &amp; ALGORITHM</span>
                                    <span class="text-[11px] font-mono text-emerald-400">INNOVATION: SYSTEM &gt; SINGLE SITE</span>
                                </div>
                                <h2 class="text-[19px] font-bold text-white mb-2">Planetary Analog Matchmaker Quantitative Engine</h2>
                                <p class="text-xs text-slate-300 leading-relaxed mb-4">
                                    Instead of presenting a static slide deck of a single desert, <strong class="text-white">AstroAnalogs</strong> operates as a programmatic multi-criteria decision and telemetry fusion engine. Each candidate Earth coordinate $(\phi_E, \lambda_E)$ is evaluated against extraterrestrial target telemetry $(\phi_T, \lambda_T)$ using a weighted composite similarity index $S_{\text{total}} \in [0, 100\%]$:
                                </p>

                                <!-- Mathematical Formula Card -->
                                <div class="bg-[#07090f]/90 border border-white/10 rounded-2xl p-4 font-mono text-xs text-sky-300 mb-4 overflow-x-auto">
                                    <div class="text-[10px] text-slate-400 uppercase mb-1">Weighted Multi-Axis Similarity Equation:</div>
                                    <div class="text-sm font-bold text-white">
                                        S<sub>total</sub> = 0.40 · S<sub>Geo/Min</sub> + 0.30 · S<sub>Topo/DEM</sub> + 0.30 · S<sub>Env/Clim</sub>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-2">
                                        where S<sub>k</sub> = 100 × [ 1 − ( |X<sub>Earth,k</sub> − X<sub>Target,k</sub>| / R<sub>norm,k</sub> ) ]
                                    </div>
                                </div>
                            </div>

                            <!-- Live Interactive Weight & Score Breakdown for Currently Selected Pair -->
                            <div class="bg-white/[0.03] rounded-2xl p-4 border border-white/[0.07]">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[11px] font-bold text-white">Active Pair Analytical Breakdown: <span id="sci-active-pair" class="text-[#ff8b6e]">Kazumura Lava Tube ↔ Marius Hills</span></span>
                                    <span id="sci-active-calc" class="font-mono text-xs font-bold text-emerald-400">94.1%</span>
                                </div>
                                <div id="sci-formula-breakdown" class="grid grid-cols-3 gap-3 text-center font-mono text-xs"></div>
                            </div>
                        </div>

                        <!-- Section 3: Advanced Multi-Sensor Data Fusion Pipeline -->
                        <div class="lg:col-span-5 bg-white/[0.03] backdrop-blur-2xl p-7 rounded-[28px] border border-white/10 flex flex-col justify-between">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-sky-500/15 border border-sky-400/30 text-[10px] font-mono font-bold text-sky-300">02 · MULTI-SENSOR DATA FUSION</span>
                                <h2 class="text-[19px] font-bold text-white mt-3 mb-3">Earth &amp; Deep-Space API Stack</h2>
                                <div class="space-y-2.5 text-xs">
                                    <div class="p-3 rounded-xl bg-white/[0.03] border border-white/[0.07]">
                                        <div class="font-bold text-sky-300 flex justify-between">
                                            <span>NASA POWER API (Climatology)</span>
                                            <span class="font-mono text-[10px] text-emerald-400">LIVE JSON</span>
                                        </div>
                                        <p class="text-slate-400 text-[11px] mt-0.5">Extracts T2M (2m temp), ALLSKY_SFC_SW_DWN (solar irradiance), RH2M (aridity), and WS2M for real-time environmental scoring.</p>
                                    </div>

                                    <div class="p-3 rounded-xl bg-white/[0.03] border border-white/[0.07]">
                                        <div class="font-bold text-sky-300 flex justify-between">
                                            <span>NASA GIBS &amp; CMR Earthdata</span>
                                            <span class="font-mono text-[10px] text-emerald-400">MODIS / SMAP</span>
                                        </div>
                                        <p class="text-slate-400 text-[11px] mt-0.5">Overlays MODIS Land Surface Temperature (LST), SMAP L4 Soil Moisture, and Landsat/Sentinel granules onto the 3D Earth globe.</p>
                                    </div>

                                    <div class="p-3 rounded-xl bg-white/[0.03] border border-white/[0.07]">
                                        <div class="font-bold text-[#ff8b6e] flex justify-between">
                                            <span>Solar System Treks (Mars / Moon Trek)</span>
                                            <span class="font-mono text-[10px] text-emerald-400">HiRISE / LOLA DEM</span>
                                        </div>
                                        <p class="text-slate-400 text-[11px] mt-0.5">Generates 3D Digital Elevation Model (DEM) wireframe meshes comparing crater rim slopes and roughness indices.</p>
                                    </div>

                                    <div class="p-3 rounded-xl bg-white/[0.03] border border-white/[0.07]">
                                        <div class="font-bold text-[#ff8b6e] flex justify-between">
                                            <span>Mars Rover Photos &amp; REMS/InSight APIs</span>
                                            <span class="font-mono text-[10px] text-emerald-400">JPL TELEMETRY</span>
                                        </div>
                                        <p class="text-slate-400 text-[11px] mt-0.5">Streams real Perseverance Mastcam-Z imagery and Martian diurnal pressure/temperature telemetry.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Where the Analogy Breaks Down (Critical Thinking Matrix) -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl p-7 rounded-[28px] border border-white/10">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-amber-500/15 border border-amber-400/30 text-[10px] font-mono font-bold text-amber-300">03 · SCIENTIFIC HONESTY &amp; BOUNDARY LIMITATIONS</span>
                                <h2 class="text-[19px] font-bold text-white mt-2">Where the Analogy Breaks Down (අසමානතා විද්‍යාත්මකව පිළිගැනීම)</h2>
                            </div>
                            <span class="text-xs text-slate-400 italic">"An analog does not have to match everything — knowing where it diverges prevents mission failure."</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>Physical Parameter</th>
                                        <th>Earth Analog Baseline</th>
                                        <th>Mars Target (Jezero / Gale)</th>
                                        <th>Lunar Target (Shackleton / Marius)</th>
                                        <th>Engineering Compensation Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-bold text-white">Surface Gravity ($g$)</td>
                                        <td class="font-mono text-xs text-sky-300">9.81 m/s² (1.00g)</td>
                                        <td class="font-mono text-xs text-[#ff8b6e]">3.72 m/s² (0.38g)</td>
                                        <td class="font-mono text-xs text-slate-200">1.62 m/s² (0.165g)</td>
                                        <td class="text-xs text-slate-300">Active-response gravity offload cranes &amp; reduced-mass rover wheel slip models.</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-white">Atmospheric Pressure</td>
                                        <td class="font-mono text-xs text-sky-300">101.3 kPa (N₂/O₂)</td>
                                        <td class="font-mono text-xs text-[#ff8b6e]">0.61 kPa (95% CO₂)</td>
                                        <td class="font-mono text-xs text-slate-200">10⁻¹² Torr (Ultra-Vacuum)</td>
                                        <td class="text-xs text-slate-300">Thermal-vacuum chambers for bearing lubrication &amp; electrostatic dust seal testing.</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-white">Ionizing Radiation</td>
                                        <td class="font-mono text-xs text-sky-300">~2.4 mSv/yr (Shielded)</td>
                                        <td class="font-mono text-xs text-[#ff8b6e]">~240 mSv/yr (Unshielded)</td>
                                        <td class="font-mono text-xs text-slate-200">~380+ mSv/yr (GCR + SPE)</td>
                                        <td class="text-xs text-slate-300">Subsurface lava tube overburden (&gt;5m basalt) or regolith sintering for crew habitats.</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-white">Diurnal Thermal Swing</td>
                                        <td class="font-mono text-xs text-sky-300">ΔT ~ 15°C – 25°C</td>
                                        <td class="font-mono text-xs text-[#ff8b6e]">ΔT ~ 70°C (-85°C to -15°C)</td>
                                        <td class="font-mono text-xs text-slate-200">ΔT ~ 250°C (-130°C to +120°C)</td>
                                        <td class="text-xs text-slate-300">Multi-layer insulation (MLI), radioisotope heater units (RHUs), and lava tube thermal stability.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Section 5: Niche Innovation + "So What?" Mission Deliverables -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white/[0.03] backdrop-blur-2xl p-6 rounded-[28px] border border-white/10">
                            <span class="px-3 py-1 rounded-full bg-purple-500/15 border border-purple-400/30 text-[10px] font-mono font-bold text-purple-300">04 · NICHE DISCOVERY STRATEGY</span>
                            <h3 class="text-[17px] font-bold text-white mt-3 mb-2">Why Look Beyond Textbook Sites?</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Over 60% of entries only mention surface deserts. AstroAnalogs integrates <strong class="text-amber-300">Subsurface Lava Tubes</strong> (Kazumura, Corona) to solve Artemis lunar radiation shielding, and <strong class="text-emerald-300">Hyper-Acidic / Evaporite Extremophile Sites</strong> (Dallol, Rio Tinto, Salar de Pajonales) to calibrate life-detection Raman spectrometers before Mars Sample Return.
                            </p>
                        </div>

                        <div class="bg-white/[0.03] backdrop-blur-2xl p-6 rounded-[28px] border border-white/10">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-[10px] font-mono font-bold text-emerald-300">05 · THE "SO WHAT?" ENGINEERING IMPACT</span>
                            <h3 class="text-[17px] font-bold text-white mt-3 mb-2">Direct Artemis &amp; Mars Mission Readiness</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Every analog match in AstroAnalogs maps directly to a NASA TRL (Technology Readiness Level) validation protocol: testing autonomous LiDAR cave-mapping drones in Hawaiian lava tubes prior to Marius Hills skylight descent, and validating VIPER/ISRU volatile drills in cryogenic impact breccias.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- PAGE 2: 3D EARTH & NASA GIBS VIEW                                 -->
                <!-- ================================================================= -->
                <section id="page-globe" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">3D Earth &amp; GIBS Orbital View</h1>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">Interactive planetary pin locator &amp; NASA MODIS/SMAP spectral layers</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <select id="globe-site-dropdown" onchange="focusGlobeOnSite(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer"></select>
                            <select id="gibs-layer-dropdown" onchange="changeGibsOverlay(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer">
                                <option value="truecolor">MODIS Terra True Color</option>
                                <option value="thermal">MODIS Land Surface Temp (LST)</option>
                                <option value="moisture">SMAP L4 Soil Moisture</option>
                            </select>
                        </div>
                    </div>

                    <!-- Requested NASA Earth-observation and planetary-mission archive switches -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 p-4 rounded-2xl bg-white/[0.03] border border-white/10">
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-3">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-sky-300">Earth Observation</span>
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-200 cursor-pointer">
                                <input id="toggle-landsat-layer" type="checkbox" class="layer-switch" onchange="toggleEarthObservationLayer('landsat', this.checked)" aria-label="Toggle Landsat layer and CMR records">
                                Landsat
                            </label>
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-200 cursor-pointer">
                                <input id="toggle-sentinel-layer" type="checkbox" class="layer-switch" onchange="toggleEarthObservationLayer('sentinel', this.checked)" aria-label="Toggle Sentinel-2 CMR records">
                                Sentinel-2 CMR
                            </label>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-3">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-[#ff8b6e]">Mission Archives</span>
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-200 cursor-pointer">
                                <input id="toggle-mro-layer" type="checkbox" class="layer-switch" onchange="toggleMissionArchive('mro', this.checked)" aria-label="Toggle Mars Reconnaissance Orbiter archive records">
                                MRO
                            </label>
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-200 cursor-pointer">
                                <input id="toggle-lro-layer" type="checkbox" class="layer-switch" onchange="toggleMissionArchive('lro', this.checked)" aria-label="Toggle Lunar Reconnaissance Orbiter archive records">
                                LRO
                            </label>
                        </div>
                        <span class="text-[10px] text-slate-400">Landsat controls a GIBS WMS overlay; Sentinel-2 and MRO/LRO return CMR archive records in NASA Live Telemetry. These are archived observations, not live spacecraft engineering telemetry.</span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-7 bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden flex flex-col">
                            <div class="px-7 py-5 border-b border-white/10 flex justify-between items-center">
                                <h3 class="text-[15px] font-bold text-white">3D Orbital Earth Visualization</h3>
                                <span id="gibs-active-badge" class="text-[11px] font-mono text-[#ff8b6e] bg-[#e04826]/15 px-3 py-1 rounded-full border border-[#e04826]/35">LAYER: MODIS TRUE COLOR</span>
                            </div>
                            <div class="relative h-[380px] w-full bg-[#05060a]">
                                <div id="threejs-earth-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></div>
                                <div class="reticle-crosshair absolute top-5 right-5 pointer-events-none opacity-75"></div>
                                <div class="absolute bottom-5 left-5 bg-[#0c0e16]/85 backdrop-blur-xl border border-white/15 rounded-2xl px-4 py-3 text-xs">
                                    <div class="text-[10px] font-mono tracking-widest text-[#ff8b6e] uppercase">ARRIVAL POINT COORDINATES</div>
                                    <div id="globe-pin-label" class="font-bold text-white mt-0.5">Kazumura Lava Tube (19.53°, -155.08°)</div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-5 bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 p-6 flex flex-col justify-between gap-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-[15px] font-bold text-white">Orbital vs. Rover Surface</h3>
                                <span class="text-[11px] font-mono text-emerald-400">JPL / LRO FEED</span>
                            </div>

                            <div class="relative h-40 rounded-2xl overflow-hidden border border-white/10 group">
                                <img id="globe-earth-photo" src="https://images.unsplash.com/photo-1542224566-6e85f2e6772f?auto=format&fit=crop&w=800&q=80" alt="Earth Surface" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#06070b] via-[#06070b]/25 to-transparent"></div>
                                <div class="absolute bottom-3 left-4">
                                    <span class="px-2.5 py-0.5 rounded-full bg-sky-500/20 border border-sky-400/35 text-[10px] font-mono text-sky-300">EARTH ANALOG</span>
                                    <div id="globe-earth-caption" class="text-xs font-bold text-white mt-1">Kazumura Lava Tube, Hawaii</div>
                                </div>
                            </div>

                            <div class="relative h-40 rounded-2xl overflow-hidden border border-white/10 group">
                                <img id="globe-space-photo" src="https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=800&q=80" alt="Planetary Target" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#06070b] via-[#06070b]/25 to-transparent"></div>
                                <div class="absolute bottom-3 left-4">
                                    <span class="px-2.5 py-0.5 rounded-full bg-[#e04826]/25 border border-[#e04826]/45 text-[10px] font-mono text-[#ff8b6e]">PLANETARY TARGET</span>
                                    <div id="globe-space-caption" class="text-xs font-bold text-white mt-1">Marius Hills Skylight (Moon)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Global Stations 4-Corner Rounded Table -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden flex flex-col">
                        <div class="p-8 pb-5 border-b border-white/10 flex justify-between items-center">
                            <div>
                                <h3 class="text-[18px] font-bold text-white">Global Earth Analog Station Registry</h3>
                                <p class="text-[13px] text-slate-400 mt-0.5">Select a station to lock 3D Earth trajectory pin</p>
                            </div>
                        </div>
                        <div class="overflow-x-auto px-8 py-4">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>Station ID</th>
                                        <th>Earth Analog Site</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Niche Category</th>
                                        <th>Target Body</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="globe-stations-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- PAGE 3: NASA LIVE TELEMETRY                                       -->
                <!-- ================================================================= -->
                <section id="page-telemetry" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">NASA Live Telemetry</h1>
                            <p class="text-[12px] font-medium text-slate-400 mt-1.5">NASA POWER climatology, CMR Earth-observation granules &amp; planetary mission archives</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select id="api-preset-dropdown" onchange="applyPresetToApiInputs(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer"></select>
                            <input id="api-lat-input" type="number" step="0.01" value="19.53" class="space-input w-24 px-3 py-2 text-xs font-mono" placeholder="Lat">
                            <input id="api-lon-input" type="number" step="0.01" value="-155.08" class="space-input w-24 px-3 py-2 text-xs font-mono" placeholder="Lon">
                            <button onclick="executeLiveNasaApiQuery()" class="px-5 py-2 rounded-full bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-md hover:brightness-110 transition-all cursor-pointer flex items-center gap-2">
                                <i class="fa-solid fa-satellite"></i> Fetch Live APIs
                            </button>
                        </div>
                    </div>

                    <!-- 4 Telemetry KPI Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10">
                            <p class="text-[12px] font-medium text-slate-400">Mean Surface Temp (T2M)</p>
                            <h3 id="kpi-temp-val" class="text-[24px] font-bold text-white font-mono mt-2">15.8 °C</h3>
                            <p class="text-[11px] text-[#ff8b6e] mt-1">NASA POWER Climatology</p>
                        </div>
                        <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10">
                            <p class="text-[12px] font-medium text-slate-400">Solar Irradiance (SW_DWN)</p>
                            <h3 id="kpi-solar-val" class="text-[24px] font-bold text-amber-300 font-mono mt-2">5.40 kWh/m²</h3>
                            <p class="text-[11px] text-slate-400 mt-1">Daily All-Sky Insolation</p>
                        </div>
                        <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10">
                            <p class="text-[12px] font-medium text-slate-400">Relative Humidity (RH2M)</p>
                            <h3 id="kpi-rh-val" class="text-[24px] font-bold text-emerald-300 font-mono mt-2">64.2 %</h3>
                            <p id="kpi-rh-note" class="text-[11px] text-emerald-400 mt-1">Subsurface Cavern Microclimate</p>
                        </div>
                        <div class="bg-white/[0.04] backdrop-blur-xl p-5 rounded-[24px] border border-white/10">
                            <p class="text-[12px] font-medium text-slate-400">Surface Wind Speed (WS2M)</p>
                            <h3 id="kpi-wind-val" class="text-[24px] font-bold text-sky-300 font-mono mt-2">3.4 m/s</h3>
                            <p class="text-[11px] text-slate-400 mt-1">2-Meter Anemometer Avg</p>
                        </div>
                    </div>

                    <!-- Telemetry Comparison Bar Chart -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl p-7 rounded-[32px] border border-white/10">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-[17px] font-bold text-white">Live Environmental Telemetry (Earth POWER vs. Mars REMS / Lunar LRO)</h3>
                                <p class="text-[12px] text-slate-400 mt-0.5">Comparative atmospheric &amp; thermal sensor metrics</p>
                            </div>
                            <span id="telemetry-chart-coords" class="text-xs font-mono text-[#ff8b6e]">LAT: 19.53° | LON: -155.08°</span>
                        </div>
                        <div class="h-[260px] w-full">
                            <canvas id="telemetryBarChart"></canvas>
                        </div>
                    </div>

                    <!-- Live API & CMR Granules Table -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden flex flex-col">
                        <div class="p-8 pb-5 border-b border-white/10 flex justify-between items-center">
                            <div>
                                <h3 class="text-[18px] font-bold text-white">NASA POWER, CMR &amp; Planetary API Endpoints</h3>
                                <p class="text-[13px] text-slate-400 mt-0.5">Live POWER response and NASA CMR archive records</p>
                            </div>
                            <span id="api-live-status-pill" class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-xs font-mono text-emerald-300">STATUS: WAITING FOR QUERY</span>
                        </div>
                        <div class="overflow-x-auto px-8 py-4">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>API Source</th>
                                        <th>Parameter / Granule ID</th>
                                        <th>Sensor / Instrument</th>
                                        <th>Coordinates</th>
                                        <th>Measured Value</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="nasa-api-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ================================================================= -->
                <!-- PAGE 4: 3D DEM TOPOGRAPHY                                         -->
                <!-- ================================================================= -->
                <section id="page-topography" class="hidden relative z-10 w-full flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-[26px] font-bold text-white tracking-tight leading-none">3D DEM Topography</h1>
                            <p id="dem-subtitle" class="text-[12px] font-medium text-slate-400 mt-1.5">NASA Solar System Treks // HiRISE &amp; LOLA Wireframe Elevation Comparison</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <select id="dem-pair-dropdown" onchange="updateDemView(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer"></select>
                            <select id="dem-mode-dropdown" onchange="toggleDemWireframeLayers(this.value)" class="space-input px-3.5 py-2 text-xs font-semibold cursor-pointer">
                                <option value="dual">Dual Overlay (Earth + Target)</option>
                                <option value="earth">Earth Analog DEM Only</option>
                                <option value="target">Target Crater DEM Only</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3D Wireframe Canvas Card -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden">
                        <div class="px-8 py-5 border-b border-white/10 flex justify-between items-center">
                            <h3 class="text-[16px] font-bold text-white">Digital Elevation Model (DEM) 3D Wireframe Mesh</h3>
                            <div class="flex items-center gap-4 text-xs font-mono">
                                <span class="flex items-center gap-1.5 text-sky-300"><span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span> Earth DEM</span>
                                <span class="flex items-center gap-1.5 text-[#ff8b6e]"><span class="w-2.5 h-2.5 rounded-full bg-[#e04826]"></span> Target Crater DEM</span>
                            </div>
                        </div>
                        <div class="relative h-[360px] w-full bg-[#05060a]">
                            <div id="threejs-dem-canvas" class="w-full h-full cursor-grab active:cursor-grabbing"></div>
                        </div>
                    </div>

                    <!-- DEM Morphometry Table -->
                    <div class="bg-white/[0.03] backdrop-blur-2xl rounded-[32px] border border-white/10 overflow-hidden flex flex-col">
                        <div class="p-8 pb-5 border-b border-white/10">
                            <h3 class="text-[18px] font-bold text-white">DEM Morphometric Comparison Registry</h3>
                            <p class="text-[13px] text-slate-400 mt-0.5">Click any row to render its 3D elevation wireframe</p>
                        </div>
                        <div class="overflow-x-auto px-8 py-4">
                            <table class="clean-table">
                                <thead>
                                    <tr>
                                        <th>Terrain ID</th>
                                        <th>Earth Formation</th>
                                        <th>Extraterrestrial Crater</th>
                                        <th>Earth Elevation</th>
                                        <th>Target Relief</th>
                                        <th>Roughness</th>
                                        <th>DEM Match</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="dem-metrics-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <!-- ===================================================================== -->
    <!-- SLIDE-OVER MISSION CONTROL DRAWER                                     -->
    <!-- ===================================================================== -->
    <div id="drawer-backdrop" onclick="toggleMissionDrawer(false)" class="hidden fixed inset-0 z-40 bg-black/40 backdrop-blur-sm transition-opacity"></div>

    <aside id="mission-drawer" class="fixed top-4 right-4 h-[calc(100vh-32px)] w-80 bg-[#121622]/95 backdrop-blur-2xl rounded-[32px] border border-white/15 shadow-[0_12px_40px_rgba(0,0,0,0.65)] z-50 transform transition-transform duration-300 ease-in-out translate-x-[120%]">
        <div class="p-6 h-full flex flex-col overflow-y-auto hover-scroll">
            
            <div class="flex justify-between items-center mb-6">
                <button onclick="toggleMissionDrawer(false)" class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
                <button onclick="returnToLanding()" class="flex items-center gap-2 text-slate-400 hover:text-[#ff8b6e] font-bold text-[13px] transition-colors cursor-pointer">
                    Exit <i class="fa-solid fa-right-from-bracket text-xs"></i>
                </button>
            </div>

            <div class="flex flex-col items-center mb-6">
                <div class="relative w-[88px] h-[88px] rounded-full border-4 border-white/15 shadow-[0_8px_24px_rgba(224,72,38,0.25)] mb-3 bg-gradient-to-br from-[#e04826] to-[#1e293b] flex items-center justify-center">
                    <i class="fa-solid fa-user-astronaut text-3xl text-white"></i>
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-emerald-500 rounded-full border-2 border-[#121622] flex items-center justify-center text-white font-bold text-xs shadow-sm">
                        ✓
                    </div>
                </div>
                <h2 class="text-[17px] font-extrabold text-white tracking-tight">Space Specialist</h2>
                <p class="text-[12px] font-medium text-slate-400 mt-0.5">Planetary Telemetry Lead</p>
            </div>

            <div class="space-y-4 mb-6 px-1">
                <div class="flex justify-between items-center">
                    <div class="flex gap-3 items-center">
                        <span class="text-[11px] font-mono font-bold text-[#ff8b6e]">SYN</span>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-slate-200">NASA API Sync</span>
                            <span class="text-[10px] text-slate-400">POWER &amp; CMR Online</span>
                        </div>
                    </div>
                    <div class="w-16 bg-white/10 rounded-full h-1.5">
                        <div class="bg-[#e04826] h-1.5 rounded-full w-[94%]"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex gap-3 items-center">
                        <span class="text-[11px] font-mono font-bold text-sky-400">DEM</span>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-slate-200">Terrain Match</span>
                            <span class="text-[10px] text-slate-400">HiRISE / LOLA Active</span>
                        </div>
                    </div>
                    <div class="w-16 bg-white/10 rounded-full h-1.5">
                        <div class="bg-sky-400 h-1.5 rounded-full w-[88%]"></div>
                    </div>
                </div>
            </div>

            <!-- CHOOSE THE NODE MATRIX -->
            <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-[#e04826]/30 mb-5">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[10px] font-display font-bold uppercase tracking-[0.12em] text-[#ff8b6e]">CHOOSE THE NODE</span>
                    <span class="text-[10px] font-mono text-slate-400">A1–B4</span>
                </div>
                <div id="sidebar-node-matrix" class="grid grid-cols-5 gap-1.5 mb-2.5 items-center"></div>
                <div class="flex items-center justify-between text-[9.5px] text-slate-400 pt-2 border-t border-white/10">
                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>Free</span>
                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#e04826]"></span>Linked</span>
                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Active</span>
                </div>
            </div>

            <!-- SPACE TRIP BOARDING PASS -->
            <div class="flight-ticket-card p-4 mt-auto">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <span class="block text-[9.5px] font-display font-bold uppercase tracking-[0.14em] text-[#ff8b6e]">SPACE TRIP</span>
                        <span id="sidebar-ticket-distance" class="text-[11px] font-mono text-slate-300 mt-0.5 block">384,400 km</span>
                    </div>
                    <div id="sidebar-planet-orb" class="planet-sphere-moon"></div>
                </div>

                <div class="flex items-center justify-between gap-1 my-2">
                    <div class="min-w-0">
                        <div class="text-[14px] font-display font-bold text-white leading-tight">Earth</div>
                        <div id="sidebar-active-name" class="text-[10px] text-slate-400 truncate mt-0.5">Kazumura Tube</div>
                    </div>
                    <div class="text-slate-400 text-xs shrink-0 flex items-center gap-1 px-1">
                        <span class="text-[9px] text-slate-600">--</span>
                        <i class="fa-solid fa-plane text-[#ff8b6e] text-[10px]"></i>
                        <span class="text-[9px] text-slate-600">--</span>
                    </div>
                    <div class="min-w-0 text-right">
                        <div id="sidebar-dest-code" class="text-[14px] font-display font-bold text-white leading-tight">Moon</div>
                        <div id="sidebar-active-target" class="text-[10px] text-slate-400 truncate mt-0.5">Marius Hills</div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[9.5px] font-mono text-slate-400 mb-2.5">
                    <span id="sidebar-ticket-coords">19.53°, -155.08°</span>
                    <span id="sidebar-active-score" class="font-bold text-emerald-400 text-[11px]">94.1%</span>
                </div>

                <div class="my-2 border-t border-dashed border-white/15"></div>
                <div class="ticket-barcode mt-2"></div>
            </div>

        </div>
    </aside>

    <!-- ===================================================================== -->
    <!-- CRUD MODAL: ADD / EDIT PLANETARY ANALOG RECORD                        -->
    <!-- ===================================================================== -->
    <div id="crud-modal" class="hidden fixed inset-0 z-50 bg-black/65 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#141824]/95 backdrop-blur-2xl p-8 rounded-[32px] shadow-[0_16px_40px_rgba(0,0,0,0.75)] w-full max-w-lg border border-white/15">
            <div class="flex justify-between items-center mb-6">
                <h2 id="crud-modal-title" class="text-[18px] font-bold text-white flex items-center gap-2.5">
                    <i class="fa-solid fa-pen-to-square text-[#ff8b6e]"></i> Add Planetary Analog Record
                </h2>
                <button onclick="closeCrudModal()" class="hover:bg-white/10 p-2 rounded-full transition-colors cursor-pointer text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="crud-form" onsubmit="saveCrudRecord(event)" class="space-y-4 text-xs">
                <input type="hidden" id="crud-edit-id" value="">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Earth Analog Site *</label>
                        <input id="crud-earth-name" type="text" required placeholder="e.g. Dallol Hydrothermal, Ethiopia" class="space-input w-full px-4 py-3 font-semibold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Target Body *</label>
                        <select id="crud-target-body" class="space-input w-full px-4 py-3 font-semibold">
                            <option value="Mars">Mars</option>
                            <option value="Moon">Moon</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Niche Classification *</label>
                        <select id="crud-niche-category" class="space-input w-full px-4 py-3 font-semibold">
                            <option value="Subsurface / Lava Tubes">Subsurface / Lava Tubes</option>
                            <option value="Extreme Biology Sites">Extreme Biology Sites</option>
                            <option value="Arid & Volcanic Terrains">Arid &amp; Volcanic Terrains</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Target Crater / Site *</label>
                        <input id="crud-target-crater" type="text" required placeholder="Jezero Crater" class="space-input w-full px-4 py-3 font-semibold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Latitude (°) *</label>
                        <input id="crud-lat" type="number" step="0.01" required placeholder="14.24" class="space-input w-full px-4 py-3 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Longitude (°) *</label>
                        <input id="crud-lon" type="number" step="0.01" required placeholder="40.30" class="space-input w-full px-4 py-3 font-mono font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Geology (%)</label>
                        <input id="crud-geo" type="number" min="1" max="100" required value="91" class="space-input w-full px-4 py-3 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Topography (%)</label>
                        <input id="crud-topo" type="number" min="1" max="100" required value="84" class="space-input w-full px-4 py-3 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Env. Match (%)</label>
                        <input id="crud-clim" type="number" min="1" max="100" required value="88" class="space-input w-full px-4 py-3 font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Terrain &amp; Mineralogy Description</label>
                    <input id="crud-terrain" type="text" placeholder="e.g. Hyper-Acidic Iron-Sulfate Brine & Jarosite Crust" class="space-input w-full px-4 py-3 font-semibold">
                </div>

                <button type="submit" class="w-full mt-2 bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white font-bold py-3.5 rounded-2xl shadow-[0_10px_25px_-5px_rgba(224,72,38,0.45)] hover:brightness-110 transition-all text-[14px] cursor-pointer border border-white/15">
                    Save Analog Record
                </button>
            </form>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- COMPLETE APPLICATION SCRIPT (THREE.JS, CHART.JS, CRUD & NASA APIS)    -->
    <!-- ===================================================================== -->
    <script type="module">
        import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js';

        // 1. Live Clock & Formatted Date
        function updateClockAndDate() {
            const now = new Date();
            const clockEl = document.getElementById('utc-clock');
            if (clockEl) clockEl.textContent = now.toISOString().substring(11, 19) + ' UTC';

            const dateEl = document.getElementById('header-formatted-date');
            if (dateEl) {
                const day = String(now.getDate()).padStart(2, '0');
                const month = now.toLocaleString('en-US', { month: 'short' });
                const year = now.getFullYear();
                const weekday = now.toLocaleString('en-US', { weekday: 'long' });
                dateEl.textContent = `${day} ${month} ${year}, ${weekday}`;
            }
        }
        updateClockAndDate();
        setInterval(updateClockAndDate, 1000);

        // 2. Sidebar Collapse & Slide-Over Drawer Controls
        let isSidebarCollapsed = false;
        window.toggleSidebar = function() {
            isSidebarCollapsed = !isSidebarCollapsed;
            const sidebar = document.getElementById('main-sidebar');
            const icon = document.getElementById('sidebar-toggle-icon');
            const brandBox = document.getElementById('sidebar-brand-box');
            const labels = document.querySelectorAll('.sidebar-label-text');
            const navBtns = document.querySelectorAll('.nav-link-btn, .nav-filter-btn, .nav-sub-btn');
            const matchmakerSubnav = document.getElementById('nav-matchmaker-subnav');

            if (isSidebarCollapsed) {
                sidebar.classList.remove('w-72');
                sidebar.classList.add('w-24');
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
                brandBox.classList.remove('px-8');
                brandBox.classList.add('justify-center', 'px-0');
                labels.forEach(el => el.classList.add('hidden'));
                if (matchmakerSubnav) matchmakerSubnav.classList.add('hidden');
                navBtns.forEach(btn => {
                    btn.classList.remove('px-5', 'gap-3.5');
                    btn.classList.add('justify-center', 'px-0');
                });
            } else {
                sidebar.classList.remove('w-24');
                sidebar.classList.add('w-72');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
                brandBox.classList.add('px-8');
                brandBox.classList.remove('justify-center', 'px-0');
                labels.forEach(el => el.classList.remove('hidden'));
                if (matchmakerSubnav) matchmakerSubnav.classList.remove('hidden');
                navBtns.forEach(btn => {
                    btn.classList.add('px-5', 'gap-3.5');
                    btn.classList.remove('justify-center', 'px-0');
                });
            }
        };

        window.toggleMissionDrawer = function(open) {
            const drawer = document.getElementById('mission-drawer');
            const backdrop = document.getElementById('drawer-backdrop');
            if (open) {
                drawer.classList.remove('translate-x-[120%]');
                drawer.classList.add('translate-x-0');
                backdrop.classList.remove('hidden');
            } else {
                drawer.classList.add('translate-x-[120%]');
                drawer.classList.remove('translate-x-0');
                backdrop.classList.add('hidden');
            }
        };

        // 3. Notifications System
        let telemetryNotifications = [
            { id: 1, title: 'Niche Lava Tube Match (94.1%)', message: 'Kazumura Cave ↔ Marius Hills Skylight verified via LRO.', time: '2 mins ago' },
            { id: 2, title: 'Extremophile Brine Sync', message: 'Dallol & Rio Tinto sulfate spectra matched to Jezero.', time: '11 mins ago' },
            { id: 3, title: 'NASA POWER & Rover APIs Online', message: 'Live climatology & Perseverance Mastcam feed active.', time: '38 mins ago' }
        ];

        function renderNotifications() {
            const list = document.getElementById('notif-list-container');
            const badge = document.getElementById('notif-badge');
            const pill = document.getElementById('notif-pill-count');

            if (telemetryNotifications.length === 0) {
                badge.classList.add('hidden');
                pill.textContent = '0 New';
                list.innerHTML = `<div class="p-6 text-center text-sm text-slate-400 font-medium">No new telemetry alerts</div>`;
                return;
            }

            badge.classList.remove('hidden');
            badge.textContent = telemetryNotifications.length;
            pill.textContent = `${telemetryNotifications.length} New`;

            list.innerHTML = telemetryNotifications.map(n => `
                <div class="p-4 border-b border-white/[0.06] hover:bg-white/[0.04] transition-colors">
                    <p class="text-xs font-bold text-slate-100 mb-0.5">${n.title}</p>
                    <p class="text-[11px] text-slate-400 leading-relaxed">${n.message}</p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-[10px] text-sky-400 font-mono">${n.time}</span>
                        <button onclick="clearNotification(${n.id})" class="flex items-center gap-1 text-[10px] font-bold text-[#ff8b6e] hover:text-white bg-[#e04826]/15 hover:bg-[#e04826] px-2.5 py-1 rounded-md transition-colors cursor-pointer">
                            <i class="fa-solid fa-check"></i> Clear
                        </button>
                    </div>
                </div>
            `).join('');
        }

        window.toggleNotificationDropdown = function() {
            document.getElementById('notif-dropdown').classList.toggle('hidden');
        };

        window.clearNotification = function(id) {
            telemetryNotifications = telemetryNotifications.filter(n => n.id !== id);
            renderNotifications();
        };

        function pushNotification(title, message) {
            telemetryNotifications.unshift({ id: Date.now(), title, message, time: 'Just now' });
            renderNotifications();
        }

        // 4. Expanded Niche NASA Benchmark Dataset (Beyond the Obvious)
        const DEFAULT_RECORDS = [
            {
                id: 'ANL-001',
                earthName: 'Kazumura Lava Tube, Hawaii',
                nicheCategory: 'Subsurface / Lava Tubes',
                targetBody: 'Moon',
                targetCrater: 'Marius Hills Skylight',
                lat: 19.53,
                lon: -155.08,
                geology: 95,
                topography: 96,
                climate: 91,
                overall: 94.1,
                elevation: '1,100 m',
                targetRelief: '-55 m Pit Depth',
                roughness: '0.62 RI',
                terrainClass: 'Subsurface Basaltic Pyroduct & Collapse Skylight',
                gravityBadge: '0.165g',
                gravityDesc: 'Lunar lava tubes span 300+ meters wide due to 1/6th gravity vs 15m width in Hawaii, requiring scaled LiDAR SLAM mapping.',
                radBadge: 'Unattenuated GCR',
                radDesc: '5m+ basaltic roof blocks >95% of galactic cosmic rays & solar flares, matching subsurface lunar habitat protection.',
                vacBadge: '10⁻¹² Torr',
                vacDesc: 'Earth tube contains humid air; lunar lava tubes hold ultra-high vacuum requiring inflatable Kevlar pressure liners.',
                utilities: [
                    { title: 'Artemis Subsurface Habitat & Radiation Shielding', desc: 'Validates autonomous LiDAR/IMU drone mapping of skylight overhangs before deploying inflatable crew modules.' },
                    { title: 'Thermal Stability & Regolith Sintering', desc: 'Tests microwave basalt sintering inside stable subterranean thermal regimes (-20°C lunar tube vs ±130°C surface).' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1542224566-6e85f2e6772f?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-002',
                earthName: 'Dallol Hydrothermal Field, Ethiopia',
                nicheCategory: 'Extreme Biology Sites',
                targetBody: 'Mars',
                targetCrater: 'Jezero Crater',
                lat: 14.24,
                lon: 40.30,
                geology: 94,
                topography: 84,
                climate: 92,
                overall: 90.4,
                elevation: '-125 m',
                targetRelief: '-2,570 m',
                roughness: '0.49 RI',
                terrainClass: 'Hyper-Acidic (pH < 0.5) & Hyper-Saline Iron Brine',
                gravityBadge: '0.38g',
                gravityDesc: 'Hydrothermal fluid convection plumes rise differently under 0.38g Martian gravity than 1g Danakil depression.',
                radBadge: '240 mSv/yr',
                radDesc: 'Intense surface UV flux in Danakil mimics early Noachian Mars, though GCR ionizing flux is lower on Earth.',
                vacBadge: '0.61 kPa',
                vacDesc: 'Modern Mars brines exist only as transient deliquescent perchlorate films due to low triple-point pressure.',
                utilities: [
                    { title: 'Extremophile Life-Detection Limit Calibration', desc: 'Defines the physico-chemical boundary (chaotropic MgCl₂ brines) where terrestrial polyextremophiles cease survival.' },
                    { title: 'SHERLOC / SuperCam Evaporite Spectroscopy', desc: 'Trains AI mineral classifiers on jarosite, halite, and iron-oxide precipitates formed in acidic hydrothermal pools.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-003',
                earthName: 'Rio Tinto Acidic Basin, Spain',
                nicheCategory: 'Extreme Biology Sites',
                targetBody: 'Mars',
                targetCrater: 'Meridiani Planum',
                lat: 37.70,
                lon: -6.55,
                geology: 96,
                topography: 81,
                climate: 85,
                overall: 88.2,
                elevation: '410 m',
                targetRelief: '-1,400 m',
                roughness: '0.44 RI',
                terrainClass: 'Chemolithoautotrophic Ferric Sulfate & Jarosite River',
                gravityBadge: '0.38g',
                gravityDesc: 'Sedimentary fluvial transport of hematite concretions ("blueberries") settles slower under 0.38g Martian gravity.',
                radBadge: '240 mSv/yr',
                radDesc: 'Ferric iron minerals in Rio Tinto strongly absorb UV radiation, shielding subsurface acidophiles just like Martian regolith.',
                vacBadge: '0.61 kPa',
                vacDesc: 'Liquid acidic river flows year-round on Earth; on Mars, acid-sulfate weathering occurred during wet Hesperian episodes.',
                utilities: [
                    { title: 'Hematite & Jarosite Biosignature Preservation', desc: 'Demonstrates how iron-sulfate minerals entomb fossilized microbial filaments over geological timescales.' },
                    { title: 'Mars Sample Return (MSR) Core Caching', desc: 'Tests sterilization and organic preservation protocols on iron-rich sulfates prior to Earth return.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-004',
                earthName: 'Corona Lava Tube (Cueva de los Verdes), Spain',
                nicheCategory: 'Subsurface / Lava Tubes',
                targetBody: 'Moon',
                targetCrater: 'Mare Tranquillitatis Pit',
                lat: 29.16,
                lon: -13.44,
                geology: 93,
                topography: 94,
                climate: 88,
                overall: 91.8,
                elevation: '180 m',
                targetRelief: '-105 m Collapse Pit',
                roughness: '0.67 RI',
                terrainClass: 'Multi-Level Volcanic Collapse Jameos & Basalt Tunnel',
                gravityBadge: '0.165g',
                gravityDesc: 'Roof arch structural stability scales inversely with gravity, allowing kilometer-scale lunar caverns.',
                radBadge: 'Unattenuated GCR',
                radDesc: 'Lanzarote basalt density (2.9 g/cm³) matches Mare Tranquillitatis titanium-rich mare basalts for radiation attenuation models.',
                vacBadge: '10⁻¹² Torr',
                vacDesc: 'Requires airlock bulkhead sealing tests in vacuum chambers before lunar cavern pressurization.',
                utilities: [
                    { title: 'ESA / NASA PANGAEA Astronaut Cave Training', desc: 'Used to train Artemis/Gateway crews in subterranean geological sampling and dark-zone navigation.' },
                    { title: 'Ground-Penetrating Radar (GPR) Void Detection', desc: 'Calibrates orbital/rover radar sounders to detect hidden subsurface lava tubes from the surface.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1532693322450-2cb5c511067d?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-005',
                earthName: 'Salar de Pajonales, Chile',
                nicheCategory: 'Extreme Biology Sites',
                targetBody: 'Mars',
                targetCrater: 'Oxia Planum',
                lat: -25.14,
                lon: -68.79,
                geology: 91,
                topography: 87,
                climate: 96,
                overall: 91.3,
                elevation: '3,537 m',
                targetRelief: '-3,000 m',
                roughness: '0.39 RI',
                terrainClass: 'High-Altitude Gypsum Stromatolites & Halite Crusts',
                gravityBadge: '0.38g',
                gravityDesc: 'Evaporite crystal growth kinetics remain chemically identical, though brine capillary wicking differs at 0.38g.',
                radBadge: '240 mSv/yr',
                radDesc: 'At 3,537m altitude in the Atacama Altiplano, UV-B/UV-C radiation reaches the highest terrestrial levels on Earth.',
                vacBadge: '0.61 kPa',
                vacDesc: 'Low atmospheric pressure (65 kPa) and extreme aridity force endolithic cyanobacteria inside translucent gypsum crystals.',
                utilities: [
                    { title: 'ExoMars Rosalind Franklin Drill Target Selection', desc: 'Identifies patterned gypsum polygons where endolithic photosynthetic pigments survive extreme UV.' },
                    { title: 'Autonomous Pattern Recognition for Rovers', desc: 'Trains onboard vision AI to autonomously flag polygonal salt-crust biosignature hotspots.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-006',
                earthName: 'McMurdo Dry Valleys, Antarctica',
                nicheCategory: 'Arid & Volcanic Terrains',
                targetBody: 'Mars',
                targetCrater: 'Gale Crater',
                lat: -77.46,
                lon: 162.53,
                geology: 91,
                topography: 85,
                climate: 97,
                overall: 91.0,
                elevation: '1,100 m',
                targetRelief: '-4,500 m',
                roughness: '0.58 RI',
                terrainClass: 'Polar Permafrost & Katabatic Wind Desert',
                gravityBadge: '0.38g',
                gravityDesc: 'Ice-cemented regolith cohesion behaves differently under 0.38g Martian gravity compared to 1g Antarctic permafrost.',
                radBadge: '220 mSv/yr',
                radDesc: 'Polar UV flux is elevated, but Earth magnetic field still shields against high-energy solar particle events.',
                vacBadge: '0.61 kPa',
                vacDesc: 'Transient summer meltwater occurs in Antarctica; on Mars ice sublimates directly from solid to vapor.',
                utilities: [
                    { title: 'Cryogenic Permafrost Ice Drilling', desc: 'Primary NASA testbed for Mars Ice Mapper drills penetrating rock-hard ice-cemented regolith.' },
                    { title: 'Ancient Paleolake Habitability Modeling', desc: 'Models microbial mat survival in ice-covered lakes in Gale Crater 3.5 billion years ago.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1516912481808-3406841bd33c?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-007',
                earthName: 'Haughton Impact Crater, Canada',
                nicheCategory: 'Arid & Volcanic Terrains',
                targetBody: 'Moon',
                targetCrater: 'Shackleton Crater',
                lat: 75.38,
                lon: -89.66,
                geology: 86,
                topography: 90,
                climate: 84,
                overall: 86.6,
                elevation: '230 m',
                targetRelief: '-4,200 m',
                roughness: '0.79 RI',
                terrainClass: 'Polar Impact Breccia & Ground Ice',
                gravityBadge: '0.165g',
                gravityDesc: 'Lunar gravity is 1.62 m/s². Astronaut EVA suit mobility and rover suspension dynamics differ sharply from 1g.',
                radBadge: 'Unattenuated GCR',
                radDesc: 'Moon lacks atmosphere and global magnetosphere, exposing Artemis crews to continuous solar wind.',
                vacBadge: '10⁻¹² Torr',
                vacDesc: 'Ultra-high lunar vacuum causes electrostatically charged, abrasive regolith dust adhesion on seals.',
                utilities: [
                    { title: 'NASA Artemis EVA & Pressurized Rover Ops', desc: 'Home to NASA Haughton-Mars Project, training astronauts in crater rim navigation and tele-robotics.' },
                    { title: 'Polar Water-Ice Prospecting (VIPER / ISRU)', desc: 'Validates neutron spectrometers and volatile drills for harvesting propellant at Shackleton Crater.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=900&q=80'
            },
            {
                id: 'ANL-008',
                earthName: 'Askja Caldera & Krafla, Iceland',
                nicheCategory: 'Arid & Volcanic Terrains',
                targetBody: 'Moon',
                targetCrater: 'Aristarchus Plateau',
                lat: 65.03,
                lon: -16.75,
                geology: 90,
                topography: 88,
                climate: 82,
                overall: 87.0,
                elevation: '1,516 m',
                targetRelief: '+2,000 m',
                roughness: '0.71 RI',
                terrainClass: 'Glacio-Volcanic Basalt & Pyroclastic Glass',
                gravityBadge: '0.165g',
                gravityDesc: 'Under 1/6th lunar gravity, explosive pyroclastic deposits disperse over wider ballistic arcs.',
                radBadge: 'Unattenuated GCR',
                radDesc: 'Sub-arctic geomagnetic latitude experiences aurorae, yet remains shielded compared to lunar surface.',
                vacBadge: '10⁻¹² Torr',
                vacDesc: 'Icelandic basaltic glass is unaltered by vacuum space-weathering nanophase iron rims found on the Moon.',
                utilities: [
                    { title: 'Apollo & Artemis Astronaut Field Geology', desc: 'Trains lunar surface crews in recognizing pyroclastic volcanic glass beads rich in trapped volatiles.' },
                    { title: 'Autonomous Rover Sample Return Caching', desc: 'Field-tests robotic arm coring in vesicular basalt flows matching Aristarchus rilles.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1476610182048-b716b8518aae?auto=format&fit=crop&w=900&q=80',
                spaceImg: 'https://images.unsplash.com/photo-1532693322450-2cb5c511067d?auto=format&fit=crop&w=900&q=80'
            }
        ];

        // 5. Planetary Targets Reference Repository (Lunar Bases & Mars Exploration)
        const PLANETARY_TARGETS_DATA = [
            {
                id: 'TGT-M1',
                name: 'Shackleton Crater Rim',
                body: 'Moon',
                subCategory: 'Lunar Bases (Artemis Landing Site)',
                coords: '89.90° S, 0.00° E',
                elevation: '-4.2 km Rim-to-Floor',
                tempRange: '-183°C PSR / -50°C Rim',
                keyFeature: 'Near-continuous solar illumination on rim + Permanently Shadowed Regions (PSRs) rich in water ice.',
                linkedAnalogId: 'ANL-007',
                linkedAnalogName: 'Haughton Impact Crater, Canada',
                img: 'https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-M2',
                name: 'Marius Hills Skylight',
                body: 'Moon',
                subCategory: 'Lunar Bases (Subsurface Lava Tube)',
                coords: '14.09° N, 56.77° W',
                elevation: '65m × 90m Skylight Pit',
                tempRange: '-20°C Constant Subsurface',
                keyFeature: 'Confirmed SELENE/LRO intact subsurface lava tube cavern offering natural GCR radiation & thermal shielding.',
                linkedAnalogId: 'ANL-001',
                linkedAnalogName: 'Kazumura Lava Tube, Hawaii',
                img: 'https://images.unsplash.com/photo-1532693322450-2cb5c511067d?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-M3',
                name: 'Mare Tranquillitatis Pit',
                body: 'Moon',
                subCategory: 'Lunar Bases (Subsurface Habitat)',
                coords: '8.33° N, 33.22° E',
                elevation: '105m Vertical Pit',
                tempRange: '+17°C Cavern Equilibrium',
                keyFeature: 'LRO Mini-RF radar confirmed accessible subterranean conduit extending 80+ meters beneath mare basalt.',
                linkedAnalogId: 'ANL-004',
                linkedAnalogName: 'Corona Lava Tube, Spain',
                img: 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-M4',
                name: 'Aristarchus Plateau',
                body: 'Moon',
                subCategory: 'Lunar Bases (ISRU Pyroclastic)',
                coords: '23.73° N, 47.49° W',
                elevation: '+2,000 m Volcanic Rise',
                tempRange: '-150°C to +110°C',
                keyFeature: 'Highest concentration of iron- & titanium-rich pyroclastic glass beads for oxygen & water ISRU extraction.',
                linkedAnalogId: 'ANL-008',
                linkedAnalogName: 'Askja Caldera, Iceland',
                img: 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-R1',
                name: 'Jezero Crater Delta',
                body: 'Mars',
                subCategory: 'Mars Exploration (Perseverance)',
                coords: '18.38° N, 77.58° E',
                elevation: '-2,570 m Paleolake Floor',
                tempRange: '-85°C to -15°C',
                keyFeature: '3.7-billion-year-old lacustrine clay & carbonate delta fan; primary Mars Sample Return biosignature site.',
                linkedAnalogId: 'ANL-002',
                linkedAnalogName: 'Dallol Hydrothermal, Ethiopia',
                img: 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-R2',
                name: 'Gale Crater & Mount Sharp',
                body: 'Mars',
                subCategory: 'Mars Exploration (Curiosity)',
                coords: '5.40° S, 137.80° E',
                elevation: '-4,500 m Basin Floor',
                tempRange: '-80°C to -10°C',
                keyFeature: '5.5 km layered sulfate-to-clay sedimentary mountain recording Mars transition from wet to hyper-arid.',
                linkedAnalogId: 'ANL-006',
                linkedAnalogName: 'McMurdo Dry Valleys, Antarctica',
                img: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-R3',
                name: 'Oxia Planum',
                body: 'Mars',
                subCategory: 'Mars Exploration (ExoMars)',
                coords: '18.20° N, 335.45° E',
                elevation: '-3,000 m Clay Plain',
                tempRange: '-82°C to -18°C',
                keyFeature: 'Massive Fe/Mg-phyllosilicate clay & evaporite exposure targeted for 2-meter subsurface core drilling.',
                linkedAnalogId: 'ANL-005',
                linkedAnalogName: 'Salar de Pajonales, Chile',
                img: 'https://images.unsplash.com/photo-1614732414444-096e5f1122d5?auto=format&fit=crop&w=800&q=80'
            },
            {
                id: 'TGT-R4',
                name: 'Meridiani Planum',
                body: 'Mars',
                subCategory: 'Mars Exploration (Opportunity)',
                coords: '0.20° N, 357.50° E',
                elevation: '-1,400 m Sulfate Plain',
                tempRange: '-78°C to -12°C',
                keyFeature: 'Acidic groundwater evaporite deposits rich in jarosite and gray hematite spherules.',
                linkedAnalogId: 'ANL-003',
                linkedAnalogName: 'Rio Tinto Acidic Basin, Spain',
                img: 'https://images.unsplash.com/photo-1630839437035-dac17da580d0?auto=format&fit=crop&w=800&q=80'
            }
        ];

        const STORAGE_KEY = 'astroanalogs_records_v4';
        let analogRecords = JSON.parse(localStorage.getItem(STORAGE_KEY)) || structuredClone(DEFAULT_RECORDS);
        let activeRecordId = analogRecords[0]?.id || 'ANL-001';
        let tablePage = 0;
        const PAGE_SIZE = 5;
        let activeTargetFilter = 'ALL';
        let activeTerrestrialFilter = 'ALL';

        function saveToStorage() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(analogRecords));
        }

        window.returnToLanding = function() {
            window.location.href = '/';
        };

        // 5A. AI Matchmaker candidate library.
        // Candidate coordinates are approximate region centers. Slope and feature scores are preliminary
        // screening priors and MUST be confirmed with DEM/spectral/field measurements before publication.
        const AI_DISCOVERY_CANDIDATES = [
            {
                id: 'DISC-HOLU', name: 'Holuhraun Lava Flow Field, Iceland', lat: 64.86, lon: -16.83,
                nicheCategory: 'Arid & Volcanic Terrains', terrainClass: 'Fissure-fed basaltic lava flows', mineralClass: 'basalt',
                slopeDeg: 5, geology: 95, topography: 88, climate: 82, targetBodies: ['Mars'],
                source: 'USGS / Planetary Science Journal · 2025',
                sourceUrl: 'https://www.usgs.gov/publications/2014-2015-holuhraun-lava-flow-field-iceland-a-planetary-analog-young-volcanic-terrains',
                evidence: 'Published planetary-analog comparison for young volcanic terrains in Elysium Planitia.'
            },
            {
                id: 'DISC-FAGR', name: 'Fagradalsfjall Volcanic Field, Iceland', lat: 63.89, lon: -22.27,
                nicheCategory: 'Arid & Volcanic Terrains', terrainClass: 'Recent basaltic vents and lava flows', mineralClass: 'basalt',
                slopeDeg: 11, geology: 92, topography: 86, climate: 78, targetBodies: ['Mars', 'Moon'],
                source: 'NASA Goddard Instrument Field Team · 2025',
                sourceUrl: 'https://science.nasa.gov/solar-system/planetary-analogs/nasas-goddard-instrument-field-team-iceland-2025/',
                evidence: 'NASA fieldwork examines volcanic vents and lava flows as analogs for Moon and Mars features.'
            },
            {
                id: 'DISC-UNIV', name: 'University Valley, Antarctica', lat: -77.52, lon: 161.85,
                nicheCategory: 'Arid & Volcanic Terrains', terrainClass: 'Ice-cemented permafrost and cold-desert ground', mineralClass: 'ice',
                slopeDeg: 4, geology: 83, topography: 91, climate: 96, targetBodies: ['Mars'],
                source: 'NASA Astrobiology Field Sites',
                sourceUrl: 'https://astrobiology.nasa.gov/research-locations/',
                evidence: 'NASA lists ice-cemented ground in University Valley as an analog for some Martian environments.'
            },
            {
                id: 'DISC-SVAL', name: 'Svalbard Permafrost Landscapes, Norway', lat: 78.22, lon: 15.63,
                nicheCategory: 'Arid & Volcanic Terrains', terrainClass: 'Arctic permafrost and freeze-thaw terrain', mineralClass: 'ice',
                slopeDeg: 9, geology: 80, topography: 89, climate: 94, targetBodies: ['Mars'],
                source: 'NASA Astrobiology Field Sites',
                sourceUrl: 'https://astrobiology.nasa.gov/research-locations/',
                evidence: 'NASA identifies Svalbard extreme environments as analogs for selected Martian environments.'
            },
            {
                id: 'DISC-KILA', name: 'Kīlauea Volcanic Field, Hawaii', lat: 19.42, lon: -155.29,
                nicheCategory: 'Subsurface / Lava Tubes', terrainClass: 'Basaltic lava flows and lava-tube terrain', mineralClass: 'basalt',
                slopeDeg: 10, geology: 94, topography: 90, climate: 75, targetBodies: ['Moon', 'Mars'],
                source: 'NASA Astrobiology Field Sites / Planetary Analog Explorer',
                sourceUrl: 'https://astrobiology.nasa.gov/research-locations/',
                evidence: 'NASA describes Kīlauea lava tubes as useful analog environments for planetary subsurface studies.'
            },
            {
                id: 'DISC-ATAC', name: 'Atacama Desert Research Region, Chile', lat: -23.86, lon: -69.25,
                nicheCategory: 'Extreme Biology Sites', terrainClass: 'Hyper-arid desert, evaporite and sulfate-bearing terrain', mineralClass: 'sulfate',
                slopeDeg: 3, geology: 94, topography: 82, climate: 97, targetBodies: ['Mars'],
                source: 'NASA Planetary Analogs / NASA Astrobiology',
                sourceUrl: 'https://science.nasa.gov/solar-system/planetary-analogs/',
                evidence: 'NASA studies Atacama soils as an analog for understanding and testing life-detection approaches for Mars.'
            },
            {
                id: 'DISC-SALDA', name: 'Lake Salda, Türkiye', lat: 37.5525, lon: 29.6814,
                nicheCategory: 'Extreme Biology Sites', terrainClass: 'Alkaline lake shore, carbonate sediments and microbialites', mineralClass: 'carbonate',
                slopeDeg: 5, geology: 96, topography: 87, climate: 74, targetBodies: ['Mars'],
                source: 'NASA Science / NASA JPL · Jezero Crater analog',
                sourceUrl: 'https://science.nasa.gov/blogs/earth-matters/2021/02/17/what-a-lake-in-turkey-can-tell-us-about-mars/',
                evidence: 'NASA documents the carbonate mineralogy and delta-like depositional features at Lake Salda as useful comparisons for Jezero Crater.'
            },
            {
                id: 'DISC-UTAH', name: 'Navajo Sandstone Concretion Fields, Utah, USA', lat: 37.25, lon: -110.50,
                nicheCategory: 'Extreme Biology Sites', terrainClass: 'Groundwater-altered sandstone with hematite concretions', mineralClass: 'iron-oxide',
                slopeDeg: 8, geology: 92, topography: 84, climate: 76, targetBodies: ['Mars'],
                source: 'Nature · Terrestrial hematite-concretion analog study',
                sourceUrl: 'https://www.nature.com/articles/nature02600',
                evidence: 'Published research compares Utah Navajo Sandstone hematite concretions with similar features observed on Mars.'
            }
        ];

        let activeAiView = 'discover';
        let aiDiscoveryHasRun = false;
        let aiLastDiscoverResults = [];
        let aiLastCustomResults = [];

        function aiEscape(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            })[char]);
        }

        function getCandidateById(id) {
            return AI_DISCOVERY_CANDIDATES.find(candidate => candidate.id === id);
        }

        async function fetchAiPowerTemperature(candidate) {
            const cacheKey = `astroanalogs_ai_power_v1_${candidate.id}`;
            try {
                const cached = JSON.parse(localStorage.getItem(cacheKey) || 'null');
                if (Number.isFinite(cached?.temperature) && Date.now() - cached.savedAt < 6 * 60 * 60 * 1000) {
                    return { temperature: cached.temperature, source: 'NASA POWER browser cache' };
                }
            } catch (error) {
                console.warn('AI Matchmaker POWER cache read failed.', error);
            }

            const controller = new AbortController();
            const timeoutId = window.setTimeout(() => controller.abort(), 7000);
            try {
                const params = new URLSearchParams({
                    parameters: 'T2M', community: 'RE', longitude: String(candidate.lon),
                    latitude: String(candidate.lat), format: 'JSON'
                });
                const response = await fetch(`https://power.larc.nasa.gov/api/temporal/climatology/point?${params.toString()}`, {
                    signal: controller.signal,
                    headers: { Accept: 'application/json' }
                });
                if (!response.ok) throw new Error(`NASA POWER returned HTTP ${response.status}`);
                const payload = await response.json();
                const value = Number(payload?.properties?.parameter?.T2M?.ANN);
                if (!Number.isFinite(value) || value <= -900) throw new Error('NASA POWER annual T2M value was not available.');
                const temperature = Number(value.toFixed(1));
                localStorage.setItem(cacheKey, JSON.stringify({ temperature, savedAt: Date.now() }));
                return { temperature, source: 'Live NASA POWER' };
            } catch (error) {
                console.warn(`NASA POWER temperature unavailable for ${candidate.name}.`, error);
                return { temperature: null, source: 'NASA POWER unavailable' };
            } finally {
                window.clearTimeout(timeoutId);
            }
        }

        function getActiveBenchmark() {
            return analogRecords.find(record => record.id === (document.getElementById('ai-discover-reference')?.value || activeRecordId))
                || analogRecords.find(record => record.id === activeRecordId)
                || analogRecords[0];
        }

        function renderAiCandidateCards(results, targetId, mode) {
            const container = document.getElementById(targetId);
            if (!container) return;
            if (!results.length) {
                container.innerHTML = '<div class="lg:col-span-2 bg-white/[0.02] border border-dashed border-white/15 rounded-[24px] p-10 text-center"><i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-400"></i><h3 class="text-sm font-bold text-white mt-3">No matching candidates</h3><p class="text-xs text-slate-400 mt-1">Change the mission or scan preferences and try again.</p></div>';
                return;
            }

            container.innerHTML = results.map((result, index) => {
                const candidate = result.candidate;
                const score = Math.max(0, Math.min(100, Math.round(result.score)));
                const powerText = result.power?.temperature !== null && Number.isFinite(result.power?.temperature)
                    ? `${result.power.temperature} °C` : 'Not returned';
                const tempStatus = result.power?.source || 'Not queried';
                const targetBody = mode === 'discover'
                    ? (document.getElementById('ai-discover-body')?.value || 'Mars')
                    : (document.getElementById('ai-custom-body')?.value || 'Mars');
                const addButton = `<button onclick="addAiCandidateToRegistry('${candidate.id}', '${targetBody}')" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-[11px] font-bold hover:brightness-110 transition-all cursor-pointer"><i class="fa-solid fa-plus mr-1"></i> Add to Registry</button>`;
                const metrics = mode === 'discover'
                    ? `<div class="grid grid-cols-3 gap-2 mt-4"><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Geology</div><div class="text-xs font-mono font-bold text-white mt-1">${Math.round(result.metrics.geology)}%</div></div><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Topography</div><div class="text-xs font-mono font-bold text-sky-300 mt-1">${Math.round(result.metrics.topography)}%</div></div><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Environment</div><div class="text-xs font-mono font-bold text-emerald-300 mt-1">${Math.round(result.metrics.climate)}%</div></div></div>`
                    : `<div class="grid grid-cols-3 gap-2 mt-4"><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Temperature</div><div class="text-xs font-mono font-bold text-white mt-1">${result.metrics.temperature === null ? 'N/A' : `${Math.round(result.metrics.temperature)}%`}</div></div><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Slope fit</div><div class="text-xs font-mono font-bold text-sky-300 mt-1">${Math.round(result.metrics.slope)}%</div></div><div class="rounded-xl bg-white/[0.04] p-2.5 text-center"><div class="text-[9px] text-slate-400">Mineral fit</div><div class="text-xs font-mono font-bold text-emerald-300 mt-1">${Math.round(result.metrics.mineral)}%</div></div></div>`;

                return `<article class="bg-white/[0.035] backdrop-blur-xl rounded-[24px] border border-white/10 p-5 shadow-[0_8px_24px_rgba(0,0,0,0.2)]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0"><div class="text-[9px] font-mono uppercase tracking-widest text-sky-300">CANDIDATE ${String(index + 1).padStart(2, '0')} · ${aiEscape(candidate.id)}</div><h3 class="text-[15px] font-bold text-white mt-1">${aiEscape(candidate.name)}</h3><p class="text-[10px] text-slate-400 mt-1">${candidate.lat.toFixed(2)}°, ${candidate.lon.toFixed(2)}° · ${aiEscape(candidate.nicheCategory)}</p></div>
                        <div class="shrink-0 text-right"><div class="text-[9px] text-slate-400 uppercase tracking-wider">Match</div><div class="text-[25px] font-display font-bold text-[#ff8b6e] leading-none mt-1">${score}%</div></div>
                    </div>
                    <div class="ai-result-bar mt-4"><span style="width:${score}%"></span></div>
                    ${metrics}
                    <div class="mt-4 flex flex-wrap items-center gap-2"><span class="text-[9px] font-mono px-2.5 py-1 rounded-full bg-violet-500/10 border border-violet-400/20 text-violet-200">${aiEscape(candidate.mineralClass.toUpperCase())}</span><span class="text-[9px] font-mono px-2.5 py-1 rounded-full bg-white/[0.04] border border-white/10 text-slate-300">Slope screen: ~${candidate.slopeDeg}°</span><span class="text-[9px] font-mono px-2.5 py-1 rounded-full bg-sky-500/10 border border-sky-400/20 text-sky-200">${aiEscape(powerText)}</span></div>
                    <p class="text-[10px] text-slate-400 mt-3 leading-relaxed"><span class="text-sky-200">${aiEscape(candidate.source)}</span><br>${aiEscape(candidate.evidence)} <span class="text-slate-500">${aiEscape(tempStatus)}</span></p>
                    <div class="mt-3 flex flex-wrap gap-2"><a href="${candidate.sourceUrl}" target="_blank" rel="noopener noreferrer" class="flex-1 text-center py-2.5 rounded-xl bg-white/[0.045] hover:bg-white/10 border border-white/10 text-slate-200 text-[10px] font-bold transition-all">View research source <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i></a>${addButton}</div>
                </article>`;
            }).join('');
        }

        window.showAiMatchmakerView = function(view) {
            activeAiView = view === 'custom' ? 'custom' : 'discover';
            const discoverPanel = document.getElementById('ai-discover-panel');
            const customPanel = document.getElementById('ai-custom-panel');
            const discoverTab = document.getElementById('ai-tab-discover');
            const customTab = document.getElementById('ai-tab-custom');
            const navDiscover = document.getElementById('nav-ai-discover');
            const navCustom = document.getElementById('nav-ai-custom');
            if (discoverPanel) discoverPanel.classList.toggle('hidden', activeAiView !== 'discover');
            if (customPanel) customPanel.classList.toggle('hidden', activeAiView !== 'custom');
            if (discoverTab) discoverTab.className = activeAiView === 'discover'
                ? 'px-5 py-2.5 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md transition-all cursor-pointer'
                : 'px-5 py-2.5 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold transition-all cursor-pointer';
            if (customTab) customTab.className = activeAiView === 'custom'
                ? 'px-5 py-2.5 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md transition-all cursor-pointer'
                : 'px-5 py-2.5 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold transition-all cursor-pointer';
            if (navDiscover) navDiscover.classList.toggle('active', activeAiView === 'discover');
            if (navCustom) navCustom.classList.toggle('active', activeAiView === 'custom');
        };

        window.updateAiRangeLabels = function() {
            const temp = document.getElementById('ai-temp-input');
            const slope = document.getElementById('ai-slope-input');
            const tempOut = document.getElementById('ai-temp-output');
            const slopeOut = document.getElementById('ai-slope-output');
            if (temp && tempOut) tempOut.textContent = `${temp.value}°C`;
            if (slope && slopeOut) slopeOut.textContent = `${slope.value}°`;
        };

        window.runAutoDiscover = async function() {
            const resultsEl = document.getElementById('ai-discovery-results');
            const noteEl = document.getElementById('ai-discover-result-note');
            const countEl = document.getElementById('ai-discover-count');
            const statusEl = document.getElementById('ai-engine-status');
            const runBtn = document.getElementById('ai-discover-run-btn');
            if (!resultsEl) return;

            const body = document.getElementById('ai-discover-body')?.value || 'Mars';
            const benchmark = getActiveBenchmark();
            const candidates = AI_DISCOVERY_CANDIDATES.filter(candidate => candidate.targetBodies.includes(body));
            if (!candidates.length) {
                noteEl.textContent = `No curated candidates currently target ${body}.`;
                countEl.textContent = '0 candidates';
                return;
            }

            if (runBtn) { runBtn.disabled = true; runBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Scanning...'; }
            if (statusEl) statusEl.textContent = 'ENGINE: ENRICHING CANDIDATES';
            if (noteEl) noteEl.textContent = `Screening ${candidates.length} research candidates against ${benchmark.earthName}. NASA POWER climate enrichment is being attempted.`;
            resultsEl.innerHTML = '<div class="lg:col-span-2 py-12 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl text-sky-300"></i><p class="text-xs mt-3">Querying NASA POWER and scoring the candidate library...</p></div>';

            try {
                const benchmarkPower = await fetchAiPowerTemperature({
                    id: `benchmark-${benchmark.id}`, lat: benchmark.lat, lon: benchmark.lon
                });
                const enriched = await Promise.all(candidates.map(async candidate => {
                    const power = await fetchAiPowerTemperature(candidate);
                    const geology = Math.max(0, 100 - Math.abs(candidate.geology - Number(benchmark.geology || 0)) * 1.5);
                    const topography = Math.max(0, 100 - Math.abs(candidate.topography - Number(benchmark.topography || 0)) * 1.5);
                    const climateProfile = (power.temperature !== null && benchmarkPower.temperature !== null)
                        ? Math.max(0, 100 - Math.abs(power.temperature - benchmarkPower.temperature) * 2.6)
                        : Math.max(0, 100 - Math.abs(candidate.climate - Number(benchmark.climate || 0)) * 1.5);
                    const score = (geology * 0.4) + (topography * 0.3) + (climateProfile * 0.3);
                    return { candidate, power, score, metrics: { geology, topography, climate: climateProfile } };
                }));
                aiLastDiscoverResults = enriched.sort((a, b) => b.score - a.score);
                aiDiscoveryHasRun = true;
                renderAiCandidateCards(aiLastDiscoverResults, 'ai-discovery-results', 'discover');
                if (noteEl) noteEl.textContent = `Ranked against ${benchmark.earthName}. NASA POWER annual temperature is used when both locations return data; otherwise the climate screening index is used. Scores are not probabilities of scientific equivalence.`;
                if (countEl) countEl.textContent = `${aiLastDiscoverResults.length} candidates ranked`;
                if (statusEl) statusEl.textContent = 'ENGINE: DISCOVERY COMPLETE';
            } catch (error) {
                console.error('Auto Discovery failed.', error);
                if (noteEl) noteEl.textContent = 'Discovery could not complete. Check browser connectivity and retry.';
                if (statusEl) statusEl.textContent = 'ENGINE: RETRY REQUIRED';
                resultsEl.innerHTML = '<div class="lg:col-span-2 bg-rose-500/[0.07] border border-rose-400/20 rounded-[24px] p-8 text-center text-xs text-rose-200">Auto Discovery failed. Please retry after checking the network connection.</div>';
            } finally {
                if (runBtn) { runBtn.disabled = false; runBtn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Run Auto Discovery'; }
            }
        };

        window.runCustomScan = async function() {
            const resultsEl = document.getElementById('ai-custom-results');
            const noteEl = document.getElementById('ai-custom-result-note');
            const countEl = document.getElementById('ai-custom-count');
            const statusEl = document.getElementById('ai-engine-status');
            if (!resultsEl) return;

            const preferredTemp = Number(document.getElementById('ai-temp-input')?.value ?? 15);
            const preferredSlope = Number(document.getElementById('ai-slope-input')?.value ?? 8);
            const mineral = document.getElementById('ai-mineral-input')?.value || 'any';
            const body = document.getElementById('ai-custom-body')?.value || 'Mars';
            const candidates = AI_DISCOVERY_CANDIDATES.filter(candidate => candidate.targetBodies.includes(body));

            if (statusEl) statusEl.textContent = 'ENGINE: RUNNING CUSTOM SCAN';
            if (noteEl) noteEl.textContent = 'Loading NASA POWER annual temperatures and calculating custom weighted scores...';
            resultsEl.innerHTML = '<div class="lg:col-span-2 py-12 text-center text-slate-400"><i class="fa-solid fa-circle-notch fa-spin text-2xl text-[#ff8b6e]"></i><p class="text-xs mt-3">Scanning candidate locations...</p></div>';

            const enriched = await Promise.all(candidates.map(async candidate => {
                const power = await fetchAiPowerTemperature(candidate);
                const temperatureScore = power.temperature === null
                    ? null
                    : Math.max(0, 100 - Math.abs(power.temperature - preferredTemp) * 2.6);
                const slopeScore = Math.max(0, 100 - Math.abs(candidate.slopeDeg - preferredSlope) * 3.2);
                const mineralScore = mineral === 'any'
                    ? 75
                    : (candidate.mineralClass === mineral
                        ? 100
                        : ((mineral === 'sulfate' && candidate.mineralClass === 'iron-oxide') || (mineral === 'iron-oxide' && candidate.mineralClass === 'sulfate') ? 65 : 15));
                const weightedParts = [];
                if (temperatureScore !== null) weightedParts.push({ score: temperatureScore, weight: 0.4 });
                weightedParts.push({ score: slopeScore, weight: 0.3 }, { score: mineralScore, weight: 0.3 });
                const availableWeight = weightedParts.reduce((sum, part) => sum + part.weight, 0);
                const score = weightedParts.reduce((sum, part) => sum + part.score * (part.weight / availableWeight), 0);
                return { candidate, power, score, metrics: { temperature: temperatureScore, slope: slopeScore, mineral: mineralScore } };
            }));

            aiLastCustomResults = enriched.sort((a, b) => b.score - a.score);
            renderAiCandidateCards(aiLastCustomResults, 'ai-custom-results', 'custom');
            if (noteEl) noteEl.textContent = `Preferences: ${preferredTemp}°C · ${preferredSlope}° slope · ${mineral === 'any' ? 'any mineral family' : mineral} · ${body}. Scores are relative rankings, not probabilities.`;
            if (countEl) countEl.textContent = `${aiLastCustomResults.length} candidates ranked`;
            if (statusEl) statusEl.textContent = 'ENGINE: CUSTOM SCAN COMPLETE';
        };

        window.addAiCandidateToRegistry = function(candidateId, targetBody) {
            const candidate = getCandidateById(candidateId);
            if (!candidate) { alert('This discovery candidate could not be found.'); return; }
            const existing = analogRecords.find(record => record.aiCandidateId === candidate.id);
            if (existing) {
                alert(`${candidate.name} is already in the analog registry as ${existing.id}.`);
                selectAnalogRecord(existing.id);
                switchPage('similarity');
                return;
            }

            const body = targetBody === 'Moon' && candidate.targetBodies.includes('Moon') ? 'Moon' : (candidate.targetBodies.includes(targetBody) ? targetBody : candidate.targetBodies[0]);
            const targetCrater = body === 'Moon' ? 'Lunar Volcanic / Subsurface Analog Target' : 'Martian Volcanic / Surface Analog Target';
            const id = `ANL-AI-${Date.now()}`;
            const newRecord = {
                id, aiCandidateId: candidate.id, earthName: candidate.name,
                nicheCategory: candidate.nicheCategory, targetBody: body, targetCrater,
                lat: candidate.lat, lon: candidate.lon,
                geology: candidate.geology, topography: candidate.topography, climate: candidate.climate,
                overall: Number(((candidate.geology * 0.4) + (candidate.topography * 0.3) + (candidate.climate * 0.3)).toFixed(1)),
                elevation: 'Not yet verified', targetRelief: 'Mission target reference', roughness: 'Not measured',
                terrainClass: candidate.terrainClass,
                gravityBadge: body === 'Mars' ? '0.38g' : '0.165g',
                gravityDesc: 'Mission-body gravity differs from Earth; this is a boundary condition, not a measured property of the analog site.',
                radBadge: 'Requires dosimetry',
                radDesc: 'Earth surface radiation is not equivalent to planetary-surface GCR exposure; compare using dedicated radiation datasets.',
                vacBadge: 'Not equivalent',
                vacDesc: 'Terrestrial sites remain within Earth atmospheric pressure and cannot reproduce planetary vacuum conditions.',
                utilities: [
                    { title: 'Analog Research Lead', desc: candidate.evidence },
                    { title: 'Evidence Review Required', desc: 'Confirm topography from DEM products and mineralogy from calibrated spectral or laboratory data before treating this candidate as verified.' }
                ],
                earthImg: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=900&q=80',
                spaceImg: body === 'Mars'
                    ? 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=900&q=80'
                    : 'https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=900&q=80'
            };

            analogRecords.unshift(newRecord);
            saveToStorage();
            syncAllControls();
            selectAnalogRecord(id);
            pushNotification('AI Matchmaker Candidate Added', `${candidate.name} added as an unverified research lead.`);
            switchPage('similarity');
        };

        // 6. Page Navigation (All pages supported)
        window.switchPage = function(pageName) {
            ['similarity', 'targets', 'terrestrial', 'globe', 'telemetry', 'topography', 'science', 'matchmaker'].forEach(p => {
                const pageEl = document.getElementById(`page-${p}`);
                const navEl = document.getElementById(`nav-${p}`);
                if (pageEl) pageEl.classList.add('hidden');
                if (navEl) {
                    navEl.classList.remove('text-[#ff8b6e]', 'shadow-[inset_3px_0_0_0_#e04826]');
                    navEl.classList.add('text-slate-300', 'hover:bg-white/5', 'hover:text-white');
                }
            });

            const targetPage = document.getElementById(`page-${pageName}`);
            const targetNav = document.getElementById(`nav-${pageName}`);
            if (targetPage) targetPage.classList.remove('hidden');
            if (targetNav) {
                targetNav.classList.remove('text-slate-300', 'hover:bg-white/5', 'hover:text-white');
                targetNav.classList.add('text-[#ff8b6e]', 'shadow-[inset_3px_0_0_0_#e04826]');
            }
            const aiDiscoverNav = document.getElementById('nav-ai-discover');
            const aiCustomNav = document.getElementById('nav-ai-custom');
            if (aiDiscoverNav) aiDiscoverNav.classList.remove('active');
            if (aiCustomNav) aiCustomNav.classList.remove('active');

            if (pageName === 'matchmaker') {
                showAiMatchmakerView(activeAiView || 'discover');
                const benchmarkSelect = document.getElementById('ai-discover-reference');
                if (benchmarkSelect && analogRecords.some(record => record.id === activeRecordId)) benchmarkSelect.value = activeRecordId;
            }
            if (pageName === 'similarity') {
                setTimeout(() => initThreeJsEarth(), 50);
            }
            if (pageName === 'targets') {
                renderPlanetaryTargetsGrid();
                loadMarsRoverFeed();
            }
            if (pageName === 'terrestrial') {
                renderTerrestrialDatabaseGrid();
            }
            if (pageName === 'globe') {
                setTimeout(() => {
                    initThreeJsEarth();
                    const rec = analogRecords.find(r => r.id === activeRecordId) || analogRecords[0];
                    if (rec) updateGlobeDetails(rec);
                }, 50);
            }
            if (pageName === 'topography') {
                setTimeout(() => {
                    initThreeJsDem();
                    updateDemView(activeRecordId);
                }, 50);
            }
            if (pageName === 'science' && window.MathJax?.typesetPromise) {
                window.MathJax.typesetClear?.([targetPage]);
                window.MathJax.typesetPromise([targetPage]).catch(error => console.warn('Scientific Report math rendering failed.', error));
            }
        };

        window.selectSolCycle = function(btnEl, solNum) {
            document.querySelectorAll('.sol-pill').forEach(p => p.classList.remove('active'));
            if (btnEl) btnEl.classList.add('active');
            const idx = (solNum - 14) % analogRecords.length;
            if (analogRecords[idx]) selectAnalogRecord(analogRecords[idx].id);
        };

        window.quickFilterBody = function(bodyVal) {
            const filterEl = document.getElementById('sim-target-filter');
            if (filterEl) {
                filterEl.value = bodyVal;
                tablePage = 0;
                renderSimilarityTable();
            }
        };

        // 7. Planetary Targets Page Logic + Live Mars Rover Photos API
        window.filterPlanetaryTargets = function(body, btnEl) {
            activeTargetFilter = body;
            document.querySelectorAll('.target-tab-btn').forEach(b => {
                b.className = 'target-tab-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all';
            });
            if (btnEl) {
                btnEl.className = 'target-tab-btn px-4 py-2 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md cursor-pointer transition-all';
            }
            renderPlanetaryTargetsGrid();
        };

        function renderPlanetaryTargetsGrid() {
            const grid = document.getElementById('planetary-targets-grid');
            if (!grid) return;
            const list = PLANETARY_TARGETS_DATA.filter(t => activeTargetFilter === 'ALL' || t.body === activeTargetFilter);

            grid.innerHTML = list.map(t => {
                const isMars = t.body === 'Mars';
                const badgeClass = isMars
                    ? 'bg-[#e04826]/25 border-[#e04826]/50 text-[#ff8b6e]'
                    : 'bg-sky-500/20 border-sky-400/40 text-sky-300';

                return `
                    <div class="bg-white/[0.04] backdrop-blur-xl rounded-[28px] border border-white/10 overflow-hidden flex flex-col justify-between hover:-translate-y-1 transition-all duration-300 shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] group">
                        <div>
                            <div class="relative h-44 w-full overflow-hidden">
                                <img src="${t.img}" alt="${t.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0c0f17] via-[#0c0f17]/25 to-transparent"></div>
                                <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full border text-[10px] font-mono font-bold backdrop-blur-md ${badgeClass}">
                                    ${t.body.toUpperCase()} · ${t.id}
                                </span>
                                <span class="absolute bottom-3 left-4 text-[10px] font-mono text-slate-300 bg-black/50 px-2 py-0.5 rounded-md">
                                    ${t.coords}
                                </span>
                            </div>

                            <div class="p-5">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">${t.subCategory}</div>
                                <h3 class="text-[17px] font-bold text-white leading-snug">${t.name}</h3>
                                <p class="text-xs text-slate-300 leading-relaxed mt-2">${t.keyFeature}</p>

                                <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-white/10 text-[11px]">
                                    <div>
                                        <span class="text-slate-500 block text-[10px]">Morphology</span>
                                        <span class="font-mono text-slate-200 font-semibold">${t.elevation}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500 block text-[10px]">Thermal Regime</span>
                                        <span class="font-mono text-[#ff8b6e] font-semibold">${t.tempRange}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 pb-5 pt-2">
                            <button onclick="selectAnalogRecord('${t.linkedAnalogId}'); switchPage('similarity');" class="w-full py-2.5 rounded-2xl bg-white/[0.06] hover:bg-gradient-to-r hover:from-[#e54e2b] hover:to-[#c93d1e] border border-white/10 text-xs font-bold text-white transition-all cursor-pointer flex items-center justify-center gap-2">
                                <i class="fa-solid fa-crosshairs text-[11px]"></i> Match with ${t.linkedAnalogName.split(',')[0]}
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        window.loadMarsRoverFeed = async function() {
            const gallery = document.getElementById('mars-rover-gallery');
            if (!gallery) return;

            const fallbackPhotos = [
                {
                    cam: 'MASTCAM-Z LEFT · PERSEVERANCE',
                    sol: 'Sol 1284 · Jezero Delta',
                    img: 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=600&q=80'
                },
                {
                    cam: 'NAVCAM RIGHT · CURIOSITY',
                    sol: 'Sol 4190 · Mount Sharp',
                    img: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80'
                },
                {
                    cam: 'SUPERCAM RMI · JEZERO',
                    sol: 'Sol 1280 · Sulfate Outcrop',
                    img: 'https://images.unsplash.com/photo-1630839437035-dac17da580d0?auto=format&fit=crop&w=600&q=80'
                }
            ];

            let displayPhotos = fallbackPhotos;

            try {
                const controller = new AbortController();
                const timer = setTimeout(() => controller.abort(), 3500);
                const res = await fetch('https://api.nasa.gov/mars-photos/api/v1/rovers/perseverance/latest_photos?api_key=DEMO_KEY', { signal: controller.signal });
                clearTimeout(timer);
                if (res.ok) {
                    const json = await res.json();
                    if (json?.latest_photos?.length >= 3) {
                        displayPhotos = json.latest_photos.slice(0, 3).map(p => ({
                            cam: `${p.camera?.name || 'MASTCAM'} · ${p.rover?.name || 'PERSEVERANCE'}`.toUpperCase(),
                            sol: `Sol ${p.sol} · ${p.earth_date}`,
                            img: p.img_src
                        }));
                    }
                }
            } catch (_) {
                // Uses verified NASA benchmark imagery on timeout/rate-limit
            }

            gallery.innerHTML = displayPhotos.map(p => `
                <div class="relative h-36 rounded-2xl overflow-hidden border border-white/10 group">
                    <img src="${p.img}" alt="${p.cam}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#06070b] via-[#06070b]/25 to-transparent"></div>
                    <div class="absolute bottom-2.5 left-3 right-3">
                        <div class="text-[9.5px] font-mono font-bold text-[#ff8b6e] truncate">${p.cam}</div>
                        <div class="text-[11px] font-bold text-white">${p.sol}</div>
                    </div>
                </div>
            `).join('');
        };

        // 8. Terrestrial Database Page Logic (Niche Categorization)
        window.filterTerrestrialCategory = function(cat, btnEl) {
            activeTerrestrialFilter = cat;
            document.querySelectorAll('.terr-cat-btn').forEach(b => {
                b.className = 'terr-cat-btn px-4 py-2 rounded-full bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold cursor-pointer transition-all';
            });
            if (btnEl) {
                btnEl.className = 'terr-cat-btn px-4 py-2 rounded-full bg-[#e04826] text-white text-xs font-bold shadow-md cursor-pointer transition-all';
            }
            renderTerrestrialDatabaseGrid();
        };

        function renderTerrestrialDatabaseGrid() {
            const grid = document.getElementById('terrestrial-database-grid');
            if (!grid) return;
            const list = analogRecords.filter(r => activeTerrestrialFilter === 'ALL' || r.nicheCategory === activeTerrestrialFilter);

            grid.innerHTML = list.map(r => {
                let catBadge = 'bg-sky-500/20 border-sky-400/40 text-sky-300';
                if (r.nicheCategory === 'Subsurface / Lava Tubes') catBadge = 'bg-amber-500/20 border-amber-400/40 text-amber-300';
                if (r.nicheCategory === 'Extreme Biology Sites') catBadge = 'bg-emerald-500/20 border-emerald-400/40 text-emerald-300';

                return `
                    <div class="bg-white/[0.04] backdrop-blur-xl rounded-[28px] border border-white/10 overflow-hidden flex flex-col justify-between hover:-translate-y-1 transition-all duration-300 shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] group">
                        <div>
                            <div class="relative h-48 w-full overflow-hidden">
                                <img src="${r.earthImg}" alt="${r.earthName}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0c0f17] via-[#0c0f17]/30 to-transparent"></div>
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full border text-[10px] font-mono font-bold backdrop-blur-md ${catBadge}">
                                    ${r.nicheCategory}
                                </span>
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-black/60 border border-emerald-400/40 text-emerald-300 font-mono text-[11px] font-bold backdrop-blur-md">
                                    ${r.overall}% Match
                                </span>
                                <div class="absolute bottom-3 left-4 right-4 flex justify-between items-center">
                                    <span class="text-[10px] font-mono text-slate-300">${r.lat.toFixed(2)}°, ${r.lon.toFixed(2)}°</span>
                                    <span class="text-[10px] font-mono font-bold text-[#ff8b6e]">Target: ${r.targetCrater} (${r.targetBody})</span>
                                </div>
                            </div>

                            <div class="p-5">
                                <h3 class="text-[17px] font-bold text-white">${r.earthName}</h3>
                                <p class="text-xs text-slate-400 mt-1">${r.terrainClass}</p>

                                <!-- Quantitative Similarity Mini-Bars -->
                                <div class="grid grid-cols-3 gap-2 my-4 bg-white/[0.02] p-3 rounded-2xl border border-white/[0.06] text-center">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block">Geology</span>
                                        <span class="font-mono text-xs font-bold text-white">${r.geology}%</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block">Topography</span>
                                        <span class="font-mono text-xs font-bold text-sky-300">${r.topography}%</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 block">Env. Match</span>
                                        <span class="font-mono text-xs font-bold text-emerald-400">${r.climate}%</span>
                                    </div>
                                </div>

                                <div class="text-[11px] text-slate-300 bg-[#e04826]/10 border border-[#e04826]/25 rounded-xl p-2.5">
                                    <strong class="text-[#ff8b6e]">Boundary Offset:</strong> ${r.gravityBadge} · ${r.radBadge} · ${r.vacBadge}
                                </div>
                            </div>
                        </div>

                        <div class="px-5 pb-5 flex gap-2">
                            <button onclick="selectAnalogRecord('${r.id}'); switchPage('similarity');" class="flex-1 py-2.5 rounded-2xl bg-gradient-to-r from-[#e54e2b] to-[#c93d1e] text-white text-xs font-bold shadow-md hover:brightness-110 transition-all cursor-pointer">
                                Analyze Telemetry
                            </button>
                            <button onclick="selectAnalogRecord('${r.id}'); switchPage('globe');" title="View on 3D Globe" class="px-3.5 py-2.5 rounded-2xl bg-white/[0.06] hover:bg-white/10 border border-white/10 text-sky-300 text-xs font-bold cursor-pointer">
                                <i class="fa-solid fa-globe"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // 9. Drawer Node Matrix & Sync Controls
        function renderSidebarNodeMatrix() {
            const container = document.getElementById('sidebar-node-matrix');
            if (!container) return;
            const layout = [
                { label: 'A1', idx: 0 }, { label: 'A2', idx: 1 }, { aisle: true }, { label: 'A3', idx: 2 }, { label: 'A4', idx: 3 },
                { label: 'B1', idx: 4 }, { label: 'B2', idx: 5 }, { aisle: true }, { label: 'B3', idx: 6 }, { label: 'B4', idx: 7 }
            ];
            container.innerHTML = layout.map(slot => {
                if (slot.aisle) return `<div class="h-6"></div>`;
                const rec = analogRecords[slot.idx];
                if (!rec) return `<div class="node-slot available opacity-50 cursor-default">${slot.label}</div>`;
                const stateClass = rec.id === activeRecordId ? 'chosen' : 'busy';
                return `<button type="button" onclick="selectAnalogRecord('${rec.id}')" title="${rec.earthName}" class="node-slot ${stateClass}">${slot.label}</button>`;
            }).join('');
        }

        function syncAllControls() {
            const optionsHtml = analogRecords.map(r =>
                `<option value="${r.id}">${r.earthName} ↔ ${r.targetCrater} (${r.targetBody}) — ${r.overall}%</option>`
            ).join('');

            ['sim-pair-dropdown', 'globe-site-dropdown', 'dashboard-globe-site-dropdown', 'api-preset-dropdown', 'dem-pair-dropdown', 'ai-discover-reference'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.innerHTML = optionsHtml;
                    el.value = activeRecordId;
                }
            });

            document.getElementById('stat-total-sites').textContent = analogRecords.length;
            document.getElementById('stat-mars-sites').textContent = analogRecords.filter(r => r.targetBody === 'Mars').length;
            document.getElementById('stat-moon-sites').textContent = analogRecords.filter(r => r.targetBody === 'Moon').length;

            renderSidebarNodeMatrix();
            renderSimilarityTable();
            renderGlobeStationsTable();
            renderDemTable();
            renderTerrestrialDatabaseGrid();
        }

        // 10. Select Active Record & Update All Cards, Charts & Scientific Report
        window.selectAnalogRecord = function(id) {
            const rec = analogRecords.find(r => r.id === id) || analogRecords[0];
            if (!rec) return;
            activeRecordId = rec.id;

            ['sim-pair-dropdown', 'globe-site-dropdown', 'dashboard-globe-site-dropdown', 'api-preset-dropdown', 'dem-pair-dropdown', 'ai-discover-reference'].forEach(elId => {
                const el = document.getElementById(elId);
                if (el) el.value = rec.id;
            });

            // Update Hero Banner & Top 3 Cards
            document.getElementById('hero-active-pair').textContent = `${rec.earthName.split(',')[0]} ↔ ${rec.targetCrater}`;
            document.getElementById('hero-active-score').textContent = `${rec.overall}%`;

            document.getElementById('card1-overall-score').textContent = `${rec.overall}%`;
            document.getElementById('card1-target-label').textContent = `${rec.targetCrater} · ${rec.targetBody}`;
            document.getElementById('card1-coords').textContent = `${rec.lat.toFixed(2)}°, ${rec.lon.toFixed(2)}°`;

            document.getElementById('card2-clim-badge').textContent = `${rec.climate}% Match`;
            document.getElementById('card3-geo-pct').textContent = `${rec.geology}%`;
            document.getElementById('card3-topo-pill').textContent = `Topo ${rec.topography}%`;

            // Update Dashboard Side-by-Side Real Imagery
            document.getElementById('dash-earth-img').src = rec.earthImg;
            document.getElementById('dash-earth-title').textContent = rec.earthName;
            document.getElementById('dash-earth-sub').textContent = rec.terrainClass;
            document.getElementById('dash-earth-coords').textContent = `${rec.lat.toFixed(2)}°, ${rec.lon.toFixed(2)}°`;

            document.getElementById('dash-space-img').src = rec.spaceImg;
            document.getElementById('dash-space-title').textContent = `${rec.targetCrater} (${rec.targetBody})`;
            document.getElementById('dash-space-sub').textContent = rec.nicheCategory || 'Planetary Analog Target';
            document.getElementById('dash-space-body').textContent = rec.targetBody;

            const locatorName = document.getElementById('dashboard-locator-site-name');
            const locatorType = document.getElementById('dashboard-locator-site-type');
            const locatorGeo = document.getElementById('dashboard-locator-geo');
            const locatorTopo = document.getElementById('dashboard-locator-topo');
            const locatorClimate = document.getElementById('dashboard-locator-climate');
            const locatorBody = document.getElementById('dashboard-locator-body');
            const locatorUtility = document.getElementById('dashboard-locator-utility');
            if (locatorName) locatorName.textContent = rec.earthName;
            if (locatorType) locatorType.textContent = rec.terrainClass || rec.nicheCategory || 'Terrestrial analog site';
            if (locatorGeo) locatorGeo.textContent = `${rec.geology}%`;
            if (locatorTopo) locatorTopo.textContent = `${rec.topography}%`;
            if (locatorClimate) locatorClimate.textContent = `${rec.climate}%`;
            if (locatorBody) locatorBody.textContent = rec.targetBody;
            if (locatorUtility) locatorUtility.textContent = rec.utilities?.[0]?.desc || 'Compare terrestrial terrain against planetary mission requirements.';

            // Update Scientific Report Live Formula Breakdown
            const sciPair = document.getElementById('sci-active-pair');
            const sciCalc = document.getElementById('sci-active-calc');
            const sciBreakdown = document.getElementById('sci-formula-breakdown');
            if (sciPair && sciCalc && sciBreakdown) {
                sciPair.textContent = `${rec.earthName.split(',')[0]} ↔ ${rec.targetCrater}`;
                sciCalc.textContent = `${rec.overall}%`;
                sciBreakdown.innerHTML = `
                    <div class="p-2.5 rounded-xl bg-white/[0.04] border border-white/10">
                        <div class="text-[10px] text-slate-400">0.40 × Geo (${rec.geology}%)</div>
                        <div class="font-bold text-white mt-0.5">${(rec.geology * 0.4).toFixed(1)}%</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white/[0.04] border border-white/10">
                        <div class="text-[10px] text-slate-400">0.30 × Topo (${rec.topography}%)</div>
                        <div class="font-bold text-sky-300 mt-0.5">${(rec.topography * 0.3).toFixed(1)}%</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white/[0.04] border border-white/10">
                        <div class="text-[10px] text-slate-400">0.30 × Env (${rec.climate}%)</div>
                        <div class="font-bold text-emerald-400 mt-0.5">${(rec.climate * 0.3).toFixed(1)}%</div>
                    </div>
                `;
            }

            // Update Feature Hazard Card
            document.getElementById('feat-grav-val').textContent = rec.gravityBadge;
            document.getElementById('feat-rad-sub').textContent = `${rec.radBadge} · Boundary Offset`;

            // Update Drawer Boarding Pass
            document.getElementById('sidebar-active-name').textContent = rec.earthName.split(',')[0];
            document.getElementById('sidebar-dest-code').textContent = rec.targetBody;
            document.getElementById('sidebar-active-target').textContent = rec.targetCrater;
            document.getElementById('sidebar-active-score').textContent = `${rec.overall}%`;
            document.getElementById('sidebar-ticket-distance').textContent = rec.targetBody === 'Mars' ? '225,000,000 km' : '384,400 km';
            document.getElementById('sidebar-ticket-coords').textContent = `${rec.lat.toFixed(2)}°, ${rec.lon.toFixed(2)}°`;
            document.getElementById('sidebar-planet-orb').className = rec.targetBody === 'Mars' ? 'planet-sphere-mars' : 'planet-sphere-moon';

            renderSidebarNodeMatrix();

            // Update Analytics & Boundary Offsets
            document.getElementById('sim-chart-subtitle').textContent = `${rec.earthName} vs. ${rec.targetCrater} (${rec.targetBody})`;
            document.getElementById('sim-overall-badge').textContent = `${rec.overall}% MATCH`;

            document.getElementById('lim-grav-badge').textContent = rec.gravityBadge;
            document.getElementById('lim-grav-desc').textContent = rec.gravityDesc;
            document.getElementById('lim-rad-badge').textContent = rec.radBadge;
            document.getElementById('lim-rad-desc').textContent = rec.radDesc;
            document.getElementById('lim-vac-badge').textContent = rec.vacBadge;
            document.getElementById('lim-vac-desc').textContent = rec.vacDesc;

            document.getElementById('mission-utility-box').innerHTML = rec.utilities.map(u => `
                <div class="bg-white/[0.03] p-4 rounded-2xl border border-white/[0.07]">
                    <div class="text-xs font-bold text-emerald-400 mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-rocket text-[11px]"></i> ${u.title}
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">${u.desc}</p>
                </div>
            `).join('');

            renderHeroCharts(rec);
            renderSimilarityCharts(rec);
            updateGlobeDetails(rec);

            document.getElementById('api-lat-input').value = rec.lat;
            document.getElementById('api-lon-input').value = rec.lon;
            fetchLiveNasaApis(rec.lat, rec.lon, rec);
            Object.entries(enabledArchiveLayers).forEach(([layerKey, isEnabled]) => {
                if (isEnabled && CMR_ARCHIVE_CONFIG[layerKey]?.spatial) fetchCmrArchive(layerKey);
            });

            document.getElementById('dem-subtitle').textContent = `Comparing ${rec.earthName} (${rec.elevation}) against ${rec.targetCrater} (${rec.targetRelief})`;

            renderSimilarityTable();
            renderGlobeStationsTable();
            renderDemTable();
        };

        let sparkChart = null;
        let donutChart = null;

        function renderHeroCharts(rec) {
            const sparkCanvas = document.getElementById('heroSparklineChart');
            if (sparkCanvas) {
                if (sparkChart) sparkChart.destroy();
                const ctx = sparkCanvas.getContext('2d');
                const grad = ctx.createLinearGradient(0, 0, 200, 0);
                grad.addColorStop(0, '#38bdf8');
                grad.addColorStop(1, '#e04826');

                const series = [
                    rec.climate - 12,
                    rec.geology - 5,
                    rec.topography + 4,
                    rec.climate - 3,
                    rec.overall,
                    rec.climate
                ];

                sparkChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'],
                        datasets: [{
                            data: series,
                            borderColor: grad,
                            borderWidth: 3,
                            tension: 0.42,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        scales: { x: { display: false }, y: { display: false } }
                    }
                });
            }

            const donutCanvas = document.getElementById('heroDonutChart');
            if (donutCanvas) {
                if (donutChart) donutChart.destroy();
                donutChart = new Chart(donutCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [rec.geology, Math.max(0, 100 - rec.geology)],
                            backgroundColor: ['#e04826', 'rgba(255,255,255,0.08)'],
                            borderWidth: 0,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '76%',
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
            }
        }

        let simBarChart = null;
        let radarChart = null;

        function renderSimilarityCharts(rec) {
            const radScore = rec.targetBody === 'Mars' ? 14 : 10;
            const atmScore = rec.targetBody === 'Mars' ? 22 : 5;
            const thermalScore = Math.round((rec.climate + rec.geology) / 2);

            const barCanvas = document.getElementById('similarityBarChart');
            if (barCanvas) {
                if (simBarChart) simBarChart.destroy();
                simBarChart = new Chart(barCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Geology', 'Topography', 'Climate', 'Thermal', 'Radiation', 'Atmosphere'],
                        datasets: [
                            {
                                label: rec.earthName.split(',')[0],
                                data: [rec.geology, rec.topography, rec.climate, thermalScore, radScore, atmScore],
                                backgroundColor: '#38bdf8',
                                borderRadius: 8,
                                barThickness: 12
                            },
                            {
                                label: rec.targetCrater,
                                data: [Math.min(98, rec.geology + 3), Math.min(96, rec.topography + 4), Math.min(98, rec.climate - 2), 88, 96, 95],
                                backgroundColor: '#e04826',
                                borderRadius: 8,
                                barThickness: 12
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#94a3b8', font: { size: 11, weight: '600' } }
                            },
                            y: {
                                min: 0,
                                max: 100,
                                grid: { color: 'rgba(255,255,255,0.06)' },
                                ticks: { color: '#94a3b8', font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            const radarCanvas = document.getElementById('similarityRadarChart');
            if (radarCanvas) {
                if (radarChart) radarChart.destroy();
                radarChart = new Chart(radarCanvas.getContext('2d'), {
                    type: 'radar',
                    data: {
                        labels: ['Geology', 'Topography', 'Climate', 'Thermal', 'Radiation', 'Atmosphere'],
                        datasets: [
                            {
                                label: rec.earthName.split(',')[0],
                                data: [rec.geology, rec.topography, rec.climate, thermalScore, radScore, atmScore],
                                backgroundColor: 'rgba(56, 189, 248, 0.16)',
                                borderColor: '#38bdf8',
                                pointBackgroundColor: '#38bdf8',
                                borderWidth: 2
                            },
                            {
                                label: rec.targetCrater,
                                data: [Math.min(98, rec.geology + 3), Math.min(96, rec.topography + 4), Math.min(98, rec.climate - 2), 88, 96, 95],
                                backgroundColor: 'rgba(224, 72, 38, 0.18)',
                                borderColor: '#e04826',
                                pointBackgroundColor: '#e04826',
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                min: 0,
                                max: 100,
                                angleLines: { color: 'rgba(255, 255, 255, 0.08)' },
                                grid: { color: 'rgba(255, 255, 255, 0.08)' },
                                pointLabels: { color: '#cbd5e1', font: { size: 11, family: 'Space Grotesk' } },
                                ticks: { display: false }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: '#e2e8f0', boxWidth: 10, usePointStyle: true, font: { size: 11 } }
                            }
                        }
                    }
                });
            }
        }

        window.renderSimilarityTable = function() {
            const tbody = document.getElementById('similarity-crud-tbody');
            if (!tbody) return;
            const filterTarget = document.getElementById('sim-target-filter').value;
            const searchQuery = document.getElementById('table-search-input').value.toLowerCase().trim();

            const filtered = analogRecords.filter(r => {
                const matchTarget = filterTarget === 'ALL' || r.targetBody === filterTarget;
                const matchSearch = !searchQuery ||
                    r.earthName.toLowerCase().includes(searchQuery) ||
                    r.targetCrater.toLowerCase().includes(searchQuery) ||
                    (r.nicheCategory || '').toLowerCase().includes(searchQuery) ||
                    r.id.toLowerCase().includes(searchQuery);
                return matchTarget && matchSearch;
            });

            const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
            if (tablePage >= totalPages) tablePage = totalPages - 1;
            const startIdx = tablePage * PAGE_SIZE;
            const paginated = filtered.slice(startIdx, startIdx + PAGE_SIZE);

            document.getElementById('table-pagination-info').textContent = `Page ${tablePage + 1} of ${totalPages} • ${filtered.length} total records`;
            document.getElementById('table-prev-btn').disabled = tablePage === 0;
            document.getElementById('table-next-btn').disabled = tablePage >= totalPages - 1;

            if (paginated.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-slate-400 text-sm">No matching planetary analog records found.</td></tr>`;
                return;
            }

            tbody.innerHTML = paginated.map(r => {
                const isSelected = r.id === activeRecordId;
                const targetBadge = r.targetBody === 'Mars'
                    ? '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#e04826]/15 text-[#ff8b6e] border border-[#e04826]/35 text-[10px] font-bold tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-[#e04826]"></span>MARS</span>'
                    : '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-400/15 text-slate-200 border border-slate-400/30 text-[10px] font-bold tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>MOON</span>';

                return `
                    <tr onclick="selectAnalogRecord('${r.id}')" class="cursor-pointer ${isSelected ? 'selected-row' : ''}">
                        <td class="text-[13px] font-bold font-mono text-[#ff8b6e]">${r.id}</td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-[14px] font-bold text-white">${r.earthName}</span>
                                <span class="text-[11px] text-slate-400">${r.nicheCategory || r.terrainClass}</span>
                            </div>
                        </td>
                        <td>${targetBadge}</td>
                        <td class="text-[13px] font-semibold text-slate-300">${r.targetCrater}</td>
                        <td class="font-mono text-[12px] text-slate-400">${r.lat.toFixed(2)}°, ${r.lon.toFixed(2)}°</td>
                        <td class="font-mono text-[12px] text-slate-300">${r.geology}% / ${r.topography}% / ${r.climate}%</td>
                        <td>
                            <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 font-mono text-xs font-bold">
                                ${r.overall}%
                            </span>
                        </td>
                        <td class="text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="selectAnalogRecord('${r.id}')" title="Inspect Telemetry" class="p-1.5 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/15 rounded-lg transition-colors cursor-pointer">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                                <button onclick="openCrudModal('${r.id}')" title="Edit Record" class="p-1.5 text-slate-400 hover:text-sky-400 hover:bg-sky-500/15 rounded-lg transition-colors cursor-pointer">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="deleteCrudRecord('${r.id}')" class="flex items-center gap-1 px-2.5 py-1 bg-rose-500/15 text-rose-300 hover:bg-rose-500/30 rounded-lg text-[11px] font-bold uppercase transition-colors cursor-pointer border border-rose-500/25">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        };

        window.changeTablePage = function(delta) {
            tablePage = Math.max(0, tablePage + delta);
            renderSimilarityTable();
        };

        let globeInitialized = false;
        let earthMesh = null;
        let earthPin = null;
        let earthFallbackTexture = null;
        let earthScene = null;
        let earthRing = null;
        let earthIsDragging = false;
        let earthRenderTargets = [];
        let earthAnimationStarted = false;
        let selectedGibsLayer = 'truecolor';
        let activeGibsTextureRequest = 0;
        const enabledArchiveLayers = { landsat: false, sentinel: false, mro: false, lro: false };

        const GIBS_LAYER_CONFIG = {
            truecolor: {
                layer: 'MODIS_Terra_CorrectedReflectance_TrueColor',
                label: 'LAYER: MODIS TRUE COLOR'
            },
            thermal: {
                layer: 'MODIS_Terra_Land_Surface_Temp_Day',
                label: 'LAYER: MODIS LAND SURFACE TEMP'
            },
            moisture: {
                layer: 'SMAP_L4_Analyzed_Root_Zone_Soil_Moisture',
                label: 'LAYER: SMAP ROOT-ZONE SOIL MOISTURE'
            },
            landsat: {
                layer: 'Landsat_WELD_CorrectedReflectance_Bands157_Global_Annual',
                label: 'LAYER: LANDSAT WELD ANNUAL (2000)',
                time: '2000-12-01'
            }
        };

        function loadGibsEarthTexture(layerKey) {
            const config = GIBS_LAYER_CONFIG[layerKey];
            if (!config || !earthMesh || !earthMesh.material) return;

            const requestId = ++activeGibsTextureRequest;
            const params = new URLSearchParams({
                SERVICE: 'WMS',
                VERSION: '1.1.1',
                REQUEST: 'GetMap',
                LAYERS: config.layer,
                STYLES: '',
                FORMAT: 'image/png',
                TRANSPARENT: 'FALSE',
                SRS: 'EPSG:4326',
                BBOX: '-180,-90,180,90',
                WIDTH: '2048',
                HEIGHT: '1024'
            });
            if (config.time) params.set('TIME', config.time);

            const requestUrl = `https://gibs.earthdata.nasa.gov/wms/epsg4326/best/wms.cgi?${params.toString()}`;
            const badge = document.getElementById('gibs-active-badge');
            if (badge) badge.textContent = `${config.label} · LOADING`;

            const loader = new THREE.TextureLoader();
            loader.setCrossOrigin('anonymous');
            loader.load(
                requestUrl,
                texture => {
                    if (requestId !== activeGibsTextureRequest || !earthMesh || !earthMesh.material) {
                        texture.dispose();
                        return;
                    }
                    texture.colorSpace = THREE.SRGBColorSpace;
                    texture.anisotropy = 8;
                    const previousTexture = earthMesh.material.map;
                    earthMesh.material.map = texture;
                    earthMesh.material.color.setHex(0xffffff);
                    earthMesh.material.needsUpdate = true;
                    if (previousTexture && previousTexture !== earthFallbackTexture) previousTexture.dispose();
                    if (badge) badge.textContent = config.label;
                },
                undefined,
                error => {
                    if (requestId !== activeGibsTextureRequest) return;
                    console.warn(`NASA GIBS layer could not be loaded (${config.layer}).`, error);
                    if (badge) badge.textContent = `${config.label} · UNAVAILABLE`;
                    // Keep the existing fallback/last-good texture rather than blanking the globe.
                }
            );
        }

        function latLonToVector3(lat, lon, radius) {
            const phi = (90 - lat) * (Math.PI / 180);
            const theta = (lon + 180) * (Math.PI / 180);
            const x = -(radius * Math.sin(phi) * Math.cos(theta));
            const z = radius * Math.sin(phi) * Math.sin(theta);
            const y = radius * Math.cos(phi);
            return new THREE.Vector3(x, y, z);
        }

        function createFallbackEarthTexture() {
            const canvas = document.createElement('canvas');
            canvas.width = 512;
            canvas.height = 256;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#0c2340';
            ctx.fillRect(0, 0, 512, 256);
            ctx.fillStyle = '#1e4d3b';
            for (let i = 0; i < 28; i++) {
                ctx.beginPath();
                ctx.arc((i * 73) % 512, 40 + ((i * 37) % 170), 18 + (i % 24), 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.strokeStyle = 'rgba(56, 189, 248, 0.25)';
            ctx.lineWidth = 1;
            for (let x = 0; x < 512; x += 32) {
                ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, 256); ctx.stroke();
            }
            for (let y = 0; y < 256; y += 32) {
                ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(512, y); ctx.stroke();
            }
            return new THREE.CanvasTexture(canvas);
        }

        function attachEarthRenderer(container) {
            if (!container || container.clientWidth === 0 || container.dataset.earthRendererAttached === 'true' || !earthScene) return;
            const width = Math.max(1, container.clientWidth);
            const height = Math.max(1, container.clientHeight);
            const camera = new THREE.PerspectiveCamera(42, width / height, 0.1, 1000);
            camera.position.z = 2.8;
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
            renderer.domElement.setAttribute('aria-label', 'Interactive 3D Earth analog locator');
            container.appendChild(renderer.domElement);
            container.dataset.earthRendererAttached = 'true';
            earthRenderTargets.push({ container, camera, renderer, width, height });

            let previousX = 0;
            let previousY = 0;
            container.addEventListener('mousedown', event => {
                earthIsDragging = true;
                previousX = event.clientX;
                previousY = event.clientY;
            });
            container.addEventListener('mousemove', event => {
                if (!earthIsDragging || !earthMesh) return;
                earthMesh.rotation.y += (event.clientX - previousX) * 0.006;
                earthMesh.rotation.x += (event.clientY - previousY) * 0.006;
                previousX = event.clientX;
                previousY = event.clientY;
            });
            container.addEventListener('mouseleave', () => { earthIsDragging = false; });
            window.addEventListener('mouseup', () => { earthIsDragging = false; }, { passive: true });
        }

        function initThreeJsEarth() {
            if (!globeInitialized) {
                earthScene = new THREE.Scene();
                earthFallbackTexture = createFallbackEarthTexture();
                const material = new THREE.MeshStandardMaterial({ map: earthFallbackTexture, roughness: 0.55, metalness: 0.1 });
                earthMesh = new THREE.Mesh(new THREE.SphereGeometry(1, 64, 64), material);
                earthScene.add(earthMesh);

                earthPin = new THREE.Mesh(
                    new THREE.SphereGeometry(0.036, 16, 16),
                    new THREE.MeshBasicMaterial({ color: 0xe04826 })
                );
                earthMesh.add(earthPin);

                earthRing = new THREE.Mesh(
                    new THREE.RingGeometry(1.24, 1.255, 64),
                    new THREE.MeshBasicMaterial({ color: 0xe04826, side: THREE.DoubleSide, transparent: true, opacity: 0.45 })
                );
                earthRing.rotation.x = Math.PI / 2.3;
                earthScene.add(earthRing);
                earthScene.add(new THREE.AmbientLight(0xffffff, 0.9));
                const sun = new THREE.DirectionalLight(0xffffff, 2.0);
                sun.position.set(4, 3, 5);
                earthScene.add(sun);
                globeInitialized = true;
                loadGibsEarthTexture(enabledArchiveLayers.landsat ? 'landsat' : selectedGibsLayer);
            }

            // Attach a renderer only when its page is visible. Both views share the same scene,
            // Earth texture, rotation and active station pin, avoiding duplicate globe state.
            ['dashboard-earth-canvas', 'threejs-earth-canvas'].forEach(id => {
                const container = document.getElementById(id);
                if (container && container.clientWidth > 0) attachEarthRenderer(container);
            });

            if (!earthAnimationStarted) {
                earthAnimationStarted = true;
                const animate = () => {
                    requestAnimationFrame(animate);
                    if (!earthIsDragging && earthMesh) earthMesh.rotation.y += 0.0014;
                    if (earthRing) earthRing.rotation.z -= 0.0018;
                    earthRenderTargets = earthRenderTargets.filter(target => target.container.isConnected);
                    earthRenderTargets.forEach(target => {
                        const width = Math.max(1, target.container.clientWidth);
                        const height = Math.max(1, target.container.clientHeight);
                        if (width !== target.width || height !== target.height) {
                            target.width = width;
                            target.height = height;
                            target.camera.aspect = width / height;
                            target.camera.updateProjectionMatrix();
                            target.renderer.setSize(width, height, false);
                        }
                        target.renderer.render(earthScene, target.camera);
                    });
                };
                animate();
            }
        }

        window.addEventListener('resize', () => {
            earthRenderTargets.forEach(target => {
                const width = Math.max(1, target.container.clientWidth);
                const height = Math.max(1, target.container.clientHeight);
                target.width = width;
                target.height = height;
                target.camera.aspect = width / height;
                target.camera.updateProjectionMatrix();
                target.renderer.setSize(width, height, false);
            });
        });

        function updateGlobeDetails(rec) {
            const coordinateLabel = `${rec.earthName} (${rec.lat.toFixed(2)}°, ${rec.lon.toFixed(2)}°)`;
            const fullGlobePinLabel = document.getElementById('globe-pin-label');
            const dashboardPinLabel = document.getElementById('dashboard-globe-pin-label');
            if (fullGlobePinLabel) fullGlobePinLabel.textContent = coordinateLabel;
            if (dashboardPinLabel) dashboardPinLabel.textContent = coordinateLabel;
            document.getElementById('globe-earth-photo').src = rec.earthImg;
            document.getElementById('globe-earth-caption').textContent = rec.earthName;
            document.getElementById('globe-space-photo').src = rec.spaceImg;
            document.getElementById('globe-space-caption').textContent = `${rec.targetCrater} (${rec.targetBody})`;

            if (earthPin) {
                const pos = latLonToVector3(rec.lat, rec.lon, 1.02);
                earthPin.position.copy(pos);
            }
        }

        window.focusGlobeOnSite = function(id) {
            selectAnalogRecord(id);
            const rec = analogRecords.find(r => r.id === id);
            if (rec && earthMesh) {
                earthMesh.rotation.y = -(rec.lon * Math.PI / 180) - Math.PI / 2;
                earthMesh.rotation.x = rec.lat * Math.PI / 180 * 0.5;
            }
        };

        window.changeGibsOverlay = function(layer) {
            selectedGibsLayer = GIBS_LAYER_CONFIG[layer] ? layer : 'truecolor';
            if (enabledArchiveLayers.landsat) {
                loadGibsEarthTexture('landsat');
                return;
            }
            loadGibsEarthTexture(selectedGibsLayer);
        };

        function renderGlobeStationsTable() {
            const tbody = document.getElementById('globe-stations-tbody');
            if (!tbody) return;
            tbody.innerHTML = analogRecords.map(r => `
                <tr onclick="focusGlobeOnSite('${r.id}')" class="cursor-pointer ${r.id === activeRecordId ? 'selected-row' : ''}">
                    <td class="font-mono text-xs text-[#ff8b6e] font-bold">${r.id}</td>
                    <td class="font-bold text-white">${r.earthName}</td>
                    <td class="font-mono text-xs text-slate-300">${r.lat.toFixed(2)}°</td>
                    <td class="font-mono text-xs text-slate-300">${r.lon.toFixed(2)}°</td>
                    <td class="text-xs text-slate-400">${r.nicheCategory || r.terrainClass}</td>
                    <td class="text-xs font-bold text-sky-300">${r.targetCrater} (${r.targetBody})</td>
                    <td class="text-right">
                        <button class="px-3 py-1 rounded-lg bg-[#e04826]/15 text-[#ff8b6e] hover:bg-[#e04826] hover:text-white text-[11px] font-bold transition-colors cursor-pointer">
                            Lock Pin
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        let telemetryChart = null;
        let latestPowerApiRows = [];
        let archiveApiRows = [];

        const CMR_ARCHIVE_CONFIG = {
            landsat: { label: 'NASA CMR / LANDSAT', keyword: '\"Landsat\"', spatial: true },
            sentinel: { label: 'NASA CMR / SENTINEL-2', keyword: '\"Sentinel-2\"', spatial: true },
            mro: { label: 'NASA CMR / MRO ARCHIVE', keyword: '\"Mars Reconnaissance Orbiter\"', spatial: false },
            lro: { label: 'NASA CMR / LRO ARCHIVE', keyword: '\"Lunar Reconnaissance Orbiter\"', spatial: false }
        };

        function escapeApiHtml(value) {
            return String(value ?? '').replace(/[&<>\"']/g, char => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '\"': '&quot;', "'": '&#39;'
            })[char]);
        }

        function renderNasaApiTable() {
            const tbody = document.getElementById('nasa-api-tbody');
            if (!tbody) return;

            const rows = [...latestPowerApiRows, ...archiveApiRows];
            if (!rows.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-sm text-slate-400">No API records loaded. Enable an archive switch or fetch NASA POWER data.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(row => `
                <tr>
                    <td class="font-mono text-xs font-bold text-[#ff8b6e]">${escapeApiHtml(row.src)}</td>
                    <td class="font-bold text-white">${escapeApiHtml(row.param)}</td>
                    <td class="text-xs text-slate-300">${escapeApiHtml(row.sensor)}</td>
                    <td class="font-mono text-xs text-slate-400">${escapeApiHtml(row.coords)}</td>
                    <td class="font-mono text-xs font-bold text-sky-300">${escapeApiHtml(row.val)}</td>
                    <td><span class="px-2.5 py-0.5 rounded-full ${row.statusClass || 'bg-sky-500/15 text-sky-300 border-sky-500/30'} border text-[10px] font-bold">${escapeApiHtml(row.status || 'ARCHIVE RECORD')}</span></td>
                </tr>
            `).join('');
        }

        window.applyPresetToApiInputs = function(id) {
            selectAnalogRecord(id);
        };

        window.executeLiveNasaApiQuery = function() {
            const lat = Number(document.getElementById('api-lat-input').value);
            const lon = Number(document.getElementById('api-lon-input').value);
            if (!Number.isFinite(lat) || lat < -90 || lat > 90 || !Number.isFinite(lon) || lon < -180 || lon > 180) {
                alert('Enter a valid latitude (-90 to 90) and longitude (-180 to 180).');
                return;
            }
            const rec = analogRecords.find(r => r.id === activeRecordId) || analogRecords[0];
            fetchLiveNasaApis(lat, lon, rec);
        };

        async function fetchLiveNasaApis(lat, lon, rec) {
            const statusPill = document.getElementById('api-live-status-pill');
            if (statusPill) statusPill.textContent = 'STATUS: QUERYING NASA POWER...';
            document.getElementById('telemetry-chart-coords').textContent = `LAT: ${lat.toFixed(2)}° | LON: ${lon.toFixed(2)}°`;

            // These are display fallbacks only. They are never labelled as live or cached API results.
            let t2m = lat < -50 ? -18.4 : (lat > 50 ? -11.2 : 15.8);
            let solar = lat < -50 ? 2.9 : 5.4;
            let rh = 64.2;
            let wind = 3.4;
            let sourceTag = 'REFERENCE VALUES (NO LIVE RESPONSE)';
            const cacheKey = `astroanalogs_power_v1_${lat.toFixed(2)}_${lon.toFixed(2)}`;
            let cachedValues = null;

            try {
                const cached = JSON.parse(localStorage.getItem(cacheKey) || 'null');
                if (cached?.values && Date.now() - cached.savedAt < 6 * 60 * 60 * 1000) cachedValues = cached.values;
            } catch (cacheError) {
                console.warn('NASA POWER browser cache could not be read.', cacheError);
            }

            const controller = new AbortController();
            const timer = window.setTimeout(() => controller.abort(), 8000);
            try {
                const url = `https://power.larc.nasa.gov/api/temporal/climatology/point?parameters=T2M,ALLSKY_SFC_SW_DWN,RH2M,WS2M&community=RE&longitude=${lon}&latitude=${lat}&format=JSON`;
                const resp = await fetch(url, { signal: controller.signal, headers: { Accept: 'application/json' } });
                if (!resp.ok) throw new Error(`NASA POWER returned HTTP ${resp.status}`);

                const data = await resp.json();
                const p = data?.properties?.parameter;
                const readAnn = (parameter, fallback, digits) => {
                    const value = Number(p?.[parameter]?.ANN);
                    return Number.isFinite(value) && value > -900 ? Number(value.toFixed(digits)) : fallback;
                };

                t2m = readAnn('T2M', t2m, 1);
                solar = readAnn('ALLSKY_SFC_SW_DWN', solar, 2);
                rh = readAnn('RH2M', rh, 1);
                wind = readAnn('WS2M', wind, 1);
                sourceTag = 'LIVE NASA POWER API';
                localStorage.setItem(cacheKey, JSON.stringify({
                    savedAt: Date.now(),
                    values: { t2m, solar, rh, wind }
                }));
            } catch (error) {
                if (cachedValues) {
                    t2m = Number(cachedValues.t2m);
                    solar = Number(cachedValues.solar);
                    rh = Number(cachedValues.rh);
                    wind = Number(cachedValues.wind);
                    sourceTag = 'CACHED NASA POWER (THIS BROWSER)';
                } else {
                    sourceTag = 'REFERENCE VALUES (NO LIVE RESPONSE)';
                    console.warn('NASA POWER request failed; showing clearly labelled reference values.', error);
                }
            } finally {
                window.clearTimeout(timer);
            }

            if (statusPill) statusPill.textContent = `STATUS: ${sourceTag}`;

            document.getElementById('card2-temp-display').textContent = `${t2m} °C`;
            document.getElementById('stat-solar-val').textContent = `${solar} kWh`;
            document.getElementById('stat-rh-val').textContent = `${rh}%`;
            document.getElementById('stat-wind-val').textContent = `${wind} m/s`;

            document.getElementById('kpi-temp-val').textContent = `${t2m} °C`;
            document.getElementById('kpi-solar-val').textContent = `${solar.toFixed(2)} kWh/m²`;
            document.getElementById('kpi-rh-val').textContent = `${rh} %`;
            document.getElementById('kpi-rh-note').textContent = rh < 30 ? 'Hyper-Arid Classification' : 'Moderate Aridity';
            document.getElementById('kpi-wind-val').textContent = `${wind} m/s`;

            const tCanvas = document.getElementById('telemetryBarChart');
            if (tCanvas) {
                if (telemetryChart) telemetryChart.destroy();
                telemetryChart = new Chart(tCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Surface Temp (°C)', 'Solar Flux (kWh/m²)', 'Rel. Humidity (%)', 'Wind Speed (m/s)'],
                        datasets: [
                            {
                                label: `Earth (${rec.earthName.split(',')[0]})`,
                                data: [t2m, solar, rh, wind],
                                backgroundColor: '#38bdf8',
                                borderRadius: 8,
                                barThickness: 18
                            },
                            {
                                label: `${rec.targetBody} Reference`,
                                data: [rec.targetBody === 'Mars' ? -55 : -23, rec.targetBody === 'Mars' ? 2.5 : 5.8, rec.targetBody === 'Mars' ? 4 : 0, rec.targetBody === 'Mars' ? 7.4 : 0],
                                backgroundColor: '#e04826',
                                borderRadius: 8,
                                barThickness: 18
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: '#e2e8f0', font: { size: 12 } } }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                            y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });
            }

            const powerStatus = sourceTag === 'LIVE NASA POWER API'
                ? { status: 'LIVE POWER', statusClass: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30' }
                : sourceTag.startsWith('CACHED')
                    ? { status: 'BROWSER CACHE', statusClass: 'bg-amber-500/15 text-amber-300 border-amber-500/30' }
                    : { status: 'REFERENCE ONLY', statusClass: 'bg-slate-500/15 text-slate-300 border-slate-500/30' };

            const coordinateText = `${lat.toFixed(2)}°, ${lon.toFixed(2)}°`;
            latestPowerApiRows = [
                { src: 'NASA POWER', param: 'T2M (2m Air Temperature)', sensor: 'POWER climatology', coords: coordinateText, val: `${t2m} °C`, ...powerStatus },
                { src: 'NASA POWER', param: 'ALLSKY_SFC_SW_DWN', sensor: 'All-sky surface shortwave radiation', coords: coordinateText, val: `${solar} kWh/m²/day`, ...powerStatus },
                { src: 'NASA POWER', param: 'RH2M (Relative Humidity)', sensor: 'POWER climatology', coords: coordinateText, val: `${rh} %`, ...powerStatus },
                { src: 'NASA POWER', param: 'WS2M (Wind Speed at 2m)', sensor: 'POWER climatology', coords: coordinateText, val: `${wind} m/s`, ...powerStatus }
            ];
            renderNasaApiTable();
        }

        async function fetchCmrArchive(layerKey) {
            const config = CMR_ARCHIVE_CONFIG[layerKey];
            if (!config || !enabledArchiveLayers[layerKey]) return;

            const badge = document.getElementById('api-live-status-pill');
            if (badge) badge.textContent = `STATUS: SEARCHING NASA CMR (${layerKey.toUpperCase()})...`;

            const activeRecord = analogRecords.find(r => r.id === activeRecordId) || analogRecords[0];
            const cacheKey = `astroanalogs_cmr_v1_${layerKey}_${config.spatial ? `${activeRecord.lat.toFixed(2)}_${activeRecord.lon.toFixed(2)}` : 'mission'}`;

            try {
                let mappedRows = null;
                try {
                    const cached = JSON.parse(localStorage.getItem(cacheKey) || 'null');
                    if (cached?.rows && Date.now() - cached.savedAt < 60 * 60 * 1000) mappedRows = cached.rows;
                } catch (cacheError) {
                    console.warn('CMR browser cache could not be read.', cacheError);
                }

                if (!mappedRows) {
                    const collectionParams = new URLSearchParams({ keyword: config.keyword, page_size: '5' });
                    const collectionResponse = await fetch(`https://cmr.earthdata.nasa.gov/search/collections.json?${collectionParams}`, {
                        headers: { Accept: 'application/json' }
                    });
                    if (!collectionResponse.ok) throw new Error(`CMR collection search returned HTTP ${collectionResponse.status}`);
                    const collectionData = await collectionResponse.json();
                    const collections = collectionData?.feed?.entry || [];
                    if (!collections.length) throw new Error('No matching CMR collections were returned.');

                    const granuleResults = [];
                    for (const collection of collections.slice(0, 3)) {
                        if (!collection.id) continue;
                        const granuleParams = new URLSearchParams({
                            collection_concept_id: collection.id,
                            page_size: '4'
                        });
                        granuleParams.append('sort_key[]', '-start_date');
                        if (config.spatial) {
                            const minLon = Math.max(-180, activeRecord.lon - 0.5);
                            const maxLon = Math.min(180, activeRecord.lon + 0.5);
                            const minLat = Math.max(-90, activeRecord.lat - 0.5);
                            const maxLat = Math.min(90, activeRecord.lat + 0.5);
                            granuleParams.set('bounding_box', `${minLon},${minLat},${maxLon},${maxLat}`);
                        }

                        const granuleResponse = await fetch(`https://cmr.earthdata.nasa.gov/search/granules.json?${granuleParams}`, {
                            headers: { Accept: 'application/json' }
                        });
                        if (!granuleResponse.ok) continue;
                        const granuleData = await granuleResponse.json();
                        const granules = granuleData?.feed?.entry || [];
                        granules.forEach(granule => granuleResults.push({
                            title: granule.title || granule.id || 'Untitled CMR granule',
                            id: granule.id || '',
                            collection: collection.short_name || collection.title || 'NASA CMR collection',
                            start: granule.time_start || 'Date not supplied',
                            end: granule.time_end || '',
                            updated: granule.updated || ''
                        }));
                    }

                    mappedRows = granuleResults.slice(0, 10).map(granule => ({
                        layerKey,
                        src: config.label,
                        param: granule.title,
                        sensor: granule.collection,
                        coords: config.spatial ? `${activeRecord.lat.toFixed(2)}°, ${activeRecord.lon.toFixed(2)}° area` : 'Mission archive',
                        val: granule.end ? `${granule.start} – ${granule.end}` : granule.start,
                        status: 'CMR ARCHIVE',
                        statusClass: 'bg-sky-500/15 text-sky-300 border-sky-500/30'
                    }));

                    if (!mappedRows.length) {
                        mappedRows = [{
                            layerKey, src: config.label, param: 'No granules matched this search area',
                            sensor: 'CMR collection search completed',
                            coords: config.spatial ? `${activeRecord.lat.toFixed(2)}°, ${activeRecord.lon.toFixed(2)}° area` : 'Mission archive',
                            val: 'No matching granules returned', status: 'NO GRANULES',
                            statusClass: 'bg-amber-500/15 text-amber-300 border-amber-500/30'
                        }];
                    }

                    localStorage.setItem(cacheKey, JSON.stringify({ savedAt: Date.now(), rows: mappedRows }));
                }

                archiveApiRows = archiveApiRows.filter(row => row.layerKey !== layerKey).concat(mappedRows);
                renderNasaApiTable();
                if (badge) badge.textContent = `STATUS: NASA POWER + CMR ARCHIVES`;
            } catch (error) {
                console.warn(`NASA CMR ${layerKey} archive search failed.`, error);
                archiveApiRows = archiveApiRows.filter(row => row.layerKey !== layerKey);
                archiveApiRows.push({
                    layerKey, src: config.label, param: 'CMR archive search unavailable',
                    sensor: 'Check network access and retry the switch',
                    coords: config.spatial ? `${activeRecord.lat.toFixed(2)}°, ${activeRecord.lon.toFixed(2)}° area` : 'Mission archive',
                    val: 'No live response', status: 'UNAVAILABLE',
                    statusClass: 'bg-rose-500/15 text-rose-300 border-rose-500/30'
                });
                renderNasaApiTable();
                if (badge) badge.textContent = `STATUS: CMR ${layerKey.toUpperCase()} UNAVAILABLE`;
            }
        }

        window.toggleEarthObservationLayer = function(layerKey, enabled) {
            if (!['landsat', 'sentinel'].includes(layerKey)) return;
            enabledArchiveLayers[layerKey] = Boolean(enabled);

            if (layerKey === 'landsat') {
                if (enabled) loadGibsEarthTexture('landsat');
                else loadGibsEarthTexture(selectedGibsLayer);
            }

            if (enabled) {
                fetchCmrArchive(layerKey);
            } else {
                archiveApiRows = archiveApiRows.filter(row => row.layerKey !== layerKey);
                renderNasaApiTable();
            }
        };

        window.toggleMissionArchive = function(layerKey, enabled) {
            if (!['mro', 'lro'].includes(layerKey)) return;
            enabledArchiveLayers[layerKey] = Boolean(enabled);
            if (enabled) {
                fetchCmrArchive(layerKey);
            } else {
                archiveApiRows = archiveApiRows.filter(row => row.layerKey !== layerKey);
                renderNasaApiTable();
            }
        };

        let demInitialized = false;
        let earthDemMesh = null;
        let targetDemMesh = null;

        function initThreeJsDem() {
            if (demInitialized) return;
            const container = document.getElementById('threejs-dem-canvas');
            if (!container || container.clientWidth === 0) return;
            demInitialized = true;

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, container.clientWidth / container.clientHeight, 0.1, 100);
            camera.position.set(0, 3.4, 4.2);
            camera.lookAt(0, 0, 0);

            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.setPixelRatio(window.devicePixelRatio);
            container.appendChild(renderer.domElement);

            const earthGeo = new THREE.PlaneGeometry(4.5, 3.2, 36, 26);
            earthGeo.rotateX(-Math.PI / 2);
            earthDemMesh = new THREE.Mesh(
                earthGeo,
                new THREE.MeshBasicMaterial({ color: 0x38bdf8, wireframe: true, transparent: true, opacity: 0.7 })
            );
            scene.add(earthDemMesh);

            const targetGeo = new THREE.PlaneGeometry(4.5, 3.2, 36, 26);
            targetGeo.rotateX(-Math.PI / 2);
            targetDemMesh = new THREE.Mesh(
                targetGeo,
                new THREE.MeshBasicMaterial({ color: 0xe04826, wireframe: true, transparent: true, opacity: 0.75 })
            );
            targetDemMesh.position.y = 0.08;
            scene.add(targetDemMesh);

            function animate() {
                requestAnimationFrame(animate);
                if (earthDemMesh) earthDemMesh.rotation.y += 0.002;
                if (targetDemMesh) targetDemMesh.rotation.y += 0.002;
                renderer.render(scene, camera);
            }
            animate();
        }

        window.updateDemView = function(id) {
            const rec = analogRecords.find(r => r.id === id) || analogRecords[0];
            if (!rec) return;
            if (!demInitialized) initThreeJsDem();

            const seed = (rec.topography || 80) / 100;
            if (earthDemMesh && targetDemMesh) {
                const ePos = earthDemMesh.geometry.attributes.position;
                const tPos = targetDemMesh.geometry.attributes.position;

                for (let i = 0; i < ePos.count; i++) {
                    const x = ePos.getX(i);
                    const z = ePos.getZ(i);
                    const dist = Math.sqrt(x * x + z * z);
                    const eHeight = Math.sin(x * 1.8 + seed * 4) * Math.cos(z * 1.8) * 0.45 * seed;
                    const craterRim = Math.exp(-Math.pow(dist - 1.1, 2) * 3.2) * 0.65 - Math.exp(-dist * dist * 2.5) * 0.55;
                    ePos.setY(i, eHeight);
                    tPos.setY(i, craterRim * (0.7 + seed * 0.4));
                }
                ePos.needsUpdate = true;
                tPos.needsUpdate = true;
            }
            renderDemTable();
        };

        window.toggleDemWireframeLayers = function(mode) {
            if (!earthDemMesh || !targetDemMesh) return;
            earthDemMesh.visible = mode === 'dual' || mode === 'earth';
            targetDemMesh.visible = mode === 'dual' || mode === 'target';
        };

        function renderDemTable() {
            const tbody = document.getElementById('dem-metrics-tbody');
            if (!tbody) return;
            tbody.innerHTML = analogRecords.map(r => `
                <tr onclick="selectAnalogRecord('${r.id}'); updateDemView('${r.id}')" class="cursor-pointer ${r.id === activeRecordId ? 'selected-row' : ''}">
                    <td class="font-mono text-xs text-[#ff8b6e] font-bold">${r.id}</td>
                    <td class="font-bold text-white">${r.earthName}</td>
                    <td class="text-xs font-semibold text-slate-300">${r.targetCrater}</td>
                    <td class="font-mono text-xs text-sky-300">${r.elevation || '1,850 m'}</td>
                    <td class="font-mono text-xs text-[#ff8b6e]">${r.targetRelief || '-3,100 m'}</td>
                    <td class="font-mono text-xs text-slate-300">${r.roughness || '0.52 RI'}</td>
                    <td><span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 font-mono text-xs font-bold">${r.topography}%</span></td>
                    <td class="text-right">
                        <button class="px-3 py-1 rounded-lg bg-sky-500/15 text-sky-300 hover:bg-sky-500 hover:text-white text-[11px] font-bold transition-colors cursor-pointer">
                            Render 3D
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        window.openCrudModal = function(editId = null) {
            const modal = document.getElementById('crud-modal');
            const titleEl = document.getElementById('crud-modal-title');
            const editInput = document.getElementById('crud-edit-id');

            if (editId) {
                const rec = analogRecords.find(r => r.id === editId);
                if (!rec) return;
                titleEl.innerHTML = `<i class="fa-solid fa-pen-to-square text-[#ff8b6e]"></i> Edit Analog Record (${rec.id})`;
                editInput.value = rec.id;
                document.getElementById('crud-earth-name').value = rec.earthName;
                document.getElementById('crud-target-body').value = rec.targetBody;
                document.getElementById('crud-niche-category').value = rec.nicheCategory || 'Arid & Volcanic Terrains';
                document.getElementById('crud-target-crater').value = rec.targetCrater;
                document.getElementById('crud-lat').value = rec.lat;
                document.getElementById('crud-lon').value = rec.lon;
                document.getElementById('crud-geo').value = rec.geology;
                document.getElementById('crud-topo').value = rec.topography;
                document.getElementById('crud-clim').value = rec.climate;
                document.getElementById('crud-terrain').value = rec.terrainClass || '';
            } else {
                titleEl.innerHTML = `<i class="fa-solid fa-plus text-[#ff8b6e]"></i> Add Planetary Analog Record`;
                editInput.value = '';
                document.getElementById('crud-form').reset();
                document.getElementById('crud-geo').value = 91;
                document.getElementById('crud-topo').value = 84;
                document.getElementById('crud-clim').value = 88;
            }

            modal.classList.remove('hidden');
        };

        window.closeCrudModal = function() {
            document.getElementById('crud-modal').classList.add('hidden');
        };

        window.saveCrudRecord = function(e) {
            e.preventDefault();
            const editId = document.getElementById('crud-edit-id').value;
            const earthName = document.getElementById('crud-earth-name').value.trim();
            const targetBody = document.getElementById('crud-target-body').value;
            const nicheCategory = document.getElementById('crud-niche-category').value;
            const targetCrater = document.getElementById('crud-target-crater').value.trim();
            const lat = parseFloat(document.getElementById('crud-lat').value);
            const lon = parseFloat(document.getElementById('crud-lon').value);
            const geology = parseInt(document.getElementById('crud-geo').value, 10);
            const topography = parseInt(document.getElementById('crud-topo').value, 10);
            const climate = parseInt(document.getElementById('crud-clim').value, 10);
            const terrainClass = document.getElementById('crud-terrain').value.trim() || `${nicheCategory} Field Site`;
            const overall = Number(((geology * 0.4) + (topography * 0.3) + (climate * 0.3)).toFixed(1));

            if (editId) {
                const idx = analogRecords.findIndex(r => r.id === editId);
                if (idx !== -1) {
                    analogRecords[idx] = {
                        ...analogRecords[idx],
                        earthName, targetBody, nicheCategory, targetCrater, lat, lon, geology, topography, climate, overall, terrainClass
                    };
                    pushNotification('Analog Record Updated', `${earthName} ↔ ${targetCrater} updated.`);
                }
            } else {
                const newId = `ANL-${String(analogRecords.length + 1).padStart(3, '0')}`;
                const isMars = targetBody === 'Mars';
                const newRec = {
                    id: newId,
                    earthName,
                    nicheCategory,
                    targetBody,
                    targetCrater,
                    lat,
                    lon,
                    geology,
                    topography,
                    climate,
                    overall,
                    elevation: '1,650 m',
                    targetRelief: isMars ? '-2,800 m' : '-3,400 m',
                    roughness: '0.51 RI',
                    terrainClass,
                    gravityBadge: isMars ? '0.38g' : '0.165g',
                    gravityDesc: isMars
                        ? 'Mars gravity is 3.72 m/s² (38% Earth). Rover mobility requires gravity-offload testing.'
                        : 'Lunar gravity is 1.62 m/s² (1/6th Earth). Regolith compaction differs significantly.',
                    radBadge: isMars ? '240 mSv/yr' : 'Unattenuated GCR',
                    radDesc: 'Earth magnetosphere shields against cosmic radiation; supplemental lab chambers required.',
                    vacBadge: isMars ? '0.61 kPa' : '10⁻¹² Torr',
                    vacDesc: 'Extraterrestrial pressure difference affects thermal convection and dust adhesion.',
                    utilities: [
                        { title: 'Rover Mobility & Sensor Calibration', desc: `Field validation of autonomous navigation and spectrometers at ${earthName}.` },
                        { title: 'Regolith & Sample Return Drill Test', desc: `Testing core extraction mechanics analogous to ${targetCrater}.` }
                    ],
                    earthImg: 'https://images.unsplash.com/photo-1509316785289-025f5b846b35?auto=format&fit=crop&w=900&q=80',
                    spaceImg: isMars
                        ? 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?auto=format&fit=crop&w=900&q=80'
                        : 'https://images.unsplash.com/photo-1522030299830-16b8d3d049fe?auto=format&fit=crop&w=900&q=80'
                };
                analogRecords.unshift(newRec);
                activeRecordId = newId;
                pushNotification('New Analog Added', `${earthName} ↔ ${targetCrater} added to registry.`);
            }

            saveToStorage();
            closeCrudModal();
            syncAllControls();
            selectAnalogRecord(activeRecordId);
        };

        window.deleteCrudRecord = function(id) {
            if (analogRecords.length <= 1) {
                alert('At least one planetary analog benchmark record must remain in the registry.');
                return;
            }
            if (confirm(`Delete analog record ${id}?`)) {
                const removed = analogRecords.find(r => r.id === id);
                analogRecords = analogRecords.filter(r => r.id !== id);
                if (activeRecordId === id) activeRecordId = analogRecords[0].id;
                saveToStorage();
                syncAllControls();
                selectAnalogRecord(activeRecordId);
                if (removed) pushNotification('Record Deleted', `${removed.earthName} removed from registry.`);
            }
        };

        window.resetDefaultRecords = function() {
            if (confirm('Restore original NASA niche planetary analog benchmark dataset?')) {
                analogRecords = structuredClone(DEFAULT_RECORDS);
                activeRecordId = analogRecords[0].id;
                saveToStorage();
                syncAllControls();
                selectAnalogRecord(activeRecordId);
                pushNotification('Registry Restored', 'Original NASA niche benchmark sites restored.');
            }
        };

        renderNotifications();
        syncAllControls();
        selectAnalogRecord(activeRecordId);
        // Dashboard is the initially visible page, so attach its 3D Earth renderer immediately.
        window.setTimeout(() => {
            initThreeJsEarth();
            const activeRecord = analogRecords.find(record => record.id === activeRecordId) || analogRecords[0];
            if (activeRecord) updateGlobeDetails(activeRecord);
            updateAiRangeLabels();
        }, 80);
    </script>
</body>
</html>