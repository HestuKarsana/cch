<script type="text/javascript">
	
	init.push(function () {
	
		$("#role-form").validate({ 
			focusInvalid: true, 
			//errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'role/doSave',$("#role-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'role';
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
		
		
		$("#name").rules("add", { required: true });
		$("#status").rules("add", { required: true });
		
		$.post( site_url + 'role/generateMenuAccess',{role_id:getUri(site_url,3)}, function(data){
	   		var html = generateCheckbox(data.checkbox);
   			$('#menuaccess_list').html(html);
		},"json");
	})
	
	
	/* generate checkbox function for rolemanager */
	function generateCheckbox(data){
		var html	= "";
		html	+= "<ul class=\"menu-list\" style='list-style:none;'>";
		$.each(data, function(key, value){
			var checked	= "";
			var disable	= "";
			if(value.checked){
				checked	= 'checked="checked"';
			}
			if(value.disable){
				disable	= 'disabled="disabled"';
			} 
			html	+= "<li class=\"menu-list\">";
			html	+= "<div class=\"checkbox\">";
			html	+= "<label><input type=\"checkbox\" id="+value.value+" "+disable+" "+checked+"class=\"listcheckbox "+value.parentid+"\" name=\"menulist[]\" value="+value.value+" alt="+value.parentid+" /> <label for="+value.value+"><span></span>"+value.text+"</label></label>";
			html	+= "</div>";
			if(value.child.checkbox != ""){
				html	+= generateChildCheck(value.child.checkbox);
			}
			html	+= "</li>";
		});
		html	+= "</ul>";
		return html;
	}
	
	function generateChildCheck(data){
		var html	= "";
		html	+= "<ul class=\"child-menu-list\"  style='list-style:none;'>";
		$.each(data, function(key, value){
			var checked	= "";
			var disable	= "";
			if(value.checked){
				checked	= 'checked="checked"';
			}
			if(value.disable){
				disable	= 'disabled="disabled"';
			}  
			html	+= "<li>";
			html	+= "<div class=\"checkbox\">";
			html	+= "<label><input type=\"checkbox\" id="+value.value+" "+disable+" "+checked+"class=\"listcheckbox "+value.parentid+"\" name=\"menulist[]\" value="+value.value+" alt="+value.parentid+" /> <label for="+value.value+"><span></span>"+value.text+"</label></label>";
			html	+= "</div>";
			html	+= "</li>";
		});
		html	+= "</ul>";
		return html;
	}
	
</script>
<div>
	<div class="mail-containers"  ng-controller="roleDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>');">
		<div class="mail-container-header">
			Form Role Manager <loading></loading>
			<a href="<?php echo site_url('role/create');?>" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Create</a>
		</div>
		
		<br/>
		<form medthod="post" id="role-form">
			<div class="col-md-6">
				<div class="col-md-12">
					<div class="form-group">
						<label for="name">Role Name *</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="Role Name" ng-model="name">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group" ng-controller="statusData">
						<label for="status">Status *</label>
						<select name="status" class="form-control" id="status" ng-model="status">
							<option value=""> Select Status </option>
							<option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
						</select>
					</div>
					
				</div>
				<div class="col-md-12">
					<input type="hidden" value="" name="uid" id="uid" ng-value="id" />
						<input type="submit" name="submit" class="btn btn-primary" value="Save">
						<input type="reset" name="cancel" class="btn btn-danger" value="Cancel">
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<span class="panel-title">List Menu</span>
					</div>
					<div class="panel-body">
						<div id="menuaccess_list"></div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>