<script type="text/javascript">
	
	/*
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
	*/
</script>
<section id="contacts-detail">
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
					<div class="form-group">
						<label for="customer_name">Name</label>
						<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Name" ng-model="row.name_requester" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="no_handphone">No Handphone *</label>
						<input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="No handphone"  ng-model="row.phone" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="text" class="form-control" id="email" name="email" placeholder="Email"  ng-model="row.email" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="facebook">Facebook</label>
						<input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook"  ng-model="row.facebook" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="instagram">Instagram</label>
						<input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram"  ng-model="row.instagram" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="twitter">Twitter</label>
						<input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter"  ng-model="row.twitter" ng-readonly="fedit">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="address">Home Address</label>
						<textarea type="text" class="form-control" id="address" name="address" placeholder="Address"  ng-model="row.address" ng-readonly="fedit"></textarea>
					</div>
				</div>
				
				</div>
			</div>
				
				<div class="col-md-6">
					<h4> Riwayat Pengaduan ( 5 Terakhir )</h4>
					<div id="ticketHistory" class="widget-comments panel-body tab-pane no-padding fade active in" ng-controller="ticketHistory" ng-init="init('<?php echo $this->uri->segment(3);?>')">
						<div class="panel-padding no-padding-vr">
							
							
							<div class="comment " ng-repeat="option in ticket" ng-cloak>
								<ng-avatar initials="{{option.avatar}}" round-shape="true" style="width:32px;height: 32px;" class="comment-avatar"></ng-avatar>
								<div class="comment-body">
									<div class="comment-by">
										<!--Ticket No : <a href="{{option.openTicket}}" title="">#{{option.no_ticket}}</a> on Category <a href="{{option.openTicketCategories}}" title="">{{option.category_name}}</a>-->
										Ticket No : <a href="{{option.openTicket}}" title="">#{{option.no_ticket}}</a> No Barcode / AWB <strong>{{option.awb}}</strong>
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
				<!--
				<div class="col-md-6">
					<h4> Riwayat Customer</h4>
					<div id="contactHistory" class="widget-comments panel-body tab-pane no-padding fade active in" ng-controller="contactHistory" ng-init="init('<?php echo $this->uri->segment(3);?>')">
						<div class="panel-padding no-padding-vr">
							
							<div class="comment " ng-repeat="option in ticket" ng-cloak>
								<ng-avatar initials="{{option.avatar}}" round-shape="true" style="width:32px;height: 32px;" class="comment-avatar"></ng-avatar>
								<div class="comment-body">
									<div class="comment-by">
										Ticket No : <a href="{{option.openTicket}}" title="">#{{option.no_ticket}}</a> No Barcode / AWB <strong>{{option.awb}}</strong>
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
				-->
			</form>
		
	</div>
</div>
</section>