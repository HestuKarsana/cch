<script type="text/javascript">
	
	init.push(function () {
		
		$('#ticketHistory').slimScroll({ height: 300, alwaysVisible: true, color: '#888',allowPageScroll: true });

		$('#province').on('change', function(){
			$.post( site_url + 'general/getCity', {prov:$(this).val()}, function(data){
				$('#city').html(generateOption(data));
			},"json");
		});
		
		$('#city').on('change', function(){
			$.post( site_url + 'general/getKecamatan', {city:$(this).val(), prov:$('#province').val()}, function(data){
				$('#kecamatan').html(generateOption(data));
			},"json");
		});
		
		$('#kecamatan').on('change', function(){
			$.post( site_url + 'general/getKelurahan', {kecamatan:$(this).val(),city:$('#city').val(), prov:$('#province').val()}, function(data){
				$('#kelurahan').html(generateOption(data));
			},"json");
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
			<?php echo $page_title;?> <loading></loading>
		</div>
		<br/>
		<!--
		<button ng-click="viewForm(fedit)">EDIT</button>
		-->
		<form medthod="post" id="contacts-form">
			<div class="col-md-6" >
				<div class="col-md-12">
					<div class="row" ng-show="row.type_customer">
						<div class="col-md-4">
							<div class="form-group">
								<label for="code_mum">Code MUM</label>
								<input type="text" class="form-control" id="code_mum" name="code_mum" placeholder="Code" ng-readonly="fedit"  ng-model="row.code_mum">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="type_customer">Customer Type</label>
								<select class="form-control" id="type_customer" name="type_customer" ng-controller="contacts_type" ng-model="row.type_customer" ng-readonly="fedit">
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
								  <input type="checkbox" name="verifikasi_mum" ng-disabled="fedit" value="1" ng-checked="row.verifikasi_mum==1" ng-model="row.verifikasi_mum"> Verifikasi MUM
								</label>
							  </div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="customer_name">Name</label>
						<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Name" ng-model="row.name" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="no_handphone">No Handphone *</label>
						<input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="No handphone"  ng-model="row.phone_number" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="text" class="form-control" id="email" name="email" placeholder="Email"  ng-model="row.email" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="address">Home Address</label>
						<textarea type="text" class="form-control" id="address" name="address" placeholder="Home Address"  ng-model="row.address" ng-readonly="fedit"></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="delivery_address">Delivery Address</label>
						<textarea type="text" class="form-control" id="delivery_address" name="delivery_address" placeholder="Delivery Address" ng-model="row.delivery_address" ng-readonly="fedit"></textarea>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="province">Province</label>
						<select name="province" id="province" class="form-control" ng-controller="generatePropinsi" ng-model="row.propinsi" ng-readonly="fedit">
							<option value="">- Select -</option>
							<option ng-repeat="op_propinsi in data" value="{{op_propinsi.id}}">{{op_propinsi.name}}</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="city">Kota / Kabupaten</label>
						<select name="city" id="city" class="form-control hidden">
							<option value="">- Select -</option>
						</select>
						<input type="text" disabled="true" class="form-control" ng-model="row.kota_name">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="kecamatan">Kecamatan</label>
						<select name="kecamatan" id="kecamatan" class="form-control hidden">
							<option value="">- Select -</option>
						</select>
						<input type="text" disabled="true" class="form-control" ng-model="row.kecamatan_name">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="kelurahan">Kelurahan</label>
						<select name="kelurahan" id="kelurahan" class="form-control hidden">
							<option value="">- Select -</option>
						</select>
						<input type="text" disabled="true" class="form-control" ng-model="row.kelurahan_name">
					</div>
				</div>
			
				<div class="col-md-12">
					<div class="form-group">
						<label for="patokan">Patokan</label>
						<input type="text" class="form-control" id="patokan" name="patokan" placeholder="Patokan"  ng-model="row.patokan" ng-readonly="fedit">
					</div>
				</div>
				</div>
			</div>
				
				<div class="col-md-6">
					
						<div id="ticketHistory" class="widget-comments panel-body tab-pane no-padding fade active in setMargin" ng-controller="ticketHistory" ng-init="init('<?php echo $this->uri->segment(3);?>')">
							<div class="panel-padding no-padding-vr">
								
								
								<div class="comment " ng-repeat="option in ticket" ng-cloak>
									<ng-avatar initials="{{option.avatar}}" round-shape="true" style="width:32px;height: 32px;" class="comment-avatar"></ng-avatar>
									<div class="comment-body">
										<div class="comment-by">
											<!--Ticket No : <a href="{{option.openTicket}}" title="">#{{option.no_ticket}}</a> on Category <a href="{{option.openTicketCategories}}" title="">{{option.category_name}}</a>-->
											Ticket No : <a href="{{option.openTicket}}" title="">#{{option.no_ticket}}</a> on Category <strong>{{option.category_name}}</strong>
											<span class="pull-right label label-{{option.param}}">{{option.status_name}}</span>
										</div>
										<div class="comment-text">
											{{option.subject}}
										</div>
										<div class="comment-actions">
											
											<a href="{{option.openTicket}}"><i class="fa fa-eye"></i>View</a>
											<span class="pull-right">{{option.date_ago}}</span>
										</div>
									</div>
								</div>
								
								
							</div>
							
						</div>
				</div>
				
			</form>
		
	</div>
</div>