<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Accounts - Invenstux</title>
    <script src="/assets/js/auth.test-accounts.js" defer></script>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/auth.test-accounts.css">
</head>
<body>

    <header class="site-header">
        <div class="hero-wave-section">
            <div class="hero-content">
                <div class="badge">Staging Environment</div>
                <h1>Test Accounts</h1>
                <p>Use these mock credentials to test specific features and user permissions across the system. Click any value to copy it.</p>
            </div>
            
            <div class="wave-container">
                <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,64C1200,75,1320,85,1380,90.7L1440,96L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z" fill="var(--bg-color)"/>
                </svg>
            </div>
        </div>
    </header>
    <div class="page-body">
       <div class="cards-grid">

            <div class="role-card">
                <div class="card-top">
                    <div class="role-icon admin">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="role-details admin">
                        <div class="role-name">Admin</div>
                        <span class="access-badge">Full Root Access</span>
                    </div>
                </div>
                <div class="creds-box">
                    <div class="cred-row">
                        <span class="cred-label">Email</span>
                        <div class="cred-value" title="Click to copy">admin@example.com</div>
                    </div>
                    <div class="cred-row">
                        <span class="cred-label">Password</span>
                        <div class="cred-value" title="Click to copy">admin123</div>
                    </div>
                </div>
                <ul class="features-list">
                    <li>Manage system users</li>
                    <li>Full access to products, stocks, POS, and reports</li>
                    <li>Manage warehouses and product categories</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="card-top">
                    <div class="role-icon manager">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>                     </div>
                    <div class="role-details manager">
                        <div class="role-name">Manager</div>
                        <span class="access-badge">Write Permissions</span>
                    </div>
                </div>
                <div class="creds-box">
                    <div class="cred-row">
                        <span class="cred-label">Email</span>
                        <div class="cred-value" title="Click to copy">manager@example.com</div>
                    </div>
                    <div class="cred-row">
                        <span class="cred-label">Password</span>
                        <div class="cred-value" title="Click to copy">manager123</div>
                    </div>
                </div>
                <ul class="features-list">
                    <li>Create, edit, and delete products</li>
                    <li>Stock in/out operations and warehouse management</li>
                    <li>Access POS system, view reports, and handle categories</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="card-top">
                    <div class="role-icon cashier">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>                    </div>
                    <div class="role-details cashier">
                        <div class="role-name">Cashier</div>
                        <span class="access-badge">POS Permissions</span>
                    </div>
                </div>
                <div class="creds-box">
                    <div class="cred-row">
                        <span class="cred-label">Email</span>
                        <div class="cred-value" title="Click to copy">cashier@example.com</div>
                    </div>
                    <div class="cred-row">
                        <span class="cred-label">Password</span>
                        <div class="cred-value" title="Click to copy">cashier123</div>
                    </div>
                </div>
                <ul class="features-list">
                    <li>Access Point of Sale system</li>
                    <li>View sales and inventory reports</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="card-top">
                    <div class="role-icon staff">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="role-details staff">
                        <div class="role-name">Inventory Staff</div>
                        <span class="access-badge">Inventory Access</span>
                    </div>
                </div>
                <div class="creds-box">
                    <div class="cred-row">
                        <span class="cred-label">Email</span>
                        <div class="cred-value" title="Click to copy">staff@example.com</div>
                    </div>
                    <div class="cred-row">
                        <span class="cred-label">Password</span>
                        <div class="cred-value" title="Click to copy">staff123</div>
                    </div>
                </div>
                <ul class="features-list">
                    <li>Create, edit, and delete products</li>
                    <li>Stock in/out operations</li>
                    <li>Manage product categories</li>
                </ul>
            </div>

         </div>

        <div class="action-bar">
            <div class="system-note">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>To reset the database to its default state, run the seeder command:</span>
                <code>php database/seed_users.php</code>
            </div>
            <a href="/login" class="btn-action">
                Go to Login
            </a>
        </div>
    </div>
</body>
</html>
