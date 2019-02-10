@extends('layouts.master')

@section('content')
@include('admin.users.add')
<br>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="fas fa-igloo"></i> Dashboard</a></li>
		  <li class="breadcrumb-item active"><i class="fas fa-users"></i> Users</li>
		</ol>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="float-right">
			<button class="btn btn-secondary btnRefresh">Refresh table</button>
			<button class="btn btn-success btnAdd">Add User</button>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-striped table-hover" width="100%" id="usersTable">
					<thead>
						<tr>
							<th>USERNAME</th>
							<th>NAME</th>
							<th>CONTACT NUMBER</th>
							<th>STATUS</th>
							<th>ACTION</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	var table = $("#usersTable").DataTable({
		processing: true,
		serverSide: true,
		destroy: true,
		ajax: {
			url : "{{route('api.users')}}",
			method: 'get',
			cache:false
		},
		columns : [
			{data: "username", name:"username"
			},
			{data: "id", name:"id",
				"render": function(type,data,row) {
					return row.first_name+', '+row.last_name;
				}
			},
			{data: "contact_number", name:"contact_number"},
			{data: "status", name:"status",
				"render": function(data) {
					var c = data == "Active" ? "success" : "danger";
					return '<span class="badge badge-pill badge-'+c+'">'+data+'</span>';
				}
			},
			{data: "id", name: "id",orderable:false,searchable:false,

				"render": function(data) {
					return '<button class="btn btn-sm btn-primary btnEdit mr-1" data-id="'+data+'">Edit</button>'+
					'<button class="btn btn-sm btn-danger btnDelete ml-1" data-id="'+data+'">Delete</button>'
				}

			}

		],
		drawCallback: function(args){
			$('[data-toggle="tooltip"]').tooltip()
		}
	});
	$(document).on('click','.btnAdd', function() {
		$("#userForm").parsley().reset();
		$("#userForm").trigger("reset");
		$("#userForm").removeAttr('method');
		$(".modal-title").text("Add User");
		$(".btnResetPassword").hide();
		$("#modal").modal("show");
	});

	$(document).on('submit','#userForm', function(e){
		e.preventDefault();
		var that = $(this);
		if($(this).parsley().validate()) {
			$(".btnSave").buttonLoader('loading');
			var formData = $(this).serializeArray();
			var payload = {};
			formData.map(val => {
				if(val.name == "status") {
					payload[val.name] = val.value == "on" ? "Active" : "Inactive";
				} else {
					payload[val.name] = val.value;
				}
				
			})
			typeof payload["status"] === "undefined" ? payload.status = "Inactive" : "";
			var url = that.attr('method') == 'put' ? "{{route('api.users-update','1')}}".replace('1',that.attr('data-id')) : "{{route('api.users-create')}}";
			$.ajax({
				url : url,
				method:  that.attr('method') == 'put' ? 'put' : 'post',
				cache: false,
				data: payload,
				success: function(res) {
					$(".btnSave").buttonLoader('reset');
					if(res.success) {
						$(that).parsley().reset();
						$(that).trigger("reset");
						table.ajax.reload();
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

	$(document).on('click','.btnSave', function() {

		$('#userForm').submit();
	})

	$(document).on('click','.btnDelete', function() {
		var id = $(this).attr('data-id');
		swal({
			text: "Are you sure you want to delete user?",
			icon: "warning",
			buttons: [
				"Cancel",
				{
					text: "Delete",
					closeModal:false
				}
			],
			dangerMode: true
		}).then(del => {
			if(del) {
				$.ajax({
					url: "{{route('api.users-delete','1')}}".replace('1',id),
					method: "delete",
					data: {_token:token},
					cache:false,
					success: function(res) {
						if(res.success) {
							swal({
								text:res.message,
								icon:'success'
							})
							table.ajax.reload();
						} else {
							swal({
								text:res.message,
								icon:"warning"
							});
						}
					}
				})
			}
		});
	})

	$(document).on('click','.btnRefresh', function() {
		table.ajax.reload();
	})
	$(document).on('click','.btnEdit', function() {
		var id = $(this).attr('data-id');
		var frm = $('#userForm');
		$.get("{{route('api.users-read','1')}}".replace('1',id),function(res) {
			if(res.success) {
				var keys = Object.keys(res.data);
				if(keys.length > 0) {
					keys.map(val => {
						if(val == "status") {
							var checked = res.data[val] == "Active" ? true : false;
							$(".status").prop("checked",checked);
						} else {
							$('input[name="'+val+'"]',frm).val(res.data[val]);
						}
						
					});
				}
			}
			$(".modal-title").text("Update User");
			$("#userForm").attr('method','put').attr('data-id',id);
			$(".btnResetPassword").show();
			$("#modal").modal('show');
		})
	})

	$(document).on('click', '.btnResetPassword', function() {
		var id = $("#userForm").attr('data-id');
		swal({
			text: "Are you sure you want to reset "+$("input[name=first_name]").val()+" password?",
			icon: "warning",
			buttons : [
				"Cancel", 
				{
					text: "Reset",
					closeModal: false
				}
			],
			dangerMode: true
		}).then(reset => {
			$.ajax({
				url : "{{route('api.users-resetpassword','1')}}".replace('1',id),
				method: 'patch',
				data: {_token:token},
				cache: false,
				success: function(res) {
					if(res.success) {
						$("#modal").modal('hide');
						return swal({
							text : res.message, 
							icon: "success"
						})
					} 
					swal({
						text: res.message,
						icon:"warning"
					});
				}
			})
		});
	});
</script>
@endsection