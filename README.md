# Pranav Pipeline Dashboard

> A login-protected, mobile-friendly web dashboard on top of a **Bitrix24** deal pipeline (Category 46, *Pranav Pipeline*).
> See every student deal, filter it, report on it, edit it safely, message the student on WhatsApp, and ask **Pranav** (the built-in bot) questions, all from one page on plain **cPanel / PHP hosting**.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-vanilla-F7DF1E?logo=javascript&logoColor=black)
![Bitrix24](https://img.shields.io/badge/Bitrix24-REST%20webhook-2FC6F6)
![Hosting](https://img.shields.io/badge/Hosting-cPanel-FF6C2C)
![Database](https://img.shields.io/badge/Database-Bitrix24%20CRM-2FC6F6)
![Build](https://img.shields.io/badge/Build%20step-none-success)

---

## 📑 Table of contents

1. [What this project is](#-what-this-project-is)
2. [Feature list](#-feature-list)
3. [Screens and reports](#-screens-and-reports)
4. [Pranav, the built-in bot](#-pranav-the-built-in-bot)
5. [WhatsApp templates](#-whatsapp-templates)
   - [Voice, sound and the mascot](#-voice-sound-and-the-mascot)
   - [Images and icons](#-images-and-icons)
6. [Architecture](#-architecture)
7. [Folder structure](#-folder-structure)
8. [Install on cPanel](#-install-on-cpanel)
9. [Configuration reference](#-configuration-reference)
10. [Business rules](#-business-rules-worth-knowing)
11. [Security](#-security)
12. [Tools and technologies used](#-tools-and-technologies-used)
13. [Development journey (phases)](#-development-journey-phase-by-phase)
14. [Local development and testing](#-local-development-and-testing)
15. [Things we tried and dropped](#-things-we-tried-and-dropped)
16. [Known limitations](#-known-limitations)
17. [Troubleshooting](#-troubleshooting)
18. [Roadmap](#-roadmap-ideas)

---

## 🎯 What this project is

The original code was an older university dashboard. This project is a **rebuild**: a base website that loads **all deals from one Bitrix24 pipeline** and turns them into something a sales / admissions team can use every day.

| Goal | How it is done |
|---|---|
| One link for everyone | Hosted at `https://penpapper.com/pranav-dashboard` on cPanel |
| Always fresh data | Auto-refresh every **5 minutes**, with a progress bar and "Updated X min ago" label |
| Safe editing | Edits go back to Bitrix24; comment, name and next follow-up date are enforced |
| Team separation | Admins see everything; team logins see only their own **Team Name** |
| Fast answers | Built-in **Pranav bot**, no external AI service |
| Low cost | **Bitrix24 is the database** (no separate DB to host), no Composer, no npm, no build step |


### 🆕 Latest additions

| Area | What was added |
|---|---|
| 🎙️ **Voice** | Speak to Pranav (voice to text), say **"Hey Pranav"** to wake it, replies read aloud in a **male voice with emotion**, a voice picker |
| 🔊 **Sound** | Gate-opening, chime, squeak, thud, crying and grunt effects, all synthesised in the browser (no audio files) |
| 🐒 **Corner mascot** | Scared when you drag it, cries while falling, gets angry and walks back home |
| 🖼️ **Images** | Glossy 3D icon set (63 SVG icons), university photos on the welcome screen, a hero palace image on the dashboard banner |
| 🙂 **Profile photo** | Each person can upload a photo that replaces their initial everywhere their name shows |
| 🆕 **New-deals pop-up** | At login, deals created since your last visit, grouped by **Team Name** |
| 🚪 **Log out** | Menu item renamed, with a confirmation pop-up |
| 📥 **Dropdowns** | Always open downward and never push the page sideways |
| 🏷️ **Tab icon** | Browser-tab icon recoloured to match the teal and orange brand |

---

## ✨ Feature list

### 📊 Dashboard and navigation
- 🏠 **Home** with KPI tiles (total deals, universities, active, follow-up overdue, not contacted, programs, payment modes, dropped, refund, payment overdue, documents pending) and a hero banner.
- 🎓 **Universities** list (pop-up, list style) with a page per university.
- 📋 **Master overview** and **All deals** table: search, stage chips, sorting, column chooser, CSV download.
- 🔎 **Global search pop-up** (`Ctrl + K`): suggestions before typing, live grouped results (students, universities, pages), keyboard navigation, recent searches.
- 🧭 Collapsible, slideable sidebar with live count badges.
- 🌗 **Dark / light theme** toggle. In night mode the hero scene, clouds, sparkles and background glows are boosted so the animations stay visible.
- 💾 **Saved views**: save any filtered table under a name; it appears in the sidebar and follows your login to any device.
- 📱 Mobile-friendly layout (checked at 390 px, no sideways scrolling).
- 🎬 **Welcome screen** after login: gates open, the mascot walks out and greets you, with a card per university.
- ⭐ **Priority leads** pop-up and 🆕 **New-deals pop-up** (deals created since your last visit, grouped by Team Name; clicking a deal opens it).
- 📥 **Excel export** of any filtered table (one coloured sheet plus a summary).
- 🙂 **Profile photos** that show in the top bar, the account menu, the Activity log, recent activity and the Usage screen.
- 📥 **Dropdown behaviour**: every filter and menu opens downward and only flips left or right to stay on screen, so the page never jumps sideways.

### 🔍 Filters
- Global filter bar: **University, Program, Batch, Mode of payment, Stage, Current semester, Team Name** (admins only).
- Every filter is **multi-select with a search box** and Apply / Clear buttons.
- Filter choices persist while you move between pages.
- Date filters on the activity log.

### 📑 Reports
| Report | What it answers |
|---|---|
| 🔁 Re-registration tracker | Who has moved to the next semester and who is still pending |
| 📄 Documents pending | Complete / pending / not updated, using the **Documents Status** field |
| 📞 **Lead status (Connected / RNR)** | How many students are connected, how many are RNR, every status value with its count, broken down by any field |
| 🤝 Reference report | *Has reference* and *Reference admission* |
| 📈 Re Reg Percentage | Done vs pending re-registration, by university / program / batch |
| 🎓 Alumni | Dashboard just for alumni deals |
| 🕵️ Activity log | Who changed what and when, with date filters |
| 🧩 Custom report builder | Pivot any field by any field |

Every number in every report, chart and tile is **clickable** and opens exactly those deals.

### ✏️ Editing (written back to Bitrix24)
- Sectioned edit form (five sections) with the required fields only.
- Editable: comments, **Lead Status**, **Last Payment Date**, university, phone, email and more.
- A normal save **requires** a comment, the person's name and a new **Next follow-up date**. Name/phone/email-only fixes do not.
- Phone / email: updates the existing contact, or creates one if none exists.
- **Conflict detection**: if someone else changed the deal meanwhile, the save is refused (HTTP 409) instead of overwriting.
- Every save is recorded in the activity log.

### 👥 Access control
- **Admin logins** see all data. Several admins are supported.
- **Team logins** see and edit only deals whose *Team Name* matches. Enforced on the server, not just in the UI.
- Account menu: **Your profile** (display name, colour, phone, **profile photo**), **Change password**, sound / voice / "Hey Pranav" switches, the corner mascot toggle, **Usage** (admin only), **Refresh data** and **Log out** (asks for confirmation).

### 🙂 Profile photos
- **Your profile → Upload photo**. The browser crops it square and shrinks it to 256 px; the server re-encodes it (so only pixels are kept) and stores it in `cache/avatars/`.
- Admins see everyone's photo in the Activity log and Usage; team logins only see their own.

### 📈 Usage and data health (admin only)
- **Usage screen** (account menu): page views, bot questions, bot misses, searches, edit forms opened, per-login activity, most-visited pages, questions the bot could not answer, and people per day.
- **Data health check**: compares the dashboard with Bitrix24 right now (deal count, newest deal ID, changes since last refresh).
- **Daily backup** of profiles, activity log and usage log, kept 14 days.
- **Daily summary e-mail** per team lead plus an admin copy with the Bitrix24 cross-check.

### 💬 Counsellor helpers
- 💳 **Payment overdue** and 📄 **Docs pending** sidebar lists with counts.
- 🟢 **WhatsApp button** beside every phone number with four ready-made message templates.
- 👤 **Updated by** column right after Comments (who wrote the latest comment).
- Freshness label turns amber after 30 minutes and red after 2 hours.

---

## 🖥️ Screens and reports

```
#/                       Home (KPI tiles, charts)
#/master                 Master overview
#/master/sheet?f=...     Full-page table, filtered (paydue, docpend, notcontacted, stale, ...)
#/u/<university>         One university
#/reports                Reports list
#/reports/<id>           A report (lead, docs, reg, rr, alumni, activity, ref, custom)
```

The app is a **single-page app** with hash routing. The `#/` in the address bar is normal.

---

## 🤖 Pranav, the built-in bot

A **rule-based, offline** assistant embedded in the page. No AI API, no data leaves your server.

- 🔢 **Counts and breakdowns**: "how many deals in muj + smu", "sem 1 paid in alpha", "which university has the least deals", Hinglish "kitne … hain".
- ✅ **Every number it gives is clickable** and opens the table filtered to exactly those deals.
- 🔤 Typo tolerant (Levenshtein matching plus a vocabulary of field values).
- 🔍 Finds students by **name, phone, email or deal number**; answers status, phone, follow-up, who worked last, created dates.
- 🎛️ Applies dashboard filters from plain text ("only mba and bba"), opens reports, shows latest / oldest deals, "what did I work on".
- 🧑‍🏫 **Step-by-step how-tos** for every process in the dashboard.
- ✏️ **Edit requests open the lead's edit form**, with a friendly "that's your work, boss" line. The bot never writes to Bitrix24.
- ⚠️ **Honest about gaps**: if a word in a question is not understood, the reply says so and states that it was **not** counted.
- 😢 Unanswerable in-domain questions get the "admin has not trained me" reply (and are logged for the Usage screen); off-topic questions get "out of my jungle".
- 🎭 Moods and humour, a greeting joke each time it opens. It greets people by the **display name in their profile** (falling back to the username).
- 🎙️ Voice in and out: see [Voice, sound and the mascot](#-voice-sound-and-the-mascot).

Source: the bot now lives **inside `app/index.php`**, between the `<!--MONKEY-->` and `<!--/MONKEY-->` markers (voice, speech and sound code sits in the `<!--SFX-->` block just above it). `src/monkey-bot/bot.js` is the older stand-alone copy.

---

## 📨 WhatsApp templates

Clicking the 🟢 button next to a phone number opens a pop-up with four options:

| Option | Use it when |
|---|---|
| ✅ **Connected** | You spoke to the student |
| 📵 **RNR** | The student did not pick up |
| 🎓 **Alumni Connected** | Alumni, you spoke to them |
| 🎓📵 **Alumni RNR** | Alumni, no response |

- The message is **only typed into the chat box**. You press send yourself.
- `{name}` becomes the lead's name and `{university}` becomes the lead's university from the website (e.g. "<University> Online").
- The signature uses the **logged-in person's profile** (display name and phone). Everyone must fill these in under *Your profile*; if empty, the wording from the shared Word file is used.
- The wording lives in one block (`WA_TPL`) in `app/index.php`.
- Account menu → **WhatsApp opens in** switches between a **browser tab** and the **desktop app** (per device).

---

## 🎙️ Voice, sound and the mascot

All of this runs **in the browser** (Web Speech API and Web Audio). No server code, no audio files, nothing extra to install.

### Talk to Pranav (voice to text)
- A **mic button** sits next to Send in the chat. Tap it, speak, and the words appear in the text box live. Nothing is sent automatically: press **Send** or **Enter** when you are happy.
- Language is set to Indian English (`en-IN`).

### "Hey Pranav" wake word (opt-in)
- Account menu → **Say "Hey Pranav": off / on**. Off by default.
- When on, a small pill beside the mascot shows *Listening* and the words it last heard, so you can see what the browser understood.
- Saying "hey / hi / ok / hello + Pranav" (close spellings such as *Pranab* are accepted) opens the chat and Pranav asks **"Did you just call me?"**.
- It listens only while the tab is visible and the chat is closed, and pauses while the bot is speaking.

### Pranav speaks back
- If you **dictated** your question, the reply is read aloud. If you typed, it stays silent. The **speaker button** in the chat header switches to "always read replies".
- A **male English voice** is preferred automatically (Indian English first). If none is installed the pitch is lowered instead.
- **Emotion**: the voice changes with the bot's mood (happy, excited, surprised, crying, angry, confused) and with punctuation (`!`, `?`, `...`) per sentence.
- Account menu → **Voice: … · tap to change** cycles through the English voices installed on that device and plays a sample.
- Account menu → **Sound & voice** is the master switch: it silences every effect and the speech.

### Sound effects
Synthesised with the Web Audio API: gate creak and clunk, whoosh, welcome chime, squeak, falling whistle, thud, crying, grunt, chat pop, mic blips and the wake ding. If the browser blocks sound on first load, the welcome screen shows **"🔊 Tap for sound"**.

### Corner mascot
- Roams the page, perches on cards, sleeps when idle, reacts to new deals and overdue follow-ups, and can be fed a banana.
- **Drag it**: it looks scared, then **cries while falling**, then gets **angry** and walks back to its corner.
- Account menu lets you put it to rest (setting is remembered per browser).
- Reduced-motion users get a static, seated mascot.

### Browser support and privacy
| | |
|---|---|
| Chrome, Edge | Full support |
| Safari | Speech output works; voice input is limited |
| Firefox | No speech recognition: the mic button and wake word are hidden; speech output works |

- Chrome sends microphone audio to Google's speech service to convert it. It needs internet, and the browser shows a "microphone in use" indicator, constantly when "Hey Pranav" is on. Tell your team before enabling it on a shared floor.
- Settings are remembered per browser in `localStorage`: `pp_sound`, `pp_voice_all`, `pp_voice_name`, `pp_wake`.

---

## 🖼️ Images and icons

Put an `images/` folder **inside `app/`** (next to `index.php`) in GitHub and upload it to the server beside `index.php`:

```
app/images/
  icons/                 63 glossy SVG icons (university.svg, hourglass.svg, ...)
  universities/          muj.jpg  vit.jpg  smu.jpg  dsu.jpg  amity.jpg   (your own photos)
  university_hero.png    dashboard banner picture (transparent PNG recommended)
```

- **Icons** replace the emoji on tiles, the sidebar and stats. Faces and animals stay as native emoji. If an icon is missing, the emoji shows instead, so nothing breaks.
- **University photos** appear on the welcome screen cards. Name each file after the university's short name; the page finds them automatically (`?imglist=1` lists what the server sees).
- **Hero picture**: `university_hero.png` (also `.webp`, `.jpg`, `.jpeg`) is fitted into the dashboard banner. Use a PNG with a transparent background so it blends with the banner; the page hides the built-in clouds and trees when it is present.
- The folder is searched in `images/`, `images/images/` and a few similar paths, so a mis-nested upload still works.

---

## 🧱 Architecture

```
Browser (single-page app, vanilla JS, hash routing)
   │  fetch() + X-CSRF-Token
   ▼
app/index.php         login, sign-out, whole UI (HTML + CSS + JS, Pranav bot embedded)
app/api.php           JSON API
                        data | refresh | progress | update | activity
                        profile_save | password_change
                        avatar | avatar_save | avatar_del
                        nd (new-deals pop-up) | track | usage | reconcile | views_save
app/lib.php           config, sessions, Bitrix24 REST client (cURL), cache, field metadata,
                      edit validation, activity log, usage log, daily backup, reconcile check
app/refresh.php       optional cron: refresh the cache from the command line
app/daily_summary.php optional cron: daily e-mail summary + Bitrix24 cross-check
   │
   ▼
Bitrix24 REST (inbound webhook)          app/cache/  (JSON snapshot, logs, profiles; web access denied)
```

- **PHP 7.4+**, cURL, JSON, sessions. **Bitrix24 is the database**: all deal data is read from and written back to it. No separate database, no Composer, no build step.
- Data is fetched from Bitrix24, cached in `app/cache/`, and rendered client-side.
- The whole UI is one self-contained `index.php`: no CDN, no npm.

---

## 📁 Folder structure

```
app/                         ← the only folder you upload to the server
  index.php  api.php  lib.php  refresh.php  daily_summary.php
  config.sample.php          ← copy to config.php (git-ignored)
  .htaccess                  ← blocks config/lib/cache, hides /index.php
  cache/.htaccess            ← denies web access to cached data
  cache/avatars/             ← profile photos (created automatically, git-ignored)
  images/                    ← icons, university photos, hero picture (see Images and icons)
src/monkey-bot/bot.js        ← older stand-alone copy of the bot (see warning below)
tools/
  inject_bot.py              ← embed bot.js into app/index.php
  dev.sh                     ← local dev server with a fake Bitrix24
  mock_bitrix.py             ← fake Bitrix24 REST API (1,193 sample deals)
  test-config.php            ← throw-away config for the dev server
tests/bot/                   ← Playwright runner + question sets for the bot
```

---

## 🚀 Install on cPanel

1. **Bitrix24 webhook** → *Developer resources → Other → Inbound webhook*. Give it **only the `crm` scope**. Copy the URL.
2. **Upload** the *contents* of `app/` to e.g. `public_html/pranav-dashboard/` (File Manager, enable *Show hidden files* so `.htaccess` is included).
3. **Create the config**: copy `config.sample.php` to `config.php`, fill in the webhook URL, admin username and a **long, unique password**. Add team logins if needed.
4. Open `https://your-domain/pranav-dashboard/`, sign in, press **Refresh data** once.
5. *(Optional)* cron jobs:
   - `php /home/USER/public_html/pranav-dashboard/refresh.php` every 15 to 30 minutes
   - `php /home/USER/public_html/pranav-dashboard/daily_summary.php` once a day (for example 8 AM)

### ✅ Verify it is locked down (once)
`/cache/` → 403, `/config.php` → 403 or blank, `/lib.php` → 403 or blank. Delete any test or debug scripts you uploaded.

### 🌐 Custom domain (optional)
cPanel → *Domains* → add the domain with document root `public_html/pranav-dashboard`, point DNS, run AutoSSL.

---

## ⚙️ Configuration reference

All keys live in `app/config.php`.

| Key | Required | Meaning |
|---|---|---|
| `webhook_url` | ✅ | Bitrix24 inbound webhook, with trailing slash |
| `username`, `password` | ✅ | Main admin login |
| `extra_users` | | `array('user' => 'password')` more admins |
| `team_users` | | `array('user' => array('password' => '…', 'team' => 'Team Name'))`, scoped to one team |
| `pipeline_id` | | Bitrix24 category to show (default `46`) |
| `allow_edit` | | `false` makes the dashboard read-only |
| `stale_days` | | Days without a comment before follow-up counts as overdue (default `15`) |
| `closed_stages` | | Stage IDs treated as closed (default: guessed from stage names) |
| `editable_extra` | | Extra custom-field IDs allowed in the edit form |
| `cache_dir` | | Cache location; prefer a folder **outside** `public_html` |
| `summary_admin` | | E-mail for the admin copy of the daily summary and mismatch alerts |
| `summary_to` | | `array('Team A' => 'lead@example.com')`, names must match Team Name exactly |
| `dashboard_url` | | Link shown in the summary e-mail |

Key Bitrix24 custom fields used (full list at the top of `app/lib.php`): Documents Status `UF_CRM_1790840655148`, Comment History (G) `UF_CRM_1784701507`, Team Name `UF_CRM_1790246879`, Next Follow-up `UF_CRM_1784722248`, Lead Status `UF_CRM_1784699787`, Last Payment Date (P) `UF_CRM_1782985171`, Next Payment Date (P) `UF_CRM_1782985117`.

---

## 📐 Business rules worth knowing

- **Follow-up overdue**: active deal with comments whose latest comment is `stale_days`+ days old. Closed and alumni deals are excluded.
- **Not contacted**: active deal with **no comment at all**.
- **Re Reg %**: only *Active* students count.
  - No *Last Payment Date* → **not counted at all**.
  - Today between *Last* and *Next* payment date → **Done**.
  - Anything else → **Pending**.
  - % = Done ÷ (Done + Pending).
- **Payment mode "Semester"** (Lumpsum / Annual / Semester) is different from **semester number** (Sem 1 to 6). The bot treats "dsu bba semester" as the payment mode.
- **Lead status grouping** (Connected / RNR / Other / Not updated) is decided by the wording of each Lead Status value. The report prints which values it put in each group, so you can spot a wrong one.
- **Payment overdue**: active deal whose next payment date has passed (same rule as the Re Reg report).

---

## 🔐 Security

- Keep `webhook_url` and passwords **only** in `app/config.php` (git-ignored). If a webhook is exposed, **delete it in Bitrix24**; creating a new one alone does not revoke the old one.
- Use **long, unique passwords**. Weak or reused passwords are the most likely way in.
- Scope the webhook to `crm` only.
- `.htaccess` denies `config.php`, `lib.php` and `cache/`. Verify after every deploy.
- Writes are POST-only with a per-session **CSRF token**; sessions are regenerated on login; HttpOnly cookies; 8-hour session lifetime; failed logins are delayed.
- Team logins are restricted **on the server**.
- The usage log stores the event type, login name, a short detail (page name, search text, bot question) and time. Searches and bot questions are stored as typed, so treat them as not private.
- Profile photos are validated server-side (JPEG only, size-limited, re-encoded) and served only to logged-in users.
- The microphone is used only after the person turns a voice feature on and the browser grants permission.
- Never commit `config.php` or anything under `cache/` (this includes `cache/avatars/`).

---

## 🧰 Tools and technologies used

| Area | Tools |
|---|---|
| Backend | PHP 7.4+, cURL, sessions, JSON files for cache and logs |
| Frontend | Vanilla JavaScript, CSS variables, inline SVG, hash routing (no framework) |
| Data source | Bitrix24 REST API (`crm.deal.list/get/update`, `crm.contact.*`, `crm.status.list`, `crm.deal.fields`) via inbound webhook |
| Hosting | cPanel shared hosting, `.htaccess`, cron |
| Bot | Hand-written NLP: tokenising, Levenshtein typo fixing, field-value dictionary, rule engine |
| Voice and sound | Web Speech API (recognition and synthesis), Web Audio API (synthesised effects) |
| Images | Hand-built SVG icon set, inline SVG campus scene, user-supplied photos |
| Testing | **Playwright** (headless Chromium), **jsdom**, a **mock Bitrix24 server** (Python) with 1,193 sample deals, local PHP built-in server |
| Tooling | Python scripts to inject the bot and generate fixtures; **Claude Code** for pair-programming |
| Version control | Git / GitHub (private repo recommended) |

---

## 🛠️ Development journey (phase by phase)

### Phase 1 🌱 Foundation
- Reviewed the public `University_Dashboard` repo and decided to **rebuild** rather than patch: load every deal from the Pranav Pipeline.
- Flowchart of the original site, local test setup, webhook permissions (`crm`).
- Backend `lib.php` + `api.php`, cache file, auto field-role mapping by exact Bitrix field IDs.

### Phase 2 🏠 Core UI
- Login, home with universities, master dashboard, per-university pages.
- Programs × mode-of-payment matrix, full-page sheet, charts below numbers, three coloured charts, semester ordering, compact grids.
- Slideable sidebar, ☰ toggle, emoji icons, pop-up Universities list.

### Phase 3 📑 Reports
- Report catalogue and design review from a sample report image.
- Documents pending, Reference report, Re Reg Percentage (rule rewritten twice), Alumni dashboard, activity log with date filters, custom pivot builder.
- Every number made **clickable** and verified to match the rows that open.

### Phase 4 🎛️ Filters
- Multi-select filters with search, Apply / Clear, persistence (fixing the "selection jumps back to first option" bug), Team Name filter for admins, date filters.

### Phase 5 ✏️ Editing and access control
- Edit form → Bitrix24 write-back, five sections, required comment + name + follow-up date.
- Contact create/update fix for phone and email.
- Admin / team logins, several admins, `profiles.php` with hashed passwords, activity log "who did what".

### Phase 6 🎨 Look and feel
- Hero banner, animated login with a **monkey mascot** (typing, peeking, detective, celebration), logo, account menu, bar-graph brand mark, dark / light toggle, auto-refresh with progress bar and "Updated X min ago".

### Phase 7 🤖 Pranav bot
- Spec → rule-based bot → many upgrade rounds: typo handling, field-value dictionary, count engine, how-tos, watcher jokes, edit-opens-lead flow, Hinglish counts, clickable numbers, "semester" payment-mode fix, unrecognised-word warning.
- Regression suites of a few hundred questions, diffed against saved baselines after every change.

### Phase 8 🔒 Hardening and operations
- Webhook leak response (regenerated, old one deleted, debug files removed), `.htaccess` lock-down, clean URL.
- Usage tracking, Usage screen, bot-miss list.
- Daily backup, Bitrix24 reconcile check, freshness colours, daily summary e-mail.

### Phase 9 📞 Counsellor workflow
- Payment overdue and Docs pending lists, Lead status report, saved views, Updated by column, WhatsApp button and four message templates.

### Phase 10 🎨 Polish: images, mascot and account
- Glossy 3D SVG icon set, university photos and hero palace image, redrawn welcome campus, hidden clouds / trees when the hero image is used.
- Downward-only dropdowns, new-deals pop-up, simplified account menu with a confirmed **Log out**, teal tab icon.
- Corner mascot reactions (scared, crying, angry, back home).

### Phase 11 🎙️ Voice, sound and profile photos
- Voice to text, "Hey Pranav" wake word with a live "heard" pill, spoken replies in a male voice with emotion, a voice picker, synthesised sound effects and a master mute.
- Profile photos everywhere a name appears. The bot now greets people by profile name and no longer calls itself a monkey.

### Phase 12 📦 Packaging
- GitHub-ready project: `app/`, `src/`, `tools/`, `tests/`, `.gitignore`, config sample with placeholders only, and this README.

---

## 🧪 Local development and testing

Requirements: PHP 7.4+, Python 3, Node 18+ (tests only).

```bash
tools/dev.sh        # fake Bitrix24 + dashboard on http://127.0.0.1:8080  (admin / S3cret-Pass-123)

# bot tests (dev server must be running)
cd tests/bot && npm install && npx playwright install chromium
node run.js bt2_q.json        # prints each question with the bot's answer
```

> ⚠️ **Do not run `tools/inject_bot.py` any more.** The bot has since been edited directly inside `app/index.php` (voice, name handling, wording), and re-injecting the older `src/monkey-bot/bot.js` would overwrite those changes. Edit the `<!--MONKEY-->` block in `app/index.php` instead, or first copy it back into `src/` if you want the split layout again.

> ⚠️ All development testing used **mock data**. Run the **Data health check** and spot-check numbers against Bitrix24 on your live data.

---

## 🗑️ Things we tried and dropped

| Idea | Outcome |
|---|---|
| 🔑 Passkey / fingerprint sign-in (WebAuthn) | Built and tested, then **removed** on request. The browser's "ask for PC password before autofill" prompt is a browser setting; a website cannot force it. |
| 📞 Call icon next to phone numbers | Removed; only WhatsApp remains |
| 🪟 Reuse one WhatsApp tab | **Not possible** from a website: WhatsApp Web severs the link to the opener. A desktop-app mode exists instead; a Chrome extension was offered and declined |
| 🖼️ Per-option emoji icon set in the sidebar | Replaced later by the glossy 3D icon set (see Images) |
| 🌐 `pranavpipeline.com` domain | Optional, not required |
| 🔐 Password hardening | Offered, declined for now (see Known limitations) |
| ☁️ Generated realistic clouds for the hero banner | Dropped; the hero picture is supplied by the owner |
| 📲 *WhatsApp opens in…*, *Follow-up overdue*, *Search* and *Activity log* items in the account menu | Removed to keep the menu short (all still reachable from the page itself) |
| 🐒 Calling the bot "the monkey" in its replies | Removed; the bot is just Pranav. The corner mascot is still drawn as a monkey |

---

## ⚠️ Known limitations

- Weak or reused passwords are still the biggest risk if they exist in `config.php`. Passwords are plain text there (protected by `.htaccess`); profile-changed passwords are hashed.
- Login throttling is a delay, not a per-IP lockout.
- "Updated by" shows the author of the latest **comment**. Changes made directly in Bitrix24 without a comment cannot be attributed (the webhook has no user-lookup permission).
- The Lead status grouping is wording-based; check the printed group list once.
- WhatsApp opens a new browser tab on each click (browser limitation). The desktop-app mode avoids this but needs the app installed.
- WhatsApp signatures come from each user's profile; an empty profile falls back to the sample signer.
- **Voice** needs Chrome or Edge for dictation and "Hey Pranav". The wake word only works while the tab is open and visible, and it cannot judge loudness, only words. Voice quality and the male voice depend on the voices installed on each device (Edge's "Natural" voices are best). Chrome's online voices may ignore pitch.
- Browsers can block sound on first load; the welcome screen then offers a tap-for-sound button.
- Profile photos are stored as files under `cache/`; keep that folder writable and out of Git.
- Mock-data tested; the Bitrix24 cross-check, daily e-mail delivery and real-WhatsApp opening should be verified once on live systems.

---

## 🩺 Troubleshooting

| Symptom | Fix |
|---|---|
| "Setup needed" page | `config.php` missing or still has `PASTE_…` values |
| Empty dashboard / "Could not load" | Webhook wrong, deleted or missing `crm` scope. Test `…/crm.deal.list.json` in a browser |
| Numbers differ from Bitrix24 | Press **Refresh data**, then run the **Data health** check |
| Sign-out goes to the site home page | Use the `.htaccess` and `index.php` from this repo |
| `500` after editing `.htaccess` | Remove the `<FilesMatch>` block first; keep the `<IfModule mod_rewrite.c>` block |
| Summary e-mail not arriving | Check `summary_admin` / `summary_to`, the cron job, and that PHP `mail()` is enabled on the host |
| WhatsApp message shows the wrong signer | Fill in *Your profile* → display name and phone |
| Icons or university photos not showing | The `images/` folder must sit next to `index.php` on the server. Open `…/index.php?imglist=1` to see which folders and files the server finds |
| Mic button missing | The browser has no speech recognition (Firefox). Use Chrome or Edge |
| "Mic is blocked" | Click the lock icon in the address bar → Microphone → Allow, then reload |
| "Hey Pranav" does nothing | Turn it on in the account menu, then check the pill: it should show *Listening* and the words heard. No pill = switch is off or the mic is blocked |
| No welcome sound | The browser blocked it; tap **🔊 Tap for sound** on the welcome screen. Check **Sound & voice** is on |
| Voice sounds female or flat | Account menu → **Voice** → tap until a male voice appears; in Edge pick a *Natural* voice |
| Profile photo will not upload | Use a JPG or PNG under a few MB; make sure `cache/` is writable |

---

## 🗺️ Roadmap ideas

- 🔒 Hash all passwords in `config.php`, per-IP login lockout, idle-session timeout.
- 📲 WhatsApp summaries (needs a paid messaging API).
- 🧾 Unit tests for the validation rules in `lib.php`.
- 🔗 Optional history-API routing (clean URLs without `#/`).
- 🗣️ Language switch for voice input (Hindi, Tamil, Kannada).
- 🧠 Review the Usage screen's "bot could not answer" list weekly and add the missing phrasing.

---

## 📝 Licence and privacy

No licence is included. Keep the repository **private**: it contains your field IDs, pipeline rules and business logic. Never publish `config.php`, the webhook URL, or anything from `cache/`.
