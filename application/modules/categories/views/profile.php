<script>
	init.push(function () {
		
		$("#user-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
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
			errorPlacement: function () {},
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
	});
	
function showModalPassword(uid){
	$('#modalPasswordForm').modal({
		backdrop : 'static',
		show : true
	});
	
	$('#modalPasswordForm #uid').val(uid);
}	
</script>
<style>
table#example{
	font-size:11px;
}
table thead tr th{
	font-size:11px !important;
}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="panel">	
			<div class="panel-heading">
				<span class="panel-title">User Profile <a href="javascript:void(0);" onclick="javascript:showModalPassword('<?php echo $this->uri->segment(3);?>');" class="pull-right btn btn-primary btn-xs"><i class="fa fa-lock"></i> Change Password</a></span>
				
			</div>
			<div class="panel-body" ng-controller="userDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')">
				<form medthod="post" action="<?php echo site_url('user/save');?>" id="user-form">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="username">Username *</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="Username" ng-model="username" ng-readonly="username">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="fullname">Fullname *</label>
								<input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname" ng-model="fullname">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="is_admin">Is Admin</label>
								<div class="checkbox">
									<label>
									  <input type="checkbox" name="is_admin" value="1" ng-checked="is_admin == 1"> Can login as admin
									</label>
								  </div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" ng-controller="statusData">
								<label for="status">Status *</label>
								<select name="status" class="form-control" id="status" ng-model="status">
									<option value=""> Select Status </option>
									<option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
							
						</div>
						
					</div>
					<hr>
					<input type="hidden" value="" name="uid" id="uid" ng-value="id" />
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="reset" name="cancel" class="btn btn-danger" value="Cancel">
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