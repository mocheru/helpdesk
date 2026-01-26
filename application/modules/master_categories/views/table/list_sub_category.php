<?php
$ENABLE_MANAGE  = has_permission('Helpdesk.Manage');
$ENABLE_DELETE  = has_permission('Helpdesk.Delete');
?>

<?php if (!empty($sub_categories)): ?>
    <?php foreach ($sub_categories as $sub): ?>
        <div class="sub-category-item" id="sub-item-<?= $sub->id; ?>">
            <div class="row">
                <div class="col-md-5 sub-name-display">
                    <strong><?= $sub->sub_name; ?></strong>
                </div>
                <div class="col-md-5 sub-remark-display">
                    <small class="text-muted"><?= $sub->remark ?: '-'; ?></small>
                </div>
                <div class="col-md-2 text-right sub-action-buttons">
                    <?php if ($ENABLE_MANAGE): ?>
                        <button class="btn btn-sm btn-warning edit-sub-category"
                            data-id="<?= $sub->id; ?>"
                            data-category-id="<?= $sub->id_category; ?>"
                            data-name="<?= htmlspecialchars($sub->sub_name); ?>"
                            data-remark="<?= htmlspecialchars($sub->remark); ?>"
                            title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                    <?php endif; ?>

                    <?php if ($ENABLE_DELETE): ?>
                        <button class="btn btn-sm btn-danger delete-sub-category"
                            data-id="<?= $sub->id; ?>"
                            data-category-id="<?= $sub->id_category; ?>"
                            data-name="<?= $sub->sub_name; ?>"
                            title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info" style="margin-bottom: 0;">
        <i class="fa fa-info-circle"></i> No sub categories available
    </div>
<?php endif; ?>