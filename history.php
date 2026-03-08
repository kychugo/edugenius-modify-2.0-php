<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History – EduGenius AI</title>
    <link rel="manifest" href="./manifest.json">
    <link rel="icon" sizes="192x192" href="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #a78bfa;
            --secondary: #06b6d4;
            --text-primary: #1e1b4b;
            --text-secondary: #64748b;
            --bg-main: #f0f2ff;
            --glass-bg: rgba(255,255,255,0.80);
            --glass-border: rgba(255,255,255,0.65);
            --radius: 20px;
            --radius-sm: 12px;
        }
        .dark {
            --primary: #a78bfa;
            --primary-light: #c4b5fd;
            --secondary: #22d3ee;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --bg-main: #0d0d1a;
            --glass-bg: rgba(15,15,40,0.85);
            --glass-border: rgba(255,255,255,0.08);
        }
        * { font-family:'Plus Jakarta Sans','Inter','Segoe UI','Microsoft JhengHei',Arial,sans-serif; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            min-height:100vh;
            background-color:var(--bg-main);
            color:var(--text-primary);
            transition:background-color .4s ease;
            position:relative; overflow-x:hidden;
        }
        body::before {
            content:''; position:fixed; inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.18) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14) 0%,transparent 55%);
            pointer-events:none; z-index:0;
        }
        .glass-card {
            background:var(--glass-bg);
            backdrop-filter:blur(24px) saturate(160%);
            -webkit-backdrop-filter:blur(24px) saturate(160%);
            border:1px solid var(--glass-border);
            border-radius:var(--radius);
            box-shadow:0 8px 32px rgba(0,0,0,.08);
            position:relative;
        }
        .glass-card::before {
            content:''; position:absolute; top:0;left:0;right:0; height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);
            pointer-events:none;
        }
        .gradient-text {
            background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text;
        }
        .nav-bar {
            display:flex; align-items:center; justify-content:space-between;
            padding:.75rem 1rem; margin-bottom:1.5rem;
            background:var(--glass-bg);
            backdrop-filter:blur(20px);
            border:1px solid var(--glass-border);
            border-radius:var(--radius-sm);
            box-shadow:0 4px 20px rgba(0,0,0,.06);
        }
        .btn-primary {
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff; border:none; border-radius:var(--radius-sm);
            font-weight:700; cursor:pointer; transition:all .2s ease;
        }
        .btn-primary:hover { opacity:.9; transform:translateY(-1px); }
        .btn-secondary {
            background:var(--glass-bg);
            color:var(--text-primary);
            border:1px solid var(--glass-border);
            border-radius:var(--radius-sm);
            font-weight:600; cursor:pointer; transition:all .2s ease;
            text-decoration:none; display:inline-flex; align-items:center;
        }
        .btn-secondary:hover { border-color:var(--primary); color:var(--primary); }
        .btn-danger {
            background:rgba(239,68,68,.10); color:#ef4444;
            border:1px solid rgba(239,68,68,.25); border-radius:var(--radius-sm);
            font-weight:600; cursor:pointer; transition:all .2s ease;
        }
        .btn-danger:hover { background:rgba(239,68,68,.20); }

        /* Session card */
        .session-card {
            background:var(--glass-bg);
            border:1px solid var(--glass-border);
            border-radius:var(--radius-sm);
            overflow:hidden;
            transition:box-shadow .2s ease, border-color .2s ease;
        }
        .session-card:hover { border-color:rgba(124,58,237,.3); box-shadow:0 4px 20px rgba(124,58,237,.08); }
        .session-header {
            display:flex; align-items:center; gap:.75rem;
            padding:.875rem 1rem; cursor:pointer;
        }
        .session-icon {
            width:36px; height:36px; border-radius:10px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff; font-size:.8rem;
        }
        .session-body { display:none; padding:1rem; border-top:1px solid var(--glass-border); }
        .session-body.open { display:block; }
        .session-tag {
            display:inline-flex; align-items:center; gap:4px;
            padding:2px 10px; border-radius:99px; font-size:.7rem; font-weight:700;
        }

        /* Message bubbles */
        .msg-user {
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff; border-radius:14px 14px 4px 14px;
            padding:.6rem .9rem; font-size:.85rem; max-width:85%; margin-left:auto;
            word-break:break-word; white-space:pre-wrap;
        }
        .msg-ai {
            background:var(--glass-bg);
            border:1px solid var(--glass-border);
            color:var(--text-primary);
            border-radius:14px 14px 14px 4px;
            padding:.6rem .9rem; font-size:.85rem; max-width:92%;
            word-break:break-word; white-space:pre-wrap;
        }

        /* Date divider */
        .date-divider {
            display:flex; align-items:center; gap:.75rem;
            margin:1.5rem 0 .75rem;
        }
        .date-divider::before,.date-divider::after {
            content:''; flex:1; height:1px; background:var(--glass-border);
        }
        .date-divider span {
            font-size:.72rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.08em; color:var(--text-secondary);
        }

        /* Theme toggle icons */
        .theme-moon { display:inline-block; }
        .theme-sun  { display:none; }
        .dark .theme-moon { display:none; }
        .dark .theme-sun  { display:inline-block; }

        /* Loading spinner */
        .spinner {
            display:inline-block; width:20px; height:20px;
            border:2px solid var(--glass-border);
            border-top-color:var(--primary);
            border-radius:50%; animation:spin .7s linear infinite;
        }
        @keyframes spin { to { transform:rotate(360deg); } }

        /* Auth overlay */
        #auth-overlay {
            position:fixed; inset:0; z-index:1000;
            background:var(--bg-main);
            display:flex; align-items:center; justify-content:center;
        }

        /* Custom scrollbar */
        .custom-scroll::-webkit-scrollbar { width:4px; }
        .custom-scroll::-webkit-scrollbar-track { background:transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }

        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .animate-in { animation:fadeInUp .4s ease both; }
    </style>
    <?= firebaseConfigScript() ?>
</head>
<body>
    <!-- Auth overlay shown while Firebase checks auth state -->
    <div id="auth-overlay">
        <div class="text-center">
            <div class="spinner" style="width:36px;height:36px;border-width:3px;margin:0 auto 1rem"></div>
            <p style="color:var(--text-secondary);font-size:.875rem">Checking authentication…</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-3xl relative z-10">

        <!-- Nav bar -->
        <div class="nav-bar">
            <div class="flex items-center gap-3">
                <a href="./index.php" class="btn-secondary px-3 py-2 text-sm gap-2" aria-label="Back to EduGenius">
                    <i class="fas fa-arrow-left" style="color:var(--primary)"></i>
                    <span class="hidden sm:inline">Back</span>
                </a>
                <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius AI Logo"
                     style="width:30px;height:30px;border-radius:8px;object-fit:cover">
                <span class="font-bold text-sm gradient-text">History</span>
            </div>
            <div class="flex items-center gap-2">
                <!-- Filter -->
                <select id="filterTool" onchange="applyFilter()"
                        class="text-sm px-3 py-2 rounded-xl font-semibold outline-none"
                        style="background:var(--glass-bg);border:1px solid var(--glass-border);color:var(--text-primary)">
                    <option value="">All Tools</option>
                    <option value="Ask AI">Ask AI</option>
                    <option value="Dictionary AI">Dictionary AI</option>
                    <option value="Guide Learning">Guide Learning</option>
                    <option value="Code Review">Code Review</option>
                    <option value="Exam Paper Generator">Exam Paper</option>
                    <option value="English Writing">English Writing</option>
                    <option value="Vocabulary Generator">Vocabulary</option>
                    <option value="文言文翻譯">文言文翻譯</option>
                    <option value="文樞翻譯">文樞翻譯</option>
                </select>
                <button id="theme-toggle" onclick="toggleTheme()"
                        class="w-10 h-10 flex items-center justify-center rounded-xl btn-secondary" aria-label="Toggle dark mode">
                    <i class="fas fa-moon theme-moon" style="color:var(--primary)"></i>
                    <i class="fas fa-sun theme-sun" style="color:var(--primary)"></i>
                </button>
            </div>
        </div>

        <!-- Page heading -->
        <div class="text-center mb-8 animate-in">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
                 style="background:rgba(124,58,237,.10);color:var(--primary);border:1px solid rgba(124,58,237,.20)">
                <i class="fas fa-history"></i> AI Interaction History
            </div>
            <h1 class="text-3xl font-extrabold mb-2"><span class="gradient-text">Your Learning History</span></h1>
            <p class="text-sm" style="color:var(--text-secondary)">All your AI interactions, stored securely in the cloud</p>
        </div>

        <!-- History list -->
        <div id="historyList">
            <div class="text-center py-12">
                <div class="spinner" style="width:32px;height:32px;border-width:3px;margin:0 auto 1rem"></div>
                <p style="color:var(--text-secondary);font-size:.875rem">Loading history…</p>
            </div>
        </div>

        <!-- Load more -->
        <div id="loadMoreWrap" class="text-center mt-6 hidden">
            <button id="loadMoreBtn" onclick="loadMore()"
                    class="btn-primary px-6 py-2.5 text-sm rounded-xl">
                <i class="fas fa-chevron-down mr-2"></i>Load More
            </button>
        </div>

        <footer class="text-center text-xs pt-8 mt-4" style="border-top:1px solid var(--glass-border);color:var(--text-secondary)">
            <p class="font-semibold">EduGenius AI 智學</p>
            <p class="mt-1">Copyright &copy; 2026 Hugo Wong. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // ── Theme ────────────────────────────────────────────────────────
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        // ── Helpers ──────────────────────────────────────────────────────
        function escapeHtml(str) {
            return String(str)
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
        }

        function formatDate(ts) {
            if (!ts) return '';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleDateString('en-HK', { year:'numeric', month:'short', day:'numeric' });
        }
        function formatTime(ts) {
            if (!ts) return '';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleTimeString('en-HK', { hour:'2-digit', minute:'2-digit' });
        }

        function toolIcon(tool) {
            const map = {
                'Ask AI':'robot', 'Dictionary AI':'book', 'Guide Learning':'compass',
                'Code Review':'terminal', 'Exam Paper Generator':'file-alt',
                'English Writing':'pen-fancy', 'Vocabulary Generator':'lightbulb',
                '文言文翻譯':'scroll', '文樞翻譯':'scroll', 'Vocabulary':'lightbulb'
            };
            return map[tool] || 'robot';
        }

        function toolColor(tool) {
            const map = {
                'Ask AI':'#7c3aed', 'Dictionary AI':'#10b981', 'Guide Learning':'#8b5cf6',
                'Code Review':'#06b6d4', 'Exam Paper Generator':'#f59e0b',
                'English Writing':'#14b8a6', 'Vocabulary Generator':'#eab308',
                '文言文翻譯':'#ef4444', '文樞翻譯':'#ef4444', 'Vocabulary':'#eab308'
            };
            return map[tool] || '#7c3aed';
        }

        function subjectBadgeColor(subject) {
            const map = {
                'ICT':'rgba(99,102,241,.15)', 'Math':'rgba(16,185,129,.15)',
                'Physics':'rgba(245,158,11,.15)', 'Biology':'rgba(34,197,94,.15)',
                'English':'rgba(20,184,166,.15)', '中文':'rgba(239,68,68,.15)',
                'History':'rgba(124,58,237,.15)'
            };
            return map[subject] || 'rgba(124,58,237,.12)';
        }

        // ── State ────────────────────────────────────────────────────────
        let _allSessions = [];     // full list loaded from Firestore
        let _filtered    = [];     // after filter
        let _lastDoc     = null;
        let _loading     = false;
        let _userId      = null;
        let _filterTool  = '';

        // ── Render ───────────────────────────────────────────────────────
        function groupByDate(sessions) {
            const groups = {};
            sessions.forEach(s => {
                const d = formatDate(s.updated_at);
                if (!groups[d]) groups[d] = [];
                groups[d].push(s);
            });
            return groups;
        }

        function renderSessions(sessions) {
            const list = document.getElementById('historyList');
            if (!sessions.length) {
                list.innerHTML = `
                    <div class="glass-card p-10 text-center animate-in">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background:rgba(124,58,237,.10)">
                            <i class="fas fa-history text-2xl" style="color:var(--primary)"></i>
                        </div>
                        <h3 class="text-lg font-bold mb-2" style="color:var(--text-primary)">No history yet</h3>
                        <p class="text-sm" style="color:var(--text-secondary)">
                            Your AI interactions will appear here after you use any tool.
                        </p>
                        <a href="./index.php" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 text-sm rounded-xl mt-4">
                            <i class="fas fa-arrow-left"></i> Go to EduGenius
                        </a>
                    </div>`;
                return;
            }
            const groups = groupByDate(sessions);
            list.innerHTML = Object.keys(groups).map(date => `
                <div class="date-divider"><span>${escapeHtml(date)}</span></div>
                ${groups[date].map(s => renderSessionCard(s)).join('')}
            `).join('');
        }

        function renderSessionCard(s) {
            const color = toolColor(s.tool);
            const icon  = toolIcon(s.tool);
            const time  = formatTime(s.updated_at);
            const msgs  = (s.messages || []).slice(0, 40);
            const msgHtml = msgs.map(m => {
                const isUser = m.role === 'user';
                const content = escapeHtml((m.content || '').substring(0, 800));
                const ts = m.timestamp ? `<span class="text-xs opacity-50 ml-2">${new Date(m.timestamp).toLocaleTimeString('en-HK',{hour:'2-digit',minute:'2-digit'})}</span>` : '';
                return isUser
                    ? `<div class="flex justify-end mb-2"><div class="msg-user">${content}${ts}</div></div>`
                    : `<div class="flex justify-start mb-2"><div class="msg-ai">${content}${ts}</div></div>`;
            }).join('');

            return `
            <div class="session-card animate-in mb-3" id="card-${s.id}">
                <div class="session-header" onclick="toggleCard('${s.id}')">
                    <div class="session-icon" style="background:linear-gradient(135deg,${color},${color}99)">
                        <i class="fas fa-${icon}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-sm" style="color:var(--text-primary)">${escapeHtml(s.tool || '')}</span>
                            ${s.subject ? `<span class="session-tag text-xs font-bold" style="background:${subjectBadgeColor(s.subject)};color:var(--text-primary)">${escapeHtml(s.subject)}</span>` : ''}
                        </div>
                        <p class="text-xs truncate mt-0.5" style="color:var(--text-secondary)">${escapeHtml((s.summary || '').substring(0,80))}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs font-medium" style="color:var(--text-secondary)">${escapeHtml(time)}</span>
                        <button onclick="event.stopPropagation();deleteSession('${s.id}')"
                                class="btn-danger w-8 h-8 flex items-center justify-center rounded-lg text-xs" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <i class="fas fa-chevron-down text-xs transition-transform" style="color:var(--text-secondary)" id="chevron-${s.id}"></i>
                    </div>
                </div>
                <div class="session-body custom-scroll" id="body-${s.id}" style="max-height:500px;overflow-y:auto">
                    ${msgs.length ? msgHtml : '<p class="text-sm text-center py-4" style="color:var(--text-secondary)">No messages</p>'}
                    ${(s.messages || []).length > 40 ? `<p class="text-center text-xs py-2" style="color:var(--text-secondary)">… ${(s.messages.length - 40)} more messages not shown</p>` : ''}
                </div>
            </div>`;
        }

        function toggleCard(id) {
            const body    = document.getElementById('body-' + id);
            const chevron = document.getElementById('chevron-' + id);
            if (!body) return;
            const isOpen = body.classList.toggle('open');
            if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : '';
        }

        async function deleteSession(id) {
            if (!confirm('Delete this history entry?')) return;
            const ok = await window._deleteHistoryFn(_userId, id);
            if (ok) {
                _allSessions = _allSessions.filter(s => s.id !== id);
                _filtered    = _filtered.filter(s => s.id !== id);
                const card   = document.getElementById('card-' + id);
                if (card) card.remove();
                if (!_filtered.length) renderSessions([]);
            } else {
                alert('Failed to delete. Please try again.');
            }
        }

        function applyFilter() {
            _filterTool = document.getElementById('filterTool').value;
            _filtered = _filterTool
                ? _allSessions.filter(s => s.tool === _filterTool)
                : [..._allSessions];
            renderSessions(_filtered);
            document.getElementById('loadMoreWrap').classList.toggle('hidden', !_lastDoc || !!_filterTool);
        }

        async function loadMore() {
            if (_loading || !_lastDoc) return;
            _loading = true;
            const btn = document.getElementById('loadMoreBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px"></span> Loading…';
            try {
                const result = await window._loadHistoryFn(_userId, _lastDoc);
                const newSessions = result.docs;
                _lastDoc = result.lastDoc;
                _allSessions = [..._allSessions, ...newSessions];
                applyFilter();
                document.getElementById('loadMoreWrap').classList.toggle('hidden', !_lastDoc || !!_filterTool);
            } catch (e) {
                console.error(e);
            } finally {
                _loading = false;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Load More';
            }
        }

        async function initialLoad(userId) {
            _userId = userId;
            try {
                const result = await window._loadHistoryFn(userId, null);
                _allSessions = result.docs;
                _lastDoc     = result.lastDoc;
                applyFilter();
                if (_lastDoc && !_filterTool) {
                    document.getElementById('loadMoreWrap').classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
                document.getElementById('historyList').innerHTML = `
                    <div class="glass-card p-8 text-center">
                        <i class="fas fa-exclamation-circle text-3xl mb-3" style="color:#ef4444"></i>
                        <p class="text-sm" style="color:var(--text-secondary)">Failed to load history. Please try again.</p>
                    </div>`;
            }
        }
    </script>

    <!-- Firebase: auth guard + history loader -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

        const app = initializeApp(window.__FIREBASE_CONFIG__);
        const auth = getAuth(app);
        const overlay = document.getElementById('auth-overlay');

        // Expose PHP API helpers to the non-module script
        window._loadHistoryFn = async function(userId, afterCursor) {
            const token = await auth.currentUser?.getIdToken();
            if (!token) return { docs: [], lastDoc: null };
            let url = './api/history.php?limit=20';
            if (afterCursor) url += '&after=' + encodeURIComponent(afterCursor);
            const res = await fetch(url, { headers: { 'Authorization': 'Bearer ' + token } });
            if (!res.ok) return { docs: [], lastDoc: null };
            const data = await res.json();
            const docs = data.docs || [];
            const lastDoc = docs.length > 0 ? docs[docs.length-1].updated_at : null;
            return { docs, lastDoc };
        };

        window._deleteHistoryFn = async function(userId, sessionId) {
            try {
                const token = await auth.currentUser?.getIdToken();
                if (!token) return false;
                await fetch('./api/history.php?id=' + encodeURIComponent(sessionId), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token }
                });
                return true;
            } catch (e) {
                console.warn('Delete failed:', e);
                return false;
            }
        };

        onAuthStateChanged(auth, (user) => {
            if (user) {
                overlay.style.display = 'none';
                initialLoad(user.uid);
            } else {
                window.location.replace('./index.php');
            }
        });
    </script>
</body>
</html>
