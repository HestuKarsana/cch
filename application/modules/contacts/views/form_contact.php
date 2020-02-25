<script type="text/javascript">
	
	init.push(function () {
		$('#province').on('change', function(){
			$.post( site_url + 'general/getCity', {prov:$(this).val()}, function(data){
				$('#kota_name').addClass('hidden');
				$('#city').html(generateOption(data)).removeClass('hidden');
			},"json");
		});
		
		$('#city').on('change', function(){
			$.post( site_url + 'general/getKecamatan', {city:$(this).val(), prov:$('#province').val()}, function(data){
				$('#kecamatan_name').addClass('hidden');
				$('#kecamatan').html(generateOption(data)).removeClass('hidden');
			},"json");
		});
		
		$('#kecamatan').on('change', function(){
			$.post( site_url + 'general/getKelurahan', {kecamatan:$(this).val(),city:$('#city').val(), prov:$('#province').val()}, function(data){
				$('#kelurahan_name').addClass('hidden');
				$('#kelurahan').html(generateOption(data)).removeClass('hidden');
			},"json");
		});
		
		$("#contacts-form").validate({ 
			focusInvalid: true, 
			//errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'contacts/save',$("#contacts-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'contacts';
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
		
		$("#customer_name").rules("add", { required: true });
		$("#no_handphone").rules("add", { required: true });
		$("#email").rules("add", { 
			email: true ,
			remote: { 
				url : site_url + "contacts/check_email", 
				type:'post', 
				data: { 
					uid : function(){ 
						return $('#uid').val();
					}
				} 
			}
		});
		
		
	})
	
	function generateOption(data){
		var html = '';
		$.each(data, function(key, value){
			html += '<option value="'+value.id+'">'+value.name+'</option>';
		});
		return html;
	}
</script>
<div ng-controller="contactsDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Contacts <span class="h6"><?php echo $page_title;?></span><loading></loading>
		</div>
		<br />
		
		<form medthod="post" action="<?php echo site_url('contacts/save');?>" id="contacts-form">
			<div class="col-md-6 col-md-offset-3">
				<div class="row" ng-show="row.type_customer > 0">
					<div class="col-md-4">
						<div class="form-group">
							<label for="code_mum">Code MUM</label>
							<input type="text" class="form-control" id="code_mum" name="code_mum" placeholder="Code"  ng-model="row.code_mum">
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="type_customer">Customer Type</label>
							<select class="form-control" id="type_customer" name="type_customer" ng-controller="contacts_type" ng-model="row.type_customer">
								<option value=""> Pilih </option>
								<option ng-repeat="opt in option" value="{{opt.id}}">{{opt.name}}</option>
							</select>
						</div>
					</div>
					<div class="col-md-8" ng-show="row.parent_customer">
						<div class="form-group">
							<label for="parent_customer_name">Parent</label>
							<input type="text" name="parent_customer_name" id="parent_customer_name" class="form-control" ng-readonly="true" ng-model="row.parent_customer_name">
							<input type="hidden" name="parent_customer" id="parent_customer" ng-value="row.parent_customer">
						</div>
					</div>
					<div class="col-md-4" ng-show="row.parent_customer">
						<label for="parent_customer_name">.</label>
						<div class="checkbox">
							<label>
							  <input type="checkbox" name="verifikasi_mum" value="1" ng-checked="row.verifikasi_mum==1" ng-model="row.verifikasi_mum"> Verifikasi MUM
							</label>
						  </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="customer_name">Name</label>
							<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Name"  ng-model="row.name">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="no_handphone">No Handphone *</label>
							<input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="No handphone"  ng-model="row.phone_number">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email">Email Address</label>
							<input type="text" class="form-control" id="email" name="email" placeholder="Email"  ng-model="row.email">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="address">Home Address</label>
							<textarea type="text" class="form-control" id="address" name="address" placeholder="Home Address"  ng-model="row.address"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="delivery_address">Delivery Address</label>
							<textarea type="text" class="form-control" id="delivery_address" name="delivery_address" placeholder="Delivery Address" ng-model="row.delivery_address"></textarea>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="province">Province</label>
							<select name="province" id="province" class="form-control" ng-controller="generatePropinsi" ng-model="row.propinsi" >
								<option value="">- Select -</option>
								<option ng-repeat="op_propinsi in data" value="{{op_propinsi.id}}">{{op_propinsi.name}}</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="city">Kota / Kabupaten</label>
							<select name="city" id="city" class="form-control hidden">
								
							</select>
							<input type="text" disabled="true" class="form-control" ng-model="row.kota_name" id="kota_name">
						</div>
					</div>
				</div>
				
				<div class="row">										
					<div class="col-md-6">
						<div class="form-group">
							<label for="kecamatan">Kecamatan</label>
							<select name="kecamatan" id="kecamatan" class="form-control hidden">
								
							</select>
							<input type="text" disabled="true" class="form-control" ng-model="row.kecamatan_name" id="kecamatan_name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="kelurahan">Kelurahan</label>
							<select name="kelurahan" id="kelurahan" class="form-control hidden">
								
							</select>
							<input type="text" disabled="true" class="form-control" ng-model="row.kelurahan_name" id="kelurahan_name">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="patokan">Patokan</label>
							<input type="text" class="form-control" id="patokan" name="patokan" placeholder="Patokan" ng-model="row.patokan">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					
					<input type="hidden" name="uid" id="uid" ng-value="row.id" />
					<!--
					<button type="button" name="cancel" class="btn btn-info" value="Cancel" onclick="javascript:history.back();"><i class="fa fa-backward"></i></button>
					-->
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="button" name="cancel" class="btn btn-danger" value="Cancel" onclick="javascript:history.back();">
					
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>