# Hotel Management System

A production-oriented **Hotel Management Software** built with **Laravel** to manage real hotel operations: reservations, check-in/check-out, customers, rooms, and payments.

This application simulates the workflow of a real hotel front-desk and internal staff back-office.

It contains:
- a public hotel website for customers
- a secured internal management dashboard for hotel staff

![Lint](https://github.com/yamdev07/HotelManagement/actions/workflows/ci.yml/badge.svg)

---

## Business Workflow

The system models a real hotel process:

1. A customer searches a room
2. Staff creates a reservation
3. Customer checks-in
4. Stay is tracked
5. Payment is recorded
6. Transaction history is stored
7. Customer checks-out and room becomes available again

This workflow ensures room availability consistency and operational tracking.

---

## Main Modules

### Authentication & Access
- Secure login system
- Staff access control
- Session protection

### Room Management
- Room listing
- Room categories
- Room status (available / reserved / occupied / maintenance)
- Pricing management

### Reservation Management
- Reservation creation
- Reservation update & cancellation
- Date-based availability checking
- Reservation history

### Customer Management
- Customer registration
- Customer stay history
- Customer ↔ reservation linking

### Stay Operations
- Check-in
- Check-out
- Automatic room status updates

### Payments & Transactions
- Record payments
- Track unpaid balances
- Transaction history

### Public Website
- Hotel homepage
- Rooms & suites pages
- Services page
- Contact page
- Responsive layout

---

## Technical Stack

| Layer | Technology |
|------|------|
| Backend | Laravel (PHP) |
| Frontend | Blade + Bootstrap 5 |
| Database | MySQL |
| Build Tool | Vite |
| Authentication | Laravel Auth |
| Server | Apache / Nginx |

---

## Architecture

The application follows a classical MVC pattern:

- Models → database entities (rooms, reservations, customers, payments)
- Controllers → business operations
- Views → staff dashboard & public website

Critical operations (reservation & payment) should run inside database transactions to avoid inconsistent hotel states.

---
## Demo Access

After seeding the database:

```bash
php artisan db:seed --class=DemoUserSeeder

````

Login:

Email: admin@hotel.test

Password: password123

## Installation

### Requirements
- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & npm

### Setup

```bash
git clone https://github.com/yamdev07/HotelManagement.git
cd HotelManagement

composer install
npm install
```

Configure database in .env:
````
DB_DATABASE=hotel_management
DB_USERNAME=root
DB_PASSWORD=
````

Run migrations:
````
php artisan migrate
````

Run application:
````
npm run dev
php artisan serve
````

Visit:
````
http://localhost:8000
````
## Database

The database is relational and uses foreign keys between:

- [ ] rooms

- [ ] reservations

- [ ] customers

- [ ] payments

- [ ] users

An ERD diagram is included in the repository.

Recommended improvements:

- [ ] indexes on reservation dates

- [ ] transaction usage during check-in / payment

## Production Deployment

For production:

- [ ] Set web root → public/

Disable debug:
````
APP_ENV=production
APP_DEBUG=false
````

Optimize Laravel:
````
php artisan config:cache
php artisan route:cache
php artisan view:cache
````
Security Considerations

The application implements:

- [ ]authentication protection

- [ ] session management

- [ ] CSRF protection

- [ ] input validation

Recommended additions:

- [ ] role-based authorization policies

- [ ] rate limiting on login

- [ ] audit logs

## Roadmap

Planned improvements:

- Role-based permissions (Admin / Receptionist / Housekeeping)

- Automated tests (PHPUnit)

- Online booking system

- Payment gateway integration

- REST API for mobile app

- Reporting & analytics

## Author

Yoann Adigbonon
Laravel Developer
https://github.com/yamdev07

## License

MIT License

