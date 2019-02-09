$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	loadSections();
	loadSubjects();
});
var subjects = [];
function loadSections(){
	$.post('/ses/api/sections/list.php',function(res){
		if(res.success) {
			var data = res.message,
				html = '';
			if(data.length > 0) {
				data.map(val => {
					var status = val.status;
					html+=`<tr>
						<td>${val.name}</td>
						<td>${val.year}</td>
						<td>
							<a href="#" class="btnEdit text-info" data-toggle="tooltip" title="Edit" data-original-title="Edit" data-id="${val.id}"><i class="fas fa-edit"></i></a> &nbsp;
							<a href="#" class="btnDelete text-danger" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="${val.id}" data-name="${val.name}"><i class="fas fa-trash-alt"></i></a>
						</td>
					</tr>`;					
				});
				$("#sectionsTable>tbody").html(html);
				$('[data-toggle="tooltip"]').tooltip()
				
				$('#sectionsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 2 }
					  ]
				});
			} else {
				$("#sectionsTable>tbody").html('');
				$('#sectionsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 2 }
					  ]
				});
			}
		} else {
			$('#sectionsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
				    { "orderable": false, "targets":2 }
				  ]
			});
		}
	});
}

function loadSubjects(){
	$.post('/ses/api/sections/subjects.php',function(res){
		if(res.success) {
			var data = res.message,
				html = '';
			if(data.length > 0) {
				data.map(val => {
					var status = val.status;
					html+=`<tr>
				
						<td>${val.code}</td>
						<td>${val.subject_name}</td>
						<td>${val.name} - ${val.time_day} ${val.time_hour}:${val.time_min} ${val.suffix} </td>
						<td>
							
		                    <input type="checkbox" name="subjects" class="form-control subjects" data-id="${val.id}">
		                    
						</td>
					</tr>`;					
				});
				$("#subjectsTable>tbody").html(html);
				$('[data-toggle="tooltip"]').tooltip()
				
				$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 3 }
					  ]
				});
			} else {
				$("#subjectsTable>tbody").html('');
				$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 3 }
					  ]
				});
			}
		} else {
			$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
				    { "orderable": false, "targets":3 }
				  ]
			});
		}
	})
}
$(document).on('click','.btnAdd', function(){
	loadSubjects();
	$('#sectionForm').parsley().reset();
	$('#sectionForm').trigger('reset');
	$('#sectionForm').removeAttr('method');
	$('#modal').modal('show');
});

$(document).on('change','.subjects', function(){
	var id= $(this).attr('data-id');
	if($(this).prop('checked')) {
		subjects.push(id);
	} else {
		var i = subjects.indexOf(id);
		if(i != -1) {
			subjects.splice(i,1);
		}
	}
});
$(document).on('click','.btnSave', function(){
	$('#sectionForm').submit();
});
$(document).on('submit','#sectionForm', function(e){
	e.preventDefault();
	var $this = $(this);
	if($(this).parsley().validate()) {
		$('.btnSave').buttonLoader('loading');
		var formData = $(this).serializeArray();
		var payload = {};
		formData.map(val => {
			payload[val.name] = val.value;
		});
		payload.subjects = subjects.toString();
		var method = $this.attr('method');
		var url = method == 'patch' ? '/ses/api/sections/update.php' : '/ses/api/sections/create.php';
		$.post(url,JSON.stringify(payload), function(res){
			$('.btnSave').buttonLoader('reset');
			if(res.success) {
				swal({
					text: res.message,
					icon: "success"
				})
				.then(ok => {
					window.location.reload();
				})
			}
		});
	}
})

$(document).on('click','.btnDelete', function(){
	var id = $(this).attr('data-id');
	var name = $(this).attr('data-name');
	swal({
		title: "Confirmation",
		text: "Are you sure you want to delete section "+name+"?",
		icon: "warning",
		dangerMode:true,
		buttons: [
			"Cancel",
			{
				text: "Delete",
				closeModal: true
			}
		]
	})
	.then(del => {
		if(del) {
			$.post('/ses/api/sections/delete.php',JSON.stringify({"id":id}), function(res) {
				if(res.success) {
					swal({
						text: res.message,
						icon: "success"
					})
					.then(ok => {
						if(ok) {
							$('#sectionsTable').DataTable().destroy();
							loadSections();
						}
					})
					
				}
			});
		}
	});
});

$(document).on('click','.btnEdit', function(){

	var id = $(this).attr('data-id');
	read(id).then(res => {
		if(res.success) {
			var data = res.message;
			$(".name").val(data.name);
			$(".year").val(data.year);
			$("#id").val(data.id);
			var subjs = data.subjects != '' ? data.subjects.split(',') : [];
			subjects = subjs;
			$('.subjects').each(function() {
				var $this = $(this);
				var subId = $this.attr('data-id');
				if(subjs.length > 0) {
					subjs.map(val => {
						if(subId == val) {
							$($this.prop('checked',true));
						}
					});
				}
			})
			$('#sectionForm').attr('method','patch').attr('data-id',id);
			$('#modal').modal('show');
		}
	});
});
function read(id) {
	return new Promise((resolve,res) => {
		$.post('/ses/api/sections/read.php',JSON.stringify({"id":id}), function(res){
			resolve(res);
		});
	})
}
$(document).on('click','.btnView', function(){
	var id = $(this).attr('data-id');
	var scheds = [];
	var html = '';
	var subjectList = [];
	$.post('/ses/api/sections/subjectlist.php',JSON.stringify({"id" : id}), function(res){


	})
})