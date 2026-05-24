<?php
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = array_filter(explode('/', $uri));

$currentPage = $segments[0] ?? 'dashboard';
$pageTitle = ($currentPage === 'pos')
    ? 'POS'
    : ucfirst($currentPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/layouts.main.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/dashboard.index.css">
    <link rel="stylesheet" href="/assets/css/dashboard.top-revenue.css">
    <link rel="stylesheet" href="/assets/css/users.index.css">
    <script src="/assets/js/layouts.main.js" defer></script>

    <?php if ($currentPage === 'dashboard'): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/assets/js/dashboard.index.js" defer></script>
    <?php elseif ($currentPage === 'pos'): ?>
        <script src="/assets/js/sales.pos.js" defer></script>
    <?php elseif ($currentPage === 'sales'): ?>
        <script src="/assets/js/sales.index.js" defer></script>
    <?php elseif ($currentPage === 'categories'): ?>
        <script src="/assets/js/categories.index.js" defer></script>
    <?php elseif ($currentPage === 'products'): ?>
        <script src="/assets/js/products.index.js" defer></script>
    <?php elseif ($currentPage === 'warehouses'): ?>
        <script src="/assets/js/warehouses.index.js" defer></script>
    <?php elseif ($currentPage === 'stocks'): ?>
        <script src="/assets/js/stocks.index.js" defer></script>
    <?php endif; ?>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="app-container">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="/dashboard">Inven<span>stux</span></a>
        </div>

        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li>
                    <a href="/dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php
                    require_once __DIR__ . '/../../Services/AuthorizationService.php';
$authzService = new AuthorizationService($GLOBALS['db'] ?? null);
?>

                <?php if ($authzService->canAny(['manage_products', 'manage_stock', 'manage_warehouses', 'manage_categories', 'access_pos'])): ?>
                    <li class="menu-divider">Operations</li>
                    
                    <?php if ($authzService->can('access_pos')): ?>
                    <li>
                        <a href="/pos" class="<?= $currentPage === 'pos' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </span>
                            <span>Point of Sale</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($authzService->can('manage_products')): ?>
                    <li>
                        <a href="/products" class="<?= $currentPage === 'products' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
                            </span>
                            <span>Products</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($authzService->can('manage_categories')): ?>
                    <li>
                        <a href="/categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                            </span>
                            <span>Categories</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($authzService->can('manage_stock')): ?>
                    <li>
                        <a href="/stocks" class="<?= $currentPage === 'stocks' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </span>
                            <span>Stocks</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($authzService->can('manage_warehouses')): ?>
                    <li>
                        <a href="/warehouses" class="<?= $currentPage === 'warehouses' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </span>
                            <span>Warehouses</span>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($authzService->can('view_reports')): ?>
                    <li class="menu-divider">Reporting</li>
                    <li>
                        <a href="/sales" class="<?= $currentPage === 'sales' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                    <path d="M6 9h.01M18 9h.01M6 15h.01M18 15h.01"></path>
                                </svg>
                            </span>
                            <span>Sales History</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($authzService->can('manage_users')): ?>
                    <li class="menu-divider">Administration</li>
                    <li>
                        <a href="/users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </span>
                            <span>Users</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="user-dropdown" id="userDropdown">
                <a href="/users/settings" class="dropdown-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    Settings
                </a>
                <div class="dropdown-sep"></div>
                <form method="POST" action="/logout" style="margin: 0;">
                    <button type="submit" class="dropdown-item logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>

            <div class="user-btn" onclick="toggleUserMenu()">
                <div class="user-avatar"><?= strtoupper(substr(Session::get('user_name') ?? 'A', 0, 1)) ?></div>
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars(Session::get('user_name') ?? 'Admin') ?></span>
                    <span class="user-role"><span class="status-dot"></span> Online</span>
                </div>
                <svg class="user-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="hamburger" id="hamburgerBtn" onclick="openSidebar()" aria-label="Open menu">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="breadcrumb">
                    <span class="brand-crumb">Invenstux</span>
                    <span class="sep brand-crumb">›</span>
                    <span class="current">
                        <?= htmlspecialchars($pageTitle) ?>
                    </span>     
                </div>
            </div>
            <div class="topbar-right">
            </div>
        </div>

        <div class="page-content-wrap">
            <div class="page-content">
                <?php if ($err = Session::get('error')): ?>
                    <div class="alert alert-error">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?= htmlspecialchars($err);
                    Session::set('error', null); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($succ = Session::get('success')): ?>
                    <div class="alert alert-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><?= htmlspecialchars($succ);
                    Session::set('success', null); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($path)): ?>
                    <?php require_once __DIR__ . "/../{$path}.php"; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>

