<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Database.php';

$db = (new Database())->connect();

echo "DB SEEDING STARTS\n";
echo "==================\n\n";

$roles = $db->query("SELECT * FROM roles")->fetchAll();
$roleMap = [];
foreach ($roles as $role) {
    $roleMap[$role['name']] = $role['id'];
}

$accounts = [
    
    [
        'role' => 'admin',
        'username' => 'jaspercuarte',
        'email' => 'jasper.cuarte@invenstux.com',
        'full_name' => 'Jasper Cuarte',
        'password' => 'admin123'
    ],
    [
        'role' => 'manager',
        'username' => 'carlothomassevilla',
        'email' => 'carlo.sevilla@invenstux.com',
        'full_name' => 'Carlo Thomas Sevilla',
        'password' => 'manager123'
    ],
    [
        'role' => 'cashier',
        'username' => 'nathanielroque',
        'email' => 'nathaniel.roque@invenstux.com',
        'full_name' => 'Nathaniel Roque',
        'password' => 'cashier123'
    ],
    [
        'role' => 'staff',
        'username' => 'nathanbarrera',
        'email' => 'nathan.barrera@invenstux.com',
        'full_name' => 'Nathan Barrera',
        'password' => 'staff123'
    ],
    [
        'role' => 'staff',
        'username' => 'jonashpasia',
        'email' => 'jonash.pasia@invenstux.com',
        'full_name' => 'Jonash Pasia',
        'password' => 'staff123'
    ]
];

echo "Creating/Updating user accounts...\n\n";

foreach ($accounts as $account) {
    $roleId = $roleMap[$account['role']] ?? null;
    
    if (!$roleId) {
        echo "[x] Role '{$account['role']}' not found\n";
        continue;
    }
    
    $passwordHash = password_hash($account['password'], PASSWORD_BCRYPT);
    
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$account['email']]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $stmt = $db->prepare("
            UPDATE users 
            SET password_hash = ?, role_id = ?, full_name = ?, is_active = 1
            WHERE email = ?
        ");
        $stmt->execute([$passwordHash, $roleId, $account['full_name'], $account['email']]);
        echo "[/] Updated: {$account['email']}\n";
    } else {
        $stmt = $db->prepare("
            INSERT INTO users (role_id, username, email, password_hash, full_name, is_active)
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        $stmt->execute([
            $roleId,
            $account['username'],
            $account['email'],
            $passwordHash,
            $account['full_name']
        ]);
        echo "[/] Created: {$account['email']}\n";
    }
}

echo "\n=== Test Accounts Created ===\n\n";
echo "Login Credentials:\n";
echo "---------------------------------------------\n";
foreach ($accounts as $account) {
    echo "Role: " . strtoupper($account['role']) . "\n";
    echo "Email: {$account['email']}\n";
    echo "Password: {$account['password']}\n";
    echo "---------------------------------------------\n";
}

echo "\nAccess Levels:\n";
echo "- Admin: Full system access\n";
echo "- Manager: Products, Stock, POS, Reports, Warehouses, Categories\n";
echo "- Cashier: POS only\n";
echo "- Staff: View reports only\n\n";

echo "[/] Setup complete!\n";
echo "\nLogin at: http://localhost:8000/login\n";
