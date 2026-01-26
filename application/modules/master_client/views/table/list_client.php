<?php
$ENABLE_ADD     = has_permission('Master_client.Add');
$ENABLE_MANAGE  = has_permission('Master_client.Manage');
$ENABLE_VIEW    = has_permission('Master_client.View');
$ENABLE_DELETE  = has_permission('Master_client.Delete');
?>

<style>
    /* Table Header */
    #table_client thead {
        background: #3498db;
        color: white;
    }

    /* Main Table Rows */
    #table_client tbody tr.data-row {
        background-color: #ffffff;
        transition: background-color 0.2s;
    }

    #table_client tbody tr.data-row:nth-child(2n) {
        background-color: #f7f9fb;
    }

    #table_client tbody tr.data-row:hover {
        background-color: #eaf4fb !important;
    }
</style>

<table id="table_client" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>Application Name</th>
            <th>Remark</th>
            <th width="20%">Create By</th>
            <th width="15%" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($clients)): ?>
            <?php $no = 1;
            foreach ($clients as $item): ?>
                <tr id="row-<?= $item->id; ?>" class="data-row">
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="client-name-display">
                        <strong><?= ucfirst($item->name_app); ?></strong>
                    </td>
                    <td class="remark-display">
                        <?= $item->remark ?: '-'; ?>
                    </td>
                    <td>
                        <?= $item->create_by; ?><br>
                        <small class="text-muted" style="font-style: italic; font-size: 12px;">
                            <?= date('d M Y H:i', strtotime($item->create_date)); ?>
                        </small>
                    </td>
                    <td class="text-center action-buttons">
                        <?php if ($ENABLE_MANAGE): ?>
                            <button type="button" class="btn btn-sm btn-warning edit-client"
                                data-id="<?= $item->id; ?>"
                                data-name="<?= htmlspecialchars($item->name_app); ?>"
                                data-remark="<?= htmlspecialchars($item->remark); ?>"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                        <?php endif; ?>

                        <?php if ($ENABLE_DELETE): ?>
                            <button type="button" class="btn btn-sm btn-danger delete-client"
                                data-id="<?= $item->id; ?>"
                                data-name="<?= htmlspecialchars($item->name_app); ?>"
                                title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No data available</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>