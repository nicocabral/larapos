$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip()
	loaUsersList()

});

function loaUsersList(){
	$.get('/ses/api/users/list.php',{"type":"user"}, function(res) {
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
							<td><p class="${status.toLowerCase() === 'active' ? 'text-success' : 'text-danger'}">${status}</p></td>
							<td>
								<a href="#" class="btnEdit text-info" data-toggle="tooltip" title="Edit" data-original-title="Edit" data-id="${val.id}"><i class="fas fa-edit"></i></a> &nbsp;
								<a href="#" class="btnDelete text-danger" data-toggle="tooltip" title="Delete" data-original-title="Delete" data-id="${val.id}" data-username="${val.username}"><i class="fas fa-trash-alt"></i></a>
							</td>
						</tr>`;
					});
					$("#usersTable>tbody").html(html);
					$('[data-toggle="tooltip"]').tooltip()
					
					$('#usersTable').DataTable({
						"destroy": true,
						"processing": true,
						"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
					})
				} else {
					
					$("#usersTable>tbody").html('');
					$('#usersTable').DataTable({
						"destroy": true,
						"processing": true,
						"columnDefs": [
					    { "orderable": false, "targets": 5 }
					  ]
					})
				}
				
			}
	});
}

$(document).on('click','.btnAdd', function(){
	hideResetPassword();
	$(".username").removeAttr('readonly');
	$('#userForm').trigger('reset');
	$('#userForm').removeAttr('method');
	$('#modal').modal('show');
	


});

$(document).on('click','.btnSave', function(){
	$("#userForm").submit();
})

$(document).on('submit','#userForm', function(e){
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
			payload.type = "user";
			var method = $(this).attr('method');
			var url = method === "patch" ? '/ses/api/users/update.php' : '/ses/api/users/create.php';
				payload.id = $(this).attr('id') ? $(this).attr('data-id') : '';

		$.ajax({
			url : url,
			method: 'POST',
			data: JSON.stringify(payload),
			success:function(res) {
				$('.btnSave').buttonLoader('reset');

				if(res.success) {
					var msg = method === 'patch' ? 'Save successfully'  : 'Save successfully \nUsername: '+res.user_credentials.username+'\nPassword: '+res.user_credentials.password;
					$('#userForm').parsley().reset();
					$('#userForm').trigger("reset");
					$('#userForm').removeAttr('method').removeAttr('data-id');

					swal({
						title: "Success",
						text: msg,
						icon: "success"
					}).then(ok=> {
						$('#usersTable').DataTable().destroy();
						loaUsersList()
						
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
						$('#usersTable').DataTable().destroy();
						loaUsersList();
					})
					
				}
			})
		}
	})
})

$(document).on('click','.btnRefresh', function(){
	$('#usersTable').DataTable().destroy();
	loaUsersList();
})

$(document).on('click','.btnEdit', function(){
	var id = $(this).attr('data-id');
	showResetPassword();
	$.post('/ses/api/users/read.php',JSON.stringify({"id" : id}), function(res) {
		if(res.success) {

			$('#modal').modal('show');
			var data = res.message,
				keys = Object.keys(data),
				frm = $("#userForm");
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
						if(data.status.toLowerCase() !== 'active') {
							$(".status").prop( "checked", false );
						} else {
							$(".status").prop( "checked", true );
						}
						
					})
					$('.username').attr('readonly','readonly');
					
					$('#userForm').parsley().reset();
					$('#userForm').attr('method','patch').attr('data-id',id);
				}
		}
	});
});