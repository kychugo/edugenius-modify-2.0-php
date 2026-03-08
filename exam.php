<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HKDSE Exam Paper Generator - EduGenius</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="https://unpkg.com/docx@7.8.2/build/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7c3aed; --primary-dark: #5b21b6; --secondary: #06b6d4;
            --text-primary: #1e1b4b; --text-secondary: #64748b;
            --bg-main: #f0f2ff;
            --glass-bg: rgba(255,255,255,0.82); --glass-border: rgba(255,255,255,0.65);
            --shadow-glow: 0 0 40px rgba(124,58,237,0.10);
            --radius: 20px; --radius-sm: 12px;
        }
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Plus Jakarta Sans','Segoe UI',Arial,sans-serif; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        @keyframes spin { to{transform:rotate(360deg)} }

        body {
            min-height:100vh; background-color:var(--bg-main); padding:24px 16px 100px;
            line-height:1.6; overflow-x:hidden; color:var(--text-primary);
            position:relative;
        }
        body::before {
            content:''; position:fixed; inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 5%,  rgba(124,58,237,.18) 0%,transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(6,182,212,.14) 0%,transparent 55%);
            pointer-events:none; z-index:0;
        }

        .container {
            max-width:900px; margin:0 auto;
            position:relative; z-index:1;
            animation: fadeIn .6s cubic-bezier(.16,1,.3,1) both;
        }

        /* Back button */
        .back-btn-bar { margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .back-btn {
            display:inline-flex; align-items:center; gap:8px;
            padding:8px 16px; font-size:.85rem; font-weight:700;
            background:rgba(255,255,255,.82); backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.65); border-radius:12px;
            color:var(--primary); text-decoration:none;
            transition:all .25s ease; box-shadow:0 2px 8px rgba(0,0,0,.06);
        }
        .back-btn:hover { background:rgba(124,58,237,.08); border-color:rgba(124,58,237,.4); }

        /* Card / glass */
        .glass-card {
            background:var(--glass-bg); backdrop-filter:blur(24px) saturate(160%);
            -webkit-backdrop-filter:blur(24px) saturate(160%);
            border:1px solid var(--glass-border); border-radius:var(--radius);
            box-shadow:0 8px 32px rgba(0,0,0,.08),var(--shadow-glow);
            position:relative; overflow:hidden; margin-bottom:20px;
        }
        .glass-card::before {
            content:''; position:absolute; top:0;left:0;right:0; height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent); pointer-events:none;
        }

        /* Page header */
        .page-header { padding:32px; text-align:center; }
        .page-header .icon-wrap {
            width:68px; height:68px; border-radius:20px; margin:0 auto 16px;
            display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#7c3aed,#5b21b6);
            box-shadow:0 8px 24px rgba(124,58,237,.35);
        }
        .page-header h1 { font-size:2rem; font-weight:800; margin-bottom:8px; }
        .gradient-text { background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .page-header .subtitle { color:var(--text-secondary); font-size:.95rem; }

        /* Form groups */
        .form-group {
            padding:20px 24px; border-bottom:1px solid var(--glass-border);
            animation:fadeIn .5s cubic-bezier(.16,1,.3,1) both;
            animation-delay: calc(0.08s * var(--i));
        }
        .form-group:last-of-type { border-bottom:none; }

        label {
            display:block; font-weight:700; margin-bottom:10px;
            color:var(--text-primary); font-size:.875rem;
        }
        label i { color:var(--primary); margin-right:6px; }

        select, input[type="text"], input[type="number"] {
            width:100%; padding:10px 14px;
            border:1.5px solid var(--glass-border); border-radius:10px;
            font-size:.9rem; transition:all .25s;
            background:rgba(255,255,255,.9); color:var(--text-primary);
            font-family:inherit;
        }
        select:focus, input[type="text"]:focus, input[type="number"]:focus {
            border-color:var(--primary); box-shadow:0 0 0 3px rgba(124,58,237,.15); outline:none;
        }
        select[multiple] { height:140px; padding:8px; }

        .question-type-container { display:flex; gap:12px; margin-bottom:12px; }
        .question-type-group {
            flex:1; padding:14px; border-radius:12px;
            background:rgba(124,58,237,.06); border:1px solid rgba(124,58,237,.15);
        }
        .question-type-group label { color:var(--primary); font-size:.8rem; margin-bottom:8px; }
        .question-type-group input { width:70px; padding:8px 10px; text-align:center; }

        .topic-selection { display:flex; gap:12px; margin-bottom:12px; }
        .topic-selector { flex:1; }
        .custom-topic { flex:1; }

        .subtopics-container { margin-top:12px; display:none; }
        .subtopic-checkbox { margin:5px 0; display:flex; align-items:center; }
        .subtopic-checkbox input { margin-right:8px; }
        .subtopic-label { display:flex; align-items:center; cursor:pointer; font-size:.875rem; }

        /* Buttons */
        .button-group { display:flex; gap:12px; padding:20px 24px; }
        .button-group button {
            flex:1; padding:13px 20px;
            border:none; border-radius:12px; font-size:.9rem; font-weight:700;
            cursor:pointer; transition:all .3s; display:flex; align-items:center; justify-content:center; gap:8px;
            font-family:inherit;
        }
        #generate { background:linear-gradient(135deg,#7c3aed,#5b21b6); color:#fff; }
        #generate:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.42); }
        #export { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        #export:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(16,185,129,.35); }
        button:active { transform:translateY(0)!important; }
        button:disabled { background:#94a3b8!important; cursor:not-allowed; transform:none!important; box-shadow:none!important; opacity:.7; }

        /* Output */
        .output {
            margin:0 24px 24px; padding:20px; border-radius:14px;
            background:rgba(124,58,237,.03); border:1.5px dashed rgba(124,58,237,.20);
            max-height:600px; overflow-y:auto; min-height:80px;
        }
        .output h1 { font-size:1.6rem; margin:1em 0 .5em; color:var(--text-primary); border-bottom:2px solid rgba(124,58,237,.3); padding-bottom:5px; font-weight:800; }
        .output h2 { font-size:1.3rem; margin:.8em 0 .4em; color:var(--text-primary); font-weight:700; }
        .output h3 { font-size:1.1rem; margin:.6em 0 .3em; color:var(--text-primary); font-weight:700; }
        .output h4 { font-size:1rem; margin:.5em 0 .2em; font-weight:700; }
        .output strong { font-weight:700; }
        .output em { font-style:italic; color:var(--text-secondary); }
        .output hr { margin:1.5em 0; border:0; border-top:1px solid var(--glass-border); }
        .output p { margin-bottom:.9em; line-height:1.7; }
        .output ul,.output ol { margin:1em 0; padding-left:28px; }
        .output li { margin-bottom:.4em; }

        .error { color:#ef4444; font-weight:600; background:rgba(239,68,68,.08); padding:12px 16px; border-radius:10px; border-left:4px solid #ef4444; }
        .success { color:#10b981; font-weight:600; background:rgba(16,185,129,.08); padding:12px 16px; border-radius:10px; border-left:4px solid #10b981; }

        .loader { border:3px solid rgba(124,58,237,.15); border-top:3px solid var(--primary); border-radius:50%; width:32px; height:32px; animation:spin 1s linear infinite; margin:30px auto; }

        .math-display { background:rgba(124,58,237,.05); padding:10px; border-radius:8px; margin:10px 0; overflow-x:auto; }

        .badge { display:inline-block; padding:2px 8px; border-radius:99px; font-size:.75rem; font-weight:700; margin-right:4px; margin-bottom:4px; }
        .badge-easy { background:rgba(16,185,129,.12); color:#059669; }
        .badge-medium { background:rgba(245,158,11,.12); color:#d97706; }
        .badge-hard { background:rgba(239,68,68,.12); color:#dc2626; }
        .badge-mixed { background:rgba(59,130,246,.12); color:#2563eb; }

        footer {
            position:fixed; bottom:0; left:0; width:100%;
            background:rgba(15,15,40,.95); backdrop-filter:blur(16px);
            text-align:center; padding:12px; font-size:.8rem;
            color:rgba(255,255,255,.7); border-top:1px solid rgba(255,255,255,.08);
        }
        .copyright { font-size:.75em; opacity:.7; }

        @media (max-width:768px) {
            .question-type-container,.topic-selection,.button-group { flex-direction:column; }
            .page-header h1 { font-size:1.5rem; }
        }

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
.dark .back-btn { background: rgba(15,15,40,.8); color: var(--primary); }
.dark select, .dark input[type="text"], .dark input[type="number"] {
    background: rgba(15,15,40,.8); border-color: rgba(255,255,255,.15); color: #e2e8f0;
}
.dark .output { background: rgba(15,15,40,.5); border-color: rgba(124,58,237,.3); color: #e2e8f0; }
.dark .output h1, .dark .output h2, .dark .output h3, .dark .output h4 { color: #e2e8f0; }
.dark footer { background: rgba(5,5,20,.95); }
.dark label { color: #e2e8f0; }
.dark .question-type-group { background: rgba(124,58,237,.12); border-color: rgba(124,58,237,.25); }
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
/* Exam History Panel */
#examHistoryBackdrop {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:900;
    backdrop-filter:blur(2px); transition:opacity .3s;
}
#examHistoryBackdrop.open { opacity:1; }
#examHistoryPanel {
    position:fixed; top:0; right:0; bottom:0; z-index:901;
    width:min(500px,100vw); background:var(--bg-main);
    border-left:1px solid var(--glass-border);
    box-shadow:-8px 0 32px rgba(0,0,0,.18);
    display:flex; flex-direction:column;
    transform:translateX(100%);
    transition:transform .35s cubic-bezier(.16,1,.3,1);
    overflow:hidden;
}
#examHistoryPanel.open { transform:translateX(0); }
#examHistoryPanelHeader {
    display:flex; align-items:center; justify-content:space-between;
    padding:1rem 1.25rem; background:var(--glass-bg);
    border-bottom:1px solid var(--glass-border); flex-shrink:0;
}
#examHistoryPanelBody { flex:1; overflow-y:auto; padding:1rem; }
#examHistoryPanelBody::-webkit-scrollbar { width:4px; }
#examHistoryPanelBody::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
.exh-card {
    background:var(--glass-bg); border:1px solid var(--glass-border);
    border-radius:12px; margin-bottom:.75rem; overflow:hidden;
    animation:fadeIn .3s ease both;
}
.exh-card-header {
    display:flex; align-items:center; gap:.75rem;
    padding:.85rem 1rem; cursor:pointer; transition:background .2s;
}
.exh-card-header:hover { background:rgba(124,58,237,.06); }
.exh-card-body {
    max-height:0; overflow:hidden;
    transition:max-height .35s ease, padding .2s; padding:0 1rem;
}
.exh-card-body.open { max-height:600px; overflow-y:auto; padding:.75rem 1rem; }
.exh-card-body::-webkit-scrollbar { width:3px; }
.exh-card-body::-webkit-scrollbar-thumb { background:var(--primary); border-radius:99px; }
    </style>
    <?= firebaseConfigScript() ?>
</head>
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
    <!-- Back button -->
    <div class="back-btn-bar relative z-10" style="justify-content:space-between;">
        <a id="back-btn-link" href="./index.php" class="back-btn" aria-label="Back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div style="display:flex;align-items:center;gap:.5rem">
            <button onclick="openExamHistory()" title="View Exam History" aria-label="View exam history"
                    style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:.95rem;transition:all .2s">
                <i class="fas fa-history"></i>
            </button>
            <button id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode" style="width:36px;height:36px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.1);color:var(--primary);font-size:1rem;transition:all .2s">
                <span class="theme-moon">🌙</span><span class="theme-sun" style="display:none">☀️</span>
            </button>
        </div>
    </div>

    <div class="container">
        <!-- Page header card -->
        <div class="glass-card page-header">
            <div class="icon-wrap">
                <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius AI Logo" style="width:100%;height:100%;object-fit:cover;border-radius:20px">
            </div>
            <h1><span class="gradient-text">HKDSE Exam Paper Generator</span></h1>
            <p class="subtitle">Create customized exam papers for Hong Kong Diploma of Secondary Education</p>
        </div>

        <!-- Form card -->
        <div class="glass-card">
            <div class="form-group" style="--i: 1;">
                <label for="subject"><i class="fas fa-book"></i>Subject:</label>
                <select id="subject">
                    <option value="Math">Mathematics</option>
                    <option value="Physics">Physics</option>
                    <option value="Biology">Biology</option>
                </select>
            </div>

            <div class="form-group" style="--i: 2;">
                <label><i class="fas fa-question-circle"></i>Number of Questions:</label>
                <div class="question-type-container">
                    <div class="question-type-group">
                        <label for="mcCount">Multiple Choice:</label>
                        <input type="number" id="mcCount" min="0" max="15" value="5" placeholder="0-15">
                    </div>
                    <div class="question-type-group">
                        <label for="shortCount">Short Answer:</label>
                        <input type="number" id="shortCount" min="0" max="15" value="5" placeholder="0-15">
                    </div>
                    <div class="question-type-group">
                        <label for="longCount">Long Answer:</label>
                        <input type="number" id="longCount" min="0" max="15" value="3" placeholder="0-15">
                    </div>
                </div>
            </div>

            <div class="form-group" style="--i: 3;">
                <label for="topics"><i class="fas fa-tags"></i>Topics:</label>
                <div class="topic-selection">
                    <div class="topic-selector">
                        <select id="topics" multiple>
                            <!-- Options populated dynamically -->
                        </select>
                    </div>
                    <div class="custom-topic">
                        <input type="text" id="customTopics" placeholder="Enter custom topics separated by commas">
                    </div>
                </div>
                <div id="subtopicsContainer" class="subtopics-container">
                    <label><i class="fas fa-list-ul"></i>Sub-topics:</label>
                    <div id="subtopicsList"></div>
                </div>
            </div>

            <div class="form-group" style="--i: 4;">
                <label for="difficulty"><i class="fas fa-chart-line"></i>Difficulty Level:</label>
                <select id="difficulty">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                    <option value="mixed">Mixed</option>
                </select>
            </div>

            <div class="button-group">
                <button id="generate"><i class="fas fa-magic"></i> Generate Exam Paper</button>
                <button id="export" disabled><i class="fas fa-file-word"></i> Export to Word</button>
            </div>

            <div class="output" id="output"></div>
        </div>
    </div>

    <footer>
        <div>HKDSE Exam Paper Generator — EduGenius AI</div>
        <div class="copyright">Copyright &copy; 2026 Hugo Wong. All rights reserved.</div>
    </footer>

    <script>
        const mathTopics = [
            "Number systems",
            "Quadratic Equations in One Unknown",
            "Functions and Graphs",
            "More about Graphs of Function",
            "Equations of Straight Line",
            "More about Polynomials",
            "Variations",
            "Exponential and Logarithmic Functions",
            "More about Equations",
            "Inequalities and Linear Programming",
            "Arithmetic & Geometric Sequences & Series",
            "Revision on Area and Volume",
            "More about Trigonometry",
            "Applications of Trigonometry in 2-dimensional Problems",
            "Applications of Trigonometry in 3-dimensional Problems",
            "Basic Properties of Circles",
            "Tangents to Circles",
            "Coordinate geometry in circles",
            "Locus",
            "Permutations and Combinations",
            "More about Probability",
            "Measure of Dispersion",
            "Uses and Abuses of Statistics",
            "Advanced Arithmetic & Geometric Sequences & Series",
            "Advanced 3D Trigonometry",
            "Advanced Geometry of Circles",
            "Advanced Permutations and Combinations+Probability",
            "Advanced Statistics"
        ];
        
        const physicsTopics = {
            "Heat and Gases": [
                "Temperature, heat and internal energy",
                "Transfer processes",
                "Change of state",
                "Gases"
            ],
            "Force and Motion": [
                "Position and movement",
                "Force and motion",
                "Projectile motion",
                "Work, energy and power",
                "Momentum",
                "Uniform circular motion",
                "Gravitation"
            ],
            "Wave Motion": [
                "Nature and properties of waves",
                "Light",
                "Sound"
            ],
            "Electricity and Magnetism": [
                "Electrostatics",
                "Circuits and domestic electricity",
                "Electromagnetism"
            ],
            "Radioactivity and Nuclear Energy": [
                "Radiation and radioactivity",
                "Atomic model",
                "Nuclear energy"
            ]
        };

        const biologyTopics = {
            "Book 1A": [
                "Introducing biology",
                "Movement of substances across cell membrane",
                "Enzymes and metabolism",
                "Food and humans",
                "Nutrition in humans"
            ],
            "Book 1B": [
                "Gas exchange in humans",
                "Transport in humans",
                "Nutrition and gas exchange in plants",
                "Transpiration, transport and support in plants"
            ],
            "Book 2": [
                "Cell cycle and division",
                "Reproduction in flowering plants",
                "Reproduction in humans",
                "Growth and development",
                "Detecting the environment",
                "Coordination in humans",
                "Movement in humans",
                "Homeostasis"
            ],
            "Book 3": [
                "Ecosystems",
                "Photosynthesis",
                "Respiration",
                "Non-infectious diseases",
                "Infectious diseases and disease prevention",
                "Body defence mechanisms"
            ],
            "Book 4": [
                "Basic genetics",
                "Molecular genetics",
                "Biotechnology",
                "Biodiversity",
                "Evolution I",
                "Evolution II"
            ]
        };

        const subjectSelect = document.getElementById('subject');
        const topicsSelect = document.getElementById('topics');
        const customTopicsInput = document.getElementById('customTopics');
        const generateButton = document.getElementById('generate');
        const exportButton = document.getElementById('export');
        const outputDiv = document.getElementById('output');
        const mcCountInput = document.getElementById('mcCount');
        const shortCountInput = document.getElementById('shortCount');
        const longCountInput = document.getElementById('longCount');
        const subtopicsContainer = document.getElementById('subtopicsContainer');
        const subtopicsList = document.getElementById('subtopicsList');

        // Populate topics based on selected subject
        function populateTopics() {
            topicsSelect.innerHTML = '';
            const subject = subjectSelect.value;
            
            if (subject === 'Math') {
                subtopicsContainer.style.display = 'none';
                mathTopics.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicsSelect.appendChild(option);
                });
            } else if (subject === 'Physics') {
                subtopicsContainer.style.display = 'block';
                Object.keys(physicsTopics).forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicsSelect.appendChild(option);
                });
            } else if (subject === 'Biology') {
                subtopicsContainer.style.display = 'block';
                Object.keys(biologyTopics).forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic;
                    option.textContent = topic;
                    topicsSelect.appendChild(option);
                });
            }
        }

        // Populate subtopics when a physics or biology topic is selected
        function populateSubtopics() {
            subtopicsList.innerHTML = '';
            const selectedOptions = Array.from(topicsSelect.selectedOptions);
            const subject = subjectSelect.value;
            const topics = subject === 'Physics' ? physicsTopics : biologyTopics;
            
            selectedOptions.forEach(option => {
                const topic = option.value;
                if (topics[topic]) {
                    const topicDiv = document.createElement('div');
                    topicDiv.innerHTML = `<strong>${topic}</strong>`;
                    subtopicsList.appendChild(topicDiv);
                    
                    topics[topic].forEach(subtopic => {
                        const checkboxDiv = document.createElement('div');
                        checkboxDiv.className = 'subtopic-checkbox';
                        
                        const checkboxId = `subtopic-${topic}-${subtopic}`.replace(/\s+/g, '-').toLowerCase();
                        
                        checkboxDiv.innerHTML = `
                            <label class="subtopic-label">
                                <input type="checkbox" id="${checkboxId}" value="${subtopic}" checked>
                                ${subtopic}
                            </label>
                        `;
                        subtopicsList.appendChild(checkboxDiv);
                    });
                }
            });
        }

        subjectSelect.addEventListener('change', populateTopics);
        topicsSelect.addEventListener('change', function() {
            const subject = subjectSelect.value;
            if (subject === 'Physics' || subject === 'Biology') {
                populateSubtopics();
            } else {
                subtopicsContainer.style.display = 'none';
            }
        });
        populateTopics();

        // Format AI response with HTML tags (Corrected version)
        function formatResponse(content) {
            let processedContent = content
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/`(.+?)`/g, '<code>$1</code>')
                .replace(/\\\((.*?)\\\)/g, '<span class="math-display">\\($1\\)</span>')
                .replace(/\\\[(.*?)\\\]/g, '<div class="math-display">\\[$1\\]</div>');

            const blocks = processedContent.split(/\n\s*\n/);

            const htmlBlocks = blocks.map(block => {
                const trimmedBlock = block.trim();
                if (trimmedBlock.startsWith('#')) {
                    if (trimmedBlock.startsWith('####')) return `<h4>${trimmedBlock.substring(4).trim()}</h4>`;
                    if (trimmedBlock.startsWith('###')) return `<h3>${trimmedBlock.substring(3).trim()}</h3>`;
                    if (trimmedBlock.startsWith('##')) return `<h2>${trimmedBlock.substring(2).trim()}</h2>`;
                    if (trimmedBlock.startsWith('#')) return `<h1>${trimmedBlock.substring(1).trim()}</h1>`;
                }
                if (trimmedBlock === '---') {
                    return '<hr>';
                }
                if (trimmedBlock.startsWith('- ')) {
                    const listItems = trimmedBlock.split('\n').map(item => `<li>${item.replace(/^- /, '').trim()}</li>`).join('');
                    return `<ul>${listItems}</ul>`;
                }
                if (trimmedBlock) {
                    return `<p>${trimmedBlock.replace(/\n/g, '<br>')}</p>`;
                }
                return '';
            });
            return `<div>${htmlBlocks.join('')}</div>`;
        }


        // Get selected subtopics for physics or biology
        function getSelectedSubtopics() {
            const selectedSubtopics = [];
            const checkboxes = subtopicsList.querySelectorAll('input[type="checkbox"]:checked');
            
            checkboxes.forEach(checkbox => {
                selectedSubtopics.push(checkbox.value);
            });
            
            return selectedSubtopics;
        }

        // Generate exam paper
        generateButton.addEventListener('click', async () => {
            const subject = subjectSelect.value;
            const mcCount = parseInt(mcCountInput.value) || 0;
            const shortCount = parseInt(shortCountInput.value) || 0;
            const longCount = parseInt(longCountInput.value) || 0;
            const selectedTopics = Array.from(topicsSelect.selectedOptions).map(option => option.value);
            const customTopics = customTopicsInput.value.split(',').map(topic => topic.trim()).filter(topic => topic);
            const topics = [...selectedTopics, ...customTopics];
            const difficulty = document.getElementById('difficulty').value;
            
            const subtopics = (subject === 'Physics' || subject === 'Biology') ? getSelectedSubtopics() : [];

            if (topics.length === 0) {
                outputDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> Please select or enter at least one topic.</div>';
                exportButton.disabled = true;
                return;
            }

            if (mcCount + shortCount + longCount === 0) {
                outputDiv.innerHTML = '<div class="error"><i class="fas fa-exclamation-circle"></i> Please specify at least one question to generate.</div>';
                exportButton.disabled = true;
                return;
            }

            const questionTypes = [];
            if (mcCount > 0) questionTypes.push(`${mcCount} multiple choice`);
            if (shortCount > 0) questionTypes.push(`${shortCount} short answer`);
            if (longCount > 0) questionTypes.push(`${longCount} long answer`);

            let prompt = `Generate an HKDSE ${subject} exam paper with the following specifications:
- Question types: ${questionTypes.join(', ')}
- Main topics: ${topics.join(', ')}`;
            
            if (subtopics.length > 0) {
                prompt += `\n- Sub-topics: ${subtopics.join(', ')}`;
            }
            
            prompt += `\n- Difficulty: ${difficulty}

Requirements:
1. Include questions, answers, and explanatory notes in English only
2. Highlight key concepts using **bold** and *italics*
3. Use bullet points (- ) for lists where appropriate
4. Use MathJax for mathematical expressions (e.g., \\(x^2\\) for superscripts)
5. Ensure all questions are original and suitable for HKDSE students
6. Do not repeat similar questions
7. Organize content with clear headings (use # for main title, ## for sections, ### for subsections)
8. Include a title "HKDSE ${subject} Exam Paper" at the top
9. Separate different sections with horizontal rules (---)
10. Format mathematical expressions with \\(...\\) for inline and \\[...\\] for display math
11. Clearly separate each question with a horizontal rule (---)`;

            outputDiv.innerHTML = '<div class="loader"></div>';
            generateButton.disabled = true;
            exportButton.disabled = true;

            try {
                let content = null;
                const token = window._fbUser ? await window._fbUser.getIdToken() : null;
                const response = await fetch('./api/ai_proxy.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({ subject: subject, mode: 'exam', messages: [{role:'user',content:prompt}], stream: false, temperature: 0.7, max_tokens: 3000 })
                });
                if (!response.ok) throw new Error('Request failed: ' + response.status);
                const data = await response.json();
                content = data.choices?.[0]?.message?.content;
                if (!content) throw new Error('All models failed to generate a response');
                outputDiv.innerHTML = formatResponse(content);
                MathJax.typesetPromise();
                exportButton.disabled = false;
                // Save to Firestore history (non-blocking)
                if (window.saveHistoryRecord) {
                    window.saveHistoryRecord(subject, topics, difficulty, content);
                }
                outputDiv.scrollIntoView({ behavior: 'smooth' });
            } catch (error) {
                console.error('Error:', error);
                outputDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> An error occurred while generating the exam paper: ${error.message}. Please try again.</div>`;
                exportButton.disabled = true;
            } finally {
                generateButton.disabled = false;
            }
        });
        
        // [--- START OF FULLY DEBUGGED EXPORT FUNCTION ---]
        // Export to Word with full content parsing
        exportButton.addEventListener('click', () => {
            try {
                const { docx } = window;
                if (!docx) {
                    throw new Error("docx library not loaded properly");
                }
                
                const subject = subjectSelect.value;
                const content = outputDiv.innerHTML;
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = content;
                
                // The 'children' array will be populated by parsing the HTML content.
                // No manual title or copyright will be added.
                const children = [];
                
                let currentList = null;
                
                const processNode = (node, indentLevel = 0) => {
                    if (node.nodeType !== Node.ELEMENT_NODE) {
                        return;
                    }
                    
                    const tagName = node.tagName.toLowerCase();
                    
                    if (tagName === 'h1') {
                        children.push(new docx.Paragraph({ text: node.textContent, heading: docx.HeadingLevel.HEADING_1, spacing: { before: 400, after: 200 } }));
                    } else if (tagName === 'h2') {
                        children.push(new docx.Paragraph({ text: node.textContent, heading: docx.HeadingLevel.HEADING_2, spacing: { before: 300, after: 150 } }));
                    } else if (tagName === 'h3') {
                        children.push(new docx.Paragraph({ text: node.textContent, heading: docx.HeadingLevel.HEADING_3, spacing: { before: 200, after: 100 } }));
                    } else if (tagName === 'h4') {
                        children.push(new docx.Paragraph({ text: node.textContent, heading: docx.HeadingLevel.HEADING_4, spacing: { before: 150, after: 75 } }));
                    } else if (tagName === 'hr') {
                        // Just add a visual line separator
                        children.push(new docx.Paragraph({ text: "", border: { bottom: { size: 6, color: "auto", style: "single" } }, spacing: { before: 200, after: 200 } }));
                        if (currentList) currentList = null;
                    } else if (tagName === 'p') {
                        const paragraphChildren = [];
                        
                        const processChildNode = (childNode) => {
                            if (childNode.nodeType === Node.TEXT_NODE) {
                                paragraphChildren.push(new docx.TextRun({ text: childNode.textContent }));
                            } else if (childNode.nodeType === Node.ELEMENT_NODE) {
                                let textContent = childNode.textContent;
                                let isBold = false;
                                let isItalic = false;

                                if (childNode.tagName.toLowerCase() === 'strong') isBold = true;
                                if (childNode.tagName.toLowerCase() === 'em') isItalic = true;
                                
                                // Handle nested tags if necessary, but this simple way covers most cases
                                if (childNode.childNodes.length === 1 && childNode.childNodes[0].nodeType === Node.TEXT_NODE) {
                                     paragraphChildren.push(new docx.TextRun({ text: textContent, bold: isBold, italics: isItalic }));
                                } else { // Fallback for more complex nested nodes
                                     paragraphChildren.push(new docx.TextRun({ text: textContent }));
                                }
                            }
                        };
                        
                        node.childNodes.forEach(processChildNode);
                        
                        children.push(new docx.Paragraph({ children: paragraphChildren, spacing: { after: 100 } }));
                        if (currentList) currentList = null;
                    } else if (tagName === 'ul' || tagName === 'ol') {
                        Array.from(node.children).forEach(li => {
                            if (li.tagName.toLowerCase() === 'li') {
                                children.push(new docx.Paragraph({
                                    text: li.textContent,
                                    bullet: { level: indentLevel },
                                    spacing: { after: 50 }
                                }));
                            }
                        });
                    } else if (tagName === 'div' && node.classList.contains('math-display')) {
                        children.push(new docx.Paragraph({ text: node.textContent, spacing: { before: 100, after: 100 }, alignment: docx.AlignmentType.CENTER }));
                        if (currentList) currentList = null;
                    } else if (tagName === 'div') {
                        // Recursively process children of a generic div
                        Array.from(node.childNodes).forEach(child => processNode(child, indentLevel));
                    }
                };
                
                Array.from(tempDiv.childNodes).forEach(node => processNode(node));
                
                const doc = new docx.Document({
                    sections: [{
                        properties: {
                            page: {
                                margin: {
                                    top: 1000,
                                    right: 1000,
                                    bottom: 1000,
                                    left: 1000,
                                }
                            }
                        },
                        children: children
                    }]
                });

                docx.Packer.toBlob(doc).then(blob => {
                    saveAs(blob, `HKDSE_${subject}_Exam_Paper.docx`);
                }).catch(error => {
                    console.error('Error exporting to Word:', error);
                    outputDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> Failed to export to Word: ${error.message}</div>`;
                });
                
            } catch (error) {
                console.error('Error in export:', error);
                outputDiv.innerHTML = `<div class="error"><i class="fas fa-exclamation-circle"></i> Export failed: ${error.message}</div>`;
            }
        });
        // [--- END OF FULLY DEBUGGED EXPORT FUNCTION ---]

        // Input validation for question counts
        [mcCountInput, shortCountInput, longCountInput].forEach(input => {
            input.addEventListener('change', () => {
                let value = parseInt(input.value);
                if (isNaN(value)) value = 0;
                if (value < 0) value = 0;
                if (value > 15) value = 15;
                input.value = value;
            });
        });
    </script>
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
                if (sessionId) await _loadExamSession(user, sessionId);
            } else {
                window.location.replace('./index.php');
            }
        });

        async function _loadExamSession(user, sessionId) {
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

                // Restore form fields from stored user message
                const uc = userMsg ? (userMsg.content || '') : '';
                const subMatch  = uc.match(/Subject:\s*(.+)/);
                const diffMatch = uc.match(/Difficulty:\s*(.+)/);
                if (subMatch) {
                    const sel = document.getElementById('subject');
                    if (sel) { sel.value = subMatch[1].trim(); if (window.populateTopics) window.populateTopics(); }
                }
                if (diffMatch) {
                    const sel = document.getElementById('difficulty');
                    if (sel) sel.value = diffMatch[1].trim().toLowerCase();
                }

                // Render the generated paper
                const outputDiv = document.getElementById('output');
                if (!outputDiv) return;
                const historyBanner = `<div style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.25);border-radius:12px;padding:.6rem 1rem;margin-bottom:1.25rem;font-size:.8rem;color:#5b21b6;display:flex;align-items:center;gap:.5rem">
                    <i class="fas fa-history"></i> <span>Restored from history &mdash; ${(data.summary || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>
                </div>`;
                if (window.formatResponse) {
                    outputDiv.innerHTML = historyBanner + window.formatResponse(aiMsg.content);
                } else {
                    outputDiv.innerHTML = historyBanner + '<pre style="white-space:pre-wrap;font-size:.85rem">' + aiMsg.content.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</pre>';
                }
                const exportBtn = document.getElementById('export');
                if (exportBtn) exportBtn.disabled = false;
                if (window.MathJax) MathJax.typesetPromise([outputDiv]).catch(() => {});
                outputDiv.scrollIntoView({ behavior: 'smooth' });
            } catch (e) {
                console.warn('Failed to load exam history session:', e);
            }
        }

        window.saveHistoryRecord = async function(subject, topics, difficulty, content) {
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                await fetch('./api/history.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token },
                    body: JSON.stringify({
                        tool: 'Exam Paper Generator',
                        subject: subject,
                        page: 'exam',
                        summary: (subject + ' – ' + topics.join(', ')).substring(0, 100),
                        messages: [
                            { role: 'user', content: 'Subject: ' + subject + '\nTopics: ' + topics.join(', ') + '\nDifficulty: ' + difficulty, timestamp: new Date().toISOString() },
                            { role: 'assistant', content: content, timestamp: new Date().toISOString() }
                        ]
                    })
                });
            } catch (e) {
                console.warn('History save failed:', e);
            }
        };

        // ── Exam History Panel ──────────────────────────────────────
        let _exh = { all:[], lastDoc:null, loading:false };
        const EXH_PAGE_SIZE = 15;

        window.openExamHistory = async function() {
            const backdrop = document.getElementById('examHistoryBackdrop');
            const panel = document.getElementById('examHistoryPanel');
            backdrop.style.display = 'block';
            requestAnimationFrame(() => {
                backdrop.classList.add('open');
                panel.classList.add('open');
            });
            _exh = { all:[], lastDoc:null, loading:false };
            const listEl = document.getElementById('exhList');
            listEl.innerHTML = `<div style="text-align:center;padding:2rem"><i class="fas fa-spinner fa-spin" style="color:var(--primary);font-size:1.5rem"></i></div>`;
            document.getElementById('exhLoadMoreWrap').style.display = 'none';
            try {
                const user = window._fbUser;
                if (!user) { listEl.innerHTML = '<p style="text-align:center;color:var(--text-secondary);font-size:.85rem;padding:2rem">Please sign in to view history.</p>'; return; }
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=15&tool=' + encodeURIComponent('Exam Paper Generator'), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                _exh.all = data.docs || [];
                _exh.lastDoc = _exh.all.length > 0 ? _exh.all[_exh.all.length-1].updated_at : null;
                _exhRender(_exh.all);
                document.getElementById('exhLoadMoreWrap').style.display = (_exh.lastDoc && data.count === 15) ? 'block' : 'none';
            } catch(e) {
                console.error(e);
                listEl.innerHTML = '<p style="text-align:center;color:#ef4444;font-size:.85rem;padding:2rem">Failed to load history.</p>';
            }
        };

        window.closeExamHistory = function() {
            const backdrop = document.getElementById('examHistoryBackdrop');
            const panel = document.getElementById('examHistoryPanel');
            backdrop.classList.remove('open');
            panel.classList.remove('open');
            setTimeout(() => { backdrop.style.display = 'none'; }, 380);
        };

        window.loadMoreEXH = async function() {
            if (_exh.loading || !_exh.lastDoc) return;
            _exh.loading = true;
            const btn = document.getElementById('exhLoadMoreBtn');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Loading…'; }
            try {
                const user = window._fbUser;
                if (!user) return;
                const token = await user.getIdToken();
                const res = await fetch('./api/history.php?limit=15&tool=' + encodeURIComponent('Exam Paper Generator') + '&after=' + encodeURIComponent(_exh.lastDoc), {
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                const more = data.docs || [];
                _exh.lastDoc = more.length > 0 ? more[more.length-1].updated_at : null;
                _exh.all = [..._exh.all, ...more];
                _exhRender(_exh.all);
                document.getElementById('exhLoadMoreWrap').style.display = (_exh.lastDoc && data.count === 15) ? 'block' : 'none';
            } catch(e) { console.error(e); }
            finally {
                _exh.loading = false;
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i>Load More'; }
            }
        };

        window.deleteEXHSession = async function(id) {
            if (!confirm('Delete this history entry?')) return;
            const user = window._fbUser;
            if (!user) return;
            try {
                const token = await user.getIdToken();
                await fetch('./api/history.php?id=' + encodeURIComponent(id), {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + token, 'X-Firebase-Token': token }
                });
                _exh.all = _exh.all.filter(s => s.id !== id);
                _exhRender(_exh.all);
            } catch(e) { alert('Failed to delete. Please try again.'); }
        };

        function _exhEsc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
        function _exhFmtTime(ts) {
            if (!ts) return '';
            const d = new Date(ts);
            return d.toLocaleString('en-HK',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
        }
        window._exhToggle = function(id) {
            const body = document.getElementById('exh-body-'+id);
            const chev = document.getElementById('exh-chev-'+id);
            if (!body) return;
            const open = body.classList.toggle('open');
            if (chev) chev.style.transform = open ? 'rotate(180deg)' : '';
        };

        function _exhRenderCard(s) {
            const msgs = s.messages || [];
            const userMsg = msgs.find(m => m.role === 'user');
            const aiMsg   = msgs.find(m => m.role === 'assistant');
            const uc = userMsg ? userMsg.content || '' : '';
            const subMatch  = uc.match(/Subject:\s*(.+)/);
            const topicsMatch = uc.match(/Topics:\s*(.+)/);
            const diffMatch = uc.match(/Difficulty:\s*(.+)/);
            const subject   = subMatch   ? subMatch[1].trim()   : (s.subject || '');
            const topics    = topicsMatch ? topicsMatch[1].trim() : '';
            const difficulty = diffMatch ? diffMatch[1].trim()  : '';
            const preview   = aiMsg ? (aiMsg.content||'').substring(0,500) : '';
            const time = _exhFmtTime(s.updated_at);
            return `<div class="exh-card" id="exh-card-${s.id}">
                <div class="exh-card-header" onclick="_exhToggle('${s.id}')" role="button" tabindex="0"
                     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();_exhToggle('${s.id}')}">
                    <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;background:linear-gradient(135deg,#7c3aed,#06b6d4);flex-shrink:0">
                        <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius" style="width:100%;height:100%;object-fit:cover">
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-weight:700;font-size:.8rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${_exhEsc((s.summary||subject).substring(0,60))}</p>
                        <p style="font-size:.7rem;color:var(--text-secondary);margin-top:.1rem">${_exhEsc(time)}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0">
                        <button onclick="event.stopPropagation();deleteEXHSession('${s.id}')"
                                style="width:28px;height:28px;border-radius:7px;border:none;cursor:pointer;background:rgba(239,68,68,.12);color:#ef4444;font-size:.7rem;display:flex;align-items:center;justify-content:center"
                                title="Delete" aria-label="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <i class="fas fa-chevron-down" id="exh-chev-${s.id}" style="font-size:.65rem;color:var(--text-secondary);transition:transform .3s"></i>
                    </div>
                </div>
                <div class="exh-card-body" id="exh-body-${s.id}">
                    ${subject ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Subject</span><p style="font-size:.8rem;color:var(--text-primary);margin-top:.1rem">${_exhEsc(subject)}</p></div>` : ''}
                    ${topics ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Topics</span><p style="font-size:.8rem;color:var(--text-primary);margin-top:.1rem">${_exhEsc(topics)}</p></div>` : ''}
                    ${difficulty ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Difficulty</span><p style="font-size:.8rem;color:var(--text-primary);margin-top:.1rem">${_exhEsc(difficulty)}</p></div>` : ''}
                    ${preview ? `<div style="margin-bottom:.5rem"><span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-secondary)">Paper Preview</span><p style="font-size:.78rem;color:var(--text-primary);margin-top:.1rem;white-space:pre-wrap;max-height:200px;overflow-y:auto">${_exhEsc(preview)}</p></div>` : ''}
                    <div style="padding-bottom:.5rem">
                        <a href="./exam.php?session=${s.id}" title="Restore this session"
                           style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#06b6d4);overflow:hidden;border:none;text-decoration:none">
                            <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="Restore session" style="width:100%;height:100%;object-fit:cover">
                        </a>
                    </div>
                </div>
            </div>`;
        }

        function _exhRender(sessions) {
            const list = document.getElementById('exhList');
            if (!sessions.length) {
                list.innerHTML = `<div style="text-align:center;padding:3rem 1rem">
                    <div style="width:48px;height:48px;border-radius:14px;background:rgba(124,58,237,.10);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
                        <i class="fas fa-history" style="color:var(--primary);font-size:1.3rem"></i>
                    </div>
                    <p style="font-weight:700;font-size:.9rem;color:var(--text-primary);margin-bottom:.35rem">No history yet</p>
                    <p style="font-size:.78rem;color:var(--text-secondary)">Generate an exam paper to start building history.</p>
                </div>`;
                return;
            }
            list.innerHTML = sessions.map(s => _exhRenderCard(s)).join('');
        }
    </script>
    <!-- Exam History Panel -->
    <div id="examHistoryBackdrop" onclick="closeExamHistory()" aria-hidden="true"></div>
    <div id="examHistoryPanel" role="dialog" aria-modal="true" aria-label="Exam history">
        <div id="examHistoryPanelHeader">
            <div style="display:flex;align-items:center;gap:.5rem">
                <div style="width:28px;height:28px;border-radius:8px;overflow:hidden;background:linear-gradient(135deg,#7c3aed,#06b6d4);flex-shrink:0">
                    <img src="https://i.ibb.co/gMQh9L2S/Edu-Genius-AI.png" alt="EduGenius" style="width:100%;height:100%;object-fit:cover">
                </div>
                <span style="font-weight:700;font-size:.95rem;color:var(--text-primary)">Exam History</span>
            </div>
            <button onclick="closeExamHistory()" aria-label="Close history"
                    style="width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.05);color:var(--text-secondary);font-size:.85rem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="examHistoryPanelBody">
            <div id="exhList"></div>
            <div id="exhLoadMoreWrap" style="display:none;text-align:center;padding:.75rem 0">
                <button id="exhLoadMoreBtn" onclick="loadMoreEXH()"
                        style="border:1px solid var(--glass-border);background:var(--glass-bg);color:var(--text-primary);padding:.4rem 1.25rem;border-radius:10px;font-size:.8rem;font-weight:600;cursor:pointer">
                    <i class="fas fa-chevron-down mr-1"></i> Load More
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