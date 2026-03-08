<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGenius – English Writing Practice</title>
    <link rel="manifest" href="./manifest.json">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        /* ============================================================
           EduGenius English Writing Practice
           Self-contained styles – no external dependencies
           ============================================================ */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --secondary-light: #f5f3ff;
            --bg-start: #eef2ff;
            --bg-end: #fdf4ff;
            --card-bg: #ffffff;
            --text: #1f2937;
            --text-muted: #6b7280;
            --text-secondary: #374151;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --radius: 14px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-start) 0%, #f5f3ff 50%, var(--bg-end) 100%);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ---- Header ---- */
        .site-header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
            color: var(--text);
            flex-shrink: 0;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(79,70,229,0.3);
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            background: var(--primary-light);
            padding: 0.2rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            flex-shrink: 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            flex-shrink: 0;
        }

        .back-btn:hover {
            background: #e0e7ff;
            transform: translateX(-2px);
        }

        .back-arrow {
            font-size: 1rem;
            line-height: 1;
        }

        /* ---- Main layout ---- */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3rem;
        }

        /* ---- Hero ---- */
        .hero {
            text-align: center;
            padding: 2.5rem 1rem 2rem;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 1rem;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .hero h1 {
            font-size: clamp(1.75rem, 5vw, 2.75rem);
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 580px;
            margin: 0 auto;
        }

        /* ---- Grid layout ---- */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ---- Card ---- */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.75rem;
            transition: box-shadow 0.25s;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1.5px solid var(--border);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .icon-indigo { background: var(--primary-light); }
        .icon-purple { background: var(--secondary-light); }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text);
        }

        .card-hint {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* ---- Form elements ---- */
        .form-group {
            margin-bottom: 1.125rem;
        }

        label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.375rem;
            letter-spacing: 0.01em;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.9375rem;
            color: var(--text);
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            -webkit-appearance: none;
            appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        textarea {
            resize: vertical;
            min-height: 90px;
            line-height: 1.5;
        }

        .input-mt {
            margin-top: 0.5rem;
        }

        /* ---- Buttons ---- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.675rem 1.25rem;
            border: none;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--primary-hover), #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-secondary {
            background: var(--primary-light);
            color: var(--primary);
            border: 1.5px solid #c7d2fe;
        }

        .btn-secondary:hover:not(:disabled) {
            background: #e0e7ff;
            border-color: #a5b4fc;
        }

        .btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-full {
            width: 100%;
        }

        /* ---- Output box ---- */
        .output-box {
            background: #f9fafb;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 1.125rem 1.25rem;
            margin-top: 1rem;
            min-height: 80px;
            font-size: 0.9375rem;
            line-height: 1.7;
            color: var(--text);
        }

        .output-empty {
            color: var(--text-muted);
            font-style: italic;
            font-size: 0.875rem;
        }

        /* ---- Action row ---- */
        .action-row {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        /* ---- Status bar ---- */
        .status-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #166534;
        }

        .status-bar.hidden { display: none; }

        /* ---- Loading ---- */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(79, 70, 229, 0.2);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            padding: 0.75rem 0;
        }

        /* ---- Footer ---- */
        footer {
            text-align: center;
            padding: 2rem 1.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            border-top: 1px solid var(--border);
            margin-top: 2rem;
        }

        footer strong {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ---- Utility classes (used by dynamic content from AI responses) ---- */
        .hidden { display: none !important; }
        .prose { }
        .max-w-none { max-width: none; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .text-gray-700 { color: #374151; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-600 { color: #4b5563; }
        .text-red-600 { color: #dc2626; }
        .font-semibold { font-weight: 600; }
        .list-decimal { list-style-type: decimal; }
        .pl-6 { padding-left: 1.5rem; }
        .pl-5 { padding-left: 1.25rem; }
        .leading-relaxed { line-height: 1.625; }

        /* Output prose formatting */
        .output-box p { margin-bottom: 0.5rem; }
        .output-box p:last-child { margin-bottom: 0; }
        .output-box ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .output-box li { margin-bottom: 0.25rem; }
        .output-box strong { font-weight: 600; }
        .output-box em { font-style: italic; }

        /* ---- Responsive ---- */
        @media (max-width: 640px) {
            .header-inner {
                padding: 0.75rem 1rem;
            }

            .header-subtitle {
                display: none;
            }

            .container {
                padding: 1.5rem 1rem 2rem;
            }

            .hero {
                padding: 1.75rem 0.5rem 1.5rem;
            }

            .card {
                padding: 1.25rem;
            }

            .action-row {
                flex-direction: column;
            }

            .action-row .btn {
                width: 100%;
            }

            .brand-name {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 641px) and (max-width: 1023px) {
            .container {
                padding: 1.75rem 1.25rem 2.5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* ---- Scoring panel ---- */
        .score-panel { background: #f8faff; border: 1.5px solid #c7d2fe; border-radius: 12px; padding: 1.25rem; margin-top: 1rem; }
        .score-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
        .score-item { text-align: center; padding: 0.75rem; background: white; border-radius: 10px; border: 1px solid #e0e7ff; }
        .score-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.35rem; }
        .score-bar-wrap { height: 8px; background: #e5e7eb; border-radius: 9999px; margin: 0.35rem 0; overflow: hidden; }
        .score-bar-fill { height: 100%; border-radius: 9999px; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 0.6s ease; }
        .score-num { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
        .score-max { font-size: 0.75rem; color: var(--text-muted); }
        .score-feedback { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.35rem; line-height: 1.4; }
        .upgrade-list { list-style: none; padding: 0; margin-top: 0.5rem; }
        .upgrade-item { display: flex; gap: 0.5rem; align-items: flex-start; padding: 0.4rem 0.5rem; border-radius: 6px; font-size: 0.82rem; margin-bottom: 0.3rem; background: #fff; border: 1px solid #e5e7eb; }
        .upgrade-old { color: #b91c1c; text-decoration: line-through; font-weight: 600; }
        .upgrade-arrow { color: var(--text-muted); }
        .upgrade-new { color: #065f46; font-weight: 700; }
        .upgrade-reason { color: var(--text-muted); font-size: 0.75rem; }
        /* ---- Templates panel ---- */
        .templates-section { margin-bottom: 1.5rem; }
        .templates-toggle { display: flex; align-items: center; gap: 0.5rem; width: 100%; background: white; border: 1.5px solid #c7d2fe; border-radius: 10px; padding: 0.75rem 1rem; font-family: inherit; font-size: 0.9rem; font-weight: 700; color: var(--primary); cursor: pointer; transition: all 0.2s; }
        .templates-toggle:hover { background: var(--primary-light); }
        .templates-body { display: none; margin-top: 0.5rem; }
        .templates-body.open { display: grid; grid-template-columns: 1fr; gap: 0.75rem; }
        @media (min-width: 640px) { .templates-body.open { grid-template-columns: 1fr 1fr; } }
        .template-card { background: white; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 1rem; cursor: pointer; transition: all 0.2s; }
        .template-card:hover { border-color: var(--primary); box-shadow: 0 2px 8px rgba(79,70,229,.1); }
        .template-name { font-weight: 700; font-size: 0.9rem; color: var(--text); margin-bottom: 0.5rem; }
        .template-structure { font-size: 0.78rem; color: var(--text-muted); line-height: 1.6; white-space: pre-line; }
        .template-starter { font-size: 0.78rem; color: var(--primary); font-style: italic; margin-top: 0.4rem; border-top: 1px solid #e5e7eb; padding-top: 0.4rem; }
        .use-template-btn { font-size: 0.75rem; padding: 0.3rem 0.75rem; border-radius: 6px; border: none; background: var(--primary-light); color: var(--primary); font-family: inherit; font-weight: 600; cursor: pointer; margin-top: 0.5rem; }
        .use-template-btn:hover { background: #e0e7ff; }
/* ---- Dark mode ---- */
.dark body { background: linear-gradient(135deg, #0d0d1a 0%, #1a0a2e 50%, #0d1a2e 100%); color: #e2e8f0; }
.dark .card { background: rgba(15,15,40,0.85); border: 1px solid rgba(255,255,255,0.08); color: #e2e8f0; }
.dark .site-header { background: rgba(15,15,40,0.95); }
.dark .template-card { background: rgba(15,15,40,0.85); border-color: rgba(255,255,255,.1); color: #e2e8f0; }
.dark .template-name { color: #e2e8f0; }
.dark input[type="text"], .dark select, .dark textarea { background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.15); color: #e2e8f0; }
.dark footer { color: #94a3b8; border-top-color: rgba(255,255,255,.08); }
.dark .back-btn { background: rgba(124,58,237,.2); color: #a78bfa; }
.dark .hero h1 { color: #e2e8f0; }
.dark .card-title { color: #e2e8f0; }
.dark .brand { color: #e2e8f0; }
.dark label { color: #e2e8f0; }
.dark .templates-toggle { background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.1); color: #e2e8f0; }
.dark .templates-body { background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.08); }
.dark .status-bar { background: rgba(15,15,40,.8); color: #e2e8f0; }
/* Micro-interactions */
button:not(:disabled):active { transform: scale(0.97); }
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}
/* Focus ring */
button:focus-visible, a:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
    outline: 3px solid rgba(124,58,237,.5);
    outline-offset: 2px;
}
/* Writing History Panel */
#writingHistoryBackdrop {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:900;
    backdrop-filter:blur(2px); transition:opacity .3s;
}
#writingHistoryBackdrop.open { opacity:1; }
#writingHistoryPanel {
    position:fixed; top:0; right:0; bottom:0; z-index:901;
    width:min(500px,100vw); background:var(--card-bg);
    border-left:1px solid var(--border);
    box-shadow:-8px 0 32px rgba(0,0,0,.18);
    display:flex; flex-direction:column;
    transform:translateX(100%);
    transition:transform .35s cubic-bezier(.16,1,.3,1);
    overflow:hidden;
}
#writingHistoryPanel.open { transform:translateX(0); }
#writingHistoryPanelHeader {
    display:flex; align-items:center; justify-content:space-between;
    padding:1rem 1.25rem; background:rgba(255,255,255,.95);
    border-bottom:1px solid var(--border); flex-shrink:0;
}
#writingHistoryPanelBody { flex:1; overflow-y:auto; padding:1rem; }
#writingHistoryPanelBody::-webkit-scrollbar { width:4px; }
#writingHistoryPanelBody::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
.ewh-card {
    background:var(--card-bg); border:1px solid var(--border);
    border-radius:12px; margin-bottom:.75rem; overflow:hidden;
}
.ewh-card-header {
    display:flex; align-items:center; gap:.75rem;
    padding:.85rem 1rem; cursor:pointer; transition:background .2s;
}
.ewh-card-header:hover { background:rgba(79,70,229,.06); }
.ewh-card-body {
    max-height:0; overflow:hidden;
    transition:max-height .35s ease, padding .2s; padding:0 1rem;
}
.ewh-card-body.open { max-height:600px; overflow-y:auto; padding:.75rem 1rem; }
.ewh-card-body::-webkit-scrollbar { width:3px; }
.ewh-card-body::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
.dark #writingHistoryPanel { background:rgba(15,15,40,.95); border-left-color:rgba(255,255,255,.08); }
.dark #writingHistoryPanelHeader { background:rgba(15,15,40,.85); border-bottom-color:rgba(255,255,255,.08); }
.dark .ewh-card { background:rgba(30,20,60,.8); border-color:rgba(255,255,255,.08); }
    </style>
<?= firebaseConfigScript() ?>
<body>
    <!-- Auth guard overlay -->
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

    <!-- ===== Sticky Header ===== -->
    <header class="site-header">
        <div class="header-inner">
            <a href="./index.php" class="brand" aria-label="EduGenius Home">
                <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius AI Logo" class="brand-icon" style="object-fit:cover">
                <span class="brand-name">EduGenius</span>
            </a>
            <span class="header-subtitle">✍️ English Writing Practice</span>
            <div style="display:flex;align-items:center;gap:.5rem">
                <button onclick="openWritingHistory()" title="View Writing History" aria-label="View writing history"
                        style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(79,70,229,.1);color:var(--primary);font-size:.95rem;transition:all .2s;flex-shrink:0">
                    <i class="fas fa-history"></i>
                </button>
                <button id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode"
                        style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(79,70,229,.1);color:var(--primary);font-size:1rem;transition:all .2s;flex-shrink:0">
                    <span class="theme-moon">🌙</span><span class="theme-sun" style="display:none">☀️</span>
                </button>
                <a id="back-btn-link" href="./index.php" class="back-btn" aria-label="Back">
                    <span class="back-arrow">←</span> Back
                </a>
            </div>
        </div>
    </header>

    <!-- ===== Main Content ===== -->
    <main class="container">

        <!-- Hero section -->
        <div class="hero">
            <div class="hero-badge">✍️ DSE Writing Practice</div>
            <h1>English Writing<br><span>Task Generator</span></h1>
            <p>Create custom DSE writing tasks and generate Level&nbsp;7 sample essays with EduGenius AI assistance</p>
        </div>

        <!-- Status bar (shown after task is ready) -->
        <div id="statusBar" class="status-bar hidden" role="status" aria-live="polite">
            ✅ Task ready — scroll down to generate your sample essay
        </div>

        <!-- Writing Templates -->
        <div class="templates-section">
            <button class="templates-toggle" onclick="toggleTemplates()" aria-expanded="false" aria-controls="templates-body">
                📋 Writing Templates
                <span id="templates-arrow" style="margin-left:auto;font-size:.75rem;transition:transform .3s">▼</span>
            </button>
            <div class="templates-body" id="templates-body" role="region"></div>
        </div>

        <!-- Two-column grid (stacks on mobile/tablet) -->
        <div class="content-grid">

            <!-- ── Card 1: Task Generator ── -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon icon-indigo">📝</div>
                    <div>
                        <div class="card-title">Generate Writing Task</div>
                        <div class="card-hint">Customise the settings and hit Generate</div>
                    </div>
                </div>

                <form id="taskForm" novalidate>
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select id="genre" onchange="toggleCustomGenre()" aria-label="Select writing genre">
                            <option value="argumentative essay">Argumentative Essay</option>
                            <option value="narrative">Narrative</option>
                            <option value="descriptive essay">Descriptive Essay</option>
                            <option value="letter">Letter</option>
                            <option value="article">Article</option>
                            <option value="speech">Speech</option>
                            <option value="report">Report</option>
                            <option value="other">Other (specify below)</option>
                        </select>
                        <input type="text" id="customGenre" class="hidden input-mt"
                               placeholder="Enter custom genre" aria-label="Custom genre">
                    </div>

                    <div class="form-group">
                        <label for="context">Context</label>
                        <textarea id="context" rows="3"
                                  placeholder="e.g., You are writing for a school magazine read by students and teachers in Hong Kong…"
                                  aria-label="Writing context"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="topic">Topic or Theme</label>
                        <input type="text" id="topic"
                               placeholder="e.g., Balancing technology and mental well-being"
                               aria-label="Topic or theme">
                    </div>

                    <div class="form-group">
                        <label for="wordLimit">Word Limit</label>
                        <select id="wordLimit" aria-label="Select word limit">
                            <option value="240-300">Part A: 240–300 words</option>
                            <option value="400-500">Part B: 400–500 words</option>
                        </select>
                    </div>

                    <button type="submit" id="generateTaskBtn" class="btn btn-primary btn-full">
                        ✨ Generate Task
                    </button>
                </form>

                <div id="taskOutput" class="output-box" role="region" aria-label="Generated task output" aria-live="polite">
                    <span class="output-empty">Your generated writing task will appear here…</span>
                </div>

                <div class="action-row">
                    <button id="downloadTaskBtn" class="btn btn-secondary" disabled aria-label="Download task as text file">
                        📄 Download Task
                    </button>
                </div>
            </div>

            <!-- ── Card 2: Sample Essay Generator ── -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon icon-purple">📖</div>
                    <div>
                        <div class="card-title">Generate Sample Essay</div>
                        <div class="card-hint">Generate a task first, then click below</div>
                    </div>
                </div>

                <p style="font-size:0.875rem; color:var(--text-muted); margin-bottom:1rem;">
                    Once you have generated a writing task, click the button below to produce a
                    Level&nbsp;7 model answer tailored to that task.
                </p>

                <button id="generateEssayBtn" class="btn btn-primary btn-full" disabled aria-label="Generate sample essay">
                    🖊️ Generate Sample Essay
                </button>

                <button id="score-btn" class="btn btn-secondary btn-full" style="margin-top:0.75rem" onclick="scoreWriting()" aria-label="Score my writing using DSE rubric">
                    📊 Score My Writing
                </button>

                <div id="essayOutput" class="output-box" style="margin-top:1rem;"
                     role="region" aria-label="Generated essay output" aria-live="polite">
                    <span class="output-empty">Generate a task first — your sample essay will appear here…</span>
                </div>

                <div class="action-row">
                    <button id="downloadEssayBtn" class="btn btn-secondary" disabled aria-label="Download task and essay as text file">
                        📄 Download Task &amp; Essay
                    </button>
                </div>

                <div id="score-panel"></div>
            </div>

        </div><!-- /content-grid -->

    </main>

    <!-- ===== Footer ===== -->
    <footer>
        <p><strong>EduGenius</strong> AI 智學 &nbsp;·&nbsp; Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
    </footer>

    <script>
        // ============================================================
        // EduGenius English Writing Practice – AI Logic
        // No external library dependencies
        // ============================================================

        const FALLBACK_MODELS = ['gemini-search', 'gemini-fast', 'glm', 'openai-fast', 'openai', 'deepseek'];
        const COPYRIGHT = 'Generated by EduGenius AI 智學\nCopyright © 2026 Hugo Wong. All rights reserved.';

        // ---- Toggle custom genre input ----
        function toggleCustomGenre() {
            const sel = document.getElementById('genre');
            const inp = document.getElementById('customGenre');
            inp.classList.toggle('hidden', sel.value !== 'other');
        }

        // ---- Call AI with model fallback ----
        async function callAI(prompt) {
            const messages = [{ role: 'user', content: prompt }];
            const token = window._fbUser ? await window._fbUser.getIdToken() : null;
            for (const model of FALLBACK_MODELS) {
                try {
                    const res = await fetch('./api/ai_proxy.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                        body: JSON.stringify({ subject: 'English', mode: 'writing', messages, model, stream: false, temperature: 0.9, max_tokens: 4096 })
                    });
                    if (!res.ok) continue;
                    const data = await res.json();
                    const content = data.choices?.[0]?.message?.content;
                    if (content) return content;
                } catch (e) {
                    console.warn(`Model ${model} failed:`, e.message);
                }
            }
            return '<p class="text-red-600">An error occurred while generating content. Please try again.</p>';
        }

        // ---- Format task response (AI returns HTML snippets) ----
        function formatTaskResponse(raw) {
            const wrap = document.createElement('div');
            wrap.className = 'prose max-w-none';
            wrap.innerHTML = raw;
            wrap.querySelectorAll('p').forEach(p => {
                p.className = 'mb-2 text-gray-700';
            });
            wrap.querySelectorAll('strong').forEach(s => {
                s.className = 'font-semibold text-gray-800';
            });
            wrap.querySelectorAll('ol').forEach(ol => {
                ol.className = 'list-decimal pl-6 mb-4 text-gray-700';
            });
            return wrap.outerHTML;
        }

        // ---- Format essay response (markdown-like plain text) ----
        function formatEssayResponse(raw, genre) {
            const genreStarters = {
                'letter': ['I am writing to express my view on', 'I am writing in response to'],
                'speech': ['Good morning, ladies and gentlemen', 'It is an honor to address you today'],
                'argumentative essay': ['It is often argued that', 'This essay will discuss'],
                'article': ["In today's fast-paced world", 'Recent developments in'],
                'report': ['This report aims to', 'The purpose of this report is to'],
                'narrative': ['It was a day I would never forget', 'As the sun set over'],
                'descriptive essay': ['The scene was one of breathtaking beauty', 'Imagine a place where']
            };
            const usefulPhrases = [
                { phrase: 'for the sake of',  translation: '為了' },
                { phrase: 'in light of',       translation: '鑑於' },
                { phrase: 'on the contrary',   translation: '相反地' },
                { phrase: 'by and large',      translation: '總體而言' },
                { phrase: 'in the long run',   translation: '長遠來看' }
            ];

            let text = raw;
            (genreStarters[genre.toLowerCase()] || []).forEach(s => {
                text = text.replace(new RegExp(`(${s})`, 'gi'), '**$1**');
            });
            usefulPhrases.forEach(({ phrase, translation }) => {
                text = text.replace(new RegExp(`\\b${phrase}\\b`, 'gi'), `_*${phrase}* (${translation})`);
            });

            const wrap = document.createElement('div');
            wrap.className = 'prose max-w-none';
            text.split('\n\n').filter(p => p.trim()).forEach(para => {
                const p = document.createElement('p');
                p.className = 'mb-4 text-gray-700 leading-relaxed';
                p.innerHTML = para
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>');
                wrap.appendChild(p);
            });
            return wrap.outerHTML;
        }

        // ---- Download as text file (no external library needed) ----
        function downloadText(text, filename) {
            const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // ---- Strip HTML tags to plain text for download ----
        function htmlToPlain(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.innerText || tmp.textContent || '';
        }

        // ---- State ----
        let currentTask  = '';
        let currentEssay = '';
        let currentGenre = '';

        // ---- DOM references ----
        const taskForm        = document.getElementById('taskForm');
        const taskOutput      = document.getElementById('taskOutput');
        const generateEssayBtn = document.getElementById('generateEssayBtn');
        const downloadTaskBtn  = document.getElementById('downloadTaskBtn');
        const downloadEssayBtn = document.getElementById('downloadEssayBtn');
        const generateTaskBtn  = document.getElementById('generateTaskBtn');
        const statusBar        = document.getElementById('statusBar');

        // ---- Task generation ----
        taskForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const genreEl = document.getElementById('genre');
            const customGenreEl = document.getElementById('customGenre');
            const genre = genreEl.value === 'other'
                ? (customGenreEl.value.trim() || 'custom genre')
                : genreEl.value;
            currentGenre = genre;

            const context   = document.getElementById('context').value.trim()
                              || 'A general context relevant to Hong Kong students';
            const topic     = document.getElementById('topic').value.trim()
                              || 'A relevant topic for Hong Kong youth';
            const wordLimit = document.getElementById('wordLimit').value;
            const part      = wordLimit.includes('240-300') ? 'Part A' : 'Part B';

            const prompt =
`Generate a Hong Kong DSE English Writing task designed to elicit Level 7 performance in Content, Language, and Organization. The task should meet these criteria:
- Content: Fully address the task, be entirely relevant, show creativity, develop ideas thoroughly with support, and show high audience awareness with appropriate register, tone, and style.
- Language: Use a wide range of accurate sentence structures (including complex ones), precise vocabulary, and near-perfect grammar/spelling.
- Organization: Feature logical development, sophisticated cohesive ties, and a coherent, genre-appropriate structure.
Requirements:
- Genre: ${genre}
- Context: ${context}
- Topic: ${topic}
- Word Limit: ${wordLimit} words
- Part: ${part}
- Include 2–3 guiding points to help structure the response, ensuring opportunities for sophisticated organization and cohesion.
- The task should be engaging, relevant to Hong Kong secondary students, and allow diverse perspectives or creative interpretations.
Format the output as:
<p><strong>Part:</strong> ${part}</p>
<p><strong>Genre:</strong> ${genre}</p>
<p><strong>Context:</strong> [context]</p>
<p><strong>Question:</strong> [question]</p>
<p><strong>Guiding Points:</strong></p>
<ol class="list-decimal pl-5 mb-4">[guiding points as <li> items]</ol>
<p><strong>Word Limit:</strong> ${wordLimit} words</p>`;

            // Update UI to loading state
            generateTaskBtn.disabled = true;
            generateTaskBtn.innerHTML = '<span class="spinner"></span> Generating…';
            taskOutput.innerHTML = '<div class="loading-text"><span class="spinner"></span> Generating your writing task…</div>';
            statusBar.classList.add('hidden');
            generateEssayBtn.disabled = true;
            downloadTaskBtn.disabled = true;
            downloadEssayBtn.disabled = true;
            currentEssay = '';

            currentTask = await callAI(prompt);
            taskOutput.innerHTML = formatTaskResponse(currentTask);
            // Save task to Firestore history (non-blocking)
            if (window.saveWritingTask) {
                window.saveWritingTask(genre, topic, currentTask);
            }

            // Restore UI
            generateTaskBtn.disabled = false;
            generateTaskBtn.innerHTML = '✨ Generate Task';
            generateEssayBtn.disabled = false;
            downloadTaskBtn.disabled = false;
            statusBar.classList.remove('hidden');
        });

        // ---- Essay generation ----
        generateEssayBtn.addEventListener('click', async () => {
            if (!currentTask) {
                document.getElementById('essayOutput').innerHTML =
                    '<p class="text-red-600">Please generate a task first.</p>';
                return;
            }
            const wordLimit = document.getElementById('wordLimit').value;
            const prompt =
`Generate a sample essay for the following Hong Kong DSE English Writing task:
${currentTask}
The essay should aim for Level 7 in Content (fully relevant, creative, well-supported ideas, high audience awareness), Language (wide range of accurate complex structures, precise vocabulary, near-perfect grammar), and Organization (logical development, sophisticated cohesive ties, genre-appropriate structure). Word count: ${wordLimit} words.`;

            const essayOutput = document.getElementById('essayOutput');
            generateEssayBtn.disabled = true;
            generateEssayBtn.innerHTML = '<span class="spinner"></span> Generating…';
            essayOutput.innerHTML = '<div class="loading-text"><span class="spinner"></span> Generating your sample essay…</div>';

            currentEssay = await callAI(prompt);
            essayOutput.innerHTML = formatEssayResponse(currentEssay, currentGenre);
            // Save essay to Firestore history (non-blocking)
            if (window.saveWritingEssay) {
                window.saveWritingEssay(currentEssay);
            }

            generateEssayBtn.disabled = false;
            generateEssayBtn.innerHTML = '🖊️ Generate Sample Essay';
            downloadEssayBtn.disabled = false;
        });

        // ---- Download task ----
        downloadTaskBtn.addEventListener('click', () => {
            if (!currentTask) return;
            const plain = htmlToPlain(currentTask);
            downloadText(
                `EduGenius – Hong Kong DSE English Writing Task\n${'='.repeat(50)}\n\n${plain}\n\n${'='.repeat(50)}\n${COPYRIGHT}`,
                'DSE_Writing_Task.txt'
            );
        });

        // ---- Download task + essay ----
        downloadEssayBtn.addEventListener('click', () => {
            if (!currentTask || !currentEssay) return;
            const plainTask  = htmlToPlain(currentTask);
            const plainEssay = htmlToPlain(formatEssayResponse(currentEssay, currentGenre));
            downloadText(
                `EduGenius – Hong Kong DSE English Writing Task & Sample Essay\n${'='.repeat(60)}\n\n--- WRITING TASK ---\n\n${plainTask}\n\n--- SAMPLE ESSAY ---\n\n${plainEssay}\n\n${'='.repeat(60)}\n${COPYRIGHT}`,
                'DSE_Writing_Task_and_Essay.txt'
            );
        });

        // ============================================================
        // Writing Templates
        // ============================================================
        const WRITING_TEMPLATES = [
            {
                name: '📝 Argumentative Essay',
                structure: `Introduction\n  • Hook sentence\n  • Background context\n  • Thesis statement\n\nBody Paragraph 1 – Point 1\n  • Topic sentence\n  • Evidence / example\n  • Explanation (link to thesis)\n\nBody Paragraph 2 – Point 2\n  • (same structure)\n\nCounter-argument Paragraph\n  • Acknowledge opposing view\n  • Refute with evidence\n\nConclusion\n  • Restate thesis (differently)\n  • Call to action / final thought`,
                starter: 'Opening: "In today\'s rapidly changing world, the issue of [topic] has become increasingly significant…"'
            },
            {
                name: '📧 Email / Formal Letter',
                structure: `Salutation: Dear [Name/Sir/Madam],\n\nPurpose Paragraph\n  • State reason for writing\n\nMain Body (1-2 paragraphs)\n  • Provide details / requests\n  • Use formal linking words\n\nClosing Paragraph\n  • Summarise / call to action\n\nComplimentary close:\nYours sincerely / faithfully,\n[Your name]`,
                starter: 'Opening: "I am writing with regard to [topic/issue] and would like to bring your attention to…"'
            },
            {
                name: '📋 Report',
                structure: `Title: Report on [Topic]\nPrepared by: [Name]  Date: [Date]\n\n1. Introduction / Aim\n   • Purpose of report\n\n2. Findings\n   2.1 [Sub-heading]\n   2.2 [Sub-heading]\n\n3. Conclusions\n\n4. Recommendations`,
                starter: 'Opening: "The aim of this report is to examine [topic] and to make recommendations based on the findings."'
            },
            {
                name: '🎤 Speech',
                structure: `Greeting: Good morning / afternoon, [audience].\n\nIntroduction\n  • Hook the audience\n  • State your topic and stance\n\nMain Points (2-3)\n  • Each point with evidence\n  • Use rhetorical devices\n  • Connect with audience\n\nConclusion\n  • Memorable closing line\n  • Call to action\n\nThank you.`,
                starter: 'Opening: "Have you ever wondered why [topic]? Today, I stand before you to shed light on this pressing issue…"'
            },
            {
                name: '📖 Short Story',
                structure: `Exposition\n  • Introduce character & setting\n\nRising Action\n  • Introduce conflict / problem\n\nClimax\n  • Turning point (most intense)\n\nFalling Action\n  • Events after the climax\n\nResolution\n  • How conflict is resolved\n  • Character's reflection / lesson`,
                starter: 'Opening: "It was the kind of [morning/evening] that made [character name] feel as if anything could happen…"'
            }
        ];

        function toggleTemplates() {
            const body = document.getElementById('templates-body');
            const arrow = document.getElementById('templates-arrow');
            const isOpen = body.classList.toggle('open');
            const btn = body.previousElementSibling;
            if (btn) btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (arrow) arrow.style.transform = isOpen ? 'rotate(180deg)' : '';
        }

        function renderTemplates() {
            const container = document.getElementById('templates-body');
            if (!container) return;
            container.innerHTML = WRITING_TEMPLATES.map((t, i) => `
                <div class="template-card">
                    <div class="template-name">${t.name}</div>
                    <div class="template-structure">${t.structure}</div>
                    <div class="template-starter">${t.starter}</div>
                    <button class="use-template-btn" onclick="useTemplate(${i})" aria-label="Use ${t.name} template">✏️ Use This Template</button>
                </div>`).join('');
        }

        function useTemplate(idx) {
            const t = WRITING_TEMPLATES[idx];
            const essayOutput = document.getElementById('essayOutput');
            if (essayOutput) {
                essayOutput.innerHTML = `<pre style="white-space:pre-wrap;font-family:inherit;font-size:0.9rem;color:var(--text)">[${t.name} – Fill in your content]\n\n${t.structure}\n\n---\nOpening suggestion:\n${t.starter}</pre>`;
                essayOutput.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            const body = document.getElementById('templates-body');
            if (body && body.classList.contains('open')) toggleTemplates();
        }

        // ============================================================
        // DSE Writing Scorer
        // ============================================================
        async function scoreWriting() {
            const essayOutput = document.getElementById('essayOutput');
            const essayText = essayOutput ? (essayOutput.innerText || essayOutput.textContent || '').trim() : '';
            if (!essayText || essayText.length < 50 || essayText.includes('Generate a task first')) {
                alert('Please generate a sample essay first (or write one), then click Score My Writing.');
                return;
            }

            const scoreBtn = document.getElementById('score-btn');
            const scorePanel = document.getElementById('score-panel');
            if (scoreBtn) { scoreBtn.disabled = true; scoreBtn.textContent = '⏳ Scoring…'; }
            if (scorePanel) scorePanel.innerHTML = '<div style="text-align:center;padding:1.5rem;color:#6b7280"><span class="spinner" style="margin:auto;display:block;width:24px;height:24px;border-width:3px"></span><p style="margin-top:.75rem;font-size:.875rem">Analysing your writing…</p></div>';

            const prompt = `You are a DSE English Writing examiner. Evaluate this student essay using the Hong Kong DSE English Paper 2 marking criteria.

Essay:
"""
${essayText.substring(0, 2000)}
"""

Return ONLY valid JSON (absolutely no other text):
{
  "content": { "score": 5, "max": 7, "feedback": "Brief feedback on ideas and task achievement" },
  "language": { "score": 4, "max": 7, "feedback": "Brief feedback on grammar, vocabulary, sentence structure" },
  "organisation": { "score": 5, "max": 7, "feedback": "Brief feedback on structure, coherence, paragraphing" },
  "overall": "One-sentence overall comment and single most important improvement tip.",
  "wordUpgrades": [
    { "original": "good", "upgraded": "exceptional", "reason": "More precise for academic writing" },
    { "original": "bad", "upgraded": "detrimental", "reason": "Stronger academic vocabulary" }
  ]
}`;

            try {
                const raw = await callAI(prompt);
                if (scoreBtn) { scoreBtn.disabled = false; scoreBtn.textContent = '📊 Score My Writing'; }

                let parsed = null;
                try {
                    const m = raw.match(/\{[\s\S]*\}/);
                    if (m) parsed = JSON.parse(m[0]);
                } catch (_) {}

                if (!parsed || !parsed.content) {
                    if (scorePanel) scorePanel.innerHTML = '<p style="color:#dc2626;text-align:center;padding:1rem">Could not parse scoring result. Please try again.</p>';
                    return;
                }

                const c = parsed.content, l = parsed.language, o = parsed.organisation;
                const total = (c.score || 0) + (l.score || 0) + (o.score || 0);
                const pct = Math.round((total / 21) * 100);

                const upgradeHtml = (parsed.wordUpgrades || []).length
                    ? `<div style="margin-top:0.75rem">
                        <p style="font-size:.8rem;font-weight:700;color:var(--text-secondary);margin-bottom:.4rem">💡 Word Upgrade Suggestions</p>
                        <ul class="upgrade-list">
                            ${(parsed.wordUpgrades || []).map(u => `<li class="upgrade-item">
                                <span class="upgrade-old">${u.original || ''}</span>
                                <span class="upgrade-arrow">→</span>
                                <span class="upgrade-new">${u.upgraded || ''}</span>
                                <span class="upgrade-reason">– ${u.reason || ''}</span>
                            </li>`).join('')}
                        </ul>
                       </div>` : '';

                if (scorePanel) scorePanel.innerHTML = `
                    <div class="score-panel">
                        <p style="font-size:.85rem;font-weight:700;color:var(--text-secondary);margin-bottom:.75rem">📊 DSE Writing Score</p>
                        <div class="score-grid">
                            ${[
                                { label: 'Content / Ideas', data: c },
                                { label: 'Language', data: l },
                                { label: 'Organisation', data: o }
                            ].map(item => `<div class="score-item">
                                <div class="score-label">${item.label}</div>
                                <div class="score-num">${item.data.score}<span class="score-max">/${item.data.max}</span></div>
                                <div class="score-bar-wrap"><div class="score-bar-fill" style="width:${Math.round((item.data.score / item.data.max) * 100)}%"></div></div>
                                <div class="score-feedback">${item.data.feedback || ''}</div>
                            </div>`).join('')}
                        </div>
                        <p style="text-align:center;font-size:.9rem;font-weight:700;color:var(--primary);margin-bottom:.5rem">Total: ${total}/21 (${pct}%)</p>
                        <p style="font-size:.85rem;color:var(--text-secondary);text-align:center;margin-bottom:.5rem">${parsed.overall || ''}</p>
                        ${upgradeHtml}
                    </div>`;
            } catch (err) {
                if (scoreBtn) { scoreBtn.disabled = false; scoreBtn.textContent = '📊 Score My Writing'; }
                if (scorePanel) scorePanel.innerHTML = `<p style="color:#dc2626;text-align:center;padding:1rem">Error: ${err.message}</p>`;
            }
        }

        renderTemplates();
    </script>
    <!-- Firebase auth guard -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";
        const app = initializeApp(window.__FIREBASE_CONFIG__);
        const auth = getAuth(app);
        const overlay = document.getElementById('auth-guard-overlay');
        onAuthStateChanged(auth, (user) => {
            if (user) {
                if (overlay) overlay.style.display = 'none';
                window._fbUser = user;
            } else {
                window.location.replace('./index.php');
            }
        });
        let _writingSessionId = null;
        window.saveWritingTask = async function(genre, topic, taskContent) {
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                const resp = await fetch('./api/history.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({
                        tool: 'English Writing',
                        subject: 'English',
                        page: 'eng-writing',
                        summary: `${genre}: ${topic}`.substring(0, 100),
                        messages: [
                            { role: 'user', content: `Genre: ${genre}\nTopic: ${topic}`, timestamp: new Date().toISOString() },
                            { role: 'assistant', content: taskContent, timestamp: new Date().toISOString() }
                        ]
                    })
                });
                const data = await resp.json();
                _writingSessionId = data.id || null;
            } catch (e) {
                console.warn('History save failed:', e);
            }
        };
        window.saveWritingEssay = async function(essayContent) {
            const user = window._fbUser;
            if (!user) return;
            try {
                if (_writingSessionId) {
                    const token = await user.getIdToken();
                    await fetch('./api/history.php?id=' + encodeURIComponent(_writingSessionId), {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                        body: JSON.stringify({
                            messages: [{ role: 'assistant', content: `[Sample Essay]\n${essayContent}`, timestamp: new Date().toISOString() }]
                        })
                    });
                }
            } catch (e) {
                console.warn('History save failed:', e);
            }
        };

        // ── Writing History Panel ───────────────────────────────────
        let _ewh = { all:[], lastDoc:null, loading:false };
        const EWH_PAGE_SIZE = 15;

        window.openWritingHistory = async function() {
            const backdrop = document.getElementById('writingHistoryBackdrop');
            const panel = document.getElementById('writingHistoryPanel');
            backdrop.style.display = 'block';
            requestAnimationFrame(() => {
                backdrop.classList.add('open');
                panel.classList.add('open');
            });
            _ewh = { all:[], lastDoc:null, lastCursor:null, loading:false };
            const listEl = document.getElementById('ewhList');
            listEl.innerHTML = `<div style="text-align:center;padding:2rem"><i class="fas fa-spinner fa-spin" style="color:var(--primary);font-size:1.5rem"></i></div>`;
            document.getElementById('ewhLoadMoreWrap').style.display = 'none';
            try {
                const user = window._fbUser;
                if (!user) { listEl.innerHTML = '<p style="text-align:center;color:var(--text-muted);font-size:.85rem;padding:2rem">Please sign in to view history.</p>'; return; }
                const token = await user.getIdToken();
                const resp = await fetch('./api/history.php?limit=' + EWH_PAGE_SIZE + '&tool=' + encodeURIComponent('English Writing'), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                const json = await resp.json();
                _ewh.all = json.docs || [];
                _ewh.lastCursor = _ewh.all.length ? _ewh.all[_ewh.all.length-1].updated_at : null;
                _ewhRender(_ewh.all);
                document.getElementById('ewhLoadMoreWrap').style.display = (_ewh.all.length >= EWH_PAGE_SIZE && _ewh.lastCursor) ? 'block' : 'none';
            } catch(e) {
                console.error(e);
                listEl.innerHTML = '<p style="text-align:center;color:#ef4444;font-size:.85rem;padding:2rem">Failed to load history.</p>';
            }
        };

        window.closeWritingHistory = function() {
            const backdrop = document.getElementById('writingHistoryBackdrop');
            const panel = document.getElementById('writingHistoryPanel');
            backdrop.classList.remove('open');
            panel.classList.remove('open');
            setTimeout(() => { backdrop.style.display = 'none'; }, 380);
        };

        window.loadMoreEWH = async function() {
            if (_ewh.loading) return;
            _ewh.loading = true;
            const btn = document.getElementById('ewhLoadMoreBtn');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading…'; }
            try {
                if (!_ewh.lastCursor) return;
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const resp = await fetch('./api/history.php?limit=' + EWH_PAGE_SIZE + '&tool=' + encodeURIComponent('English Writing') + '&after=' + encodeURIComponent(_ewh.lastCursor), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                const json = await resp.json();
                const more = json.docs || [];
                _ewh.lastCursor = more.length ? more[more.length-1].updated_at : null;
                _ewh.all = [..._ewh.all, ...more];
                _ewhRender(_ewh.all);
                document.getElementById('ewhLoadMoreWrap').style.display = (more.length >= EWH_PAGE_SIZE && _ewh.lastCursor) ? 'block' : 'none';
            } catch(e) { console.error(e); }
            finally {
                _ewh.loading = false;
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-chevron-down"></i> Load More'; }
            }
        };

        window.deleteEWHSession = async function(id) {
            if (!confirm('Delete this history entry?')) return;
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                await fetch('./api/history.php?id=' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                _ewh.all = _ewh.all.filter(s => s.id !== id);
                _ewhRender(_ewh.all);
            } catch(e) { alert('Failed to delete. Please try again.'); }
        };

        function _ewhEsc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function _ewhFmtTime(ts) {
            if (!ts) return '';
            const d = new Date(ts);
            return d.toLocaleString('en-HK',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
        }
        window._ewhToggle = function(id) {
            const body = document.getElementById('ewh-body-'+id);
            const chev = document.getElementById('ewh-chev-'+id);
            if (!body) return;
            const open = body.classList.toggle('open');
            if (chev) chev.style.transform = open ? 'rotate(180deg)' : '';
        };

        function _ewhRenderCard(s) {
            const msgs = s.messages || [];
            const userMsg = msgs.find(m => m.role === 'user');
            const aiMsg   = msgs.find(m => m.role === 'assistant');
            const uc = userMsg ? userMsg.content || '' : '';
            const genreMatch = uc.match(/Genre:\s*(.+)/);
            const topicMatch = uc.match(/Topic:\s*(.+)/);
            const genre = genreMatch ? genreMatch[1].trim() : '';
            const topic = topicMatch ? topicMatch[1].trim() : (s.summary || '');
            const preview = aiMsg ? (aiMsg.content||'').substring(0,300) : '';
            const time = _ewhFmtTime(s.updated_at);
            return `<div class="ewh-card" id="ewh-card-${s.id}">
                <div class="ewh-card-header" onclick="_ewhToggle('${s.id}')" role="button" tabindex="0"
                     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();_ewhToggle('${s.id}')}">
                    <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#4f46e5,#7c3aed);flex-shrink:0;font-size:1.1rem">✍️</div>
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:700;font-size:.8rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_ewhEsc((s.summary||topic).substring(0,60))}</p>
                        <p style="font-size:.7rem;color:var(--text-muted);margin-top:.1rem">${_ewhEsc(time)}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                        <button onclick="event.stopPropagation();deleteEWHSession('${s.id}')"
                                style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:rgba(239,68,68,.12);color:#ef4444;font-size:.7rem;display:flex;align-items:center;justify-content:center"
                                title="Delete" aria-label="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <i class="fas fa-chevron-down" id="ewh-chev-${s.id}" style="font-size:.65rem;color:var(--text-muted);transition:transform .3s"></i>
                    </div>
                </div>
                <div class="ewh-card-body" id="ewh-body-${s.id}">
                    ${genre ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Genre</span><p style="font-size:.8rem;color:var(--text);margin-top:.1rem">${_ewhEsc(genre)}</p></div>` : ''}
                    ${topic ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Topic / Context</span><p style="font-size:.8rem;color:var(--text);margin-top:.1rem">${_ewhEsc(topic.substring(0,200))}</p></div>` : ''}
                    ${preview ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Task Preview</span><p style="font-size:.78rem;color:var(--text);margin-top:.1rem;white-space:pre-wrap;max-height:160px;overflow-y:auto">${_ewhEsc(preview)}</p></div>` : ''}
                </div>
            </div>`;
        }

        function _ewhRender(sessions) {
            const list = document.getElementById('ewhList');
            if (!sessions.length) {
                list.innerHTML = `<div style="text-align:center;padding:3rem 1rem">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(79,70,229,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.4rem">✍️</div>
                    <p style="font-weight:700;font-size:.9rem;color:var(--text);margin-bottom:.35rem">No history yet</p>
                    <p style="font-size:.78rem;color:var(--text-muted)">Generate writing tasks to start building history.</p>
                </div>`;
                return;
            }
            list.innerHTML = sessions.map(s => _ewhRenderCard(s)).join('');
        }
    </script>
    <!-- Writing History Panel -->
    <div id="writingHistoryBackdrop" onclick="closeWritingHistory()" aria-hidden="true"></div>
    <div id="writingHistoryPanel" role="dialog" aria-modal="true" aria-label="Writing history">
        <div id="writingHistoryPanelHeader">
            <div style="display:flex;align-items:center;gap:.5rem">
                <div style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#4f46e5,#7c3aed);flex-shrink:0;font-size:.95rem">✍️</div>
                <span style="font-weight:700;font-size:.95rem;color:var(--text)">Writing History</span>
            </div>
            <button onclick="closeWritingHistory()" aria-label="Close history"
                    style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.05);color:var(--text-muted);font-size:.85rem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="writingHistoryPanelBody">
            <div id="ewhList"></div>
            <div id="ewhLoadMoreWrap" style="display:none;text-align:center;padding:.75rem 0">
                <button id="ewhLoadMoreBtn" onclick="loadMoreEWH()"
                        style="border:1px solid var(--border);background:var(--card-bg);color:var(--text);padding:.4rem 1.25rem;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer">
                    <i class="fas fa-chevron-down"></i> Load More
                </button>
            </div>
        </div>
    </div>

<script>
    (function() {
        const params = new URLSearchParams(window.location.search);
        const back = params.get('back');
        const link = document.getElementById('back-btn-link');
        if (link) {
            link.href = './index.php' + (back ? '?openSubject=' + encodeURIComponent(back) : '');
        }
    })();
</script>
<script>
    // Theme toggle
    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        document.querySelectorAll('.theme-moon').forEach(el => el.style.display = isDark ? 'none' : '');
        document.querySelectorAll('.theme-sun').forEach(el => el.style.display = isDark ? '' : 'none');
    }
    (function() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
            document.querySelectorAll('.theme-moon').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.theme-sun').forEach(el => el.style.display = '');
        }
    })();
</script>
</body>
</html>
