var users = '',students = '';
$(document).ready(function(){
    render()
    .then(ret => {

        if(ret) {
            renderChart();
        }
    });
})
function render(){
    return new Promise(async r => {
        var ret = await getData();
        if(ret) {
           r(true);

        }
    })
}
function getData() {
    return new Promise(r => {

        $.get('/ses/api/dashboard/dashboard.php',{}, function(res){
            if(res.success) {
                renderChart(res.items.students.totalStudents,res.items.users.totalUsers);
                $(".noStudents").html(res.items.students.totalStudents);
                $(".noUsers").html(res.items.users.totalUsers);
            }
        })
        r(true);
    })
}

function renderChart(students,users){

    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Students", "Users"],
            datasets: [{
                label: '',
                data: [parseInt(students), parseInt(users)],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
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
}