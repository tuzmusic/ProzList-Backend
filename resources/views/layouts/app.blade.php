<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Matrix</title>
  <!-- Bootstrap core CSS-->
  <link href="{{asset('backend/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{asset('backend/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('css/developer.css')}}" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="{{asset('backend/css/sb-admin.css')}}" rel="stylesheet">
</head>

<body class="bg-dark">
        @yield('content')
<script src="{{asset('backend/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('backend/vendor/popper/popper.min.js')}}"></script>
<script src="{{asset('backend/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Core plugin JavaScript-->
<script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
</body>

</html>
