@extends('layouts.app')
@section('title', 'Categories')

@section('css')
{{-- adminlte css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')


<section class="category container-fluid px-3 px-md-5 my-5" id="category">
    @if (session('message'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>{{ session('message') }}</strong>
    </div>
    @endif

    <div class="row mb-5">
        <h5 class="my-4 text-uppercase purple mb-4">Add new Categories</h5>

        <form name="add_category" action="{{ route('admin.category.store') }}" class="col-12 col-sm-8" method="POST">
            <div style="gap: 2rem;" class="d-flex flex-row align-items-center">
                @method('post')
                @csrf
                <div style="width: 55%;" class="form-floating mt-2">
                    <input type="text" name="category" placeholder="Enter Category Name" class="form-control @error('password') is-invalid @enderror" id="category">
                    <label for="category">Add New Category</label>
                </div>
                <div>
                    <button style="background-color: #f2f2f2; color: #4c2281; font-weight: 700;" class="events purple form-control d-block mx-auto rounded-pill">Add Category</button>
                </div>
            </div>


            @error('category')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </form>
    </div>

    <div class="row mb-3">
        <h5 class="mb-5 text-uppercase purple mb-3">View existing categories</h5>

        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Our Categories <sup><span class="badge bg-success">{{count($categories)}}</span></sup></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="ourCategories" class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $key => $category)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$category->name}}</td>
                                <td>
                                    {{-- <button type="button" class="btn btn-primary edit-button px-2" data-toggle="modal" data-target="#edit-modal" data-file-id="{{ $category->id }}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button> --}}
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#cat{{ ++$key }}">
                                        <i class="fa fa-trash text-danger px-2" aria-hidden="true"></i>
                                    </button>
                                    <div class="modal fade" id="cat{{ $key }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Delete Category</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete category {{ $category->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.category.delete', $category->id) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Yes</button>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</section>
{{-- @include('pages.category.edit') --}}
@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        $("#ourCategories").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "print"]
        }).buttons().container().appendTo('#ourCategories_wrapper .col-md-6:eq(0)');
    });
</script>

{{-- <script>
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var fileId = $(this).data('file-id');
            $('#file-id').val(fileId);
            $('#edit-form').attr('action', "{{ route('category.update', ['category' => ':id']) }}".replace(':id', fileId));

});
});
</script> --}}

@endsection