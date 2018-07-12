<!-- Data Tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/af-2.2.0/b-1.3.1/b-colvis-1.3.1/b-html5-1.3.1/b-print-1.3.1/cr-1.3.3/fc-3.2.2/fh-3.1.2/kt-2.2.1/r-2.1.1/sc-1.4.2/se-1.2.2/datatables.min.css"/>
<!-- global styles -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" >
<link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.css') }}" >
<link rel="stylesheet" href="{{ asset('css/plugins/toastr/toastr.min.css') }}" >
<link rel="stylesheet" href="{{ asset('js/plugins/gritter/jquery.gritter.css') }}" >

<link rel="stylesheet" href="{{ asset('css/plugins/tablesaw/tablesaw.css') }}" >
<link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/animate.css') }}" >
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<link rel="stylesheet" href="{{ URL::asset('mix/app.css') }}" >
<!-- summer editor -->
{{-- <link rel="stylesheet" href="{{ URL::asset('css/plugins/summernote/summernote.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/plugins/summernote/summernote-bs3.css') }}"> --}}

<!-- date pickers -->
{{-- <link rel="stylesheet" href="{{ URL::asset('css/plugins/datapicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/plugins/clockpicker/clockpicker.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/plugins/daterangepicker/daterangepicker-bs3.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/plugins/jasny/jasny-bootstrap.min.css') }}"> --}}
<!-- jquery steps -->
<link rel="stylesheet" href="{{ URL::asset('css/plugins/steps/jquery.steps.css') }}">


<!-- tips styles -->
<style>
    .logo-style{
    width: 70px; height: 70px; border-radius: 50%; background-color: #19aa8d; display:table-cell; vertical-align:middle; text-align:center;
    }
.panel-default > .panel-heading {
  background: #1AB394;
  color: white;
}
</style>
<!-- end globals -->
<!-- page level styles -->
@yield('css')
<!-- end page level styles -->
