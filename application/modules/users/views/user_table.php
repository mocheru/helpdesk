<?php if (!empty($users)) : ?>
    <div class="table-responsive">
        <table id="user-table" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:160px;"><?= lang('users_username') ?></th>
                    <th style="width:160px;"><?= lang('users_email') ?></th>
                    <th style="width:160px;"><?= lang('users_nm_lengkap') ?></th>
                    <th style="width:160px;">Client Apps</th>
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
                        <td>
                            <div class="fw-semibold">
                                <?= htmlspecialchars($user['username']) ?>
                            </div>

                            <div class="mt-1">
                                <?php if ($user['status'] == 0) : ?>
                                    <span class="badge bg-primary-subtle text-primary me-1">
                                        Internal
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-warning-subtle text-warning me-1">
                                        External
                                    </span>
                                <?php endif; ?>

                                <?php if ($user['is_ba'] == 1) : ?>
                                    <span class="badge bg-success-subtle text-success">
                                        Business Analyst
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['nm_lengkap']) ?></td>
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