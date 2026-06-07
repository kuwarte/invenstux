# Invenstux

A web-based inventory management system built with vanilla PHP. Handles product catalogs, multi-warehouse stock control, point of sale, and sales reporting across role-based user accounts.

---

## Stack

- **Backend** — PHP 8.x, no framework
- **Database** — MySQL 8.x
- **Frontend** — Vanilla HTML/CSS/JS, Chart.js
- **Architecture** — MVC (Controllers, Services, Repositories)
- **Auth** — Session-based with role-permission system (RBAC)

---

## Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- A local server (PHP built-in server works fine)

---

## Setup

**1. Clone the repository**

```bash
git clone <repo-url>
cd invenstux
```

**2. Configure environment**

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

**3. Create the database**

```sql
CREATE DATABASE inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**4. Run migrations and seed data**

```bash
php database/run_migration.php
```

This runs migrations 001–009 in order, seeds roles, permissions, and 200 sample inventory items across 10 categories.

Migrations 010–013 (triggers, procedures, views, and indexes) must be applied manually through your MySQL client (phpMyAdmin, MySQL Workbench, TablePlus, or `mysql` CLI). The files are located in `database/migrations/`:

```
010_create_triggers.sql
011_create_procedures.sql
012_create_views.sql
013_create_indexes.sql
```

Apply them in that exact order. Example using the CLI:

```bash
mysql -u root -p inventory_db < database/migrations/010_create_triggers.sql
mysql -u root -p inventory_db < database/migrations/011_create_procedures.sql
mysql -u root -p inventory_db < database/migrations/012_create_views.sql
mysql -u root -p inventory_db < database/migrations/013_create_indexes.sql
```

Alternatively, open `database/schema/schema.sql` in your MySQL client and run the full file — it contains everything from 001 to 013 in a single script.

**5. Seed user accounts**

```bash
php database/seed_users.php
```

**6. Start the development server**

```bash
php -S localhost:8000 -t public
```

Open `http://localhost:8000` in your browser.

---

## Test Accounts

Visit `http://localhost:8000/test-accounts` to see all available credentials.

| Role | Email | Password |
|------|-------|----------|
| Admin | jasper.cuarte@invenstux.com | admin123 |
| Manager | carlo.sevilla@invenstux.com | manager123 |
| Cashier | nathaniel.roque@invenstux.com | cashier123 |
| Staff | nathan.barrera@invenstux.com | staff123 |
| Staff | jonash.pasia@invenstux.com | staff123 |

---

## Project Structure

```
invenstux/
├── app/
│   ├── Controllers/        Route handlers
│   ├── Services/           Business logic
│   ├── Repositories/       Database queries
│   └── Views/              PHP templates
├── config/
│   ├── config.php          App constants
│   └── database.php        DB connection config
├── core/
│   ├── Controller.php      Base controller
│   ├── Database.php        PDO wrapper
│   ├── Router.php          URL dispatcher
│   ├── Session.php         Session helper
│   └── Logger.php          File-based logger
├── database/
│   ├── migrations/         SQL table definitions (001–013)
│   ├── seeds/              Seed data (roles, permissions, inventory)
│   ├── schema/             Full combined schema.sql
│   ├── run_migration.php   Migration runner
│   └── seed_users.php      User account seeder
├── public/
│   ├── index.php           Entry point
│   └── assets/             CSS, JS
└── routes/
    └── web.php             All application routes
```

---

## Features

**Dashboard**
- KPI cards: total products, warehouses, orders, revenue
- Revenue vs target bar chart
- Top revenue products (pie chart + ranked table)
- Inventory risk assessment (low-stock items)
- Date range filter (today, 7 days, 30 days)
- Export dashboard data as CSV or JSON

**Products**
- Full product catalog with SKU, category, unit cost, unit of measure
- Active/inactive status with soft-delete protection (blocks delete if sales history exists)
- Category filter, search, show/hide inactive toggle

**Categories**
- Hierarchical nested categories (parent/child with tree display)
- Product count per category
- Search with recursive match

**Warehouses**
- Multi-location inventory management
- Manager assignment per warehouse
- Active/inactive toggle

**Stock Management**
- Stock In / Stock Out operations via stored procedure (`sp_adjust_stock`)
- Warehouse-to-warehouse transfer (`sp_transfer_stock`)
- Configurable min/max thresholds per product per warehouse
- Inventory ledger with warehouse, status, and search filters
- Low-stock and out-of-stock detection

**Point of Sale (POS)**
- Warehouse-scoped product catalog with category tabs
- Real-time stock availability per warehouse
- Cart with quantity controls, subtotals, and change calculation
- Atomic checkout via `sp_process_sale` stored procedure
- Receipt generation

**Sales History**
- Full transaction log with cashier, totals, payment, and change
- Filter by date range, cashier name, and minimum total
- Per-sale detail view and printable receipt

**Stock Audit Log** (`/audit`)
- Complete `stock_movements` history
- Movement types: IN, OUT, SALE, ADJUSTMENT, TRANSFER_IN, TRANSFER_OUT
- Filter by type, warehouse, product, and date range
- Sale movements link directly to the transaction record

**User Management**
- Create, edit, activate/deactivate, and delete users
- Role assignment (admin, manager, cashier, staff)
- Role-based permission gating on every route

---

## Roles and Permissions

| Permission | Admin | Manager | Staff | Cashier |
|---|:---:|:---:|:---:|:---:|
| manage_users | x | | | |
| manage_products | x | | x | |
| manage_categories | x | | x | |
| manage_stock | x | | x | |
| manage_warehouses | x | | | |
| access_pos | x | | | x |
| view_reports | x | x | | x |

Manager access is intentionally limited to `view_reports` only — dashboard, sales history, and stock audit log. All operational tasks (products, stock, warehouses, POS) are handled by staff and admin.

---

## Database Schema

The schema is fully defined in `database/schema/schema.sql` and covers:

- **Tables** — roles, users, categories, warehouses, products, product_warehouse, permissions, role_permissions, sales, sale_items, stock_movements
- **Triggers** — pre-insert sale validation, post-insert stock decrement and movement logging, negative stock guard, hard-delete block on products with sales history
- **Stored Procedures** — `sp_process_sale`, `sp_adjust_stock`, `sp_transfer_stock`
- **Views** — `vw_product_performance`, `vw_sales_dashboard`, `vw_dashboard_sales_stream`, `vw_low_stock_alert`, `vw_stock_movements_detailed`, `vw_warehouse_stock_summary`, `vw_daily_sales_summary`, `vw_dashboard_global_counters`
- **Indexes** — covering indexes on foreign keys, date columns, and frequently filtered columns

---

## Resetting the Database

To re-run migrations and re-seed from scratch:

```bash
php database/run_migration.php
php database/seed_users.php
```

The migration runner is idempotent — it skips tables that already exist and drops/recreates triggers, procedures, and views on each run.

---

## Development Notes

- The entry point is `public/index.php`. All requests are routed through `.htaccess` rewrites to this file.
- Controllers call services, services call repositories. Raw SQL lives only in repositories and the `database/` folder.
- The `core/Logger.php` writes to `logs/` — check there for procedure call traces and errors.
- `APP_DEBUG=true` in `.env` enables error display. Set to `false` for any shared environment.
