<?php
$ENABLE_ADD     = has_permission('Helpdesk.Add');
$ENABLE_MANAGE  = has_permission('Helpdesk.Manage');
$ENABLE_VIEW    = has_permission('Helpdesk.View');
$ENABLE_DELETE  = has_permission('Helpdesk.Delete');
?>

<style>
	#app-loader {
		display: none !important;
	}

	.skeleton {
		background: #f2f2f2;
		border-radius: 4px;
		animation: shimmer 1.5s infinite linear;
		background: linear-gradient(90deg, #f2f2f2 25%, #e0e0e0 50%, #f2f2f2 75%);
		background-size: 200% 100%;
	}

	@keyframes shimmer {
		0% {
			background-position: 200% 0;
		}

		100% {
			background-position: -200% 0;
		}
	}

	.skeleton-line {
		height: 20px;
		margin: 8px 0;
	}

	.skeleton-line.short {
		width: 60%;
	}

	.skeleton-line.medium {
		width: 80%;
	}
</style>

<!-- Berry Card -->
<div class="card">
	<div class="card-body">
		<!-- Tab Navigation -->
		<div class="mb-3">
			<ul class="nav nav-tabs" id="helpdeskTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="open-tab" data-bs-toggle="tab" data-bs-target="#tab-open" type="button" role="tab">
						<i class="fa-solid fa-circle-dot"></i> Open
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#tab-approved" type="button" role="tab">
						<i class="fa-solid fa-check-double"></i> Approved
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="cancel-tab" data-bs-toggle="tab" data-bs-target="#tab-cancel" type="button" role="tab">
						<i class="fa-solid fa-ban"></i> Cancel
					</button>
				</li>
			</ul>
		</div>



		<!-- Tab Content -->
		<div class="tab-content" id="helpdeskTabContent">

			<!-- OPEN TAB -->
			<div class="tab-pane fade show active" id="tab-open" role="tabpanel">
				<div class="mb-3">
					<div class="d-flex justify-content-end gap-2">
						<button class="btn btn-primary btn-sm refresh-list-helpdesk">
							<i class="fa-solid fa-arrows-rotate"></i> Refresh
						</button>

						<?php if (has_permission('Helpdesk.Add')): ?>
							<a href="<?= site_url('helpdesk/add_ticket') ?>" class="btn btn-success btn-sm">
								<i class="fa-solid fa-plus"></i> Add New Ticket
							</a>
						<?php endif; ?>
					</div>
				</div>

				<!-- OPEN CONTENT -->
				<div id="skeleton-loading-open"></div>
				<div id="helpdesk-content-open" style="display:none;"></div>

			</div>

			<!-- APPROVED TAB -->
			<div class="tab-pane fade" id="tab-approved" role="tabpanel">
				<div id="skeleton-loading-approved"></div>
				<div id="helpdesk-content-approved" style="display:none;"></div>
			</div>

			<!-- CANCEL TAB -->
			<div class="tab-pane fade" id="tab-cancel" role="tabpanel">
				<div id="skeleton-loading-cancel"></div>
				<div id="helpdesk-content-cancel" style="display:none;"></div>
			</div>

		</div>


		<!-- Content Area -->
		<!-- <div id="helpdesk-content" style="display:none;"></div> -->
	</div>
</div>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
	function loadHelpdeskList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_ticket',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading-open').html(getSkeletonHTML()).show();
				$('#helpdesk-content-open').hide();
			},
			success: function(response) {
				$('#skeleton-loading-open').hide();
				$('#helpdesk-content-open').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_helpdesk')) {
					$('#table_helpdesk').DataTable().destroy();
				}

				$('#table_helpdesk').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});
			},
			error: function() {
				$('#skeleton-loading-open').hide();
				$('#helpdesk-content-open')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data helpdesk.</div>')
					.show();
			}
		});
	}

	function loadApprovedList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_approved',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading-approved').html(getSkeletonHTML()).show();
				$('#helpdesk-content-approved').hide();
			},
			success: function(response) {
				$('#skeleton-loading-approved').hide();
				$('#helpdesk-content-approved').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_approved')) {
					$('#table_approved').DataTable().destroy();
				}

				$('#table_approved').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});
			},
			error: function() {
				$('#skeleton-loading-approved').hide();
				$('#helpdesk-content-approved')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data approved.</div>')
					.show();
			}
		});
	}

	function loadCancelList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_cancel',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading-cancel').html(getSkeletonHTML()).show();
				$('#helpdesk-content-cancel').hide();
			},
			success: function(response) {
				$('#skeleton-loading-cancel').hide();
				$('#helpdesk-content-cancel').html(response).fadeIn();

				if ($.fn.DataTable.isDataTable('#table_cancel')) {
					$('#table_cancel').DataTable().destroy();
				}

				$('#table_cancel').DataTable({
					paging: true,
					searching: true,
					order: [],
					info: true,
					responsive: true,
				});
			},
			error: function() {
				$('#skeleton-loading-cancel').hide();
				$('#helpdesk-content-cancel')
					.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data cancel.</div>')
					.show();
			}
		});
	}

	function getSkeletonHTML() {
		var skeletonRows = '';
		for (var i = 0; i < 5; i++) {
			skeletonRows += `
				<tr>
					<td width="5%"><div class="skeleton skeleton-line short"></div></td>
					<td><div class="skeleton skeleton-line medium"></div></td>
					<td width="15%"><div class="skeleton skeleton-line short"></div></td>
					<td width="15%"><div class="skeleton skeleton-line short"></div></td>
					<td width="15%"><div class="skeleton skeleton-line medium"></div></td>
					<td width="15%"><div class="skeleton skeleton-line medium"></div></td>
					<td width="20%"><div class="skeleton skeleton-line short"></div></td>
				</tr>
			`;
		}

		return `
			<div class="table-responsive">
				<table class="table table-bordered">
					<tbody>${skeletonRows}</tbody>
				</table>
			</div>
   	 `;
	}


	function updateTicketApproval(ticketId, action) {
		var actionText = (action === 'approve') ? 'approve' : 'reject';
		var actionUpper = actionText.charAt(0).toUpperCase() + actionText.slice(1);
		var isApprove = (action === 'approve') ? 1 : 2;

		var modalContent = `
			<div class="mb-3">
				<p>
					${action === 'approve'
						? 'Ticket akan ditandai sebagai selesai dan ditutup.'
						: 'Ticket akan dikembalikan untuk revisi.'}
				</p>
				<div class="form-group mt-3">
					<label for="approvalReason" class="form-label">
						${action === 'approve' ? 'Catatan Approval' : 'Alasan Penolakan'}
						<small class="text-danger"></small>
					</label>
					<textarea
						class="form-control"
						id="approvalReason"
						rows="3"
						placeholder="${action === 'approve'
							? 'Masukkan catatan approval...'
							: 'Masukkan alasan penolakan...'}"
					></textarea>
				</div>
			</div>
		`;

		Swal.fire({
			title: 'Konfirmasi ' + actionUpper,
			html: modalContent,
			icon: (action === 'approve') ? 'question' : 'warning',
			showCancelButton: true,
			confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, ' + actionUpper,
			cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal',
			confirmButtonColor: (action === 'approve') ? '#198754' : '#dc3545',
			cancelButtonColor: '#6c757d',
			preConfirm: () => {
				var approvalReason = $('#approvalReason').val();

				if (!approvalReason || !approvalReason.trim()) {
					Swal.showValidationMessage(
						action === 'approve' ?
						'Catatan approval wajib diisi' :
						'Alasan penolakan wajib diisi'
					);
					return false;
				}

				return {
					approvalReason: approvalReason.trim()
				};
			},
			allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
			if (result.isConfirmed) {
				Swal.fire({
					title: 'Processing...',
					html: '<i class="fa-solid fa-spinner fa-spin fa-2x"></i>',
					allowOutsideClick: false,
					showConfirmButton: false
				});

				$.ajax({
					url: siteurl + active_controller + 'update_approval',
					type: 'POST',
					data: {
						id: ticketId,
						is_approve: isApprove,
						approval_reason: result.value.approvalReason
					},
					dataType: 'json',
					success: function(response) {
						if (response.status == 1) {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil!',
								text: response.message,
								timer: 2000,
								showConfirmButton: false
							});
							loadHelpdeskList();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Gagal!',
								text: response.message
							});
						}
					},
					error: function(xhr) {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: xhr.responseJSON?.message || 'Terjadi kesalahan pada sistem'
						});
					}
				});
			}
		});
	}

	function changeTicketStatus(ticketId, status, statusText) {
		var modalContent = '';
		var showReasonInput = false;

		switch (status) {
			case 1: // Process
				modalContent = '<h2>Ubah status ticket menjadi Process?</h2>';
				break;

			case 2: // Pending
				modalContent = '<h2>Ubah status ticket menjadi Pending?</h2>';
				break;

			case 3: // Cancel
				modalContent = `
					<div class="mb-3">
						<h2>Ubah status ticket menjadi Cancel?</h2>
						<div class="form-group mt-3">
							<label for="cancelReason" class="form-label">
								Alasan Pembatalan <small class="text-danger"></small>
							</label>
							<textarea
								class="form-control"
								id="cancelReason"
								rows="3"
								placeholder="Masukkan alasan pembatalan..."
							></textarea>
						</div>
					</div>
				`;
				showReasonInput = true;
				break;

			case 4: // Done
				modalContent = '<h2>Ubah status ticket menjadi Done?</h2>';
				break;

			default:
				modalContent = '<h2>Konfirmasi perubahan status?</h2>';
		}

		Swal.fire({
			html: modalContent,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Ubah Status',
			cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#6c757d',
			showLoaderOnConfirm: true,
			preConfirm: () => {
				var cancelReason = showReasonInput ? $('#cancelReason').val() : '';

				// ✅ VALIDASI WAJIB ISI UNTUK CANCEL
				if (status === 3 && (!cancelReason || !cancelReason.trim())) {
					Swal.showValidationMessage('Alasan pembatalan wajib diisi');
					return false;
				}

				return new Promise((resolve, reject) => {
					$.ajax({
						url: siteurl + active_controller + 'update_status',
						type: 'POST',
						data: {
							id: ticketId,
							status: status,
							cancel_reason: cancelReason ? cancelReason.trim() : ''
						},
						dataType: 'json',
						success: function(response) {
							if (response.status == 1) {
								resolve(response);
							} else {
								reject(new Error(response.message || 'Terjadi kesalahan'));
							}
						},
						error: function(xhr, status, error) {
							reject(new Error(
								xhr.responseJSON?.message || error || 'Request gagal'
							));
						}
					});
				});
			},
			allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
			if (result.isConfirmed && result.value) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: result.value.message,
					timer: 2000,
					showConfirmButton: false
				});
				loadHelpdeskList();
			}
		});
	}

	// function updateTicketCounters() {
	// 	$.ajax({
	// 		url: siteurl + active_controller + 'get_ticket_counters',
	// 		type: 'GET',
	// 		success: function(response) {
	// 			if (response.status) {
	// 				$('#badge-open').text(response.data.open || 0);
	// 				$('#badge-process').text(response.data.process || 0);
	// 				$('#badge-pending').text(response.data.pending || 0);
	// 				$('#badge-total').text(response.data.total || 0);
	// 			}
	// 		}
	// 	});
	// }

	$(document).ready(function() {
		loadHelpdeskList();

		// $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
		// 	var target = $(e.target).data('bs-target');

		// 	if (target === '#tab-open') {
		// 		loadHelpdeskList();
		// 	} else if (target === '#tab-approved') {
		// 		loadApprovedList();
		// 	} else if (target === '#tab-cancel') {
		// 		loadCancelList();
		// 	}
		// });

		// Refresh button
		// $(document).on('click', '.refresh-list-helpdesk', function(e) {
		// 	e.preventDefault();
		// 	var activeTab = $('.nav-tabs .nav-link.active').attr('id');

		// 	if (activeTab === 'open-tab') {
		// 		loadHelpdeskList();
		// 	} else if (activeTab === 'approved-tab') {
		// 		loadApprovedList();
		// 	} else if (activeTab === 'cancel-tab') {
		// 		loadCancelList();
		// 	}
		// });

		$(document).on('click', '.refresh-list-helpdesk', function(e) {
			e.preventDefault();
			loadHelpdeskList();
		});

		// Event untuk view detail ticket
		$(document).on('click', '.view-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			window.location.href = siteurl + active_controller + 'view_ticket/' + ticketId;
		});

		// Event untuk edit ticket
		$(document).on('click', '.edit-ticket', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			window.location.href = siteurl + active_controller + 'edit_ticket/' + ticketId;
		});

		// Event handlers untuk button status
		$(document).on('click', '.process-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 1, 'Process');
		});

		$(document).on('click', '.pending-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 2, 'Pending');
		});

		$(document).on('click', '.cancel-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 3, 'Cancel');
		});

		$(document).on('click', '.done-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');
			changeTicketStatus(ticketId, 4, 'Done');
		});

		$(document).on('click', '.approve-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');

			updateTicketApproval(ticketId, 'approve');
		});

		$(document).on('click', '.reject-status', function(e) {
			e.preventDefault();
			var ticketId = $(this).data('id');

			updateTicketApproval(ticketId, 'reject');
		});

	});
</script>