<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>{{ config('app.name', 'Matrix') }}</title>

        <link href="{{asset('css/developer.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('backend/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('backend/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('backend/css/sb-admin.css')}}" rel="stylesheet">
        <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

        <script src="{{asset('backend/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('js/jquery.validate.js') }}"></script>
        <script src="{{asset('backend/vendor/popper/popper.min.js')}}"></script>
        <script src="{{asset('backend/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
        <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('backend/vendor/datatables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.js')}}"></script>
        <script src="{{asset('backend/js/sb-admin.min.js')}}"></script>
        <script src="{{asset('backend/js/sb-admin-datatables.min.js')}}"></script>
        <script src="{{asset('backend/js/sb-admin-charts.min.js')}}"></script>
        <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('backend/script/developer.js') }}"></script>
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand project_title" href="{{url('home')}}">{{ config('app.name', 'Matrix') }}</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                @include('layouts.sidemenu')
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                        <a class="nav-link text-center" id="sidenavToggler">
                            <i class="fa fa-fw fa-angle-left"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-fw fa-sign-out"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
                @yield('content')
            </div>
            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © {{ config('app.name', 'Matrix') }} <?php echo date('Y'); ?></small>
                    </div>
                </div>
            </footer>
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Are You Sure to End Current Session ..??</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{url('logout')}}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="popModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="popModalT">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="popModalP">Are You Sure to End Current Session ..??</div>
                    <div class="modal-footer" id="popModalF">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="{{url('logout')}}">Logdasdout</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
    $(document).ready(function () {
        $("body").delegate(".canallchk", "click", function () {
            $(".innerallchk, .mainchk").prop("checked", "");
        });
    });
    function doaction(url, type) {
        var len = $(".innerallchk:checked").length;
        if (len == 0) {
            $("#popModalT").html("Warning Message");
            $("#popModalP").html("Please select at least one record to continue.");
            $("#popModalF").html('<button type="button" data-dismiss="modal" class="btn btn-primary">Ok</button>');
            $("#popModal").modal("show");
        } else {
            var val = $('.innerallchk:checked').map(function () {
                return this.value;
            }).get();
            if (type == 'Delete') {
                $("#popModalP").html("Are you sure you want to delete record(s)?");
                val = "'" + val + "'"
                url = "'" + url + "'"
                type = "'" + type + "'"
                $("#popModalF").html('<button type="button" onclick="postdataforaction(' + url + ',' + type + ',' + val + ');" class="btn btn-danger">Ok</button><button type="button" data-dismiss="modal" class="btn btn-default canallchk">Cancel</button>');
                $("#popModal").modal("show");
            } else {
                postdataforaction(url, type, val);
            }
        }
    }
    function postdataforaction(url, type, val) {
        load();
        var u = url + "/" + val;
        $.ajax({
            url: "{{ url('') }}/" + u,
            type: "Delete",
            data: {
                type: type,
                _token: "{{csrf_token()}}"
            },
            success: function (data) {
                if (data.status == 200) {
                    if(type=='Delete'){
                         $("#popModalT").html("Warning Message");
                            $("#popModalP").html("Please select at least one record to continue.");
                            $("#popModalF").html('<button type="button" data-dismiss="modal" class="btn btn-primary">Ok</button>');
                    }else{
                            $("#popModalT").html("Success Message");
                            $("#popModalP").html("Record(s) Updated Successfully.");
                            $("#popModalF").html('<button type="button" data-dismiss="modal" class="btn btn-success">Ok</button>');
                    }
                    $("#popModal").modal("toggle");
                    refresh();
                    $(".innerallchk, .mainchk").prop("checked", "");
                } else {
                    alert(data.msg);
                }
            },
            error: function (request, msg, error) {
                unload();
                alert("Something went wrong. Please try again.");
            }
        });
    }
    function refresh() {
        $("#exampletable").DataTable().ajax.reload(null, false);
    }
    </script>
</html>
