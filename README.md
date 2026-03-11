# 📦 Premium Inventory Management System

A state-of-the-art, data-driven Inventory Management System designed with a focus on **visual excellence** and **performance**. Built with a robust **Laravel 11 API** and a modern, reactive **Vue 3 frontend**.

---

## ✨ Key Features

-   **📊 Advanced Analytics Dashboard**: Real-time insights with interactive charts and metrics.
-   **📈 Dynamic Reports**: Monthly revenue trends, category distribution, and top product performance.
-   **📦 Product & Stock Management**: Full CRUD operations with low-stock alerting system.
-   **🔄 Stock Movements**: Track every item entry and exit with detailed history.
-   **👥 Role-Based Access Control**: Secure access for Admins, Staff, and Cashiers.
-   **💹 Sales Tracking**: Comprehensive sales management with total revenue calculations.
-   **🎨 Premium UI**: Modern aesthetic featuring glassmorphism, smooth animations, and a sleek dark-friendly palette.

---

## 🛠️ Tech Stack

-   **Frontend**: [Vue 3](https://vuejs.org/), [Vite](https://vitejs.dev/), [Tailwind CSS](https://tailwindcss.com/), [Chart.js](https://www.chartjs.org/)
-   **Backend**: [Laravel 11](https://laravel.com/), [Sanctum (Auth)](https://laravel.com/docs/sanctum)
-   **Database**: SQLite (Configured for easy local development)
-   **API**: RESTful Architecture

---

## 🚀 Getting Started

### Prerequisites

-   **PHP** >= 8.2
-   **Composer**
-   **Node.js** & **npm**

### 1. Backend Setup (Laravel)

```bash
# Navigate to backend directory
cd backend

# Install PHP dependencies
composer install

# Initialize environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Set up the database (SQLite)
# Note: Ensure DB_CONNECTION=sqlite in .env
touch database/database.sqlite
php artisan migrate --seed

# Start the API server
php artisan serve
```
> The API will be accessible at: `http://localhost:8000`

### 2. Frontend Setup (Vue.js)

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Start the development server
npm run dev
```
> The application will be accessible at: `http://localhost:5173`

---

## 🔐 Default Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password123` |
| **Staff** | `staff@example.com` | `password123` |

---

## 📁 Project Structure

```text
├── backend/            # Laravel API Source
│   ├── app/            # Business Logic
│   ├── database/       # Migrations & Seeders
│   └── routes/         # API Endpoints
├── frontend/           # Vue.js Application
│   ├── src/            # Components, Views, Stores
│   └── public/         # Static Assets
└── README.md           # Documentation
```

---

## ⚖️ License

Developed as a high-performance inventory solution.
