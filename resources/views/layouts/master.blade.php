<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<title>Quick Book Point of Sale</title>
	<meta name="token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{asset('assets/img/logohead.png')}}" />
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/fontawesome/css/all.css')}}">
	<link rel="stylesheet" href="{{asset('assets/datatable/css/dataTables.bootstrap4.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/datepicker/css/bootstrap-datepicker.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
	
	@yield('css')
	<body>
		@include('includes.navs')
		@include('admin.users.myaccount')
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
	<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
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

		$(document).on('click','.myaccount', function() {
			$('.modal-title').html(`{{Auth::user()->first_name}}, {{Auth::user()->last_name}} <br> <span class="badge badge-pill badge-success">{{Auth::user()->status}}</span>`)
			$("#myaccountModal").modal("show");
		});

		$(document).on('click','.btnUpdate', function() {
			$("#myaccountForm").submit();
		})

		$(document).on("submit",'#myaccountForm', function(e) {
			e.preventDefault();
			var that = $(this);
			if(that.parsley().validate()) {
				$(".btnUpdate").buttonLoader('loading');
				var payload = {};
				var formData = that.serializeArray();
					formData.map(val => {
						if(val.name == "status") {
							payload[val.name] = $("#myaccountForm .myaccountstatus").prop('checked') ? "Active" : "Inactive";
						} else {
							payload[val.name] = val.value;
						}
					});
				$.ajax({
					url : "{{route('api.users-updatemyaccount')}}",
					method : "put",
					cache: false,
					data: payload,
					success: function(res) {
						$(".btnUpdate").buttonLoader('reset');
						if(res.success) {
							$("#myaccountModal").modal("hide");
							return swal({
								text: res.message,
								icon: "success"
							})
						}
						return swal({
							text: res.message,
							icon: "warning"
						})
					}
				})

			}
		})
		$(document).on("click",'.btnResetMyaccountPassword', function() {
			swal({
				text: "Are you sure you want to change your password?",
				icon: "warning",
				buttons: [
					"No",
					{
						text:"Yes",
						closeModal: false
					}
				],
				dangerMode:true,
			}).then(yes => {
				if(yes) {
					swal({
					  text: 'Input new password',
					  content: {
					    element: "input",
					    attributes: {
					      type: "password",
					      className: 'form-control',
					    },
					  },
					  buttons: [
					  	"Cancel",
					  	{
					  		text: "Proceed",
					  		closeModal:false
					  	}
					  ],
					  closeOnClickOutside: false,
					}).then(proceed => {
					  	if(proceed) {
					  		$.ajax({
								url : "{{route('api.users-changepassword','1')}}".replace('1',"{{Auth::user()->id}}"),
								method: "patch",
								cache:false,
								data: {_token:token, newpassword:proceed},
								success: function(res) {
									if(res.success) {
										return swal({
											text: res.message,
											icon: "success"
										}).then(ok => {
											window.location.reload();
										})
									} 
									return swal({
										text:res.message,
										icon:"warning"
									})
								}
							})
					  	}

					})
					
				}
			});
		})
	</script>
		@yield('script')
		@yield('js')
	</body>
</html>