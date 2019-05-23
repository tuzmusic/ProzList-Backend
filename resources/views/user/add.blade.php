@extends('layouts.inner_app')

@section('content')
<div class="card mb-3">
    <div id="msg"></div>    
    <div class="row">
        <div class="col-md-12">
            <form id="frm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @isset($data)
                <input type="hidden" name="_method" value="PUT">
                @endisset
                <div class="col-md-12">  
                   <div class="form-group">
                       <label>Name <span class="error">*</span></label>
                       <input type="text" class="form-control" value="@isset($data){{$data->name}}@endisset" name="name" placeholder="Enter Name">
                   </div>
               </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Email <span class="error">*</span></label>
                        <input type="text" class="form-control" value="@isset($data){{$data->email}}@endisset" name="email" placeholder="Enter email">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Password @if(!isset($data))<span class="error">*</span>@endif</label>
                        <input type="password" class="form-control @if(!isset($data)) required @endif" name="password" id="password" placeholder="Enter password">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Confirm Password @if(!isset($data))<span class="error">*</span>@endif</label>
                        <input type="password" class="form-control @if(!isset($data)) required @endif" name="confirmpassword" placeholder="Enter password again">
                    </div>
                </div>
				<div class="col-md-12">
                    <div class="form-group">
                        <label>Role @if(!isset($data))<span class="error">*</span>@else : @endif</label>
                        @if(isset($data))
                            <h4 style="margin: 0px;">{{$data->role}}</h4>
                        @else
                            <select name="role" class="form-control">
                                <option value="">Select Type</option>
                                <option @isset($data) @if($data->role == 'Admin') selected=selected="selected" @endif @endisset value="Admin">Admin</option>
                                <option @isset($data) @if($data->role == 'Member') selected=selected="selected" @endif @endisset value="Member">Member</option>
                            </select>
                        @endif
                    </div>  
                </div>
                <div class="col-md-12">
                    <input type="submit" id="btn" class="btn btn-primary" value="Submit">
                    <a class="btn btn-warning" href="{{url('admin/user')}}">Cancel</a>
                </div><br>
            </form>
        </div>
    </div>           
</div>

<script>
    $(function () {
        $("#btn").on("click",function (e) {
            e.preventDefault();
            var val = $("#frm").validate({
                rules: {
                    role : {
                        required: true
                    },
                    name : {
                        required: true
                    },
                    email : {
                        required: true,
                        email: true
                    },
                    confirmpassword : {
                        equalTo: "#password"
                    }
                }
            });
            if(val.form() != false) {
                var url = "@isset($data){{url('admin/user/'.$data->id)}}@endisset @empty($data){{url('admin/user')}}@endempty".trim();
                var action = "@isset($data){{'PUT'}}@endisset @empty($data){{'POST'}}@endempty".trim();
                var fdata = new FormData($("#frm")[0]);
                "@isset($data)";
                fdata.append("role","{{$data->role}}");
                "@endisset";
                load();
                $.ajax({
                    url : url,
                    type: 'POST',
                    data : fdata,
                    headers: {
                        'X_CSRF_TOKEN':'{{ csrf_token() }}',
                    },
                    processData: false,
                    contentType: false,
                    success:function(data, textStatus, jqXHR){
                        var res = data;
                        console.log(res);
                        $("#msg").fadeIn("slow");
                        $("#msg").removeClass();
                        if(data.status == 200) {
                            $("#msg").addClass("alert alert-success");
                            if(action == 'POST') {
                                $("#frm")[0].reset();
                            }
                        }else {
                            $("#msg").addClass("alert alert-danger");
                        }
                        $("#msg").html(res.msg);
                        unload();
                        hidemsg(5000,2000);
                        scrolltop();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert("Something went wrong. Please try again.")
                        unload();
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>

@endsection