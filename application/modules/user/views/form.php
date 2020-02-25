<script type="text/javascript">
	
	init.push(function () {
		$('.select2min').select2({minimumResultsForSearch:Infinity});
		$('#role').select2().on('select2:select', function (e) {
			var data = e.params.data;
			$('#kantor_pos').rules('add',{required:true});
			$('#kantor_pos').prop("disabled", false);
			$('#utype').rules('add',{required:true});
			$('#utype').prop("disabled", false);

			if(data.id == '0007'){
				$('#kantor_pos').rules('remove','required');
				$('#kantor_pos').prop("disabled", true);

				$('#utype').rules('remove','required');
				$('#utype').prop("disabled", true);
			}
		});
		$('#kantor_pos').select2({
			ajax: {
				url: site_url + 'app/get_kantor_pos',
				dataType: 'json',
				method:"POST",
				delay: 250,
				data: function (params) {
					var query = {
						city: params.term
					}
					return query;
				},
				processResults: function (data) {
					return {
						results : data
					}
				},
				cache: true
			},
			placeholder: 'Cari Kantor Pos',
			minimumInputLength: 1,
		})


		


		$("#user-form").validate({
			focusInvalid: true, 
			//errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'user/doSave',$("#user-form").serialize(),function(data){
					if(data.status){
						bootbox.dialog({
							message: data.message,
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
						$.growl.error({ message: data.message });
					}
					
				},'json').fail(function(jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 500)
					{
						$.growl.error({message:"Terjadi kesalahan, silahkan hubungi administrator."});
					}else if(textStatus == 'parseerror'){
						$.growl.error({message:"Terjadi kesalahan, silahkan hubungi administrator."});
						howError();
					}
					
				});
			}
		});
		
		$("#fullname").rules("add", { required: true });
		$("#username").rules("add", { 
			required: true, 
			remote: { 
				url : site_url + "user/check_username", 
				type:'post', 
				data: { 
					uid : function(){ 
						return $('#uid').val();
					}
				} 
			} 
		});
		$("#email").rules("add", { required: true, email:true,
			remote: { 
				url : site_url + "user/check_email", 
				type:'post', 
				data: { 
					uid : function(){ 
						return $('#uid').val();
					}
				} 
			} 
		});
		$("#utype").rules("add", { required: true});
		$("#kantor_pos").rules("add", { required: true});
		$("#password").rules("add", { required: true, minlength:4 });
		$("#repassword").rules("add", { equalTo: '#password' });
		$("#role").rules("add", { required: true });
		$("#status").rules("add", { required: true });
		
	})
</script>
<section id="page-form-user">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Pengguna <loading></loading>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form medthod="post" id="user-form">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="fullname">Nama Lengkap *</label>
								<input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group" ng-controller="userRole">
								<label for="role">Role *</label>
								<select name="role" class="form-control" id="role" >
									<option value=""> Select Role </option>
									<option ng-repeat="option in data" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="utype">Level *</label>
								<select name="utype" id="utype" class="form-control select2min">
									<option value="KPRK">KPRK</option>
									<option value="Regional">Regional</option>
									<option value="Pusat">Pusat</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="kantor_pos">Kantor Pos *</label>
								<select name="kantor_pos" id="kantor_pos" class="form-control">
								</select>
							</div>
						</div>
						
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="username">Akun Pengguna *</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="User">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="email">Email *</label>
								<input type="text" class="form-control" id="email" name="email" placeholder="email@domain.com">
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
							<div class="form-group">
								<label for="password">Password *</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="repassword">Repassword *</label>
								<input type="password" class="form-control" id="repassword" name="repassword" placeholder="repassword">
							</div>
						</div>
					</div>
					<!--
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="is_admin">Is Admin</label>
								<div class="checkbox">
									<label>
									  <input type="checkbox" name="is_admin" value="1"> Can login as admin
									</label>
								  </div>
							</div>
						</div>
					</div>
					-->
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group" ng-controller="statusData">
								<label for="status">Status *</label>
								<select name="status" class="form-control" id="status" >
									<option value=""> Select Status </option>
									<option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
							
						</div>
						
					</div>
					<hr>
					<input type="hidden" value="" name="uid" id="uid" />
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="button" name="cancel" class="btn btn-danger" value="Cancel" onclick="javascript:history.back();">
				</form> 
			</div>
		</div>
	</div>
</section>