@extends('layouts.inner_app')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="box-header">
            <div class="btn-group pull-right status">
                <button class="btn btn-warning" type="button">Action</button>
                <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul role="menu" class="dropdown-menu">
                    <li><a onclick="doaction('admin/user','Active');" >Active</a></li>
                    <li><a onclick="doaction('admin/user','Inactive');" >Inactive</a></li>
                    <li><a onclick="doaction('admin/user','Delete');" >Delete</a></li>
                </ul>
            </div>
            <a href="{{url('admin/user/create')}}" class="btn btn-primary pull-left">Add User</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="exampletable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th><input class="mainchk" type="checkbox"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("body").delegate(".delsing","click",function () {
            var id = $(this).attr("id");
            $(".innerallchk, .mainchk").prop("checked","");
            $(this).parents("tr").find(".innerallchk").prop("checked",true);
            doaction("admin/user","Delete");
        });
    });
</script>
<script>
$(function() {
    $('#exampletable').DataTable({
        processing: true,
        serverSide: true,
        bSort: false,
        ajax: {
                'url': "{{url('admin/user/getallg')}}",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
        },
        columns: [
            { data: 'id'},
            { data: 'name'},
            { data: 'email'},
            { data: 'role'},
            { data: 'status'},
            { data: 'action'}
        ]
    });
});
</script>
@endsection