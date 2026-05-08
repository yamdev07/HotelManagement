<div align="center">

# 🏨 Hotel Management System

**A production-grade hotel management platform built with Laravel.**
Reservations, check-in/out, payments, housekeeping, and a public website — all in one system.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)](LICENSE)
[![Lint](https://github.com/yamdev07/HotelManagement/actions/workflows/ci.yml/badge.svg)](https://github.com/yamdev07/HotelManagement/actions/workflows/ci.yml)
[![Tests](https://github.com/yamdev07/HotelManagement/actions/workflows/tests.yml/badge.svg)](https://github.com/yamdev07/HotelManagement/actions/workflows/tests.yml)

[🌐 Live Demo — lecactushotel.bj](https://lecactushotel.bj) · [🐛 Report a Bug](https://github.com/yamdev07/HotelManagement/issues) · [💡 Request Feature](https://github.com/yamdev07/HotelManagement/issues)

</div>

---

## 📋 Table of Contents

- [About](#-about)
- [Business Workflow](#-business-workflow)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [Getting Started](#-getting-started)
- [Production Deployment](#-production-deployment)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)
- [Author](#-author)

---

## 🚀 About

**Hotel Management System** is a full-featured, production-oriented platform currently live and used by **Cactus Hotel** ([lecactushotel.bj](https://lecactushotel.bj)).

It covers both the **public-facing hotel website** for customers and a **secured back-office dashboard** for hotel staff — handling the entire operational lifecycle from room search to checkout.

> ✅ Currently running in production. Not a demo project.

---

## 🔄 Business Workflow

The system models a real hotel front-desk process end-to-end:

```
Customer searches room
        ↓
Staff creates reservation
        ↓
Customer checks in
        ↓
Stay is tracked (housekeeping, restaurant, extras)
        ↓
Payment is recorded
        ↓
Customer checks out → Room status resets automatically
```

This workflow guarantees **room availability consistency** and full **operational traceability**.

---

## ✨ Features

### 🔐 Authentication & Access Control
- Secure login system with session protection
- Role-based staff access (Admin, Receptionist, Housekeeping)
- CSRF protection and input validation

### 🛏️ Room Management
- Room listing with categories and photo gallery
- Room status tracking: `available` · `reserved` · `occupied` · `maintenance`
- Dynamic pricing management

### 📅 Reservation Management
- Reservation creation, update, and cancellation
- Date-based availability checking with conflict prevention
- Full reservation history per customer

### 👤 Customer Management
- Customer registration and profile management
- Stay history and customer ↔ reservation linking
- Debt tracking and payment follow-up

### 🏠 Stay Operations
- Check-in and check-out workflows
- Automatic room status updates on each operation
- Housekeeping module for room readiness tracking

### 💳 Payments & Transactions
- Payment recording per stay
- Unpaid balance tracking
- Full transaction history with invoice generation

### 🌐 Public Hotel Website
- Hotel homepage, rooms & suites pages
- Services and contact pages
- Fully responsive layout (mobile-first)

---

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 10 (PHP 8.1+) |
| **Frontend** | Blade · Bootstrap 5 · JavaScript · Vite |
| **Database** | MySQL 8.0 |
| **Authentication** | Laravel Auth |
| **Containerization** | Docker + docker-compose |
| **CI/CD** | GitHub Actions (Lint + Tests) |
| **Server** | Nginx / Apache |

---

## 🏗️ Architecture

The application follows a strict **MVC pattern**:

```
app/
├── Models/          → Database entities (Room, Reservation, Customer, Payment)
├── Controllers/     → Business operations (one controller per domain)
└── Views/ (Blade)   → Staff dashboard + Public website

database/
├── migrations/      → Schema versioning
└── seeders/         → Demo & test data
```

> ⚠️ **Critical operations** (reservation creation, payment recording, check-in/out) are wrapped in **database transactions** to prevent inconsistent hotel states.

An **ERD diagram** is included in the repository root (`erd.PNG`).

---

## ⚙️ Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & npm
- Docker *(optional but recommended)*

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/yamdev07/HotelManagement.git
cd HotelManagement
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

> Edit `.env` with your database credentials:
> ```env
> DB_DATABASE=hotel_management
> DB_USERNAME=root
> DB_PASSWORD=your_password
> ```

**4. Run migrations**
```bash
php artisan migrate
```

**5. Seed demo data** *(optional)*
```bash
php artisan db:seed --class=DemoUserSeeder
```

**6. Start the application**
```bash
npm run dev
php artisan serve
```

> 🌐 Application available at **http://localhost:8000**

### 🐳 Docker Setup

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

### Demo Credentials

```
Email:    admin@hotel.test
Password: password123
```

> ⚠️ Change these credentials before any production deployment.

---

## 🚀 Production Deployment

**1. Set the web root to** `public/`

**2. Disable debug mode**
```env
APP_ENV=production
APP_DEBUG=false
```

**3. Optimize Laravel**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**4. Security checklist**
- [x] Authentication protection
- [x] Session management
- [x] CSRF protection
- [x] Input validation & SQL injection prevention
- [ ] Role-based authorization policies
- [ ] Login rate limiting
- [ ] Audit logs

---

## 🗺️ Roadmap

- [x] Room, reservation & customer management
- [x] Check-in / check-out workflows
- [x] Payment & transaction tracking
- [x] Public hotel website
- [x] Docker containerization
- [x] CI/CD pipeline (Lint + Tests)
- [x] Live production deployment (Cactus Hotel)
- [ ] Role-based permissions (Admin / Receptionist / Housekeeping)
- [ ] Automated test suite (PHPUnit)
- [ ] Online booking system
- [ ] Payment gateway integration (FedaPay / Stripe)
- [ ] REST API for mobile app
- [ ] Advanced reporting & analytics

---

## 🤝 Contributing

Contributions are welcome!

1. **Fork** the repository
2. **Create** your feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** your changes: `git commit -m 'feat: add amazing feature'`
4. **Push** to your branch: `git push origin feature/amazing-feature`
5. **Open** a Pull Request

Please follow [conventional commits](https://www.conventionalcommits.org/) for commit messages.

See [CONTRIBUTING.md](CONTRIBUTING.md) for full guidelines.

---

## 📜 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

<div align="center">

**Yoann ADIGBONON**
*Full-Stack Developer · SaaS Architecture · Software Security*

[![GitHub](https://img.shields.io/badge/GitHub-yamdev07-181717?style=for-the-badge&logo=github)](https://github.com/yamdev07)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-yoann--adigbonon-0A66C2?style=for-the-badge&logo=linkedin)](https://linkedin.com/in/yoann-adigbonon)
[![Portfolio](https://img.shields.io/badge/Portfolio-yyamd.com-4F46E5?style=for-the-badge&logo=vercel)](https://yyamd.com)

</div>

---

<div align="center">
  <sub>Currently live in production at <a href="https://lecactushotel.bj">lecactushotel.bj</a> 🏨 · Built with ❤️ in Bénin 🇧🇯</sub>
</div>
