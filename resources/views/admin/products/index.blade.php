@extends('layouts.master')

@section('content')
@include('admin.products.add')
<br>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="fas fa-igloo"></i> Dashboard</a></li>
		  <li class="breadcrumb-item active"><i class="fas fa-boxes"></i> Products</li>
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
			<button class="btn btn-success btnAdd">Add Product</button>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-striped table-hover" id="productsTable" width="100%">
					<thead>
						<tr>
							<th>CODE/SKU</th>
							<th>NAME</th>
							<th>QTY</th>
							<th>PRICE</th>
							<th>STATUS</th>
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
		var table = $("#productsTable").DataTable({
			processing: true,
			serverSide: true,
			destroy   : true,
			ajax      : {
				url : '{{route("api.products")}}',
				method: 'get',
			},
			columns  : [
				{data: 'sku', name: 'sku',
					"render": function(type,data,row) {
						return '<button class="btn btn-link btnView" data-toggle="tooltip" data-placement="top" title="View" data-original-title="View" data-id="'+row.id+'">'+row.sku+'</button>';
					}
				},
				{data: 'name', name: 'name'},
				{data: 'qty', name: 'qty',
					"render": function(data) {
						return numeral(data).format('0,0');
					}
				},
				{data: 'price', name: 'price',
					"render": function(data) {
						return  numeral(data).format('0,0.00');
					}
				},
				{data: 'status', name: 'status',
					"render": function(data) {
						var statClass = data == "Active" ? "success" : "danger";

						return '<span class="badge badge-pill badge-'+statClass+'">'+data+'</span>';
					}
				},
				{data: 'id', name: 'id',orderable: false,searchable:false,
					"render": function(data) {
						return '<button type="button" class="btn btn-danger btnDelete" data-id="'+data+'">Delete</button>';
					}
				}	
			],
			drawCallback: function(args){
				$('[data-toggle="tooltip"]').tooltip()
			}
		});

		$(document).on('click','.btnRefresh', function(){
			table.ajax.reload();
		});
		$(document).on('click','.btnAdd', function(){
			$("#productForm").parsley().reset();
			$("#productForm").trigger("reset");
			$("#productForm").removeAttr('method');
			$(".modal-title").text("Add Product");
			$("#modal").modal("show");
		});

		$(document).on('submit','#productForm', function(e) {
			e.preventDefault();
			var that = $(this);
			if($(this).parsley().validate()) {
				$(".btnSave").buttonLoader('loading');
				var formData = $(this).serializeArray();
				var payload = {};
				
				formData.map(val =>{ 
					if(val.name == "status") {
						payload[val.name] = $('.status').prop('checked') ? "Active" : "Inactive";
					} else {
						payload[val.name] = val.value;
					}
					
				});

				var url = that.attr('method') == 'put' ? "{{route('api.product-update','1')}}".replace('1',that.attr('data-id')) : '{{route("api.product-create")}}';
				$.ajax({
					url : url,
					method : that.attr('method') == 'put' ? 'put' : 'post',
					cache:false,
					data: payload,
					success:function(res) {
						$(".btnSave").buttonLoader('reset');
						if(res.success) {
							$(that).parsley().reset();
							$(that).trigger("reset");
							swal({
								text: res.message,
								icon: 'success'
							})
							table.ajax.reload();
						}
					}
				})
			}
		});	
		$(document).on('click','.btnSave', function(){
			$("#productForm").submit();
		})

		$(document).on('click','.btnDelete', function(){
			var id = $(this).attr('data-id');

			swal({
				text: "Are you sure you want to delete product?",
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
						url : "{{route('api.product-delete','1')}}".replace("1", id),
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
		$(document).on('click','.btnView', function(){
			var id = $(this).attr('data-id');
			var url = "{{route('api.product-read','1')}}".replace("1",id);
			$.get(url,function(res){
				if(res.success) {
					var frm = $("#productForm");
					var keys = Object.keys(res.data);
					if(keys.length>0) {
						keys.map(val => {
							
							if(val == "status" && res.data[val] == "Active") {
								$(".status").prop( "checked", true );
							} else {
								$(".status").prop( "checked", false );
								$("[name="+val+"]",frm).val(res.data[val]);
							}
						});

						$("#modal").modal('show');
						$("#productForm").attr('method','put').attr('data-id',id);
						$(".modal-title").text("Update Product");
					}
				}
			})
		});

		$(document).on('keyup', '.number',function(){
			var val = $(this).val();
			$(this).val(Math.abs(val));
		})
	</script>
@endsection