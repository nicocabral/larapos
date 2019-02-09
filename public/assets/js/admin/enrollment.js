var studentItems = [];
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip()
	
	loadStudents().then(ret => {

		renderTable();
	});
});

function loadStudents(){
	return new Promise(r => {
		$.get('/ses/api/students/list.php',{},function(res){
			if(res.success) {
				var data = res.items;
				if(data.length > 0) {
					data.map(val => {
						studentItems[val.id] = val;
					});
					r(true);
				}
			}
		})
	})
}

function renderTable() {
	var html = '';
	if(studentItems.length > 0) {
		var gradeOrYear = '';
		studentItems.map(val => {
			if(val.metas.length > 0) {
				var metas = val.metas;
				metas.map(vel => {
					gradeOrYear = vel.Year;
				});
			} else {
				gradeOrYear = '------';
			}
			html+=`
				<tr>
					<td><a href="javascript:void(0);" class="text-info btnEnrollment" data-toggle="tooltip" data-original-title="View" data-id="${val.id}" data-username="${val.username}" data-status="${val.status}">${val.username}</a></td>
					<td>${val.last_name}, ${val.first_name}</td>
					<td>${val.status}</td>
				</tr>
			`;
		});
		$("#studentsTable>tbody").html(html);
			$("#studentsTable").DataTable({
				"processing":true,
				"destroy" : true,
			});
			$('[data-toggle="tooltip"]').tooltip()
	}
}

$(document).on('click','.btnEnrollment', function(){
	var username = $(this).attr('data-username');
	studentId = $(this).attr('data-id');
	$(".studentId").html(username);
	$this = $(this);

	loadSectionList().then(ok =>{
		var html = '';
	
		if(sectionList.length > 0) {
			sectionList.map(val => {
				html+=`<option value="${val.id}">${val.year} - ${val.name}</option>`;
			});
		}
		var status = $(this).attr('data-status');
		
		$("#yearAndSection").html(html);		
		
		if(status.toLowerCase() === "enrolled"){
			$(".btnEnroll").attr('disabled','disabled');
			$(".yearAndSection").attr('disabled','disabled');
		} else {
			$(".btnEnroll").removeAttr('disabled');
			$(".yearAndSection").removeAttr('disabled','disabled');
		}

		loadSubjects().then(ok => {
			if(ok) {
				setTimeout(function(){
					getEnrollementId(studentId);

				},500);
			}
		});
	});
	$("#enrollmentModal").modal('show');
});
var sectionList = [];
var studentId = '';
function loadSectionList(){
	return new Promise((resolve,rej) => {

		$.post('/ses/api/sections/list.php', function(res){
			sectionList = res.message;
			resolve(sectionList);
		})
	})
}

$(document).on('click','.btnEnroll', function(){
	$.post('/ses/api/sections/enrollment.php',JSON.stringify({"studentId" : studentId,"sectionId" : $("#yearAndSection").val()}), function(res){
		if(res.success) {
			swal({
				text: "Enroll successfully",
				icon: "success"
			})
			.then(ok => {
				window.location.reload();
			})
		}else {
			swal({
				text:res.message,
				icon: "warning"
			})
		}
	})
});

$(document).on('change','.yearAndSection', function(){
	var id = $(this).val();
	loadSubjects(id);
});
function getEnrollementId(id){
	$.get('/ses/portal/views/admin/enrollment/enrollment_id.php?rollId='+id, function(res) {
		if(res) {
			$(".yearAndSection").val(parseInt(res)).trigger("change");
		}
	});
}
function loadSubjects(id = null){
	return new Promise((resolve,rej) => {
		id = id === null ? sectionList[0].id : id;
		$.get("/ses/portal/views/admin/enrollment/subjects.php?sectionId="+id, function(res) {
		$("#subjectsTable>tbody").html(res);
		resolve(true);
	})
});
}