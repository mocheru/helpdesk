<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row">

				<!-- PROFILE -->
				<div class="col-lg-5 text-center border-end profile-left">
					<div class="mt-3">
						<div class="chat-avtar d-inline-flex mx-auto mb-3 position-relative profile-avatar-wrapper">
							<img
								id="profilePhoto"
								class="rounded-circle img-fluid wid-90 img-thumbnail profile-avatar"
								src="<?= (isset($user->photo) && file_exists('assets/images/users/' . $user->photo))
											? base_url('assets/images/users/' . $user->photo)
											: base_url('assets/images/male-def.png'); ?>"
								alt="User image">

							<div class="profile-action">
								<button
									type="button"
									class="btn btn-sm btn-icon btn-light-secondary rounded-circle change-photo-btn"
									title="Ganti Foto">
									<i class="ti ti-camera"></i>
								</button>

								<?php if (isset($user->photo) && !empty($user->photo)): ?>
									<button
										type="button"
										class="btn btn-sm btn-icon btn-light-danger rounded-circle delete-photo-btn"
										title="Hapus Foto">
										<i class="ti ti-trash"></i>
									</button>
								<?php endif; ?>
							</div>

							<input type="file" id="photoInput" style="display:none;" accept="image/*">
						</div>

						<h5 class="mb-1"><?= $user->nm_lengkap; ?></h5>
						<p class="text-muted text-sm mb-1">
							<i class="ti ti-at"></i> <?= $user->username; ?>
						</p>
						<div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
							<span class="badge bg-light-<?= $user->st_aktif == 1 ? 'success' : 'danger'; ?>">
								<?= $user->st_aktif == 1 ? 'Aktif' : 'Non-Aktif'; ?>
							</span>
							<span class="badge bg-light-info">
								<?= $user->status == 1 ? 'External' : 'Internal'; ?>
							</span>
							<?php if ($user->is_ba == 1): ?>
								<span class="badge bg-light-primary">Business Analyst</span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- INFORMASI LAINNYA -->
				<div class="col-lg-7">
					<h5 class="mb-3">Informasi Lainnya</h5>

					<ul class="list-group list-group-flush">
						<li class="list-group-item px-0">
							<div class="d-flex justify-content-between">
								<span class="text-muted">
									<i class="ti ti-phone me-2"></i>No. HP
								</span>
								<span class="fw-medium"><?= $user->hp ?: '-'; ?></span>
							</div>
						</li>

						<li class="list-group-item px-0">
							<div class="d-flex justify-content-between">
								<span class="text-muted">
									<i class="ti ti-apps me-2"></i>Email
								</span>
								<span class="fw-medium text-end"><?= $user->email ?: '-'; ?></span>
							</div>
						</li>

						<li class="list-group-item px-0">
							<div class="d-flex justify-content-between">
								<span class="text-muted">
									<i class="ti ti-map-pin me-2"></i>Kota
								</span>
								<span class="fw-medium"><?= $user->kota ?: '-'; ?></span>
							</div>
						</li>

						<li class="list-group-item px-0">
							<div class="d-flex justify-content-between">
								<span class="text-muted">
									<i class="ti ti-home me-2"></i>Alamat
								</span>
								<span class="fw-medium text-end"><?= $user->alamat ?: '-'; ?></span>
							</div>
						</li>

						<li class="list-group-item px-0">
							<div class="d-flex justify-content-between">
								<span class="text-muted">
									<i class="ti ti-apps me-2"></i>Client Apps
								</span>
								<span class="fw-medium text-end"><?= $client_apps ?: '-'; ?></span>
							</div>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>
</div>


<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row">

				<!-- INFORMASI PERSONAL -->
				<div class="col-lg-6 border-end">
					<h5 class="mb-3">Informasi Personal</h5>

					<form id="formPersonalInfo">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="nm_lengkap" value="<?= $user->nm_lengkap; ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" class="form-control" name="email" value="<?= $user->email; ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label">No. HP <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="hp" value="<?= $user->hp; ?>" required>
							</div>

							<div class="col-md-6">
								<label class="form-label">Kota <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="kota" value="<?= $user->kota; ?>" required>
							</div>

							<div class="col-12">
								<label class="form-label">Alamat <span class="text-danger">*</span></label>
								<textarea class="form-control" name="alamat" rows="3" required><?= $user->alamat; ?></textarea>
							</div>

							<div class="col-12">
								<button type="submit" class="btn btn-primary">
									<i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
								</button>
							</div>
						</div>
					</form>
				</div>

				<!-- PENGATURAN AKUN -->
				<div class="col-lg-6">
					<h5 class="mb-3">Pengaturan Akun</h5>

					<!-- Username -->
					<div class="mb-4">
						<h6 class="mb-3">Username</h6>
						<form id="formUsername">
							<div class="row g-3 align-items-end">
								<div class="col-md-8">
									<label class="form-label">Username Baru</label>
									<input type="text" class="form-control" name="username" value="<?= $user->username; ?>" required>
								</div>
								<div class="col-md-4">
									<button type="submit" class="btn btn-primary w-100">
										<i class="ti ti-edit me-2"></i>Update Username
									</button>
								</div>
							</div>
						</form>
					</div>

					<hr>

					<!-- Password -->
					<div>
						<h6 class="mb-3">Password</h6>
						<form id="formPassword">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label">Password Lama <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="password" class="form-control" name="current_password" id="currentPassword" required>
										<button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="currentPassword">
											<i class="ti ti-eye"></i>
										</button>
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label">Password Baru <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="password" class="form-control" name="new_password" id="newPassword" required>
										<button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="newPassword">
											<i class="ti ti-eye"></i>
										</button>
									</div>
									<small class="text-muted">Min. 5 karakter</small>
								</div>

								<div class="col-md-6">
									<label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
										<button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="confirmPassword">
											<i class="ti ti-eye"></i>
										</button>
									</div>
								</div>

								<div class="col-12">
									<button type="submit" class="btn btn-primary">
										<i class="ti ti-lock me-2"></i>Update Password
									</button>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		// Toggle password visibility
		$(document).on('click', '.toggle-password-btn', function() {
			const fieldId = $(this).data('target');
			const $field = $('#' + fieldId);
			const $icon = $(this).find('i');

			if ($field.attr('type') === 'password') {
				$field.attr('type', 'text');
				$icon.removeClass('ti-eye').addClass('ti-eye-off');
			} else {
				$field.attr('type', 'password');
				$icon.removeClass('ti-eye-off').addClass('ti-eye');
			}
		});

		// Change Photo Button
		$(document).on('click', '.change-photo-btn', function() {
			$('#photoInput').click();
		});

		// Delete Photo
		$(document).on('click', '.delete-photo-btn', function() {
			Swal.fire({
				title: 'Hapus Foto Profile?',
				text: 'Foto profile akan dihapus',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + 'profile/delete_photo',
						type: 'POST',
						dataType: 'json',
						beforeSend: function() {
							Swal.fire({
								title: 'Menghapus...',
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});
						},
						success: function(response) {
							if (response.status == 1) {
								$('#profilePhoto').attr('src', response.default_photo + '?' + new Date().getTime());

								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message,
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									location.reload();
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									text: response.message
								});
							}
						},
						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: 'Terjadi kesalahan saat menghapus foto'
							});
						}
					});
				}
			});
		});

		// Upload Photo
		$('#photoInput').on('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				// Validate file type
				if (!file.type.match('image.*')) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'File harus berupa gambar'
					});
					return;
				}

				// Validate file size (max 2MB)
				if (file.size > 2048000) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Ukuran file maksimal 2MB'
					});
					return;
				}

				const formData = new FormData();
				formData.append('photo', file);

				Swal.fire({
					title: 'Uploading...',
					text: 'Sedang mengupload foto',
					allowOutsideClick: false,
					didOpen: () => {
						Swal.showLoading();
					}
				});

				$.ajax({
					url: siteurl + 'profile/update_photo',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						if (response.status == 1) {
							$('#profilePhoto').attr('src', response.photo_url + '?' + new Date().getTime());
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: response.message,
								timer: 2000,
								showConfirmButton: false
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: response.message
							});
						}
					},
					error: function() {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Terjadi kesalahan saat upload'
						});
					}
				});
			}
		});

		// Update Personal Info
		$('#formPersonalInfo').on('submit', function(e) {
			e.preventDefault();

			$.ajax({
				url: siteurl + 'profile/update_info',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Menyimpan...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan'
					});
				}
			});
		});

		// Update Username
		$('#formUsername').on('submit', function(e) {
			e.preventDefault();

			$.ajax({
				url: siteurl + 'profile/update_username',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Menyimpan...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan'
					});
				}
			});
		});

		// Update Password
		$('#formPassword').on('submit', function(e) {
			e.preventDefault();

			const newPass = $('input[name="new_password"]').val();
			const confirmPass = $('input[name="confirm_password"]').val();

			if (newPass !== confirmPass) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Konfirmasi password tidak sesuai'
				});
				return;
			}

			$.ajax({
				url: siteurl + 'profile/update_password',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Menyimpan...',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});
				},
				success: function(response) {
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						}).then(() => {
							$('#formPassword')[0].reset();
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							html: response.message
						});
					}
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Terjadi kesalahan'
					});
				}
			});
		});
	});
</script>