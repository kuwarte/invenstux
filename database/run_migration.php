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
    '009_create_stock_movements.sql' => 'Stock audit trail'
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
            
            $sql = str_replace(['DELIMITER //', 'DELIMITER ;'], '', $sql);
            $sql = preg_replace('/--.*$/m', '', $sql); 
            
            $parts = preg_split('/(END\/\/|END;)/i', $sql);
            
            foreach ($parts as $idx => $part) {
                $part = trim($part);
                if (empty($part)) continue;
                
                if (stripos($part, 'DROP') !== false) {
                    $dropStmt = $part;
                    if (!preg_match('/;\s*$/', $dropStmt)) {
                        $dropStmt .= ';';
                    }
                    try {
                        $mysqli->query($dropStmt);
                    } catch (Exception $e) {
                    }
                    continue;
                }
                
                if (stripos($part, 'CREATE PROCEDURE') !== false || stripos($part, 'CREATE TRIGGER') !== false) {
                    $part .= ' END';
                    
                    if (!$mysqli->query($part)) {
                        throw new Exception($mysqli->error);
                    }
                }
            }
            
            $mysqli->close();
        } 
        else if (strpos($file, 'view') !== false) {
            $sql = preg_replace('/--.*$/m', '', $sql);
            
            $statements = explode(';', $sql);
            $currentView = '';
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement)) continue;
                
                if (stripos($statement, 'DROP VIEW') !== false) {
                    try {
                        $pdo->exec($statement);
                    } catch (Exception $e) {
                    }
                } else if (stripos($statement, 'CREATE VIEW') !== false) {
                    $currentView = $statement;
                } else if (!empty($currentView)) {
                    $currentView .= ';' . $statement;
                    
                    if (stripos($statement, 'ORDER BY') !== false || 
                        stripos($statement, 'GROUP BY') !== false ||
                        preg_match('/FROM\s+\w+\s*$/i', $statement)) {
                        $pdo->exec($currentView);
                        $currentView = '';
                    }
                }
            }
            
            if (!empty($currentView)) {
                $pdo->exec($currentView);
            }
        }
        else {
            $sql = preg_replace('/--.*$/m', '', $sql);
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
        if (strpos($e->getMessage(), 'already exists') !== false) {
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
        $pdo->exec($sql);
        printf("  [/] %-35s [SUCCESS]\n", $description);
    } catch (Exception $e) {
        printf("  [-] %-35s [SKIPPED]\n", $description);
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
