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
				<table class="table table-hover table-striped">
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
	$(document).ready(function(){
		renderChart();
	    // render()
	    // .then(ret => {

	    //     if(ret) {
	    //         renderChart();
	    //     }
	    // });
	})
	// function render(){
	//     return new Promise(async r => {
	//         var ret = await getData();
	//         if(ret) {
	//            r(true);

	//         }
	//     })
	// }
	// function getData() {
	//     return new Promise(r => {

	//         $.get('/ses/api/dashboard/dashboard.php',{}, function(res){
	//             if(res.success) {
	//                 renderChart(res.items.students.totalStudents,res.items.users.totalUsers);
	//                 $(".noStudents").html(res.items.students.totalStudents);
	//                 $(".noUsers").html(res.items.users.totalUsers);
	//             }
	//         })
	//         r(true);
	//     })
	// }

	function renderChart(students,users){

	    var productSales = document.getElementById("productSales");
	    var productTotalSales = document.getElementById("productTotalSales");
	  	new Chart(productSales, {
	        type: 'bar',
	        data: {
	            labels: ["Today","Last 7 Days", "Last 14 Days", "Last 30 Days"],
	            datasets: [{
	                label: 'Quantity',
	                data: [12,25,13,6],
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
		            labels: ["Today","Last 7 Days", "Last 14 Days", "Last 30 Days"],
		            datasets: [{
		                label: 'Amount',
		                fill: false,
		                data: [1000,5000,4000,2500],
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
</script>
	
@endsection