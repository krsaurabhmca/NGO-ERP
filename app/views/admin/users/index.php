<?php require_once 'app/views/admin/layouts/header.php'; ?>
<?php require_once 'app/views/admin/layouts/sidebar.php'; ?>

<div class="page-wrapper">
    <?php require_once 'app/views/admin/layouts/topbar.php'; ?>

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="mb-3">
                        <ol class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item"><a href="<?php echo url('admin/dashboard'); ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Users</a></li>
                        </ol>
                    </div>
                    <h2 class="page-title fw-bold fs-1">
                        User Management
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-user">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add New User
                        </a>
                        <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-add-role">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /><path d="M17 16l4 4" /><path d="M21 16l-4 4" /></svg>
                            Add New Role
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php
            $totalUsers = count($users ?? []);
            $activeCount = 0;
            $inactiveCount = 0;
            $adminCount = 0;
            foreach ($users as $u) {
                if ($u->status === 'active') $activeCount++;
                else $inactiveCount++;
                if (($u->role_id ?? 0) == 1) $adminCount++;
            }
            ?>
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card card-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold"><?php echo $totalUsers; ?></div>
                                <div class="text-secondary fs-6">Total Users</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold"><?php echo $activeCount; ?></div>
                                <div class="text-secondary fs-6">Active</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold"><?php echo $inactiveCount; ?></div>
                                <div class="text-secondary fs-6">Inactive</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield-cog" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M14.5 12.5a2.5 2.5 0 1 0 -5 0a2.5 2.5 0 0 0 5 0z" /><path d="M17.5 17.5l2.5 2.5" /><path d="M19 17.5a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0 -5z" /></svg>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold"><?php echo $adminCount; ?></div>
                                <div class="text-secondary fs-6">Super Admins</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex w-100 align-items-center gap-3">
                        <form method="GET" action="<?php echo url('admin/users'); ?>" class="d-flex gap-2 flex-grow-1">
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                </span>
                                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            </div>
                            <button type="submit" class="btn btn-secondary">Search</button>
                            <?php if (!empty($_GET['search'])): ?>
                                <a href="<?php echo url('admin/users'); ?>" class="btn btn-ghost-secondary">Clear</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th class="fw-semibold">User</th>
                                <th class="fw-semibold">Email</th>
                                <th class="fw-semibold">Role</th>
                                <th class="fw-semibold">Last Login</th>
                                <th class="fw-semibold">Status</th>
                                <th class="fw-semibold text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span class="avatar avatar-sm me-2 d-inline-flex align-items-center justify-content-center" style="background: #e9ecef;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6c757d" width="18" height="18"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
</span>
                                                <div class="flex-fill">
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($user->name); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary"><?php echo htmlspecialchars($user->email); ?></td>
                                        <td>
                                            <?php if (($user->role_id ?? 0) == 1): ?>
                                                <span class="badge bg-orange-lt"><?php echo htmlspecialchars($user->role_name ?? 'Super Admin'); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-azure-lt"><?php echo htmlspecialchars($user->role_name ?? '—'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-secondary"><?php echo $user->last_login ? date('d M, Y h:i A', strtotime($user->last_login)) : '—'; ?></td>
                                        <td>
                                            <?php if ($user->status === 'active'): ?>
                                                <span class="badge bg-success me-1"></span> Active
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-1"></span> Inactive
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1">
                                                <?php if ($user->role_id != 1): ?>
                                                <a href="javascript:void(0)" onclick="editUser('<?php echo ($user->uuid ?? $user->id); ?>')" class="btn btn-icon btn-azure btn-sm" title="Edit" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($user->id != $_SESSION['user_id']): ?>
                                                <a href="javascript:void(0)" onclick="confirmToggleStatus('<?php echo url('admin/users/toggle-status/' . ($user->uuid ?? $user->id)); ?>', '<?php echo $user->status === 'active' ? 'deactivate' : 'activate'; ?>')" class="btn btn-icon btn-<?php echo $user->status === 'active' ? 'warning' : 'success'; ?> btn-sm" title="<?php echo $user->status === 'active' ? 'Deactivate' : 'Activate'; ?>" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><?php if ($user->status === 'active'): ?><path d="M5 12l5 5l10 -10" /><?php else: ?><path d="M12 5l0 14" /><path d="M5 12l14 0" /><?php endif; ?></svg>
                                                </a>
                                                <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/users/delete/' . ($user->uuid ?? $user->id)); ?>', 'admin/users/delete/<?php echo ($user->uuid ?? $user->id); ?>', '<?php echo csrf_token('admin/users/delete/' . ($user->uuid ?? $user->id)); ?>')" class="btn btn-icon btn-danger btn-sm" title="Delete" data-bs-toggle="tooltip">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal modal-blur fade" id="modal-add-role" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/users/store-role'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">Add New Role</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Editor">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <label class="form-label mb-2">Existing Roles</label>
                            <?php if (!empty($roles)): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($roles as $role): ?>
                                        <span class="badge bg-secondary-lt text-dark px-2 py-2 d-inline-flex align-items-center gap-2">
                                            <?php echo htmlspecialchars($role->name); ?>
                                            <?php if (strtolower($role->name) !== 'super admin'): ?>
                                            <a href="javascript:void(0)" onclick="confirmDelete('<?php echo url('admin/users/delete-role/' . ($role->uuid ?? $role->id)); ?>', 'admin/users/delete-role/<?php echo ($role->uuid ?? $role->id); ?>', '<?php echo csrf_token('admin/users/delete-role/' . ($role->uuid ?? $role->id)); ?>')" class="text-danger text-decoration-none" style="line-height: 1;">&times;</a>
                                            <?php endif; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small mb-0">No roles added yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal modal-blur fade" id="modal-add-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?php echo url('admin/users/store'); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter user's full name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required placeholder="example@email.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required placeholder="Min 6 characters" minlength="6">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required placeholder="Re-enter password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role_id" class="form-select">
                                        <option value="">Select Role</option>
                                        <?php if (!empty($roles)): ?>
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?php echo $role->id; ?>"><?php echo htmlspecialchars($role->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top px-3 py-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">Grant Module Access</h6>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPerms('add')">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllPerms('add')">Deselect All</button>
                            </div>
                        </div>
                        <?php $moduleLabels = [
                            'cms' => 'Content',
                            'campaigns' => 'Crowdfunding',
                            'contacts' => 'Messages',
                            'stakeholders' => 'People',
                        ]; ?>
                        <?php if (!empty($modules)): ?>
                        <div class="row g-2">
                            <?php foreach ($modules as $mod): ?>
                                <?php $perms = $modulePermissions[$mod->module] ?? []; ?>
                                <?php if (!empty($perms)): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card card-sm border">
                                        <div class="card-header py-1 px-2 d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold small"><?php echo htmlspecialchars($moduleLabels[$mod->module] ?? ucwords($mod->module)); ?></span>
                                            <input type="checkbox" id="add_selall_<?php echo $mod->module; ?>" onchange="toggleModulePerms(this, '<?php echo $mod->module; ?>', 'add')" title="Select all in this module" style="width:0.85rem;height:0.85rem;margin:0;flex-shrink:0">
                                        </div>
                                        <div class="card-body px-2 py-1">
                                            <?php foreach ($perms as $perm): ?>
                                            <label class="d-flex align-items-center gap-1" style="cursor:pointer;line-height:1.6">
                                                <input type="checkbox" class="perm-<?php echo $mod->module; ?>" name="permissions[]" value="<?php echo $perm->id; ?>" id="add_perm_<?php echo $perm->id; ?>" style="width:0.85rem;height:0.85rem;margin:0;flex-shrink:0">
                                                <span class="small"><?php echo htmlspecialchars($perm->name); ?></span>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <div class="text-muted small py-1">No modules available.</div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal modal-blur fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="edit-user-form" action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required placeholder="Enter user's full name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required placeholder="example@email.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password <span class="text-muted small fw-normal">(Leave blank to keep current)</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" minlength="6">
                                </div>
                            </div>
                            <div class="col-lg-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role_id" id="edit_role_id" class="form-select">
                                        <option value="">Select Role</option>
                                        <?php if (!empty($roles)): ?>
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?php echo $role->id; ?>"><?php echo htmlspecialchars($role->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" id="edit_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top px-3 py-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0">Grant Module Access</h6>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPerms('edit')">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllPerms('edit')">Deselect All</button>
                            </div>
                        </div>
                        <?php if (!empty($modules)): ?>
                        <div class="row g-2">
                            <?php foreach ($modules as $mod): ?>
                                <?php $perms = $modulePermissions[$mod->module] ?? []; ?>
                                <?php if (!empty($perms)): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card card-sm border">
                                        <div class="card-header py-1 px-2 d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold small"><?php echo htmlspecialchars($moduleLabels[$mod->module] ?? ucwords($mod->module)); ?></span>
                                            <input type="checkbox" id="edit_selall_<?php echo $mod->module; ?>" onchange="toggleModulePerms(this, '<?php echo $mod->module; ?>', 'edit')" title="Select all in this module" style="width:0.85rem;height:0.85rem;margin:0;flex-shrink:0">
                                        </div>
                                        <div class="card-body px-2 py-1">
                                            <?php foreach ($perms as $perm): ?>
                                            <label class="d-flex align-items-center gap-1" style="cursor:pointer;line-height:1.6">
                                                <input type="checkbox" class="edit-perm perm-<?php echo $mod->module; ?>" name="permissions[]" value="<?php echo $perm->id; ?>" id="edit_perm_<?php echo $perm->id; ?>" style="width:0.85rem;height:0.85rem;margin:0;flex-shrink:0">
                                                <span class="small"><?php echo htmlspecialchars($perm->name); ?></span>
                                            </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <div class="text-muted small py-1">No modules available.</div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary ms-auto">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toggle Status Confirmation Modal -->
    <div class="modal modal-blur fade" id="modal-toggle-user-status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-warning"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-warning icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                    <h3>Change User Status?</h3>
                    <div class="text-muted">Are you sure you want to <span id="user-toggle-status-text"></span> this user?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a href="#" class="btn w-100" data-bs-dismiss="modal">Cancel</a>
                            </div>
                            <div class="col">
                                <button id="btn-confirm-toggle-user" class="btn btn-warning w-100">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModulePerms(el, module, prefix) {
            const checked = el.checked;
            const modal = document.getElementById(`modal-${prefix}-user`);
            modal.querySelectorAll(`.perm-${module}`).forEach(cb => cb.checked = checked);
        }

        function selectAllPerms(prefix) {
            const modal = document.getElementById(`modal-${prefix}-user`);
            modal.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = true);
            modal.querySelectorAll('[id^="' + prefix + '_selall_"]').forEach(cb => cb.checked = true);
        }

        function deselectAllPerms(prefix) {
            const modal = document.getElementById(`modal-${prefix}-user`);
            modal.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
            modal.querySelectorAll('[id^="' + prefix + '_selall_"]').forEach(cb => cb.checked = false);
        }

        function editUser(id) {
            fetch('<?php echo url('admin/users/get/'); ?>' + id)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const user = result.data;
                        document.getElementById('edit-user-form').action = '<?php echo url('admin/users/update/'); ?>' + id;
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_role_id').value = user.role_id || '';
                        document.getElementById('edit_status').value = user.status;

                        document.querySelectorAll('.edit-perm').forEach(cb => cb.checked = false);
                        document.querySelectorAll('[id^="edit_selall_"]').forEach(cb => cb.checked = false);
                        if (user.permission_ids && user.permission_ids.length) {
                            user.permission_ids.forEach(id => {
                                const cb = document.getElementById('edit_perm_' + id);
                                if (cb) cb.checked = true;
                            });
                        }

                        const modal = new bootstrap.Modal(document.getElementById('modal-edit-user'));
                        modal.show();
                    } else {
                        showToast(result.message || 'Failed to fetch user data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while fetching user data', 'error');
                });
        }

        function confirmToggleStatus(url, action) {
            const modal = new bootstrap.Modal(document.getElementById('modal-toggle-user-status'));
            const statusText = action === 'deactivate' ? 'deactivate' : 'activate';
            const statusEl = document.getElementById('user-toggle-status-text');
            if (statusEl) statusEl.textContent = statusText;
            
            const confirmBtn = document.getElementById('btn-confirm-toggle-user');
            confirmBtn.onclick = function(e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = csrfMeta?.getAttribute('content') || '';

                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = '_csrf_action';
                actionInput.value = '_default';
                form.appendChild(actionInput);

                var tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_csrf_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
                
                document.body.appendChild(form);
                form.submit();
            };
            
            modal.show();
        }
    </script>

    <style>
    @media (max-width: 575.98px) {
        .card-header .d-flex.gap-2 { flex-wrap: wrap; gap: 0.25rem !important; }
        .card-header .input-icon { width: 100%; }
        .card-header .input-icon .form-control { width: 100%; }
        #modal-add-user .col-lg-4.col-md-6 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
        #modal-add-user .card-sm .card-body { padding: 0.3rem !important; }
        #modal-add-user .card-sm small { font-size: 0.6rem !important; }
    }
    </style>
    <?php require_once 'app/views/admin/layouts/footer.php'; ?>
</div>
