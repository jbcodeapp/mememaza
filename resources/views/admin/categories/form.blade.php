@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	$banner_image = $thumb_nail = '';
	if($id > 0) {
		$button = 'Update';
		if($obj != null) {
			if($obj->banner_image != null) {
				$banner_image = cdn(PUB.'category/'.$id.'/'.$obj->banner_image);
			}
			
			if($obj->image != null) {
				$thumb_nail = cdn(PUB.'category/'.$id.'/'.$obj->image);
			}
		}
	}
?>

	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Category Form
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
                
				<form method="post" action="{{ url('category_handle') }}" id="category_form" class="form form-horizontal" enctype='multipart/form-data'>
					@csrf
					<div class="row">
						<?php if($id > 0) {  ?>
							<input type="hidden" name="id" class="pkid" value="{{ $id }}" />
						<?php } ?>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Name</label>
								<input type="text" class="form-control name" name="name" value="{{ $obj->name??null }}" placeholder="Enter Category">
							</div>
						</div>
						
						<div class="col-12">
						  <div class="form-group">
							<label for="exampleInputFile">Thumbnail Image</label>
							<div class="input-group image">
							  <div class="custom-file">
								<input type="file" class="custom-file-input image" name="image">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							@if ($thumb_nail != '')
								<br>
								<img src="{{ $thumb_nail }}" height="50" />
							@endif
						  </div>
						</div>
						
						<div class="col-12">
						  <div class="form-group">
							<label for="exampleInputFile">Banner Image</label>
							<div class="input-group banner_image">
							  <div class="custom-file">
								<input type="file" class="custom-file-input banner_image" name="banner_image">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							@if ($banner_image != '')
								<br>
								<img src="{{ $banner_image }}" height="50" />
							@endif
						  </div>
						</div>
						
						 <div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">meta title</label>
								<input type="text" class="form-control meta_title" name="meta_title" value="{{ $obj->meta_title??null }}" placeholder="Enter meta title">
							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">meta keyword</label>
								<input type="text" class="form-control meta_keyword" name="meta_keyword" value="{{ $obj->meta_keyword??null }}" placeholder="Enter meta keyword">
							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">meta desc</label>
								<input type="text" class="form-control meta_desc" name="meta_desc" value="{{ $obj->meta_desc??null }}" placeholder="Enter meta desc">
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
	
	  <?php /* ?>
	  <!-- left column -->
	  <div class="col-md-12">
		<!-- general form elements -->
		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">Add Category</h3>
		  </div>
		  <!-- /.card-header -->
		  <!-- form start -->
		  <form>
			<div class="card-body">
			  <div class="form-group">
				<label for="exampleInputEmail1">Name</label>
				<input type="text" class="form-control name" value="" placeholder="Enter Category">
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputEmail1">Category Type</label>
				<input type="radio" value="1" /> Parent
				<input type="radio" value="2" checked /> Child
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputEmail1">Parent Category</label>
				<select class="form-control parent_id">
					<option value="">Select Parent Category</option>
				</select>
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputFile">Image</label>
				<div class="input-group">
				  <div class="custom-file">
					<input type="file" class="custom-file-input image" id="">
					<label class="custom-file-label" for="exampleInputFile">Choose file</label>
				  </div>
				  <div class="input-group-append">
					<span class="input-group-text">Upload</span>
				  </div>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputFile">Banner Image</label>
				<div class="input-group">
				  <div class="custom-file">
					<input type="file" class="custom-file-input banner_image" id="">
					<label class="custom-file-label" for="exampleInputFile">Choose file</label>
				  </div>
				  <div class="input-group-append">
					<span class="input-group-text">Upload</span>
				  </div>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="exampleInputEmail1">Status</label>
				<select class="form-control status">
					<option value="">Select Status</option>
					<option value="1">Active</option>
					<option value="2">Inactive</option>
				</select>
			  </div>
			  
			</div>
			<!-- /.card-body -->

			<div class="card-footer">
			  <button type="submit" class="btn btn-primary">Submit</button>
			</div>
		  </form>
		</div>
		<!-- /.card -->

	  </div>
	  
	  <!--/.col (left) -->
	  <!-- right column -->
	
	  <div class="col-md-6">
		<div class="card card-danger">
		  <div class="card-header">
			<h3 class="card-title">Different Width</h3>
		  </div>
		  <div class="card-body">
			<div class="row">
			  <div class="col-3">
				<input type="text" class="form-control" placeholder=".col-3">
			  </div>
			  <div class="col-4">
				<input type="text" class="form-control" placeholder=".col-4">
			  </div>
			  <div class="col-5">
				<input type="text" class="form-control" placeholder=".col-5">
			  </div>
			</div>
		  </div>
		  <!-- /.card-body -->
		</div>
		
	  </div><?php */ ?>
	  <!--/.col (right) -->
	</div>
</section>
@endsection


@push('style')

@endpush

@push('scripts')
<script src="{{ cdn('admin/admin.js') }}"></script>

<script type="text/javascript">
	
	$(document).ready(function() {
		CategoryManager.init();
		
	});
</script>
@endpush