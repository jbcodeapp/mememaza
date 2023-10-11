@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	$src = $position = '';
	if($id > 0) {
		if($obj != null) {
			$src = cdn(PUB.'uploads/banner/'.$obj->banner);
			$position = $obj->position;
			$button = 'Update';
		}
	}
?>

	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Banner Form
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
                
				<form method="post" action="{{ url('banner_handle') }}" id="banner_form" class="form form-horizontal" enctype='multipart/form-data'>
					@csrf
					<div class="row">
						<?php if($id > 0) {  ?>
							<input type="hidden" name="id" class="pkid" value="{{ $id }}" />
						<?php } ?>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Position</label>
								<select name="position" class="banner form-control">
									<option value="">Select</option>
									<option value="1" <?php if($position == 1) { echo 'selected'; } ?>>Header</option>
									<option value="2" <?php if($position == 2) { echo 'selected'; } ?>>Left</option>
									<option value="3" <?php if($position == 3) { echo 'selected'; } ?>>Right</option>
									<option value="4" <?php if($position == 4) { echo 'selected'; } ?>>Bottom</option>
								</select>

							</div>
						</div>
						
						
						<div class="col-12 ">
						  <div class="form-group">
							<label for="exampleInputFile">Upload Banner</label>
							<div class="input-group banner">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="banner">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							@if ($src != '')
								<br>
								
								<img src="{{ $src }}" height="45" />

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
	
	</div>
</section>
@endsection


@push('style')

@endpush

@push('scripts')


<script type="text/javascript">
	
	$(document).ready(function() {
		
		BannerManager.init();
		
	});
</script>
@endpush