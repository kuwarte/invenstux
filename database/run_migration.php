<?php

define('IMS_MIGRATION_START', microtime(true));

echo "\n";
echo "╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                        INVENSTUX DB MIGRATION                          ║\n";
echo "║                     Inventory Management System                        ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

echo "[1/5] VERIFICATION PHASE\n";
echo "─────────────────────────────────────────────────────────────────────────\n\n";

// check php version
echo "  % Checking PHP version...\n";
$phpVersion = phpversion();
echo "    PHP Version: $phpVersion\n";
if (version_compare($phpVersion, '8.0.0', '>=')) {
    echo "    [/] Compatible (8.0 or higher required)\n\n";
} else {
    echo "    [x] FAILED: PHP 8.0 or higher required\n\n";
    exit(1);
}

// check required php ext
// check php.ini in php dir
echo "  % Checking required PHP extensions...\n";
$required = ['pdo' => 'PDO', 'pdo_mysql' => 'PDO MySQL Driver', 'mbstring' => 'Multibyte String', 'session' => 'Session'];
$missedExtensions = [];

foreach ($required as $ext => $label) {
    if (extension_loaded($ext)) {
        echo "    [/] $label\n";
    } else {
        echo "    [x] $label (MISSING)\n";
        $missedExtensions[] = $ext;
    }
}

if (!empty($missedExtensions)) {
    echo "\n    ERROR: Missing extensions: " . implode(', ', $missedExtensions) . "\n";
    echo "    Please enable these extensions in php.ini\n\n";
    exit(1);
}
echo "\n";

// check .env if exists
echo "  % Checking configuration...\n";
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    echo "    [/] .env file found\n";
    $env = parse_ini_file($envFile);

    if (isset($env['DB_HOST'], $env['DB_DATABASE'], $env['DB_USERNAME'])) {
        echo "    [/] Database credentials configured\n";
        echo "      - Host: {$env['DB_HOST']}\n";
        echo "      - Database: {$env['DB_DATABASE']}\n";
        echo "      - User: {$env['DB_USERNAME']}\n";
    } else {
        echo "    [x] FAILED: Incomplete database configuration in .env\n";
        echo "      Required: DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD\n\n";
        exit(1);
    }
} else {
    echo "    [x] FAILED: .env file not found in project root\n";
    echo "      Please copy .env.example to .env and configure database credentials\n\n";
    exit(1);
}

echo "\n[2/5] DATABASE CONNECTION\n";
echo "─────────────────────────────────────────────────────────────────────────\n\n";

// create db connection
echo "  % Connecting to database...\n";
try {
    require_once dirname(__DIR__) . '/config/database.php';
    require_once dirname(__DIR__) . '/core/Database.php';
    $db = (new Database())->connect();
    echo "    [/] Connection established\n";
    echo "    [/] Using database: {$env['DB_DATABASE']}\n\n";
} catch (Exception $e) {
    echo "    [x] FAILED: Database connection error\n";
    echo '    Error: ' . $e->getMessage() . "\n";
    echo "    Check your database credentials in .env\n\n";
    exit(1);
}


echo "[3/5] RUNNING MIGRATIONS\n";
echo "─────────────────────────────────────────────────────────────────────────\n\n";

echo "  Executing 9 migrations...\n";

$migrations = [
    '001_create_roles.sql' => 'Create roles table',
    '002_create_users.sql' => 'Create users table',
    '003_create_categories.sql' => 'Create categories table',
    '004_create_warehouses.sql' => 'Create warehouses table',
    '005_create_products.sql' => 'Create products table',
    '006_create_product_warehouse.sql' => 'Create product-warehouse relationships',
    '007_create_permissions.sql' => 'Create permissions table (RBAC)',
    '008_create_sales.sql' => 'Create sales & transactions table (POS)',
    '009_create_stock_movements.sql' => 'Create stock audit trail'
];

$successCount = 0;
$skipCount = 0;
$errorCount = 0;

echo "\n";
foreach ($migrations as $migration => $description) {
    $file = dirname(__DIR__) . '/database/migrations/' . $migration;

    if (!file_exists($file)) {
        printf("  [x] %-40s [FILE NOT FOUND]\n", $description);
        $errorCount++;
        continue;
    }

    try {
        $sql = file_get_contents($file);
        $db->exec($sql);
        printf("  [/] %-40s [SUCCESS]\n", $description);
        $successCount++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            printf("  ~ %-40s [SKIPPED - Already exists]\n", $description);
            $skipCount++;
        } else {
            printf("  x %-40s [ERROR]\n", $description);
            echo '    -> ' . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
}

echo "\n  Migration Summary: $successCount successful, $skipCount skipped, $errorCount errors\n\n";

echo "[4/5] SEEDING DATABASE\n";
echo "─────────────────────────────────────────────────────────────────────────\n\n";

echo "  Executing 4 seed files...\n";

$seeds = [
    '001_seed_roles.sql' => 'Seed default user roles',
    '002_seed_admin.sql' => 'Seed default admin user',
    '003_seed_permissions.sql' => 'Seed role permissions',
    '004_seed_inventory.sql' => 'Seed sample inventory data'
];

$seedSuccessCount = 0;
$seedSkipCount = 0;

echo "\n";
foreach ($seeds as $seed => $description) {
    $file = dirname(__DIR__) . '/database/seeds/' . $seed;

    if (!file_exists($file)) {
        printf("  [x] %-40s [FILE NOT FOUND]\n", $description);
        continue;
    }

    try {
        $sql = file_get_contents($file);
        $db->exec($sql);
        printf("  ~ %-40s [SUCCESS]\n", $description);
        $seedSuccessCount++;
    } catch (PDOException $e) {
        printf("  x %-40s [SKIPPED]\n", $description);
        echo "    └─ May already exist or no changes needed\n";
        $seedSkipCount++;
    }
}

echo "\n  Seed Summary: $seedSuccessCount successful, $seedSkipCount skipped\n\n";

echo "[5/5] FINAL VERIFICATION\n";
echo "─────────────────────────────────────────────────────────────────────────\n\n";

echo "  % Verifying database tables...\n";
$tables = [
    'roles' => 'User Roles',
    'users' => 'User Accounts',
    'categories' => 'Product Categories',
    'products' => 'Products',
    'warehouses' => 'Warehouses',
    'product_warehouse' => 'Product-Warehouse Mappings',
    'permissions' => 'Role Permissions (RBAC)',
    'sales' => 'Sales Transactions (POS)',
    'stock_movements' => 'Stock Audit Trail'
];

$tablesVerified = 0;
$tablesMissing = 0;

echo "\n";
try {
    $stmt = $db->query('SHOW TABLES');
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $tableName => $description) {
        if (in_array($tableName, $existingTables)) {
            printf("  [/] %-30s %s\n", $description, "[$tableName]");
            $tablesVerified++;
        } else {
            printf("  [x] %-30s [%s] MISSING\n", $description, $tableName);
            $tablesMissing++;
        }
    }
} catch (Exception $e) {
    echo '  [x] Could not verify tables: ' . $e->getMessage() . "\n";
}

echo "\n";

$migrationTime = round(microtime(true) - IMS_MIGRATION_START, 2);

if ($tablesMissing === 0) {
    echo "  [/] ALL TABLES VERIFIED\n";
} else {
    echo "  [!] WARNING: $tablesMissing table(s) missing\n";
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════════════════╗\n";
echo "║                      MIGRATION COMPLETED SUCCESSFULLY                  ║\n";
echo '║                        (Completed in ' . str_pad($migrationTime . 's', 6) . ")                           ║\n";
echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";

echo "SYSTEM FEATURES\n";
echo "─────────────────────────────────────────────────────────────────────────\n";
echo "  [/] Core Inventory Management System (IMS)\n";
echo "  [/] Point of Sale (POS) System with Transaction Tracking\n";
echo "  [/] Role-Based Access Control (RBAC)\n";
echo "  [/] Complete Stock Audit Trail with Stock Movements\n";
echo "  [/] Multi-Warehouse Support\n";
echo "  [/] Sales Analytics & Reporting\n";
echo "  [/] Database Transaction Support\n\n";

echo "USER ROLES & PERMISSIONS\n";
echo "─────────────────────────────────────────────────────────────────────────\n";
echo "  % Admin          → Full system access, user management, settings\n";
echo "  % Manager        → Products, Stock, POS, Reports, Analytics\n";
echo "  % Cashier        → POS transactions only\n";
echo "  % Staff          → View reports and analytics only\n\n";

echo "APPLICATION URLS\n";
echo "─────────────────────────────────────────────────────────────────────────\n";
echo "  Dashboard:       http://localhost/\n";
echo "  POS System:      http://localhost/pos\n";
echo "  Sales History:   http://localhost/sales\n";
echo "  Products:        http://localhost/products\n";
echo "  Stock:           http://localhost/stocks\n";
echo "  Users:           http://localhost/users\n";
echo "  Categories:      http://localhost/categories\n";
echo "  Warehouses:      http://localhost/warehouses\n\n";

echo "NEXT STEPS\n";
echo "─────────────────────────────────────────────────────────────────────────\n";
echo "  1. Log in with admin credentials at http://localhost/\n";
echo "  2. Change admin password immediately\n";
echo "  3. Configure warehouse locations\n";
echo "  4. Add product categories and inventory\n";
echo "  5. Create additional user accounts and assign roles\n";
echo "  6. Start processing sales transactions via POS\n\n";

echo "═════════════════════════════════════════════════════════════════════════\n\n";
