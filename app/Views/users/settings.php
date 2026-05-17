<link rel="stylesheet" href="/assets/css/users.forms.css">
<div class="fade-in">
    <header class="page-header">
        <div>
            <h1 class="page-title">Account Settings</h1>
            <p class="text-secondary">Manage your account preferences and security</p>
        </div>
    </header>

    <div class="settings-container">
        <!-- Profile Info Card -->
        <div class="card">
            <div class="card-body">
                <h2 class="section-title">Profile Information</h2>
                <div class="profile-info-box">
                    <div class="profile-avatar">
                        <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="profile-name"><?= htmlspecialchars($user['full_name'] ?? '') ?></div>
                        <div class="profile-subtext">@<?= htmlspecialchars($user['username'] ?? '') ?></div>
                        <div class="profile-subtext"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card">
            <div class="card-body">
                <h2 class="section-title">Change Password</h2>
                <p class="section-desc">Update your password to keep your account secure</p>
                
                <form method="POST" action="/users/change-password" class="form-stack">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-input" required minlength="6">
                        <small class="help-text">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-input" required minlength="6">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Update Password
                        </button>
                        <a href="/dashboard" class="btn btn-white">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>