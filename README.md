# HotelManagement

**HotelManagement** is a full-featured hotel management web application built with **Laravel**.  
It includes a **public-facing hotel website** and a **secure internal dashboard** to manage rooms, reservations, services, and users.

This project was designed to simulate a real-world hotel management system, from customer experience to internal operations.

---

## 🏨 Project Overview

The application is divided into two main parts:

### 🌐 Public Website
- Hotel homepage with branding and hero section
- Rooms & Suites listing with pricing and availability
- Restaurant and services pages
- Contact page
- Custom logo and favicon integration
- Responsive design (desktop & mobile)

### 🔐 Admin Dashboard
- Secure authentication
- Dashboard access for hotel staff
- Room management
- Reservation management
- Service management
- User access control
- Sidebar navigation with branding

---

## 🛠️ Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Blade, Bootstrap 5
- **Database:** MySQL
- **Authentication:** Laravel Auth
- **Deployment:** ISPConfig / Apache / Nginx
- **Assets:** Custom logo & favicon

---

## 🚀 Getting Started

### Prerequisites

Make sure you have:
- PHP >= 8.1
- Composer
- MySQL
- Node.js & npm (optional, if using Vite)
- A local or remote web server

---

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yamdev07/HotelManagement.git
   cd HotelManagement
    ````

Install PHP dependencies
````
composer install
````

Create environment file
````
cp .env.example .env
````

Configure your environment
Update .env with your database and app settings:
````
APP_NAME="Hotel Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_management
DB_USERNAME=root
DB_PASSWORD=
````

Generate application key
````
php artisan key:generate
````

Run database migrations
````
php artisan migrate
````
Install npm 
````
npm install
````
Start the application
````
npm run dev
php artisan serve
````

Visit: http://localhost:8000

## 🌍 Production Deployment

This project is designed to be deployed in production environments such as ISPConfig, cPanel, or Plesk.

Deployment Notes

- Set the document root to the public/ directory

- Upload assets (logo, favicon) to public/

- Set correct permissions:

- Directories: 755

- Files: 644

- Disable debug mode in production:
  ````
  APP_DEBUG=false
  ````

Clear caches after deployment:
````
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
````
## 🎨 Logo & Favicon

The application uses a custom logo and favicon for branding.

To add or update the favicon:

1- Generate favicon files from your logo

2- Place them in the public/ directory

3- Reference them in the main layout <head>

Supported formats:
````
favicon.ico

favicon-16x16.png

favicon-32x32.png

apple-touch-icon.png
````
## 🧪 Testing (Planned)

Testing support will be added to improve reliability and code quality:

- Feature tests (authentication, reservations)

- Unit tests (business logic)

- CI integration (GitHub Actions)

## 🗺️ Roadmap

- [ ] Role-based permissions (Admin / Staff)

- [ ] Automated tests

- [ ] REST API for mobile apps

- [ ] Online booking & payment integration

- [ ] Multi-language support

## 👨‍💻 Author

**yamdev07**

GitHub: https://github.com/yamdev07

Role: Full-Stack Developer (Laravel)

## 📄 License

This project is open-source and available under the MIT License.
