<?php
$id = isset($category) ? $category->id : '';
$category_name = isset($category) ? $category->category_name : '';
$remark = isset($category) ? $category->remark : '';
$title = empty($id) ? 'Add New Category' : 'Edit Category';
?>

<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title"><?= $title; ?></h4>
		</div>
		
		<form id="form-category">
			<div class="modal-body">
				<div class="form-group">
					<label for="category_name">Category Name <span class="text-red">*</span></label>
					<input type="text" 
						class="form-control" 
						id="category_name" 
						name="category_name" 
						placeholder="Enter category name" 
						value="<?= $category_name; ?>" 
						required>
				</div>

				<div class="form-group">
					<label for="remark">Remark</label>
					<textarea class="form-control" 
						id="remark" 
						name="remark" 
						rows="3" 
						placeholder="Enter remark (optional)"><?= $remark; ?></textarea>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
				<button type="submit" class="btn btn-primary" id="btn-save-category">
					<i class="fa fa-save"></i> Save
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#form-category').on('submit', function(e) {
			e.preventDefault();
			
			var formData = $(this).serialize();
			var url = siteurl + active_controller;
			
			<?php if (empty($id)): ?>
				url += 'add_category';
			<?php else: ?>
				url += 'edit_category/<?= $id; ?>';
			<?php endif; ?>

			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				dataType: 'json',
				beforeSend: function() {
					$('#btn-save-category').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
				},
				success: function(response) {
					$('#btn-save-category').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
					
					if (response.status == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: response.message,
							timer: 2000,
							showConfirmButton: false
						});
						
						$('#categoryModal').modal('hide');
						loadCategoryList();
					} else {
						Swal.fire('Failed!', response.message, 'error');
					}
				},
				error: function() {
					$('#btn-save-category').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
					Swal.fire('Error!', 'Something went wrong', 'error');
				}
			});
		});
	});
</script>