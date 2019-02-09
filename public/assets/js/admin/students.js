$(document).ready(function(){
	formWizard()
	$('[data-toggle="tooltip"]').tooltip()
	loadStudentList();
});
function hideResetPassword(){
	$(".btnResetpassword").hide();
}
function showResetPassword(){
	$(".btnResetpassword").show();
}
function loadStudentList(){
	
	$.ajax({
		url: '/ses/api/users/list.php',
		method: 'get',
		data: {"type" : "student"},
		dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function(res) {
			var html = '';
			if(res.success) {
				var data = res.message;
				if(data.length > 0) {
					
					data.map(val => {
						var status = val.status;
						html+= `<tr>
							<td>${val.username}</td>
							<td>${val.first_name}</td>
							<td>${val.last_name}</td>
							<td>${val.contact_number}</td>
							<td><p class="${status.toLowerCase() === 'active'|| status.toLowerCase() === "enrolled" ? 'text-success' : 'text-danger'}">${status}</p></td>
							<td>
								<a href="#" class="btnEdit text-info" data-toggle="tooltip" title="Edit" data-original-title="Edit" data-id="${val.id}"><i class="fas fa-edit"></i></a> &nbsp;
								<a href="#" class="btnDelete text-danger" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="${val.id}" data-username="${val.username}"><i class="fas fa-trash-alt"></i></a>
							</td>
						</tr>`;
					});
					$("#studentsTable>tbody").html(html);
					$('[data-toggle="tooltip"]').tooltip()
					
					$('#studentsTable').DataTable({
						"destroy": true,
						"processing": true,
						"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
					})
				} else {
					
					$("#studentsTable>tbody").html('');
					$('#studentsTable').DataTable({
						"destroy": true,
						"processing": true,
						"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
					})
				}
				
			}
			
		}
		
	})
}

$(document).on('click','.btnAdd', function(){
	hideResetPassword();
	$(".btnSave").hide();
	$(".username").removeAttr('readonly');
	$('#studentForm').trigger('reset');
	$('#studentForm').removeAttr('method');
	$('#modal').modal('show');

});
$(document).on('click','.btnNext', function(){
	if($(this).is('a')) {
		$('.nav-link.show').each(function(){
			if($(this).attr('href') === "#studStrand" && $(this).hasClass('active')) {
				$(".btnSave").show();
				$('.btnNext').not(this).hide(); 
			} else {

				$(".btnSave").hide();
				$(".btnNext").show();
				
			}
		});
	} else {
		$('.nav-link.show').each(function(){
			if($(this).hasClass("active")) {
				switch($(this).attr('id')) {
					case 'liStudFamInfo':
						$("#liStudStrand").click();
						break;
						
					case 'liStudDetails':
						$("#liStudFamInfo").click();
						break;
				}
			}
		});
		
	}
	
});
$(document).on('click','.nav-link', function(){
	if($(this).attr('href') === "#studStrand") {
		$(".btnSave").show();
		$('.btnNext').not(this).hide(); 
	} else {
		$(".btnSave").hide();
		$(".btnNext").show();
	}
});
$(document).on('click','.btnSave', function(){
	$("#studentForm").submit();
})

$(document).on('submit','#studentForm', function(e){
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
			payload.type = "student";
			var method = $(this).attr('method');
			var url = method === "patch" ? '/ses/api/students/update.php' : '/ses/api/students/create.php';
				payload.id = $(this).attr('id') ? $(this).attr('data-id') : '';
			payload.metas = JSON.stringify({
				"famInfo_mName" : $("input[name=famInfo_mName]").val(),
				"famInfo_fName" : $("input[name=famInfo_fName]").val(),
				"famInfo_gName" : $("input[name=famInfo_gName]").val(),
				"famInfo_gContactNo" : $("input[name=famInfo_gContactNo]").val(),
				"famInfo_gAddress" : $("textarea[name=famInfo_gAddress]").val(),
				"studStrand" : $("select[name=studStrand]").val(),
				"studGrade" : $("select[name=studGrade]").val()
			});

		$.ajax({
			url : url,
			method: 'POST',
			data: JSON.stringify(payload),
			success:function(res) {
				$('.btnSave').buttonLoader('reset');

				if(res.success) {
					var msg = method === 'patch' ? 'Save successfully'  : 'Save successfully \nUsername: '+res.user_credentials.username+'\nPassword: '+res.user_credentials.password;
					$('#studentForm').parsley().reset();
					if(method !== 'patch') {
						$('#studentForm').trigger("reset");
						$('#studentForm').removeAttr('method').removeAttr('data-id');
					} 
					swal({
						title: "Success",
						text: msg,
						icon: "success"
					}).then(ok=> {
						$('#studentsTable').DataTable().destroy();
						loadStudentList()
						
					})
				}
			}
		})

	}

});

$(document).on('click','.btnDelete', function(){
	var id = $(this).attr('data-id'),
		username = $(this).attr('data-username');
	swal({
		title: "Confirmation",
		text: "Are you you want to delete "+ username+'?',
		icon: "warning",
		dangerMode: true,
		buttons: [
			"Cancel",
			{
				text: "Delete",
				closeModal:false
			}
		]
	})
	.then(del => {
		if(del) {
			$.post('/ses/api/users/delete.php',JSON.stringify({"id":id}),function(res){
				if(res.success) {
					swal({
						title: "Success",
						text: res.message,
						icon: 'success'
					}).then(ok => {
						$('#studentsTable').DataTable().destroy();
						loadStudentList();
					})
					
				}
			})
		}
	})
})

$(document).on('click','.btnRefresh', function(){
	$('#studentsTable').DataTable().destroy();
	loadStudentList();
})

$(document).on('click','.btnEdit', function(){
	var id = $(this).attr('data-id');
	showResetPassword();
	$(".btnSave").show();
	$.post('/ses/api/users/read.php',JSON.stringify({"id" : id}), function(res) {
		if(res.success) {
			$('#modal').modal('show');
			var data = res.message,
				keys = Object.keys(data),
				frm = $("#studentForm");
				$(".btnResetpassword").attr('data-username',data.username).attr('data-id',data.id);
				if(keys.length > 0) {
					keys.map(val => {
						var v = val;
						if(val === "contact_number") {
							val = val.includes('_') ? val.split('_') : val;
							val = val[0]+val[1].charAt(0).toUpperCase() + val[1].slice(1);
							$("input[name="+val+"]",frm).val(data[v]);
						}
						val = val.includes('_') ? val.replace('_','') : val;
						$("input[name="+val+"]",frm).val(data[v]);
						$("textarea[name="+val+"]",frm).val(data[v]);
						$("select[name="+val+"]",frm).val(data[v]);
						if(data.status.toLowerCase() !== 'active') {
							$(".status").prop( "checked", false );
						} else {
							$(".status").prop( "checked", true );
						}
						
					})
					if(typeof data.metas === "object") {
						var metaKeys = Object.keys(data.metas);
						if(metaKeys.length > 0 ){
							metaKeys.map(v => {
								$("input[name="+v+"]",frm).val(data.metas[v]);
								$("textarea[name="+v+"]",frm).val(data.metas[v]);
								$("select[name="+v+"]",frm).val(data.metas[v]);
							});
						}
					}
					$('#studentForm').parsley().reset();
					$('#studentForm').attr('method','patch').attr('data-id',id);
				}
		}
	});
});
function formWizard(){
	// Step show event
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
       //alert("You are on step "+stepNumber+" now");
       if(stepPosition === 'first'){
       		if($("#studentForm").attr('method') === "patch") {
       			$(".btnSave").show();
       		} else {
       			$(".btnSave").hide();
       		}
       	   
           $("#prev-btn").addClass('disabled');
       }else if(stepPosition === 'final'){
       	   $(".btnSave").show();
           $("#next-btn").addClass('disabled');
       }else{
       	   if($("#studentForm").attr('method') === "patch") {
       			$(".btnSave").show();
       		} else {
       			$(".btnSave").hide();
       		}
           $("#prev-btn").removeClass('disabled');
           $("#next-btn").removeClass('disabled');
       }
    });


    // Smart Wizard 1
    $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'arrows',
            transitionEffect:'fade',
            showStepURLhash: false,
           
    });

}