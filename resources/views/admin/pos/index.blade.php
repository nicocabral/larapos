@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="{{asset('assets/datatable/css/select.dataTables.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/datatable/css/buttons.dataTables.min.css')}}">
@endsection
@section('content')
@include('admin.pos.products')
@include('admin.pos.sales')
<br>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item"><a href="{{route('index')}}"><i class="fas fa-igloo"></i> Dashboard</a></li>
		  <li class="breadcrumb-item active"><i class="fas fa-shopping-basket"></i> POS</li>
		</ol>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<button class="btn btn-primary float-left btnSalesList" >Sales list</button>
		<button class="btn btn-success float-right btnAdd">Add Item</button>

		<button class="btn btn-success float-right btnSaveItems mr-2" data-loading-text="<i class='fas fa-circle-notch fa-spin'></i> Saving...">Save</button>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<h5><strong>ITEMS</strong></h5>
				<table class="table table-striped table-condensed table-hover" width="100%" id="cartTable">
					<thead>
						<tr>
							<th></th>
							<th>SKU/CODE</th>
							<th>NAME</th>
							<th>QTY</th>
							<th>PRICE</th>
							<th>TOTAL</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div class="row">
					<div class="col-md-10">
						<div class="float-right">
							<p  class="text-right"><strong>TOTAL ITEMS</strong></p>
							<p  class="text-right"><strong>TOTAL AMOUNT</strong></p>
						</div>
					</div>
					<div class="col-md-2">
						<center>
							<p><strong id="total_items">0</strong></p>
							<h5 class="text-danger"><strong class="total_amount">0.00</strong></h5>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card">
			<div class="card-body">
				<h5><strong>PAYMENT</strong></h5>
				<div>
					<label>Total</label>
					<h5 class="text-danger"><strong class="total_amount">0.00</strong></h5>
				</div>
				<div>
					<label>Amount</label>
					<input type="number" class="form-control p_amount" value="0" step="any">
				</div>
				<br>
				<div>
					<label>Change</label><br>
					<label id="change"><strong></strong></label>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('js')
<script src="{{asset('assets/datatable/js/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/datatable/js/buttons.print.min.js')}}"></script>
<script>
	$(document).on('click','.btnAdd', function(){
		$('#itemModal').modal('show');
	});
	var items = [],selected = [];
	var itemTable = $("#itemTable").DataTable({
		processing: true,
		severSide: true,
		destroy: true,
		select: {
            style: 'multi'
        },
		ajax: {
			url : "{{route('api.products')}}",
			method: 'get',
			cache:false
		},
		createdRow: function( row, data, dataIndex ) {
		      $(row).attr('data-id', data.id).addClass("tableRows").attr('id','tableRows'+data.id);
		},
		deferRender: true,
		rowId: 'extn',
		columns: [
			{data: "sku", name: "sku"},
			{data: "name", name: "name"},
			{data: "qty", name: "qty"},
			{data: "price", name: "price",
				"render": function(type,data,row) {
					items[row.id] = row;
					return numeral(row.price).format('0,0.00');
				}
			}
		],

	});
	var salesTable = $("#salesTable").DataTable({
		processing: true,
		severSide: true,
		destroy: true,
		ajax: {
			url : "{{route('api.pos-saleslist')}}",
			method: "get",
			cache:false
		},
		dom: 'lBfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ],
		columns: [
			{
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '<a href="#"><i class="fas fa-plus-circle text-success showItemsIcon"></i> Items</a>'
            },
			{data:"id", name: "name"},
			{data:"total", name: "total",
				"render": function(data) {
					return numeral(data).format('0,0.00');
				}
			},
			{data:"paid_amount",name:"paid_amount",
				"render": function(data) {
					return numeral(data).format('0,0.00');
				}
			},
			{data:"status",name:"status",
				"render": function(data) {
					var c = data == "Paid" ? 'success' : 'warning';
					return '<span class="badge badge-pill badge-'+c+'">'+data+'</span>';
				}
			},
			{data:"date", name:"date"}
		]
	})
	$(document).on('click','#itemTable tbody tr', function(){
		var data = itemTable.row( this ).data();
		if($(this).hasClass('selected')) {
			swal({
			  text: 'Input QTY',
			  content: {
			    element: "input",
			    attributes: {
			      type: "number",
			      className: 'form-control qty',
			      value: "1"
			    },
			  },
			  closeOnClickOutside: false,
			}).then(qty => {
			   selected[data.id] = data;
			   selected[data.id].selected_qty = qty;  
			   renderItems();

			})
		} else {
			delete selected[data.id];
			renderItems()
		}
	});

	$(document).on('keyup','.qty', function(){
		var val = $(this).val();
		val = Math.abs(val) === 0 ? '' : val;
		$(this).val(val);
	})

	function renderItems(){
		var tr = '';
		var totalAmount = 0;
		var totalItems = 0;
		if(selected.length > 0) {
			selected.map(val => {
				var total = parseInt(val.selected_qty) * parseFloat(val.price);
				totalItems++;
				totalAmount = totalAmount + total;
				tr+=`<tr>
					<td><a href="#" class="text-danger btnRemoveItem" data-toggle="tooltip" data-placement="top" title="Remove" data-original-title="Remove" data-id="${val.id}"><i class="fas fa-trash-alt"></i></a></td>
					<td><h5>${val.sku}</h5></td>
					<td><h5>${val.name}</h5></td>
					<td><input type="number" class="form-control selected_qty" value="${val.selected_qty}" style="width: 50% !important" data-id="${val.id}"></td>
					<td><h5>${numeral(val.price).format('0,0.00')}</h5></td>
					<td><h5>${numeral(total).format('0,0.00')}</h5></td>
				</tr>`;
			});
		}
		selected.total_items = totalItems;
		selected.total_amount = totalAmount;
		$("#cartTable tbody").html(tr);
		$("#total_items").text(totalItems);
		$(".total_amount").text(numeral(totalAmount).format('0,0.00'));
		$('[data-toggle="tooltip"]').tooltip();
		var p_amount = $(".p_amount").val();

		p_amount = p_amount == 0 ? 0 : Math.abs(p_amount);
		var change = parseFloat(p_amount) - parseFloat(totalAmount);
		if(p_amount == 0) {
			change = 0;
		}
		if(selected.length === 0) {
			change = 0;
			$(".p_amount").val(change);
		}
		$("#change").html('<h4 class="text-danger"><strong>'+numeral(change).format('0,0.00')+'</strong></h4>');
	}

	$(document).on('input', '.selected_qty', function(){
		var val = Math.abs($(this).val());
		var id = $(this).attr('data-id');
		selected[id].selected_qty = val;
		renderItems();

	});
	$(document).on('click','.btnRemoveItem', function(){
		var id = $(this).attr('data-id');
		delete selected[id];
		renderItems();
		itemTable.rows('#tableRows'+id).deselect();
	});
	$(document).on('input','.p_amount', function(){
		var val = Math.abs($(this).val());

		var change = parseFloat(val)-parseFloat(selected.total_amount);
		if(val == '') {
			change = 0.00
		}
		$("#change").html('<h4 class="text-danger"><strong>'+numeral(change).format('0,0.00')+'</strong></h4>');

	});

	$(document).on('click','.btnSaveItems', function(){
		var that = $(this);
		var paid_amount = $(".p_amount").val()
		if(selected.length === 0) {
			return swal({
				text: "Save failed.Items is empty",
				icon: "warning",
				dangerMode: true
			})
		} 
		if(paid_amount == "" || paid_amount == 0) {
			return swal({
				text: "Please input paid amount",
				icon: "warning"
			})
		}
		var items = [];
		selected.map(val => {
			val.total = parseFloat(val.selected_qty) * parseFloat(val.price);
			items.push(val);
		});
		items.total_amount = selected.total_amount;
		var payload = {
			"paid_amount" : paid_amount,
			"status"      : paid_amount < selected.total_amount ? "Partial Paid" : "Paid",
			"total_amount": selected.total_amount,
			"items"       : items,
			"_token"       : token
		}
		that.buttonLoader('loading')
		$.post("{{route('api.pos-create')}}",payload,function(res) {
			that.buttonLoader('reset')
			if(res.success) {
				selected = [];
				renderItems();
				swal({
					text: res.message,
					icon: "success"
				})
				itemTable.ajax.reload();

			}
		});


	})
	$(document).on('click','.btnSalesList', function() {
		salesTable.ajax.reload();
		$("#salesModal").modal('show');
	});
	function format ( d ) {
	 var table = '';
   		table +=`<table class="table table-bordered table-condensed" width="100%">
					<thead>
						<tr>
							<th>SKU/CODE</th>
							<th>NAME</th>
							<th>DESCRIPTION</th>
							<th>QTY</th>
							<th>PRICE</th>
							<th>TOTAL</th>
						</tr>
					</thead>
					<tbody>
   				`;
	    if(d.items.length>0) {
	    	d.items.map(val => {
	    		
	    		table+=`<tr>
						<td>${val.ref_sku}</td>
						<td>${val.ref_name}</td>
						<td>${val.ref_description}</td>
						<td>${val.ref_qty}</td>
						<td>${numeral(val.ref_price).format('0,0.00')}</td>
						<td>${numeral(val.ref_total).format('0,0.00')}</td>
	    		</tr>`;
	    	})
	    }

    	table+='</tbody></table>';
    	return table;
	}
	$('#salesTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = salesTable.row( tr );
 
        if ( row.child.isShown() ) {
           
            row.child.hide();
            tr.removeClass('shown');
            $(this).find('.showItemsIcon').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            
        } else {
        	$(this).find('.showItemsIcon').removeClass('fa-plus-circle').addClass('fa-minus-circle');
        	
        
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });
</script>

@endsection