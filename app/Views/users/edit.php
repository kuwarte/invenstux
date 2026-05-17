<link rel="stylesheet" href="/assets/css/users.forms.css">
<div class="form-wrapper">
<div class="fade-in">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Edit User</h1>
            <p class="text-secondary">Update user information and permissions</p>
        </div>
    </header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/users/update">
                <input type="hidden" name="id" value="<?= $user['id'] ?? '' ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-input" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        Change Password (Optional)
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current password" minlength="6">
                        <div class="help-text">Leave blank if you don't want to change the password</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role_id" class="form-input" required>
                        <option value="">Select a role</option>
                        <?php if (isset($roles) && !empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $role['id'] == ($user['role_id'] ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="/users" class="btn btn-white">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
                        </div>