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

Every AI interaction is automatically saved to **Firebase Firestore** under the authenticated user's account. Users can view, browse, and delete their history on the **History** page (`history.html`).

### Firestore Data Structure

```
users/{userId}/history/{sessionId}
  - tool        : string   — e.g. "Ask AI", "Code Review"
  - subject     : string   — e.g. "Math", "ICT", "English"
  - page        : string   — source page identifier
  - summary     : string   — first user message (≤ 100 chars)
  - messages    : array    — [{role, content, timestamp}]
  - createdAt   : Timestamp
  - updatedAt   : Timestamp
```

**Minimum-usage design:**
- Chat tools (Ask AI, Dictionary AI, Guide Learning): 1 write on the first Q&A, then 1 write per subsequent Q&A pair using `arrayUnion`.
- Single-generation tools (Code Review, Exam, Writing, Vocabulary, Translation): 1 write per generation.
- History reads only happen when the user explicitly opens `history.html` (20 sessions per page, paginated).

### Firebase Firestore Security Rules

Apply the rules in `firestore.rules` to your Firebase project:

1. Open the [Firebase Console](https://console.firebase.google.com/).
2. Select your project (`login-system-9c566`).
3. Go to **Firestore Database → Rules**.
4. Replace the existing rules with the contents of `firestore.rules`.
5. Click **Publish**.

```
rules_version = '2';
service cloud.firestore {
  match /databases/{database}/documents {
    match /users/{userId}/history/{sessionId} {
      allow read:   if request.auth != null && request.auth.uid == userId;
      allow create: if request.auth != null
                    && request.auth.uid == userId
                    && request.resource.data.tool     is string
                    && request.resource.data.subject  is string
                    && request.resource.data.summary  is string
                    && request.resource.data.messages is list
                    && request.resource.data.messages.size() >= 1
                    && request.resource.data.messages.size() <= 200;
      allow update: if request.auth != null
                    && request.auth.uid == userId
                    && request.resource.data.messages is list
                    && request.resource.data.messages.size() <= 200;
      allow delete: if request.auth != null && request.auth.uid == userId;
    }
    match /{document=**} {
      allow read, write: if false;
    }
  }
}
```
