# MyREDCAM

UOSCC is a web platform that connects civil society organizations (OSCs, from the Portuguese *Organizações da Sociedade Civil*) with people who want to take part in their events and projects. The interface is in Brazilian Portuguese.

The platform has three kinds of users:

- **Visitors** browse events, projects, organizations and spaces without an account.
- **Supporters** create an account to register for activities, save favorites, request spaces and obtain participation certificates.
- **Organization managers** publish events, projects and spaces for their OSC and decide on space reservation requests.

## Features

### Public area (no account required)

| Feature | Description |
| --- | --- |
| Home page | Counters and featured cards for events, organizations and spaces, read from the database. |
| Events and projects | Listing with free-text search and filters (organization, type, region, date, kind, registration status). Filtering runs in the browser. |
| Activity detail | Description, organization, space, region, deadline, remaining spots and registration form. |
| Organizations | Directory and public profile with the organization's activities and spaces. |
| Spaces | Listing with filters (capacity, facilities, region) and a detail page with a reservation request form. |

### Supporter area

- Sign up, sign in and sign out.
- Profile with skills, interests and availability. The profile is reused for simplified registration.
- Simplified registration for open activities, without retyping contact data.
- Favorites: organizations and activities can be saved and removed with a second click.
- Confirmation of participation, which unlocks a certificate page for the activity.
- Follow-up of the supporter's own space reservation requests and their status.
- Notifications panel generated from favorites and profile interests.

### Organization area

- Register an organization together with its responsible account (created in one transaction).
- Organization profile with its activities, spaces and the number of pending reservation requests per space.
- Publish events and projects, and register spaces (with a new or existing location).
- Review reservation requests for each space: approve or reject.
- Create additional accounts bound to the organization (supporters or managers).

## Roles and permissions

Roles are defined in the `UserRole` enum.

| Role | Value | How it is assigned |
| --- | --- | --- |
| Visitor | `Visitor` | Database default. Browsing without signing in is the normal visitor experience. |
| Supporter | `Supporter` | Created through the supporter sign-up form. |
| Organization manager (*Responsável por OSC*) | `OrganizationManager` | Created through the organization registration form, or by another manager of the same organization. |

| Action | Guest | Supporter | Manager |
| --- | --- | --- | --- |
| Browse home, events, organizations, spaces | Yes | Yes | Yes |
| Register for an activity | Redirected to sign in or sign up | Yes | Yes |
| Request a space reservation | Redirected to sign in or sign up | Yes | Yes |
| Supporter dashboard | No | Yes | Yes |
| Create activity, space, organization record or user | No | No (403) | Yes |
| View reservation requests of a space | No | No (403) | Only for spaces of their own organization |
| Read an account page | No | Own account only | Own account and accounts of their organization |

Authorization is enforced in the form requests (`authorize()`), in controllers (`abort_unless(...)`) and by the `guest` and `auth` route middleware.

## Business rules

### Activity registration

An activity is *open* only when all of these are true. The rule lives in `App\Support\ActivityRegistration`.

1. The deadline has not passed. The deadline is `registration_deadline`, or `activity_date` when none is set. It ends at 23:59:59 of that day in the `America/Bahia` timezone.
2. Spots remain. The public pages and the simplified registration treat an empty `capacity` as 0 spots, so the activity is reported as full (`esgotada`). `Activity::isRegistrationOpen()` and `remainingSpots()` treat an empty capacity as unlimited, but the controllers do not use them.

Resulting status values are `aberta`, `encerrada` and `esgotada`.

Other rules:

- A guest who submits the form is redirected to sign in (if the e-mail already has an account) or to sign up (if not). The name and e-mail are pre-filled and the user returns to the activity page afterwards. Nothing is saved for the guest.
- The same e-mail cannot register twice for the same activity (public form).
- A supporter cannot register twice for the same activity (database unique index on `activity_id` + `user_id`).
- Simplified registration requires a profile with name and e-mail.
- Confirming participation sets `completed_at`. The certificate page returns 404 until then.

### Space reservations

- The requested date must be today or later, in the `America/Bahia` timezone.
- A request is created with status `Pending`.
- The public form refuses a date that already has an `Approved` reservation for that space.
- A manager can approve or reject only `Pending` requests. A decided request cannot be decided again.
- Approval runs in a database transaction with a row lock, so two managers cannot approve different requests for the same date.
- Rejecting leaves the date open for other requests.

### Dates and time zones

The Laravel application timezone is `UTC` (`config/app.php`). Registration deadlines, reservation dates and the supporter dashboard timestamps use `America/Bahia` explicitly.

## Architecture

The application is a server-rendered Laravel monolith. There is no JSON API.

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.3, Laravel 13 |
| Views | Blade templates and components |
| Styling | Tailwind CSS 4 |
| Bundler | Vite 8 with `laravel-vite-plugin` |
| Client script | Vanilla JavaScript (`resources/js/app.js`): listing filters and the mobile menu |
| Database | MySQL (default in `.env.example`); SQLite in memory for tests |
| Sessions, cache, queue | Database drivers |
| Tests | Pest 4 |
| Formatting | Laravel Pint |

### Directory layout

```text
app/
  Enums/                 ActivityType, FocusArea, ReservationStatus, UserRole
  Http/
    Controllers/         One controller per area (see Routes)
    Requests/            Form requests with validation and authorization
  Models/                Eloquent models
  Support/               ActivityRegistration, GuestAuthenticationRedirect, ViewPresenters
database/
  migrations/            Schema history
  factories/             Model factories
  seeders/               DatabaseSeeder (demo catalog)
resources/
  css/app.css            Tailwind entry point
  js/app.js              Filters and menu behavior
  views/                 Blade pages and components (names in Portuguese)
routes/web.php           All routes
tests/Feature/           Feature tests (Pest)
```

### Support classes

| Class | Purpose |
| --- | --- |
| `ViewPresenters` | Converts `Activity`, `Organization` and `Space` models into the arrays the public views consume. Keys are in Portuguese (`titulo`, `vagas`, `inscritos`, `prazo_inscricao`, and so on). |
| `ActivityRegistration` | Computes the registration status (`aberta`, `encerrada`, `esgotada`) from a presented activity. |
| `GuestAuthenticationRedirect` | Redirects a guest to sign in or sign up, remembers the return URL and pre-fills the form. |

## Data model

```text
Organization 1 ── n User
Organization 1 ── n Space            Space n ── 1 Location
Organization 1 ── n Activity         Activity n ── 1 Space (optional)
Organization n ── n Activity         (collaborators, pivot activity_organization)
Activity 1 ── n Registration         Registration n ── 1 User (optional)
Space 1 ── n SpaceReservation        SpaceReservation n ── 1 User (optional)
User 1 ── n SupporterFavorite        SupporterFavorite ── Organization | Activity (polymorphic)
```

### Class diagram


### Tables

**organizations**

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `name` | string | |
| `description` | text | |
| `contact_email`, `contact_phone` | string, nullable | |
| `social_media_links` | string, nullable | Either a JSON object of `label => url` or a single URL, shown as "Site". |
| `focus_area` | enum | See *Enumerations* |

**users**

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint | |
| `name`, `email` | string | `email` is unique |
| `password` | string, nullable | Hashed |
| `organization_id` | FK, nullable | `nullOnDelete` |
| `role` | enum | `Visitor` (default), `Supporter`, `OrganizationManager` |
| `supporter_skills`, `supporter_interests` | text, nullable | |
| `supporter_availability` | string(500), nullable | |

**locations**: `cep`, `neighbourhood`, `street`, `number` (integer).

**spaces**: `name`, `description`, `capacity`, `facilities` (JSON list), `location_id` (FK, cascade), `organization_id` (FK, cascade).

**activities**

| Column | Type | Notes |
| --- | --- | --- |
| `title`, `description` | string, text | |
| `activity_date` | date | |
| `space_id` | FK, nullable | `nullOnDelete` |
| `organization_id` | FK | Owner, cascade |
| `activity_type` | enum | `Event`, `Project` |
| `capacity` | integer, nullable | Empty means no spot limit is defined |
| `registration_deadline` | date, nullable | |

**activity_organization**: pivot with composite primary key (`activity_id`, `organization_id`).

**registrations**: `activity_id`, `user_id` (nullable), `participant_name`, `participant_contact`, `registered_at`, `completed_at` (nullable). Unique on (`activity_id`, `user_id`).

**space_reservations**: `space_id`, `user_id` (nullable), `requester_name`, `requester_contact`, `requested_date`, `purpose` (nullable), `status` (default `Pending`).

**supporter_favorites**: `user_id`, polymorphic `favoritable` (`favoritable_type`, `favoritable_id`). Unique per user and item.

**sessions**: standard Laravel database session table.

### Enumerations

| Enum | Cases |
| --- | --- |
| `ActivityType` | `Event`, `Project` |
| `ReservationStatus` | `Pending` (Pendente), `Approved` (Aprovada), `Rejected` (Recusada) |
| `UserRole` | `Visitor` (Visitante), `Supporter` (Apoiador), `OrganizationManager` (Responsável por OSC) |
| `FocusArea` | `EducationAndCitizenship`, `EnvironmentAndSustainability`, `HealthcareAndCommunityWellbeing`, `SocialAssistanceAndFamilySupport`, `HumanRightsAndSocialInclusion`, `CultureSportsAndLeisure` |

Each enum except `ActivityType` exposes a `label()` method that returns the Portuguese text shown in the interface.

## Routes

URLs are in Portuguese. All routes are in `routes/web.php`. A health check is available at `/up`.

### Public

| Method | URL | Name | Description |
| --- | --- | --- | --- |
| GET | `/` | `home` | Home page |
| GET | `/eventos` | `eventos` | Events and projects listing |
| GET | `/eventos/{id}` | `eventos.show` | Activity detail |
| POST | `/eventos/{id}/inscricao` | `eventos.inscricao` | Register for an activity |
| GET | `/organizacoes` | `organizacoes` | Organization directory |
| GET | `/organizacoes/{id}` | `organizacoes.show` | Organization profile |
| GET | `/espacos` | `espacos` | Spaces listing |
| GET | `/espacos/{id}` | `espacos.show` | Space detail |
| POST | `/espacos/{id}/reserva` | `espacos.reserva` | Request a reservation |

The `{id}` parameters accept numbers only.

### Guests only

| Method | URL | Name | Description |
| --- | --- | --- | --- |
| GET, POST | `/apoiadores/cadastro` | `apoiadores.cadastro`, `.store` | Supporter sign-up |
| GET | `/apoiadores/entrar` | `login` | Sign-in form |
| POST | `/apoiadores/entrar` | `apoiadores.login` | Sign in (managers go to their organization profile, others to the dashboard) |
| GET, POST | `/organizacoes/cadastro` | `organizacoes.cadastro`, `.store` | Organization and manager sign-up |

### Authenticated

| Method | URL | Name | Description |
| --- | --- | --- | --- |
| GET | `/apoiadores` | `apoiadores` | Supporter dashboard |
| GET | `/apoiadores/reservas` | `apoiadores.reservas` | The user's reservation requests |
| PUT | `/apoiadores/perfil` | `apoiadores.perfil` | Update profile |
| POST | `/apoiadores/favoritos/{type}/{id}` | `apoiadores.favoritos` | Toggle favorite |
| POST | `/apoiadores/inscricoes/{type}/{id}` | `apoiadores.inscricoes` | Simplified registration |
| POST | `/apoiadores/participacoes/{type}/{id}/confirmar` | `apoiadores.participacoes.confirmar` | Confirm participation |
| GET | `/apoiadores/certificados/{type}/{id}` | `apoiadores.certificados` | Certificate page |
| POST | `/apoiadores/sair` | `apoiadores.sair` | Sign out |
| GET | `/organizacoes/perfil` | `organizacoes.perfil` | Organization profile (managers only) |
| GET | `/eventos/cadastro` | `eventos.create` | New activity form (managers only) |
| GET | `/espacos/cadastro` | `espacos.create` | New space form (managers only) |
| GET | `/espacos/{id}/reservas` | `espacos.reservas` | Reservation requests of a space |
| POST | `/espacos/{id}/reservas/{reservation}/aprovar` | `espacos.reservas.aprovar` | Approve a request |
| POST | `/espacos/{id}/reservas/{reservation}/recusar` | `espacos.reservas.recusar` | Reject a request |
| GET | `/usuarios/{id}` | `users.show` | Account page |
| POST | `/usuarios` | `users.store` | Create an account for the manager's organization |

In the dashboard routes, `{type}` is `evento`, `projeto` or `osc` (favorites only). Any other value returns 404.

### Create endpoints that check the role in the request

`POST /eventos`, `POST /espacos` and `POST /organizacoes` sit outside the `auth` group, but their form requests allow only organization managers. Other callers receive 403.

## Validation reference

| Form | Field | Rules |
| --- | --- | --- |
| Organization registration | `name` | required, 3 to 255 characters |
| | `description` | required, at least 10 characters |
| | `contact_email` | optional, valid e-mail |
| | `contact_phone`, `social_media_links` | optional, up to 255 characters |
| | `focus_area` | required, a `FocusArea` value |
| | `manager_name` | required, 3 to 120 characters |
| | `manager_email` | required, valid, unique among users |
| | `password` | required, confirmed, at least 8 characters |
| Supporter sign-up | `name` | required, 3 to 120 characters |
| | `email` | required, valid, unique |
| | `password` | required, confirmed, at least 8 characters |
| Activity | `title` | required, 3 to 255 characters |
| | `description` | required, at least 10 characters |
| | `activity_date` | required, `Y-m-d` (Brazilian `d/m/Y` input is converted) |
| | `space_id` | optional, must exist |
| | `activity_type` | required, `Event` or `Project` |
| | `capacity` | optional, integer, at least 1 |
| | `registration_deadline` | optional, `Y-m-d`, not after `activity_date` |
| Space | `name` | required, 3 to 255 characters |
| | `description` | required, at least 10 characters |
| | `capacity` | required, integer, at least 1 |
| | `facilities.*` | optional list of strings, up to 255 characters each |
| | `location_id` | optional, must exist |
| | `cep`, `neighbourhood`, `street`, `number` | required when `location_id` is absent |
| Activity registration | `nome` | required, 3 to 120 characters |
| | `email` | required, valid e-mail |
| Space reservation | `nome` | required, 3 to 120 characters |
| | `email` | required, valid e-mail |
| | `data` | required, `Y-m-d`, today or later (Bahia time) |
| | `finalidade` | required, 10 to 1000 characters |
| Account (`users.store`) | `name`, `email` | required, up to 100 characters, e-mail unique |
| | `password` | required, confirmed, at least 8 characters |
| | `role` | `Supporter` or `OrganizationManager` |
| Supporter profile | `name` | required, 3 to 120 characters |
| | `email` | required, valid, unique except for the user |
| | `skills`, `interests` | optional, up to 1000 characters |
| | `availability` | optional, up to 500 characters |

## Getting started

### Requirements

- PHP 8.3 or later with the extensions Laravel requires
- Composer
- Node.js and npm
- MySQL (or change `DB_CONNECTION` in `.env`)

### Installation

```bash
cp .env.example .env
composer install
php artisan key:generate
```

Edit `.env` and set the database credentials. The example file uses `DB_CONNECTION=mysql` and `DB_DATABASE=redcam_db`. Uncomment and fill `DB_HOST`, `DB_PORT`, `DB_USERNAME` and `DB_PASSWORD` as needed. Then run:

```bash
php artisan migrate
php artisan db:seed      # optional: loads the demo catalog
npm install
npm run build
php artisan serve
```

The application is served at `http://localhost:8000`.

`composer run setup` runs the installation steps in one command (dependencies, `.env`, key, migrations, `npm install`, build).

### Development

```bash
composer run dev     # development processes defined by the project
npm run dev          # Vite dev server only
```

If a page fails with "Unable to locate file in Vite manifest", run `npm run build` or keep `npm run dev` running.

### Demo data

`php artisan db:seed` loads a fixed catalog so pages and tests render known values:

- Four organizations: Instituto Viver Melhor, Conecta Comunidade, Rede Verde Camaçari and Movimento Jovem UOSCC.
- Spaces with locations, each owned by an organization.
- Five activities (four events and one demonstration project) with registrations that leave some activities full or past their deadline.

The seeder creates no user accounts. Create a supporter through `/apoiadores/cadastro` and a manager through `/organizacoes/cadastro`.

### Configuration

| Variable | Default in `.env.example` | Notes |
| --- | --- | --- |
| `APP_NAME` | `Laravel` | Change to the product name |
| `APP_URL` | `http://localhost:8000` | |
| `APP_LOCALE` | `en` | The interface texts are written in Portuguese in the views |
| `DB_CONNECTION` | `mysql` | |
| `SESSION_DRIVER` | `database` | Requires the `sessions` table |
| `CACHE_STORE` | `database` | |
| `QUEUE_CONNECTION` | `database` | |
| `MAIL_MAILER` | `log` | Mail is written to the log. No mail is sent by the application code today. |

Do not commit the `.env` file. It holds credentials.

Feature tests are in `tests/Feature`:

| File | Covers |
| --- | --- |
| `AcceptanceCriteriaTest` | Creating an activity with or without capacity, public listing, filters by type and date, the four seeded organizations |
| `PublicDiscoveryTest` | Registration status in discovery, spaces discovery, public pages that need no account |
| `PublicActivityFormsTest` | Registration states, deadlines in Bahia time, reservation validation, guest redirects, 404s |
| `OrganizationProfilesTest` | Public organization directory and profiles, home counters, project labels |
| `OrganizationProfileTest` | Manager-only organization profile and space registration |
| `RoleAuthorizedStoreTest` | Role checks and validation for creating organizations, activities and spaces |
| `SpaceReservationManagementTest` | Approve and reject flow, date conflicts, authorization |
| `SupporterAuthenticationTest` | Sign-up, sign-in, sign-out, organization self-registration |
| `SupporterDashboardTest` | Profile, favorites, simplified registration, participation and certificate |
| `SupporterReservationsTest` | A supporter following their own reservation requests |
| `UserAccountTest` | Account page access and account creation |

Format code before committing:

```bash
vendor/bin/pint --dirty
```

## Known limitations

- `activity_date` is a date column, so the time shown for an activity is always `00:00`, even though the seeder passes a time.
- Notifications on the supporter dashboard are text generated on each page load. No e-mail or push notification is sent.
- The certificate is an HTML page. There is no PDF export.
- A guest's name and e-mail are not stored when they are redirected to sign up. They only pre-fill the sign-up form.
- `LocationController` is an empty scaffold and is not routed.
- `ActivitySeeder` is empty and is not called by `DatabaseSeeder`.
- Rejected or approved reservations cannot be cancelled by a manager after the decision.
