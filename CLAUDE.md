# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Full setup (install deps, generate key, migrate, build assets)
composer run setup

# Development (Laravel serve + queue + pail logs + Vite, all concurrent)
composer run dev

# Run tests
composer run test

# Individual dev servers
php artisan serve
npm run dev
npm run build

# Database
php artisan migrate
php artisan migrate:fresh --seed
```

## Architecture

**Ricon** is a Laravel 13 event registration app for an ultra trail running race. Participants register publicly; admins approve/reject via a protected panel.

### Status Flow
`pending` → `payment_submitted` → `approved` / `rejected`

On approval, `Registration::assignBibNumber()` auto-assigns the next sequential bib within the race category, starting from `race_categories.bib_start_number`. The formatted bib (e.g. `100-001`) is via `getFormattedBibAttribute()`.

### Models & Key Fields
- **RaceCategory** — `slug`, `price`, `max_slots`, `bib_start_number`, `is_active`. Only active categories appear on the public registration form.
- **Registration** — UUID PK. Belongs to `RaceCategory` and has one `PaymentProof`. Status tracked via `status` column. `reviewed_by` FK to `users`.
- **PaymentProof** — image stored on AWS S3 (`payment_proofs/` directory), status: `pending/verified/rejected`.
- **User** — admin-only accounts. Role checked via `role === 'admin'` in `EnsureAdmin` middleware.

### Routes
- **Public:** `/`, `/rules`, `/about`, `/race-category/{slug}`, `/register` (GET/POST), `/register/success`
- **Admin** (`auth` + `EnsureAdmin` middleware, prefix `/admin`): dashboard, registrations index/show/approve/reject/bib/export, race-categories CRUD
- **Auth** (`routes/auth.php`): login at `/login`, registration at `/user-register`

### Controllers
- `RegistrationController` — public form create/store/success; `store()` uploads payment proof to S3
- `Admin\RegistrationController` — index (filterable by category/status/shirt_size/sex/age_group), approve/reject (sends email via `RegistrationApproved`/`RegistrationRejected` Mailables), CSV export
- `Admin\RaceCategoryController` — full CRUD
- `Admin\DashboardController` — aggregate stats

### Views & Frontend
- **Admin views** extend `layouts/admin.blade.php`
- **Public views** extend `layouts/public.blade.php`
- Use **PrelineUI** (CDN) + **Tailwind CSS v3** + **Alpine.js** for all UI
- Tailwind is configured in `tailwind.config.js` with Preline plugin; scans `resources/views/**/*.blade.php`
- Fonts: Figtree (body), Kufam (special headings)
- Public site uses dark theme with orange CTAs; admin uses gray/white theme

### Storage & Mail
- Payment proof images → AWS S3 (`s3` disk)
- Emails sent via **Resend** (`App\Mail\RegistrationApproved`, `App\Mail\RegistrationRejected`) using Markdown templates in `resources/views/emails/registration/`

### Database
- Default connection: SQLite (set `DB_CONNECTION` env for Postgres/MySQL)
- All primary keys are UUIDs
- `registrations.sex` column (renamed from `gender` in a migration — use `sex` everywhere)
