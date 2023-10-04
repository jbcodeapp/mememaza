@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	$src = $story_type = $strtime = $cond = '';
	$link = 'd-none';
	if($id > 0) {
		if($obj != null) {
			$strtime = $obj->time;
			if($obj->story_type == 1) {
				$story_type = 1;
				$src = cdn(PUB.'uploads/story/'.$obj->id.'/'.$obj->story);
			} else if($obj->story_type == 2) {
				$story_type = 2;
				$src = cdn(PUB.'uploads/story/'.$obj->id.'/'.$obj->story);
			}
			
			$button = 'Update';
		}
	}
	
?>

	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Story Form
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
                
				<form method="post" action="{{ url('story_handle') }}" id="story_form" class="form form-horizontal" enctype='multipart/form-data'>
					@csrf
					<div class="row">
						<?php if($id > 0) {  ?>
							<input type="hidden" name="id" class="pkid" value="{{ $id }}" />
						<?php } ?>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Time</label>
								<input type="text" name="time" class="form-control time" id="datepicker"
								placeholder="Choose Date and Time"
								value="{{ $strtime }}"
								format="DD-MM-YYYY hh:mm"
								/>

							</div>
						</div>
						
						<div class="col-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Story Type</label>
								<select name="story_type" class="banner form-control story_type">
									<option value="">Select</option>
									<option value="1" <?php if($story_type == 1) { echo 'selected'; } ?>>Image</option>
									<option value="2" <?php if($story_type == 2) { echo 'selected'; } ?>>Video</option>
								</select>

							</div>
						</div>
						
						
						<div class="col-12 conddiv {{ $cond }}">
						  <div class="form-group">
							<label for="exampleInputFile">Upload Story</label>
							<div class="input-group story">
							  <div class="custom-file">
								<input type="file" class="custom-file-input " name="story">
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							</div>
							
						  </div>
						</div>
						
						<div class="col-12 linkdiv">
							<div class="form-group">
								<label for="exampleInputEmail1">Link</label>
								<input type="text" name="link" class="form-control link" 
								value="{{ @$obj->link }}"
								/>

							</div>
						</div>
						
						@if ($src != '')
							<div class="col-12 ">
								<br>
								<?php if($story_type == 1) { ?>
									<img src="{{ $src }}" height="45" />
								<?php } else if($story_type == 2) { ?>
									<video width="190" height="140" controls>
									  <source src="<?php echo $src; ?>" type="video/mp4">
									  Sorry, your browser doesn t support the video element.
									</video>
								<?php } else if($story_type == 3) { ?>
									<a href="{{ $src }}" target=""></a>
								<?php } ?>
							</div>
							@endif
						
						
						  <br><br>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />

@endpush

@push('scripts')
<script src="{{ cdn('admin/admin.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>


<script type="text/javascript">
	jQuery(function($) {
    //$("#datepicker").datetimepicker();
	  //$("#datepicker").datetimepicker({format: 'Y-m-d H:i:s'});
$('#datepicker').datetimepicker({
    dateFormat: "yy-mm-dd", 
    timeFormat: "HH:mm:ss"
});
});
	$(document).ready(function() {
		$('body').on('change', '.story_type', function() {
			
			let val = $(this).val();
			//let link = $('.linkdiv').addClass('d-none');
			//let cond = $('.conddiv').addClass('d-none');
			
			if(val == 1) {
				//$('.linkdiv').addClass('d-none');
				//$('.conddiv').removeClass('d-none');
			} else if(val == 2) {
				//$('.linkdiv').addClass('d-none');
				//$('.conddiv').removeClass('d-none');
			}
		});
		StoryManager.init();
		
	});
</script>
@endpush