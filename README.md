# Social Library

Social Library is a modern web application built with Laravel designed to help users discover, read, and track books, while allowing authors to manage their content and admins to oversee the platform.

## System Modules & Specifications

### 1. User Roles & Authentication
- **User:** Can browse books, read, favorite books, rate/review books, track reading history, and view/edit profile.
- **Author:** In addition to user privileges, authors have a dedicated dashboard to manage their own books and view interactions (ratings/favorites).
- **Admin:** Has overarching control over the platform. Can manage users, authors, books, view global statistics, and oversee system records.

### 2. Core Modules

#### **Books**
- `Book` model: Represents the core entity. Contains metadata like title, description, cover image, and content.
- Authors can publish and manage books.
- Users can browse the book catalog.

#### **Reading History**
- `ReadingHistory` model: Tracks the progress of users as they read books.
- Saves current page / progress.
- Accessible via the user's dashboard.

#### **Favorites / Bookmarks**
- `Favorite` model: Allows users to save/bookmark books for later reading.
- Easily accessible via a dedicated "Saved" section.

#### **Ratings & Reviews**
- `Rating` model: Users can leave ratings and reviews on books they have read.
- Used to calculate average book ratings displayed on the platform.

#### **Donations**
- `Donation` model: System for managing user contributions or supporting authors/platform.

### 3. Tech Stack
- **Framework:** Laravel (PHP)
- **Frontend:** Tailwind CSS, Blade Templates, Vite (Asset bundling)
- **Database:** Relational Database integration via Eloquent ORM migrations and seeders.

## Getting Started

1. Clone the repository
2. Run `composer install` and `npm install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed` to generate the default schema and data
6. Build assets with `npm run build` or start dev server `npm run dev`
7. Start the Laravel application using `php artisan serve`
