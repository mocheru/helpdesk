<?php
$ENABLE_MANAGE = has_permission('Helpdesk.Manage');
$ENABLE_VIEW = has_permission('Helpdesk.View');
$ENABLE_DELETE = has_permission('Helpdesk.Delete');
$loginUserId = $this->auth->user_id();
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_helpdesk" style="width:100%;">
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
                <th style="min-width: 150px;">Create By</th>
                <th style="min-width: 150px;">Approve By</th>
                <th style="min-width: 100px;" class="text-center">Action</th>
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
                            <?php
                            $statusClass = 'bg-secondary';
                            $statusIcon = 'fa-question';
                            $statusText = 'Unknown';

                            switch ((int) $row['status']) {
                                case 0: // Open
                                    $statusClass = 'bg-info';
                                    $statusIcon  = 'fa-circle-dot';
                                    $statusText  = 'Open';
                                    break;

                                case 1: // Process (On Progress)
                                    $statusClass = 'bg-warning';
                                    $statusIcon  = 'fa-spinner fa-spin';
                                    $statusText  = 'Process';
                                    break;

                                case 2: // Pending
                                    $statusClass = 'bg-secondary';
                                    $statusIcon  = 'fa-hourglass-half';
                                    $statusText  = 'Pending';
                                    break;

                                case 3: // Cancel
                                    $statusClass = 'bg-danger';
                                    $statusIcon  = 'fa-ban';
                                    $statusText  = 'Cancel';
                                    break;

                                case 4: // Done
                                    $statusClass = 'bg-success';
                                    $statusIcon  = 'fa-circle-check';
                                    $statusText  = 'Done';
                                    break;

                                case 5: // Close
                                    $statusClass = 'bg-dark';
                                    $statusIcon  = 'fa-lock';
                                    $statusText  = 'Closed';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $statusClass ?>">
                                <i class="fa-solid <?= $statusIcon ?>"></i>
                                <?= $statusText ?>
                            </span>

                            <?php if (!is_null($row['is_approve'])): ?>
                                <div class="mt-1">
                                    <?php if ($row['is_approve'] == 1): ?>
                                        <span class="badge bg-success" data-bs-toggle="tooltip" title="Approved on <?= date('d-m-Y H:i', strtotime($row['approval_date'])) ?>">
                                            <i class="fa-solid fa-check-double"></i> Approved
                                        </span>
                                    <?php elseif ($row['is_approve'] == 2): ?>
                                        <span class="badge bg-danger" data-bs-toggle="tooltip" title="Rejected on <?= date('d-m-Y H:i', strtotime($row['approval_date'])) ?>">
                                            <i class="fa-solid fa-xmark"></i> Rejected
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php
                            $picId   = isset($row['pic_id']) ? trim((string)$row['pic_id']) : '';
                            $status  = isset($row['status']) ? (int)$row['status'] : 0;
                            $approvalById = isset($row['approval_by_id']) ? trim((string)$row['approval_by_id']) : '';
                            $picById = isset($row['pic_id']) ? trim((string)$row['pic_id']) : '';

                            if ($picId === '' && $status !== 3):
                            ?>
                                <div class="mt-1">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fa-solid fa-user-slash"></i> PIC belum ditunjuk
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php
                            if ((int)$row['status'] === 4 && (string)$row['is_approve'] === '0'):
                            ?>
                                <div class="mt-1">
                                    <span class="badge bg-secondary">
                                        <i class="fa-solid fa-clock"></i> Menunggu Approval
                                    </span>
                                </div>
                            <?php endif; ?>

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

                            if (
                                empty($dueDate) ||
                                $dueDate === '0000-00-00'
                            ) {
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
                            <div>
                                <i class="fa-solid fa-user text-muted"></i>
                                <?= htmlspecialchars($row['create_by']) ?>
                            </div>
                            <small class="text-muted">
                                <i class="fa-solid fa-clock"></i>
                                <?= date('d-m-Y H:i', strtotime($row['create_date'])) ?>
                            </small>
                        </td>
                        <td>
                            <?= trim((string)$row['approval_by']) === '' ? '-' : htmlspecialchars($row['approval_by']) ?>
                        </td>
                        <?php
                        $status = (int)$row['status'];
                        ?>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">

                                <?php if ($ENABLE_MANAGE): ?>

                                    <?php if (in_array($status, [0, 2]) && $picById == $loginUserId): // open, pending 
                                    ?>
                                        <button type="button"
                                            class="btn btn-primary btn-sm px-2 py-1 process-status"
                                            data-id="<?= $row['id'] ?>"
                                            title="Process Ticket">
                                            <i class="fa-solid fa-angles-right"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($status === 1 && $picById == $loginUserId): // process 
                                    ?>
                                        <button type="button"
                                            class="btn btn-secondary btn-sm px-2 py-1 pending-status"
                                            data-id="<?= $row['id'] ?>"
                                            title="Pending Ticket">
                                            <i class="fa-solid fa-hourglass-half"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($status === 0): // open, process, pending 
                                    ?>
                                        <button type="button"
                                            class="btn btn-danger btn-sm px-2 py-1 cancel-status"
                                            data-id="<?= $row['id'] ?>"
                                            title="Cancel Ticket">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($status === 1 && $picById == $loginUserId): // process 
                                    ?>
                                        <button type="button"
                                            class="btn btn-success btn-sm px-2 py-1 done-status"
                                            data-id="<?= $row['id'] ?>"
                                            title="Done Ticket">
                                            <i class="fa-solid fa-clipboard-check"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($ENABLE_VIEW):
                                ?>
                                    <button type="button"
                                        class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                        data-id="<?= $row['id'] ?>"
                                        title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE && $status === 0): ?>
                                    <button type="button"
                                        class="btn btn-warning btn-sm px-2 py-1 edit-ticket"
                                        data-id="<?= $row['id'] ?>"
                                        title="Edit Ticket">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE && $status === 4 && $approvalById !== '' && $approvalById !== '0' && $approvalById == $loginUserId): ?>
                                    <button type="button"
                                        class="btn btn-danger btn-sm px-2 py-1 reject-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="reject Ticket">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($ENABLE_MANAGE && $status === 4 && $approvalById !== '' && $approvalById !== '0' && $approvalById == $loginUserId): ?>
                                    <button type="button"
                                        class="btn btn-success btn-sm px-2 py-1 approve-status"
                                        data-id="<?= $row['id'] ?>"
                                        title="approve Ticket">
                                        <i class="fa-solid fa-check-double"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>

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
                <tr>
                    <td colspan="12" class="text-center">
                        <div class="py-4">
                            <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No helpdesk tickets found</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>