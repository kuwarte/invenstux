<?php
$systemStats = $systemStats ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Invenstux</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/auth.login.css">
</head>
<body>

    <div class="mobile-brand">
        <div class="brand-logo">Inven<span>stux</span></div>
        <div class="mobile-tagline">Your stock, your sales, always in sync.</div>
    </div>

    <div class="left-panel">
        <svg class="bg-art" viewBox="0 0 640 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="glow1" cx="30%" cy="25%" r="55%">
                    <stop offset="0%" stop-color="#059669" stop-opacity="0.2"/> 
                    <stop offset="100%" stop-color="#064e3b" stop-opacity="0"/>
                </radialGradient>
                <radialGradient id="glow2" cx="80%" cy="75%" r="50%">
                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.12"/> 
                    <stop offset="100%" stop-color="#064e3b" stop-opacity="0"/>
                </radialGradient>
            </defs>
            <rect width="640" height="900" fill="#064e3b"/>
            <rect width="640" height="900" fill="url(#glow1)"/>
            <rect width="640" height="900" fill="url(#glow2)"/>

            <g stroke="#ffffff" stroke-opacity="0.04" stroke-width="1">
                <line x1="0" y1="150" x2="640" y2="150"/>
                <line x1="0" y1="300" x2="640" y2="300"/>
                <line x1="0" y1="450" x2="640" y2="450"/>
                <line x1="0" y1="600" x2="640" y2="600"/>
                <line x1="0" y1="750" x2="640" y2="750"/>
                <line x1="160" y1="0" x2="160" y2="900"/>
                <line x1="320" y1="0" x2="320" y2="900"/>
                <line x1="480" y1="0" x2="480" y2="900"/>
            </g>

            <g fill="#6ee7b7" fill-opacity="0.12">
                <circle cx="64" cy="64" r="2.5"/>
                <circle cx="128" cy="64" r="2.5"/>
                <circle cx="192" cy="64" r="2.5"/>
                <circle cx="256" cy="64" r="2.5"/>
                <circle cx="64" cy="128" r="2.5"/>
                <circle cx="128" cy="128" r="2.5"/>
                <circle cx="192" cy="128" r="2.5"/>
                <circle cx="256" cy="128" r="2.5"/>
                <circle cx="64" cy="192" r="2.5"/>
                <circle cx="128" cy="192" r="2.5"/>
                <circle cx="192" cy="192" r="2.5"/>
            </g>

            <g fill="#10b981" fill-opacity="0.09">
                <rect x="80" y="790" width="44" height="44" rx="5"/>
                <rect x="134" y="790" width="44" height="44" rx="5"/>
                <rect x="188" y="790" width="44" height="44" rx="5"/>
                <rect x="242" y="790" width="44" height="44" rx="5"/>
            </g>
            <g fill="#10b981" fill-opacity="0.06">
                <rect x="80" y="736" width="44" height="44" rx="5"/>
                <rect x="134" y="736" width="44" height="44" rx="5"/>
                <rect x="188" y="736" width="44" height="44" rx="5"/>
            </g>
            <g fill="#6ee7b7" fill-opacity="0.12">
                <rect x="80" y="682" width="44" height="44" rx="5"/>
                <rect x="134" y="682" width="44" height="44" rx="5"/>
            </g>
            <g stroke="#6ee7b7" stroke-opacity="0.12" stroke-width="1">
                <line x1="68" y1="840" x2="310" y2="840"/>
                <line x1="68" y1="786" x2="310" y2="786"/>
                <line x1="68" y1="732" x2="310" y2="732"/>
                <line x1="68" y1="678" x2="310" y2="678"/>
            </g>
            <line x1="68" y1="812" x2="296" y2="812" stroke="#10b981" stroke-opacity="0.3" stroke-width="1.5" stroke-dasharray="4 4"/>

            <path d="M0,0 L180,0 L0,140 Z" fill="#10b981" fill-opacity="0.06"/>
        </svg>

        <div class="panel-content">
            <div class="brand-logo">Inven<span>stux</span></div>

            <div class="panel-headline">
                <div class="eyebrow">Business Operations</div>
                <h2>Every item.<br> Every sale.<br> Always in control.</h2>
                <p>From the shelf to the counter — keep your stock accurate, your transactions fast, and your business moving.</p>
            </div>

            <div class="panel-stats">
                <div class="stat-item">
                    <div class="stat-num"><?= $systemStats['warehouses']; ?></div>
                    <div class="stat-label">Warehouses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?= htmlspecialchars($systemStats['uptime']); ?></div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?= number_format($systemStats['transactions']); ?></div>
                    <div class="stat-label">Transactions</div>
                </div>
            </div>
        </div>
        <div class="panel-wave-divider">
            <svg class="desktop-wave" viewBox="0 0 100 900" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M40,0 
                         C90,180 15,360 70,540 
                         C110,720 20,810 50,900 
                         L100,900 L100,0 Z" fill="#ffffff"/>
            </svg>

            <svg class="mobile-wave" viewBox="0 0 1440 74" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,24 C240,70 480,75 720,48 C960,20 1200,-10 1440,5 L1440,74 L0,74 Z" fill="#ffffff"/>
            </svg>
        </div>
    </div>

    <div class="right-panel">
<div class="desktop-right-wave">
            <svg viewBox="0 0 60 900" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,0 
                         C35,150 55,300 25,450 
                         C-5,600 45,750 0,900 
                         L0,900 Z" fill="#064e3b"/>
            </svg>

        </div>
        <div class="form-container">
            <div class="form-header">
                <h1>Welcome back</h1>
                <p>Sign in to manage your catalog, stock, and sales.</p>
            </div>

            <?php if (Session::get('error')): ?>
            <div class="alert-error"><?= htmlspecialchars(Session::get('error'));
                Session::set('error', null); ?></div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <div class="form-group">
                    <label>Email Address <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path d="M2.5 6.667l7.5 5 7.5-5M2.5 5h15a.833.833 0 01.833.833v8.334A.833.833 0 0117.5 15h-15a.833.833 0 01-.833-.833V5.833A.833.833 0 012.5 5z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="email" name="email" placeholder="name@company.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                                <rect x="3.333" y="9.167" width="13.333" height="9.167" rx="1.667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.667 9.167V6.667a3.333 3.333 0 016.667 0v2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    Sign In
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8h10M9 4l4 4-4 4"/>
                    </svg>
                </button>
            </form>

            <div class="form-footer">
                <a href="/test-accounts">View Test Accounts</a>
            </div>
        </div>
    </div>

</body>
</html>
