<script>
	init.push(function () {
		
		$("#user-form").validate({ 
			//focusInvalid: true, 
			rules:{
				erepassword:{
					equalTo : '#epassword'
				}
			},
			//errorPlacement: function () {},
			submitHandler: function(form){
				$.post( site_url + 'user/update',$("#user-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'user';
									}
								}
							},
							className: "bootbox-sm"
						});
					}else{
						$.growl.error({ message: data.msg });
					}
				},'json').fail(function(jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 500)
					{
						alert('Error 500');
					}else if(textStatus == 'parseerror'){
						alert('Parse error');
						howError();
					}
				});
			}
		});
		
		$("#password-form").validate({ 
			focusInvalid: true, 
			//errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'user/changePassword',$("#password-form").serialize(),function(data){
					$('#modalPasswordForm').modal('hide');
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'user';
									}
								}
							},
							className: "bootbox-sm"
						});
					}else{
						$.growl.error({ message: data.msg });
					}
					
				},'json').fail(function(jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 500)
					{
						alert('Error 500');
					}else if(textStatus == 'parseerror'){
						alert('Parse error');
						howError();
					}
					
				});
				
			}
		});
		
		$("#fullname").rules("add", { required: true });
		$("#status").rules("add", { required: true });
		
		$("#old_password").rules("add", { required: true });
		$("#new_password").rules("add", { required: true });
		$("#retype_password").rules("add", { equalTo: '#new_password' });
	});
	
function showModalPassword(uid){
	$('#modalPasswordForm').modal({
		backdrop : 'static',
		show : true
	});
	
	$('#modalPasswordForm #uid').val(uid);
}	
</script>
<div ng-controller="userDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')">
	<div class="mail-containers">
		<div class="mail-container-header">
			User Manager <loading></loading>
			<!--
			<a href="javascript:void(0);" onclick="javascript:showModalPassword('<?php echo $this->uri->segment(3);?>');" class="pull-right btn btn-primary btn-xs"><i class="fa fa-lock"></i> Change Password</a></span>
			-->
		</div>
		<br />
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form medthod="post" id="user-form">
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="fullname">Nama Lengkap *</label>
								<input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nama Lengkap" ng-model="fullname">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="username">Username *</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="Username" ng-model="username" ng-readonly="is_admin">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email">Email *</label>
								<input type="text" class="form-control" id="email" name="email" placeholder="Email" ng-model="email" ng-readonly="is_admin">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="Telepon">Telepon *</label>
								<input type="text" class="form-control" id="telepon" name="telepon" placeholder="081xxx">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" ng-controller="userRole">
								<label for="role">Role User *</label>
								<select name="role" class="form-control" id="role" ng-model="role" ng-readonly="is_admin">
									<option value=""> Select Role </option>
									<option ng-repeat="option in data" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" ng-controller="statusData">
								<label for="status">Status *</label>
								<select name="status" class="form-control" id="status" ng-model="status" ng-readonly="is_admin">
									<option value=""> Select Status </option>
									<option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group" ng-controller="userRole">
								<label for="epassword">Password </label>
								<input type="password" name="epassword" id="epassword" class="form-control">
								<small>( biarkan kosong jika tidak akan merubah password )</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group" ng-controller="statusData">
								<label for="repassword">Ulangi Password</label>
								<input type="password" name="erepassword" id="erepassword" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="row">
						
						
					</div>
					<hr>
					<input type="hidden" value="" name="uid" id="uid" ng-value="id" />
					<input type="submit" name="submit" class="btn btn-primary" value="Update">
				</form>
			</div>
		</div>
	</div>
</div>

<div id="modalPasswordForm" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form medthod="post"  id="password-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Change Password</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="old_password">Old Password</label>
								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="new_password">New Password</label>
								<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="retype_password">Retype New Password</label>
								<input type="password" class="form-control" id="retype_password" name="retype_password" placeholder="Retype New Password">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="uid" id="uid" value="">
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="reset" name="cancel" class="btn btn-danger" value="Cancel" data-dismiss="modal">
					
				</div>
			</form>
		</div>
	</div>
</div>