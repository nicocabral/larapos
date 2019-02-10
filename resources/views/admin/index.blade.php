@extends('layouts.master')
@section('breadcrumb')

@endsection
@section('content')
<br>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li class="breadcrumb-item active"><i class="fas fa-igloo"></i> Dashboard</li>
		</ol>
	</div>
</div>

<br>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h4><i class="fas fa-chart-bar"></i> Product Sold <small>(QTY)</small> </h4>
				<canvas id="productSales" width="200" height="100"></canvas>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h4><i class="fas fa-chart-line"></i> Product Total Sales <small>(Amount)</small></h4>
				<canvas id="productTotalSales" width="200" height="100"></canvas>
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h5><i class="fas fa-clipboard-list"></i> Today Sales</h5>
				<br>
				<table class="table table-hover table-striped" width="100%" id="salesTable">
					<thead>
						<tr>
							<th>ID</th>
							<th>Total</th>
							<th>Status</th>

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
	var totalQtySold = ''.totalSalesAmount = '';
	$(document).ready(function(){
		dashboard().then(ok => {
			renderChart();
		});
		
	})

	function dashboard(){
		return new Promise(resolve =>{
			$.get("{{route('api.dashboard')}}", function(res) {
				if(res.success) {
					totalQtySold = res.totalQtySold;
					totalSalesAmount = res.totalSalesAmount;
					resolve(true);
				}
			});
		})
	}
	function renderChart(students,users){
		var labelKeys = Object.keys(totalQtySold);
		console.log(labelKeys)
	    var productSales = document.getElementById("productSales");
	    var productTotalSales = document.getElementById("productTotalSales");
	  	new Chart(productSales, {
	        type: 'bar',
	        data: {
	            labels: labelKeys,
	            datasets: [{
	                label: 'Quantity',
	                data: [totalQtySold[labelKeys[0]],totalQtySold[labelKeys[1]],totalQtySold[labelKeys[2]],totalQtySold[labelKeys[3]]],
	                backgroundColor: [
	                    'rgb(21, 140, 186)',
	                    'rgb(191, 191, 63)',
	                    'rgb(63, 191, 127)',
	                    'rgb(191, 63, 63)',
	                ],
	                borderWidth: 1
	            },
	           ]
	        },
	        options: {
	            scales: {
	                yAxes: [{
	                    ticks: {
	                        beginAtZero:true
	                    }
	                }]
	            }
	        }
	    });

	  	new Chart(productTotalSales, {
	  		 type: 'line',
		        data: {
		            labels: labelKeys,
		            datasets: [{
		                label: 'Amount',
		                fill: false,
		                data: [totalSalesAmount[labelKeys[0]],totalSalesAmount[labelKeys[1]],totalSalesAmount[labelKeys[2]],totalSalesAmount[labelKeys[3]]],
		                backgroundColor: [
		                    'rgb(21, 140, 186)',
		               
		                ],
		                borderWidth: 1,
		                options: {
					        elements: {
					            line: {
					                tension: 0, // disables bezier curves
					            }
					        }
					    }
		            },
		           ]
		        },
		        options: {
		            scales: {
		                yAxes: [{
		                    ticks: {
		                        beginAtZero:true
		                    }
		                }]
		            }
		        }
	  	});
	}

	var table = $("#salesTable").DataTable({
		processing: true,
		serverSide: true,
		destroy: true,
		ajax: {
			url : "{{route('api.pos-saleslist')}}",
			method: 'get',
			cache:false,
		},
		columns: [
			{data: "id", name: "id"},
			{data: "total", name: "total",
				"render": function(data) {
					return numeral(data).format('0,0.00');
				}
			},
			{data: "status",name:"status",

				"render":function(data) {
					var c = data == "Paid" ? "success" : "info";
					return '<span class="badge badge-pill badge-'+c+'">'+data+'</span>';
				}
			}
		]
	})
</script>
	
@endsection