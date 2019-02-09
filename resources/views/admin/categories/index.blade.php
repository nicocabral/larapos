@extends('layouts.master')


@section('content')
@include('admin.categories.add')
<br>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="fas fa-igloo"></i> Dashboard</a></li>
		  <li class="breadcrumb-item active"><i class="fas fa-layer-group"></i> Category</li>
		</ol>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-8">
		
	</div>
	<div class="col-md-4">
		<div class="float-right">
			<button class="btn btn-secondary btnRefresh">Refresh table</button>
			<button class="btn btn-success btnAdd">Add Category</button>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-striped table-hover" id="categoryTable" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>NAME</th>
							<th>ACTION</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	var table = $("#categoryTable").DataTable({
		processing: true,
		serverSide: true,
		destroy: true,
		ajax: {
			url : "{{route('api.cat')}}",
			method: 'get',
			cache: false
		},
		columns: [
			{data: "id", name: "id"},
			{data: "name", name: "name"},
			{data: "id", name: "id", orderable:false, searchable:false,

				"render": function(data) {
					return '<button class="btn btn-success btnEdit mr-1" data-id="'+data+'">Edit</button> '+
						   '<button class="btn btn-danger btnDelete ml-1" data-id="'+data+'">Delete</button>';
				}
			}
		]
	});
	$(document).on('click','.btnRefresh', function(){
		table.ajax.reload();
	});

	$(document).on('click','.btnAdd', function() {
		$("#categoryForm").parsley().reset();
		$("#categoryForm").trigger("reset");
		$("#categoryForm").removeAttr('method');
		$(".modal-title").text("Add Category");
		$("#modal").modal('show');
	});

	$(document).on('submit','#categoryForm', function(e){
		e.preventDefault();
		var that = $(this);
		if(that.parsley().validate()) {
			$('.btnSave').buttonLoader('loading');
			var payload = {};
			var formData = that.serializeArray();
			if(formData.length > 0) {
				formData.map(val => {
					payload[val.name] = val.value;
				});
			}
			var url = that.attr('method') == 'put' ? "{{route('api.cat-update','1')}}".replace('1',that.attr('data-id')) : "{{route('api.cat-create')}}" ;
			$.ajax({
				url : url,
				method: that.attr('method') == 'put' ? 'put' : 'post',
				cache: false,
				data: payload,
				success:function(res) {
					$('.btnSave').buttonLoader('reset');
					if(res.success) {
						$(that).parsley().reset();
						$(that).trigger("reset");
						swal({
							text : res.message,
							icon : "success"
						});
						table.ajax.reload();
					}
				}
			})
		}
	});

	$(document).on('click','.btnSave', function() {
		$("#categoryForm").submit();
	})

	$(document).on('click','.btnDelete', function(){
			var id = $(this).attr('data-id');

			swal({
				text: "Are you sure you want to delete category?",
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
						url : "{{route('api.cat-delete','1')}}".replace("1", id),
						method: "delete",
						data: {"_token": token},
						cache: false,
						success:function(res){
							if(res.success) {
								swal({
									text: res.message,
									icon: "success"
								})
								table.ajax.reload();
							}
						}
					})
				}
			});
		});	

	$(document).on('click','.btnEdit', function() {
		var id = $(this).attr('data-id');
		var url = "{{route('api.cat-read','1')}}".replace("1",id);
		$.get(url,function(res){
			if(res.success) {
				var frm = $("#categoryForm");
				var keys = Object.keys(res.data);
				if(keys.length>0) {
					keys.map(val => {
						$("[name="+val+"]",frm).val(res.data[val]);
						if(val == "status" && res.data[val] == "Active") {
							$(".status").prop( "checked", true );
						}
					});

					$("#modal").modal('show');
					$("#categoryForm").attr('method','put').attr('data-id',id);
					$(".modal-title").text("Update Category");
				}
			}
		})
	})
</script>

@endsection