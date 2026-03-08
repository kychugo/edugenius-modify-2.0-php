# EduGenius AI 智學

An AI-powered learning platform for Hong Kong DSE students.

## Features

- Multi-subject AI tutors: Ask AI, Dictionary AI, Guide Learning
- ICT Code Review (Python / HKDSE syllabus)
- Exam Paper Generator (Math, Physics, Biology)
- English Writing Practice & Sample Essays
- Vocabulary Generator & Quiz
- Classical Chinese tools: 文言文翻譯, 文樞

## History Feature (New)

Every AI interaction is automatically saved to **MySQL** (via the PHP backend) under the authenticated user's account. Users can view, browse, and delete their history on the **History** page (`history.php`).

### Database Structure

History sessions are stored in MySQL. Each record contains:

- `tool` — e.g. "Ask AI", "Code Review"
- `subject` — e.g. "Math", "ICT", "English"
- `page` — source page identifier
- `summary` — first user message (≤ 100 chars)
- `messages` — JSON array of `{role, content, timestamp}`
- `created_at`, `updated_at` — timestamps

**Minimum-usage design:**
- Chat tools (Ask AI, Dictionary AI, Guide Learning): 1 write on the first Q&A, then 1 write per subsequent Q&A pair.
- Single-generation tools (Code Review, Exam, Writing, Vocabulary, Translation): 1 write per generation.
- History reads only happen when the user explicitly opens `history.php` (20 sessions per page, paginated).
