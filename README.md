# QAC Houston - Full-Stack Web Application

Quality Automotive Center (QAC) Houston - Auto repair and service business website.

## Tech Stack

- **Frontend:** Next.js 14 (App Router), TypeScript, Tailwind CSS
- **Backend:** Laravel 11 (API-only), MariaDB/MySQL
- **Auth:** Laravel Sanctum (token-based)
- **Admin Panel:** Filament PHP v5
- **Cache/Queue:** Redis

## Project Structure

```
qac/
├── frontend/          # Next.js application (port 3000)
│   ├── src/
│   │   ├── app/       # Pages (App Router)
│   │   ├── components/ # Reusable components
│   │   └── lib/       # API client library
│   ├── .env.local     # Frontend env variables
│   └── package.json
│
├── backend/           # Laravel API (port 8000)
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/V1/  # API controllers
│   │   │   ├── Requests/V1/          # Form requests
│   │   │   └── Resources/V1/         # API resources
│   │   ├── Models/                   # Eloquent models
│   │   └── Filament/Resources/       # Admin panel resources
│   ├── database/
│   │   ├── migrations/               # Database migrations
│   │   ├── factories/                # Model factories
│   │   └── seeders/                  # Database seeders
│   ├── routes/api.php                # API routes
│   └── .env                          # Backend env variables
│
└── README.md
```

## Local Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/MariaDB (included with XAMPP)
- Redis (optional - can use database fallback)

### Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Copy environment file (if not already done)
cp .env.example .env

# Configure database in .env:
# DB_CONNECTION=mariadb
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=qac_houston
# DB_USERNAME=root
# DB_PASSWORD=

# Create database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS qac_houston CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# Run migrations and seeders
php artisan migrate --seed

# Create Filament admin user (if not already created)
php artisan make:filament-user

# Start the development server
php artisan serve --host=127.0.0.1 --port=8000
```

The API will be available at `http://localhost:8000/api/v1/`
Filament admin panel at `http://localhost:8000/admin`

### Frontend Setup

```bash
cd frontend

# Install dependencies
npm install

# Configure environment (already set up)
# Edit .env.local if needed:
# NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
# NEXT_PUBLIC_SITE_URL=http://localhost:3000

# Start development server
npm run dev
```

The website will be available at `http://localhost:3000`

### Default Admin Credentials

- **Email:** admin@qachouston.com
- **Password:** password

## API Endpoints

### Public Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/services` | List all services |
| GET | `/api/v1/services/{id}` | Get service details |
| GET | `/api/v1/reviews` | List approved reviews |
| GET | `/api/v1/faqs` | List FAQs |
| GET | `/api/v1/vendors` | List vendors |
| GET | `/api/v1/testimonials` | List testimonials |
| POST | `/api/v1/appointments` | Create appointment |
| POST | `/api/v1/fleet-inquiries` | Submit fleet inquiry |
| POST | `/api/v1/ppi-requests` | Submit PPI request |
| POST | `/api/v1/leads` | Submit lead |
| POST | `/api/v1/contact` | Submit contact form |

### Authenticated Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Login (returns token) |
| POST | `/api/v1/auth/logout` | Logout (revokes token) |
| GET | `/api/v1/auth/me` | Get current user |
| GET/POST/PUT/DELETE | `/api/v1/customers` | Customer CRUD |
| GET/POST/PUT/DELETE | `/api/v1/vehicles` | Vehicle CRUD |
| GET | `/api/v1/appointments` | List appointments |
| GET | `/api/v1/appointments/{id}` | Get appointment |
| PUT | `/api/v1/appointments/{id}` | Update appointment |
| DELETE | `/api/v1/appointments/{id}` | Delete appointment |

## Color Palette

- **Primary Dark:** `#313639` (QAC charcoal)
- **Secondary Dark:** `#33373A`, `#2F3739`
- **Accent:** `#FF9000` (Amber)
- **Light Tints:** `#FFF9C5`, `#FFF7C4`

## Database Schema

- **customers** - Customer information
- **vehicles** - Customer vehicles (FK - customers)
- **services** - Service offerings (categorized: electrical, mechanical, special)
- **appointments** - Service appointments (FK - customers, vehicles, services)
- **memberships** - Loyalty membership programs (FK - customers)
- **fleet_inquiries** - Fleet service inquiries
- **ppi_requests** - Pre-purchase inspection requests
- **reviews** - Customer reviews
- **leads** - Lead tracking from various sources
- **faqs** - Frequently asked questions
- **vendors** - Vendor/partner information
- **testimonials** - Customer testimonials
