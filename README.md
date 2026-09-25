# Visionyr

Laravel application for Visionyr — an AI Brand Builder. The public marketing site is built
from the static slicing in `../slicing-visionyr`; the CMS that powers it lives at `/webcms`.

**Current status:** the public site, member accounts, and the CMS are all built.

## Requirements

| | Version | Notes |
| --- | --- | --- |
| PHP | 8.3+ | Developed on 8.4. Needs `pdo_mysql`; `pdo_sqlite` is **not** used |
| Composer | 2.x | |
| MySQL | 5.7+ | Developed on 5.7.33 |
| Node | 20+ | Developed on 24 |

Laravel 13. On Windows this was built under Laragon, which serves the project at
`http://visionyr.test` automatically.

## Installation

### 1. Dependencies

```bash
composer install
npm install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and set the database block:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=visionyr
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Databases

Two are needed — the app database and a separate one for the test suite:

```sql
CREATE DATABASE visionyr          CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE visionyr_testing  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

The test database name is fixed in `phpunit.xml`. Tests run against MySQL rather than SQLite
because the CMS dashboard's sign-up trend uses `DATE_FORMAT`.

### 4. Migrate and seed

```bash
php artisan migrate --seed
```

This creates the first CMS admin and 12 sample members. **No default password is shipped** —
the seeder generates one and prints it once:

```
Created admin admin@visionyr.com
Password: k7Qm2xVp9Ltz4Rbd
Save it now — it will not be shown again.
```

Copy it before the output scrolls away, then sign in at `/webcms/login`.

To choose the credentials yourself, set them in `.env` before seeding:

```dotenv
SEED_ADMIN_EMAIL=you@example.com
SEED_ADMIN_PASSWORD=your-own-password
```

Re-running the seeder never changes an existing admin's password.

### 5. Build the front-end

```bash
npm run build     # production
npm run dev       # or leave running while developing
```

### 6. Blueprint generation (optional but recommended)

Without an OpenRouter key the app still works — blueprint generation falls back to a
deterministic template. To generate real blueprints, add your key to `.env`:

```dotenv
OPENROUTER_API_KEY=xxx
OPENROUTER_MODEL="openai/gpt-6-luna"
```

Then verify:

```bash
php artisan blueprint:check              # key, credits, model, schema support, est. cost
php artisan blueprint:check --generate   # generate one real blueprint end to end
```

See [Generation (OpenRouter)](#generation-openrouter) for choosing a model and what it costs.

### 7. Create a member to sign in with

Self-registration is switched off, so `/register` returns 404 and members are created by
staff. Sign in to `/webcms/members` as the admin above and add one, or from the console:

```bash
php artisan tinker
```

```php
App\Models\Member::create([
    'name' => 'Test Member',
    'email' => 'member@example.com',
    'phone' => '081234567890',
    'password' => 'xxx',
    'is_active' => true,
]);
```

Members need an account to reach `/create`.

## Running it

| | |
| --- | --- |
| Laragon | Already served at `http://visionyr.test` |
| Anywhere else | `php artisan serve` |

Check it worked: `/` is the marketing site, `/webcms/login` is the CMS.

## Deploying to shared hosting

Laravel expects the document root to be `public/`. Where a host cannot point it there — Hostinger
serves `public_html` and will not change it — use one of the two layouts below. Both use the same
front controller, `deploy/public_html/index.php`, which finds the application either way.

### Preferred: application outside the web root

```
domains/visionyr.co/
├── laravel/            app/ bootstrap/ config/ database/ resources/ routes/
│                       storage/ vendor/ artisan  .env
└── public_html/        ← document root
    ├── index.php       from deploy/public_html/
    ├── .htaccess       from public/
    ├── build/  favicon.ico  robots.txt
```

Nothing but the public assets is reachable over HTTP, even if `.htaccess` stops being applied.
Upload everything except `public/` into `laravel/`, then the contents of `public/` into
`public_html/`, replacing `index.php` with the one from `deploy/public_html/`.

### Fallback: everything in the web root

Only when the host will not allow a folder beside `public_html`. Upload the whole project into
`public_html/`, then add `deploy/root.htaccess` as `public_html/.htaccess`. It rewrites requests
into `public/` and refuses `.env`, `vendor/`, and the source outright.

This is defence in depth, not equivalent: if `.htaccess` is ever ignored, the protection goes
with it. Prefer the split layout where it is possible at all.

### What not to upload

`node_modules/`, `tests/`, `.git/`, and `.env` — set the environment on the server instead.
`vendor/` and `public/build/` must be uploaded, since shared hosting rarely runs `composer` or
`npm`; build them locally first with `composer install --no-dev --optimize-autoloader` and
`npm run build`.

### On the server

- `storage/` and `bootstrap/cache/` must be writable (755 is usually enough).
- Create `.env` from `.env.example`, set `APP_ENV=production`, `APP_DEBUG=false`, a fresh
  `APP_KEY`, the database credentials, and the OpenRouter key.
- **Without SSH** you cannot run `php artisan migrate`. Export the schema locally
  (`mysqldump --no-data visionyr`) plus the seeded admin row, and import through phpMyAdmin.
- Set `max_execution_time` comfortably above `OPENROUTER_TIMEOUT` — see the note under
  Generation below.

## Common setup problems

**`could not find driver (Connection: sqlite)`** — `.env` is still on the Laravel default.
Set `DB_CONNECTION=mysql` and the rest of the database block.

**Tests fail with an unknown database** — `visionyr_testing` has not been created. See step 3.

**Blueprints always say "Template" in the CMS** — generation is falling back. Run
`php artisan blueprint:check` to see why; the usual causes are a missing key, an account with
no credits, or a model that does not support structured outputs.

**Generation times out in production** — a blueprint can take 30-90 seconds. Raise PHP's
`max_execution_time` well above that, and keep `OPENROUTER_TIMEOUT` **below** it. That ordering
matters: whichever limit fires first decides whether the visitor gets a template or a 504.

**Styles look unstyled** — `npm run build` has not been run, or `public/build` is missing.

## The public site

| Route                  | What it does                                          |
| ---------------------- | ----------------------------------------------------- |
| `/`                    | Marketing home page                                   |
| `/create`              | Five-step discovery form — **requires an account**    |
| `/blueprint/{uuid}`    | A generated Brand Blueprint                           |
| `/register`            | Member sign-up — **off by default**, see below         |
| `/login`, `/logout`    | Member sign-in and sign-out                           |
| `/dashboard`           | Signed-in member's profile                            |
| `/contact`             | Contact form (name, email, phone, message)            |
| `/privacy`, `/terms`   | Legal documents                                       |

Home page copy for the repeating blocks — features, showcase brands, pricing plans, FAQs —
lives in `config/marketing.php`, so the section templates stay markup-only. That file is the
thing to move behind a CMS module when the content needs to be editable in the browser. It
also holds `sales_whatsapp`, the number "Talk to Sales" opens a chat with.

### Contact and legal pages

Contact enquiries are saved to `contact_messages` with a nullable `handled_at`, so a CMS
module can triage them later. Nothing emails them on yet — that is the next thing to add.

`/privacy` and `/terms` are both rendered by `LegalController` from `config/legal.php`. Each
section there becomes a heading, an anchor, and an entry in the sticky contents list. A body
is a list of blocks: a string is a paragraph, `['list' => [...]]` is a bullet list.

**The legal copy is a drafted starting point, not reviewed by a lawyer.** Have counsel check
both documents before launch.

### Motion

The home page animates as you scroll: sections and cards rise into view (staggered within a
grid), the dashboard meters fill and their figures count up, the header lifts, and the nav
underlines whichever section you are reading.

The contact form shows a pending button while it posts and counts characters as you type;
the legal pages carry a reading-progress bar, a contents list that tracks your position, and
a back-to-top button.

It is all additive. Mark an element `data-reveal` to have it rise in, and its container
`data-reveal-group` to stagger the children. Give a meter `data-bar` (or `data-bar-height`)
with `--bar-target` inline, a figure `data-count`, and wrap the panel in `data-figures` to
start them together. The hiding only applies once `.js` is on `<html>`, so with JavaScript
off the page renders complete and static, and everything is switched off under
`prefers-reduced-motion`.

### Monthly quota

Each member may generate `blueprint.monthly_quota` blueprints per calendar month (5 by
default, `BLUEPRINT_MONTHLY_QUOTA`). The counter lives on `members` as
`blueprint_quota_used` plus `blueprint_quota_period`, a "YYYY-MM" stamp: when the stamp no
longer matches the current month the count resets itself, so no scheduled job is involved.

`Member::blueprintQuota()` returns the limit, used, remaining, and reset date;
`hasBlueprintQuota()` gates `/create` (the form is replaced by an explanation, and the POST
returns 429) and `consumeBlueprintQuota()` is called once a blueprint is stored.

Staff can top someone up early from `/webcms/members` — the refresh button on each row calls
`Member::refreshBlueprintQuota()`, which zeroes the count and stamps
`blueprint_quota_refreshed_at`. The button is disabled when the allowance is already full.

### Generation (OpenRouter)

Generation goes through OpenRouter. Set these in `.env`:

```dotenv
OPENROUTER_API_KEY=xxx
OPENROUTER_MODEL="openai/gpt-6-luna"
OPENROUTER_TIMEOUT=180
OPENROUTER_MAX_TOKENS=16000
```

#### Choosing a model

Whatever you pick must support **structured outputs** — `blueprint:check` reports this. Without
it the response usually misses the schema and falls back.

Measured on the real task (one brief, Fragrance/Premium):

| Model | Result |
| --- | --- |
| `openai/gpt-6-luna` | Paid, ~$0.0018 per blueprint. Needs credits on the account. |
| `dots-studio/dots-3-note-preview:free` | Best free option: good copy, honest scores, 46-91s, succeeded 3 runs in 4 |
| `nvidia/nemotron-3-super-120b-a12b:free` | Works but ~2x slower (86s) and less grounded in the brief |
| `qwen/qwen3.8-27b:free` | Repeatedly 429s |
| `thinkingmachines/inkling:free` | 403 — agentic harnesses only, unusable here |

Free models are fine for development and unusable for production: they are slow, capped at 50
requests a day, and fail often enough that a real share of members would silently get the
template. Add credits before launch.

Reasoning models spend completion tokens thinking *before* they emit any JSON — one measured
4,533 reasoning tokens against 1,209 of output. `OPENROUTER_MAX_TOKENS` defaults to 16000 for
that reason; lowering it truncates the JSON mid-object.

Verify the setup at any time:

```bash
php artisan blueprint:check              # key, account credit, model, schema support, est. cost
php artisan blueprint:check --generate   # also generates one real blueprint (costs credits)
php artisan blueprint:check --model=x/y  # try a different model without editing .env
```

Three files do the work:

| File | Responsibility |
| --- | --- |
| `app/Services/Blueprint/BlueprintPrompt.php` | The system prompt — **edit this to change output quality** |
| `app/Services/Blueprint/BlueprintSchema.php` | The JSON schema sent to the model, and the re-check on the way back |
| `app/Services/Blueprint/OpenRouterBlueprintGenerator.php` | The call, and the fallback |

`BlueprintSchema` is the contract the result page renders. It is sent as a strict
`response_format` json_schema **and** re-validated on the response, because a provider that
ignores the schema must never reach a view.

**Keep `OPENROUTER_TIMEOUT` below the host's `max_execution_time`.** If PHP is killed first the
fallback never runs and the visitor gets a 504; if the HTTP client gives up first, the template
fills in and they still get a blueprint. Leave headroom for the rest of the request — on a host
allowing 300s, 180 is comfortable. A timeout is deliberately **not** retried, since a second
attempt would double the worst-case wall time; transient HTTP errors still are.

**Nothing hard-fails.** No API key, a provider outage, a timeout, malformed JSON, or a payload
that misses the schema all fall back to `BrandBlueprintGenerator` (the deterministic template)
so the founder still gets a result. Each blueprint records `generator`, `model`,
`generation_ms`, and `failure_reason`, so a fallback shows up in the CMS instead of passing
silently. Watch the `Brand Blueprints` module's "Fell back to template" counter.

Founder input is wrapped in `<brief>` tags and the system prompt states that the contents are
data, never instructions — the vision and audience fields are free text from the public web.

### How a blueprint gets made

`/create` is behind the `auth` middleware. A guest who clicks any "Generate My Brand
Blueprint" call to action is sent to `/login` with a line explaining why, and signing in —
or registering from there — returns them to `/create` rather than the dashboard. A finished
blueprint stays reachable by its link without an account, so it can still be shared.

`/create` posts to Laravel. `App\Services\BrandBlueprintGenerator` builds the blueprint,
`BrandBlueprint` stores it with the answers that produced it, and the result renders at its
own uuid URL that can be shared or revisited.

The generator is a deterministic template today. It is the one seam where real generation
belongs: replace the body of `generate()` and every caller, view, and already-stored
blueprint keeps working, because the array it returns is the contract the result page
renders. `BrandBlueprintGeneratorTest` pins that shape.

The form posts over `fetch` so the designed generating stage stays on screen, and the server
answers with the result URL instead of a redirect. The progress bar holds at 95% until the
response lands, so it will stretch honestly once generation actually takes time; today it is
instant, and `blueprint.minimum_ms` keeps the stage on screen long enough to read. Without
JavaScript the same form still works — every step is visible and it submits normally.

The discovery questions, the industry and price-position choices, and the generating steps
are all in `config/blueprint.php`. The store request validates the two choice fields against
that same config, so adding an option there is all it takes.

Members see their own blueprints on `/dashboard`; staff see all of them, with the answers that
produced each one, under `/webcms/brand-blueprints`.

Blueprints record a `member_id` for whoever generated them. It is nullable because guests
could generate blueprints before sign-in was required. `Member::brandBlueprints()` is the
inverse.

## The CMS

Everything under `/webcms` is the CMS. It is served to staff authenticated through the
`admin` guard.

| Route                      | What it does                                    |
| -------------------------- | ----------------------------------------------- |
| `/webcms/login`            | Sign in (rate limited to 5 attempts)            |
| `/webcms`                  | Dashboard — member counts and sign-up trend     |
| `/webcms/admin-users`      | Manage staff accounts                           |
| `/webcms/members`          | Manage members                                  |
| `/webcms/brand-blueprints` | Every generated blueprint, its answers and result |

Routes live in `routes/webcms.php` and are registered with the `webcms` prefix and
`webcms.` name prefix from `bootstrap/app.php`.

### Authentication

Two guards are configured in `config/auth.php`:

- **`admin`** — `AdminUser` model, `admin_users` table. Used by the CMS. Inactive admins
  cannot sign in, and an admin can neither delete nor deactivate their own account.
- **`web`** — `Member` model, `members` table. The default guard, used by the public site.
  Inactive members cannot sign in; `is_active` is passed as a credential so a deactivated
  account simply never matches and is told no more than a wrong password would tell it.

Both sign-in screens rate limit to five attempts per email and IP, and stamp `last_login_at`.

**Self-registration is currently switched off.** `config/features.php` holds the flag, and the
`feature:` middleware makes `/register` return 404 while it is off; the sign-in screen points
people at the contact form instead. Members are created by staff under `/webcms/members`.

To bring it back, set `MEMBER_REGISTRATION_ENABLED=true` — the screen, controller, and its
tests are all still in place. `RegistrationTest` turns the flag on so that path stays covered
either way.

### Modules

**Admin Users** — `name`, `email`, `password`, plus `is_active` and a `last_login_at` stamp.

**Members** — `name`, `email`, `phone`, `password` are mandatory, plus `is_active`,
`email_verified_at` and `last_login_at`. Further fields are expected; add them with a new
migration, to `#[Fillable]` on `App\Models\Member`, to the store/update form requests, and
to `resources/views/webcms/members/_form.blade.php`.

Both modules support search, pagination, and create/edit/delete. Passwords are optional on
edit — leaving the field blank keeps the existing one.

## Front-end

Tailwind CSS v4 through Vite. The design tokens from the slicing — navy / mint / steel, Inter
and Instrument Serif, the three shadows, the `grid-bg` utility — are defined once in
`resources/css/app.css` and shared by the site and the CMS. Fonts are self-hosted by the Vite
fonts plugin rather than fetched from Google.

Three JS entries, so neither side ships the other's code:

| Entry                   | Loaded by       | Contains                              |
| ----------------------- | --------------- | ------------------------------------- |
| `resources/js/app.js`   | everything      | Icons, footer year, password toggles  |
| `resources/js/site.js`  | public site     | FAQ accordion, discovery wizard       |
| `resources/js/cms.js`   | `/webcms`       | Sidebar, delete confirmations         |

Icons are tree-shaken: add one to both the import and the `icons` map in `app.js` before
using `data-lucide="..."` in a template. Lucide dropped its brand icons in v1, so the two
social marks are drawn in `resources/views/components/site/brand-icon.blade.php`.

Views are split by surface:

- `resources/views/site/` — public pages, with the home page's sections in `site/home/`
- `resources/views/components/site/` — public components
- `resources/views/webcms/` and `resources/views/components/webcms/` — the CMS

## Tests

```bash
php artisan test
```

Feature tests cover the home page, the blueprint flow end to end, member registration,
sign-in and the member dashboard, CMS login, the CMS dashboard, and both CRUD modules;
`BrandBlueprintGeneratorTest` covers the generator in isolation.

Tests run against MySQL rather than SQLite — see `phpunit.xml` — because the dashboard's
sign-up trend uses `DATE_FORMAT`. Create the `visionyr_testing` database once before running
them.

Two things worth knowing when writing more:

- Let `assertSee()` escape the expected value (its default) rather than passing `false`;
  Blade escapes apostrophes and ampersands in the output.
- In a Blade component, an HTML entity passed as a *prop* gets double-escaped and shows up
  as literal text. Pass the character itself, or pass it as an attribute instead.
- `overflow-hidden` on an ancestor silently breaks `position: sticky` in a descendant. The
  legal page's section wrapper deliberately does without it.
- A resource route's parameter name must match the controller argument or implicit binding
  silently injects an *empty* model instead of failing. `brand-blueprints` sets
  `->parameters(['brand-blueprints' => 'blueprint'])` for that reason.
