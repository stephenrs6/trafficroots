{{-- Mix --}}
<script src="{{ mix('mix/app.js') }}"></script>
<!-- Mainly scripts -->
{{-- <script src="{{ asset('js/jquery-3.1.1.js') }}"></script> --}}
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Data Tables -->
<!--{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/af-2.2.0/b-1.3.1/b-colvis-1.3.1/b-html5-1.3.1/b-print-1.3.1/cr-1.3.3/fc-3.2.2/fh-3.1.2/kt-2.2.1/r-2.1.1/sc-1.4.2/se-1.2.2/datatables.min.js"></script> --}}-->

<!--table filtering and print data -->
<script src="{{ asset('js/plugins/dataTables/jquery.dataTables.js') }}"></script>

<!-- Flot -->
<script src="{{ URL::asset('js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('js/plugins/flot/jquery.flot.spline.js') }}"></script>
<script src="{{ asset('js/plugins/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('js/plugins/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('js/plugins/flot/jquery.flot.time.js') }}"></script>

<!-- Peity -->
{{-- <script src="{{ asset('js/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('js/demo/peity-demo.js') }}"></script> --}}

<!-- Custom and plugin javascript -->
<script src="{{ asset('js/inspinia.js') }}"></script>
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
{{-- <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script> --}}

<!-- jQuery UI -->
{{-- <script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script> --}}

<!-- GITTER -->
{{-- <script src="{{ asset('js/plugins/gritter/jquery.gritter.min.js') }}"></script> --}}

<!-- Sparkline -->
{{-- <script src="{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}"></script> --}}

<!-- Sparkline demo data  -->
{{-- <script src="{{ asset('js/demo/sparkline-demo.js') }}"></script> --}}

<!-- ChartJS-->
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>

<!-- jQuerySteps -->
<script src="{{ asset('js/plugins/staps/jquery.steps.min.js') }}"></script>

<!-- Google reCaptcha -->
<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- Toastr -->
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>

<script type="text/javascript">
/*date table filtering only*/
if ($(".dataTableSearchOnly").length) {
	alert("test");
	$('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, pageLength: 10,
		responsive: true
	});
}
	
$('[data-toggle="tooltip"]').tooltip();
	   	
toastr.options = {
    "closeButton": true,
    "debug": false,
    // "progressBar": true,
    "preventDuplicates": true,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "600",
    "hideDuration": "600",
    "timeOut": "4000",
    "extendedTimeOut": "2000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "slideDown",
    "hideMethod": "slideUp"
};

function setActiveNav(selector) {
    $('.nav-click').removeClass("active");
    $(selector).addClass("active");
}
</script>
<!-- Input Mask -->
{{-- <script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script> --}}
<!-- SUMMERNOTE -->
{{-- <script src="{{ asset('js/plugins/summernote/summernote.min.js') }}"></script> --}}

<!-- Input Mask-->
{{-- <script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script> --}}

<!-- Data picker -->
{{-- <script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script> --}}

<!-- Clock picker -->
{{-- <script src="{{ asset('js/plugins/clockpicker/clockpicker.js') }}"></script> --}}

<!-- Date range use moment.js same as full calendar plugin -->
{{-- <script src="{{ asset('js/plugins/fullcalendar/moment.min.js') }}"></script> --}}

<!-- Date range picker -->
{{-- <script src="{{ asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script> --}}

{{-- <script src="{{ asset('js/main.js') }}"></script> --}}

<!-- end globals -->
<!-- page level scripts -->
@yield('js')
<!-- end page level scripts -->
