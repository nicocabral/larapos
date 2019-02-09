$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	loadSubjects();
});

function loadSubjects(){
	$.get('/ses/api/subjects/list.php',{},function(res) {
		if(res.success) {
			var data = res.message,
				html = '';
			if(data.length > 0) {
				data.map(val => {
					var status = val.status;
					html+=`<tr>
						<td><a href="#" class="text-info btnSchedule" data-toggle="tooltip" title="Add Schedule's" data-original-title="Add Schedule's" data-id="${val.id}">${val.code}</a></td>
						<td>${val.name}</td>
						<td>${val.description}</td>
						<td>${val.unit}</td>
						<td><p class="${status.toLowerCase() === 'active' ? 'text-success' : 'text-danger'}">${status}</p></td>
						<td>
							<a href="#" class="btnEdit text-info" data-toggle="tooltip" title="Edit" data-original-title="Edit" data-id="${val.id}"><i class="fas fa-edit"></i></a> &nbsp;
							<a href="#" class="btnDelete text-danger" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="${val.id}" data-code="${val.code}"><i class="fas fa-trash-alt"></i></a>
						</td>
					</tr>`;					
				});
				$("#subjectsTable>tbody").html(html);
				$('[data-toggle="tooltip"]').tooltip()
				
				$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
				});
			} else {
				$("#subjectsTable>tbody").html('');
				$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
				});
			}
		} else {
			$('#subjectsTable').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
				    { "orderable": false, "targets": 5 }
				  ]
			});
		}
	});
}

$(document).on('click','.btnAdd', function(){
	$('.code').removeAttr('readonly','readonly');
	$('#subjectForm').parsley().reset();
	$('#subjectForm').trigger('reset');
	$('#subjectForm').removeAttr('method');
	$('textarea').val('')
	$('#modal').modal('show');
});

$(document).on('submit','#subjectForm', function(e){
	e.preventDefault();
	if($(this).parsley().validate()) {
		$('.btnSave').buttonLoader('loading');
		var formData = $(this).serializeArray(),
			payload = {};
			formData.map((val,i) => {
				if(val.name === 'status') {
					payload[val.name] = val.value === 'on' ? "Active" : "Inactive";
				} else {
					payload[val.name] = val.value;	
				}
			});
			payload.status = typeof payload.status ==="undefined" ? "Inactive" : "Active";
			var method =  $(this).attr('method');
			var url = method === "patch" ? '/ses/api/subjects/update.php' : '/ses/api/subjects/create.php';
				payload.id = $(this).attr('id') ? $(this).attr('data-id') : '';
		
			$.post(url,JSON.stringify(payload),function(res){
				$('.btnSave').buttonLoader('reset');
				if(res.success){
					$('#subjectForm').parsley().reset();
					$('#subjectForm').trigger('reset');
					swal({
						title: "Success",
						text: "Save successfully",
						icon: "success"
					}).
					then(ok =>{
						
						$('#subjectsTable').DataTable().destroy();
						loadSubjects();	
						
					})
				} else {
					swal({
						title: "Warning",
						text: res.message,
						icon: "warning"
					})
				}

			})

	}
});
$(document).on('click','.btnSave', function(){
	$('#subjectForm').submit();
});

$(document).on('click','.btnDelete', function(){
	var id = $(this).attr('data-id'),
		code = $(this).attr('data-code');
	swal({
		title: "Warning",
		text: "Are you sure you want to delete "+code+'?',
		icon: "warning",
		dangerMode: true,
		buttons: [
			"Cancel",
			{
				"text" : "Delete",
				closeModal: false
			}
		]
	})
	.then(del => {
		if(del) {
			$.post('/ses/api/subjects/delete.php',JSON.stringify({"id": id}), function(res){
				if(res.success) {
					swal({
						title: "Success",
						text: "Deleted successfully",
						icon: "success"
					});
					$('#subjectsTable').DataTable().destroy();
					loadSubjects();
				}
			})
		}
	})
})
$(document).on('click','.btnRefresh', function(){
	$('#subjectsTable').DataTable().destroy();
	loadSubjects();
})
$(document).on('click','.btnEdit', function() {
	var id = $(this).attr('data-id');
	$.post('/ses/api/subjects/read.php',JSON.stringify({"id" : id}), function(res) {
		if(res.success) {
			$('#modal').modal('show');
			var data = res.message,
				frm = $('#subjectForm'),
				keys = Object.keys(data);;
				if(keys.length > 0) {
					keys.map(val => {
						if(val === 'status') {
							if(data[val].toLowerCase() === 'active') {
								$('.status').prop('checked',true);
							} else {
								$('.status').prop('checked',false);
							}
						}
						$('input[name='+val+']',frm).val(data[val]);
						$('textarea[name='+val+']',frm).val(data[val]);
						$('.code').attr('readonly','readonly');

					});
					frm.parsley().reset();
					frm
					.attr('data-id',id)
					.attr('method','patch');
				}
		}
	});

});

$(document).on('click','.btnSchedule', async function(){
	var subject = $(this).text(),
		id = $(this).attr('data-id');
	$('#schedulesList').DataTable().destroy();
	loadSubjectSchedules({"id" : id});
	$('#modal-title').text(subject);
	$("#subjectId").val(id);
	$('#scheduleModal').modal('show');
});
function loadSubjectSchedules(args) {
	return new Promise((resolve,rej) => {
		$.get('/ses/api/schedules/list.php?subjectId='+args.id,function(res){
			var data = res.success ? res.message : [];
			var html = '';
			if(data.length > 0) {
				data.map(val => {
					html+= 	`<tr>
						<td>${val.name}</td>
						<td>${val.unit}</td>
						<td>${val.time_day} ${val.time_hour}:${val.time_min} ${val.suffix}</td>
						<td>${val.status}</td>
						<td>
							<a href="#" class="btnEditSchedule text-info" data-toggle="tooltip" title="Edit" data-original-title="Edit" data-id="${val.id}"><i class="fas fa-edit"></i></a> &nbsp;
							<a href="#" class="btnDeleteSchedule text-danger" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="${val.id}"><i class="fas fa-trash-alt"></i></a>
						</td>
					</tr>`;
				});

				$("#schedulesList>tbody").html(html);
				$('[data-toggle="tooltip"]').tooltip()		
				$('#schedulesList').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 3 }
					  ]
				});

			} else {
				$("#schedulesList>tbody").html('');
				$('#schedulesList').DataTable({
				"destroy": true,
				"processing": true,
				"columnDefs": [
					    { "orderable": false, "targets": 3 }
					  ]
				});
			}	
		})
	})
}

$(document).on('submit','#schedForm', function(e) {
	e.preventDefault();
	var $this = $(this);
	if($(this).parsley().validate()) {
		$('.btnSaveSched').buttonLoader('loading');
		var formData = $(this).serializeArray();
		var payload = {};
			formData.map(val => {

				payload[val.name] = val.value;
			});
		var url = $this.attr('method') === 'patch' ? '/ses/api/schedules/update.php' : '/ses/api/schedules/create.php';
			$this.attr('method') === 'patch' ? payload.id = $this.attr('data-id') : '';
		$.post(url,JSON.stringify(payload), function(res) {
			$('.btnSaveSched').buttonLoader('reset');
			if(res.success) {
				swal({
					text: "Save successfully",
					icon: "success"
				})
				.then(ok => {
					$('#schedulesList').DataTable().destroy();
					loadSubjectSchedules({"id" : $("#subjectId").val()});
					$('#schedForm').parsley().reset();
					$('#schedForm').trigger('reset');
					$('#schedForm').removeAttr('method');
				})
			}
		});
	} 
});
$(document).on('click','.btnSaveSched', function() {
	$("#schedForm").submit();
});


$(document).on('click','.btnDeleteSchedule', function(){
	var id = $(this).attr('data-id');
	swal({
		title: "Confirmation",
		text: "Are you sure you want to delete schedule?",
		icon: "warning",
		buttons :[
			"Cancel",
			{
				text: "Delete",
				closeModal: false
			}
		]
	})
	.then(del => {
		if(del) {
			$.post('/ses/api/schedules/delete.php',JSON.stringify({"id" : id}), function(res){
				if(res.success) {
					swal({
					text: "Deleted successfully",
					icon: "success"
					})
					.then(ok => {
						$('#schedulesList').DataTable().destroy();
						loadSubjectSchedules({"id" : $("#subjectId").val()});
						$('#subjectForm').trigger('reset');
					})
				}
			});
		}
	})
});

$(document).on('click','.btnEditSchedule', function() {
	var id = $(this).attr('data-id');
	$.post('/ses/api/schedules/read.php',JSON.stringify({"id" : id}), function(res) {
		if(res.success) {
			var data = res.message;
			$("#schedForm").attr('method','patch').attr('data-id',id);
			$('.sName').each(function() {
				if($(this).attr('value') == data.name) {
					$(this).attr('checked',true);
				}
			});
			$(".sUnit").val(data.unit);
			$('#timeDay').val(data.time_day);
			$("#timeHour").val(data.time_hour);
			$("#timeMin").val(data.time_min);
			$("#suffix").val(data.suffix);
			$("#status").val(data.status);
			$("#modal-content").scrollTop(0);
			

		}
	});
})
$(document).on('keypress','.sUnit', function() {
	var val = $(this).val();
	return $(this).val(Math.abs(val));
});

$(document).on('click','.btnRefreshSched', function(){
	loadSubjectSchedules({"id":$("#subjectId").val()});
});