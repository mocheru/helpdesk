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

	.skeleton-toggle {
		width: 90px;
		height: 34px;
		border-radius: 34px;
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
	<div class="card-header bg-white d-flex align-items-center justify-content-between">
		<div>
			<?php if ($ENABLE_ADD) : ?>
				<a href="<?= site_url('users/setting/create') ?>" class="btn btn-sm btn-success">
					<i class="fa fa-plus me-1"></i>Add User
				</a>
			<?php endif; ?>
		</div>
		<button class="btn btn-outline-secondary btn-sm refresh-users">
			<i class="fa fa-refresh"></i> Refresh
		</button>
	</div>

	<div class="card-body">
		<!-- Skeleton Loading -->
		<div id="skeleton-loading">
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
									<div class="skeleton skeleton-toggle"></div>
								</td>
								<?php if ($ENABLE_MANAGE) : ?>
									<td class="text-end">
										<div class="skeleton skeleton-button"></div>
										<div class="skeleton skeleton-button"></div>
									</td>
								<?php endif; ?>
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

		function loadUsers() {
			$.ajax({

				url: siteurl + active_controller + 'setting/get_data',
				type: 'GET',
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

		$(document).on('click', '.refresh-users', function(e) {
			e.preventDefault();
			loadUsers();
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