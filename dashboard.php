<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard – EduGenius AI</title>
    <link rel="manifest" href="./manifest.json">
    <link rel="icon" sizes="192x192" href="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        /* ============================================================
           EduGenius Dashboard – Self-contained, matches platform DS
           ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:        #7c3aed;
            --primary-light:  #a78bfa;
            --primary-dark:   #5b21b6;
            --secondary:      #06b6d4;
            --text-primary:   #1e1b4b;
            --text-secondary: #64748b;
            --bg-main:        #f0f2ff;
            --glass-bg:       rgba(255,255,255,0.82);
            --glass-border:   rgba(255,255,255,0.65);
            --shadow-glow:    0 0 40px rgba(124,58,237,.10);
            --radius:         20px;
            --radius-sm:      12px;
        }
        .dark {
            --primary:        #a78bfa;
            --primary-light:  #c4b5fd;
            --primary-dark:   #7c3aed;
            --secondary:      #22d3ee;
            --text-primary:   #e2e8f0;
            --text-secondary: #94a3b8;
            --bg-main:        #0d0d1a;
            --glass-bg:       rgba(15,15,40,0.85);
            --glass-border:   rgba(255,255,255,0.08);
            --shadow-glow:    0 0 60px rgba(124,58,237,.18);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            background-color: var(--bg-main);
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
            transition: background-color .4s ease, color .4s ease;
        }

        /* Gradient background blobs */
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 15%  5%,  rgba(124,58,237,.18) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14)  0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(236,72,153,.08) 0%, transparent 55%);
        }
        .dark body::before {
            background:
                radial-gradient(ellipse 80% 60% at 15%  5%,  rgba(124,58,237,.32) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.22)  0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(236,72,153,.14) 0%, transparent 55%);
        }

        /* ---- Animations ---- */
        @keyframes fadeInUp   { from { opacity:0; transform:translateY(28px) } to { opacity:1; transform:translateY(0) } }
        @keyframes pulse      { 0%,100%{opacity:1} 50%{opacity:.4} }
        @keyframes shimmer    { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        @keyframes barGrow    { from{width:0} to{width:var(--w)} }
        @keyframes streakBeat { 0%,100%{transform:scale(1)} 50%{transform:scale(1.12)} }

        .animate-in   { animation: fadeInUp .6s cubic-bezier(.16,1,.3,1) both; }
        .delay-1 { animation-delay:.08s }
        .delay-2 { animation-delay:.16s }
        .delay-3 { animation-delay:.24s }
        .delay-4 { animation-delay:.32s }
        .delay-5 { animation-delay:.40s }

        /* ---- Glass card ---- */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.08), var(--shadow-glow);
            position: relative; overflow: hidden;
            transition: box-shadow .3s, transform .3s;
        }
        .glass-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.5), transparent);
            pointer-events: none;
        }

        /* ---- Gradient text ---- */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ---- Buttons ---- */
        .btn-primary {
            display: inline-flex; align-items: center; gap:.5rem;
            background: linear-gradient(135deg,#7c3aed 0%,#5b21b6 100%);
            color: #fff; border: none; border-radius: var(--radius-sm);
            font-weight: 700; cursor: pointer; text-decoration: none;
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .dark .btn-primary { background: linear-gradient(135deg,#a78bfa 0%,#7c3aed 100%); }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.42); }
        .btn-primary:active { transform:translateY(0); }

        .btn-secondary {
            display: inline-flex; align-items: center; gap:.5rem;
            background: var(--glass-bg); color: var(--text-primary);
            border: 1px solid var(--glass-border); border-radius: var(--radius-sm);
            font-weight: 600; cursor: pointer; text-decoration: none;
            transition: all .25s ease; backdrop-filter: blur(12px);
        }
        .btn-secondary:hover { border-color: rgba(124,58,237,.45); color: var(--primary); background: rgba(124,58,237,.06); }

        /* ---- Auth overlay ---- */
        #auth-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--bg-main);
            display: flex; align-items: center; justify-content: center;
        }
        .auth-spinner {
            width: 48px; height: 48px; border: 4px solid rgba(124,58,237,.2);
            border-top-color: var(--primary); border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ---- Header ---- */
        .site-header {
            position: sticky; top: 0; z-index: 100;
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 2px 16px rgba(0,0,0,.06);
            transition: background .4s ease;
        }
        .header-inner {
            max-width: 1200px; margin: 0 auto;
            padding: .875rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
        }
        .brand { display:flex; align-items:center; gap:.625rem; text-decoration:none; flex-shrink:0; }
        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: #fff;
            box-shadow: 0 2px 8px rgba(124,58,237,.3);
        }
        .brand-name {
            font-size: 1.2rem; font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .header-divider {
            width: 1px; height: 24px;
            background: var(--glass-border); flex-shrink: 0;
        }
        .page-title {
            font-size: 1rem; font-weight: 700;
            color: var(--text-primary); flex: 1;
        }
        .header-actions { display: flex; align-items: center; gap: .5rem; }

        .theme-moon { display:inline-block; }
        .theme-sun  { display:none; }
        .dark .theme-moon { display:none; }
        .dark .theme-sun  { display:inline-block; }

        /* ---- Layout ---- */
        .main { max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem 4rem; position: relative; z-index: 1; }

        /* ---- Welcome banner ---- */
        .welcome-banner {
            padding: 2rem 2.25rem;
            background: linear-gradient(135deg, rgba(124,58,237,.14) 0%, rgba(6,182,212,.10) 100%);
            border: 1px solid rgba(124,58,237,.18);
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
            flex-wrap: wrap;
        }
        .welcome-greeting { font-size: 1.6rem; font-weight: 800; color: var(--text-primary); }
        .welcome-sub { font-size: .95rem; color: var(--text-secondary); margin-top: .35rem; }
        .welcome-tip {
            background: rgba(255,255,255,.5); backdrop-filter: blur(8px);
            border: 1px solid var(--glass-border); border-radius: var(--radius-sm);
            padding: .75rem 1.1rem; max-width: 440px;
            font-size: .875rem; color: var(--text-secondary); line-height: 1.5;
        }
        .dark .welcome-tip { background: rgba(255,255,255,.06); }
        .welcome-tip strong { color: var(--primary); }

        /* ---- Section heading ---- */
        .section-heading {
            font-size: 1.1rem; font-weight: 700;
            color: var(--text-primary); margin-bottom: 1rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .section-heading i { color: var(--primary); }

        /* ---- Stats grid ---- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            padding: 1.5rem 1.75rem;
            display: flex; align-items: center; gap: 1.1rem;
            transition: transform .3s;
        }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #fff; flex-shrink: 0;
        }
        .stat-label { font-size: .78rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .04em; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--text-primary); line-height: 1.1; }
        .stat-sub   { font-size: .78rem; color: var(--text-secondary); margin-top: .2rem; }

        /* ---- Skeleton loader ---- */
        .skeleton {
            background: linear-gradient(90deg, rgba(124,58,237,.08) 25%, rgba(124,58,237,.16) 50%, rgba(124,58,237,.08) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        /* ---- Two-column layout ---- */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }

        /* ---- Bar chart ---- */
        .bar-chart { padding: 1.5rem; }
        .bar-row {
            display: flex; align-items: center; gap: .75rem;
            margin-bottom: .875rem;
        }
        .bar-row:last-child { margin-bottom: 0; }
        .bar-label {
            width: 140px; flex-shrink: 0;
            font-size: .8rem; font-weight: 600;
            color: var(--text-secondary); text-align: right;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .bar-track {
            flex: 1; height: 12px;
            background: rgba(124,58,237,.08); border-radius: 99px; overflow: hidden;
        }
        .bar-fill {
            height: 100%; border-radius: 99px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            width: var(--w, 0%);
            animation: barGrow .8s cubic-bezier(.16,1,.3,1) both;
        }
        .bar-count {
            width: 32px; flex-shrink: 0;
            font-size: .8rem; font-weight: 700; color: var(--primary); text-align: left;
        }

        /* ---- Recent sessions ---- */
        .session-item {
            display: flex; align-items: center; gap: .875rem;
            padding: .875rem 1rem;
            border-bottom: 1px solid var(--glass-border);
            cursor: pointer; transition: background .2s;
            text-decoration: none; color: inherit;
        }
        .session-item:last-child { border-bottom: none; }
        .session-item:hover { background: rgba(124,58,237,.05); }
        .session-dot {
            width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
        }
        .session-info { flex: 1; min-width: 0; }
        .session-tool-row { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
        .badge {
            display: inline-block; padding: .15rem .55rem;
            border-radius: 99px; font-size: .72rem; font-weight: 700;
            color: #fff; white-space: nowrap;
        }
        .badge-subject {
            background: rgba(124,58,237,.12); color: var(--primary);
            border: 1px solid rgba(124,58,237,.22); font-size: .7rem;
        }
        .session-summary {
            font-size: .8rem; color: var(--text-secondary);
            margin-top: .2rem; overflow: hidden;
            display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;
        }
        .session-time { font-size: .75rem; color: var(--text-secondary); white-space: nowrap; flex-shrink: 0; }

        /* ---- Streak ---- */
        .streak-widget { padding: 1.75rem 2rem; text-align: center; }
        .streak-flame {
            font-size: 3.5rem; line-height: 1;
            animation: streakBeat 2s ease-in-out infinite;
            display: block; margin-bottom: .5rem;
        }
        .streak-count { font-size: 3rem; font-weight: 800; color: var(--primary); line-height: 1; }
        .streak-label { font-size: .85rem; color: var(--text-secondary); margin-top: .35rem; }
        .streak-note  { font-size: .78rem; color: var(--text-secondary); margin-top: .5rem; font-style: italic; }

        /* ---- SRS widget ---- */
        .srs-widget { padding: 1.75rem 2rem; }
        .srs-numbers { display: flex; align-items: baseline; gap: .5rem; margin: .75rem 0; }
        .srs-due     { font-size: 2.2rem; font-weight: 800; color: #ef4444; line-height: 1; }
        .srs-total   { font-size: 1rem; color: var(--text-secondary); }
        .srs-bar-track {
            height: 8px; background: rgba(124,58,237,.1); border-radius: 99px; overflow: hidden; margin-bottom: .75rem;
        }
        .srs-bar-fill {
            height: 100%; border-radius: 99px;
            background: linear-gradient(90deg, #ef4444, #f59e0b);
            width: var(--w, 0%);
            animation: barGrow .8s cubic-bezier(.16,1,.3,1) both;
        }

        /* ---- Quick launch ---- */
        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: .875rem;
        }
        .quick-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: .6rem; padding: 1.25rem .875rem;
            border: 1px solid var(--glass-border); border-radius: var(--radius-sm);
            background: var(--glass-bg); backdrop-filter: blur(12px);
            cursor: pointer; text-decoration: none; color: var(--text-primary);
            transition: all .3s cubic-bezier(.4,0,.2,1);
            font-weight: 700; font-size: .875rem;
        }
        .quick-btn:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.12); }
        .quick-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff; flex-shrink: 0;
        }

        /* ---- Empty states ---- */
        .empty-state {
            text-align: center; padding: 2.5rem 1rem;
            color: var(--text-secondary); font-size: .9rem;
        }
        .empty-state i { font-size: 2rem; margin-bottom: .75rem; display: block; opacity: .4; }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 99px; }

        /* ---- Responsive ---- */
        @media (max-width: 640px) {
            .header-inner { padding: .75rem 1rem; }
            .main { padding: 1.25rem .875rem 3rem; }
            .welcome-banner { padding: 1.5rem 1.25rem; }
            .welcome-greeting { font-size: 1.25rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stat-card { padding: 1.1rem 1rem; }
            .stat-value { font-size: 1.6rem; }
            .bar-label { width: 100px; }
            .brand-name { font-size: 1.05rem; }
            .page-title { display: none; }
        }
    </style>
</head>

<?= firebaseConfigScript() ?><body>

<!-- Auth guard overlay -->
<div id="auth-overlay" role="status" aria-label="Checking authentication">
    <div class="auth-spinner"></div>
</div>

<!-- ===== HEADER ===== -->
<header class="site-header" role="banner">
    <div class="header-inner">
        <a href="./index.php" class="brand" aria-label="EduGenius AI home">
            <div class="brand-icon" aria-hidden="true"><i class="fas fa-graduation-cap"></i></div>
            <span class="brand-name">EduGenius</span>
        </a>
        <div class="header-divider" aria-hidden="true"></div>
        <span class="page-title">My Dashboard</span>
        <div class="header-actions">
            <button id="theme-toggle" class="btn-secondary" style="width:40px;height:40px;justify-content:center;padding:0;" aria-label="Toggle dark mode">
                <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i>
                <i class="fas fa-sun  theme-sun"  style="color:var(--primary)"></i>
            </button>
            <a id="back-btn" href="./index.php" class="btn-secondary" style="padding:.5rem 1rem;font-size:.875rem;" aria-label="Back to EduGenius">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span>Back</span>
            </a>
        </div>
    </div>
</header>

<!-- ===== MAIN ===== -->
<main class="main" id="main-content" aria-label="Dashboard content">

    <!-- Welcome banner -->
    <section class="welcome-banner glass-card animate-in mb-6" aria-label="Welcome banner">
        <div>
            <div class="welcome-greeting" id="welcome-greeting">Welcome back! 👋</div>
            <div class="welcome-sub" id="welcome-sub">Loading…</div>
        </div>
        <div class="welcome-tip" role="note" aria-label="Daily DSE tip">
            <strong><i class="fas fa-lightbulb" aria-hidden="true"></i> DSE Tip:</strong>
            <span id="dse-tip">Loading tip…</span>
        </div>
    </section>

    <!-- Stats grid -->
    <section aria-label="Learning statistics" class="mb-6">
        <h2 class="section-heading animate-in delay-1">
            <i class="fas fa-chart-bar" aria-hidden="true"></i> Your Stats
        </h2>
        <div class="stats-grid" id="stats-grid">
            <!-- stat cards injected by JS -->
            <div class="glass-card stat-card animate-in delay-1" aria-label="Total AI sessions loading">
                <div class="stat-icon skeleton" style="width:52px;height:52px;"></div>
                <div style="flex:1"><div class="skeleton" style="height:1rem;width:60%;margin-bottom:.5rem;"></div><div class="skeleton" style="height:2rem;width:40%;"></div></div>
            </div>
            <div class="glass-card stat-card animate-in delay-2" aria-label="This week sessions loading">
                <div class="stat-icon skeleton" style="width:52px;height:52px;"></div>
                <div style="flex:1"><div class="skeleton" style="height:1rem;width:60%;margin-bottom:.5rem;"></div><div class="skeleton" style="height:2rem;width:40%;"></div></div>
            </div>
            <div class="glass-card stat-card animate-in delay-3" aria-label="Most used tool loading">
                <div class="stat-icon skeleton" style="width:52px;height:52px;"></div>
                <div style="flex:1"><div class="skeleton" style="height:1rem;width:60%;margin-bottom:.5rem;"></div><div class="skeleton" style="height:2rem;width:40%;"></div></div>
            </div>
            <div class="glass-card stat-card animate-in delay-4" aria-label="Subjects explored loading">
                <div class="stat-icon skeleton" style="width:52px;height:52px;"></div>
                <div style="flex:1"><div class="skeleton" style="height:1rem;width:60%;margin-bottom:.5rem;"></div><div class="skeleton" style="height:2rem;width:40%;"></div></div>
            </div>
        </div>
    </section>

    <!-- Tool chart + Recent sessions -->
    <div class="two-col mb-6">

        <!-- Tool usage chart -->
        <section aria-label="Tool usage chart">
            <h2 class="section-heading animate-in delay-2">
                <i class="fas fa-tools" aria-hidden="true"></i> Tool Usage
            </h2>
            <div class="glass-card animate-in delay-2" id="tool-chart">
                <div class="bar-chart">
                    <!-- skeleton rows -->
                    <div class="bar-row" aria-hidden="true">
                        <div class="skeleton bar-label" style="height:.8rem;"></div>
                        <div class="bar-track"><div class="skeleton" style="height:100%;width:100%;"></div></div>
                    </div>
                    <div class="bar-row" aria-hidden="true">
                        <div class="skeleton bar-label" style="height:.8rem;"></div>
                        <div class="bar-track"><div class="skeleton" style="height:100%;width:100%;"></div></div>
                    </div>
                    <div class="bar-row" aria-hidden="true">
                        <div class="skeleton bar-label" style="height:.8rem;"></div>
                        <div class="bar-track"><div class="skeleton" style="height:100%;width:100%;"></div></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent sessions -->
        <section aria-label="Recent sessions">
            <h2 class="section-heading animate-in delay-3">
                <i class="fas fa-history" aria-hidden="true"></i> Recent Sessions
            </h2>
            <div class="glass-card animate-in delay-3" id="recent-sessions" style="overflow:hidden;">
                <div class="empty-state" aria-live="polite">
                    <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                    Loading…
                </div>
            </div>
        </section>
    </div>

    <!-- Streak + SRS -->
    <div class="two-col mb-6">

        <!-- Study streak -->
        <section aria-label="Study streak">
            <h2 class="section-heading animate-in delay-3">
                <i class="fas fa-fire" aria-hidden="true"></i> Study Streak
            </h2>
            <div class="glass-card streak-widget animate-in delay-3" id="streak-widget">
                <span class="streak-flame" aria-hidden="true">🔥</span>
                <div class="streak-count" id="streak-count">–</div>
                <div class="streak-label">day streak</div>
                <div class="streak-note" id="streak-note">Loading…</div>
            </div>
        </section>

        <!-- SRS summary -->
        <section aria-label="SRS vocabulary summary">
            <h2 class="section-heading animate-in delay-4">
                <i class="fas fa-book" aria-hidden="true"></i> SRS Vocabulary
            </h2>
            <div class="glass-card srs-widget animate-in delay-4">
                <div class="stat-label">Words Due Today</div>
                <div class="srs-numbers">
                    <div class="srs-due" id="srs-due">–</div>
                    <div class="srs-total" id="srs-total">/ – total</div>
                </div>
                <div class="srs-bar-track" aria-hidden="true">
                    <div class="srs-bar-fill" id="srs-bar" style="--w:0%"></div>
                </div>
                <a href="./vocab.php#srs" class="btn-primary" style="padding:.5rem 1.1rem;font-size:.85rem;border-radius:10px;" aria-label="Go to SRS review">
                    <i class="fas fa-play" aria-hidden="true"></i> Start Review
                </a>
            </div>
        </section>
    </div>

    <!-- Quick launch -->
    <section aria-label="Quick launch subjects" class="animate-in delay-5">
        <h2 class="section-heading">
            <i class="fas fa-rocket" aria-hidden="true"></i> Quick Launch
        </h2>
        <div class="quick-grid" id="quick-grid">
            <!-- Injected by JS -->
        </div>
    </section>

</main>

<!-- ===== SCRIPTS ===== -->
<script>
    /* ----------------------------------------------------------------
       Non-module helpers (called by Firebase module script below)
    ---------------------------------------------------------------- */

    const TOOL_COLOR = {
        'Ask AI':                '#7c3aed',
        'Dictionary AI':         '#10b981',
        'Guide Learning':        '#8b5cf6',
        'Error Analysis':        '#ef4444',
        'Code Review':           '#06b6d4',
        'Exam Paper Generator':  '#f59e0b',
        'English Writing':       '#14b8a6',
        'Vocabulary Generator':  '#eab308',
        'Vocabulary':            '#eab308',
    };

    const TOOL_ICON = {
        'Ask AI':                'comments',
        'Dictionary AI':         'book',
        'Guide Learning':        'map-signs',
        'Error Analysis':        'exclamation-triangle',
        'Code Review':           'code',
        'Exam Paper Generator':  'file-alt',
        'English Writing':       'pen-nib',
        'Vocabulary Generator':  'language',
        'Vocabulary':            'language',
    };

    const SUBJECT_MAP = {
        '中文':    { color: '#ef4444', icon: 'fa-dragon',      label: '中文'    },
        'English': { color: '#3b82f6', icon: 'fa-book-open',   label: 'English' },
        'Math':    { color: '#10b981', icon: 'fa-calculator',  label: 'Math'    },
        'Physics': { color: '#8b5cf6', icon: 'fa-atom',        label: 'Physics' },
        'Biology': { color: '#22c55e', icon: 'fa-dna',         label: 'Biology' },
        'ICT':     { color: '#6366f1', icon: 'fa-laptop-code', label: 'ICT'     },
    };

    const DSE_TIPS = [
        'Attempt every question – partial marks are awarded even for incomplete answers.',
        'Manage your time: allocate roughly 1 minute per mark in Section B.',
        'Show all working clearly; method marks can save you even if your final answer is wrong.',
        'Read each question twice before writing – many marks are lost by misreading.',
        'Review past papers from 2012 onward; question styles repeat more than you think.',
    ];

    /* ---- Theme ---- */
    (function initTheme() {
        const stored = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (stored === 'dark' || (!stored && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    })();

    document.getElementById('theme-toggle').addEventListener('click', () => {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    /* ---- Back button ---- */
    (function initBackBtn() {
        const params = new URLSearchParams(window.location.search);
        const subject = params.get('back');
        const btn = document.getElementById('back-btn');
        if (subject) {
            btn.href = './index.php?openSubject=' + encodeURIComponent(subject);
        } else {
            btn.href = './index.php';
        }
    })();

    /* ---- Welcome banner ---- */
    function initWelcome(user) {
        const hour = new Date().getHours();
        const greeting = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
        const name = (user.displayName || user.email || 'there').split(' ')[0];
        document.getElementById('welcome-greeting').textContent = `${greeting}, ${name}! 👋`;

        const dateStr = new Date().toLocaleDateString('en-HK', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        document.getElementById('welcome-sub').textContent = dateStr;

        const tipIdx = new Date().getDate() % DSE_TIPS.length;
        document.getElementById('dse-tip').textContent = ' ' + DSE_TIPS[tipIdx];
    }

    /* ---- Stats ---- */
    function renderStats(sessions) {
        const now = Date.now();
        const weekAgo = now - 7 * 24 * 60 * 60 * 1000;

        const total = sessions.length;
        const thisWeek = sessions.filter(s => {
            const t = s.updated_at;
            if (!t) return false;
            const ms = typeof t.toMillis === 'function' ? t.toMillis() : (t.seconds ? t.seconds * 1000 : +new Date(t));
            return ms >= weekAgo;
        }).length;

        // Most used tool
        const toolCount = {};
        sessions.forEach(s => { if (s.tool) toolCount[s.tool] = (toolCount[s.tool] || 0) + 1; });
        const topTool = Object.keys(toolCount).sort((a,b) => toolCount[b]-toolCount[a])[0] || '–';

        // Unique subjects
        const subjects = new Set(sessions.map(s => s.subject).filter(Boolean));

        // SRS
        let srsCount = 0;
        try {
            const srs = JSON.parse(localStorage.getItem('edugenius_srs_v1') || '{}');
            srsCount = Object.keys(srs).length;
        } catch(e) {}

        const cards = [
            { icon:'comments', bg:'linear-gradient(135deg,#7c3aed,#5b21b6)', label:'Total AI Sessions', value:total, sub:'all time' },
            { icon:'calendar-week', bg:'linear-gradient(135deg,#06b6d4,#0284c7)', label:'This Week', value:thisWeek, sub:'last 7 days' },
            { icon:'star', bg:'linear-gradient(135deg,#f59e0b,#d97706)', label:'Most Used Tool', value:topTool, sub:'favourite tool', small:true },
            { icon:'globe', bg:'linear-gradient(135deg,#10b981,#059669)', label:'Subjects Explored', value:subjects.size, sub:srsCount + ' SRS words saved' },
        ];

        document.getElementById('stats-grid').innerHTML = cards.map((c, i) => `
            <div class="glass-card stat-card animate-in delay-${i+1}" role="listitem" aria-label="${escHtml(c.label)}: ${escHtml(String(c.value))}">
                <div class="stat-icon" style="background:${c.bg};" aria-hidden="true">
                    <i class="fas fa-${c.icon}"></i>
                </div>
                <div>
                    <div class="stat-label">${escHtml(c.label)}</div>
                    <div class="stat-value${c.small ? ' ' : ''}" style="${c.small && String(c.value).length > 8 ? 'font-size:1rem;' : ''}">${escHtml(String(c.value))}</div>
                    <div class="stat-sub">${escHtml(c.sub)}</div>
                </div>
            </div>
        `).join('');
    }

    /* ---- Tool chart ---- */
    function renderChart(sessions) {
        const toolCount = {};
        sessions.forEach(s => { if (s.tool) toolCount[s.tool] = (toolCount[s.tool] || 0) + 1; });
        const sorted = Object.entries(toolCount).sort((a,b) => b[1]-a[1]).slice(0,6);
        const max = sorted[0]?.[1] || 1;

        if (!sorted.length) {
            document.getElementById('tool-chart').innerHTML = `<div class="empty-state"><i class="fas fa-chart-bar"></i>No data yet</div>`;
            return;
        }

        const rows = sorted.map(([tool, count]) => {
            const pct = Math.max(4, Math.round((count / max) * 100));
            const color = TOOL_COLOR[tool] || '#7c3aed';
            return `
            <div class="bar-row" role="listitem" aria-label="${escHtml(tool)}: ${count} sessions">
                <div class="bar-label" title="${escHtml(tool)}">${escHtml(tool)}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="--w:${pct}%;background:${color};"></div>
                </div>
                <div class="bar-count" aria-hidden="true">${count}</div>
            </div>`;
        }).join('');

        document.getElementById('tool-chart').innerHTML = `<div class="bar-chart" role="list" aria-label="Tool usage chart">${rows}</div>`;
    }

    /* ---- Recent sessions ---- */
    function renderRecent(sessions) {
        const recent = sessions.slice(0, 5);
        const el = document.getElementById('recent-sessions');

        if (!recent.length) {
            el.innerHTML = `<div class="empty-state"><i class="fas fa-history"></i>No sessions yet</div>`;
            return;
        }

        el.innerHTML = recent.map(s => {
            const color   = TOOL_COLOR[s.tool] || '#7c3aed';
            const summary = (s.summary || 'No summary').substring(0, 60) + ((s.summary || '').length > 60 ? '…' : '');
            const timeStr = formatRelTime(s.updated_at);
            const subject = s.subject || '';
            const href    = './index.php?openSubject=' + encodeURIComponent(subject || '');

            return `
            <a class="session-item" href="${escHtml(href)}" aria-label="${escHtml(s.tool || '')} – ${escHtml(summary)}">
                <div class="session-dot" style="background:${color};" aria-hidden="true"></div>
                <div class="session-info">
                    <div class="session-tool-row">
                        <span class="badge" style="background:${color};">${escHtml(s.tool || 'AI')}</span>
                        ${subject ? `<span class="badge badge-subject">${escHtml(subject)}</span>` : ''}
                    </div>
                    <div class="session-summary">${escHtml(summary)}</div>
                </div>
                <div class="session-time" aria-label="Time: ${escHtml(timeStr)}">${escHtml(timeStr)}</div>
            </a>`;
        }).join('');
    }

    /* ---- Streak ---- */
    function updateStreak(sessions) {
        const today = getTodayStr();
        const hasToday = sessions.some(s => {
            const t = s.updated_at;
            if (!t) return false;
            const ms = typeof t.toMillis === 'function' ? t.toMillis() : (t.seconds ? t.seconds * 1000 : +new Date(t));
            return new Date(ms).toISOString().slice(0,10) === today;
        });

        let data = { lastDate: '', streak: 0 };
        try { data = JSON.parse(localStorage.getItem('edugenius_streak_v1') || '{}'); } catch(e) {}
        if (!data.streak) data.streak = 0;

        const yesterday = new Date(); yesterday.setDate(yesterday.getDate()-1);
        const yesterdayStr = yesterday.toISOString().slice(0,10);

        if (hasToday && data.lastDate !== today) {
            // New day with activity
            if (data.lastDate === yesterdayStr) {
                data.streak += 1;
            } else if (data.lastDate !== today) {
                data.streak = 1; // streak broken
            }
            data.lastDate = today;
            localStorage.setItem('edugenius_streak_v1', JSON.stringify(data));
        } else if (!hasToday && data.lastDate !== today && data.lastDate !== yesterdayStr) {
            // Streak broken
            data.streak = 0;
            localStorage.setItem('edugenius_streak_v1', JSON.stringify(data));
        }

        document.getElementById('streak-count').textContent = data.streak;
        const note = hasToday
            ? '✅ You studied today – keep it up!'
            : '📚 Study today to extend your streak!';
        document.getElementById('streak-note').textContent = note;
    }

    /* ---- SRS ---- */
    function renderSRS() {
        let srs = {};
        try { srs = JSON.parse(localStorage.getItem('edugenius_srs_v1') || '{}'); } catch(e) {}
        const total = Object.keys(srs).length;
        const todayStr = getTodayStr();
        let due = 0;
        Object.values(srs).forEach(w => {
            const nextReview = w.nextReview || w.next_review || null;
            if (!nextReview || nextReview <= todayStr) due++;
        });

        document.getElementById('srs-due').textContent   = due;
        document.getElementById('srs-total').textContent = `/ ${total} total`;
        const pct = total ? Math.round((due / total) * 100) : 0;
        document.getElementById('srs-bar').style.setProperty('--w', pct + '%');
    }

    /* ---- Quick launch ---- */
    function renderQuickLaunch() {
        document.getElementById('quick-grid').innerHTML = Object.entries(SUBJECT_MAP).map(([subject, cfg]) => `
            <a class="quick-btn" href="./index.php?openSubject=${encodeURIComponent(subject)}"
               aria-label="Launch ${escHtml(cfg.label)}">
                <div class="quick-icon" style="background:${cfg.color};" aria-hidden="true">
                    <i class="fas ${cfg.icon}"></i>
                </div>
                <span>${escHtml(cfg.label)}</span>
            </a>
        `).join('');
    }

    /* ---- Helpers ---- */
    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;')
            .replace(/'/g,'&#39;');
    }

    function getTodayStr() {
        return new Date().toISOString().slice(0,10);
    }

    function formatRelTime(t) {
        if (!t) return '';
        const ms = +new Date(t);
        const diff = Date.now() - ms;
        if (diff < 60000)   return 'Just now';
        if (diff < 3600000) return Math.floor(diff/60000) + 'm ago';
        if (diff < 86400000)return Math.floor(diff/3600000) + 'h ago';
        if (diff < 604800000)return Math.floor(diff/86400000) + 'd ago';
        return new Date(ms).toLocaleDateString('en-HK',{month:'short',day:'numeric'});
    }

    /* Exposed to Firebase module */
    window._dashboardInit = function(user, sessions) {
        initWelcome(user);
        renderStats(sessions);
        renderChart(sessions);
        renderRecent(sessions);
        updateStreak(sessions);
        renderSRS();
        renderQuickLaunch();
    };
</script>

<!-- Firebase module script -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
    import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

    const app  = initializeApp(window.__FIREBASE_CONFIG__);
    const auth = getAuth(app);
    const overlay = document.getElementById('auth-overlay');

    onAuthStateChanged(auth, async (user) => {
        if (!user) {
            window.location.replace('./index.php');
            return;
        }
        overlay.style.display = 'none';

        try {
            const token = await user.getIdToken();
            const res = await fetch('./api/history.php?limit=100', {
                headers: { 'Authorization': 'Bearer ' + token }
            });
            const data = await res.json();
            const sessions = data.docs || [];
            window._dashboardInit(user, sessions);
        } catch (err) {
            console.error('Failed to load history:', err);
            window._dashboardInit(user, []);
        }
    });
</script>

</body>
</html>
