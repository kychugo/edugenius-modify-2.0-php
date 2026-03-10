<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGenius AI - Smart Learning Platform</title>
    <link rel="manifest" href="./manifest.json">
    <link rel="icon" sizes="192x192" href="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png">

    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- MathJax for math/physics chatboxes -->
    <script>window.MathJax = { tex: { inlineMath: [['\\(','\\)'],['$','$']], displayMath: [['\\[','\\]'],['$$','$$']] }, options: { skipHtmlTags: ['script','noscript','style','textarea','pre','code'] } };</script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #a78bfa;
            --primary-dark: #5b21b6;
            --secondary: #06b6d4;
            --text-primary: #1e1b4b;
            --text-secondary: #64748b;
            --bg-main: #f0f2ff;
            --glass-bg: rgba(255,255,255,0.80);
            --glass-border: rgba(255,255,255,0.65);
            --shadow-glow: 0 0 40px rgba(124,58,237,0.10);
            --radius: 20px;
            --radius-sm: 12px;
        }
        .dark {
            --primary: #a78bfa;
            --primary-light: #c4b5fd;
            --primary-dark: #7c3aed;
            --secondary: #22d3ee;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --bg-main: #0d0d1a;
            --glass-bg: rgba(15,15,40,0.85);
            --glass-border: rgba(255,255,255,0.08);
            --shadow-glow: 0 0 60px rgba(124,58,237,0.18);
        }
        * { font-family: 'Plus Jakarta Sans','Inter','Segoe UI','Microsoft JhengHei',Arial,sans-serif; box-sizing: border-box; }

        @keyframes fadeInUp { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes slideInRight { from{opacity:0;transform:translateX(24px)} to{opacity:1;transform:translateX(0)} }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
        @keyframes pulseGlow { 0%,100%{opacity:.8} 50%{opacity:1} }
        @keyframes dotPulse { 0%,60%,100%{transform:scale(1);opacity:.6} 30%{transform:scale(1.4);opacity:1} }

        .animate-fadeInUp { animation: fadeInUp 0.6s cubic-bezier(.16,1,.3,1) both; }
        .animate-slideInRight { animation: slideInRight 0.4s cubic-bezier(.16,1,.3,1) both; }
        .delay-100{animation-delay:.1s} .delay-200{animation-delay:.2s} .delay-300{animation-delay:.3s}
        .delay-400{animation-delay:.4s} .delay-500{animation-delay:.5s}

        html { scroll-behavior: smooth; }
        body {
            min-height:100vh;
            background-color: var(--bg-main);
            position:relative; overflow-x:hidden;
            transition: background-color .4s ease;
            color: var(--text-primary);
        }
        body::before {
            content:''; position:fixed; inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.18) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(236,72,153,.08) 0%,transparent 55%);
            pointer-events:none; z-index:0;
        }
        .dark body::before {
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.32) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.22) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(236,72,153,.14) 0%,transparent 55%);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.08), var(--shadow-glow);
            position:relative; overflow:hidden;
        }
        .glass-card::before {
            content:''; position:absolute; top:0;left:0;right:0; height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);
            pointer-events:none;
        }

        .custom-scrollbar::-webkit-scrollbar { width:5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background:var(--primary-light); }

        .gradient-text {
            background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }

        .btn-primary {
            background:linear-gradient(135deg,#7c3aed 0%,#5b21b6 100%);
            color:#fff!important; border:none;
            border-radius:var(--radius-sm); font-weight:700;
            transition:all .3s cubic-bezier(.4,0,.2,1);
            position:relative; overflow:hidden;
        }
        .dark .btn-primary { background:linear-gradient(135deg,#a78bfa 0%,#7c3aed 100%); }
        .btn-primary::after { content:'';position:absolute;inset:0; background:linear-gradient(135deg,rgba(255,255,255,.15) 0%,transparent 60%); opacity:0;transition:opacity .3s;border-radius:inherit; }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.42); }
        .btn-primary:hover::after { opacity:1; }
        .btn-primary:active { transform:translateY(0); }

        .btn-secondary {
            background:var(--glass-bg); color:var(--text-primary);
            border:1px solid var(--glass-border);
            border-radius:var(--radius-sm); font-weight:600;
            transition:all .25s ease; backdrop-filter:blur(12px);
        }
        .btn-secondary:hover { border-color:rgba(124,58,237,.45);color:var(--primary);background:rgba(124,58,237,.06); }

        .btn-danger {
            background:rgba(239,68,68,.08); color:#ef4444;
            border:1px solid rgba(239,68,68,.20);
            border-radius:var(--radius-sm); font-weight:600;
            transition:all .25s ease;
        }
        .btn-danger:hover { background:rgba(239,68,68,.18);border-color:rgba(239,68,68,.50); }

        .subject-card { transition:all .35s cubic-bezier(.4,0,.2,1); cursor:pointer; }
        .subject-card:hover { transform:translateY(-10px) scale(1.02); box-shadow:0 28px 56px rgba(0,0,0,.13),var(--shadow-glow); }
        .subject-card:active { transform:translateY(-4px) scale(1.01); }

        .icon-ring { border-radius:18px;width:68px;height:68px;display:flex;align-items:center;justify-content:center;transition:transform .35s ease; }
        .subject-card:hover .icon-ring { transform:scale(1.12) rotate(-4deg); }

        .tool-card { transition:all .3s cubic-bezier(.4,0,.2,1); cursor:pointer; }
        .tool-card:hover { transform:translateX(8px); box-shadow:0 16px 40px rgba(0,0,0,.10),var(--shadow-glow); }

        .message-bubble { position:relative;max-width:80%;word-wrap:break-word;animation:fadeInUp .35s cubic-bezier(.16,1,.3,1) both; }

        .user-bubble {
            background:linear-gradient(135deg,#7c3aed 0%,#5b21b6 100%);
            color:#fff; border-radius:20px 20px 4px 20px;
            box-shadow:0 4px 20px rgba(124,58,237,.30);
        }
        .dark .user-bubble { background:linear-gradient(135deg,#a78bfa 0%,#7c3aed 100%); }

        .ai-bubble {
            background:var(--glass-bg); backdrop-filter:blur(16px);
            border:1px solid var(--glass-border); color:var(--text-primary);
            border-radius:20px 20px 20px 4px; box-shadow:0 4px 16px rgba(0,0,0,.08);
        }

        .loading-dots { display:inline-flex;align-items:center;gap:5px; }
        .loading-dots span { height:8px;width:8px;background:var(--primary);border-radius:50%;animation:dotPulse 1.2s ease-in-out infinite both; }
        .loading-dots span:nth-child(1){animation-delay:0s}
        .loading-dots span:nth-child(2){animation-delay:.2s}
        .loading-dots span:nth-child(3){animation-delay:.4s}

        .input-field {
            background:var(--glass-bg); border:1.5px solid var(--glass-border);
            border-radius:var(--radius-sm); color:var(--text-primary);
            transition:all .25s ease; backdrop-filter:blur(12px);
        }
        .input-field:focus { outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.15); }
        .input-field::placeholder { color:var(--text-secondary); }

        .status-online { position:relative; }
        .status-online::after {
            content:''; position:absolute; bottom:3px; right:3px;
            width:13px; height:13px; background:#10b981;
            border-radius:50%; border:3px solid var(--bg-main);
        }

        .nav-bar {
            display:flex; justify-content:space-between; align-items:center;
            padding:12px 20px; border-radius:16px;
            background:var(--glass-bg); backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            border:1px solid var(--glass-border);
            box-shadow:0 4px 16px rgba(0,0,0,.06); margin-bottom:24px;
        }

        .chat-container {
            background:var(--glass-bg); backdrop-filter:blur(24px) saturate(150%);
            -webkit-backdrop-filter:blur(24px) saturate(150%);
            border:1px solid var(--glass-border); border-radius:var(--radius);
            box-shadow:0 8px 32px rgba(0,0,0,.10);
            display:flex; flex-direction:column; flex:1;
        }
        .chat-input-area { border-top:1px solid var(--glass-border);padding:16px; border-radius:0 0 var(--radius) var(--radius); }

        .msg-action-btn {
            font-size:.72rem;font-weight:600;padding:4px 10px;
            border-radius:8px;border:1px solid var(--glass-border);
            background:var(--glass-bg);color:var(--text-secondary);
            transition:all .2s ease;cursor:pointer;
        }
        .msg-action-btn:hover { background:var(--primary);color:#fff;border-color:var(--primary); }

        /* Markdown table styles */
        .md-table-wrap { overflow-x:auto; margin:.75rem 0; }
        .md-table { width:100%; border-collapse:collapse; font-size:.85rem; }
        .md-th { background:rgba(124,58,237,.12); color:var(--text-primary); font-weight:700;
                 padding:.5rem .75rem; border:1px solid rgba(124,58,237,.20); text-align:left; }
        .md-td { padding:.45rem .75rem; border:1px solid var(--glass-border); color:var(--text-primary); vertical-align:top; }
        .md-table tbody tr:nth-child(even) .md-td { background:rgba(124,58,237,.04); }
        .md-table tbody tr:hover .md-td { background:rgba(124,58,237,.08); }

        .sr-only { position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0; }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important; } }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
        .stream-cursor { display:inline-block; animation: blink 1s step-end infinite; color:var(--primary); font-weight:bold; }
        button:active:not(:disabled), .tool-card:active { transform: scale(0.97); }
        @media (max-width:768px) { .message-bubble{max-width:92%} :root{--radius:16px;--radius-sm:10px} }
        @media print { .no-print { display:none!important; } }

        /* Class-based dark mode icon toggle for theme buttons */
        .theme-moon { display:inline-block; }
        .theme-sun  { display:none; }
        .dark .theme-moon { display:none; }
        .dark .theme-sun  { display:inline-block; }

        /* ── Inline History Panel ──────────────────────────────────── */
        #historyPanelBackdrop {
            display:none; position:fixed; inset:0; z-index:900;
            background:rgba(0,0,0,.45); backdrop-filter:blur(4px);
            -webkit-backdrop-filter:blur(4px);
            opacity:0; transition:opacity .3s ease;
        }
        #historyPanelBackdrop.open { opacity:1; }
        #historyPanel {
            position:fixed; top:0; right:0; bottom:0; z-index:901;
            width:min(480px,100vw);
            background:var(--bg-main);
            border-left:1px solid var(--glass-border);
            box-shadow:-8px 0 32px rgba(0,0,0,.18);
            display:flex; flex-direction:column;
            transform:translateX(100%);
            transition:transform .35s cubic-bezier(.16,1,.3,1);
            overflow:hidden;
        }
        #historyPanel.open { transform:translateX(0); }
        #historyPanelHeader {
            display:flex; align-items:center; justify-content:space-between;
            padding:1rem 1.25rem;
            background:var(--glass-bg);
            border-bottom:1px solid var(--glass-border);
            flex-shrink:0;
        }
        #historyPanelBody {
            flex:1; overflow-y:auto; padding:1rem;
        }
        #historyPanelBody::-webkit-scrollbar { width:4px; }
        #historyPanelBody::-webkit-scrollbar-track { background:transparent; }
        #historyPanelBody::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
        .hp-session-card {
            background:var(--glass-bg);
            border:1px solid var(--glass-border);
            border-radius:var(--radius-sm);
            margin-bottom:.75rem;
            overflow:hidden;
            animation:fadeInUp .3s ease both;
        }
        .hp-session-header {
            display:flex; align-items:center; gap:.75rem;
            padding:.85rem 1rem; cursor:pointer;
            transition:background .2s;
        }
        .hp-session-header:hover { background:rgba(124,58,237,.06); }
        .hp-session-icon {
            width:36px; height:36px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            flex-shrink:0; color:#fff; font-size:.85rem;
        }
        .hp-session-body {
            max-height:0; overflow:hidden;
            transition:max-height .35s ease, padding .2s;
            padding:0 1rem;
        }
        .hp-session-body.open {
            max-height:420px; overflow-y:auto;
            padding:.75rem 1rem;
        }
        .hp-session-body::-webkit-scrollbar { width:3px; }
        .hp-session-body::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
        .hp-msg-user {
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff; border-radius:12px 12px 4px 12px;
            padding:.5rem .75rem; font-size:.78rem; margin-bottom:.4rem;
            max-width:85%; word-break:break-word;
        }
        .hp-msg-ai {
            background:var(--glass-bg); border:1px solid var(--glass-border);
            color:var(--text-primary); border-radius:12px 12px 12px 4px;
            padding:.5rem .75rem; font-size:.78rem; margin-bottom:.4rem;
            max-width:85%; word-break:break-word;
        }
        .hp-date-divider {
            text-align:center; font-size:.7rem; font-weight:700;
            color:var(--text-secondary); margin:.75rem 0 .4rem;
            text-transform:uppercase; letter-spacing:.06em;
        }
        #historyPanelLoadMore {
            display:none; text-align:center; padding:.75rem 0;
        }
    </style>
    <?= firebaseConfigScript() ?>
</head>
<body class="transition-all duration-300">
    <a href="#mainContent" class="skip-link" style="position:fixed;top:-9999px;left:0;z-index:99999;background:var(--primary);color:#fff;padding:.5rem 1rem;border-radius:0 0 8px 0;font-size:.9rem;font-weight:700;text-decoration:none;transition:top .2s" onfocus="this.style.top='0'" onblur="this.style.top='-9999px'">Skip to main content</a>
    <!-- Authentication Screen -->
    <div class="flex items-center justify-center min-h-screen px-4 py-8 relative z-10" id="authScreen">
        <div class="w-full max-w-md animate-fadeInUp">
            <div class="text-center mb-8">
                <div class="inline-block relative mb-5">
                    <div class="absolute inset-0 rounded-3xl" style="background:linear-gradient(135deg,#7c3aed,#06b6d4);filter:blur(16px);opacity:.3;transform:scale(1.15)"></div>
                    <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png"
                         alt="Platform Logo"
                         class="relative w-24 h-24 mx-auto rounded-3xl shadow-2xl status-online"
                         style="background:linear-gradient(135deg,#7c3aed,#06b6d4)">
                    <span class="sr-only">EduGenius AI Logo</span>
                </div>
                <h1 class="text-4xl font-extrabold mb-2">
                    <span class="gradient-text">EduGenius</span>
                </h1>
                <p class="text-sm font-medium" style="color:var(--text-secondary)">Your intelligent AI study companion 智學</p>
            </div>

            <div class="glass-card p-8">
                <form class="space-y-5" role="form" aria-label="Authentication form">
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text-secondary)">
                            <i class="fas fa-envelope mr-1.5" style="color:var(--primary)"></i>Email Address
                        </label>
                        <input type="email" id="email" placeholder="you@example.com"
                               required aria-required="true"
                               class="input-field w-full px-4 py-3 text-sm outline-none">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text-secondary)">
                            <i class="fas fa-lock mr-1.5" style="color:var(--primary)"></i>Password
                        </label>
                        <input type="password" id="password" placeholder="Enter your password"
                               required aria-required="true"
                               class="input-field w-full px-4 py-3 text-sm outline-none">
                    </div>
                    <div class="space-y-3 pt-2">
                        <button type="button" id="sign-in-button"
                                class="w-full btn-primary py-3 px-6 rounded-xl text-sm focus:ring-4 focus:ring-purple-300 outline-none">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>
                        <button type="button" id="register-button"
                                class="w-full btn-secondary py-3 px-6 rounded-xl text-sm focus:ring-4 outline-none">
                            <i class="fas fa-user-plus mr-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px" style="background:var(--glass-border)"></div>
                    <span class="text-xs font-semibold" style="color:var(--text-secondary)">OR</span>
                    <div class="flex-1 h-px" style="background:var(--glass-border)"></div>
                </div>

                <button type="button" id="google-sign-in-button"
                        class="w-full py-3 px-6 rounded-xl text-sm font-semibold flex items-center justify-center gap-3 outline-none transition-all duration-200 focus:ring-4 focus:ring-blue-300"
                        style="background:#fff;color:#3c4043;border:1px solid #dadce0;box-shadow:0 1px 3px rgba(0,0,0,.08);">
                    <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                        <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                        <path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/>
                        <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </button>

                <div id="auth-error" class="mt-4 p-3 rounded-xl text-sm hidden"
                     style="background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.25);color:#ef4444"
                     role="alert" aria-live="polite"></div>

                <div class="mt-6 pt-5 text-center text-xs" style="border-top:1px solid var(--glass-border);color:var(--text-secondary)">
                    <p class="font-semibold">EduGenius AI 智學</p>
                    <p class="mt-1">Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="mainContent" tabindex="-1" style="outline:none"></div>
    <!-- Cover Page (Subject List) -->
    <div class="container mx-auto px-4 py-8 hidden relative z-10" id="coverScreen">
        <div class="max-w-6xl mx-auto">
            <!-- Nav bar -->
            <div class="nav-bar no-print animate-fadeInUp">
                <div class="flex items-center gap-3">
                    <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius AI Logo" class="flex-shrink-0" style="width:36px;height:36px;border-radius:10px;object-fit:cover;background:linear-gradient(135deg,#7c3aed,#06b6d4)">
                    <span class="font-bold text-sm gradient-text">EduGenius AI</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Hero heading -->
            <div class="text-center mb-12 animate-fadeInUp">
                <div class="inline-block mb-4">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest"
                         style="background:rgba(124,58,237,.10);color:var(--primary);border:1px solid rgba(124,58,237,.20)">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                        AI-Powered Learning
                    </div>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4" style="color:var(--text-primary)">
                    Choose Your <span class="gradient-text">Subject</span>
                </h1>
                <p class="text-lg max-w-2xl mx-auto" style="color:var(--text-secondary)">
                    Explore AI-powered learning tools designed for Hong Kong DSE students
                </p>
            </div>

            <!-- Subject Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <!-- Chinese -->
                <div class="glass-card subject-card p-6 animate-fadeInUp"
                     onclick="showAIList('中文')" role="button" tabindex="0" aria-label="中文 subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                            <i class="fas fa-dragon text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">中文</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">古典文學與現代語文</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(239,68,68,.12);color:#ef4444">4 AI Tools</span>
                    </div>
                </div>

                <!-- English -->
                <div class="glass-card subject-card p-6 animate-fadeInUp delay-100"
                     onclick="showAIList('English')" role="button" tabindex="0" aria-label="English subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#3b82f6,#2563eb)">
                            <i class="fas fa-book-open text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">English</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">Language arts, literature &amp; composition</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(59,130,246,.12);color:#3b82f6">6 AI Tools</span>
                    </div>
                </div>

                <!-- Math -->
                <div class="glass-card subject-card p-6 animate-fadeInUp delay-200"
                     onclick="showAIList('Math')" role="button" tabindex="0" aria-label="Mathematics subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#10b981,#059669)">
                            <i class="fas fa-calculator text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">Math</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">Algebra, geometry, calculus &amp; statistics</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(16,185,129,.12);color:#10b981">4 AI Tools</span>
                    </div>
                </div>

                <!-- Physics -->
                <div class="glass-card subject-card p-6 animate-fadeInUp delay-300"
                     onclick="showAIList('Physics')" role="button" tabindex="0" aria-label="Physics subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                            <i class="fas fa-atom text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">Physics</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">Mechanics, electricity, waves &amp; modern physics</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(139,92,246,.12);color:#8b5cf6">4 AI Tools</span>
                    </div>
                </div>

                <!-- Biology -->
                <div class="glass-card subject-card p-6 animate-fadeInUp delay-400"
                     onclick="showAIList('Biology')" role="button" tabindex="0" aria-label="Biology subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                            <i class="fas fa-dna text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">Biology</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">Cell biology, genetics, ecology &amp; physiology</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(34,197,94,.12);color:#22c55e">4 AI Tools</span>
                    </div>
                </div>

                <!-- ICT -->
                <div class="glass-card subject-card p-6 animate-fadeInUp delay-500"
                     onclick="showAIList('ICT')" role="button" tabindex="0" aria-label="ICT subject">
                    <div class="text-center">
                        <div class="icon-ring mx-auto mb-4" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
                            <i class="fas fa-laptop-code text-2xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">ICT</h2>
                        <p class="text-sm mb-4" style="color:var(--text-secondary)">Programming, networking, databases &amp; cybersecurity</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(99,102,241,.12);color:#6366f1">5 AI Tools</span>
                    </div>
                </div>
            </div>

            <footer class="text-center text-xs pt-6" style="border-top:1px solid var(--glass-border);color:var(--text-secondary)">
                <p class="font-semibold">EduGenius AI 智學</p>
                <p class="mt-1">Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Subject AI List Page -->
    <div class="container mx-auto px-4 py-8 hidden relative z-10" id="aiListScreen">
        <div class="max-w-3xl mx-auto">
            <!-- Nav -->
            <div class="nav-bar no-print animate-fadeInUp">
                <button onclick="showCover()"
                        class="btn-secondary flex items-center gap-2 px-3 py-2 text-sm" aria-label="Back to subjects">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back</span>
                </button>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle-ai-list" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button-ai-list" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-10 animate-fadeInUp">
                <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="Platform Logo"
                     class="w-16 h-16 mx-auto rounded-2xl shadow-xl mb-4">
                <h1 id="aiListHeader" class="text-3xl font-extrabold mb-2" style="color:var(--text-primary)">
                    Subject AI Tools
                </h1>
                <p class="text-sm" style="color:var(--text-secondary)">Choose the perfect AI tool for your learning needs</p>
            </div>

            <!-- AI Options -->
            <div class="space-y-4 mb-12" id="aiOptions">
                <!-- Populated by JavaScript -->
            </div>

            <footer class="text-center text-xs pt-6" style="border-top:1px solid var(--glass-border);color:var(--text-secondary)">
                <p class="font-semibold">EduGenius AI 智學</p>
                <p class="mt-1">Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Ask Interface -->
    <div class="container mx-auto px-4 py-8 hidden relative z-10" id="askAIInterface">
        <div class="max-w-4xl mx-auto flex flex-col" style="height:calc(100vh - 64px)">
            <!-- Nav -->
            <div class="nav-bar no-print">
                <button onclick="showAIList(currentSubject)"
                        class="btn-secondary flex items-center gap-2 px-3 py-2 text-sm" aria-label="Back to AI tools">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back</span>
                </button>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle-askai" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button-askai" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Chat header -->
            <div class="text-center mb-5">
                <h1 id="askAIHeader" class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">
                    <i class="fas fa-robot mr-2" style="color:#7c3aed"></i>AI Tool
                </h1>
                <p class="text-sm" style="color:var(--text-secondary)">Ask your question here...</p>
            </div>

            <!-- Chat container -->
            <div class="chat-container">
                <div id="askAIChatBox"
                     class="flex-1 p-5 overflow-y-auto custom-scrollbar space-y-4"
                     role="log" aria-label="Chat messages" aria-live="polite">
                </div>

                <!-- Input area -->
                <div class="chat-input-area">
                    <div class="space-y-2">
                        <!-- Image preview -->
                        <div id="askAIImagePreviewContainer" class="hidden relative w-32 mb-2">
                            <img id="askAIImagePreview"
                                 class="w-32 h-32 object-cover rounded-xl shadow-lg" alt="Image preview">
                            <button onclick="clearImagePreview('askAI')"
                                    class="absolute -top-2 -right-2 w-7 h-7 text-white rounded-full text-sm flex items-center justify-center shadow-lg"
                                    style="background:#ef4444">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex items-end gap-2">
                            <textarea id="askAIInput"
                                      placeholder="Type your question here..."
                                      class="input-field flex-1 px-4 py-3 text-sm outline-none resize-none"
                                      oninput="this.style.height='auto';this.style.height=(this.scrollHeight)+'px';" rows="1" aria-label="Type your message"></textarea>
                            <div class="flex items-center gap-2">
                                <label for="askAIPhotoInput" title="Upload Image"
                                       class="w-11 h-11 flex items-center justify-center rounded-xl btn-secondary cursor-pointer">
                                    <i class="fas fa-image" style="color:var(--primary)"></i>
                                </label>
                                <input type="file" id="askAIPhotoInput" accept="image/*" onchange="previewImage('askAI')" class="hidden">
                                <button onclick="pasteImage('askAI')" title="Paste Image"
                                        class="w-11 h-11 hidden md:flex items-center justify-center rounded-xl btn-secondary"
                                        aria-label="Paste image from clipboard">
                                    <i class="fas fa-paste" style="color:var(--primary)"></i>
                                </button>
                            </div>
                            <button id="askAISendBtn" onclick="sendMessage('askAI')" title="Send"
                                    class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl text-white font-bold focus:ring-4 focus:ring-purple-300 outline-none transition-all duration-200"
                                    style="background:linear-gradient(135deg,var(--primary),var(--secondary))"
                                    aria-label="Send message">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dictionary Interface -->
    <div class="container mx-auto px-4 py-8 hidden relative z-10" id="dictionaryAIInterface">
        <div class="max-w-4xl mx-auto flex flex-col" style="height:calc(100vh - 64px)">
            <!-- Nav -->
            <div class="nav-bar no-print">
                <button onclick="showAIList(currentSubject)"
                        class="btn-secondary flex items-center gap-2 px-3 py-2 text-sm" aria-label="Back to AI tools">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back</span>
                </button>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle-dictionaryai" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button-dictionaryai" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Chat header -->
            <div class="text-center mb-5">
                <h1 id="dictionaryAIHeader" class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">
                    <i class="fas fa-book mr-2" style="color:#10b981"></i>AI Tool
                </h1>
                <p class="text-sm" style="color:var(--text-secondary)">Ask a word or concept...</p>
            </div>

            <!-- Chat container -->
            <div class="chat-container">
                <div id="dictionaryAIChatBox"
                     class="flex-1 p-5 overflow-y-auto custom-scrollbar space-y-4"
                     role="log" aria-label="Chat messages" aria-live="polite">
                </div>

                <!-- Input area -->
                <div class="chat-input-area">
                    <div class="space-y-2">
                        <div class="flex items-end gap-2">
                            <textarea id="dictionaryAIInput"
                                      placeholder="Enter a word or concept..."
                                      class="input-field flex-1 px-4 py-3 text-sm outline-none resize-none"
                                      oninput="this.style.height='auto';this.style.height=(this.scrollHeight)+'px';" rows="1" aria-label="Type your message"></textarea>
                            <div class="flex items-center gap-2">
                            </div>
                            <button id="dictionaryAISendBtn" onclick="sendMessage('dictionaryAI')" title="Send"
                                    class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl text-white font-bold focus:ring-4 focus:ring-purple-300 outline-none transition-all duration-200"
                                    style="background:linear-gradient(135deg,var(--primary),var(--secondary))"
                                    aria-label="Send message">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guidelearning Interface -->
    <div class="container mx-auto px-4 py-8 hidden relative z-10" id="guideLearningInterface">
        <div class="max-w-4xl mx-auto flex flex-col" style="height:calc(100vh - 64px)">
            <!-- Nav -->
            <div class="nav-bar no-print">
                <button onclick="showAIList(currentSubject)"
                        class="btn-secondary flex items-center gap-2 px-3 py-2 text-sm" aria-label="Back to AI tools">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back</span>
                </button>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle-guidelearning" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button-guidelearning" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Chat header -->
            <div class="text-center mb-5">
                <h1 id="guideLearningHeader" class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">
                    <i class="fas fa-compass mr-2" style="color:#8b5cf6"></i>AI Tool
                </h1>
                <p class="text-sm" style="color:var(--text-secondary)">Ask your question here...</p>
            </div>

            <!-- Chat container -->
            <div class="chat-container">
                <div id="guideLearningChatBox"
                     class="flex-1 p-5 overflow-y-auto custom-scrollbar space-y-4"
                     role="log" aria-label="Chat messages" aria-live="polite">
                </div>

                <!-- Input area -->
                <div class="chat-input-area">
                    <div class="space-y-2">
                        <!-- Image preview -->
                        <div id="guideLearningImagePreviewContainer" class="hidden relative w-32 mb-2">
                            <img id="guideLearningImagePreview"
                                 class="w-32 h-32 object-cover rounded-xl shadow-lg" alt="Image preview">
                            <button onclick="clearImagePreview('guideLearning')"
                                    class="absolute -top-2 -right-2 w-7 h-7 text-white rounded-full text-sm flex items-center justify-center shadow-lg"
                                    style="background:#ef4444">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex items-end gap-2">
                            <textarea id="guideLearningInput"
                                      placeholder="Type your question here..."
                                      class="input-field flex-1 px-4 py-3 text-sm outline-none resize-none"
                                      oninput="this.style.height='auto';this.style.height=(this.scrollHeight)+'px';" rows="1" aria-label="Type your message"></textarea>
                            <div class="flex items-center gap-2">
                                <label for="guideLearningPhotoInput" title="Upload Image"
                                       class="w-11 h-11 flex items-center justify-center rounded-xl btn-secondary cursor-pointer">
                                    <i class="fas fa-image" style="color:var(--primary)"></i>
                                </label>
                                <input type="file" id="guideLearningPhotoInput" accept="image/*" onchange="previewImage('guideLearning')" class="hidden">
                                <button onclick="pasteImage('guideLearning')" title="Paste Image"
                                        class="w-11 h-11 hidden md:flex items-center justify-center rounded-xl btn-secondary"
                                        aria-label="Paste image from clipboard">
                                    <i class="fas fa-paste" style="color:var(--primary)"></i>
                                </button>
                            </div>
                            <button id="guideLearningSendBtn" onclick="sendMessage('guideLearning')" title="Send"
                                    class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl text-white font-bold focus:ring-4 focus:ring-purple-300 outline-none transition-all duration-200"
                                    style="background:linear-gradient(135deg,var(--primary),var(--secondary))"
                                    aria-label="Send message">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Analysis Interface -->
    <div class="container mx-auto px-4 py-6 hidden relative z-10" id="errorAnalysisInterface">
        <div class="max-w-4xl mx-auto pb-8">
            <!-- Nav -->
            <div class="nav-bar no-print">
                <button onclick="showAIList(currentSubject)"
                        class="btn-secondary flex items-center gap-2 px-3 py-2 text-sm" aria-label="Back to AI tools">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back</span>
                </button>
                <div class="flex items-center gap-2">
                    <button onclick="openHistoryPanel()"
                            class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="View history" title="History">
                        <i class="fas fa-history" style="color:var(--primary)"></i>
                    </button>
                    <button id="theme-toggle-erroranalysis" class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                        <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i><i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                    </button>
                    <button id="sign-out-button-erroranalysis" class="w-10 h-10 flex items-center justify-center rounded-xl btn-danger" aria-label="Sign out" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background:linear-gradient(135deg,#ef4444,#dc2626)">
                    <i class="fas fa-search-plus text-white text-xl"></i>
                </div>
                <h1 id="errorAnalysisHeader" class="text-2xl font-extrabold mb-1" style="color:var(--text-primary)">
                    Error Analysis
                </h1>
                <p id="errorAnalysisSubDesc" class="text-sm" style="color:var(--text-secondary)">
                    Paste your answer below for AI analysis
                </p>
            </div>

            <!-- Input Card -->
            <div class="glass-card p-5 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-xs font-bold uppercase tracking-wider" style="color:var(--text-secondary)">
                        <i class="fas fa-clipboard-list mr-1"></i> Analysis Inputs
                    </label>
                    <button onclick="clearEASession()" class="btn-secondary text-xs px-3 py-1.5 rounded-xl flex items-center gap-1">
                        <i class="fas fa-redo mr-1"></i> New Analysis
                    </button>
                </div>

                <!-- Part 1: Exam Question (optional) -->
                <div class="mb-3">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <i class="fas fa-question-circle text-xs" style="color:#7c3aed"></i>
                        <label class="text-xs font-semibold" style="color:var(--text-secondary)">Exam Question <span class="font-normal">(optional)</span></label>
                    </div>
                    <textarea id="eaQuestionInput" rows="2"
                              class="input-field w-full px-4 py-3 text-sm outline-none resize-y"
                              placeholder="Paste the exam question here (optional)..."
                              aria-label="Exam question"></textarea>
                </div>

                <!-- Part 2: Your Answer / Working (required) -->
                <div class="mb-3">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <i class="fas fa-pen text-xs" style="color:#ef4444"></i>
                        <label class="text-xs font-semibold" style="color:var(--text-secondary)">Your Answer / Working <span style="color:#ef4444">*</span></label>
                    </div>
                    <textarea id="eaAnswerInput" rows="5"
                              class="input-field w-full px-4 py-3 text-sm outline-none resize-y"
                              placeholder="Paste your answer, working, or code here..."
                              aria-label="Your answer or working"></textarea>
                </div>

                <!-- Part 3: Model Answer (optional) -->
                <div class="mb-3">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <i class="fas fa-check-circle text-xs" style="color:#10b981"></i>
                        <label class="text-xs font-semibold" style="color:var(--text-secondary)">Model Answer <span class="font-normal">(optional)</span></label>
                    </div>
                    <textarea id="eaModelInput" rows="2"
                              class="input-field w-full px-4 py-3 text-sm outline-none resize-y"
                              placeholder="Paste the model / correct answer here (optional)..."
                              aria-label="Model answer"></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <i class="fas fa-image text-xs" style="color:#f59e0b"></i>
                        <label class="text-xs font-semibold" style="color:var(--text-secondary)">Upload Image <span class="font-normal">(optional)</span></label>
                    </div>
                    <div id="errorAnalysisImagePreviewContainer" class="hidden relative w-32 mb-2">
                        <img id="errorAnalysisImagePreview"
                             class="w-32 h-32 object-cover rounded-xl shadow-lg" alt="Image preview">
                        <button onclick="clearImagePreview('errorAnalysis')"
                                class="absolute top-1 right-1 w-6 h-6 flex items-center justify-center rounded-full text-white text-xs"
                                style="background:rgba(0,0,0,.55)" aria-label="Remove image">✕</button>
                    </div>
                    <div class="flex gap-2">
                        <label for="errorAnalysisPhotoInput" title="Upload Image"
                               class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary cursor-pointer">
                            <i class="fas fa-image" style="color:var(--primary)"></i>
                        </label>
                        <input type="file" id="errorAnalysisPhotoInput" accept="image/*"
                               onchange="previewImage('errorAnalysis')" class="hidden">
                        <button onclick="pasteImage('errorAnalysis')" title="Paste Image from clipboard"
                                class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary"
                                aria-label="Paste image from clipboard">
                            <i class="fas fa-clipboard" style="color:var(--primary)"></i>
                        </button>
                    </div>
                </div>

                <!-- Hidden buffer used by sendMessage('errorAnalysis') -->
                <input type="hidden" id="errorAnalysisInput">

                <button id="errorAnalysisSendBtn"
                        onclick="sendErrorAnalysis()"
                        class="w-full mt-2 py-3 rounded-xl text-white font-bold flex items-center justify-center gap-2 transition-all duration-200"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626)"
                        aria-label="Analyse my work">
                    <i class="fas fa-search-plus"></i>
                    Analyse My Work
                </button>
            </div>

            <!-- Analysis Results Area -->
            <div id="errorAnalysisChatBox" class="space-y-4" role="log" aria-label="Analysis results" aria-live="polite">
                <!-- Results appear here -->
            </div>

            <!-- Follow-up input area (appears after first analysis) -->
            <div id="eaFollowUpArea" class="mt-4 hidden">
                <div class="glass-card p-4">
                    <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text-secondary)">
                        <i class="fas fa-comment-dots mr-1"></i> Follow-up Question
                    </p>
                    <div class="flex items-end gap-2">
                        <textarea id="eaFollowUpInput"
                                  rows="2"
                                  class="input-field flex-1 px-3 py-2 text-sm outline-none resize-none"
                                  placeholder="Ask a follow-up question about the analysis..."
                                  oninput="this.style.height='auto';this.style.height=(this.scrollHeight)+'px';"
                                  aria-label="Follow-up question"></textarea>
                        <button onclick="sendEAFollowUp()"
                                class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl text-white font-bold transition-all duration-200"
                                style="background:linear-gradient(135deg,#ef4444,#dc2626)"
                                aria-label="Send follow-up">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword, createUserWithEmailAndPassword, signOut, onAuthStateChanged, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

        const app = initializeApp(window.__FIREBASE_CONFIG__);
        const auth = getAuth(app);
        const googleProvider = new GoogleAuthProvider();

        // DOM Elements
        const authScreen = document.getElementById('authScreen');
        const coverScreen = document.getElementById('coverScreen');
        const aiListScreen = document.getElementById('aiListScreen');
        const askAIInterface = document.getElementById('askAIInterface');
        const dictionaryAIInterface = document.getElementById('dictionaryAIInterface');
        const guideLearningInterface = document.getElementById('guideLearningInterface');
        const errorAnalysisInterface = document.getElementById('errorAnalysisInterface');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const signInButton = document.getElementById('sign-in-button');
        const registerButton = document.getElementById('register-button');
        const googleSignInButton = document.getElementById('google-sign-in-button');
        const signOutButtons = document.querySelectorAll('[id^="sign-out-button"]');
        const authError = document.getElementById('auth-error');

        // Loading state management
        function setLoadingState(element, isLoading) {
            if (isLoading) {
                element.disabled = true;
                element.innerHTML = `<div class="loading-dots mr-2"><span></span><span></span><span></span></div>Loading...`;
            } else {
                element.disabled = false;
                // Restore original content based on button type
                if (element.id === 'sign-in-button') {
                    element.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Sign In';
                } else if (element.id === 'register-button') {
                    element.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
                } else if (element.id === 'google-sign-in-button') {
                    element.innerHTML = `<svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>Continue with Google`;
                }
            }
        }

        // Firebase Authentication
        onAuthStateChanged(auth, (user) => {
            if (user) {
                authScreen.classList.add('hidden');
                coverScreen.classList.remove('hidden');
                console.log('User signed in:', user.email);
            } else {
                authScreen.classList.remove('hidden');
                coverScreen.classList.add('hidden');
                aiListScreen.classList.add('hidden');
                askAIInterface.classList.add('hidden');
                dictionaryAIInterface.classList.add('hidden');
                guideLearningInterface.classList.add('hidden');
                errorAnalysisInterface.classList.add('hidden');
                authError.classList.add('hidden');
                authError.textContent = '';
                console.log('No user signed in');
            }
        });

        signInButton.addEventListener('click', () => {
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (!email || !password) {
                showError('Please enter both email and password.');
                return;
            }
            
            setLoadingState(signInButton, true);
            
            signInWithEmailAndPassword(auth, email, password)
                .then(() => {
                    emailInput.value = '';
                    passwordInput.value = '';
                    hideError();
                })
                .catch((error) => {
                    showError(getAuthErrorMessage(error.code));
                })
                .finally(() => {
                    setLoadingState(signInButton, false);
                });
        });

        registerButton.addEventListener('click', () => {
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (!email || !password) {
                showError('Please enter both email and password.');
                return;
            }
            
            setLoadingState(registerButton, true);
            
            createUserWithEmailAndPassword(auth, email, password)
                .then(() => {
                    emailInput.value = '';
                    passwordInput.value = '';
                    hideError();
                })
                .catch((error) => {
                    showError(getAuthErrorMessage(error.code));
                })
                .finally(() => {
                    setLoadingState(registerButton, false);
                });
        });

        signOutButtons.forEach(btn => btn.addEventListener('click', () => {
            signOut(auth).then(() => console.log('Signed out')).catch((error) => {
                showError(getAuthErrorMessage(error.code));
            });
        }));

        googleSignInButton.addEventListener('click', () => {
            setLoadingState(googleSignInButton, true);
            signInWithPopup(auth, googleProvider)
                .then(() => {
                    hideError();
                })
                .catch((error) => {
                    showError(getAuthErrorMessage(error.code));
                })
                .finally(() => {
                    setLoadingState(googleSignInButton, false);
                });
        });

        function getAuthErrorMessage(code) {
            const messages = {
                'auth/invalid-email':            'The email address is not valid. Please enter a correct email.',
                'auth/user-not-found':           'No account found with this email. Please check or create a new account.',
                'auth/wrong-password':           'Incorrect password. Please try again.',
                'auth/invalid-credential':       'Incorrect email or password. Please try again.',
                'auth/email-already-in-use':     'This email is already registered. Please sign in instead.',
                'auth/weak-password':            'Password is too weak. Please use at least 6 characters.',
                'auth/too-many-requests':        'Too many failed attempts. Please wait a moment and try again.',
                'auth/network-request-failed':   'Network error. Please check your internet connection and try again.',
                'auth/user-disabled':            'This account has been disabled. Please contact support.',
                'auth/operation-not-allowed':    'This sign-in method is not enabled. Please contact support.',
                'auth/popup-closed-by-user':     'Sign-in popup was closed before completing. Please try again.',
                'auth/popup-blocked':            'Sign-in popup was blocked by the browser. Please allow popups and try again.',
                'auth/cancelled-popup-request':  'Another sign-in popup is already open. Please complete or close it first.',
                'auth/requires-recent-login':    'Please sign out and sign in again to perform this action.',
            };
            return messages[code] || 'Something went wrong. Please try again.';
        }

        function showError(message) {
            authError.textContent = message;
            authError.classList.remove('hidden');
        }

        function hideError() {
            authError.classList.add('hidden');
            authError.textContent = '';
        }

        // Make functions globally available
        window.auth = auth;

        // ── History API (MySQL via PHP) ─────────────────────────────────
        // Per-session doc IDs (one per AI tool type, reset on each showAI call)
        const _historyIds = { askAI: null, dictionaryAI: null, guideLearning: null, errorAnalysis: null };

        window.resetHistorySession = function(type) {
            _historyIds[type] = null;
        };

        async function getIdToken() {
            const user = auth.currentUser;
            if (!user) return null;
            return await user.getIdToken();
        }

        window.saveHistory = async function(type, userMsg, aiMsg, subject) {
            const token = await getIdToken();
            if (!token) return;
            const toolLabels = { askAI: 'Ask AI', dictionaryAI: 'Dictionary AI', guideLearning: 'Guide Learning', errorAnalysis: 'Error Analysis' };
            const userEntry  = { role: 'user',      content: userMsg, timestamp: new Date().toISOString() };
            const aiEntry    = { role: 'assistant', content: aiMsg,   timestamp: new Date().toISOString() };
            try {
                if (!_historyIds[type]) {
                    const res = await fetch('./api/history.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                        body: JSON.stringify({
                            tool: toolLabels[type] || type,
                            subject: subject,
                            page: 'index',
                            summary: userMsg.substring(0, 100),
                            messages: [userEntry, aiEntry]
                        })
                    });
                    if (res.ok) {
                        const data = await res.json();
                        _historyIds[type] = data.id;
                        // Generate AI title asynchronously (non-blocking)
                        _generateAITitle(type, userMsg, aiMsg, token, data.id);
                    }
                } else {
                    await fetch('./api/history.php?id=' + encodeURIComponent(_historyIds[type]), {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                        body: JSON.stringify({ messages: [userEntry, aiEntry] })
                    });
                }
            } catch (e) {
                console.warn('History save failed:', e);
            }
        };

        async function _generateAITitle(type, userMsg, aiMsg, token, sessionId) {
            try {
                const titlePrompt = `Generate a concise chat title (5 words or fewer) that summarises this conversation. Return only the title text, no punctuation or quotes.\n\nUser: ${userMsg.substring(0, 200)}\nAI: ${aiMsg.substring(0, 200)}`;
                const res = await fetch('./api/ai_proxy.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({
                        subject: 'English',
                        mode: 'ask',
                        model: 'gemini-fast',
                        stream: false,
                        messages: [{ role: 'user', content: titlePrompt }],
                        max_tokens: 20,
                        temperature: 0.7
                    })
                });
                if (!res.ok) return;
                const data = await res.json();
                const title = (data.choices?.[0]?.message?.content || '').trim().replace(/^["']|["']$/g, '').substring(0, 100);
                if (!title) return;
                await fetch('./api/history.php?id=' + encodeURIComponent(sessionId), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({ summary: title })
                });
            } catch (e) {
                console.warn('AI title generation failed:', e);
            }
        }

        window.deleteHistorySession = async function(userId, sessionId) {
            const token = await getIdToken();
            if (!token) return false;
            try {
                const res = await fetch('./api/history.php?id=' + encodeURIComponent(sessionId), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                return res.ok;
            } catch (e) {
                console.warn('History delete failed:', e);
                return false;
            }
        };

        window.loadHistory = async function(userId, afterDoc = null) {
            const token = await getIdToken();
            if (!token) return { docs: [], lastDoc: null };
            try {
                let url = './api/history.php?limit=20';
                if (afterDoc) url += '&after=' + encodeURIComponent(afterDoc);
                const res = await fetch(url, {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) return { docs: [], lastDoc: null };
                const data = await res.json();
                const docs = data.docs || [];
                const lastDoc = docs.length > 0 ? docs[docs.length - 1].updated_at : null;
                return { docs, lastDoc };
            } catch (e) {
                console.warn('History load failed:', e);
                return { docs: [], lastDoc: null };
            }
        };

        window.continueHistorySession = function(type, sessionId) {
            if (!sessionId) return;
            _historyIds[type] = sessionId;
        };
    </script>

    <script>
        const FALLBACK_MODELS = ['gemini-search', 'gemini-fast', 'glm', 'openai-fast', 'openai', 'deepseek'];
        // glm and deepseek do not support vision; only use these models when an image is included
        const VISION_MODELS = ['gemini-search', 'gemini-fast', 'openai-fast', 'openai'];


        // Global variables
        let currentSubject = '';
        let currentMode = '';
        let histories = {
            'askAI': [],
            'dictionaryAI': [],
            'guideLearning': [],
            'errorAnalysis': []
        };

        // Theme management
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Load theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        // Theme toggle event listeners
        document.querySelectorAll('[id^="theme-toggle"]').forEach(btn => {
            btn.addEventListener('click', toggleTheme);
        });

        // Navigation Functions
        function showCover() {
            document.getElementById('coverScreen').classList.remove('hidden');
            document.getElementById('aiListScreen').classList.add('hidden');
            document.getElementById('askAIInterface').classList.add('hidden');
            document.getElementById('dictionaryAIInterface').classList.add('hidden');
            document.getElementById('guideLearningInterface').classList.add('hidden');
            document.getElementById('errorAnalysisInterface').classList.add('hidden');
        }

        function showAIList(subject) {
            currentSubject = subject;
            document.getElementById('coverScreen').classList.add('hidden');
            document.getElementById('aiListScreen').classList.remove('hidden');
            document.getElementById('askAIInterface').classList.add('hidden');
            document.getElementById('dictionaryAIInterface').classList.add('hidden');
            document.getElementById('guideLearningInterface').classList.add('hidden');
            document.getElementById('errorAnalysisInterface').classList.add('hidden');

            document.getElementById('aiListHeader').innerHTML = `<i class="fas fa-${getSubjectIcon(subject)} mr-3"></i>${subject} AI Tools`;
            
            const aiOptions = document.getElementById('aiOptions');
            const tools = [
                {
                    name: 'Ask AI',
                    icon: 'fas fa-robot',
                    description: 'Get detailed answers to your questions',
                    action: () => showAI(subject, 'askAI'),
                    color: 'from-blue-400 to-blue-600',
                    iconColorClass: 'text-white'
                },
                {
                    name: 'Dictionary AI',
                    icon: 'fas fa-book',
                    description: 'Look up terms and concepts',
                    action: () => showAI(subject, 'dictionaryAI'),
                    color: 'from-green-400 to-green-600',
                    iconColorClass: 'text-white'
                },
                {
                    name: 'Guide Learning',
                    icon: 'fas fa-compass',
                    description: 'Get guided hints and step-by-step help',
                    action: () => showAI(subject, 'guideLearning'),
                    color: 'from-purple-400 to-purple-600',
                    iconColorClass: 'text-white'
                },
            ];

            // Add Error Analysis for all subjects except 中文 (no 中文-specific analysis prompt exists)
            if (subject !== '中文') {
                tools.push({
                    name: 'Error Analysis',
                    icon: 'fas fa-search-plus',
                    description: 'Paste your answer – AI identifies your mistakes and explains how to improve',
                    action: () => showAI(subject, 'errorAnalysis'),
                    color: 'from-red-400 to-rose-600',
                    iconColorClass: 'text-white'
                });
            }

            // Add subject-specific tools
            if (subject === '中文') {
                tools.push({
                    name: '文樞--古典文學互動學習平台',
                    icon: 'fas fa-scroll',
                    description: '古典文學互動學習',
                    action: () => { window.location.href = './mensyu2.php?back=' + encodeURIComponent(subject); },
                    color: 'from-red-400 to-red-600',
                    iconColorClass: 'text-white'
                });
            }

            if (subject === 'English') {
                tools.push(
                    {
                        name: 'English Writing Practice',
                        icon: 'fas fa-pen-fancy',
                        description: 'Improve your English writing skills',
                        action: () => { window.location.href = './eng-writing.php?back=' + encodeURIComponent(subject); },
                        color: 'from-teal-500 to-teal-700',
                        iconColorClass: 'text-white'
                    },
                    {
                        name: 'Vocabulary Generator',
                        icon: 'fas fa-lightbulb',
                        description: 'Generate custom vocabulary lists',
                        action: () => { window.location.href = './vocab.php?back=' + encodeURIComponent(subject) + '#generator'; },
                        color: 'from-amber-500 to-amber-700',
                        iconColorClass: 'text-white'
                    },
                    {
                        name: 'Vocabulary Quiz',
                        icon: 'fas fa-question-circle',
                        description: 'Test your vocabulary knowledge',
                        action: () => { window.location.href = './vocab.php?back=' + encodeURIComponent(subject) + '#quiz'; },
                        color: 'from-red-400 to-red-600',
                        iconColorClass: 'text-white'
                    }
                );
            }

            if (['Physics', 'Math', 'Biology'].includes(subject)) {
                tools.push({
                    name: 'Exam Paper Generator',
                    icon: 'fas fa-file-signature',
                    description: 'Generate practice exam papers',
                    action: () => { window.location.href = './exam.php?back=' + encodeURIComponent(subject); },
                    color: 'from-orange-500 to-orange-700',
                    iconColorClass: 'text-white'
                });
            }

            if (subject === 'ICT') {
                tools.push({
                    name: 'ICT Coding Review Platform',
                    icon: 'fas fa-terminal',
                    description: 'Review and improve your code',
                    action: () => { window.location.href = './coding.php?back=' + encodeURIComponent(subject); },
                    color: 'from-cyan-600 to-cyan-800',
                    iconColorClass: 'text-white'
                });
                tools.push({
                    name: 'Code Completion Exercise',
                    icon: 'fas fa-puzzle-piece',
                    description: 'Fill-in-the-blank Python exercises with AI feedback',
                    action: () => { window.location.href = './code-completion.php?back=' + encodeURIComponent(subject); },
                    color: 'from-emerald-500 to-emerald-700',
                    iconColorClass: 'text-white'
                });
            }

            aiOptions.innerHTML = tools.map((tool, idx) => `
                <div class="glass-card tool-card p-5 animate-fadeInUp"
                     style="animation-delay:${idx * 0.08}s"
                     onclick="handleToolClick('${tool.name}', '${subject}')"
                     role="button" tabindex="0" aria-label="${tool.name} for ${subject}">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br ${tool.color} shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="${tool.icon} text-xl text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold mb-0.5" style="color:var(--text-primary)">${tool.name}</h3>
                            <p class="text-sm truncate" style="color:var(--text-secondary)">${tool.description}</p>
                        </div>
                        <i class="fas fa-chevron-right flex-shrink-0 text-sm" style="color:var(--primary)"></i>
                    </div>
                </div>
            `).join('');
        }

        function handleToolClick(toolName, subject) {
            if (toolName === 'Ask AI') {
                showAI(subject, 'askAI');
            } else if (toolName === 'Dictionary AI') {
                showAI(subject, 'dictionaryAI');
            } else if (toolName === 'Guide Learning') {
                showAI(subject, 'guideLearning');
            } else if (toolName === '文樞--古典文學互動學習平台') {
                window.location.href = './mensyu2.php?back=' + encodeURIComponent(subject);
            } else if (toolName === 'English Writing Practice') {
                window.location.href = './eng-writing.php?back=' + encodeURIComponent(subject);
            } else if (toolName === 'Error Analysis') {
                showAI(subject, 'errorAnalysis');
            } else if (toolName === 'ICT Coding Review Platform') {
                window.location.href = './coding.php?back=' + encodeURIComponent(subject);
            } else if (toolName === 'Code Completion Exercise') {
                window.location.href = './code-completion.php?back=' + encodeURIComponent(subject);
            } else if (toolName === 'Exam Paper Generator') {
                window.location.href = './exam.php?back=' + encodeURIComponent(subject);
            } else if (toolName === 'Vocabulary Generator') {
                window.location.href = './vocab.php?back=' + encodeURIComponent(subject) + '#generator';
            } else if (toolName === 'Vocabulary Quiz') {
                window.location.href = './vocab.php?back=' + encodeURIComponent(subject) + '#quiz';
            }
        }

        function getSubjectIcon(subject) {
            const icons = {
                '中文': 'dragon',
                'English': 'book-open',
                'Math': 'calculator',
                'Physics': 'atom',
                'Biology': 'dna',
                'ICT': 'laptop-code'
            };
            return icons[subject] || 'book';
        }

        function showAI(subject, type) {
            currentSubject = subject;
            currentMode = type;
            document.getElementById('coverScreen').classList.add('hidden');
            document.getElementById('aiListScreen').classList.add('hidden');
            document.getElementById('askAIInterface').classList.add('hidden');
            document.getElementById('dictionaryAIInterface').classList.add('hidden');
            document.getElementById('guideLearningInterface').classList.add('hidden');
            document.getElementById('errorAnalysisInterface').classList.add('hidden');
            
            document.getElementById(type + 'Interface').classList.remove('hidden');

            const headers = {
                askAI: document.getElementById('askAIHeader'),
                dictionaryAI: document.getElementById('dictionaryAIHeader'),
                guideLearning: document.getElementById('guideLearningHeader'),
                errorAnalysis: document.getElementById('errorAnalysisHeader')
            };
            
            const toolNames = {
                askAI: 'Ask AI',
                dictionaryAI: 'Dictionary AI',
                guideLearning: 'Guide Learning',
                errorAnalysis: 'Error Analysis'
            };

            headers[type].innerHTML = `<i class="fas fa-${getToolIcon(type)} mr-2 text-${getToolColor(type)}-500"></i>${toolNames[type]} - ${subject}`;
            
            // Update Error Analysis subtitle with subject-specific description
            if (type === 'errorAnalysis') {
                const eaDescs = {
                    'English': 'Paste your writing or answer. AI identifies grammar, vocabulary, and coherence errors.',
                    'Math': 'Paste your working or solution. AI identifies calculation and conceptual errors with step-by-step correction.',
                    'Physics': 'Paste your answer or calculation. AI identifies physics errors and explains the correct approach.',
                    'Biology': 'Paste your answer. AI identifies factual, terminology, and explanation errors.',
                    'ICT': 'Paste your code or answer. AI identifies syntax, logical, and algorithmic errors.'
                };
                const subDescEl = document.getElementById('errorAnalysisSubDesc');
                if (subDescEl) subDescEl.textContent = eaDescs[subject] || 'Paste your answer below for AI analysis';
                // Reset the follow-up area when opening Error Analysis
                const followUp = document.getElementById('eaFollowUpArea');
                if (followUp) followUp.classList.add('hidden');
            }
            
            histories[type] = [];
            
            resetChatBox(type, subject);
            // Reset Firestore session so the next message starts a fresh history doc
            if (window.resetHistorySession) window.resetHistorySession(type);
            document.getElementById(type + 'Input').focus();
        }

        function getToolIcon(type) {
            const icons = {
                askAI: 'robot',
                dictionaryAI: 'book',
                guideLearning: 'compass',
                errorAnalysis: 'search-plus'
            };
            return icons[type] || 'robot';
        }

        function getToolColor(type) {
            const colors = {
                askAI: 'blue',
                dictionaryAI: 'green',
                guideLearning: 'purple',
                errorAnalysis: 'red'
            };
            return colors[type] || 'blue';
        }

        function resetChatBox(type, subject) {
            const chatBox = document.getElementById(type + 'ChatBox');

            // Error Analysis uses a form-based UI – just clear the results area
            if (type === 'errorAnalysis') {
                chatBox.innerHTML = '';
                const followUp = document.getElementById('eaFollowUpArea');
                if (followUp) followUp.classList.add('hidden');
                return;
            }

            const toolNames = {
                askAI: 'AI Assistant',
                dictionaryAI: 'Dictionary AI',
                guideLearning: 'Guide AI'
            };
            
            const welcomeMessages = {
                askAI: `Hello! How can I assist you with ${subject} today?`,
                dictionaryAI: 'Hello! Enter a term to learn more.',
                guideLearning: 'Hello! Ask a question to get started with guided learning. I will help you think through it step by step using hints. After my response, you can click <strong>💡 Get Hint</strong> to get a more specific hint if you need one.'
            };

            chatBox.innerHTML = `
                <div class="flex justify-start">
                    <div class="message-bubble ai-bubble p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background:linear-gradient(135deg,var(--primary),var(--secondary))">
                                <i class="fas fa-${getToolIcon(type)} text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm mb-1" style="color:var(--text-primary)">${toolNames[type]}</p>
                                <p class="text-sm" style="color:var(--text-primary)">${welcomeMessages[type]}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Image handling functions
        function previewImage(type) {
            const preview = document.getElementById(`${type}ImagePreview`);
            const container = document.getElementById(`${type}ImagePreviewContainer`);
            const file = document.getElementById(`${type}PhotoInput`).files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                container.classList.add('hidden');
            }
        }

        function clearImagePreview(type) {
            const preview = document.getElementById(`${type}ImagePreview`);
            const container = document.getElementById(`${type}ImagePreviewContainer`);
            const input = document.getElementById(`${type}PhotoInput`);
            
            preview.src = '';
            container.classList.add('hidden');
            input.value = '';
        }

        async function pasteImage(type) {
            try {
                if (!navigator.clipboard?.read) {
                    showNotification('Clipboard access is not supported by your browser.', 'warning');
                    return;
                }
                const items = await navigator.clipboard.read();
                for (const item of items) {
                    if (item.types.some(t => t.startsWith('image/'))) {
                        const blob = await item.getType(item.types.find(t => t.startsWith('image/')));
                        const input = document.getElementById(`${type}PhotoInput`);
                        
                        const file = new File([blob], 'pasted-image.png', { type: blob.type });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        previewImage(type);
                        return;
                    }
                }
                showNotification('No image found in clipboard!', 'warning');
            } catch (error) {
                console.error("Paste error:", error);
                showNotification('Failed to paste image! Please try uploading.', 'error');
            }
        }
        
        // ===== START: DEBUGGED AND UPDATED CODE =====

        // New helper function to convert an image file to a Base64 string
        async function toBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

        // Replaced sendMessage function to handle image encoding
        async function sendMessage(type) {
            const input = document.getElementById(`${type}Input`);
            // The dictionary AI does not have a file input, so we use optional chaining
            const fileInput = document.getElementById(`${type}PhotoInput`);
            const sendBtn = document.getElementById(`${type}SendBtn`);

            const question = input.value.trim();
            const file = fileInput?.files[0];
            
            if (!question && !file) {
                const errorMessage = type === 'dictionaryAI' 
                    ? 'Please enter a term!' 
                    : 'Please enter a question or upload an image!';
                showNotification(errorMessage, 'warning');
                return;
            }

            const userContent = question || `Analyze this ${currentSubject}-related image.`;

            addUserMessage(type, userContent, file);

            input.value = '';
            input.style.height = 'auto';
            if (fileInput) {
                clearImagePreview(type);
            }

            setButtonLoading(sendBtn, true);
            const loadingMessageId = addLoadingMessage(type);

            try {
                let base64Image = null;
                if (file) {
                    base64Image = await toBase64(file);
                }

                await sendRequest(type, userContent, loadingMessageId, base64Image);

            } catch (error) {
                removeMessage(loadingMessageId);
                addAIMessage(type, `Error: ${error.message}`, true);
                console.error('Error:', error);
            } finally {
                setButtonLoading(sendBtn, false);
            }
        }
        
        // Streaming sendRequest function
        async function sendRequest(type, messageContent, loadingMessageId, base64Image = null) {
            let userMessagePayload;
            if (base64Image) {
                userMessagePayload = {
                    role: "user",
                    content: [
                        { type: "image_url", image_url: { url: base64Image, detail: "high" } },
                        { type: "text", text: messageContent }
                    ]
                };
            } else {
                userMessagePayload = { role: "user", content: messageContent };
            }
            histories[type].push(userMessagePayload);
            const validMessages = histories[type].filter(msg => msg.content);
            if (validMessages.length === 0) throw new Error('No valid messages to send.');

            const modelsToTry = base64Image ? VISION_MODELS : FALLBACK_MODELS;

            function getMessagesForModel(model, messages) {
                if (VISION_MODELS.includes(model)) return messages;
                return messages.map(msg => {
                    if (Array.isArray(msg.content)) {
                        const textPart = msg.content.find(p => p.type === 'text');
                        if (!textPart) return null;
                        return { ...msg, content: textPart.text };
                    }
                    return msg;
                }).filter(msg => msg && msg.content);
            }

            // Remove the plain loading bubble and create a streaming bubble
            removeMessage(loadingMessageId);
            const streamId = 'stream-' + Date.now();
            addStreamingBubble(type, streamId);

            let aiResponse = null;
            for (const model of modelsToTry) {
                try {
                    const messagesForModel = getMessagesForModel(model, validMessages);
                    const token = await window.auth.currentUser?.getIdToken();
                    const response = await fetch('./api/ai_proxy.php', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'X-Firebase-Token': token
                        },
                        body: JSON.stringify({
                            subject: currentSubject,
                            mode: currentMode,
                            messages: messagesForModel,
                            model: model,
                            stream: true,
                            temperature: 0,
                            max_tokens: 4096
                        })
                    });

                    if (!response.ok) {
                        console.warn(`Model ${model} failed with status ${response.status}`);
                        continue;
                    }

                    // Stream the response
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    let accum = '';
                    let streamedText = '';
                    let done = false;

                    while (!done) {
                        const { value, done: doneReading } = await reader.read();
                        done = doneReading;
                        if (value) {
                            accum += decoder.decode(value, { stream: true });
                            const lines = accum.split('\n');
                            accum = lines.pop(); // keep incomplete last line
                            for (const line of lines) {
                                const trimmed = line.trim();
                                if (!trimmed || trimmed === 'data: [DONE]') continue;
                                if (trimmed.startsWith('data: ')) {
                                    try {
                                        const json = JSON.parse(trimmed.slice(6));
                                        const delta = json.choices?.[0]?.delta?.content;
                                        if (delta) {
                                            streamedText += delta;
                                            updateStreamingBubble(streamId, streamedText);
                                        }
                                    } catch (e) { console.warn('Malformed SSE chunk:', trimmed, e); }
                                }
                            }
                        }
                    }

                    if (streamedText) {
                        aiResponse = streamedText;
                        break;
                    }
                    console.warn(`Model ${model} streaming returned no content`);
                } catch (modelError) {
                    console.warn(`Model ${model} streaming failed: ${modelError.message}`);
                }
            }

            if (!aiResponse) {
                removeStreamingBubble(streamId);
                throw new Error('All models failed to generate a response');
            }

            finalizeStreamingBubble(type, streamId, aiResponse);
            histories[type].push({ role: "assistant", content: aiResponse });

            if (window.saveHistory) {
                window.saveHistory(type, messageContent, aiResponse, currentSubject);
            }
        }

        function addStreamingBubble(type, streamId) {
            const chatBox = document.getElementById(`${type}ChatBox`);
            const messageDiv = document.createElement('div');
            messageDiv.id = streamId;
            messageDiv.className = 'flex justify-start';
            messageDiv.innerHTML = `
                <div class="message-bubble ai-bubble p-4 max-w-xs md:max-w-md lg:max-w-2xl">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,var(--primary),var(--secondary))">
                            <i class="fas fa-${getToolIcon(type)} text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm mb-2" style="color:var(--text-primary)">AI Assistant</p>
                            <div class="stream-content prose prose-sm max-w-none text-sm" style="color:var(--text-primary)">
                                <span class="stream-cursor">&#9611;</span>
                            </div>
                        </div>
                    </div>
                </div>`;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function updateStreamingBubble(streamId, text) {
            const bubble = document.getElementById(streamId);
            if (!bubble) return;
            const content = bubble.querySelector('.stream-content');
            if (!content) return;
            content.innerHTML = formatMarkdown(text) + '<span class="stream-cursor">&#9611;</span>';
            const chatBox = bubble.closest('[id$="ChatBox"]');
            if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
        }

        function finalizeStreamingBubble(type, streamId, fullText) {
            const bubble = document.getElementById(streamId);
            if (!bubble) return;
            const isGuide = type === 'guideLearning';
            const content = bubble.querySelector('.stream-content');
            if (content) {
                content.innerHTML = formatMarkdown(fullText);
                content.classList.remove('stream-content');
                content.classList.add('prose', 'prose-sm', 'max-w-none', 'text-sm');
            }
            if (['Math', 'Physics'].includes(currentSubject) && window.MathJax) {
                MathJax.typesetPromise([bubble]).catch(e => console.warn('MathJax error:', e));
            }
            const innerDiv = bubble.querySelector('.flex-1');
            if (innerDiv) {
                const hintBtn = isGuide ? `
                    <button onclick="requestHint('guideLearning')" class="msg-action-btn" style="background:rgba(139,92,246,.12);color:#7c3aed;" aria-label="Get another hint">
                        <i class="fas fa-lightbulb mr-1"></i>&#128161; Get Hint
                    </button>` : '';
                const actionsDiv = document.createElement('div');
                actionsDiv.className = 'mt-3 flex gap-2 flex-wrap';
                actionsDiv.innerHTML = `
                    <button onclick="copyMessage(this)" class="msg-action-btn" aria-label="Copy message">
                        <i class="fas fa-copy mr-1"></i>Copy
                    </button>
                    <button onclick="shareMessage(this)" class="msg-action-btn" aria-label="Share message">
                        <i class="fas fa-share mr-1"></i>Share
                    </button>
                    ${hintBtn}`;
                innerDiv.appendChild(actionsDiv);
            }
            // Show follow-up area after first Error Analysis response
            if (type === 'errorAnalysis') {
                const followUp = document.getElementById('eaFollowUpArea');
                if (followUp) followUp.classList.remove('hidden');
            }
        }

        function removeStreamingBubble(streamId) {
            const bubble = document.getElementById(streamId);
            if (bubble) bubble.remove();
        }

        function requestHint(type) {
            if (type !== 'guideLearning') return;
            const input = document.getElementById(`${type}Input`);
            input.value = 'Please give me a more specific hint for the same question.';
            sendMessage(type);
        }

        function clearEASession() {
            const chatBox = document.getElementById('errorAnalysisChatBox');
            if (chatBox) chatBox.innerHTML = '';
            const followUp = document.getElementById('eaFollowUpArea');
            if (followUp) followUp.classList.add('hidden');
            const followInput = document.getElementById('eaFollowUpInput');
            if (followInput) { followInput.value = ''; followInput.style.height = 'auto'; }
            // Clear structured input fields
            ['eaQuestionInput', 'eaAnswerInput', 'eaModelInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            const mainInput = document.getElementById('errorAnalysisInput');
            if (mainInput) mainInput.value = '';
            // Clear image preview if present
            try { clearImagePreview('errorAnalysis'); } catch(e) {}
            histories['errorAnalysis'] = [];
            if (window.resetHistorySession) window.resetHistorySession('errorAnalysis');
        }

        // Compose structured analysis from multi-part fields, then send
        // Marker prefixes used both here and in addUserMessage to detect structured content
        const EA_MARKER = {
            QUESTION: '📌 Exam Question:',
            ANSWER:   '✍️ My Answer / Working:',
            MODEL:    '✅ Model Answer:'
        };

        function sendErrorAnalysis() {
            const question = (document.getElementById('eaQuestionInput')?.value || '').trim();
            const answer   = (document.getElementById('eaAnswerInput')?.value || '').trim();
            const model    = (document.getElementById('eaModelInput')?.value || '').trim();
            const fileInput = document.getElementById('errorAnalysisPhotoInput');
            const file = fileInput?.files[0];

            if (!answer && !file) {
                showNotification('Please enter your answer / working or upload an image!', 'warning');
                return;
            }

            // Compose structured prompt using shared markers
            let composed = '';
            if (question) composed += `${EA_MARKER.QUESTION}\n${question}\n\n`;
            composed += `${EA_MARKER.ANSWER}\n${answer || '[See uploaded image]'}`;
            if (model) composed += `\n\n${EA_MARKER.MODEL}\n${model}`;

            // Set into hidden buffer for sendMessage to read
            const mainInput = document.getElementById('errorAnalysisInput');
            if (mainInput) mainInput.value = composed;

            // Clear visible fields after composing
            ['eaQuestionInput', 'eaAnswerInput', 'eaModelInput'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            sendMessage('errorAnalysis');
        }

        function sendEAFollowUp() {
            const input = document.getElementById('eaFollowUpInput');
            const question = input ? input.value.trim() : '';
            if (!question) return;
            // Temporarily redirect sendMessage to use the follow-up input
            const mainInput = document.getElementById('errorAnalysisInput');
            if (mainInput) {
                mainInput.value = question;
                input.value = '';
                input.style.height = 'auto';
            }
            sendMessage('errorAnalysis');
        }

        // ===== END: DEBUGGED AND UPDATED CODE =====

        function addUserMessage(type, content, file) {
            const chatBox = document.getElementById(`${type}ChatBox`);
            const messageDiv = document.createElement('div');

            let imageHTML = '';
            if (file) {
                const imageURL = URL.createObjectURL(file);
                imageHTML = `<img src="${imageURL}" class="message-image max-w-full h-32 object-cover rounded-xl mt-2" alt="Uploaded image">`;
            }

            // Error Analysis: distinguish initial structured analysis from follow-up questions
            if (type === 'errorAnalysis') {
                const _esc = s => String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                // Detect structured initial analysis using shared EA_MARKER constants
                const isStructured = content.startsWith(EA_MARKER.QUESTION) || content.startsWith(EA_MARKER.ANSWER);
                if (isStructured) {
                    const qMatch = content.match(/📌 Exam Question:\n([\s\S]*?)(?:\n\n✍️|$)/);
                    const aMatch = content.match(/✍️ My Answer \/ Working:\n([\s\S]*?)(?:\n\n✅|$)/);
                    const mMatch = content.match(/✅ Model Answer:\n([\s\S]*)/);
                    const qText = qMatch ? qMatch[1].trim() : '';
                    const aText = aMatch ? aMatch[1].trim() : '';
                    const mText = mMatch ? mMatch[1].trim() : '';
                    messageDiv.className = '';
                    messageDiv.innerHTML = `
                        <div style="border:1.5px solid rgba(239,68,68,.22);border-radius:14px;overflow:hidden;margin-bottom:.5rem">
                            ${qText ? `<div style="padding:.75rem 1rem;border-bottom:1px solid rgba(124,58,237,.15);background:rgba(124,58,237,.04)">
                                <p class="font-bold text-xs mb-1 uppercase tracking-wider" style="color:#7c3aed"><i class="fas fa-question-circle mr-1"></i>Exam Question</p>
                                <p class="text-sm" style="color:var(--text-primary);white-space:pre-wrap;word-break:break-word">${_esc(qText)}</p>
                            </div>` : ''}
                            <div style="padding:.75rem 1rem;${mText ? 'border-bottom:1px solid rgba(239,68,68,.12);' : ''}background:rgba(239,68,68,.04)">
                                <p class="font-bold text-xs mb-1 uppercase tracking-wider" style="color:#ef4444"><i class="fas fa-pen mr-1"></i>My Answer / Working</p>
                                <p class="text-sm" style="color:var(--text-primary);white-space:pre-wrap;word-break:break-word">${_esc(aText || '[See image below]')}</p>
                                ${imageHTML}
                            </div>
                            ${mText ? `<div style="padding:.75rem 1rem;background:rgba(16,185,129,.04)">
                                <p class="font-bold text-xs mb-1 uppercase tracking-wider" style="color:#10b981"><i class="fas fa-check-circle mr-1"></i>Model Answer</p>
                                <p class="text-sm" style="color:var(--text-primary);white-space:pre-wrap;word-break:break-word">${_esc(mText)}</p>
                            </div>` : ''}
                        </div>`;
                } else {
                    // Follow-up question: display as a normal user chat bubble
                    messageDiv.className = 'flex justify-end';
                    messageDiv.innerHTML = `
                        <div class="message-bubble user-bubble p-4 max-w-xs md:max-w-md lg:max-w-lg">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm mb-1 text-white opacity-80">You</p>
                                    <p class="text-sm text-white">${_esc(content).replace(/\n/g,'<br>')}</p>
                                    ${imageHTML}
                                </div>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                     style="background:rgba(255,255,255,.20)">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                            </div>
                        </div>`;
                }
            } else {
                messageDiv.className = 'flex justify-end';
                messageDiv.innerHTML = `
                    <div class="message-bubble user-bubble p-4 max-w-xs md:max-w-md lg:max-w-lg">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm mb-1 text-white opacity-80">You</p>
                                <p class="text-sm text-white">${content.replace(/\n/g, '<br>')}</p>
                                ${imageHTML}
                            </div>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(255,255,255,.20)">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function addLoadingMessage(type) {
            const chatBox = document.getElementById(`${type}ChatBox`);
            const messageDiv = document.createElement('div');
            const messageId = 'loading-' + Date.now();
            messageDiv.id = messageId;
            messageDiv.className = 'flex justify-start';
            
            messageDiv.innerHTML = `
                <div class="message-bubble ai-bubble p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,var(--primary),var(--secondary))">
                            <i class="fas fa-${getToolIcon(type)} text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm mb-2" style="color:var(--text-primary)">AI Assistant</p>
                            <div class="loading-dots"><span></span><span></span><span></span></div>
                        </div>
                    </div>
                </div>
            `;
            
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            return messageId;
        }

        function removeMessage(messageId) {
            const message = document.getElementById(messageId);
            if (message) {
                message.remove();
            }
        }

        function addAIMessage(type, content, isError = false) {
            const chatBox = document.getElementById(`${type}ChatBox`);
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex justify-start';
            
            const bgColor = isError ? 'bg-red-100 dark:bg-red-900' : 'bg-gray-100 dark:bg-gray-700';
            const textColor = isError ? 'text-red-900 dark:text-red-100' : 'text-gray-900 dark:text-white';
            
            const errorStyle = isError ? 'background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.20);' : '';
            const errorColor = isError ? '#ef4444' : 'var(--text-primary)';
            messageDiv.innerHTML = `
                <div class="message-bubble ai-bubble p-4 max-w-xs md:max-w-md lg:max-w-2xl" style="${errorStyle}">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,var(--primary),var(--secondary))">
                            <i class="fas fa-${getToolIcon(type)} text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm mb-2" style="color:${errorColor}">AI Assistant</p>
                            <div class="prose prose-sm max-w-none text-sm" style="color:var(--text-primary)">${formatMarkdown(content)}</div>
                            ${!isError ? `
                                <div class="mt-3 flex gap-2 flex-wrap">
                                    <button onclick="copyMessage(this)" class="msg-action-btn" aria-label="Copy message">
                                        <i class="fas fa-copy mr-1"></i>Copy
                                    </button>
                                    <button onclick="shareMessage(this)" class="msg-action-btn" aria-label="Share message">
                                        <i class="fas fa-share mr-1"></i>Share
                                    </button>
                                    ${type === 'guideLearning' ? `
                                    <button onclick="requestHint('guideLearning')" class="msg-action-btn" style="background:rgba(139,92,246,.12);color:#7c3aed;" aria-label="Get another hint">
                                        <i class="fas fa-lightbulb mr-1"></i>💡 Get Hint
                                    </button>` : ''}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            // Render math for Math and Physics subjects
            if (!isError && ['Math', 'Physics'].includes(currentSubject) && window.MathJax) {
                MathJax.typesetPromise([messageDiv]).catch(e => console.warn('MathJax error:', e));
            }
            // Show follow-up area after Error Analysis response
            if (!isError && type === 'errorAnalysis') {
                const followUp = document.getElementById('eaFollowUpArea');
                if (followUp) followUp.classList.remove('hidden');
            }
        }

        function setButtonLoading(button, isLoading) {
            button.disabled = isLoading;
            const iconContainer = button;
            
            if (isLoading) {
                iconContainer.innerHTML = `
                    <div class="loading-dots" style="transform: scale(1.5);">
                        <span></span><span></span><span></span>
                    </div>`;
            } else {
                if (button.id.includes('dictionary')) {
                    iconContainer.innerHTML = `<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>`;
                } else if (button.id.includes('errorAnalysis')) {
                    iconContainer.innerHTML = `<i class="fas fa-search-plus mr-2"></i>Analyse My Work`;
                } else {
                    iconContainer.innerHTML = `<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>`;
                }
            }
        }

        function copyMessage(button) {
            const messageContent = button.closest('.message-bubble').querySelector('.prose').textContent;
            navigator.clipboard.writeText(messageContent).then(() => {
                showNotification('Message copied to clipboard!', 'success');
                button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied';
                setTimeout(() => {
                    button.innerHTML = '<i class="fas fa-copy mr-1"></i>Copy';
                }, 2000);
            }).catch(() => {
                showNotification('Failed to copy message!', 'error');
            });
        }

        function shareMessage(button) {
            const messageContent = button.closest('.message-bubble').querySelector('.prose').textContent;
            if (navigator.share) {
                navigator.share({
                    title: 'AI Response',
                    text: messageContent
                }).catch(() => {
                    copyMessage(button);
                });
            } else {
                copyMessage(button);
            }
        }

        function formatMarkdown(text) {
            if (!text) return '';

            // Step 1: Protect code blocks and math expressions so their content is not mangled
            const codeBlocks = [];
            let html = text.replace(/```[\s\S]*?```/g, (match) => {
                const inner = match.replace(/^```[^\n]*\n?/, '').replace(/```$/, '');
                const placeholder = `%%CODEBLOCK_${codeBlocks.length}%%`;
                codeBlocks.push(`<pre class="bg-gray-200 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 p-3 rounded-lg overflow-x-auto my-2"><code>${inner.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</code></pre>`);
                return placeholder;
            });

            // Protect display math \[...\]
            const mathBlocks = [];
            html = html.replace(/\\\[[\s\S]*?\\\]/g, (match) => {
                const placeholder = `%%MATHBLOCK_${mathBlocks.length}%%`;
                mathBlocks.push(`<div class="overflow-x-auto my-2">${match}</div>`);
                return placeholder;
            });
            // Protect inline math \(...\)
            html = html.replace(/\\\([\s\S]*?\\\)/g, (match) => {
                const placeholder = `%%MATHBLOCK_${mathBlocks.length}%%`;
                mathBlocks.push(match);
                return placeholder;
            });
            // Protect display math $$...$$
            html = html.replace(/\$\$([\s\S]*?)\$\$/g, (match) => {
                const placeholder = `%%MATHBLOCK_${mathBlocks.length}%%`;
                mathBlocks.push(`<div class="overflow-x-auto my-2">${match}</div>`);
                return placeholder;
            });
            // Protect inline math $...$
            html = html.replace(/\$([^\$\n]+?)\$/g, (match) => {
                const placeholder = `%%MATHBLOCK_${mathBlocks.length}%%`;
                mathBlocks.push(match);
                return placeholder;
            });

            // Step 2: Process line by line
            const lines = html.split('\n');
            const output = [];
            let listItems = [];
            let tableLines = [];

            const flushList = () => {
                if (listItems.length > 0) {
                    output.push(`<ul class="list-disc list-inside space-y-1 my-2">${listItems.join('')}</ul>`);
                    listItems = [];
                }
            };

            const flushTable = () => {
                // A valid Markdown table needs at least a header row + separator row (|---|---|)
                if (tableLines.length < 2) {
                    // Not enough lines for a valid table, emit as paragraphs
                    tableLines.forEach(l => output.push(`<p class="my-1">${applyInline(l)}</p>`));
                    tableLines = [];
                    return;
                }
                // Row 0 = headers, Row 1 = separator (---|---), Row 2+ = body rows
                // Strip leading/trailing pipes then split on inner pipes
                const parseRow = (row) => row.replace(/^\||\|$/g, '').split('|').map(c => c.trim());
                const headers = parseRow(tableLines[0]);
                const bodyRows = tableLines.slice(2);
                const thead = `<thead><tr>${headers.map(h => `<th class="md-th">${applyInline(h)}</th>`).join('')}</tr></thead>`;
                const tbody = bodyRows.length
                    ? `<tbody>${bodyRows.map(r => `<tr>${parseRow(r).map(c => `<td class="md-td">${applyInline(c)}</td>`).join('')}</tr>`).join('')}</tbody>`
                    : '';
                output.push(`<div class="md-table-wrap"><table class="md-table">${thead}${tbody}</table></div>`);
                tableLines = [];
            };

            const isTableRow = (s) => s.startsWith('|') && s.endsWith('|');
            // Matches Markdown table separator rows like |---|---| or |:---|:---:|
            const isSeparatorRow = (s) => /^\|[\s\-|:]+\|$/.test(s);

            for (const line of lines) {
                const trimmed = line.trim();
                if (!trimmed) {
                    flushList();
                    if (tableLines.length > 0) flushTable();
                    continue;
                }
                // Table detection
                if (isTableRow(trimmed)) {
                    flushList();
                    tableLines.push(trimmed);
                    continue;
                } else if (tableLines.length > 0) {
                    flushTable();
                }

                if (trimmed.startsWith('#### ')) {
                    flushList();
                    output.push(`<h4 class="text-base font-semibold mt-3 mb-1">${applyInline(trimmed.slice(5))}</h4>`);
                } else if (trimmed.startsWith('### ')) {
                    flushList();
                    output.push(`<h3 class="text-lg font-semibold mt-4 mb-2">${applyInline(trimmed.slice(4))}</h3>`);
                } else if (trimmed.startsWith('## ')) {
                    flushList();
                    output.push(`<h2 class="text-xl font-semibold mt-4 mb-2">${applyInline(trimmed.slice(3))}</h2>`);
                } else if (trimmed.startsWith('# ')) {
                    flushList();
                    output.push(`<h1 class="text-2xl font-bold mt-4 mb-2">${applyInline(trimmed.slice(2))}</h1>`);
                } else if (/^[-*]\s/.test(trimmed)) {
                    listItems.push(`<li class="ml-4">${applyInline(trimmed.slice(2))}</li>`);
                } else if (trimmed.startsWith('%%CODEBLOCK_')) {
                    flushList();
                    const idx = parseInt(trimmed.replace('%%CODEBLOCK_', '').replace('%%', ''), 10);
                    output.push(codeBlocks[idx]);
                } else if (trimmed.startsWith('%%MATHBLOCK_')) {
                    flushList();
                    const idx = parseInt(trimmed.replace('%%MATHBLOCK_', '').replace('%%', ''), 10);
                    output.push(mathBlocks[idx]);
                } else {
                    flushList();
                    // Restore any inline math blocks within paragraph text
                    let line = applyInline(trimmed);
                    line = line.replace(/%%MATHBLOCK_(\d+)%%/g, (_, i) => mathBlocks[parseInt(i, 10)]);
                    output.push(`<p class="my-1">${line}</p>`);
                }
            }
            flushList();
            if (tableLines.length > 0) flushTable();

            // Restore any remaining math placeholders that weren't on standalone lines
            let result = output.join('\n');
            result = result.replace(/%%MATHBLOCK_(\d+)%%/g, (_, i) => mathBlocks[parseInt(i, 10)]);
            return result;

            function applyInline(s) {
                return s
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/`([^`]+)`/g, '<code class="bg-gray-200 dark:bg-gray-600 px-1 py-0.5 rounded text-sm">$1</code>');
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full`;
            
            const notifColors = {
                success: 'background:linear-gradient(135deg,#10b981,#059669)',
                error:   'background:linear-gradient(135deg,#ef4444,#dc2626)',
                warning: 'background:linear-gradient(135deg,#f59e0b,#d97706)',
                info:    'background:linear-gradient(135deg,#3b82f6,#2563eb)'
            };
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            notification.setAttribute('style', notifColors[type] + ';color:#fff;border-radius:14px;padding:12px 18px;box-shadow:0 8px 24px rgba(0,0,0,.2)');
            notification.innerHTML = `
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <i class="${icons[type]}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey && !e.ctrlKey && !e.metaKey) {
                const activeInput = document.activeElement;
                if (activeInput && activeInput.tagName === 'TEXTAREA' && activeInput.id.endsWith('Input')) {
                    e.preventDefault();
                    const type = activeInput.id.replace('Input', '');
                    sendMessage(type);
                }
            }
            if (e.key === 'Escape') {
                // Close history panel first if open
                const panel = document.getElementById('historyPanel');
                if (panel && panel.classList.contains('open')) {
                    closeHistoryPanel();
                    return;
                }
                const interfaces = ['askAIInterface', 'dictionaryAIInterface', 'guideLearningInterface', 'errorAnalysisInterface'];
                const currentInterface = interfaces.find(id => !document.getElementById(id).classList.contains('hidden'));
                if (currentInterface) {
                    showAIList(currentSubject);
                }
            }
        });

        // Paste event handling for images
        document.addEventListener('paste', (e) => {
            const activeInterface = ['askAIInterface', 'guideLearningInterface', 'errorAnalysisInterface'].find(id => 
                !document.getElementById(id).classList.contains('hidden')
            );
            
            if (activeInterface) {
                const type = activeInterface.replace('Interface', '');
                if (e.clipboardData.files.length > 0) {
                     const file = e.clipboardData.files[0];
                     if(file.type.startsWith("image/")){
                        e.preventDefault();
                        const input = document.getElementById(`${type}PhotoInput`);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        previewImage(type);
                     }
                }
            }
        });

        // Make functions globally available
        window.showCover = showCover;
        window.showAIList = showAIList;
        window.showAI = showAI;
        window.sendMessage = sendMessage;
        window.previewImage = previewImage;
        window.clearImagePreview = clearImagePreview;
        window.pasteImage = pasteImage;
        window.copyMessage = copyMessage;
        window.shareMessage = shareMessage;
        window.toggleTheme = toggleTheme;
        window.handleToolClick = handleToolClick;
        window.addUserMessage = addUserMessage;
        window.addAIMessage = addAIMessage;
        window.histories = histories;
        window.requestHint = requestHint;

        // Handle ?openSubject= URL param (used by external tool pages to return to the correct AI list)
        (function() {
            const params = new URLSearchParams(window.location.search);
            const openSubject = params.get('openSubject');
            if (openSubject) {
                // Remove the query param from the URL without reload
                history.replaceState(null, '', window.location.pathname);
                const cover = document.getElementById('coverScreen');
                if (!cover) return;
                // If coverScreen is already visible, navigate immediately
                if (!cover.classList.contains('hidden')) {
                    showAIList(openSubject);
                    return;
                }
                // Otherwise wait for auth guard to show coverScreen
                const observer = new MutationObserver(() => {
                    if (!cover.classList.contains('hidden')) {
                        observer.disconnect();
                        showAIList(openSubject);
                    }
                });
                observer.observe(cover, { attributes: true, attributeFilter: ['class'] });
            }
        })();
    </script>

    <!-- ── Inline History Panel ─────────────────────────────────────────── -->
    <div id="historyPanelBackdrop" onclick="closeHistoryPanel()" aria-hidden="true"></div>
    <div id="historyPanel" role="dialog" aria-modal="true" aria-label="History panel">
        <div id="historyPanelHeader">
            <div class="flex items-center gap-2">
                <i class="fas fa-history" style="color:var(--primary);font-size:1.1rem"></i>
                <span class="font-bold text-base" style="color:var(--text-primary)">Chat History</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select id="hpFilterTool" onchange="applyHPFilter()"
                        class="btn-secondary text-xs px-2 py-1.5 rounded-lg outline-none cursor-pointer"
                        style="background:var(--glass-bg);color:var(--text-primary);border:1px solid var(--glass-border)">
                    <option value="">All Tools</option>
                    <option value="Ask AI">Ask AI</option>
                    <option value="Dictionary AI">Dictionary AI</option>
                    <option value="Guide Learning">Guide Learning</option>
                    <option value="Error Analysis">Error Analysis</option>
                    <option value="Code Review">Code Review</option>
                    <option value="Exam Paper Generator">Exam Paper Generator</option>
                    <option value="English Writing">English Writing</option>
                    <option value="Vocabulary Generator">Vocabulary Generator</option>
                </select>
                <input type="text" id="hpSearch" placeholder="🔍 Search history…"
                       oninput="applyHPFilter()"
                       style="background:var(--glass-bg);color:var(--text-primary);border:1px solid var(--glass-border);border-radius:8px;padding:.35rem .7rem;font-size:.78rem;outline:none;min-width:0;flex:1;max-width:160px;">
                <button onclick="exportHistoryText()" title="Export history as text" aria-label="Export history"
                        class="w-9 h-9 flex items-center justify-center rounded-xl btn-secondary">
                    <i class="fas fa-download" style="color:var(--text-secondary)"></i>
                </button>
                <button onclick="closeHistoryPanel()"
                        class="w-9 h-9 flex items-center justify-center rounded-xl btn-secondary"
                        aria-label="Close history panel">
                    <i class="fas fa-times" style="color:var(--text-secondary)"></i>
                </button>
            </div>
        </div>
        <div id="historyPanelBody">
            <div id="hpList"></div>
            <div id="historyPanelLoadMore">
                <button id="hpLoadMoreBtn" onclick="loadMoreHP()"
                        class="btn-secondary px-5 py-2 text-sm font-semibold rounded-xl">
                    <i class="fas fa-chevron-down mr-2"></i>Load More
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Inline History Panel ──────────────────────────────────────────
        let _hp = { all:[], filtered:[], lastDoc:null, loading:false, filterTool:'' };
        const _HP_CHAT_TOOL_MAP = { 'Ask AI': 'askAI', 'Dictionary AI': 'dictionaryAI', 'Guide Learning': 'guideLearning', 'Error Analysis': 'errorAnalysis' };
        // Tools that redirect to their own page when clicked in history
        const _HP_REDIRECT_MAP = { 'Exam Paper Generator': 'exam.php', 'Vocabulary Generator': 'vocab.php', 'Code Review': 'coding.php', 'Code Completion': 'code-completion.php' };

        function _hpEscape(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                                .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
        }
        function _hpFmtDate(ts) {
            if (!ts) return '';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleDateString('en-HK',{year:'numeric',month:'short',day:'numeric'});
        }
        function _hpFmtTime(ts) {
            if (!ts) return '';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleTimeString('en-HK',{hour:'2-digit',minute:'2-digit'});
        }
        function _hpToolColor(tool) {
            const m = {'Ask AI':'#7c3aed','Dictionary AI':'#10b981','Guide Learning':'#8b5cf6',
                       'Error Analysis':'#ef4444',
                       'Code Review':'#06b6d4','Exam Paper Generator':'#f59e0b',
                       'English Writing':'#14b8a6','Vocabulary Generator':'#eab308',
                       '文言文翻譯':'#ef4444','文樞翻譯':'#ef4444','Vocabulary':'#eab308'};
            return m[tool]||'#7c3aed';
        }
        function _hpToolIcon(tool) {
            const m = {'Ask AI':'robot','Dictionary AI':'book','Guide Learning':'compass',
                       'Error Analysis':'search-plus',
                       'Code Review':'code',
                       'Exam Paper Generator':'file-alt',
                       'English Writing':'pen-fancy','Vocabulary Generator':'lightbulb',
                       '文言文翻譯':'scroll','文樞翻譯':'scroll','Vocabulary':'lightbulb'};
            return m[tool]||'robot';
        }

        function _hpRenderSessions(sessions) {
            const list = document.getElementById('hpList');
            if (!sessions.length) {
                list.innerHTML = `
                    <div style="text-align:center;padding:3rem 1rem">
                        <div style="width:52px;height:52px;border-radius:14px;background:rgba(124,58,237,.10);
                             display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                            <i class="fas fa-history" style="color:var(--primary);font-size:1.4rem"></i>
                        </div>
                        <p class="font-bold text-sm" style="color:var(--text-primary);margin-bottom:.4rem">No history yet</p>
                        <p class="text-xs" style="color:var(--text-secondary)">Your AI interactions will appear here.</p>
                    </div>`;
                return;
            }
            // Group by date
            const groups = {};
            sessions.forEach(s => {
                const d = _hpFmtDate(s.updatedAt);
                if (!groups[d]) groups[d] = [];
                groups[d].push(s);
            });
            list.innerHTML = Object.keys(groups).map(date => `
                <div class="hp-date-divider">${_hpEscape(date)}</div>
                ${groups[date].map(s => _hpRenderCard(s)).join('')}
            `).join('');
        }

        function _hpNonChatBody(s) {
            const msgs = s.messages || [];
            if (!msgs.length) return '<p style="text-align:center;font-size:.78rem;color:var(--text-secondary);padding:.5rem 0">No content</p>';
            const userMsg = msgs.find(m => m.role === 'user');
            const aiMsg   = msgs.find(m => m.role === 'assistant');
            if (s.tool === 'Code Review') {
                const uc = userMsg ? userMsg.content || '' : '';
                const qMatch = uc.match(/^Question:\s*([\s\S]*?)(?:\n\nCode:|$)/);
                const cMatch = uc.match(/\n\nCode:\n([\s\S]*)/);
                const question = qMatch ? qMatch[1].trim() : uc.substring(0, 200);
                const code = cMatch ? cMatch[1].trim() : '';
                const feedback = aiMsg ? (aiMsg.content || '').substring(0, 600) : '';
                return `<div style="padding:.5rem 0">
                    ${question ? `<div style="margin-bottom:.6rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Question</span><p style="font-size:.78rem;color:var(--text-primary);margin-top:.15rem">${_hpEscape(question.substring(0,200))}</p></div>` : ''}
                    ${code ? `<div style="margin-bottom:.6rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Code Submitted</span><pre style="font-size:.7rem;background:#1a1a2e;color:#e2e8f0;padding:.5rem .75rem;border-radius:8px;overflow-x:auto;margin-top:.2rem;max-height:110px;overflow-y:auto;font-family:monospace">${_hpEscape(code.substring(0,500))}</pre></div>` : ''}
                    ${feedback ? `<div><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">AI Feedback Preview</span><p style="font-size:.77rem;color:var(--text-primary);margin-top:.15rem;white-space:pre-wrap;max-height:120px;overflow-y:auto">${_hpEscape(feedback)}</p></div>` : ''}
                </div>`;
            }
            if (s.tool === 'Exam Paper Generator') {
                const uc = userMsg ? userMsg.content || '' : '';
                const examContent = aiMsg ? (aiMsg.content || '').substring(0, 600) : '';
                return `<div style="padding:.5rem 0">
                    ${uc ? `<div style="margin-bottom:.6rem;padding:.5rem .75rem;background:rgba(245,158,11,.08);border-left:3px solid #f59e0b;border-radius:0 8px 8px 0"><p style="font-size:.77rem;color:var(--text-primary);white-space:pre-wrap">${_hpEscape(uc.substring(0,200))}</p></div>` : ''}
                    ${examContent ? `<div><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Generated Paper Preview</span><p style="font-size:.77rem;color:var(--text-primary);margin-top:.15rem;white-space:pre-wrap;max-height:140px;overflow-y:auto">${_hpEscape(examContent)}</p></div>` : ''}
                </div>`;
            }
            // Default for English Writing, Vocabulary, etc.
            const uc = userMsg ? (userMsg.content || '').substring(0, 300) : '';
            const ac = aiMsg   ? (aiMsg.content   || '').substring(0, 400) : '';
            return `<div style="padding:.5rem 0">
                ${uc ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Input</span><p style="font-size:.77rem;color:var(--text-primary);margin-top:.15rem;white-space:pre-wrap">${_hpEscape(uc)}</p></div>` : ''}
                ${ac ? `<div><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Result Preview</span><p style="font-size:.77rem;color:var(--text-primary);margin-top:.15rem;white-space:pre-wrap;max-height:120px;overflow-y:auto">${_hpEscape(ac)}</p></div>` : ''}
            </div>`;
        }

        function _hpRenderCard(s) {
            const color = _hpToolColor(s.tool);
            const rawIcon = _hpToolIcon(s.tool);
            const time  = _hpFmtTime(s.updatedAt);
            const isChatSession = Object.keys(_HP_CHAT_TOOL_MAP).includes(s.tool);
            const starred = (JSON.parse(localStorage.getItem('hp_starred') || '{}')).hasOwnProperty(s.id);

            // Build icon HTML – support EduGenius logo image for Code Review
            const isImgIcon = rawIcon.startsWith('__img__');
            const iconHtml = isImgIcon
                ? `<img src="${rawIcon.slice(7)}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit" alt="${_hpEscape(s.tool)}">`
                : `<i class="fas fa-${rawIcon}" aria-hidden="true"></i>`;

            const starBtn = `
                <button onclick="event.stopPropagation();toggleHPStar('${s.id}', this)"
                        style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:8px;font-size:.8rem;background:${starred ? 'rgba(245,158,11,.15)' : 'rgba(0,0,0,.05)'};color:${starred ? '#f59e0b' : 'var(--text-secondary)'};border:none;cursor:pointer;"
                        title="${starred ? 'Unstar' : 'Star'}" aria-label="${starred ? 'Unstar session' : 'Star session'}">
                    <i class="fas fa-star"></i>
                </button>`;
            const deleteBtn = `
                <button onclick="event.stopPropagation();deleteHPSession('${s.id}')"
                        class="btn-danger" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:8px;font-size:.7rem"
                        title="Delete" aria-label="Delete session">
                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
                </button>`;

            if (isChatSession) {
                // Chat sessions: clicking the card directly continues the chat, no preview
                return `
                <div class="hp-session-card" id="hp-card-${s.id}">
                    <div class="hp-session-header" onclick="_hpContinueChat('${s.id}')"
                         role="button" tabindex="0"
                         aria-label="Continue ${_hpEscape(s.tool||'')} session"
                         style="cursor:pointer"
                         onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();_hpContinueChat('${s.id}')}">
                        <div class="hp-session-icon" style="background:linear-gradient(135deg,${color},${color}99)">
                            ${iconHtml}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                                <span style="font-weight:700;font-size:.8rem;color:var(--text-primary)">${_hpEscape(s.tool||'')}</span>
                                ${s.subject?`<span style="font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:6px;background:rgba(124,58,237,.12);color:var(--text-primary)">${_hpEscape(s.subject)}</span>`:''}
                            </div>
                            <p style="font-size:.72rem;color:var(--text-secondary);margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_hpEscape((s.summary||'').substring(0,70))}</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                            <span style="font-size:.7rem;color:var(--text-secondary)">${_hpEscape(time)}</span>
                            ${starBtn}
                            ${deleteBtn}
                            <i class="fas fa-play-circle" title="Continue session" style="font-size:1.2rem;color:var(--primary)"></i>
                        </div>
                    </div>
                </div>`;
            } else {
                // Check if this tool redirects to a dedicated page
                const redirectPage = _HP_REDIRECT_MAP[s.tool];
                if (redirectPage) {
                    // Redirect tools: clicking opens the page with session content restored
                    return `
                <div class="hp-session-card" id="hp-card-${s.id}">
                    <div class="hp-session-header" onclick="closeHistoryPanel();window.location.href='${redirectPage}?session=${s.id}'"
                         role="button" tabindex="0"
                         aria-label="Open ${_hpEscape(s.tool||'')} session"
                         style="cursor:pointer"
                         onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();closeHistoryPanel();window.location.href='${redirectPage}?session=${s.id}'}">
                        <div class="hp-session-icon" style="background:linear-gradient(135deg,${color},${color}99)">
                            ${iconHtml}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                                <span style="font-weight:700;font-size:.8rem;color:var(--text-primary)">${_hpEscape(s.tool||'')}</span>
                                ${s.subject?`<span style="font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:6px;background:rgba(124,58,237,.12);color:var(--text-primary)">${_hpEscape(s.subject)}</span>`:''}
                            </div>
                            <p style="font-size:.72rem;color:var(--text-secondary);margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_hpEscape((s.summary||'').substring(0,70))}</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                            <span style="font-size:.7rem;color:var(--text-secondary)">${_hpEscape(time)}</span>
                            ${starBtn}
                            ${deleteBtn}
                            <i class="fas fa-external-link-alt" title="Open in page" style="font-size:.75rem;color:var(--primary)"></i>
                        </div>
                    </div>
                </div>`;
                }
                // Non-chat, non-redirect sessions: expand/collapse with dedicated tool-specific content
                return `
                <div class="hp-session-card" id="hp-card-${s.id}">
                    <div class="hp-session-header" onclick="toggleHPCard('${s.id}')"
                         role="button" tabindex="0" aria-expanded="false" id="hp-header-${s.id}"
                         onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();toggleHPCard('${s.id}')}">
                        <div class="hp-session-icon" style="background:linear-gradient(135deg,${color},${color}99)">
                            ${iconHtml}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                                <span style="font-weight:700;font-size:.8rem;color:var(--text-primary)">${_hpEscape(s.tool||'')}</span>
                                ${s.subject?`<span style="font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:6px;background:rgba(124,58,237,.12);color:var(--text-primary)">${_hpEscape(s.subject)}</span>`:''}
                            </div>
                            <p style="font-size:.72rem;color:var(--text-secondary);margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_hpEscape((s.summary||'').substring(0,70))}</p>
                        </div>
                        <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                            <span style="font-size:.7rem;color:var(--text-secondary)">${_hpEscape(time)}</span>
                            ${starBtn}
                            ${deleteBtn}
                            <i class="fas fa-chevron-down" id="hp-chevron-${s.id}" aria-hidden="true" style="font-size:.65rem;color:var(--text-secondary);transition:transform .3s"></i>
                        </div>
                    </div>
                    <div class="hp-session-body" id="hp-body-${s.id}">
                        ${_hpNonChatBody(s)}
                    </div>
                </div>`;
            }
        }

        function toggleHPCard(id) {
            const body    = document.getElementById('hp-body-'+id);
            const chevron = document.getElementById('hp-chevron-'+id);
            if (!body) return;
            const isOpen = body.classList.toggle('open');
            if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : '';
            const header = document.getElementById('hp-header-'+id);
            if (header) header.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        async function deleteHPSession(id) {
            if (!confirm('Delete this history entry?')) return;
            const user = window.auth && window.auth.currentUser;
            if (!user) return;
            const ok = window.deleteHistorySession
                ? await window.deleteHistorySession(user.uid, id)
                : false;
            if (ok) {
                _hp.all      = _hp.all.filter(s=>s.id!==id);
                _hp.filtered = _hp.filtered.filter(s=>s.id!==id);
                const card = document.getElementById('hp-card-'+id);
                if (card) card.remove();
                if (!_hp.filtered.length) _hpRenderSessions([]);
            } else {
                alert('Failed to delete. Please try again.');
            }
        }

        function _hpContinueChat(sessionId) {
            const session = _hp.all.find(s => s.id === sessionId);
            if (!session) return;
            const toolMap = _HP_CHAT_TOOL_MAP;
            const type = toolMap[session.tool];
            if (!type) return;
            const subject = session.subject;
            if (!subject || !window.showAI) return;
            closeHistoryPanel();
            // Navigate to the chatbox (this resets history and chatbox)
            window.showAI(subject, type);
            // Re-populate chatbox with saved messages
            const chatBox = document.getElementById(type + 'ChatBox');
            if (!chatBox) return;
            chatBox.innerHTML = '';
            const msgs = session.messages || [];
            msgs.forEach(m => {
                if (m.role === 'user') {
                    if (window.addUserMessage) window.addUserMessage(type, m.content || '');
                    if (window.histories && window.histories[type]) window.histories[type].push({ role: 'user', content: m.content || '' });
                } else if (m.role === 'assistant') {
                    if (window.addAIMessage) window.addAIMessage(type, m.content || '');
                    if (window.histories && window.histories[type]) window.histories[type].push({ role: 'assistant', content: m.content || '' });
                }
            });
            // Continue saving new messages to the same Firestore document
            if (window.continueHistorySession) window.continueHistorySession(type, sessionId);
        }

        function applyHPFilter() {
            _hp.filterTool = document.getElementById('hpFilterTool').value;
            const searchEl = document.getElementById('hpSearch');
            const keyword = (searchEl ? searchEl.value.trim().toLowerCase() : '');
            _hp.filtered = _hp.all.filter(s => {
                const toolMatch = !_hp.filterTool || s.tool === _hp.filterTool;
                const keywordMatch = !keyword ||
                    (s.summary || '').toLowerCase().includes(keyword) ||
                    (s.tool || '').toLowerCase().includes(keyword) ||
                    (s.subject || '').toLowerCase().includes(keyword);
                return toolMatch && keywordMatch;
            });
            _hpRenderSessions(_hp.filtered);
            document.getElementById('historyPanelLoadMore').style.display =
                (_hp.lastDoc && !_hp.filterTool && !keyword) ? 'block' : 'none';
        }

        async function loadMoreHP() {
            if (_hp.loading || !_hp.lastDoc) return;
            _hp.loading = true;
            const btn = document.getElementById('hpLoadMoreBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading…';
            try {
                const user = window.auth && window.auth.currentUser;
                if (!user) return;
                const result = await window.loadHistory(user.uid, _hp.lastDoc);
                const newSessions = result.docs;
                _hp.lastDoc = result.lastDoc;
                _hp.all = [..._hp.all, ...newSessions];
                applyHPFilter();
                document.getElementById('historyPanelLoadMore').style.display =
                    (_hp.lastDoc && !_hp.filterTool) ? 'block' : 'none';
            } catch(e) { console.error(e); }
            finally {
                _hp.loading = false;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Load More';
            }
        }

        async function openHistoryPanel() {
            const backdrop = document.getElementById('historyPanelBackdrop');
            const panel    = document.getElementById('historyPanel');
            backdrop.style.display = 'block';
            requestAnimationFrame(() => {
                backdrop.classList.add('open');
                panel.classList.add('open');
            });
            // Reset state and load fresh
            _hp = { all:[], filtered:[], lastDoc:null, loading:false, filterTool:'' };
            document.getElementById('hpFilterTool').value = '';
            const _searchEl = document.getElementById('hpSearch');
            if (_searchEl) _searchEl.value = '';
            document.getElementById('hpList').innerHTML =
                `<div style="text-align:center;padding:2rem"><i class="fas fa-spinner fa-spin" style="color:var(--primary);font-size:1.5rem"></i></div>`;
            document.getElementById('historyPanelLoadMore').style.display = 'none';
            try {
                const user = window.auth && window.auth.currentUser;
                if (!user) {
                    document.getElementById('hpList').innerHTML =
                        '<p style="text-align:center;color:var(--text-secondary);font-size:.85rem;padding:2rem">Please sign in to view history.</p>';
                    return;
                }
                const result = await window.loadHistory(user.uid, null);
                _hp.all     = result.docs;
                _hp.lastDoc = result.lastDoc;
                applyHPFilter();
            } catch(e) {
                console.error(e);
                document.getElementById('hpList').innerHTML =
                    '<p style="text-align:center;color:#ef4444;font-size:.85rem;padding:2rem">Failed to load history. Please try again.</p>';
            }
        }

        function closeHistoryPanel() {
            const backdrop = document.getElementById('historyPanelBackdrop');
            const panel    = document.getElementById('historyPanel');
            backdrop.classList.remove('open');
            panel.classList.remove('open');
            setTimeout(() => { backdrop.style.display = 'none'; }, 350);
        }

        function toggleHPStar(id, btn) {
            const stored = JSON.parse(localStorage.getItem('hp_starred') || '{}');
            const isStarred = stored.hasOwnProperty(id);
            if (isStarred) { delete stored[id]; } else { stored[id] = true; }
            localStorage.setItem('hp_starred', JSON.stringify(stored));
            btn.style.background = isStarred ? 'rgba(0,0,0,.05)' : 'rgba(245,158,11,.15)';
            btn.style.color = isStarred ? 'var(--text-secondary)' : '#f59e0b';
            btn.title = isStarred ? 'Star' : 'Unstar';
        }

        function exportHistoryText() {
            const sessions = _hp.filtered.length ? _hp.filtered : _hp.all;
            if (!sessions.length) { alert('No history to export.'); return; }
            let text = 'EduGenius AI Chat History Export\n';
            text += `Exported: ${new Date().toLocaleString('en-HK')}\n`;
            text += '='.repeat(50) + '\n\n';
            sessions.forEach(s => {
                text += `[${s.tool || 'Unknown Tool'} \u2013 ${s.subject || ''}]\n`;
                text += `Date: ${_hpFmtDate(s.updatedAt)} ${_hpFmtTime(s.updatedAt)}\n`;
                text += `Summary: ${s.summary || ''}\n`;
                text += '-'.repeat(40) + '\n';
                (s.messages || []).forEach(m => {
                    const role = m.role === 'user' ? 'You' : 'AI';
                    text += `${role}: ${(m.content || '').substring(0, 800)}\n\n`;
                });
                text += '\n';
            });
            const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `edugenius-history-${new Date().toISOString().slice(0,10)}.txt`;
            a.click();
            URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>