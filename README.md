# Pranav Pipeline Dashboard

A login-protected, mobile-friendly web dashboard on top of a **Bitrix24** deal pipeline (Category 46, "Pranav Pipeline").
It shows every deal as KPI tiles, charts, reports and a sortable/filterable table, lets counsellors **edit deals safely**
(comment + owner name + next follow-up date are enforced), and ships with **Monkey bot 🐒**, a built-in, rule-based assistant
that answers questions about the data (no external AI service, no data leaves your server).

Built for plain **cPanel / PHP shared hosting**. No database, no Composer, no build step.

> **Security first:** the Bitrix24 webhook and every password live only in `app/config.php`, which is git-ignored.
> Never commit it, never paste the webhook in chat or tickets. See [Security](#security).

---

## Features

**Dashboard**
- Home with KPI tiles (total, active, overdue, not contacted, programs, payment modes, stages…), stage donut, monthly trend, recent changes.
- Global filters: University, Program, Batch, Mode of payment, Stage, Current semester, Team Name (multi-select).
- Per-university pages, a master table with search, stage chips, sorting, paging and **CSV download**.
- **Global search pop-up** (click the search bar or press `Ctrl+K`): suggestions before you type, live grouped results while you type (students, universities, pages/reports), keyboard navigation, recent searches.
- **Reports:** Re-registration tracker, Documents pending, Reference report, Re Reg Percentage, Alumni, activity log, and a pivot builder (any field by any field).
- Light / dark theme toggle (remembers your choice).

**Editing (Bitrix24 write-back)**
- Edit deal fields from a form; changes go to Bitrix24 via REST.
- Every normal save requires a **comment**, the person's **name** and a new **Next follow-up date**. Name/phone/email-only fixes don't.
- Conflict detection (HTTP 409) if someone else changed the deal meanwhile; your comment is saved on the deal with every change.

**Access control**
- Admin logins see everything; **team logins** see and edit only deals whose *Team Name* matches.
- CSRF token on every write, session hardening, delayed login failures, activity log.
- Users can change their own display name, colour and password in the app.

**Monkey bot 🐒** (rule-based, offline)
- Counts and breakdowns ("how many deals in muj + smu", "sem 1 paid in alpha", "which university has the least deals"), typo-tolerant, Hinglish "kitne…" supported.
- **Every number it gives is clickable** and opens the table filtered to exactly those deals.
- Finds students by name / phone / email / deal number, answers status, phone, follow-up, who worked last, created dates, etc.
- Applies dashboard filters from plain text ("only mba and bba"), opens reports, shows latest/oldest deals, "what did I work on".
- Step-by-step how-tos for every process. For edits it **opens the lead's edit form** and walks you through it (editing is the user's job; the bot never writes to Bitrix24).
- Watches what you do on the page and cracks a monkey joke on every open.

---

## Architecture

```
Browser (single-page app, hash routing: #/ , #/master , #/u/<name> , #/reports/<id>)
   │  fetch() + X-CSRF-Token
   ▼
app/index.php   login, sign-out, the whole UI (HTML + CSS + vanilla JS, Monkey bot embedded)
app/api.php     JSON API: data | refresh | update | progress | activity | profile_save | password_change
app/lib.php     config loader, sessions, Bitrix24 REST client (cURL), cache, field metadata, validation, activity log
app/refresh.php optional cron script to refresh the cache from the command line
   │
   ▼
Bitrix24 REST (inbound webhook)         app/cache/  (JSON snapshot, activity log, profiles — web access denied)
```

- **PHP 7.4+** (no extensions beyond cURL, JSON and sessions). **No database.**
- Data is fetched from Bitrix24, cached in `app/cache/`, and rendered client-side. "Refresh data" re-pulls it.
- Frontend is a single self-contained `index.php` (no CDN, no npm). The bot's source is `src/monkey-bot/bot.js` and is **embedded** into `index.php` (see [Development](#development)).

### Folder structure

```
app/                      <- the only folder you upload to the server
  index.php  api.php  lib.php  refresh.php
  config.sample.php       <- copy to config.php and fill in (config.php is git-ignored)
  .htaccess               <- blocks config/lib/cache, hides /index.php
  cache/.htaccess         <- denies web access to cached data
src/monkey-bot/bot.js     <- Monkey bot source (injected into app/index.php)
tools/
  inject_bot.py           <- embed bot.js into app/index.php
  dev.sh                  <- local dev server with a fake Bitrix24
  mock_bitrix.py          <- fake Bitrix24 REST API (1,193 sample deals)
  test-config.php         <- throw-away config for the dev server
tests/bot/                <- Playwright runner + question sets for the bot
```

---

## Install on cPanel

1. **Bitrix24 webhook.** Bitrix24 → *Developer resources → Other → Inbound webhook*. Give it **only the `crm` scope**. Copy the URL.
2. **Upload** the *contents* of `app/` to e.g. `public_html/pranav-dashboard/` (File Manager → Upload, or Git deployment). Enable *Show hidden files* so `.htaccess` is uploaded.
3. **Create the config.** Copy `config.sample.php` to `config.php` in the same folder and fill in the webhook URL, admin username and a **long unique password**. Add team logins if you need them.
4. Open `https://your-domain/pranav-dashboard/`, sign in, click **Refresh data** the first time.
5. *(Optional)* cron every 15–30 min: `php /home/USER/public_html/pranav-dashboard/refresh.php`.

### Verify it is locked down (do this once)
Each of these must **not** show data: `/cache/` → 403, `/config.php` → 403 or blank, `/lib.php` → 403 or blank.
Delete any test or debug scripts you may have uploaded (e.g. `checklogin.php`).

### Custom domain (optional)
cPanel → *Domains* → add the domain as an addon domain with document root `public_html/pranav-dashboard`, point DNS at the host, run AutoSSL.
`.htaccess` redirects `/index.php` to the folder URL. The `#/` in the address is the app's hash routing.

---

## Configuration reference (`app/config.php`)

| Key | Required | Meaning |
|---|---|---|
| `webhook_url` | yes | Bitrix24 inbound webhook, with trailing slash. |
| `username`, `password` | yes | Main admin login. |
| `extra_users` | no | `array('user' => 'password')` more admins. |
| `team_users` | no | `array('user' => array('password' => '…', 'team' => 'Team Name'))`, scoped to one team. |
| `pipeline_id` | no | Bitrix24 category to show (default `46`). |
| `allow_edit` | no | `false` makes the dashboard read-only. |
| `stale_days` | no | Days without a comment before a deal counts as follow-up overdue (default `15`). |
| `closed_stages` | no | Stage IDs treated as closed (default: guessed from stage names). |
| `editable_extra` | no | Extra custom-field IDs allowed in the edit form. |
| `cache_dir` | no | Where cache files live. Prefer a folder **outside** `public_html`. |

Custom-field IDs used by the app (University `UF_CRM_1784702446`, Program, Batch, Semester statuses, payment dates, …) are listed at the top of `app/lib.php`. Change them there if your portal uses different fields.

---

## Business rules worth knowing

- **Follow-up overdue:** active deal with comments whose latest comment is `stale_days`+ days old (or undated). Closed and alumni deals are excluded.
- **Not contacted:** active deal with no comment at all.
- **Re Reg %:** only *Active* students count. **Done** = today falls between *Last Payment Date (P)* and *Next Payment Date (P)*. **Pending** = no Next Payment Date, or today is past it. Students with **no Last Payment Date are not counted at all.**
- **Payment mode "Semester"** (Lumpsum / Annual / Semester) is different from *semester number* (Sem 1…6); the bot understands both.

---

## Security

- Keep `webhook_url` and passwords **only** in `app/config.php` (git-ignored). If a webhook is ever exposed, delete it in Bitrix24 and create a new one. Creating a new one alone does not revoke the old one.
- Use long, unique passwords. Weak or reused passwords are the most likely way in, because login pages get scanned by bots automatically.
- Scope the webhook to `crm` only.
- `.htaccess` denies `config.php`, `lib.php`, and the `cache/` folder; verify after every deploy.
- Writes are POST-only with a per-session CSRF token; sessions are regenerated on login; login failures are delayed.
- Anyone with a team login can only read/edit that team's deals; the server enforces this, not just the UI.

---

## Development

Requirements: PHP 7.4+, Python 3, Node 18+ (tests only).

```bash
tools/dev.sh                          # fake Bitrix24 + dashboard on http://127.0.0.1:8080  (admin / S3cret-Pass-123)

# edit the bot, then re-embed it into the page
python3 tools/inject_bot.py

# bot tests (dev server must be running)
cd tests/bot && npm install && npx playwright install chromium
node run.js bt2_q.json                # prints each question with the bot's answer
```

The `.json` files in `tests/bot/` are question sets (counts, filters, edits, how-tos, regression cases).
The runner prints answers for review (diff against a previous run to catch regressions).

> Edit the bot in `src/monkey-bot/bot.js`, never directly inside the `<!--MONKEY-->` block of `app/index.php`: re-injecting overwrites it.

---

## Troubleshooting

| Symptom | Fix |
|---|---|
| "Setup needed" page | `config.php` missing or still has `PASTE_…` values. |
| Dashboard empty / "Could not load" | Webhook wrong, deleted, or missing `crm` scope. Test the URL + `crm.deal.list.json` in a browser. |
| Sign-out goes to the site home page | Old `.htaccess` redirect. Use the one in `app/.htaccess` and the current `index.php`. |
| Numbers differ from Bitrix24 | Click **Refresh data**. The dashboard shows a cached snapshot. |
| `500` after editing `.htaccess` | A directive clashes with the host: remove the `<FilesMatch>` block first, keep `<IfModule mod_rewrite.c>`. |

---

## Roadmap ideas
- Password hashing in `config.php` (currently plain text, protected by `.htaccess`).
- Optional history API routing (clean URLs without `#/`).
- Unit tests for `lib.php` validation rules.
