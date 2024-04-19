@extends('admin.layouts.auth')

@section('content')
<?php
	$button = 'Create';
	// $src = $position = '';
	if($id > 0) {
		if($obj != null) {
			$src = cdn(PUB.'uploads/advertisements/'.$obj->advertisement);
			// $position = $obj->position;
			$button = 'Update';
		}
	}
?>

<div class="content-header">
  <div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-12">
		Advertisement Form
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('advertisements_handle') }}" id="advertisements_form" class="form form-horizontal" enctype='multipart/form-data'>
                        @csrf
                        <div class="row">
                            @if($id > 0)
                                <input type="hidden" name="id" class="pkid" value="{{ $id }}" />
                            @endif
                            <div class="col-12 ">
                                <div class="form-group">
                                    <label for="exampleInputFile">Upload Advertisement</label>
                                    <div class="input-group advertisement">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input " name="advertisement">
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
                                    <label for="exampleInputEmail2">Name</label>
                                    <input type="text" class="form-control meta_name" name="name" value="{{ $obj->name ?? null }}" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Meta Title</label>
                                    <input type="text" class="form-control meta_title" name="meta_title" value="{{ $obj->meta_title ?? null }}" placeholder="Enter Meta Title">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Meta Keyword</label>
                                    <input type="text" class="form-control meta_keyword" name="meta_keyword" value="{{ $obj->meta_keyword ?? null }}" placeholder="Enter Meta Keyword">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail2">Meta Description</label>
                                    <input type="text" class="form-control meta_desc" name="meta_desc" value="{{ $obj->meta_desc ?? null }}" placeholder="Enter Meta Description">
                                </div>
                            </div>
							<div class="col-12 linkdiv">
								<div class="form-group">
									<label for="exampleInputEmail2">Link</label>
									<input type="text" name="link" class="form-control link" 
									value="{{ @$obj->link }}"
									/>
	
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
</section>
@endsection

@push('style')

@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        AdvertisementManager.init();
    });
</script>
@endpush
