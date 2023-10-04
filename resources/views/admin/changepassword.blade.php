@extends('admin.layouts.auth')

@section('content')



	<div class="content-header">
	  <div class="container-fluid">
		<div class="row mb-2">
		  <div class="col-sm-12">
			Change Password
		  </div><!-- /.col -->
		  
		</div><!-- /.row -->
	  </div><!-- /.container-fluid -->
	</div>
	

	
<section class="content">
	<div class="row">
		
		<div class="col-md-12">
		<div class="row">
          <div class="col-12">
            <div class="card card-primary card-tabsd">
              <div class="card-header p-0 pt-1">
                
              </div>
              <div class="card-body">
               
					<form method="post" action="{{ url('updatechangepassword') }}" id="change_password_form" class="form form-horizontal">
						@csrf
						<div class="row">
							
							  <div class="col-12">
									<div class="form-group">
									<label for="exampleInputEmail1">Password</label>
									<input type="password" class="form-control password" name="password" placeholder="Enter Password">
								  </div>
							  </div>
							  
							  <div class="col-12">
									<div class="form-group">
									<label for="exampleInputEmail1">Confirm Password</label>
									<input type="password" class="form-control confirmed" name="confirmed" placeholder="Enter Confirm Password">
								  </div>
							  </div>
							  
							  
							  
							  <div class="col-12">
								  <div class="form-group">
									<div class="card-">
									  <button type="submit" class="btn btn-primary" data-text="{{ 'Update' }}">Update</button>
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

<script type="text/javascript">
	
	$(document).ready(function() {
		ChangePasswordManager.init();
		
	});
</script>
@endpush