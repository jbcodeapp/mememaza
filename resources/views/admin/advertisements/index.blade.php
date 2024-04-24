@extends('admin.layouts.auth')

@section('content')
<div class="content-header">
  <div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-12">
		Advertisement <a href="{{ url('advertisements_form', ['id' => -1]) }}" class="btn btn-sm btn-primary">Add</a>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" data-ajax="{{ url('advertisements_ajax') }}" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Advertisement</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
<script type="text/javascript">
    $(document).ready(function() {
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
                'type': 'POST',
                'url': $('#example1').data('data-ajax'), 
                'data': {
                    formName: 'afscpMcn',
                    action: 'search',
                },
				error: function(xhr, error, thrown) {
					alert('DataTables error: ' + error); 
				}
            },
            columns: [
                { data: 'no' },
                { data: 'advertisement' },
                { data: 'name' },
                { data: 'action' },
            ],
        });
    });
</script>

@endpush
