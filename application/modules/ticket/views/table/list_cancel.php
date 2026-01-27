<?php
$ENABLE_VIEW = has_permission('Ticket.View');
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_cancel" style="width:100%;">
        <thead class="table-light">
            <tr>
                <th style="min-width: 20px;">No</th>
                <th style="min-width: 80px;">No Tiket</th>
                <th style="min-width: 200px;">Report</th>
                <th style="min-width: 150px;">Category</th>
                <th style="min-width: 150px;">Sub Category</th>
                <th style="min-width: 180px;">Causes</th>
                <th style="min-width: 180px;">Action Plan</th>
                <th style="min-width: 110px;">Due Date</th>
                <th style="min-width: 120px;">PIC</th>
                <th style="min-width: 200px;">Cancel Reason</th>
                <th style="min-width: 150px;">Cancelled By</th>
                <th style="min-width: 120px;">Cancel Date</th>
                <?php if ($ENABLE_VIEW): ?>
                    <th style="min-width: 120px;" class="text-center">Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($helpdesk)): ?>
                <?php $no = 1;
                foreach ($helpdesk as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['no_ticket']) ?></strong> <br>
                            <span class="badge bg-danger">
                                <i class="fa-solid fa-ban"></i> Cancel
                            </span>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;"
                                title="<?= htmlspecialchars($row['report']) ?>">
                                <?= htmlspecialchars($row['report']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                        <td><?= htmlspecialchars($row['sub_category_name']) ?></td>
                        <td>
                            <?php $causes = trim((string)$row['causes']); ?>
                            <div class="text-truncate" style="max-width: 180px;"
                                title="<?= $causes === '' ? '-' : htmlspecialchars($causes) ?>">
                                <?= $causes === '' ? '-' : htmlspecialchars($causes) ?>
                            </div>
                        </td>
                        <td>
                            <?php $actionPlan = trim((string)$row['action_plan']); ?>
                            <div class="text-truncate" style="max-width: 180px;"
                                title="<?= $actionPlan === '' ? '-' : htmlspecialchars($actionPlan) ?>">
                                <?= $actionPlan === '' ? '-' : htmlspecialchars($actionPlan) ?>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <i class="fa-solid fa-calendar-days text-muted"></i>
                            <?php
                            $dueDate = $row['due_date'];

                            if (empty($dueDate) || $dueDate === '0000-00-00') {
                                echo '-';
                            } else {
                                echo date('d-m-Y', strtotime($dueDate));
                            }
                            ?>
                        </td>
                        <td>
                            <?= trim((string)$row['pic']) === '' ? '-' : htmlspecialchars($row['pic']) ?>
                        </td>
                        <td>
                            <?php $cancelReason = trim((string)$row['cancel_reason']); ?>
                            <div class="text-truncate" style="max-width: 200px;"
                                title="<?= $cancelReason === '' ? '-' : htmlspecialchars($cancelReason) ?>">
                                <?= $cancelReason === '' ? '-' : htmlspecialchars($cancelReason) ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <i class="fa-solid fa-user text-muted"></i>
                                <?= htmlspecialchars($row['create_by']) ?>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <?php
                            $updateDate = $row['update_date'];
                            if (empty($updateDate) || $updateDate === '0000-00-00 00:00:00') {
                                echo '-';
                            } else {
                                echo '<i class="fa-solid fa-clock text-muted"></i> ' . date('d-m-Y H:i', strtotime($updateDate));
                            }
                            ?>
                        </td>
                        <?php if ($ENABLE_VIEW): ?>
                            <td class="text-center">
                                <?php
                                $unread_count = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;
                                ?>

                                <button type="button"
                                    class="btn btn-primary btn-sm px-2 py-1 open-chat position-relative"
                                    data-id="<?= $row['id'] ?>"
                                    data-ticket="<?= $row['no_ticket'] ?>"
                                    title="Chat Room">
                                    <i class="fa-solid fa-comments"></i>
                                    <?php if ($unread_count > 0): ?>
                                        <span class="chat-unread-badge-<?= $row['id'] ?> position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 9px; padding: 2px 5px;">
                                            <?= $unread_count > 99 ? '99+' : $unread_count ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="chat-unread-badge-<?= $row['id'] ?> position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="display: none; font-size: 9px; padding: 2px 5px;">
                                            0
                                        </span>
                                    <?php endif; ?>
                                </button>
                                <button type="button"
                                    class="btn btn-secondary btn-sm px-2 py-1 view-history"
                                    data-id="<?= $row['id'] ?>"
                                    data-ticket="<?= $row['no_ticket'] ?>"
                                    title="View History">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                    data-id="<?= $row['id'] ?>"
                                    title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        <?php endif; ?>



                        <!-- <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-primary btn-sm px-2 py-1 process-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="process Ticket">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-secondary btn-sm px-2 py-1 pending-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="pending Ticket">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-danger btn-sm px-2 py-1 cancel-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="cancel Ticket">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-success btn-sm px-2 py-1 done-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="done Ticket">
                                        <i class="fa-solid fa-clipboard-check"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_VIEW): ?>
                                    <button type="button"
                                        class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                        data-id="<?= $row['id'] ?>"
                                        title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-warning btn-sm px-2 py-1 edit-ticket"
                                        data-id="<?= $row['id'] ?>"
                                        title="Edit Ticket">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-danger btn-sm px-2 py-1 reject-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="reject Ticket">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <button type="button"
                                        class="btn btn-success btn-sm px-2 py-1 approve-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="approve Ticket">
                                        <i class="fa-solid fa-check-double"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td> -->

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>