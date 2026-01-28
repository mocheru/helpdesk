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

	.skeleton-button {
		width: 36px;
		height: 36px;
		border-radius: 4px;
		display: inline-block;
		margin-left: 5px;
	}

	/* Toggle Switch CSS */
	.toggle-switch {
		position: relative;
		width: 90px;
		height: 34px;
		display: inline-block;
	}

	.toggle-switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.toggle-slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #dc3545;
		transition: all 0.4s;
		border-radius: 34px;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	}

	.toggle-slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		transition: all 0.4s;
		border-radius: 50%;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	}

	.toggle-switch input:checked+.toggle-slider {
		background-color: #28a745;
	}

	.toggle-switch input:checked+.toggle-slider:before {
		transform: translateX(56px);
	}

	.toggle-slider:after {
		content: 'Non-Aktif';
		position: absolute;
		right: 8px;
		top: 50%;
		transform: translateY(-50%);
		color: white;
		font-size: 11px;
		font-weight: 600;
		opacity: 1;
		transition: opacity 0.2s;
		white-space: nowrap;
	}

	.toggle-switch input:checked+.toggle-slider:after {
		content: 'Aktif';
		right: auto;
		left: 12px;
	}

	.client-apps-empty {
		color: #999;
		font-style: italic;
		font-size: 13px;
	}
</style>

<div class="card shadow-sm border-0">
	<div class="card-header bg-white d-flex align-items-center">
		<div class="d-flex align-items-center">
			<label class="me-2 mb-0 fw-semibold">Filter:</label>
			<select id="filter-user-type" class="form-select form-select-sm" style="width: 180px;">
				<option value="all">All Users</option>
				<option value="internal">Internal</option>
				<option value="external">External</option>
				<option value="ba">Business Analyst</option>
			</select>
		</div>
		<div class="ms-auto d-flex align-items-center gap-2">
			<?php if ($ENABLE_ADD) : ?>
				<a href="<?= site_url('users/setting/create') ?>" class="btn btn-sm btn-success">
					<i class="fa fa-plus me-1"></i>Add User
				</a>
			<?php endif; ?>
			<button class="btn btn-outline-secondary btn-sm refresh-users">
				<i class="fa fa-refresh"></i> Refresh
			</button>
		</div>
	</div>

	<div class="card-body">
		<!-- Skeleton Loading -->
		<div id="skeleton-loading">
			<div class="table-responsive">
				<table class="table table-striped table-hover align-middle w-100">
					<tbody>
						<?php for ($i = 0; $i < 5; $i++) : ?>
							<tr>
								<td>
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line short"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
								<td>
									<div class="skeleton skeleton-line medium"></div>
								</td>
							</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Content Area -->
		<div id="users-content" style="display:none;"></div>
	</div>
</div>

<script>
	$(document).ready(function() {
		loadUsers();

		function loadUsers(filter = 'all') {
			$.ajax({
				url: siteurl + active_controller + 'setting/get_data',
				type: 'GET',
				data: {
					filter_type: filter
				},
				beforeSend: function() {
					$('#skeleton-loading').show();
					$('#users-content').hide();
				},
				success: function(response) {
					$('#skeleton-loading').hide();
					$('#users-content').html(response).fadeIn();

					// Initialize tooltips
					const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
					tooltipTriggerList.forEach((el) => new bootstrap.Tooltip(el));

					if ($.fn.DataTable.isDataTable('#user-table')) {
						$('#user-table').DataTable().destroy();
					}

					$('#user-table').DataTable({
						paging: true,
						searching: true,
						order: [],
						info: true,
						responsive: true,
					});
				},
				error: function(xhr, status, error) {
					$('#skeleton-loading').hide();
					$('#users-content')
						.html('<div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data users.</div>')
						.show();
					console.error('Error:', error);
				}
			});
		}

		$('#filter-user-type').on('change', function() {
			const filterValue = $(this).val();
			loadUsers(filterValue);
		});

		$(document).on('click', '.refresh-users', function(e) {
			e.preventDefault();
			const currentFilter = $('#filter-user-type').val();
			loadUsers(currentFilter);
		});

		$(document).on('change', '.toggle-status-checkbox', function() {
			const idUser = $(this).data('id');
			const currentStatus = $(this).data('status');
			const checkbox = $(this);

			$.ajax({
				url: '<?= site_url('users/setting/toggle_status') ?>',
				type: 'POST',
				data: {
					id_user: idUser,
					current_status: currentStatus
				},
				dataType: 'json',
				beforeSend: function() {
					checkbox.prop('disabled', true);
				},
				success: function(response) {
					if (response.status) {
						checkbox.data('status', response.new_status);
					} else {
						checkbox.prop('checked', currentStatus == 1);
					}
				},
				error: function(xhr, status, error) {
					checkbox.prop('checked', currentStatus == 1);
					console.error('Error:', error);
					showToast('Error occurred. Please try again.', 'error');
				},
				complete: function() {
					checkbox.prop('disabled', false);
				}
			});
		});
	});
</script>