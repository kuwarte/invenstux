<?php
define('INVESTUX_MIGRATION_START', microtime(true));

echo "MIGRATION STARTS\n";
echo "==================\n\n";

$envFile = dirname(__DIR__) . '/.env';
if (!file_exists($envFile)) {
    die("ERROR: .env file not found. Copy .env.example to .env and configure.\n");
}

$env = parse_ini_file($envFile);
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';

try {
    $pdo = (new Database())->connect();
    echo "[/] Connected to database: {$env['DB_DATABASE']}\n\n";
} catch (Exception $e) {
    die("ERROR: Database connection failed - " . $e->getMessage() . "\n");
}

$migrations = [
    '001_create_roles.sql' => 'Roles table',
    '002_create_users.sql' => 'Users table',
    '003_create_categories.sql' => 'Categories table',
    '004_create_warehouses.sql' => 'Warehouses table',
    '005_create_products.sql' => 'Products table',
    '006_create_product_warehouse.sql' => 'Product-Warehouse relationships',
    '007_create_permissions.sql' => 'Permissions & RBAC',
    '008_create_sales.sql' => 'Sales & POS tables (3NF)',
    '009_create_stock_movements.sql' => 'Stock audit trail',
];

echo "RUNNING MIGRATIONS\n";
echo "-----------------------------------------------------------------------\n\n";

$success = 0;
$skipped = 0;
$errors = 0;

foreach ($migrations as $file => $description) {
    $path = dirname(__DIR__) . '/database/migrations/' . $file;
    
    if (!file_exists($path)) {
        printf("  [x] %-35s [FILE NOT FOUND]\n", $description);
        $errors++;
        continue;
    }
    
    try {
        $sql = file_get_contents($path);
        
        if (strpos($file, 'procedure') !== false || strpos($file, 'trigger') !== false) {
            $mysqli = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_DATABASE']);

            if ($mysqli->connect_error) {
                throw new Exception($mysqli->connect_error);
            }

            // Strip DELIMITER directives and split on // which is the actual statement terminator
            $sql = preg_replace('/--[^\n]*$/m', '', $sql);
            $sql = str_replace('DELIMITER //', '', $sql);
            $sql = str_replace('DELIMITER ;', '', $sql);

            // Split on // to get individual CREATE TRIGGER / CREATE PROCEDURE blocks
            $blocks = array_filter(array_map('trim', explode('//', $sql)));

            foreach ($blocks as $block) {
                if (empty($block)) continue;

                // Drop existing before recreating
                if (preg_match('/CREATE\s+(?:DEFINER\s*=\s*\S+\s+)?TRIGGER\s+(\w+)/i', $block, $m)) {
                    $mysqli->query("DROP TRIGGER IF EXISTS `{$m[1]}`");
                } elseif (preg_match('/CREATE\s+(?:DEFINER\s*=\s*\S+\s+)?PROCEDURE\s+(\w+)/i', $block, $m)) {
                    $mysqli->query("DROP PROCEDURE IF EXISTS `{$m[1]}`");
                }

                if (!$mysqli->query($block)) {
                    throw new Exception($mysqli->error . "\n\nFailed block:\n" . substr($block, 0, 300));
                }
            }

            $mysqli->close();
        } 
        else if (strpos($file, 'view') !== false) {
            $sql = preg_replace('/--[^\n]*$/m', '', $sql);

            // Extract each CREATE VIEW block by splitting on semicolons,
            // then reassemble — views don't use DELIMITER so ; is the terminator.
            // We drop-and-recreate each view for idempotency.
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if (empty($statement)) continue;

                // Drop the view before recreating it
                if (preg_match('/CREATE\s+(?:OR\s+REPLACE\s+)?VIEW\s+(\w+)/i', $statement, $m)) {
                    $pdo->exec("DROP VIEW IF EXISTS `{$m[1]}`");
                }

                $pdo->exec($statement);
            }
        }
        else {
            $sql = preg_replace('/--[^\n]*$/m', '', $sql);
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
        }
        
        printf("  [/] %-35s [SUCCESS]\n", $description);
        $success++;
        
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false ||
            strpos($e->getMessage(), 'Duplicate key name') !== false) {
            printf("  [-] %-35s [SKIPPED]\n", $description);
            $skipped++;
        } else {
            printf("  [x] %-35s [ERROR]\n", $description);
            echo "    > " . substr($e->getMessage(), 0, 100) . "...\n";
            $errors++;
        }
    }
}

echo "\n";
echo "Summary: $success successful, $skipped skipped, $errors errors\n\n";

echo "SEEDING DATABASE\n";
echo "-----------------------------------------------------------------------\n\n";

$seeds = [
    '001_seed_roles.sql' => 'Default roles',
    '002_seed_permissions.sql' => 'Permissions',
    '003_seed_inventory.sql' => 'Sample data'
];

foreach ($seeds as $file => $description) {
    $path = dirname(__DIR__) . '/database/seeds/' . $file;
    
    if (!file_exists($path)) {
        printf("  [x] %-35s [FILE NOT FOUND]\n", $description);
        continue;
    }
    
    try {
        $sql = file_get_contents($path);
        $sql = preg_replace('/--[^\n]*$/m', '', $sql);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        printf("  [/] %-35s [SUCCESS]\n", $description);
    } catch (Exception $e) {
        printf("  [x] %-35s [ERROR]\n", $description);
        echo "    > " . substr($e->getMessage(), 0, 100) . "\n";
    }
}

$time = round(microtime(true) - INVESTUX_MIGRATION_START, 2);

echo "\n";
echo "MIGRATION COMPLETE\n";
echo "==================\n\n";

echo "RUN USERS SEED:\n";
echo "  php database/seed_users.php\n\n";

echo "START SERVER:\n";
echo "  php -S localhost:8000 -t public\n\n";

echo "ACCESS:\n";
echo "  http://localhost:8000\n\n";
echo "Completed in {$time} seconds\n";
