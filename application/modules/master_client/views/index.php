<?php
$ENABLE_ADD     = has_permission('Master_client.Add');
$ENABLE_MANAGE  = has_permission('Master_client.Manage');
$ENABLE_VIEW    = has_permission('Master_client.View');
$ENABLE_DELETE  = has_permission('Master_client.Delete');
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

	.add-form-container {
		background-color: #f8f9fa;
		border: 2px solid var(--bs-primary);
		border-radius: 8px;
		padding: 20px;
		margin-bottom: 20px;
	}

	.form-edit-row {
		background-color: #fff9e6;
	}

	.inline-form-input {
		width: 100%;
		padding: 8px 12px;
		border: 1px solid #dee2e6;
		border-radius: 6px;
		font-size: 0.875rem;
	}

	.inline-form-input:focus {
		border-color: var(--bs-primary);
		outline: 0;
		box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
	}

	.inline-form-textarea {
		width: 100%;
		padding: 8px 12px;
		border: 1px solid #dee2e6;
		border-radius: 6px;
		resize: vertical;
		min-height: 80px;
		font-size: 0.875rem;
	}

	.inline-form-textarea:focus {
		border-color: var(--bs-primary);
		outline: 0;
		box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
	}
</style>

<div class="card">
	<div class="card-body">
		<!-- Header Section -->
		<div class="mb-3">
			<div class="row align-items-center">
				<div class="col-md-8">
					<div id="button-add-container">
						<?php if ($ENABLE_ADD): ?>
							<button class="btn btn-success btn-sm add-client">
								<i class="fa-solid fa-plus"></i> Add Client
							</button>
						<?php endif; ?>
					</div>

					<!-- Form Add Client -->
					<div id="form-add-container" style="display:none;">
						<div class="add-form-container">
							<div class="row g-3">
								<div class="col-md-5">
									<label for="new_name_app" class="form-label">
										Application Name <span class="text-danger">*</span>
									</label>
									<input type="text" class="form-control" id="new_name_app"
										placeholder="Enter application name">
								</div>
								<div class="col-md-5">
									<label for="new_remark" class="form-label">Remark</label>
									<textarea class="form-control" id="new_remark" rows="3"
										placeholder="Enter remark (optional)"></textarea>
								</div>
								<div class="col-md-2">
									<label class="form-label d-block">&nbsp;</label>
									<button type="button" class="btn btn-primary btn-sm w-100 save-new-client">
										<i class="fa-solid fa-floppy-disk"></i> Save
									</button>
									<button type="button" class="btn btn-secondary btn-sm w-100 mt-2 cancel-add-client">
										<i class="fa-solid fa-xmark"></i> Cancel
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 text-end">
					<button class="btn btn-primary btn-sm refresh-list-client">
						<i class="fa-solid fa-rotate"></i> Refresh
					</button>
				</div>
			</div>
		</div>

		<!-- Skeleton Loading -->
		<div id="skeleton-loading">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tbody>
						<?php for ($i = 0; $i < 5; $i++): ?>
							<tr>
								<td width="5%">
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td width="20%">
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td width="15%">
									<div class="skeleton skeleton-line short"></div>
								</td>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Content Area -->
		<div id="client-content" style="display:none;"></div>
	</div>
</div>


<script>
	var dataTableInstance = null;

	function loadClientList() {
		$.ajax({
			url: siteurl + active_controller + 'get_list_client',
			type: 'GET',
			beforeSend: function() {
				$('#skeleton-loading').show();
				$('#client-content').hide();
			},
			success: function(response) {
				$('#skeleton-loading').hide();
				$('#client-content').html(response).fadeIn();
			},
			error: function() {
				$('#skeleton-loading').hide();
				$('#client-content')
					.html('<div class="alert alert-danger">Gagal memuat data client.</div>')
					.show();
			}
		});
	}

	function resetAddForm() {
		$('#new_name_app').val('');
		$('#new_remark').val('');
		$('.save-new-client')
			.prop('disabled', false)
			.html('<i class="ti ti-device-floppy"></i> Save');
	}

	$(document).ready(function() {

		loadClientList();

		$(document).on('click', '.refresh-list-client', function(e) {
			e.preventDefault();
			loadClientList();
		});

		$(document).on('click', '.add-client', function() {

			if ($('.form-edit-row').length > 0) {
				Swal.fire({
					icon: 'info',
					title: 'Informasi',
					text: 'Silakan selesaikan atau batalkan form edit terlebih dahulu.'
				});
				return;
			}

			resetAddForm();

			$('#button-add-container').hide();
			$('#form-add-container').slideDown();
			$('#new_name_app').focus();
		});

		$(document).on('click', '.cancel-add-client', function() {
			$('#form-add-container').slideUp(function() {
				$('#button-add-container').show();
				resetAddForm();
			});
		});

		$(document).on('click', '.save-new-client', function() {
			var nameApp = $('#new_name_app').val().trim();
			var remark = $('#new_remark').val().trim();

			if (nameApp === '') {
				Swal.fire({
					icon: 'warning',
					title: 'Peringatan',
					text: 'Nama aplikasi wajib diisi!'
				});
				$('#new_name_app').focus();
				return;
			}

			var formData = {
				name_app: nameApp,
				remark: remark
			};

			$.ajax({
				url: siteurl + active_controller + 'add_client',
				type: 'POST',
				data: formData,
				dataType: 'json',
				beforeSend: function() {
					$('.save-new-client')
						.prop('disabled', true)
						.html('<i class="ti ti-loader-2 spin"></i> Menyimpan...');
				},
				success: function(response) {

					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});

						$('.save-new-client')
							.prop('disabled', false)
							.html('<i class="ti ti-device-floppy"></i> Save');

						$('#form-add-container').slideUp(function() {
							resetAddForm();
						});
						$('#button-add-container').show();

						loadClientList();

					} else {
						$('.save-new-client')
							.prop('disabled', false)
							.html('<i class="ti ti-device-floppy"></i> Save');

						Swal.fire({
							icon: 'error',
							title: 'Gagal!',
							text: response.message
						});
					}
				},
				error: function() {
					$('.save-new-client')
						.prop('disabled', false)
						.html('<i class="ti ti-device-floppy"></i> Save');

					Swal.fire({
						icon: 'error',
						title: 'Kesalahan!',
						text: 'Terjadi kesalahan pada sistem. Silakan coba lagi.'
					});
				}
			});
		});

		$(document).on('keypress', '#new_name_app', function(e) {
			if (e.which == 13) {
				e.preventDefault();
				$('.save-new-client').click();
			}
		});

		// Edit Client
		$(document).on('click', '.edit-client', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var remark = $(this).data('remark');
			var $currentRow = $('#row-' + id);

			if ($('#form-add-container').is(':visible')) {
				Swal.fire({
					icon: 'info',
					title: 'Informasi',
					text: 'Silakan selesaikan atau batalkan form tambah terlebih dahulu.'
				});
				return;
			}

			var $otherEditingRows = $('.form-edit-row').not($currentRow);
			if ($otherEditingRows.length > 0) {
				Swal.fire({
					icon: 'info',
					title: 'Informasi',
					text: 'Silakan selesaikan atau batalkan proses edit yang sedang berlangsung.'
				});
				return;
			}

			$currentRow.data('original-name', name);
			$currentRow.data('original-remark', remark);
			$currentRow.addClass('form-edit-row');

			$currentRow.find('.client-name-display').html(
				'<input type="text" class="inline-form-input edit-client-name" ' +
				'value="' + name + '" data-id="' + id + '">'
			);

			$currentRow.find('.remark-display').html(
				'<textarea class="inline-form-textarea edit-remark" data-id="' + id + '">' +
				(remark || '') +
				'</textarea>'
			);

			$currentRow.find('.action-buttons').html(`
				<button type="button" class="btn btn-primary btn-sm save-edit-client" data-id="${id}">
					<i class="ti ti-device-floppy"></i> Save
				</button>
				<button type="button" class="btn btn-secondary btn-sm cancel-edit-client" data-id="${id}">
					<i class="ti ti-x"></i> Cancel
				</button>
			`);

			$currentRow.find('.edit-client-name').focus();
		});

		$(document).on('click', '.cancel-edit-client', function() {
			var id = $(this).data('id');
			var $row = $('#row-' + id);
			var originalName = $row.data('original-name');
			var originalRemark = $row.data('original-remark');

			$row.removeClass('form-edit-row');
			$row.find('.client-name-display').html('<strong>' + originalName + '</strong>');
			$row.find('.remark-display').html(originalRemark ? originalRemark : '-');

			$row.find('.action-buttons').html(`
            <?php if ($ENABLE_MANAGE): ?>
                <button type="button" class="btn btn-warning btn-sm edit-client"
                    data-id="${id}"
                    data-name="${originalName}"
                    data-remark="${originalRemark}"
                    title="Edit">
                    <i class="ti ti-edit"></i>
                </button>
            <?php endif; ?>
            <?php if ($ENABLE_DELETE): ?>
                <button type="button" class="btn btn-danger btn-sm delete-client"
                    data-id="${id}"
                    data-name="${originalName}"
                    title="Delete">
                    <i class="ti ti-trash"></i>
                </button>
            <?php endif; ?>
        `);
		});

		$(document).on('click', '.save-edit-client', function() {
			var id = $(this).data('id');
			var nameApp = $('.edit-client-name[data-id="' + id + '"]').val().trim();
			var remark = $('.edit-remark[data-id="' + id + '"]').val().trim();

			if (nameApp === '') {
				Swal.fire({
					icon: 'warning',
					title: 'Warning',
					text: 'Nama Aplikasi wajib diisi.'
				});
				$('.edit-client-name[data-id="' + id + '"]').focus();
				return;
			}

			$.ajax({
				url: siteurl + active_controller + 'edit_client/' + id,
				type: 'POST',
				data: {
					name_app: nameApp,
					remark: remark
				},
				dataType: 'json',
				beforeSend: function() {
					$('.save-edit-client[data-id="' + id + '"]')
						.prop('disabled', true)
						.html('<i class="ti ti-loader-2 spin"></i> Saving...');
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});
						loadClientList();
					} else {
						$('.save-edit-client[data-id="' + id + '"]')
							.prop('disabled', false)
							.html('<i class="ti ti-device-floppy"></i> Save');
						Swal.fire({
							icon: 'error',
							title: 'Failed',
							text: response.message
						});
					}
				},
				error: function() {
					$('.save-edit-client[data-id="' + id + '"]')
						.prop('disabled', false)
						.html('<i class="ti ti-device-floppy"></i> Save');
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'System error occurred.'
					});
				}
			});
		});

		$(document).on('click', '.delete-client', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');

			Swal.fire({
				title: 'Konfirmasi Hapus',
				html: '<p>Apakah Anda yakin ingin menghapus client "<b>' + name + '</b>"?</p>',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus',
				cancelButtonText: 'Batal',
				confirmButtonColor: '#dc3545',
				cancelButtonColor: '#6c757d'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_client',
						type: 'POST',
						data: {
							id: id
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
								loadClientList();
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Gagal!',
									text: response.message
								});
							}
						},
						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Error!',
								text: 'Terjadi kesalahan pada sistem.'
							});
						}
					});
				}
			});
		});

		$(document).on('keypress', '.edit-client-name', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				var id = $(this).data('id');
				$('.save-edit-client[data-id="' + id + '"]').click();
			}
		});
	});
</script>