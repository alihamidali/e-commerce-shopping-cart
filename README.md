# E-Commerce Shopping Cart

A full-stack e-commerce shopping cart application built with Laravel and Vue.js following clean architecture principles and best practices.

## Tech Stack

### Backend
- **Laravel 12** - PHP framework
- **MySQL/SQLite** - Database
- **Laravel Breeze** - Authentication scaffolding
- **Repository Pattern** - Clean architecture
- **Service Layer** - Business logic separation
- **Jobs & Queues** - Asynchronous processing
- **Task Scheduling** - Automated reports

### Frontend
- **Vue 3** - Progressive JavaScript framework
- **TypeScript** - Type-safe JavaScript
- **Pinia** - State management
- **Tailwind CSS** - Utility-first CSS framework
- **Inertia.js** - Modern monolith approach

## Features

✅ User authentication (Laravel Breeze)
✅ Product browsing with stock information
✅ Shopping cart management (database-driven, user-specific)
✅ Add/update/remove cart items
✅ Order creation and history
✅ Low stock email notifications (Queue/Job)
✅ Daily sales reports (Scheduled task)
✅ Clean architecture with PSR standards
✅ Type-safe frontend with TypeScript
✅ Centralized state management with Pinia
✅ Custom Tailwind design system

## Architecture

### Backend (Clean Architecture)

Following Laravel best practices and PSR standards:

- **Form Requests** - Input validation (`app/Http/Requests/`)
- **Repository Pattern** - Data access layer (`app/Repositories/`)
- **Service Layer** - Business logic (`app/Services/`)
- **API Resources** - Response transformation (`app/Http/Resources/`)
- **Jobs** - Asynchronous tasks (`app/Jobs/`)
- **Commands** - Scheduled tasks (`app/Console/Commands/`)

### Frontend (Modern Vue 3 + TypeScript)

- **Pinia Stores** - Centralized state management (`resources/js/stores/`)
- **API Services** - Separated API logic (`resources/js/services/`)
- **TypeScript Interfaces** - Type definitions (`resources/js/types/`)
- **Composables** - Reusable logic (`resources/js/composables/`)
- **Tailwind Config** - Design system with custom tokens

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 20.x
- npm
- MySQL or SQLite

### Setup Steps

1. **Install dependencies**
   ```bash
   composer install
   npm install --legacy-peer-deps
   ```

2. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database in `.env`**
   ```
   DB_CONNECTION=sqlite
   ```

4. **Configure mail**
   ```
   MAIL_MAILER=log
   MAIL_ADMIN_EMAIL=admin@example.com
   ```

5. **Run migrations and seed database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   This creates:
   - Admin: admin@example.com (password: password)
   - User: user@example.com (password: password)
   - 10 sample products

6. **Build frontend**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

7. **Start server**
   ```bash
   php artisan serve
   ```

8. **Queue worker** (separate terminal)
   ```bash
   php artisan queue:work
   ```

9. **Scheduler** (for daily reports)
   ```bash
   php artisan schedule:work
   # or add to crontab:
   * * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1
   ```

## Usage

Visit `http://localhost:8000` and login with:
- Email: user@example.com
- Password: password

Navigate to:
- `/products` - Browse and add items to cart
- `/cart` - View and manage cart
- `/orders` - View order history

## API Endpoints

### Products
- `GET /api/products` - List products
- `POST /api/products` - Create product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

### Cart
- `GET /api/cart` - Get cart
- `POST /api/cart` - Add item
- `PATCH /api/cart/{id}` - Update quantity
- `DELETE /api/cart/{id}` - Remove item

### Orders
- `GET /api/orders` - List orders
- `POST /api/orders` - Create order from cart
- `GET /api/orders/{id}` - Order details

## Database Schema

**Products**: id, name, price, stock_quantity, timestamps
**Cart Items**: id, user_id, product_id, quantity, timestamps
**Orders**: id, user_id, total_amount, timestamps
**Order Items**: id, order_id, product_id, quantity, price, timestamps

## Key Files

### Backend
- `app/Services/` - Business logic
- `app/Repositories/` - Data layer
- `app/Http/Controllers/` - Controllers
- `app/Jobs/LowStockNotification.php` - Low stock alerts
- `app/Console/Commands/SendDailySalesReport.php` - Daily reports

### Frontend
- `resources/js/stores/` - Pinia stores
- `resources/js/services/api.ts` - API services
- `resources/js/types/index.ts` - TypeScript types
- `resources/js/Pages/` - Vue pages
- `tailwind.config.js` - Design system

## Design Tokens (Tailwind)

All colors, spacing, and fonts defined in `tailwind.config.js`:
- **Colors**: primary, secondary, success, warning, danger
- **Spacing**: 18, 22, 26, 30, 128, 144
- **Fonts**: xs to 5xl with line heights

## Testing

Manual testing for low stock:
1. Place order reducing stock to ≤10
2. Check `storage/logs/laravel.log`
3. View email in logs

## Code Standards

- PSR-12 coding standards
- Repository pattern
- Service layer architecture
- TypeScript type safety
- Clean code principles (DRY, SOLID)

## License

Open-sourced software.
