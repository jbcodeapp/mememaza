@extends('admin.layouts.auth')

@section('content')
<div class="content-header">
  <div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-12">
		Reel <a href="{{ url('reel_form', ['id' => -1]) }}" class="btn btn-sm btn-primary">Add</a>
	  </div><!-- /.col -->
	  
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
           
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" data-ajax="{{ url('reel_ajax') }}" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Reel</th>
					<th>Link</th>
					<th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
@endsection


@push('style')

@endpush

@push('scripts')

<script src="{{ cdn('admin/admin.js') }}"></script>

<script type="text/javascript">

$(function () {
   
	var $table = $('#example1').DataTable({
		  "paging": true,
		  "lengthChange": false,
		  "searching": true,
		  "ordering": false,
		  "info": true,
		  "autoWidth": false,
		  "responsive": true,
		  
		  'processing': true,
		  'serverSide': true,
		  "ajax": {
				'type': 'get',
				'url': $('#example1').attr('data-ajax'),
				'data': {
				   formName: 'afscpMcn',
				   action: 'search',
				   // etc..
				},
			},
			columns: [
				{ data: 'no' },
				{ data: 'reel' },
				{ data: 'link' },
				{ data: 'action' },
			],
		});
	});	
   
	$(document).ready(function() {
		//CategoryManager.init();
	});
</script>
@endpush