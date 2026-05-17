<link rel="stylesheet" href="/assets/css/users.forms.css">
<div class="form-wrapper">
    <div class="fade-in">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Create User</h1>
            <p class="text-secondary">Add a new user to the system</p>
        </div>
    </header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/users/store">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-input" placeholder="John Doe" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" placeholder="john@example.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimum 6 characters" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role_id" class="form-input" required>
                            <option value="">Select a role</option>
                            <?php if (isset($roles) && !empty($roles)): ?>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="/users" class="btn btn-white">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>