@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	$vdo = $src = '';
	$title = $image = $desc = $category_id = '';
	if($id > 0) {
		if($obj != null) {
			$button = 'Update';
			$title = $obj->title;
			$desc = $obj->desc;
			if($obj->image != null) {
				$src = cdn(PUB.'uploads/post/'.$obj->image);
			}
			$category_id = $obj->category_id;
		}
	}
?>

	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Post Form
		  </div><!-- /.col -->
		  
		</div><!-- /.row -->
	  </div><!-- /.container-fluid -->
	</div>
	

	
<section class="content">
	<div class="row">
		
		<div class="col-md-12">
		<div class="row">
          <div class="col-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                
              </div>
              <div class="card-body">
                
				<form method="post" action="{{ url('post_handle') }}" id="post_form" class="form form-horizontal" enctype='multipart/form-data'>
					@csrf
					<div class="row">
						<?php if($id > 0) {  ?>
							<input type="hidden" name="id" class="pkid" value="{{ $id }}" />
						<?php } ?>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Title</label>
								<input type="text" class="form-control title" name="title" value="{{ $obj->title??null }}" placeholder="Enter title">
							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Select Category</label>
								<select name="category_id" class="form-control category_id">
									<option value="">Select Category</option>
									<?php foreach($categories as $category) { ?>
										<option value="{{ $category->id }}" <?php if($category->id == $category_id) { echo 'selected'; } ?>>{{ $category->name }}</option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						
						
						<div class="col-12 ">
						  <div class="form-group">
							<label for="exampleInputFile">Upload Image</label>
							<div class="input-group img">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="img">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							@if ($src != '')
								<br>
								
								<img src="{{ $src }}" height="33" />

							@endif
						  </div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Desc</label>
								<textarea id="summernote" name="desc">
								{{ $desc }}
								</textarea>

							</div>
						</div>
						
						  
						<div class="col-12">
							<div class="form-group">
								<div class="card-">
								  <button type="submit" class="btn btn-primary" data-text="{{ $button }}">{{ $button }}</button>
								</div>
							</div>
						</div>
						
					</div>
				</form>

              </div>
              <!-- /.card -->
            </div>
          </div>
          
        </div>
		
	  </div>
	
	</div>
</section>
@endsection


@push('style')

@endpush

@push('scripts')
<script src="{{ cdn('admin/admin.js') }}"></script>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script type="text/javascript">
	$(function () {
			
			$('#summernote').summernote()
		
		});
	$(document).ready(function() {
		
		PostManager.init();
		
	});
</script>
@endpush