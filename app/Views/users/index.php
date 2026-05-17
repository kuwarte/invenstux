<div class="fade-in page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Team Members</h1>
            <p class="text-secondary">Manage your organization's members and their deployment roles.</p>
        </div>
        
        <div class="header-actions">
    
    <a href="/users/create" class="btn btn-primary">Add Member</a>
</div>
    </header>

    <div class="card">
        <div class="card-header">
            <span class="card-title">All Users</span>
            <span class="badge badge-info"><?= count($users ?? []) ?> Users</span>
        </div>
        
        <?php if (!empty($users)): ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="user-details">
                                    <div class="user-name"><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></div>
                                    <div class="user-handle">@<?= htmlspecialchars($user['username'] ?? 'user') ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="email-badge">
                                <?= htmlspecialchars($user['email']) ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $roleClass = strtolower($user['role_name'] ?? '');
                            ?>
                            <span class="role-badge role-<?= htmlspecialchars($roleClass) ?>">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <?= htmlspecialchars($user['role_name'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="status-badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <div class="action-buttons">
                                <?php if ($user['id'] != ($_SESSION['user_id'] ?? null)): ?>
                                    <a href="/users/update?id=<?= $user['id'] ?>" class="btn-icon btn-edit" title="Edit User">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    
                                    <form method="POST" action="/users/toggle-status" class="inline-form">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn-icon <?= $user['is_active'] ? 'btn-deactivate' : 'btn-activate' ?>" title="<?= $user['is_active'] ? 'Deactivate' : 'Activate' ?> User">
                                            <?php if ($user['is_active']): ?>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                                            <?php else: ?>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <?php endif; ?>
                                        </button>
                                    </form>

                                    <form method="POST" action="/users/delete" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn-icon btn-delete" title="Delete User">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="current-user-label">Current User</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <h3>No Users Found</h3>
            <p class="text-secondary">Start by adding your first user to the system.</p>
            <a href="/users/create" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add User
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>