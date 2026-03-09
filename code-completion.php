<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Interactive AI-powered Code Completion Exercise platform for HKDSE ICT students – practise Python coding with adaptive difficulty and instant AI feedback.">
    <title>Code Completion Exercise – EduGenius</title>

    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* ── Design System ─────────────────────────────────────────── */
        :root {
            --primary: #7c3aed; --primary-dark: #5b21b6;
            --secondary: #06b6d4; --accent-green: #10b981; --accent-amber: #f59e0b;
            --text-primary: #1e1b4b; --text-secondary: #64748b;
            --bg-main: #f0f2ff;
            --glass-bg: rgba(255,255,255,0.82); --glass-border: rgba(255,255,255,0.65);
            --shadow-glow: 0 0 40px rgba(124,58,237,0.10);
            --radius: 20px; --radius-sm: 12px;
            --mono: 'JetBrains Mono','Monaco','Menlo','Ubuntu Mono',monospace;
        }
        * { font-family: 'Plus Jakarta Sans','Inter','Segoe UI',Arial,sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn   { from{opacity:0} to{opacity:1} }
        @keyframes blankPulse { 0%,100%{box-shadow:0 0 0 3px rgba(124,58,237,.2)} 50%{box-shadow:0 0 0 6px rgba(124,58,237,.35)} }
        @keyframes slideDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
        @keyframes scoreReveal { from{opacity:0;transform:scale(.8)} to{opacity:1;transform:scale(1)} }
        @keyframes shimmer {
            0%   { background-position:-200% 0 }
            100% { background-position: 200% 0 }
        }

        body {
            min-height: 100vh; background-color: var(--bg-main);
            position: relative; overflow-x: hidden; color: var(--text-primary);
        }
        body::before {
            content:''; position:fixed; inset:0; pointer-events:none; z-index:0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.18) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(99,102,241,.10) 0%,transparent 55%);
        }

        /* ── Glass Card ─────────────────────────────────────────────── */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(24px) saturate(160%); -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid var(--glass-border); border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.08), var(--shadow-glow);
            position: relative; overflow: hidden;
        }
        .glass-card::before {
            content:''; position:absolute; top:0;left:0;right:0; height:1px;
            background: linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);
            pointer-events: none;
        }

        /* ── Buttons ─────────────────────────────────────────────────── */
        .btn-primary {
            background: linear-gradient(135deg,#7c3aed,#5b21b6); color:#fff!important; border:none;
            border-radius: var(--radius-sm); font-weight:700; transition:all .3s ease; cursor:pointer;
        }
        .btn-primary:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.42); }
        .btn-primary:disabled { opacity:.55; cursor:not-allowed; }
        .btn-success {
            background: linear-gradient(135deg,#10b981,#059669); color:#fff!important; border:none;
            border-radius: var(--radius-sm); font-weight:700; transition:all .3s ease; cursor:pointer;
        }
        .btn-success:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 20px rgba(16,185,129,.35); }
        .btn-success:disabled { opacity:.55; cursor:not-allowed; }
        .btn-back {
            background: var(--glass-bg); color:var(--primary); border:1px solid var(--glass-border);
            border-radius: var(--radius-sm); font-weight:600; transition:all .25s ease;
            backdrop-filter: blur(12px); display:inline-flex; align-items:center; gap:8px; cursor:pointer;
        }
        .btn-back:hover { border-color:rgba(124,58,237,.45); background:rgba(124,58,237,.06); }

        /* ── Inputs ──────────────────────────────────────────────────── */
        .input-field {
            background: var(--glass-bg); border: 1.5px solid var(--glass-border);
            border-radius: var(--radius-sm); color: var(--text-primary); transition: all .25s ease;
        }
        .input-field:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(124,58,237,.15); }
        .input-field::placeholder { color:var(--text-secondary); }

        /* ── Gradient Text ───────────────────────────────────────────── */
        .gradient-text {
            background: linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }

        /* ── Difficulty Pills ────────────────────────────────────────── */
        .diff-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: .45rem 1.1rem; border-radius: 99px; font-size: .82rem; font-weight: 700;
            border: 2px solid transparent; cursor: pointer; transition: all .2s ease; user-select:none;
        }
        .diff-pill.easy   { background:rgba(16,185,129,.10); color:#10b981; border-color:rgba(16,185,129,.25); }
        .diff-pill.medium { background:rgba(245,158,11,.10); color:#d97706; border-color:rgba(245,158,11,.25); }
        .diff-pill.hard   { background:rgba(239,68,68,.10);  color:#dc2626; border-color:rgba(239,68,68,.25);  }
        .diff-pill.easy.active   { background:#10b981; color:#fff; border-color:#10b981; box-shadow:0 4px 14px rgba(16,185,129,.35); }
        .diff-pill.medium.active { background:#d97706; color:#fff; border-color:#d97706; box-shadow:0 4px 14px rgba(245,158,11,.35); }
        .diff-pill.hard.active   { background:#dc2626; color:#fff; border-color:#dc2626; box-shadow:0 4px 14px rgba(239,68,68,.35);  }

        /* ── Workflow Steps ──────────────────────────────────────────── */
        .step-indicator {
            display: flex; align-items: center; gap: 0;
        }
        .step-dot {
            width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: .8rem; font-weight: 700; flex-shrink:0;
            transition: all .3s ease;
        }
        .step-dot.done    { background: linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .step-dot.active  { background: linear-gradient(135deg,var(--primary),#5b21b6); color:#fff; animation:blankPulse 2s infinite; }
        .step-dot.pending { background: rgba(100,116,139,.15); color:var(--text-secondary); }
        .step-line { flex:1; height:2px; background:rgba(124,58,237,.15); margin:0 .25rem; min-width:16px; }
        .step-line.done { background:linear-gradient(90deg,#10b981,#059669); }

        /* ── Exercise Code Display ───────────────────────────────────── */
        .code-paper {
            background: #0d0d1a; border-radius: 16px;
            padding: 1.5rem; font-family: var(--mono); font-size: .875rem;
            line-height: 1.85; color: #cdd6f4; overflow-x: auto;
            border: 1px solid rgba(124,58,237,.25);
            box-shadow: 0 8px 32px rgba(0,0,0,.25), inset 0 1px 0 rgba(255,255,255,.04);
            position: relative; white-space: pre;
        }
        .code-paper::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg,#7c3aed,#06b6d4,#10b981);
            border-radius: 16px 16px 0 0;
        }
        .code-line-num {
            display: inline-block; width: 2.2rem; text-align: right;
            color: rgba(100,116,139,.5); font-size: .75rem; margin-right: .85rem; user-select:none;
        }
        .code-blank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(124,58,237,.25); border: 1.5px dashed rgba(124,58,237,.6);
            color: #a78bfa; border-radius: 6px; padding: .05rem .6rem;
            font-weight: 700; font-size: .82rem; vertical-align: baseline;
            min-width: 2.5rem; cursor: default; transition: all .2s;
        }
        .code-blank-badge:hover, .code-blank-badge.active {
            background: rgba(124,58,237,.38); border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,.18);
        }

        /* ── Answer Sheet ────────────────────────────────────────────── */
        .answer-sheet {
            background: rgba(255,255,255,.93); border-radius: 16px;
            border: 1.5px solid rgba(124,58,237,.18);
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
        }
        .answer-row {
            display: flex; align-items: flex-start; gap: .85rem; padding: .9rem 1.1rem;
            border-bottom: 1px solid rgba(124,58,237,.07); transition: background .15s;
        }
        .answer-row:last-of-type { border-bottom: none; }
        .answer-row:hover { background: rgba(124,58,237,.025); }
        .blank-num-circle {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg,#7c3aed,#06b6d4); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 800; flex-shrink: 0; margin-top: .15rem;
        }
        .blank-input {
            flex: 1; padding: .55rem .85rem; font-family: var(--mono); font-size: .875rem;
            border: 1.5px solid rgba(124,58,237,.22); border-radius: 10px;
            background: rgba(255,255,255,.9); color: var(--text-primary);
            transition: all .25s ease; outline: none;
        }
        .blank-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(124,58,237,.15); }
        .blank-input.correct { border-color: #10b981; background: rgba(16,185,129,.08); }
        .blank-input.wrong   { border-color: #ef4444; background: rgba(239,68,68,.08); }
        .blank-input::placeholder { color: var(--text-secondary); font-family:'Plus Jakarta Sans',sans-serif; font-size:.8rem; }

        /* ── Progress Bar ────────────────────────────────────────────── */
        .progress-bar-track { background:rgba(124,58,237,.12); border-radius:99px; height:6px; overflow:hidden; }
        .progress-bar-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg,#7c3aed,#06b6d4); transition:width .4s cubic-bezier(.16,1,.3,1); }

        /* ── Results Panel ───────────────────────────────────────────── */
        .result-card {
            border-radius: 14px; padding: 1.1rem 1.25rem;
            animation: slideDown .4s cubic-bezier(.16,1,.3,1) both;
            border: 1.5px solid transparent; margin-bottom: .75rem;
        }
        .result-card.correct {
            background: rgba(16,185,129,.07); border-color: rgba(16,185,129,.35);
        }
        .result-card.wrong {
            background: rgba(239,68,68,.05); border-color: rgba(239,68,68,.30);
        }
        .result-card.partial {
            background: rgba(245,158,11,.06); border-color: rgba(245,158,11,.35);
        }
        .result-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .result-icon.correct { background:rgba(16,185,129,.18); }
        .result-icon.wrong   { background:rgba(239,68,68,.15); }
        .result-icon.partial { background:rgba(245,158,11,.18); }

        /* ── Score Card ──────────────────────────────────────────────── */
        .score-card {
            border-radius: 20px; padding: 2rem; text-align: center;
            animation: scoreReveal .5s cubic-bezier(.16,1,.3,1) both;
        }
        .score-ring {
            width: 90px; height: 90px; border-radius: 50%;
            background: linear-gradient(135deg,var(--primary),var(--secondary));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto .85rem; box-shadow: 0 8px 28px rgba(124,58,237,.38);
        }
        .score-num { font-size: 1.6rem; font-weight: 800; color: #fff; }

        /* ── Teacher Commentary ──────────────────────────────────────── */
        .teacher-box {
            background: linear-gradient(135deg, rgba(124,58,237,.06), rgba(6,182,212,.06));
            border: 1.5px solid rgba(124,58,237,.18); border-radius: 14px; padding: 1.25rem;
        }
        .teacher-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg,#7c3aed,#06b6d4);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; flex-shrink:0;
        }

        /* ── Section Label ───────────────────────────────────────────── */
        .section-label { font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--text-secondary); margin-bottom:6px; display:block; }

        /* ── Misc Utilities ──────────────────────────────────────────── */
        .animate-fadeInUp  { animation: fadeInUp .6s cubic-bezier(.16,1,.3,1) both; }
        .animate-fadeIn    { animation: fadeIn .4s ease both; }
        button:not(:disabled):active { transform: scale(0.97); }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; } }
        button:focus-visible, a:focus-visible, input:focus-visible { outline:3px solid rgba(124,58,237,.5); outline-offset:2px; }

        /* ── Hint Tooltip ────────────────────────────────────────────── */
        .hint-chip {
            display: inline-flex; align-items: center; gap: 4px;
            background: rgba(245,158,11,.12); color: #d97706; border: 1px solid rgba(245,158,11,.3);
            border-radius: 8px; padding: .15rem .55rem; font-size: .73rem; font-weight: 600; cursor:default;
        }

        /* ── Shimmer Loading ─────────────────────────────────────────── */
        .shimmer {
            background: linear-gradient(90deg, rgba(124,58,237,.07) 25%, rgba(124,58,237,.18) 50%, rgba(124,58,237,.07) 75%);
            background-size: 200% 100%; animation: shimmer 1.4s infinite;
            border-radius: 8px; height: 1rem; margin: .35rem 0;
        }

        /* ── Sample Topic Chips ──────────────────────────────────────── */
        .topic-chip {
            display: inline-flex; align-items: center; padding: .3rem .8rem;
            background: rgba(124,58,237,.09); color: var(--primary);
            border: 1px solid rgba(124,58,237,.22); border-radius: 99px;
            font-size: .78rem; font-weight: 600; cursor: pointer; transition: all .2s;
        }
        .topic-chip:hover { background: rgba(124,58,237,.18); border-color: var(--primary); transform: translateY(-1px); }

        /* ── Dark Mode ───────────────────────────────────────────────── */
        .dark {
            --primary: #a78bfa; --primary-dark: #7c3aed; --secondary: #22d3ee;
            --text-primary: #e2e8f0; --text-secondary: #94a3b8;
            --bg-main: #0d0d1a;
            --glass-bg: rgba(15,15,40,0.85); --glass-border: rgba(255,255,255,0.08);
            --shadow-glow: 0 0 60px rgba(124,58,237,0.18);
        }
        .dark body { background-color: var(--bg-main); color: var(--text-primary); }
        .dark body::before {
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%, rgba(124,58,237,.32) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.22) 0%,transparent 55%);
        }
        .dark .glass-card       { background: var(--glass-bg); border-color: var(--glass-border); }
        .dark .input-field      { background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.15); color: #e2e8f0; }
        .dark .input-field::placeholder { color: #64748b; }
        .dark .btn-back         { background: rgba(15,15,40,.8); color: var(--primary); }
        .dark .answer-sheet     { background: rgba(15,15,40,.9); border-color: rgba(124,58,237,.3); }
        .dark .answer-row:hover { background: rgba(124,58,237,.06); }
        .dark .blank-input      { background: rgba(15,15,40,.7); border-color: rgba(124,58,237,.35); color: #e2e8f0; }
        .dark .blank-input.correct { background: rgba(16,185,129,.12); }
        .dark .blank-input.wrong   { background: rgba(239,68,68,.12); }
        .dark .teacher-box      { background: linear-gradient(135deg,rgba(124,58,237,.12),rgba(6,182,212,.12)); }
        .dark .topic-chip       { background: rgba(124,58,237,.18); color: var(--primary); }
        .dark .diff-pill.easy   { background: rgba(16,185,129,.15); }
        .dark .diff-pill.medium { background: rgba(245,158,11,.15); }
        .dark .diff-pill.hard   { background: rgba(239,68,68,.15); }

        /* ── Theme Toggle Icons ──────────────────────────────────────── */
        .theme-moon { display: inline; }
        .theme-sun  { display: none; }
        .dark .theme-moon { display: none; }
        .dark .theme-sun  { display: inline; }

        /* ── Mobile ──────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .exercise-grid { grid-template-columns: 1fr !important; }
        }

        /* ── Code Completion History Panel ──────────────────────────── */
        #ccHistoryBackdrop {
            display:none; position:fixed; inset:0; z-index:900;
            background:rgba(0,0,0,.45); backdrop-filter:blur(4px);
            opacity:0; transition:opacity .3s ease;
        }
        #ccHistoryBackdrop.open { opacity:1; }
        #ccHistoryPanel {
            position:fixed; top:0; right:0; bottom:0; z-index:901;
            width:min(480px,100vw);
            background:var(--glass-bg,rgba(255,255,255,.95));
            border-left:1px solid var(--glass-border,rgba(255,255,255,.65));
            box-shadow:-8px 0 32px rgba(0,0,0,.18);
            display:flex; flex-direction:column;
            transform:translateX(100%);
            transition:transform .35s cubic-bezier(.16,1,.3,1);
            overflow:hidden;
        }
        #ccHistoryPanel.open { transform:translateX(0); }
        #ccHistoryPanelHeader {
            display:flex; align-items:center; justify-content:space-between;
            padding:1rem 1.25rem;
            background:var(--glass-bg,rgba(255,255,255,.95));
            border-bottom:1px solid var(--glass-border,rgba(255,255,255,.65));
            flex-shrink:0;
        }
        #ccHistoryPanelBody { flex:1; overflow-y:auto; padding:1rem; }
        #ccHistoryPanelBody::-webkit-scrollbar { width:4px; }
        #ccHistoryPanelBody::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
        .cch-card {
            background:var(--glass-bg,rgba(255,255,255,.82));
            border:1px solid var(--glass-border,rgba(255,255,255,.65));
            border-radius:12px; margin-bottom:.75rem; overflow:hidden;
            animation:fadeInUp .3s ease both;
        }
        .cch-card-header {
            display:flex; align-items:center; gap:.75rem;
            padding:.85rem 1rem; cursor:pointer; transition:background .2s;
        }
        .cch-card-header:hover { background:rgba(124,58,237,.06); }
        .dark #ccHistoryPanel { background:rgba(15,15,40,.95); border-left-color:rgba(255,255,255,.08); }
        .dark #ccHistoryPanelHeader { background:rgba(15,15,40,.85); border-bottom-color:rgba(255,255,255,.08); }
        .dark .cch-card { background:rgba(30,20,60,.8); border-color:rgba(255,255,255,.08); }
    </style>
</head>
<?= firebaseConfigScript() ?>
<body class="transition-all duration-300">

    <!-- ── Auth Guard Overlay ───────────────────────────────────────── -->
    <div id="auth-guard-overlay" style="position:fixed;inset:0;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(15,15,40,.96);backdrop-filter:blur(8px);">
        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(124,58,237,.3);border-radius:20px;padding:2.5rem 2rem;max-width:360px;width:90%;text-align:center;">
            <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <p style="color:#fff;font-size:1.15rem;font-weight:700;margin:0 0 .5rem;">Login Required</p>
            <p style="color:rgba(255,255,255,.65);font-size:.9rem;margin:0 0 1.5rem;">Please sign in to use this tool.</p>
            <a href="./index.php" style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;text-decoration:none;padding:.65rem 1.75rem;border-radius:12px;font-weight:600;font-size:.95rem;">Go to Login</a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 relative z-10" style="max-width:1100px">

        <!-- ── Top Navigation Bar ───────────────────────────────────── -->
        <div class="flex items-center justify-between mb-6 animate-fadeInUp">
            <a id="back-btn-link" href="./coding.php" class="btn-back px-4 py-2 text-sm" aria-label="Back to Code Review">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="flex items-center gap-2">
                <button onclick="openCCHistory()" title="View History" aria-label="View history"
                        style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:.95rem;transition:all .2s">
                    <i class="fas fa-history"></i>
                </button>
                <button id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode"
                        style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:1rem;transition:all .2s">
                    <span class="theme-moon">🌙</span><span class="theme-sun">☀️</span>
                </button>
            </div>
        </div>

        <!-- ── Page Header ────────────────────────────────────────────── -->
        <div class="glass-card p-8 mb-8 text-center animate-fadeInUp">
            <div class="flex items-center justify-center gap-4 mb-3">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden" style="background:linear-gradient(135deg,#10b981,#059669)">
                    <i class="fas fa-puzzle-piece text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold"><span class="gradient-text">Code Completion</span></h1>
                    <p class="text-sm font-semibold" style="color:var(--text-secondary)">AI-Powered Interactive Exercise Platform</p>
                </div>
            </div>
            <p class="text-base mb-5" style="color:var(--text-secondary);max-width:600px;margin-left:auto;margin-right:auto">
                Fill in the missing code blanks, then let the AI mark your answers and explain exactly what each blank means — just like a real exam, with instant teacher feedback.
            </p>
            <!-- Step indicator -->
            <div class="step-indicator justify-center max-w-xs mx-auto" id="step-indicator">
                <div class="step-dot active" id="step1-dot">1</div>
                <div class="step-line"       id="step12-line"></div>
                <div class="step-dot pending" id="step2-dot">2</div>
                <div class="step-line"       id="step23-line"></div>
                <div class="step-dot pending" id="step3-dot">3</div>
            </div>
            <div class="flex justify-center gap-12 mt-2 text-xs font-semibold" style="color:var(--text-secondary)">
                <span id="step1-label">Setup</span>
                <span id="step2-label">Answer</span>
                <span id="step3-label">Results</span>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- STEP 1 — Setup Panel                                        -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div id="setup-panel" class="glass-card p-7 mb-6 animate-fadeInUp">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#7c3aed,#5b21b6)">
                    <i class="fas fa-sliders-h text-white text-sm"></i>
                </div>
                <h2 class="text-lg font-extrabold" style="color:var(--text-primary)">Exercise Setup</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left: Difficulty + Topic -->
                <div>
                    <!-- Difficulty -->
                    <span class="section-label"><i class="fas fa-layer-group mr-1" style="color:var(--primary)"></i>Difficulty Level</span>
                    <div class="flex flex-wrap gap-2 mb-5" id="diff-group">
                        <button class="diff-pill easy active" onclick="setDifficulty('easy',this)"   data-diff="easy">
                            <i class="fas fa-circle" style="font-size:.55rem"></i> Easy
                        </button>
                        <button class="diff-pill medium"      onclick="setDifficulty('medium',this)" data-diff="medium">
                            <i class="fas fa-circle" style="font-size:.55rem"></i> Medium
                        </button>
                        <button class="diff-pill hard"        onclick="setDifficulty('hard',this)"   data-diff="hard">
                            <i class="fas fa-circle" style="font-size:.55rem"></i> Hard
                        </button>
                    </div>
                    <div id="diff-desc" class="text-xs mb-5 p-3 rounded-xl" style="background:rgba(16,185,129,.07);color:var(--text-secondary);border:1px solid rgba(16,185,129,.2)">
                        <strong style="color:#10b981">🟢 Easy:</strong> 2–3 simple blanks — variable names, basic operators and simple return values. Perfect for beginners.
                    </div>

                    <!-- Topic -->
                    <span class="section-label"><i class="fas fa-book mr-1" style="color:var(--primary)"></i>Topic</span>
                    <div class="flex gap-2 mb-3">
                        <input type="text" id="topic-input" class="input-field flex-1 px-4 py-2.5 text-sm"
                               placeholder="e.g. linear search, bubble sort, file handling…"
                               onkeydown="if(event.key==='Enter')generateExercise()">
                        <button onclick="generateExercise()" id="gen-btn" class="btn-primary py-2.5 px-5 text-sm rounded-xl">
                            <i class="fas fa-bolt mr-2"></i>Generate
                        </button>
                    </div>
                    <p class="text-xs mb-1" style="color:var(--text-secondary)">Quick topics (click to use):</p>
                    <div class="flex flex-wrap gap-1.5" id="topic-chips">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Right: How it works -->
                <div class="rounded-2xl p-5" style="background:rgba(124,58,237,.04);border:1.5px solid rgba(124,58,237,.12)">
                    <p class="font-bold text-sm mb-3" style="color:var(--text-primary)">
                        <i class="fas fa-chalkboard-teacher mr-2" style="color:var(--primary)"></i>How It Works
                    </p>
                    <ol class="space-y-2.5 text-sm" style="color:var(--text-secondary);list-style:none;padding:0">
                        <li class="flex items-start gap-2">
                            <span style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;flex-shrink:0">1</span>
                            <span>Choose a <strong>difficulty</strong> and type a Python <strong>topic</strong>, then click <em>Generate</em>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;flex-shrink:0">2</span>
                            <span>The AI creates a code snippet with numbered <span style="background:rgba(124,58,237,.2);padding:.05rem .4rem;border-radius:4px;font-family:monospace;color:#7c3aed;font-weight:700">[1]</span> blanks. Fill each blank in the <em>Answer Sheet</em>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;flex-shrink:0">3</span>
                            <span>Click <em>Submit for AI Marking</em> — the AI checks every blank, explains any mistakes, and gives you teacher-style feedback.</span>
                        </li>
                    </ol>
                    <div class="mt-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2" style="background:rgba(6,182,212,.08);color:#0891b2;border:1px solid rgba(6,182,212,.2)">
                        <i class="fas fa-robot"></i> AI evaluates semantic correctness — not just exact text matching!
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- STEP 2 — Exercise Panel (hidden until generated)            -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div id="exercise-panel" class="mb-6" style="display:none">

            <!-- Exercise meta bar -->
            <div class="glass-card px-5 py-3 mb-4 flex items-center justify-between flex-wrap gap-3 animate-fadeInUp">
                <div class="flex items-center gap-3 flex-wrap">
                    <span id="ex-diff-badge" class="diff-pill easy" style="font-size:.75rem;padding:.3rem .85rem">🟢 Easy</span>
                    <span id="ex-title" class="text-sm font-bold" style="color:var(--text-primary)">Exercise Title</span>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Blank fill progress -->
                    <div class="flex items-center gap-2 text-xs font-semibold" style="color:var(--text-secondary)">
                        <i class="fas fa-pencil-alt" style="color:var(--primary);font-size:.75rem"></i>
                        <span id="fill-counter">0 / 0 blanks filled</span>
                    </div>
                    <button onclick="resetExercise()" class="btn-back text-xs px-3 py-1.5" title="Generate a new exercise">
                        <i class="fas fa-redo-alt mr-1" style="font-size:.7rem"></i>New Exercise
                    </button>
                </div>
            </div>
            <!-- Progress bar -->
            <div class="progress-bar-track mb-4" id="fill-progress-bar">
                <div class="progress-bar-fill" id="fill-progress-fill" style="width:0%"></div>
            </div>

            <!-- 2-column grid: Code Paper + Answer Sheet -->
            <div class="grid lg:grid-cols-5 gap-5 exercise-grid">

                <!-- ── Left: Question Paper ─────────────────────────── -->
                <div class="lg:col-span-3 animate-fadeInUp">
                    <div class="glass-card p-5 h-full">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(16,185,129,.12)">
                                <i class="fas fa-file-code text-sm" style="color:#10b981"></i>
                            </div>
                            <h3 class="font-extrabold text-sm" style="color:var(--text-primary)">📄 Question Paper</h3>
                        </div>

                        <!-- Description -->
                        <p id="ex-description" class="text-sm mb-4" style="color:var(--text-secondary);line-height:1.6"></p>

                        <!-- Code display -->
                        <div class="code-paper" id="code-display" aria-label="Code exercise with numbered blanks"></div>

                        <!-- Learning objectives -->
                        <div id="ex-objectives" class="mt-4 p-3 rounded-xl text-xs" style="background:rgba(124,58,237,.05);border:1px solid rgba(124,58,237,.12)">
                            <p class="font-bold mb-1.5" style="color:var(--primary)"><i class="fas fa-bullseye mr-1.5"></i>Learning Objectives</p>
                            <ul id="objectives-list" class="space-y-1" style="color:var(--text-secondary);list-style:none;padding:0"></ul>
                        </div>
                    </div>
                </div>

                <!-- ── Right: Answer Sheet ─────────────────────────── -->
                <div class="lg:col-span-2 animate-fadeInUp">
                    <div class="answer-sheet h-full flex flex-col">
                        <!-- Header -->
                        <div class="px-4 py-3.5 border-b" style="border-color:rgba(124,58,237,.12)">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(124,58,237,.12)">
                                    <i class="fas fa-pencil-alt text-sm" style="color:var(--primary)"></i>
                                </div>
                                <h3 class="font-extrabold text-sm" style="color:var(--text-primary)">✍️ Answer Sheet</h3>
                            </div>
                            <p class="text-xs mt-1" style="color:var(--text-secondary)">Fill in the code for each numbered blank above.</p>
                        </div>

                        <!-- Blank inputs (populated by JS) -->
                        <div id="answer-rows" class="flex-1 overflow-y-auto"></div>

                        <!-- Footer: submit button -->
                        <div class="px-4 py-4 border-t" style="border-color:rgba(124,58,237,.12)">
                            <button id="submit-btn" onclick="submitForMarking()" class="btn-success w-full py-3 text-sm rounded-xl" disabled>
                                <i class="fas fa-robot mr-2"></i>Submit for AI Marking
                            </button>
                            <p class="text-xs mt-2 text-center" style="color:var(--text-secondary)">
                                <i class="fas fa-info-circle mr-1"></i>AI checks semantic correctness &amp; explains every mistake
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- STEP 3 — Results Panel (hidden until marked)                -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div id="results-panel" class="mb-8" style="display:none">
            <div class="glass-card p-7 animate-fadeInUp">

                <!-- Score header -->
                <div id="score-section" class="mb-8"></div>

                <!-- Per-blank results -->
                <div class="mb-6">
                    <h3 class="font-extrabold text-base mb-4" style="color:var(--text-primary)">
                        <i class="fas fa-clipboard-check mr-2" style="color:var(--primary)"></i>Detailed Marking — Blank by Blank
                    </h3>
                    <div id="result-cards"></div>
                </div>

                <!-- Teacher Commentary -->
                <div id="teacher-commentary-section" class="mb-6"></div>

                <!-- Next Steps -->
                <div id="next-steps-section"></div>

                <!-- Action buttons -->
                <div class="flex flex-wrap gap-3 mt-6 pt-5 border-t" style="border-color:rgba(124,58,237,.12)">
                    <button onclick="tryAgain()" class="btn-primary py-2.5 px-6 text-sm rounded-xl">
                        <i class="fas fa-redo mr-2"></i>Try Same Exercise Again
                    </button>
                    <button onclick="resetExercise()" class="btn-success py-2.5 px-6 text-sm rounded-xl">
                        <i class="fas fa-plus mr-2"></i>Generate New Exercise
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-4 text-xs" style="color:var(--text-secondary)">
            <p><i class="fas fa-copyright mr-1"></i>Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
        </div>
    </div><!-- /container -->


    <!-- ── JavaScript ────────────────────────────────────────────────── -->
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script>

    <script>
    /* ══════════════════════════════════════════════════════════════════
       API Configuration
    ══════════════════════════════════════════════════════════════════ */
    const FALLBACK_MODELS = ['gemini-search', 'gemini-fast', 'glm', 'openai-fast', 'openai', 'deepseek'];

    async function callAI(prompt) {
        let lastErr;
        const token = window._fbUser ? await window._fbUser.getIdToken() : null;
        for (const model of FALLBACK_MODELS) {
            try {
                const resp = await fetch('./api/ai_proxy.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token
                    },
                    body: JSON.stringify({
                        subject: 'ICT', mode: 'code_completion',
                        model, stream: false, temperature: 0.65,
                        max_tokens: 8192,
                        messages: [{ role: 'user', content: prompt }]
                    })
                });
                if (!resp.ok) { lastErr = new Error(`HTTP ${resp.status}`); continue; }
                const data = await resp.json();
                const text = data?.choices?.[0]?.message?.content;
                if (text) return text;
                lastErr = new Error('Empty response');
            } catch (e) { lastErr = e; }
        }
        throw lastErr || new Error('All AI models failed');
    }

    /* ══════════════════════════════════════════════════════════════════
       State
    ══════════════════════════════════════════════════════════════════ */
    let currentDifficulty = 'easy';
    let currentExercise   = null;   // parsed JSON from AI
    let markingResult     = null;   // parsed JSON from AI marking

    /* ══════════════════════════════════════════════════════════════════
       Sample Topics
    ══════════════════════════════════════════════════════════════════ */
    const SAMPLE_TOPICS = [
        'linear search', 'binary search', 'bubble sort', 'selection sort',
        'insertion sort', 'file handling', 'function definition', 'stack operations',
        'queue operations', '2D list', 'string processing', 'while loop',
        'for loop', 'recursion', 'dictionary operations'
    ];
    (function buildTopicChips() {
        const c = document.getElementById('topic-chips');
        if (!c) return;
        c.innerHTML = SAMPLE_TOPICS.map(t =>
            `<button class="topic-chip" onclick="selectTopic('${t.replace(/'/g,"\\'")}')">${t}</button>`
        ).join('');
    })();

    function selectTopic(t) {
        document.getElementById('topic-input').value = t;
        document.getElementById('topic-input').focus();
    }

    /* ══════════════════════════════════════════════════════════════════
       Difficulty
    ══════════════════════════════════════════════════════════════════ */
    const DIFF_CONFIG = {
        easy: {
            badge: '🟢 Easy',
            badgeClass: 'easy',
            desc: '<strong style="color:#10b981">🟢 Easy:</strong> 2–3 simple blanks — variable names, basic operators and simple return values. Perfect for beginners.',
            descStyle: 'background:rgba(16,185,129,.07);color:var(--text-secondary);border:1px solid rgba(16,185,129,.2)',
            prompt: '2 to 3 blanks. Keep blanks very simple: single variable names, basic arithmetic operators, simple string or number literals, or straightforward return values. No complex expressions.'
        },
        medium: {
            badge: '🟡 Medium',
            badgeClass: 'medium',
            desc: '<strong style="color:#d97706">🟡 Medium:</strong> 3–5 blanks of moderate complexity — function calls, list operations, loop conditions and basic logic.',
            descStyle: 'background:rgba(245,158,11,.07);color:var(--text-secondary);border:1px solid rgba(245,158,11,.2)',
            prompt: '3 to 5 blanks of moderate difficulty: simple function calls (e.g. len(lst), range(n)), list indexing, comparisons, or loop conditions. Each blank should be a short expression (1–4 tokens).'
        },
        hard: {
            badge: '🔴 Hard',
            badgeClass: 'hard',
            desc: '<strong style="color:#dc2626">🔴 Hard:</strong> 5–7 blanks requiring deeper understanding — algorithm logic, nested conditions, multiple operations and compound expressions.',
            descStyle: 'background:rgba(239,68,68,.07);color:var(--text-secondary);border:1px solid rgba(239,68,68,.2)',
            prompt: '5 to 7 blanks that require deeper understanding: compound conditions, nested function calls, algorithm-specific logic (e.g. mid = (low + high) // 2), multi-step expressions, or subtle Python idioms.'
        }
    };

    function setDifficulty(diff, btn) {
        currentDifficulty = diff;
        // Update pill styling
        document.querySelectorAll('.diff-pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        // Update description box
        const cfg = DIFF_CONFIG[diff];
        const box = document.getElementById('diff-desc');
        box.innerHTML  = cfg.desc;
        box.style.cssText += ';' + cfg.descStyle;
    }

    /* ══════════════════════════════════════════════════════════════════
       Step Indicator
    ══════════════════════════════════════════════════════════════════ */
    function goToStep(n) {
        for (let i = 1; i <= 3; i++) {
            const dot  = document.getElementById(`step${i}-dot`);
            const lbl  = document.getElementById(`step${i}-label`);
            if (i < n) {
                dot.className = 'step-dot done'; dot.innerHTML = '<i class="fas fa-check" style="font-size:.6rem"></i>';
            } else if (i === n) {
                dot.className = 'step-dot active'; dot.textContent = String(i);
            } else {
                dot.className = 'step-dot pending'; dot.textContent = String(i);
            }
        }
        if (n > 1) { document.getElementById('step12-line').className = 'step-line done'; }
        else       { document.getElementById('step12-line').className = 'step-line'; }
        if (n > 2) { document.getElementById('step23-line').className = 'step-line done'; }
        else       { document.getElementById('step23-line').className = 'step-line'; }
    }

    /* ══════════════════════════════════════════════════════════════════
       Generate Exercise  (Step 1 → Step 2)
    ══════════════════════════════════════════════════════════════════ */
    async function generateExercise() {
        const topic = (document.getElementById('topic-input').value.trim()) || 'linear search';
        const cfg   = DIFF_CONFIG[currentDifficulty];

        // Button loading state
        const btn = document.getElementById('gen-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating…';

        // Hide previous results
        document.getElementById('results-panel').style.display  = 'none';
        document.getElementById('exercise-panel').style.display = 'none';

        const prompt = `You are an experienced Hong Kong DSE ICT teacher creating a Python code completion worksheet.

Topic: "${topic}"
Difficulty: ${currentDifficulty.toUpperCase()} — ${cfg.prompt}

IMPORTANT RULES:
- Use ONLY Python constructs from the HKDSE ICT syllabus (no lambdas, map/filter, list comprehensions, etc.).
- Replace each blank with a placeholder that looks like: [BLANK_1], [BLANK_2], [BLANK_3], etc.
- Every [BLANK_N] must be listed in the "blanks" array with the same position number.
- The "codeWithBlanks" field must be valid Python when all blanks are filled.
- Keep the code short: 8-20 lines total.
- Do NOT put blanks inside comments.

Return ONLY valid JSON (no markdown fences, no extra text):
{
  "title": "Short exercise title",
  "description": "One or two sentences describing what this code does and what the student needs to complete.",
  "codeWithBlanks": "def example(lst):\\n    result = [BLANK_1]\\n    for i in [BLANK_2]:\\n        result.append(lst[i])\\n    return result",
  "blanks": [
    { "position": 1, "answer": "[]", "hint": "Initialise an empty list", "concept": "List initialisation" },
    { "position": 2, "answer": "range(len(lst))", "hint": "Iterate over every valid index", "concept": "range() and len()" }
  ],
  "learningObjectives": [
    "Understand how to initialise and build a list",
    "Apply range() and len() for iteration"
  ],
  "explanation": "This function copies all elements from lst into a new list. Understanding list initialisation and index-based iteration are core HKDSE ICT skills."
}`;

        try {
            const raw = await callAI(prompt);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bolt mr-2"></i>Generate';

            // Extract JSON
            let parsed = null;
            try {
                const m = raw.match(/\{[\s\S]*\}/);
                if (m) parsed = JSON.parse(m[0]);
            } catch (_) {}

            if (!parsed?.codeWithBlanks || !Array.isArray(parsed?.blanks) || parsed.blanks.length === 0) {
                alert('Could not parse the AI response. Please try again.');
                return;
            }

            currentExercise = parsed;
            markingResult   = null;
            renderExercise(parsed);
            goToStep(2);
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bolt mr-2"></i>Generate';
            alert('Generation failed: ' + err.message);
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       Render Exercise
    ══════════════════════════════════════════════════════════════════ */
    function esc(s) {
        return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function renderExercise(ex) {
        const cfg = DIFF_CONFIG[currentDifficulty];

        // Meta bar
        const badge = document.getElementById('ex-diff-badge');
        badge.textContent  = cfg.badge;
        badge.className    = `diff-pill ${cfg.badgeClass} active`;
        document.getElementById('ex-title').textContent = ex.title || 'Code Completion Exercise';
        document.getElementById('ex-description').textContent = ex.description || '';

        // Code display with numbered blanks
        const codeLines = (ex.codeWithBlanks || '').split('\n');
        const codeHtml  = codeLines.map((line, li) => {
            const escapedLine = esc(line).replace(/\[BLANK_(\d+)\]/g, (_, n) =>
                `<span class="code-blank-badge" id="badge-blank-${n}" title="Blank ${n}">[${n}]</span>`
            );
            return `<span class="code-line-num">${li + 1}</span>${escapedLine}`;
        }).join('\n');
        document.getElementById('code-display').innerHTML = codeHtml;

        // Learning objectives
        const objList = document.getElementById('objectives-list');
        if (Array.isArray(ex.learningObjectives) && ex.learningObjectives.length) {
            objList.innerHTML = ex.learningObjectives.map(o =>
                `<li class="flex items-start gap-1.5"><i class="fas fa-check-circle" style="color:#10b981;margin-top:.2rem;font-size:.7rem;flex-shrink:0"></i><span>${esc(o)}</span></li>`
            ).join('');
            document.getElementById('ex-objectives').style.display = '';
        } else {
            document.getElementById('ex-objectives').style.display = 'none';
        }

        // Answer rows
        const rowsEl = document.getElementById('answer-rows');
        rowsEl.innerHTML = ex.blanks.map((b, i) => `
            <div class="answer-row" id="answer-row-${i}">
                <div class="blank-num-circle">${b.position}</div>
                <div style="flex:1">
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="text-xs font-bold" style="color:var(--text-primary)">Blank ${b.position}</span>
                        <span class="hint-chip"><i class="fas fa-lightbulb" style="font-size:.6rem"></i>${esc(b.hint || '')}</span>
                        <span class="text-xs" style="color:var(--text-secondary);font-style:italic">${esc(b.concept || '')}</span>
                    </div>
                    <input type="text" class="blank-input" id="blank-input-${i}"
                           placeholder="Type your answer here…"
                           aria-label="Answer for blank ${b.position}"
                           oninput="onBlankInput()"
                           onkeydown="if(event.key==='Enter'){event.preventDefault();focusNextBlank(${i})}">
                </div>
            </div>`
        ).join('');

        // Reset submit button
        document.getElementById('submit-btn').disabled = true;

        // Show panels
        document.getElementById('exercise-panel').style.display = '';
        document.getElementById('results-panel').style.display  = 'none';

        updateFillProgress();

        // Smooth scroll
        setTimeout(() => document.getElementById('exercise-panel').scrollIntoView({ behavior:'smooth', block:'start' }), 100);
    }

    function focusNextBlank(currentIndex) {
        const next = document.getElementById(`blank-input-${currentIndex + 1}`);
        if (next) next.focus();
        else document.getElementById('submit-btn').focus();
    }

    function onBlankInput() {
        updateFillProgress();
        // Highlight corresponding code badge
        if (!currentExercise) return;
        currentExercise.blanks.forEach((b, i) => {
            const inp  = document.getElementById(`blank-input-${i}`);
            const badge = document.getElementById(`badge-blank-${b.position}`);
            if (badge) badge.classList.toggle('active', !!(inp && inp.value.trim()));
        });
    }

    function updateFillProgress() {
        if (!currentExercise) return;
        const total  = currentExercise.blanks.length;
        const filled = currentExercise.blanks.filter((_, i) => {
            const v = document.getElementById(`blank-input-${i}`);
            return v && v.value.trim() !== '';
        }).length;
        document.getElementById('fill-counter').textContent = `${filled} / ${total} blanks filled`;
        const pct = total ? (filled / total * 100) : 0;
        document.getElementById('fill-progress-fill').style.width = pct + '%';
        document.getElementById('submit-btn').disabled = filled === 0;
    }

    /* ══════════════════════════════════════════════════════════════════
       Submit for AI Marking  (Step 2 → Step 3)
    ══════════════════════════════════════════════════════════════════ */
    async function submitForMarking() {
        if (!currentExercise) return;
        const ex = currentExercise;

        // Collect student answers
        const studentAnswers = ex.blanks.map((b, i) => {
            const inp = document.getElementById(`blank-input-${i}`);
            return inp ? inp.value.trim() : '';
        });

        // Disable submit + loading
        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>AI is marking…';

        // Show loading in results panel
        const resultsPanel = document.getElementById('results-panel');
        resultsPanel.style.display = '';
        document.getElementById('score-section').innerHTML   = shimmerBlock(3);
        document.getElementById('result-cards').innerHTML    = shimmerBlock(ex.blanks.length * 2);
        document.getElementById('teacher-commentary-section').innerHTML = shimmerBlock(2);
        document.getElementById('next-steps-section').innerHTML = '';
        resultsPanel.scrollIntoView({ behavior:'smooth', block:'start' });

        const answersText = ex.blanks.map((b, i) =>
            `Blank ${b.position}: expected="${b.answer}" | student wrote="${studentAnswers[i] || '(empty)'}"`
        ).join('\n');

        const prompt = `You are an experienced HKDSE ICT teacher marking a Python code completion exercise.

Exercise Title: "${ex.title}"
Topic: ${document.getElementById('topic-input').value.trim() || 'Python'}
Difficulty: ${currentDifficulty.toUpperCase()}

Code with blanks:
${ex.codeWithBlanks}

Student's answers:
${answersText}

For each blank, assess:
1. Is the student's answer CORRECT (exactly right or semantically equivalent)?
2. Is it PARTIAL (on the right track but has a small error: wrong syntax, off-by-one, similar but wrong function name)?
3. Is it WRONG (fundamentally incorrect or empty)?

For WRONG or PARTIAL answers, explain:
- WHY the student's answer is wrong (be specific and educational)
- WHAT the correct answer is and WHY it works in this context
- Connect to the HKDSE ICT syllabus concept being tested

Be encouraging and constructive — this is a learning tool, not a punishment!

Return ONLY valid JSON (no markdown fences):
{
  "results": [
    {
      "blankPosition": 1,
      "isCorrect": true,
      "isPartial": false,
      "studentAnswer": "what student wrote",
      "correctAnswer": "expected answer",
      "explanation": "Clear, educational explanation. For correct: reinforce why this works. For wrong/partial: explain why the student's answer fails and how the correct answer works.",
      "teacherNote": "Short, encouraging teacher comment (max 1 sentence)"
    }
  ],
  "overallScore": 2,
  "totalBlanks": 3,
  "teacherCommentary": "2–4 sentence overall teacher feedback. Acknowledge what went well, identify patterns in mistakes, and give actionable advice. Be warm and encouraging.",
  "nextSteps": "Specific HKDSE ICT topic or concept the student should review to improve."
}`;

        try {
            const raw = await callAI(prompt);
            let parsed = null;
            try {
                const m = raw.match(/\{[\s\S]*\}/);
                if (m) parsed = JSON.parse(m[0]);
            } catch (_) {}

            if (!parsed?.results) {
                throw new Error('Could not parse AI marking response.');
            }

            markingResult = parsed;
            renderResults(parsed, ex, studentAnswers);
            goToStep(3);

            // Update input highlighting
            ex.blanks.forEach((b, i) => {
                const inp = document.getElementById(`blank-input-${i}`);
                const res = parsed.results.find(r => r.blankPosition === b.position);
                if (inp && res) {
                    inp.classList.remove('correct','wrong');
                    inp.classList.add(res.isCorrect ? 'correct' : 'wrong');
                }
            });

            // Save to history (non-blocking)
            if (window.saveCCHistoryRecord) {
                const topic = document.getElementById('topic-input').value.trim() || 'Python';
                window.saveCCHistoryRecord(topic, currentDifficulty, ex, parsed, studentAnswers);
            }

        } catch (err) {
            document.getElementById('score-section').innerHTML = `
                <div class="p-4 rounded-xl text-sm font-semibold" style="background:rgba(239,68,68,.08);color:#dc2626;border:1px solid rgba(239,68,68,.25)">
                    <i class="fas fa-exclamation-circle mr-2"></i>Marking failed: ${esc(err.message)} — please try again.
                </div>`;
            document.getElementById('result-cards').innerHTML = '';
            document.getElementById('teacher-commentary-section').innerHTML = '';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-robot mr-2"></i>Submit for AI Marking';
        }
    }

    function shimmerBlock(rows) {
        return Array.from({length: rows}, (_, i) =>
            `<div class="shimmer" style="height:1.1rem;width:${70 + (i%3)*10}%;margin-bottom:.5rem;animation-delay:${i*.08}s"></div>`
        ).join('');
    }

    /* ══════════════════════════════════════════════════════════════════
       Render Results
    ══════════════════════════════════════════════════════════════════ */
    function renderResults(marking, ex, studentAnswers) {
        const score = marking.overallScore ?? 0;
        const total = marking.totalBlanks  ?? ex.blanks.length;
        const pct   = total ? Math.round(score / total * 100) : 0;

        const starEmoji = pct === 100 ? '🏆' : pct >= 70 ? '⭐' : pct >= 40 ? '📝' : '💪';
        const grade = pct === 100 ? 'Perfect Score!'
                    : pct >= 70  ? 'Well Done!'
                    : pct >= 40  ? 'Good Effort!'
                    : 'Keep Practising!';
        const gradeColor = pct === 100 ? '#10b981' : pct >= 70 ? '#7c3aed' : pct >= 40 ? '#d97706' : '#ef4444';

        // Score section
        document.getElementById('score-section').innerHTML = `
            <div class="score-card" style="background:linear-gradient(135deg,rgba(124,58,237,.06),rgba(6,182,212,.06));border:1.5px solid rgba(124,58,237,.15)">
                <div class="score-ring" style="background:linear-gradient(135deg,${gradeColor},${gradeColor}88)">
                    <div>
                        <div class="score-num">${score}/${total}</div>
                    </div>
                </div>
                <div style="font-size:1.6rem;margin-bottom:.25rem">${starEmoji}</div>
                <h2 class="text-xl font-extrabold mb-1" style="color:${gradeColor}">${grade}</h2>
                <p class="text-sm" style="color:var(--text-secondary)">${pct}% correct — ${score} out of ${total} blanks right</p>
            </div>`;

        // Per-blank result cards
        const cards = (marking.results || []).map((r, i) => {
            const statusClass = r.isCorrect ? 'correct' : r.isPartial ? 'partial' : 'wrong';
            const iconHtml = r.isCorrect
                ? '<i class="fas fa-check-circle" style="color:#10b981"></i>'
                : r.isPartial
                    ? '<i class="fas fa-adjust" style="color:#d97706"></i>'
                    : '<i class="fas fa-times-circle" style="color:#ef4444"></i>';
            const label = r.isCorrect ? 'Correct' : r.isPartial ? 'Partially Correct' : 'Incorrect';
            const labelColor = r.isCorrect ? '#10b981' : r.isPartial ? '#d97706' : '#ef4444';

            // Use the actual answer the student typed, not what the AI thinks they typed
            const blankIdx = ex.blanks.findIndex(b => b.position === r.blankPosition);
            const actualStudentAnswer = blankIdx >= 0 ? (studentAnswers[blankIdx] || '') : '';

            return `
            <div class="result-card ${statusClass}" style="animation-delay:${i * .08}s">
                <div class="flex items-start gap-3">
                    <div class="result-icon ${statusClass}">${iconHtml}</div>
                    <div style="flex:1;min-width:0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="font-bold text-sm" style="color:var(--text-primary)">Blank ${r.blankPosition}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background:${r.isCorrect ? 'rgba(16,185,129,.15)' : r.isPartial ? 'rgba(245,158,11,.15)' : 'rgba(239,68,68,.12)'};color:${labelColor}">${label}</span>
                        </div>
                        <!-- Answers row -->
                        <div class="flex flex-wrap gap-3 mb-2 text-xs">
                            <div>
                                <span class="section-label" style="margin-bottom:2px">Your Answer</span>
                                <code style="background:rgba(0,0,0,.06);padding:.2rem .5rem;border-radius:6px;font-family:var(--mono);font-size:.8rem;display:block;color:${r.isCorrect ? '#10b981' : '#ef4444'}">${esc(actualStudentAnswer || '(empty)')}</code>
                            </div>
                            ${!r.isCorrect ? `<div>
                                <span class="section-label" style="margin-bottom:2px">Correct Answer</span>
                                <code style="background:rgba(16,185,129,.1);padding:.2rem .5rem;border-radius:6px;font-family:var(--mono);font-size:.8rem;display:block;color:#10b981">${esc(r.correctAnswer || '')}</code>
                            </div>` : ''}
                        </div>
                        <!-- Explanation -->
                        <div class="text-sm mb-2" style="color:var(--text-secondary);line-height:1.6">${esc(r.explanation || '')}</div>
                        <!-- Teacher note -->
                        ${r.teacherNote ? `<div class="text-xs font-semibold flex items-center gap-1.5" style="color:var(--primary)">
                            <i class="fas fa-chalkboard-teacher" style="font-size:.7rem"></i>${esc(r.teacherNote)}
                        </div>` : ''}
                    </div>
                </div>
            </div>`;
        });
        document.getElementById('result-cards').innerHTML = cards.join('');

        // Teacher Commentary
        if (marking.teacherCommentary) {
            document.getElementById('teacher-commentary-section').innerHTML = `
                <div class="teacher-box">
                    <div class="flex items-start gap-3">
                        <div class="teacher-avatar">👩‍🏫</div>
                        <div>
                            <p class="font-bold text-sm mb-1.5" style="color:var(--primary)">Teacher's Commentary</p>
                            <p class="text-sm" style="color:var(--text-secondary);line-height:1.7">${esc(marking.teacherCommentary)}</p>
                        </div>
                    </div>
                </div>`;
        } else {
            document.getElementById('teacher-commentary-section').innerHTML = '';
        }

        // Next Steps
        if (marking.nextSteps) {
            document.getElementById('next-steps-section').innerHTML = `
                <div class="flex items-start gap-3 p-4 rounded-xl" style="background:rgba(6,182,212,.06);border:1.5px solid rgba(6,182,212,.2)">
                    <i class="fas fa-map-signs" style="color:#0891b2;margin-top:.15rem;font-size:1rem;flex-shrink:0"></i>
                    <div>
                        <p class="font-bold text-sm mb-1" style="color:#0891b2">Next Steps</p>
                        <p class="text-sm" style="color:var(--text-secondary)">${esc(marking.nextSteps)}</p>
                    </div>
                </div>`;
        } else {
            document.getElementById('next-steps-section').innerHTML = '';
        }
    }

    /* ══════════════════════════════════════════════════════════════════
       Reset / Try Again
    ══════════════════════════════════════════════════════════════════ */
    function tryAgain() {
        if (!currentExercise) return;
        // Clear inputs and highlighting
        currentExercise.blanks.forEach((_, i) => {
            const inp = document.getElementById(`blank-input-${i}`);
            if (inp) {
                inp.value = '';
                inp.classList.remove('correct','wrong');
            }
        });
        document.getElementById('results-panel').style.display = 'none';
        updateFillProgress();
        onBlankInput();
        goToStep(2);
        document.getElementById('exercise-panel').scrollIntoView({ behavior:'smooth' });
    }

    function resetExercise() {
        currentExercise = null;
        markingResult   = null;
        document.getElementById('exercise-panel').style.display = 'none';
        document.getElementById('results-panel').style.display  = 'none';
        goToStep(1);
        document.getElementById('setup-panel').scrollIntoView({ behavior:'smooth' });
        document.getElementById('topic-input').focus();
    }

    /* ══════════════════════════════════════════════════════════════════
       Theme Toggle
    ══════════════════════════════════════════════════════════════════ */
    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
    (function applyTheme() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    })();

    /* ══════════════════════════════════════════════════════════════════
       Back button — respect ?back= parameter
    ══════════════════════════════════════════════════════════════════ */
    (function() {
        const back = new URLSearchParams(window.location.search).get('back');
        const link = document.getElementById('back-btn-link');
        if (link) {
            if (!back) {
                link.href = './coding.php';
            } else if (back === 'ICT') {
                // Came directly from the ICT AI Tools list — return there
                link.href = './index.php?openSubject=ICT';
            } else {
                link.href = './coding.php?back=' + encodeURIComponent(back);
            }
        }
    })();
    </script>

    <!-- ── Firebase Auth Guard ──────────────────────────────────────── -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";
        const app  = initializeApp(window.__FIREBASE_CONFIG__);
        const auth    = getAuth(app);
        const overlay = document.getElementById('auth-guard-overlay');
        onAuthStateChanged(auth, async (user) => {
            if (user) {
                if (overlay) overlay.style.display = 'none';
                window._fbUser = user;
                // Restore from history if session ID is in URL
                const sessionId = new URLSearchParams(window.location.search).get('session');
                if (sessionId) await _loadCCSession(user, sessionId);
            } else {
                window.location.replace('./index.php');
            }
        });

        async function _loadCCSession(user, sessionId) {
            try {
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?id=' + encodeURIComponent(sessionId), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) return;
                const data = await res.json();
                const msgs = data.messages || [];
                const userMsg = msgs.find(m => m.role === 'user');
                const aiMsg   = msgs.find(m => m.role === 'assistant');
                if (!aiMsg) return;

                // Parse stored data from user message
                const uc = userMsg ? (userMsg.content || '') : '';
                const topicMatch = uc.match(/Topic:\s*(.+)/);
                const diffMatch  = uc.match(/Difficulty:\s*(.+)/i);
                const topic = topicMatch ? topicMatch[1].trim() : '';
                const diff  = diffMatch  ? diffMatch[1].trim().toLowerCase()  : 'easy';

                // Show history restoration banner in the results section
                const historyBanner = `<div style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.25);border-radius:12px;padding:.6rem 1rem;margin-bottom:1.25rem;font-size:.8rem;color:#5b21b6;display:flex;align-items:center;gap:.5rem">
                    <i class="fas fa-history"></i> <span>Restored from history &mdash; ${(data.summary || topic || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>
                </div>`;

                // Try to parse stored marking result from AI message
                let parsed = null;
                try {
                    const m = aiMsg.content.match(/\{[\s\S]*\}/);
                    if (m) parsed = JSON.parse(m[0]);
                } catch (_) {}

                if (topic && document.getElementById('topic-input')) {
                    document.getElementById('topic-input').value = topic;
                }

                // Show results panel with restored data
                const scoreEl = document.getElementById('score-section');
                const resultsEl = document.getElementById('results-panel');
                if (resultsEl) resultsEl.style.display = '';
                if (scoreEl) {
                    scoreEl.innerHTML = historyBanner + (parsed
                        ? `<div style="text-align:center;padding:1.5rem"><p class="font-bold" style="color:var(--primary)">${data.summary || topic}</p><p style="font-size:.85rem;color:var(--text-secondary);margin-top:.4rem">Score: ${parsed.overallScore ?? '?'}/${parsed.totalBlanks ?? '?'} · ${diff} difficulty</p></div>`
                        : `<pre style="white-space:pre-wrap;font-size:.82rem">${aiMsg.content.substring(0,2000)}</pre>`);
                }
                if (resultsEl) resultsEl.scrollIntoView({ behavior:'smooth', block:'start' });
            } catch(e) {
                console.warn('Failed to load code completion history session:', e);
            }
        }

        window.saveCCHistoryRecord = async function(topic, difficulty, ex, marking, studentAnswers) {
            try {
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const score = marking.overallScore ?? 0;
                const total = marking.totalBlanks ?? ex.blanks.length;
                const userContent = `Topic: ${topic}\nDifficulty: ${difficulty}\nTitle: ${ex.title || ''}\n\nExercise:\n${ex.codeWithBlanks || ''}`;
                const aiContent   = JSON.stringify(marking);
                const res = await fetch('./api/history.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({
                        tool: 'Code Completion',
                        subject: 'ICT',
                        summary: `${topic} (${difficulty}) — ${score}/${total}`,
                        messages: [
                            { role: 'user',      content: userContent },
                            { role: 'assistant', content: aiContent  }
                        ]
                    })
                });
                if (!res.ok) return;
                const saved = await res.json();
                // Generate AI title asynchronously
                if (saved && saved.id) _generateCCTitle(saved.id, topic, difficulty, score, total);
            } catch(e) {
                console.warn('History save failed:', e);
            }
        };

        async function _generateCCTitle(id, topic, difficulty, score, total) {
            try {
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const titlePrompt = `Summarise this code completion exercise result in ≤10 words: Topic="${topic}", Difficulty="${difficulty}", Score=${score}/${total}. Return only the summary text.`;
                const resp = await fetch('./api/ai_proxy.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({ subject: 'ICT', mode: 'code_completion', model: 'gemini-fast', stream: false, temperature: 0.4, max_tokens: 40, messages: [{ role: 'user', content: titlePrompt }] })
                });
                if (!resp.ok) return;
                const data = await resp.json();
                const title = data?.choices?.[0]?.message?.content?.trim();
                if (!title) return;
                await fetch('./api/history.php?id=' + encodeURIComponent(id), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({ summary: title })
                });
            } catch(_) {}
        }

        // ── Code Completion History Panel ──────────────────────────────
        let _cch = { all:[], lastDoc:null, loading:false };
        const CCH_PAGE_SIZE = 15;

        window.openCCHistory = async function() {
            const backdrop = document.getElementById('ccHistoryBackdrop');
            const panel    = document.getElementById('ccHistoryPanel');
            backdrop.style.display = 'block';
            requestAnimationFrame(() => {
                backdrop.classList.add('open');
                panel.classList.add('open');
            });
            _cch = { all:[], lastDoc:null, loading:false };
            const listEl = document.getElementById('cchList');
            listEl.innerHTML = `<div style="text-align:center;padding:2rem"><i class="fas fa-spinner fa-spin" style="color:var(--primary);font-size:1.5rem"></i></div>`;
            document.getElementById('cchLoadMoreWrap').style.display = 'none';
            try {
                const user = window._fbUser;
                if (!user) { listEl.innerHTML = '<p style="text-align:center;color:var(--text-secondary);font-size:.85rem;padding:2rem">Please sign in to view history.</p>'; return; }
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=' + CCH_PAGE_SIZE + '&tool=' + encodeURIComponent('Code Completion'), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                _cch.all = data.docs || [];
                _cch.lastDoc = _cch.all.length > 0 ? _cch.all[_cch.all.length-1].updated_at : null;
                _cchRender(_cch.all);
                document.getElementById('cchLoadMoreWrap').style.display = (_cch.lastDoc && data.count === CCH_PAGE_SIZE) ? 'block' : 'none';
            } catch(e) {
                console.error(e);
                listEl.innerHTML = '<p style="text-align:center;color:#ef4444;font-size:.85rem;padding:2rem">Failed to load history.</p>';
            }
        };

        window.closeCCHistory = function() {
            const backdrop = document.getElementById('ccHistoryBackdrop');
            const panel    = document.getElementById('ccHistoryPanel');
            backdrop.classList.remove('open');
            panel.classList.remove('open');
            setTimeout(() => { backdrop.style.display = 'none'; }, 380);
        };

        window.loadMoreCCH = async function() {
            if (_cch.loading || !_cch.lastDoc) return;
            _cch.loading = true;
            const btn = document.getElementById('cchLoadMoreBtn');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…'; }
            try {
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=' + CCH_PAGE_SIZE + '&tool=' + encodeURIComponent('Code Completion') + '&after=' + encodeURIComponent(_cch.lastDoc), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                const more = data.docs || [];
                _cch.lastDoc = more.length > 0 ? more[more.length-1].updated_at : null;
                _cch.all = [..._cch.all, ...more];
                _cchRender(_cch.all);
                document.getElementById('cchLoadMoreWrap').style.display = (_cch.lastDoc && data.count === CCH_PAGE_SIZE) ? 'block' : 'none';
            } catch(e) { console.error(e); }
            finally {
                _cch.loading = false;
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Load More'; }
            }
        };

        window.deleteCCHSession = async function(id) {
            if (!confirm('Delete this history entry?')) return;
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                await fetch('./api/history.php?id=' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                _cch.all = _cch.all.filter(s => s.id !== id);
                _cchRender(_cch.all);
            } catch(e) { alert('Failed to delete. Please try again.'); }
        };

        function _cchEsc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function _cchFmtTime(ts) {
            if (!ts) return '';
            const d = new Date(ts);
            return d.toLocaleString('en-HK',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
        }

        function _cchRenderCard(s) {
            const msgs = s.messages || [];
            const userMsg = msgs.find(m => m.role === 'user');
            const uc = userMsg ? userMsg.content || '' : '';
            const topicMatch = uc.match(/Topic:\s*(.+)/);
            const diffMatch  = uc.match(/Difficulty:\s*(.+)/i);
            const topic = topicMatch ? topicMatch[1].trim() : (s.summary || '');
            const diff  = diffMatch  ? diffMatch[1].trim() : '';
            const time  = _cchFmtTime(s.updated_at);
            const summary = s.summary || (topic + (diff ? ' · ' + diff : ''));
            return `<div class="cch-card" id="cch-card-${s.id}">
                <div class="cch-card-header" onclick="closeCCHistory();window.location.href='./code-completion.php?session=${s.id}'"
                     role="button" tabindex="0"
                     aria-label="Open Code Completion session"
                     style="cursor:pointer"
                     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();closeCCHistory();window.location.href='./code-completion.php?session=${s.id}'}">
                    <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#10b981,#059669);flex-shrink:0">
                        <i class="fas fa-puzzle-piece" style="color:#fff;font-size:1rem"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:700;font-size:.8rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_cchEsc(topic.substring(0,60))}${diff ? ' · '+_cchEsc(diff) : ''}</p>
                        <p style="font-size:.72rem;color:var(--text-secondary);margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_cchEsc(summary.substring(0,70))}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                        <span style="font-size:.7rem;color:var(--text-secondary)">${_cchEsc(time)}</span>
                        <button onclick="event.stopPropagation();deleteCCHSession('${s.id}')"
                                style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:rgba(239,68,68,.12);color:#ef4444;font-size:.7rem;display:flex;align-items:center;justify-content:center"
                                title="Delete" aria-label="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <i class="fas fa-external-link-alt" title="Open in page" style="font-size:.75rem;color:var(--primary)"></i>
                    </div>
                </div>
            </div>`;
        }

        function _cchRender(sessions) {
            const list = document.getElementById('cchList');
            if (!sessions.length) {
                list.innerHTML = `<div style="text-align:center;padding:3rem 1rem">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(16,185,129,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                        <i class="fas fa-puzzle-piece" style="color:#10b981;font-size:1.3rem"></i>
                    </div>
                    <p style="font-weight:700;font-size:.9rem;color:var(--text-primary);margin-bottom:.35rem">No history yet</p>
                    <p style="font-size:.78rem;color:var(--text-secondary)">Complete a code exercise to start building history.</p>
                </div>`;
                return;
            }
            list.innerHTML = sessions.map(s => _cchRenderCard(s)).join('');
        }
    </script>

    <!-- Code Completion History Panel -->
    <div id="ccHistoryBackdrop" onclick="closeCCHistory()" aria-hidden="true"></div>
    <div id="ccHistoryPanel" role="dialog" aria-modal="true" aria-label="Code Completion history">
        <div id="ccHistoryPanelHeader">
            <div style="display:flex;align-items:center;gap:.5rem">
                <div style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#10b981,#059669);flex-shrink:0">
                    <i class="fas fa-puzzle-piece" style="color:#fff;font-size:.85rem"></i>
                </div>
                <span style="font-weight:700;font-size:.95rem;color:var(--text-primary)">Code Completion History</span>
            </div>
            <button onclick="closeCCHistory()" aria-label="Close history"
                    style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.05);color:var(--text-secondary);font-size:.85rem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="ccHistoryPanelBody">
            <div id="cchList"></div>
            <div id="cchLoadMoreWrap" style="display:none;text-align:center;padding:.75rem 0">
                <button id="cchLoadMoreBtn" onclick="loadMoreCCH()"
                        style="border:1px solid var(--glass-border);background:var(--glass-bg);color:var(--text-primary);padding:.4rem 1.25rem;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer">
                    <i class="fas fa-chevron-down mr-1"></i> Load More
                </button>
            </div>
        </div>
    </div>
</body>
</html>
