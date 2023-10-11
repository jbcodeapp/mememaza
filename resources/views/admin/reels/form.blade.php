@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	$vdo = $src = $image = $type ='';
	$reel = $reel_type = $link = $category_id = null;
	if($id > 0) {
		if($obj != null) {
			$button = 'Update';
			$reel = $obj->reel;
			$reel_type = $obj->reel_type;
			$link = $obj->link;
			$category_id = $obj->category_id;
			$vdo = '';
			if($obj->reel_type == 1) {
				$type = 1;
			} else if($obj->reel_type == 2) {
				$type = 2;
				//echo $vdo = cdn('uploads/reel/'.$obj->id.'/'.$obj->link);
				$src = cdn(PUB.'uploads/reel/'.$obj->id.'/'.$obj->link);
			} else if($obj->reel_type == 3) {
				$type = 3;
				$src = cdn(PUB.'uploads/reel/'.$obj->link);
				$image = '<img src="'.$src.'" height="33" />';
			}
		}
	}
?>

	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Reel Form
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
                
				<form method="post" action="{{ url('reel_handle') }}" id="reel_form" class="form form-horizontal" enctype='multipart/form-data'>
					@csrf
					<div class="row">
						<?php if($id > 0) {  ?>
							<input type="hidden" name="id" class="pkid" value="{{ $id }}" />
						<?php } ?>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Name</label>
								<input type="text" class="form-control name" name="name" value="{{ $obj->reel??null }}" placeholder="Enter Reel">
							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Select Category</label>
								<select name="category_id" class="form-control">
									<option value="">Select Category</option>
									<?php foreach($categories as $category) { ?>
										<option value="{{ $category->id }}" <?php if($category->id == $category_id) { echo 'selected'; } ?>>{{ $category->name }}</option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Reel Type</label>
								<select class="form-control reel_type" name="reel_type">
									<option value="1" <?php if($reel_type == 1) { echo 'selected'; } ?>>Video Link</option>
									<option value="2" <?php if($reel_type == 2) { echo 'selected'; } ?>>Upload Video</option>
									<option value="3" <?php if($reel_type == 3) { echo 'selected'; } ?>>Upload Image</option>
								</select>
							</div>
						</div>
						
						<div class="col-12 vdolink">
							<div class="form-group">
								<label for="exampleInputEmail1">Enter Video Link</label>
								<input type="text" class="form-control videolink" name="videolink" value="{{ $obj->link??null }}">
							</div>
						</div>
						
						<div class="col-12 upvdo d-none">
						  <div class="form-group">
							<label for="exampleInputFile">Upload Video</label>
							<div class="input-group video">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="video">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							@if ($vdo != '' && $type != 3)
								<br>
								<video width="190" height="140" controls>
								  <source src=<?php echo $src; ?>" type="video/mp4">
								  Sorry, your browser doesn t support the video element.
								</video>
							@endif
						  </div>
						</div>
						
						 <div class="col-12 upthumb d-none">
						  <div class="form-group ">
							<label for="exampleInputFile">Upload Thumb</label>
							<div class="input-group thumb">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="thumb">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							
						  </div>
						</div>
						
						<div class="col-12 upimage d-none">
						  <div class="form-group ">
							<label for="exampleInputFile">Upload Image</label>
							<div class="input-group image">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="image">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							
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
		<?php if($obj != null) {
				if($obj->reel_type == 1) { ?>
					$('.upvdo').addClass('d-none');
					$('.vdolink').removeClass('d-none');
					$('.upthumb').addClass('d-none');
				<?php } if($obj->reel_type == 2) { ?>
					$('.upvdo').removeClass('d-none');
					$('.vdolink').addClass('d-none');
					$('.upthumb').removeClass('d-none');
					
			<?php } if($obj->reel_type == 3) { ?>
				$('.upvdo').removeClass('d-none');
					$('.vdolink').addClass('d-none');
					$('.upthumb').removeClass('d-none');
			<?php } ?>
		<?php } ?>
		$('body').on('change', '.reel_type', function() {
			$('.upvdo').addClass('d-none');
			$('.vdolink').addClass('d-none');
			$('.upthumb').addClass('d-none');
			$('.upimage').addClass('d-none');
			if($(this).val() == 1) { //Link
				$('.vdolink').removeClass('d-none');
			} else if($(this).val() == 2) { //Vdo
				$('.upvdo').removeClass('d-none');
				$('.upthumb').removeClass('d-none');
			} else if($(this).val() == 3) { //Image
				$('.upimage').removeClass('d-none');
			}
		});
		ReelManager.init();
		
	});
</script>
@endpush