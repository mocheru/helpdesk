<?php
$ENABLE_ADD     = has_permission('Helpdesk.Add');
$ENABLE_MANAGE  = has_permission('Helpdesk.Manage');

$mode = isset($helpdesk->id) ? (isset($view_mode) && $view_mode ? 'view' : 'edit') : 'add';
$is_readonly = ($mode === 'view');

$is_external = isset($is_external) ? $is_external : false;
$hide_for_external = ($is_external && !$is_readonly);

// Data
$id = isset($helpdesk->id) ? $helpdesk->id : '';
$no_ticket = isset($helpdesk->no_ticket) ? $helpdesk->no_ticket : '';
$report = isset($helpdesk->report) ? $helpdesk->report : '';
$category_id = isset($helpdesk->category_id) ? $helpdesk->category_id : '';
$category_name = isset($helpdesk->category_name) ? $helpdesk->category_name : '';
$sub_category_id = isset($helpdesk->sub_category_id) ? $helpdesk->sub_category_id : '';
$sub_category_name = isset($helpdesk->sub_category_name) ? $helpdesk->sub_category_name : '';
$causes = isset($helpdesk->causes) ? $helpdesk->causes : '';
$action_plan = isset($helpdesk->action_plan) ? $helpdesk->action_plan : '';
$due_date = isset($helpdesk->due_date) ? $helpdesk->due_date : '';
$pic_id = isset($helpdesk->pic_id) ? $helpdesk->pic_id : '';
$pic = isset($helpdesk->pic) ? $helpdesk->pic : '';
$status = isset($helpdesk->status) ? $helpdesk->status : 'Open';
$create_by = isset($helpdesk->create_by) ? $helpdesk->create_by : '';
$create_date = isset($helpdesk->create_date) ? $helpdesk->create_date : '';
$colCategory = ($mode === 'view') ? 'col-md-6' : 'col-md-4';
?>

<!-- Select2 Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Flatpickr (Date Picker) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }

    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid var(--bs-primary);
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .info-box-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .info-box-item:last-child {
        margin-bottom: 0;
    }

    .info-box-label {
        font-weight: 600;
        width: 120px;
        color: #6c757d;
    }

    .info-box-value {
        flex: 1;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* View mode styling */
    .view-field {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        min-height: 42px;
        display: flex;
        align-items: center;
    }

    .view-textarea {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        min-height: 100px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>

<div class="card">
    <div class="card-body">
        <?php if ($mode === 'view' && $id): ?>
            <div class="info-box">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-ticket text-primary"></i> Ticket No
                            </span>
                            <span class="info-box-value">
                                <strong>: <?= htmlspecialchars($no_ticket) ?></strong>
                            </span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-circle-info text-info"></i> Status
                            </span>
                            <span class="info-box-value">
                                <?php
                                $statusClass = 'bg-secondary';
                                $statusIcon  = 'fa-question';
                                $statusText  = 'Unknown';
                                switch ($status) {
                                    case 0:
                                        $statusClass = 'bg-info';
                                        $statusIcon  = 'fa-circle-dot';
                                        $statusText  = 'Open';
                                        break;

                                    case 1:
                                        $statusClass = 'bg-warning';
                                        $statusIcon  = 'fa-spinner';
                                        $statusText  = 'On Progress';
                                        break;

                                    case 2:
                                        $statusClass = 'bg-success';
                                        $statusIcon  = 'fa-circle-check';
                                        $statusText  = 'Done';
                                        break;

                                    case 3:
                                        $statusClass = 'bg-dark';
                                        $statusIcon  = 'fa-lock';
                                        $statusText  = 'Closed';
                                        break;

                                    case 4:
                                        $statusClass = 'bg-success';
                                        $statusIcon  = 'fa-lock';
                                        $statusText  = 'Close';
                                        break;
                                }
                                ?>
                                : <span class="badge <?= $statusClass ?> status-badge">
                                    <i class="fa-solid <?= $statusIcon ?>"></i>
                                    <?= $statusText ?>
                                </span>
                            </span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-building text-primary"></i> Client
                            </span>
                            <span class="info-box-value">
                                <?php
                                $client_display = isset($helpdesk->client_name) ? htmlspecialchars($helpdesk->client_name) : '-';
                                if (isset($helpdesk->client_remark) && !empty($helpdesk->client_remark)) {
                                    $client_display .= ' - ' . htmlspecialchars($helpdesk->client_remark);
                                }
                                ?>
                                : <?= $client_display ?>
                            </span>
                        </div>
                        <div class="info-box-item">
                            <span class="info-box-label">
                                <i class="fa-solid fa-user text-success"></i> Created by
                            </span>
                            <span class="info-box-value">
                                : <?= htmlspecialchars($create_by) ?> | <span class="text-muted" style="font-size: 12px;"><?= $create_date ? date('d-m-Y H:i', strtotime($create_date)) : '-' ?></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form id="form-helpdesk" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="row g-3">
                <?php if ($mode !== 'view'): ?>
                    <div class="col-md-4">
                        <?php if (!$is_readonly): ?>
                            <div class="mb-3">
                                <label class="form-label required">
                                    <i class="fa-solid fa-building"></i> Client
                                </label>
                                <?php
                                // ✅ Cek jumlah client yang dimiliki user
                                $client_count = !empty($clients) ? count($clients) : 0;
                                $is_single_client = ($client_count === 1);
                                $auto_selected_client = $is_single_client ? $clients[0]->id : '';
                                ?>

                                <select class="form-select select2" name="client_id" id="client_id" required
                                    <?= $is_single_client ? 'disabled' : '' ?>>
                                    <option value="">- Select Client -</option>
                                    <?php if (!empty($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <?php
                                            $client_info = htmlspecialchars($client->name_app);
                                            if (!empty($client->remark)) {
                                                $client_info .= ' - ' . htmlspecialchars($client->remark);
                                            }

                                            // Auto-select jika hanya 1 client ATAU jika edit mode dan sesuai
                                            $selected = '';
                                            if ($is_single_client) {
                                                $selected = 'selected';
                                            } elseif (isset($helpdesk->client_id) && $helpdesk->client_id == $client->id) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $client->id ?>" <?= $selected ?>>
                                                <?= $client_info ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Tidak ada client tersedia</option>
                                    <?php endif; ?>
                                </select>

                                <?php if ($is_single_client): ?>
                                    <input type="hidden" name="client_id" value="<?= $auto_selected_client ?>">
                                <?php else: ?>
                                    <small class="text-muted">Pilih client terkait untuk ticket ini</small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
                <div class="<?= $colCategory ?>">
                    <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Category</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-field">
                            <?= htmlspecialchars($category_name) ?>
                        </div>
                    <?php else: ?>
                        <select class="form-select select2" name="category_id" id="category_id"
                            <?= $is_readonly ? 'disabled' : 'required' ?>>
                            <option value="">- Select Category -</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= ($category_id == $cat->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->category_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="<?= $colCategory ?>">
                    <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Sub Category</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-field">
                            <?= htmlspecialchars($sub_category_name) ?>
                        </div>
                    <?php else: ?>
                        <select class="form-select select2" name="sub_category_id" id="sub_category_id"
                            <?= $is_readonly ? 'disabled' : 'required' ?>>
                            <option value="">- Select Sub Category -</option>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Report -->
            <div class="mb-3">
                <label class="form-label <?= !$is_readonly ? 'required' : '' ?>">Report / Issue Description</label>
                <?php if ($is_readonly): ?>
                    <div class="view-textarea"><?= htmlspecialchars($report) ?></div>
                <?php else: ?>
                    <textarea class="form-control" name="report" id="report" rows="4"
                        placeholder="Jelaskan masalah yang terjadi..."
                        <?= $is_readonly ? 'readonly' : 'required' ?>><?= htmlspecialchars($report) ?></textarea>
                <?php endif; ?>
            </div>

            <!-- Causes -->
            <?php if (!$hide_for_external): ?>
                <div class="mb-3">
                    <label class="form-label">Causes / Root Cause Analysis</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-textarea"><?= $causes ? htmlspecialchars($causes) : '-' ?></div>
                    <?php else: ?>
                        <textarea class="form-control" name="causes" id="causes" rows="3"
                            placeholder="Penyebab masalah (jika sudah diketahui)"
                            <?= $is_readonly ? 'readonly' : '' ?>><?= htmlspecialchars($causes) ?></textarea>
                    <?php endif; ?>
                </div>

                <!-- Action Plan -->
                <div class="mb-3">
                    <label class="form-label">Action Plan</label>
                    <?php if ($is_readonly): ?>
                        <div class="view-textarea"><?= $action_plan ? htmlspecialchars($action_plan) : '-' ?></div>
                    <?php else: ?>
                        <textarea class="form-control" name="action_plan" id="action_plan" rows="3"
                            placeholder="Rencana tindakan untuk menyelesaikan masalah"
                            <?= $is_readonly ? 'readonly' : '' ?>><?= htmlspecialchars($action_plan) ?></textarea>
                    <?php endif; ?>
                </div>

                <!-- Due Date, PIC & Approval -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Due Date</label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?= $due_date ? date('d-m-Y', strtotime($due_date)) : '-' ?>
                            </div>
                        <?php else: ?>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <input type="text" class="form-control flatpickr" name="due_date" id="due_date"
                                    value="<?= $due_date ?>" placeholder="Select Date"
                                    <?= $is_readonly ? 'readonly' : 'required' ?>>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fa-solid fa-user-check"></i> PIC (Person In Charge)
                        </label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?php
                                $pic_display = isset($helpdesk->pic_name) ? htmlspecialchars($helpdesk->pic_name) : '-';
                                // if (isset($helpdesk->pic_username)) {
                                //     $pic_display .= ' (' . htmlspecialchars($helpdesk->pic_username) . ')';
                                // }
                                echo $pic_display;
                                ?>
                            </div>
                        <?php else: ?>
                            <select class="form-select select2" name="pic_id" id="pic_id"
                                <?= $is_readonly ? 'disabled' : '' ?>>
                                <option value="">- Select PIC -</option>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user->id_user ?>"
                                            <?= (isset($pic_id) && $pic_id == $user->id_user) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user->nm_lengkap) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="fa-solid fa-user-shield"></i> Approval By
                        </label>
                        <?php if ($is_readonly): ?>
                            <div class="view-field">
                                <?php
                                $approval_display = '-';
                                if (isset($helpdesk->approval_name) && !empty($helpdesk->approval_name)) {
                                    $approval_display = htmlspecialchars($helpdesk->approval_name);
                                    if (isset($helpdesk->approval_username)) {
                                        $approval_display .= ' (' . htmlspecialchars($helpdesk->approval_username) . ')';
                                    }
                                }
                                echo $approval_display;
                                ?>
                            </div>
                        <?php else: ?>
                            <select class="form-select select2" name="approval_by_id" id="approval_by_id"
                                <?= $is_readonly ? 'disabled' : '' ?>>
                                <option value="">- Select Approval -</option>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <?php
                                        $selected = '';
                                        if (isset($helpdesk->approval_by_id) && $helpdesk->approval_by_id == $user->id_user) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $user->id_user ?>" <?= $selected ?>>
                                            <?= htmlspecialchars($user->nm_lengkap) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="card-footer">
        <div class="d-flex gap-2">
            <a href="<?= site_url('helpdesk') ?>" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            <?php if (!$is_readonly): ?>
                <button type="submit" form="form-helpdesk" class="btn btn-primary" id="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Save
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        var isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;
        var mode = '<?= $mode ?>';

        <?php if (!$is_readonly): ?>
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                minDate: 'today',
                locale: {
                    firstDayOfWeek: 1
                }
            });

            var selectedCategory = $('#category_id').val();
            var selectedSubCategory = '<?= $sub_category_id ?>';

            if (selectedCategory) {
                loadSubCategories(selectedCategory, selectedSubCategory);
            }

            $('#category_id').change(function() {
                var categoryId = $(this).val();
                loadSubCategories(categoryId);
            });

            function loadSubCategories(categoryId, selectedId = '') {
                if (categoryId) {
                    $.ajax({
                        url: siteurl + active_controller + 'get_sub_categories_select',
                        type: 'POST',
                        data: {
                            category_id: categoryId
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#sub_category_id').html('<option value="">Loading...</option>');
                            $('#sub_category_id').prop('disabled', true);
                        },
                        success: function(response) {
                            var options = '<option value="">- Select Sub Category -</option>';

                            if (response.status == 1 && response.data.length > 0) {
                                $.each(response.data, function(index, item) {
                                    var selected = (selectedId && selectedId == item.id) ? 'selected' : '';
                                    options += '<option value="' + item.id + '" ' + selected + '>' + item.sub_name + '</option>';
                                });
                            }

                            $('#sub_category_id').html(options);
                            $('#sub_category_id').prop('disabled', false);
                        },
                        error: function() {
                            $('#sub_category_id').html('<option value="">- Select Sub Category -</option>');
                            $('#sub_category_id').prop('disabled', false);
                        }
                    });
                } else {
                    $('#sub_category_id').html('<option value="">- Select Sub Category -</option>');
                    $('#sub_category_id').prop('disabled', false);
                }
            }

            // Form Submit
            $('#form-helpdesk').submit(function(e) {
                e.preventDefault();

                // Validation
                if (!$('#category_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Category harus dipilih'
                    });
                    return false;
                }

                if (!$('#sub_category_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Sub Category harus dipilih'
                    });
                    return false;
                }

                if (!$('#client_id').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Client harus dipilih'
                    });
                    return false;
                }

                if (!$('#report').val() || $('#report').val().trim().length < 10) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Report harus diisi minimal 10 karakter'
                    });
                    return false;
                }

                var formData = new FormData(this);
                var url = siteurl + active_controller + 'save_ticket';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Simpan!',
                    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            beforeSend: function() {
                                $('#btn-save').prop('disabled', true);
                                $('#btn-save').html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
                            },
                            success: function(response) {
                                $('#btn-save').prop('disabled', false);
                                $('#btn-save').html('<i class="fa-solid fa-floppy-disk"></i> Save');

                                if (response.status == 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.href = siteurl + active_controller;
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                $('#btn-save').prop('disabled', false);
                                $('#btn-save').html('<i class="fa-solid fa-floppy-disk"></i> Save');

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menyimpan data'
                                });
                            }
                        });
                    }
                });
            });
        <?php endif; ?>
    });
</script>