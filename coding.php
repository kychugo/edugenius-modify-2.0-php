<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI-powered Python code review tool – submit your code and get instant feedback from EduGenius AI.">
    <title>AI Code Review - EduGenius</title>

    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/material-darker.min.css">

    <style>
        :root {
            --primary: #7c3aed; --primary-dark: #5b21b6; --secondary: #06b6d4;
            --text-primary: #1e1b4b; --text-secondary: #64748b;
            --bg-main: #f0f2ff;
            --glass-bg: rgba(255,255,255,0.80); --glass-border: rgba(255,255,255,0.65);
            --shadow-glow: 0 0 40px rgba(124,58,237,0.10);
            --radius: 20px; --radius-sm: 12px;
        }
        * { font-family: 'Plus Jakarta Sans','Inter','Segoe UI',Arial,sans-serif; box-sizing: border-box; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        html { scroll-behavior: smooth; }
        body {
            min-height:100vh; background-color:var(--bg-main);
            position:relative; overflow-x:hidden;
            color:var(--text-primary);
        }
        body::before {
            content:''; position:fixed; inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.18) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 5%  95%, rgba(99,102,241,.10) 0%,transparent 55%);
            pointer-events:none; z-index:0;
        }
        .glass-card {
            background:var(--glass-bg);
            backdrop-filter:blur(24px) saturate(160%); -webkit-backdrop-filter:blur(24px) saturate(160%);
            border:1px solid var(--glass-border); border-radius:var(--radius);
            box-shadow:0 8px 32px rgba(0,0,0,.08),var(--shadow-glow);
            position:relative; overflow:hidden;
        }
        .glass-card::before {
            content:''; position:absolute; top:0;left:0;right:0; height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent); pointer-events:none;
        }
        .gradient-text { background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .btn-primary {
            background:linear-gradient(135deg,#7c3aed,#5b21b6); color:#fff!important; border:none;
            border-radius:var(--radius-sm); font-weight:700; transition:all .3s ease;
        }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.42); }
        .btn-success {
            background:linear-gradient(135deg,#10b981,#059669); color:#fff!important; border:none;
            border-radius:var(--radius-sm); font-weight:700; transition:all .3s ease;
        }
        .btn-success:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(16,185,129,.35); }
        .btn-back {
            background:var(--glass-bg); color:var(--primary); border:1px solid var(--glass-border);
            border-radius:var(--radius-sm); font-weight:600; transition:all .25s ease; backdrop-filter:blur(12px);
            display:inline-flex; align-items:center; gap:8px;
        }
        .btn-back:hover { border-color:rgba(124,58,237,.45); background:rgba(124,58,237,.06); }
        .input-field {
            background:var(--glass-bg); border:1.5px solid var(--glass-border);
            border-radius:var(--radius-sm); color:var(--text-primary); transition:all .25s ease;
        }
        .input-field:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(124,58,237,.15); }
        .input-field::placeholder { color:var(--text-secondary); }

        /* Markdown Content */
        .markdown-content pre { background:#1e1e2e; color:#e2e8f0; padding:1rem; border-radius:10px; overflow-x:auto; font-family:'Monaco','Menlo','Ubuntu Mono',monospace; font-size:.85rem; margin:.75rem 0; }
        .markdown-content code { background:rgba(124,58,237,.10); color:var(--primary); padding:.15rem .4rem; border-radius:6px; font-family:'Monaco','Menlo','Ubuntu Mono',monospace; font-size:.85rem; }
        .markdown-content h1,.markdown-content h2,.markdown-content h3 { font-weight:700; margin-top:1.25rem; margin-bottom:.5rem; color:var(--text-primary); }
        .markdown-content h1 { font-size:1.4rem; } .markdown-content h2 { font-size:1.2rem; } .markdown-content h3 { font-size:1.05rem; }
        .markdown-content ul { list-style-type:disc; margin-left:1.5rem; margin-bottom:.75rem; }
        .markdown-content ol { list-style-type:decimal; margin-left:1.5rem; margin-bottom:.75rem; }
        .markdown-content p { margin-bottom:.75rem; line-height:1.7; }

        /* CodeMirror */
        .CodeMirror { height:280px; border:1.5px solid var(--glass-border); border-radius:12px; font-family:'Monaco','Menlo','Ubuntu Mono',monospace; font-size:.875rem; }
        .cm-s-material-darker.CodeMirror { background:#1a1a2e; border-color:rgba(124,58,237,.3); }

        .section-label { font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--text-secondary); margin-bottom:8px; display:block; }
        .animate-fadeInUp { animation: fadeInUp .6s cubic-bezier(.16,1,.3,1) both; }
        .feedback-placeholder { display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:280px; color:var(--text-secondary); text-align:center; padding:2rem; }

        /* ---- Code Annotation ---- */
        .annotation-line { display:flex; gap:0.75rem; padding:0.35rem 0; border-bottom:1px solid rgba(124,58,237,.08); font-size:0.82rem; font-family:'Monaco','Menlo','Ubuntu Mono',monospace; }
        .annotation-code { flex:0 0 50%; color:#e2e8f0; background:#1a1a2e; padding:0.2rem 0.5rem; border-radius:4px; white-space:pre; overflow-x:auto; }
        .annotation-comment { flex:1; color:var(--text-secondary); font-family:'Plus Jakarta Sans','Inter',sans-serif; font-size:0.8rem; line-height:1.5; padding:0.1rem 0; }
        .annotation-wrap { background:#1a1a2e; border-radius:12px; padding:0.75rem; max-height:400px; overflow-y:auto; }
        /* ---- Code Completion – styles moved to code-completion.html ---- */

.dark {
    --primary: #a78bfa;
    --primary-dark: #7c3aed;
    --secondary: #22d3ee;
    --text-primary: #e2e8f0;
    --text-secondary: #94a3b8;
    --bg-main: #0d0d1a;
    --glass-bg: rgba(15,15,40,0.85);
    --glass-border: rgba(255,255,255,0.08);
    --shadow-glow: 0 0 60px rgba(124,58,237,0.18);
}
.dark body { background-color: var(--bg-main); color: var(--text-primary); }
.dark body::before {
    background:
        radial-gradient(ellipse 80% 60% at 15% 5%, rgba(124,58,237,.32) 0%,transparent 55%),
        radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.22) 0%,transparent 55%);
}
.dark .glass-card { background: var(--glass-bg); border-color: var(--glass-border); }
.dark .input-field { background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.15); color: #e2e8f0; }
.dark .input-field::placeholder { color: #64748b; }
.dark .btn-back { background: rgba(15,15,40,.8); color: var(--primary); }
.dark .section-label { color: #94a3b8; }
.dark .feedback-placeholder { color: #94a3b8; }
/* Mobile responsive */
@media (max-width: 768px) {
    .CodeMirror { height: 200px; }
    .advanced-tools-grid { grid-template-columns: 1fr !important; }
}
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
/* Code Review History Panel */
#crHistoryBackdrop {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:900;
    backdrop-filter:blur(2px); transition:opacity .3s;
}
#crHistoryBackdrop.open { opacity:1; }
#crHistoryPanel {
    position:fixed; top:0; right:0; bottom:0; z-index:901;
    width:min(500px,100vw);
    background:var(--bg-main);
    border-left:1px solid var(--glass-border);
    box-shadow:-8px 0 32px rgba(0,0,0,.18);
    display:flex; flex-direction:column;
    transform:translateX(100%);
    transition:transform .35s cubic-bezier(.16,1,.3,1);
    overflow:hidden;
}
#crHistoryPanel.open { transform:translateX(0); }
#crHistoryPanelHeader {
    display:flex; align-items:center; justify-content:space-between;
    padding:1rem 1.25rem;
    background:var(--glass-bg);
    border-bottom:1px solid var(--glass-border);
    flex-shrink:0;
}
#crHistoryPanelBody { flex:1; overflow-y:auto; padding:1rem; }
#crHistoryPanelBody::-webkit-scrollbar { width:4px; }
#crHistoryPanelBody::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
.crh-card {
    background:var(--glass-bg); border:1px solid var(--glass-border);
    border-radius:12px; margin-bottom:.75rem; overflow:hidden;
    animation:fadeInUp .3s ease both;
}
.crh-card-header {
    display:flex; align-items:center; gap:.75rem;
    padding:.85rem 1rem; cursor:pointer; transition:background .2s;
}
.crh-card-header:hover { background:rgba(124,58,237,.06); }
.crh-card-body {
    max-height:0; overflow:hidden;
    transition:max-height .35s ease, padding .2s;
    padding:0 1rem;
}
.crh-card-body.open { max-height:500px; overflow-y:auto; padding:.75rem 1rem; }
.crh-card-body::-webkit-scrollbar { width:3px; }
.crh-card-body::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
    </style>
    <?= firebaseConfigScript() ?>
</head>
<body class="transition-all duration-300">
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
    <div class="container mx-auto px-4 py-8 max-w-6xl relative z-10">

        <!-- Back link + header -->
        <div class="flex items-center justify-between mb-6 animate-fadeInUp">
            <a id="back-btn-link" href="./index.php" class="btn-back px-4 py-2 text-sm" aria-label="Back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="flex items-center gap-2">
                <button onclick="openCRHistory()" title="View Code Review History" aria-label="View history"
                        style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:.95rem;transition:all .2s">
                    <i class="fas fa-history"></i>
                </button>
                <button id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode" style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:1rem;transition:all .2s">
                    <span class="theme-moon">🌙</span><span class="theme-sun" style="display:none">☀️</span>
                </button>
            </div>
        </div>

        <!-- Page header -->
        <div class="glass-card p-8 mb-8 text-center animate-fadeInUp">
            <div class="flex items-center justify-center gap-4 mb-3">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden" style="background:linear-gradient(135deg,#7c3aed,#06b6d4)">
                <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius AI Logo" style="width:100%;height:100%;object-fit:cover">
                </div>
                <h1 class="text-3xl font-extrabold"><span class="gradient-text">AI Code Review</span></h1>
            </div>
            <p class="text-base mb-4" style="color:var(--text-secondary)">Submit your programming question and code for AI-powered feedback</p>
            <div class="flex items-center justify-center gap-6 text-xs font-semibold" style="color:var(--text-secondary)">
                <span class="flex items-center gap-1.5"><i class="fab fa-python" style="color:#3b82f6"></i>Python Support</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-file-code" style="color:#10b981"></i>File Upload</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-download" style="color:#f59e0b"></i>DOCX Export</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Input Section -->
            <div class="glass-card p-6 animate-fadeInUp">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#7c3aed,#5b21b6)">
                        <i class="fas fa-edit text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-extrabold" style="color:var(--text-primary)">Input Question &amp; Code</h2>
                </div>

                <div class="space-y-5">
                    <div>
                        <span class="section-label"><i class="fas fa-question-circle mr-1" style="color:var(--primary)"></i>Programming Question</span>
                        <textarea id="question" rows="4"
                            class="input-field w-full px-4 py-3 text-sm resize-none outline-none"
                            placeholder="Enter your programming question or problem description..."></textarea>
                    </div>

                    <div>
                        <span class="section-label"><i class="fas fa-code mr-1" style="color:var(--primary)"></i>Python Code</span>
                        <div id="code-editor" class="rounded-xl overflow-hidden"></div>
                    </div>

                    <div>
                        <span class="section-label"><i class="fas fa-upload mr-1" style="color:var(--primary)"></i>Or Upload Code File</span>
                        <input type="file" id="file-upload" accept=".py,.ipynb"
                            class="input-field w-full px-4 py-2.5 text-sm outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:cursor-pointer"
                            style="file:background:rgba(124,58,237,.12);file:color:#7c3aed">
                        <p class="text-xs mt-1.5" style="color:var(--text-secondary)">Supports .py and .ipynb files. You can also drag &amp; drop files onto the code editor.</p>
                    </div>

                    <button onclick="submitCode()" id="submit-btn"
                            class="w-full btn-primary py-3 px-6 text-sm rounded-xl">
                        <i class="fas fa-paper-plane mr-2"></i>Submit for AI Review
                    </button>
                    <button onclick="annotateCode()" id="annotate-btn-2" class="w-full btn-success py-3 px-6 text-sm rounded-xl mt-3">
                        <i class="fas fa-align-left mr-2"></i>📝 Line-by-Line Annotation
                    </button>
                </div>
            </div>

            <!-- Output Section -->
            <div class="glass-card p-6 animate-fadeInUp">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#059669)">
                            <i class="fas fa-brain text-white text-sm"></i>
                        </div>
                        <h2 class="text-xl font-extrabold" style="color:var(--text-primary)">AI Feedback</h2>
                    </div>
                    <button onclick="exportToDOCX()" id="export-btn"
                            class="btn-success py-2 px-4 text-xs rounded-xl">
                        <i class="fas fa-file-word mr-1.5"></i>Export DOCX
                    </button>
                </div>

                <div id="feedback" class="min-h-96 rounded-xl p-4 markdown-content"
                     style="background:rgba(124,58,237,.04);border:1.5px dashed rgba(124,58,237,.20)">
                    <div class="feedback-placeholder">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4"
                             style="background:rgba(124,58,237,.10)">
                            <i class="fas fa-comments text-2xl" style="color:var(--primary)"></i>
                        </div>
                        <p class="text-base font-semibold mb-1">AI feedback will appear here</p>
                        <p class="text-sm">Submit your question and code to get started</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced ICT Tools Section -->
        <div class="glass-card p-6 mt-8 animate-fadeInUp">
            <h3 class="text-lg font-extrabold mb-4" style="color:var(--text-primary)">
                <i class="fas fa-tools mr-2" style="color:var(--primary)"></i>Advanced ICT Tools
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Line-by-line Annotation -->
                <div class="rounded-2xl p-4" style="background:rgba(124,58,237,.04);border:1.5px solid rgba(124,58,237,.15)">
                    <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">
                        <i class="fas fa-align-left mr-1" style="color:var(--primary)"></i>📝 Line-by-Line Code Annotation
                    </h4>
                    <p class="text-xs mb-3" style="color:var(--text-secondary)">Get a Cantonese explanation for every line of code in the editor above.</p>
                    <button id="annotate-btn" onclick="annotateCode()" class="btn-primary py-2 px-4 text-xs rounded-xl">
                        <i class="fas fa-align-left mr-2"></i>📝 Annotate Code
                    </button>
                </div>
                <!-- Code Completion Exercise – now a dedicated platform -->
                <div class="rounded-2xl p-4" style="background:rgba(16,185,129,.04);border:1.5px solid rgba(16,185,129,.15)">
                    <h4 class="font-bold text-sm mb-2" style="color:var(--text-primary)">
                        <i class="fas fa-puzzle-piece mr-1" style="color:#10b981"></i>✏️ Code Completion Exercise
                    </h4>
                    <p class="text-xs mb-3" style="color:var(--text-secondary)">
                        Practice Python with AI-generated fill-in-the-blank exercises. Choose your difficulty,
                        complete the code, and get instant teacher-style AI feedback on every answer.
                    </p>
                    <ul class="text-xs mb-4 space-y-1" style="color:var(--text-secondary)">
                        <li class="flex items-center gap-1.5"><i class="fas fa-check-circle" style="color:#10b981"></i>Adjustable difficulty (Easy / Medium / Hard)</li>
                        <li class="flex items-center gap-1.5"><i class="fas fa-check-circle" style="color:#10b981"></i>AI checks semantic correctness — not just exact text</li>
                        <li class="flex items-center gap-1.5"><i class="fas fa-check-circle" style="color:#10b981"></i>Detailed explanation for every wrong answer</li>
                    </ul>
                    <a id="open-completion-link" href="./code-completion.php" target="_blank"
                       class="btn-success py-2 px-4 text-xs rounded-xl inline-flex items-center gap-2"
                       style="text-decoration:none">
                        <i class="fas fa-external-link-alt"></i>Open Code Completion Platform
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="glass-card p-6 mt-8 animate-fadeInUp">
            <h3 class="text-lg font-extrabold mb-6 text-center" style="color:var(--text-primary)">
                <i class="fas fa-star mr-2" style="color:#f59e0b"></i>Platform Features
            </h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-4 rounded-2xl" style="background:rgba(59,130,246,.06)">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:rgba(59,130,246,.15)">
                        <i class="fas fa-search text-xl" style="color:#3b82f6"></i>
                    </div>
                    <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Code Analysis</h4>
                    <p class="text-xs" style="color:var(--text-secondary)">Comprehensive syntax and logic checking</p>
                </div>
                <div class="text-center p-4 rounded-2xl" style="background:rgba(16,185,129,.06)">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:rgba(16,185,129,.15)">
                        <i class="fas fa-graduation-cap text-xl" style="color:#10b981"></i>
                    </div>
                    <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Educational Focus</h4>
                    <p class="text-xs" style="color:var(--text-secondary)">Aligned with HKDSE ICT syllabus</p>
                </div>
                <div class="text-center p-4 rounded-2xl" style="background:rgba(245,158,11,.06)">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:rgba(245,158,11,.15)">
                        <i class="fas fa-lightbulb text-xl" style="color:#f59e0b"></i>
                    </div>
                    <h4 class="font-bold text-sm mb-1" style="color:var(--text-primary)">Smart Suggestions</h4>
                    <p class="text-xs" style="color:var(--text-secondary)">Improvement recommendations and best practices</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center py-4 text-xs" style="color:var(--text-secondary)">
            <p><i class="fas fa-copyright mr-1"></i>Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
        </div>
    </div>

    <!-- Third-party JS Libraries -->
    <script src="https://unpkg.com/docx@7.8.2/build/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script>

    <!-- CodeMirror JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/edit/closebrackets.js"></script>

    <script>
        const { Document, Packer, Paragraph, TextRun, HeadingLevel, AlignmentType } = docx;
        
        // --- IMPROVEMENT: Initialize CodeMirror Editor ---
        const codeEditor = CodeMirror(document.getElementById('code-editor'), {
            mode: 'python',
            theme: 'material-darker',
            lineNumbers: true,
            autoCloseBrackets: true,
            extraKeys: {
                "Tab": function(cm) {
                    cm.replaceSelection("    ", "end");
                }
            }
        });
        codeEditor.setValue("# Paste your Python code here...\ndef example_function():\n    return 'Hello, World!'");
        window.codeEditor = codeEditor; // Expose for module scripts (session restore)

        async function submitCode() {
            const question = document.getElementById('question').value.trim();
            // IMPROVEMENT: Get code from CodeMirror instance
            const code = codeEditor.getValue().trim();
            const fileInput = document.getElementById('file-upload');
            const submitBtn = document.getElementById('submit-btn');
            let codeContent = code;

            if (!question || (!code && fileInput.files.length === 0)) {
                displayFeedback('Please provide a question and either paste code or upload a file.', 'error');
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyzing...';
            submitBtn.disabled = true;

            try {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    if (file.name.endsWith('.py')) {
                        codeContent = await file.text();
                    } else if (file.name.endsWith('.ipynb')) {
                        codeContent = await parseIpynb(file);
                    } else {
                        throw new Error('Unsupported file format. Please upload a .py or .ipynb file.');
                    }
                    // Load file content into the editor
                    codeEditor.setValue(codeContent);
                }

                const prompt = `You are an AI programming assistant designed for Hong Kong DSE Information and Communication Technology (ICT) students, focusing on the Python programming components of the 2025 HKDSE syllabus. The user has submitted a programming question and Python code. Your task is to analyze the code and provide feedback based on the following requirements, ensuring alignment with the HKDSE ICT syllabus (Compulsory Part: Computational Thinking and Programming; Elective Part: Algorithm and Programming):

1. **Syntactic Validity**:
- Check if the code is syntactically correct and can run without errors.
- Identify any syntax errors, run-time errors (e.g., overflow, underflow), or logical errors.
- Provide specific fixes for errors, explaining why they occur in the context of Python basics (e.g., variable assignment, data types, or control structures).

2. **Testing with Sample Inputs**:
- Test the code with sample inputs relevant to the question, covering
- Valid data (e.g., typical inputs like integers, floats, strings, or lists).
- Boundary cases (e.g., empty lists, zero, or maximum/minimum values).
- Invalid data (e.g., incorrect input types or values).
- Verify if the code produces correct outputs according to the Input-Process-Output (IPO) cycle.
- If outputs are incorrect, suggest fixes with explanations tied to syllabus topics like control structures (sequence, selection, iteration) or data structures (lists, strings).

3. **Alignment with HKDSE ICT Syllabus**:
- Ensure feedback addresses key syllabus topics:
- **Fundamentals**: Variables, data types (int, float, bool, str), mutability (immutable: int, float, bool, str; mutable: lists), operators (arithmetic, relational, Boolean), and order of operations.
- **Control Structures**: Sequence, selection (if-then-else), iteration (for/while loops, nested loops for searching/sorting).
- **Data Structures**: Lists (1D and 2D for tables/matrices), strings (processing with find(), split(), replace()), stacks/queues (using lists, handling underflow/overflow).
- **Sub-programs**: Defining functions with def, parameter passing (immutable vs. mutable objects), variable scope (local vs. global), return statements, and call stack.
- **File Handling**: Reading/writing text files (open() with 'r', 'w', 'a' modes, close(), os.path.exists(), os.remove()), processing CSV or text data.
- **Algorithms**: Linear search, binary search (for sorted lists), sorting (selection, insertion, bubble sort), and swapping values.
- **Testing/Debugging**: Identify syntax, run-time, logical, overflow, truncation, or rounding errors; suggest test data strategies (valid, boundary, invalid).
- Avoid using advanced Python built-in functions (e.g., map(), filter(), lambda) unless explicitly required by the question, focusing on syllabus-appropriate methods.

4. **Improvements**:
- Suggest improvements to enhance:
- **Modularity**: Break code into functions for reusability and clarity.
- **Simplicity**: Simplify logic while maintaining functionality, using basic control structures and data types.
- **Completeness**: Ensure the code handles all cases (e.g., edge cases, invalid inputs) as per the question.
- **Efficiency**: Compare algorithm efficiency (e.g., linear vs. binary search) and suggest optimizations where relevant.
- Provide code snippets for improvements, using only syllabus-relevant constructs.

5. **Formatting**:
- Format the response in Markdown with clear sections: ## Validity, ## Test Results, ## Syllabus-Specific Feedback, ## Improvements.
- Use code blocks (\`\`\`python) for all code snippets, including fixes and improvements.
- Explain feedback in a beginner-friendly way, avoiding jargon unless explained (e.g., define "mutable" or "call stack").
- Relate feedback to specific HKDSE ICT syllabus topics to reinforce learning objectives.

Question: ${question}
Code: 
\`\`\`python
${codeContent}
\`\`\`
`;
                displayFeedback('Analyzing code with AI...', 'loading');
                const feedback = await callGrokAPI(prompt);
                displayFeedback(feedback, 'success');
                // Save to Firestore history (non-blocking)
                if (window.saveHistoryRecord) {
                    const snippet = codeContent.substring(0, 500) + (codeContent.length > 500 ? '\n...' : '');
                    window.saveHistoryRecord(question, snippet, feedback);
                }
            } catch (error) {
                displayFeedback(`Error: ${error.message}. Please check your API configuration.`, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit for AI Review';
                submitBtn.disabled = false;
            }
        }

        async function parseIpynb(file) {
            try {
                const text = await file.text();
                const json = JSON.parse(text);
                let code = '';
                for (const cell of json.cells) {
                    if (cell.cell_type === 'code') {
                        code += cell.source.join('') + '\n';
                    }
                }
                return code;
            } catch (error) {
                throw new Error('Failed to parse .ipynb file: ' + error.message);
            }
        }

        async function callGrokAPI(prompt) {
            const token = window._fbUser ? await window._fbUser.getIdToken() : null;
            const response = await fetch('./api/ai_proxy.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                body: JSON.stringify({
                    subject: 'ICT',
                    mode: 'code_review',
                    messages: [{ role: 'user', content: prompt }],
                    stream: false,
                    temperature: 0,
                    max_tokens: 4096
                })
            });
            if (!response.ok) throw new Error('Request failed: ' + response.status);
            const data = await response.json();
            const content = data.choices?.[0]?.message?.content;
            if (!content) throw new Error('All models failed to generate a response');
            return content;
        }
        
        function displayFeedback(message, type) {
            const feedbackDiv = document.getElementById('feedback');
            if (type === 'loading') {
                feedbackDiv.innerHTML = `<div class="flex items-center justify-center h-full"><div class="text-center"><i class="fas fa-spinner fa-spin text-4xl text-indigo-600 mb-4"></i><p class="text-lg text-gray-600">${message}</p></div></div>`;
                feedbackDiv.className = 'min-h-96 p-4 bg-blue-50 rounded-lg border-2 border-blue-200 markdown-content animate-pulse-slow';
            } else if (type === 'success') {
                feedbackDiv.innerHTML = marked.parse(message);
                feedbackDiv.className = 'min-h-96 p-4 bg-green-50 rounded-lg border-2 border-green-200 markdown-content';
            } else if (type === 'error') {
                feedbackDiv.innerHTML = `<div class="flex items-center justify-center h-full"><div class="text-center"><i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i><p class="text-lg text-red-600">${message}</p></div></div>`;
                feedbackDiv.className = 'min-h-96 p-4 bg-red-50 rounded-lg border-2 border-red-200 markdown-content';
            }
        }

        // --- IMPROVEMENT: DOCX export logic updated for better code formatting ---
        function createCodeBlock(codeText) {
            const lines = codeText.split('\n');
            return lines.map(line => new Paragraph({
                children: [new TextRun({
                    text: line,
                    font: 'Courier New',
                    size: 20 // 10pt font size
                })],
                spacing: { after: 0 },
                style: "codeStyle"
            }));
        }

        function parseMarkdownToDocxElements(markdown) {
            const elements = [];
            const lines = markdown.split('\n');
            let inCodeBlock = false;
            let codeBlockContent = [];

            for (const line of lines) {
                if (line.startsWith('```')) {
                    if (inCodeBlock) {
                        elements.push(...createCodeBlock(codeBlockContent.join('\n')));
                        codeBlockContent = [];
                        inCodeBlock = false;
                    } else {
                        inCodeBlock = true;
                    }
                    continue;
                }

                if (inCodeBlock) {
                    codeBlockContent.push(line);
                    continue;
                }

                if (line.startsWith('# ')) {
                    elements.push(new Paragraph({ text: line.replace('# ', ''), heading: HeadingLevel.HEADING_1, spacing: { after: 200 } }));
                } else if (line.startsWith('## ')) {
                    elements.push(new Paragraph({ text: line.replace('## ', ''), heading: HeadingLevel.HEADING_2, spacing: { after: 150 } }));
                } else if (line.startsWith('### ')) {
                    elements.push(new Paragraph({ text: line.replace('### ', ''), heading: HeadingLevel.HEADING_3, spacing: { after: 100 } }));
                } else if (line.match(/^(\s*[-*+])\s/)) {
                    const match = line.match(/^(\s*[-*+])\s(.*)/);
                    const text = match[2];
                    elements.push(new Paragraph({ children: [new TextRun({ text: '•\t' + text, size: 24 })], spacing: { after: 100 } }));
                } else if (line.trim()) {
                    elements.push(new Paragraph({ children: [new TextRun({ text: line, size: 24 })], spacing: { after: 200 } }));
                }
            }
            if(codeBlockContent.length > 0) {
                 elements.push(...createCodeBlock(codeBlockContent.join('\n')));
            }

            return elements;
        }

        async function exportToDOCX() {
            const question = document.getElementById('question').value.trim();
            // IMPROVEMENT: Get code from CodeMirror
            const code = codeEditor.getValue().trim();
            const feedbackRaw = document.getElementById('feedback').innerText.trim();
            const fileInput = document.getElementById('file-upload');
            const exportBtn = document.getElementById('export-btn');

            if (!question || !feedbackRaw || feedbackRaw.includes('AI feedback will appear here')) {
                displayFeedback('No content to export. Please submit a question and code first.', 'error');
                return;
            }

            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
            exportBtn.disabled = true;

            try {
                const feedbackElements = parseMarkdownToDocxElements(document.getElementById('feedback').innerText);

                const doc = new Document({
                    styles: {
                        paragraphStyles: [{
                            id: "codeStyle",
                            name: "Code Block",
                            basedOn: "Normal",
                            next: "Normal",
                            run: {
                                font: "Courier New",
                                size: 20,
                            },
                            paragraph: {
                                spacing: { before: 100, after: 100 },
                            },
                        }]
                    },
                    sections: [{
                        properties: {
                            footer: {
                                default: new docx.Footer({
                                    children: [new Paragraph({
                                        children: [new TextRun({ text: "Copyright © 2026 Hugo Wong. All rights reserved.", size: 20, color: "666666" })],
                                        alignment: AlignmentType.CENTER
                                    })]
                                })
                            }
                        },
                        children: [
                            new Paragraph({ text: 'AI Code Review Report', heading: HeadingLevel.HEADING_1, spacing: { after: 200 } }),
                            new Paragraph({ text: 'Question:', heading: HeadingLevel.HEADING_2, spacing: { after: 100 } }),
                            new Paragraph({ children: [new TextRun({ text: question, size: 24 })], spacing: { after: 200 } }),
                            new Paragraph({ text: 'Submitted Code:', heading: HeadingLevel.HEADING_2, spacing: { after: 100 } }),
                            // IMPROVEMENT: Use the createCodeBlock function for the submitted code
                            ...createCodeBlock(code || (fileInput.files.length > 0 ? 'Content from uploaded file' : 'No code provided')),
                            new Paragraph({ text: 'AI Feedback:', heading: HeadingLevel.HEADING_2, spacing: { after: 100 } }),
                            ...feedbackElements
                        ]
                    }]
                });

                const blob = await Packer.toBlob(doc);
                saveAs(blob, 'ai_code_review_report.docx');
            } catch (error) {
                console.error("DOCX Export Error:", error);
                displayFeedback(`Failed to generate DOCX: ${error.message}`, 'error');
            } finally {
                exportBtn.innerHTML = '<i class="fas fa-file-word mr-2"></i>Export DOCX';
                exportBtn.disabled = false;
            }
        }

        // --- IMPROVEMENT: Updated drag and drop for CodeMirror ---
        const editorWrapper = codeEditor.getWrapperElement();
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            editorWrapper.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            editorWrapper.addEventListener(eventName, () => editorWrapper.style.backgroundColor = 'rgba(102, 126, 234, 0.1)', false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            editorWrapper.addEventListener(eventName, () => editorWrapper.style.backgroundColor = '', false);
        });
        
        editorWrapper.addEventListener('drop', handleDrop, false);

        async function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                if (file.name.endsWith('.py') || file.name.endsWith('.ipynb')) {
                    document.getElementById('file-upload').files = files; // Keep file input in sync
                    
                    let content = '';
                    if (file.name.endsWith('.py')) {
                        content = await file.text();
                    } else if (file.name.endsWith('.ipynb')) {
                        content = await parseIpynb(file);
                    }
                    codeEditor.setValue(content); // Set content in editor
                } else {
                    displayFeedback('Unsupported file type. Please drop a .py or .ipynb file.', 'error');
                }
            }
        }

        // ============================================================
        // 2.5.2 – Line-by-Line Code Annotation
        // ============================================================
        async function annotateCode() {
            const code = codeEditor.getValue().trim();
            if (!code || code === '# Paste your Python code here...\ndef example_function():\n    return \'Hello, World!\'') {
                displayFeedback('Please paste or write some Python code first.', 'error');
                return;
            }
            const annotateBtn = document.getElementById('annotate-btn');
            const annotateBtn2 = document.getElementById('annotate-btn-2');
            const loadingHtml = '<i class="fas fa-spinner fa-spin mr-2"></i>Annotating…';
            if (annotateBtn) { annotateBtn.disabled = true; annotateBtn.innerHTML = loadingHtml; }
            if (annotateBtn2) { annotateBtn2.disabled = true; annotateBtn2.innerHTML = loadingHtml; }

            const prompt = `You are a Python programming tutor for Hong Kong DSE ICT students.
Annotate the following Python code line by line. For each non-empty line, provide a brief explanation in Traditional Chinese (繁體中文) that a beginner can understand.

Return ONLY valid JSON (no prose):
{
  "lines": [
    { "code": "def greet(name):", "comment": "定義一個名為 greet 的函式，接受 name 作為參數" },
    { "code": "    print(f'Hello, {name}')", "comment": "使用 f-string 格式輸出問候語" }
  ]
}

Code to annotate:
\`\`\`python
${code.substring(0, 2000)}
\`\`\``;

            try {
                const raw = await callGrokAPI(prompt);
                let parsed = null;
                try {
                    const m = raw.match(/\{[\s\S]*\}/);
                    if (m) parsed = JSON.parse(m[0]);
                } catch (_) {}

                if (!parsed?.lines?.length) {
                    displayFeedback('Could not generate annotation. Please try again.', 'error');
                    return;
                }

                const html = `<div class="markdown-content">
                    <h2 style="font-size:1rem;font-weight:700;margin-bottom:0.75rem;color:var(--text-primary)">📝 Line-by-Line Annotation</h2>
                    <div class="annotation-wrap">
                        ${parsed.lines.map(l => `<div class="annotation-line">
                            <div class="annotation-code">${escapeAnnotation(l.code || '')}</div>
                            <div class="annotation-comment">${escapeAnnotation(l.comment || '')}</div>
                        </div>`).join('')}
                    </div>
                </div>`;
                document.getElementById('feedback').innerHTML = html;
                document.getElementById('feedback').className = 'min-h-96 p-4 bg-green-50 rounded-lg border-2 border-green-200 markdown-content';
            } catch (err) {
                displayFeedback(`Error: ${err.message}`, 'error');
            } finally {
                const defaultAnnotateHtml = '<i class="fas fa-align-left mr-2"></i>📝 Annotate Code';
                if (annotateBtn) { annotateBtn.disabled = false; annotateBtn.innerHTML = defaultAnnotateHtml; }
                if (annotateBtn2) { annotateBtn2.disabled = false; annotateBtn2.innerHTML = '<i class="fas fa-align-left mr-2"></i>📝 Line-by-Line Annotation'; }
            }
        }

        function escapeAnnotation(str) {
            return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // Code Completion Exercise has been moved to code-completion.html
    </script>
    <!-- Code Review History Panel -->
    <div id="crHistoryBackdrop" onclick="closeCRHistory()" aria-hidden="true"></div>
    <div id="crHistoryPanel" role="dialog" aria-modal="true" aria-label="Code Review history">
        <div id="crHistoryPanelHeader">
            <div class="flex items-center gap-2">
                <div style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#7c3aed,#06b6d4);flex-shrink:0">
                    <i class="fas fa-code" style="color:#fff;font-size:.85rem"></i>
                </div>
                <span style="font-weight:700;font-size:.95rem;color:var(--text-primary)">Code Review History</span>
            </div>
            <button onclick="closeCRHistory()" aria-label="Close history"
                    style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.05);color:var(--text-secondary);font-size:.85rem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="crHistoryPanelBody">
            <div id="crhList"></div>
            <div id="crhLoadMoreWrap" style="display:none;text-align:center;padding:.75rem 0">
                <button id="crhLoadMoreBtn" onclick="loadMoreCRH()"
                        style="border:1px solid var(--glass-border);background:var(--glass-bg);color:var(--text-primary);padding:.4rem 1.25rem;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer">
                    <i class="fas fa-chevron-down mr-1"></i> Load More
                </button>
            </div>
        </div>
    </div>

    <!-- Firebase auth guard -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";
        const app = initializeApp(window.__FIREBASE_CONFIG__);
        const auth = getAuth(app);
        const overlay = document.getElementById('auth-guard-overlay');
        onAuthStateChanged(auth, async (user) => {
            if (user) {
                if (overlay) overlay.style.display = 'none';
                window._fbUser = user;
                // Restore from history if session ID is in URL
                const sessionId = new URLSearchParams(window.location.search).get('session');
                if (sessionId) await _loadCodingSession(user, sessionId);
            } else {
                window.location.replace('./index.php');
            }
        });

        async function _loadCodingSession(user, sessionId) {
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

                // Parse question and code from stored user message
                const uc = userMsg ? (userMsg.content || '') : '';
                const qMatch = uc.match(/^Question:\s*([\s\S]*?)(?:\n\nCode:|$)/);
                const cMatch = uc.match(/\n\nCode:\n([\s\S]*)/);
                const question = qMatch ? qMatch[1].trim() : '';
                const code     = cMatch ? cMatch[1].trim() : '';

                // Populate the question field
                const questionEl = document.getElementById('question');
                if (questionEl && question) questionEl.value = question;

                // Populate CodeMirror editor
                if (code && window.codeEditor) window.codeEditor.setValue(code);

                // Display the AI feedback
                if (window.displayFeedback) {
                    const banner = `> **📜 Restored from history** — ${(data.summary || '').replace(/</g,'&lt;')}\n\n`;
                    window.displayFeedback(banner + aiMsg.content, 'success');
                }

                // Scroll feedback into view
                const feedbackEl = document.getElementById('feedback');
                if (feedbackEl) feedbackEl.scrollIntoView({ behavior: 'smooth' });
            } catch (e) {
                console.warn('Failed to load coding history session:', e);
            }
        }

        window.saveHistoryRecord = async function(question, codeSnippet, feedback) {
            const user = window._fbUser;
            if (!user) return;
            try {
                const prompt = `Question: ${question}\n\nCode:\n${codeSnippet}`;
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({
                        tool: 'Code Review',
                        subject: 'ICT',
                        page: 'coding',
                        summary: question.substring(0, 100),
                        messages: [
                            { role: 'user', content: prompt, timestamp: new Date().toISOString() },
                            { role: 'assistant', content: feedback, timestamp: new Date().toISOString() }
                        ]
                    })
                });
                if (res.ok) {
                    const data = await res.json();
                    _generateAITitle(token, data.id, question, feedback);
                }
            } catch (e) {
                console.warn('History save failed:', e);
            }
        };

        async function _generateAITitle(token, sessionId, userMsg, aiMsg) {
            try {
                const titlePrompt = `Generate a concise chat title (5 words or fewer) that summarises this conversation. Return only the title text, no punctuation or quotes.\n\nUser: ${userMsg.substring(0, 200)}\nAI: ${aiMsg.substring(0, 200)}`;
                const res = await fetch('./api/ai_proxy.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({ subject: 'ICT', mode: 'ask', model: 'gemini-fast', stream: false, messages: [{ role: 'user', content: titlePrompt }], max_tokens: 20, temperature: 0.7 })
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

        // ── Code Review History Panel ──────────────────────────────────
        let _crh = { all:[], lastDoc:null, loading:false };
        const CRH_PAGE_SIZE = 15;

        window.openCRHistory = async function() {
            const backdrop = document.getElementById('crHistoryBackdrop');
            const panel = document.getElementById('crHistoryPanel');
            backdrop.style.display = 'block';
            requestAnimationFrame(() => {
                backdrop.classList.add('open');
                panel.classList.add('open');
            });
            _crh = { all:[], lastDoc:null, loading:false };
            const listEl = document.getElementById('crhList');
            listEl.innerHTML = `<div style="text-align:center;padding:2rem"><i class="fas fa-spinner fa-spin" style="color:var(--primary);font-size:1.5rem"></i></div>`;
            document.getElementById('crhLoadMoreWrap').style.display = 'none';
            try {
                const user = window._fbUser;
                if (!user) { listEl.innerHTML = '<p style="text-align:center;color:var(--text-secondary);font-size:.85rem;padding:2rem">Please sign in to view history.</p>'; return; }
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=15&tool=' + encodeURIComponent('Code Review'), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                _crh.all = data.docs || [];
                _crh.lastDoc = _crh.all.length > 0 ? _crh.all[_crh.all.length-1].updated_at : null;
                _crhRender(_crh.all);
                document.getElementById('crhLoadMoreWrap').style.display = (_crh.lastDoc && data.count === 15) ? 'block' : 'none';
            } catch(e) {
                console.error(e);
                listEl.innerHTML = '<p style="text-align:center;color:#ef4444;font-size:.85rem;padding:2rem">Failed to load history.</p>';
            }
        };

        window.closeCRHistory = function() {
            const backdrop = document.getElementById('crHistoryBackdrop');
            const panel = document.getElementById('crHistoryPanel');
            backdrop.classList.remove('open');
            panel.classList.remove('open');
            setTimeout(() => { backdrop.style.display = 'none'; }, 380);
        };

        window.loadMoreCRH = async function() {
            if (_crh.loading || !_crh.lastDoc) return;
            _crh.loading = true;
            const btn = document.getElementById('crhLoadMoreBtn');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…'; }
            try {
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=15&tool=' + encodeURIComponent('Code Review') + '&after=' + encodeURIComponent(_crh.lastDoc), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                const more = data.docs || [];
                _crh.lastDoc = more.length > 0 ? more[more.length-1].updated_at : null;
                _crh.all = [..._crh.all, ...more];
                _crhRender(_crh.all);
                document.getElementById('crhLoadMoreWrap').style.display = (_crh.lastDoc && data.count === 15) ? 'block' : 'none';
            } catch(e) { console.error(e); }
            finally {
                _crh.loading = false;
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Load More'; }
            }
        };

        window.deleteCRHSession = async function(id) {
            if (!confirm('Delete this history entry?')) return;
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                await fetch('./api/history.php?id=' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                _crh.all = _crh.all.filter(s => s.id !== id);
                _crhRender(_crh.all);
            } catch(e) { alert('Failed to delete. Please try again.'); }
        };

        function _crhEsc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function _crhFmtTime(ts) {
            if (!ts) return '';
            const d = new Date(ts);
            return d.toLocaleString('en-HK',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
        }
        window._crhToggle = function _crhToggle(id) {
            const body = document.getElementById('crh-body-'+id);
            const chev = document.getElementById('crh-chev-'+id);
            if (!body) return;
            const open = body.classList.toggle('open');
            if (chev) chev.style.transform = open ? 'rotate(180deg)' : '';
        }

        function _crhRenderCard(s) {
            const msgs = s.messages || [];
            const userMsg = msgs.find(m => m.role === 'user');
            const uc = userMsg ? userMsg.content || '' : '';
            const qMatch = uc.match(/^Question:\s*([\s\S]*?)(?:\n\nCode:|$)/);
            const question = qMatch ? qMatch[1].trim() : uc.substring(0,150);
            const time = _crhFmtTime(s.updated_at);
            const summary = s.summary || question.substring(0,70);
            return `<div class="crh-card" id="crh-card-${s.id}">
                <div class="crh-card-header" onclick="closeCRHistory();window.location.href='./coding.php?session=${s.id}'"
                     role="button" tabindex="0"
                     aria-label="Open Code Review session"
                     style="cursor:pointer"
                     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();closeCRHistory();window.location.href='./coding.php?session=${s.id}'}">
                    <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#7c3aed,#06b6d4);flex-shrink:0">
                        <i class="fas fa-code" style="color:#fff;font-size:1rem"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:700;font-size:.8rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_crhEsc(question.substring(0,60))}</p>
                        <p style="font-size:.72rem;color:var(--text-secondary);margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_crhEsc(summary.substring(0,70))}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                        <span style="font-size:.7rem;color:var(--text-secondary)">${_crhEsc(time)}</span>
                        <button onclick="event.stopPropagation();deleteCRHSession('${s.id}')"
                                style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:rgba(239,68,68,.12);color:#ef4444;font-size:.7rem;display:flex;align-items:center;justify-content:center"
                                title="Delete" aria-label="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <i class="fas fa-external-link-alt" title="Open in page" style="font-size:.75rem;color:var(--primary)"></i>
                    </div>
                </div>
            </div>`;
        }

        function _crhRender(sessions) {
            const list = document.getElementById('crhList');
            if (!sessions.length) {
                list.innerHTML = `<div style="text-align:center;padding:3rem 1rem">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(124,58,237,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                        <i class="fas fa-history" style="color:var(--primary);font-size:1.3rem"></i>
                    </div>
                    <p style="font-weight:700;font-size:.9rem;color:var(--text-primary);margin-bottom:.35rem">No history yet</p>
                    <p style="font-size:.78rem;color:var(--text-secondary)">Submit code for review to start building history.</p>
                </div>`;
                return;
            }
            list.innerHTML = sessions.map(s => _crhRenderCard(s)).join('');
        }
    </script>
<script>
    (function() {
        const params = new URLSearchParams(window.location.search);
        const back = params.get('back');
        const link = document.getElementById('back-btn-link');
        if (link) {
            link.href = './index.php' + (back ? '?openSubject=' + encodeURIComponent(back) : '');
        }
        // Pass ?back= through to the Code Completion platform link
        const ccLink = document.getElementById('open-completion-link');
        if (ccLink && back) {
            ccLink.href = './code-completion.php?back=' + encodeURIComponent(back);
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