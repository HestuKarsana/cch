
<script type="text/javascript">
	
	init.push(function () {
		
		$('#ticketHistory').slimScroll({ height: 300, alwaysVisible: true, color: '#888',allowPageScroll: true });
		$('#editClick').on('click', function(){
			$('#name, #address, #phone, #email').removeAttr('readonly');
		});
		$('#cancelClick').on('click', function(){
			$('#name, #address, #phone, #email').attr('readonly','true');
		});
		
		$("#contacts-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
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
										//window.location = site_url + 'contacts';
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
		$("#phone").rules("add", { required: true });
		$("#email").rules("add", { email: true });
	})
	
</script>

<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title"><?php echo $page_title;?></span>
			</div>
			<form medthod="post" action="<?php echo site_url('contacts/save');?>" id="contacts-form">
				<div class="row">
					<div class="col-md-6">
						<div class="panel-body" ng-controller="contactsDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="name">Name *</label>
										<input type="text" class="form-control" id="name" name="name" ng-cloak value="" placeholder="Name" ng-model="name">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="address">Address</label>
										<textarea class="form-control" id="address" name="address" ng-cloak value="" placeholder="Address" ng-model="address"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="phone">Phone *</label>
										<input type="text" class="form-control" id="phone" name="phone" ng-cloak placeholder="phone" ng-model="phone">
									</div>
								</div>
								<div class="col-md-7">
									<div class="form-group">
										<label for="email">Email</label>
										<input type="text" class="form-control" id="email" name="email" ng-cloak placeholder="email" ng-model="email">
									</div>
								</div>
							</div>
							<hr>
							<input type="hidden" value="" name="uid" id="uid" ng-value="id" />
							<button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
							<!--
							<button type="button" name="Edit" class="btn btn-danger" id="editClick" ><i class="fa fa-pencil"></i> Edit</button>
							<button type="button" name="Edit" class="btn btn-danger" id="cancelClick" ><i class="fa fa-cancel"></i> Cancel</button>
							-->
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
				</div>
			</form>
		</div>
	</div>
	
</div>