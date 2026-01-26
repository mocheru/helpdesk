<?php if (!empty($users)) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">#</th>
                    <th><?= lang('users_username') ?></th>
                    <th><?= lang('users_email') ?></th>
                    <th><?= lang('users_nm_lengkap') ?></th>
                    <th><?= lang('users_alamat') ?></th>
                    <th><?= lang('users_kota') ?></th>
                    <th><?= lang('users_hp') ?></th>
                    <th>Client Apps</th>
                    <th style="width:120px;"><?= lang('users_st_aktif') ?></th>
                    <?php if ($ENABLE_MANAGE) : ?>
                        <th style="width:110px;" class="text-end">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user) : ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['nm_lengkap']) ?></td>
                        <td><?= !empty($user['alamat']) ? htmlspecialchars($user['alamat']) : '-' ?></td>
                        <td><?= !empty($user['kota']) ? htmlspecialchars($user['kota']) : '-' ?></td>
                        <td><?= !empty($user['hp']) ? htmlspecialchars($user['hp']) : '-' ?></td>
                        <td>
                            <?php if (!empty($user['client_apps'])) : ?>
                                <ul class="client-apps-list">
                                    <?php 
                                    $apps = explode(', ', $user['client_apps']);
                                    foreach ($apps as $app) : 
                                    ?>
                                        <li><?= htmlspecialchars(trim($app)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <span class="client-apps-empty">No client apps</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       class="toggle-status-checkbox" 
                                       data-id="<?= $user['id_user'] ?>" 
                                       data-status="<?= $user['st_aktif'] ?>"
                                       <?= $user['st_aktif'] == 1 ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <?php if ($ENABLE_MANAGE) : ?>
                            <td class="text-end">
                                <a class="btn-icon btn-icon-edit"
                                   href="<?= site_url('users/setting/edit/' . $user['id_user']) ?>"
                                   data-bs-toggle="tooltip" title="Edit User">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <?php if ($user['id_user'] != 1) : ?>
                                    <a class="btn-icon btn-icon-view"
                                       href="<?= site_url('users/setting/permission/' . $user['id_user']) ?>"
                                       data-bs-toggle="tooltip" title="Edit Permission">
                                        <i class="ti ti-shield-lock"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div class="text-center py-5">
        <i class="fa fa-users fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">No users found</p>
    </div>
<?php endif; ?>