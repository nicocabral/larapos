<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<title>Quick Book Point of Sale</title>
	<link rel="shortcut icon" href="{{asset('assets/img/logohead.png')}}" />
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/fontawesome/css/all.css')}}">
	<link rel="stylesheet" href="{{asset('assets/datatable/css/dataTables.bootstrap4.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
	<meta name="token" content="{{ csrf_token() }}">
	@yield('css')
	<body>
		@include('includes.navs')
	
		<div class="container">
			@yield('content')
		</div>
		
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('assets/js/popper.min.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('assets/datatable/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/js/page/page.js')}}"></script>
	<script src="{{asset('assets/sweetalert/dist/sweetalert.min.js')}}"></script>
	<script src="{{asset('assets/chart/Chart.min.js')}}"></script>
	<script src="{{asset('assets/js/loadingoverlay.min.js')}}"></script>
	<script src="{{asset('assets/js/parsley.min.js')}}"></script>
	<script src="{{asset('assets/js/numeral.min.js')}}"></script>
	<script>
		var token = $("meta[name=token]").attr('content');
		$.LoadingOverlaySetup({
	    	background      : "rgba(255,255,255, 0.5)",
	    	image           : "{{asset('assets/img/loader.gif')}}",
		});
		$.LoadingOverlay("show");
		$(document).ready(function(){
			$.LoadingOverlay("hide");
		});
		const urlParams = window.location.href;
		const myParam = urlParams.split('/')[5];
		$('.nav-item').each(function(){
			if($(this).attr('data-href') === myParam) {

				$(this).addClass('active').not(this).removeClass('active');
			}
		})
		$(document).on('click','.btnLogout', function(){
		swal({
			title: "Confirmation",
			text: "Are you sure you want to logout?",
			icon: "warning",
			buttons: [
				"Cancel",
				{
					text:"Logout",
					closeModal: false
				}
			],
			dangerMode: true
		}).then(logout => {
			if(logout) {
				window.location.href="{{route('api.logout')}}";
			}
		});
	});
	</script>
		@yield('script')
		@yield('js')
	</body>
</html>