<script type="text/javascript">
	
	init.push(function () {
		
		$("#phone").select2({
			placeholder: "Search Contact",
			minimumInputLength: 1,
			ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
				//url: "http://api.rottentomatoes.com/api/public/v1.0/movies.json",
				url: site_url + "ticket/getContactData",
				type: "POST",
				dataType: 'json',
				data: function (term, page) {
					return {
						q: term, // search term
						page_limit: 10,
						//apikey: "ju6z9mjyajq2djue3gbvv26t" // please do not use so this example keeps working
					};
				},
				results: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								text: item.name,
								slug: item.slug,
								id: item.id
							}
						})
					};
				}
			}
			//formatResult: movieFormatResult, // omitted for brevity, see the source of this page
			//formatSelection: movieFormatSelection,  // omitted for brevity, see the source of this page
			//dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
			//escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
		});
		
		
		$("#ticket-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'ticket/save',$("#ticket-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'ticket';
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
		$("#subject").rules("add", { required: true });
		$("#phone").rules("add", { required: true });
		$("#category").rules("add", { required: true });
		$("#complaint").rules("add", { required: true });
		$("#status").rules("add", { required: true });
		
	})
	//window.LanderApp.start(init);
	function showError(){
		$('#uidemo-modals-alerts-danger').modal({
			keyboard: false,
			backdrop:'static'
		});
	}
	
</script>

<div class="row">
	<div class="col-sm-12">
		<div class="panel">	
			<div class="panel-heading">
				<span class="panel-title">Create Ticket</span>
			</div>
			<div class="panel-body" ng-controller="ticketDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')">
				<form medthod="post" action="<?php echo site_url('ticket/save');?>" id="ticket-form">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone *</label>
								<input type="text" ng-if="phone != undefined" readonly="true" class="form-control" id="phone_old" name="phone" placeholder="phone" ng-model="phone">
								<input type="text" ng-if="phone == undefined"class="form-control" id="phone" name="phone" placeholder="phone" ng-model="phone">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" ng-controller="ticketCategory">
								<label for="category">Categories *</label>
								<select name="category" class="form-control" id="category" ng-model="category">
									<option value=""> Select Categories </option>
									<option ng-repeat="option in data" value="{{option.id}}">{{option.name}}</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="subject">Subject *</label>
								<input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" ng-model="subject">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="complaint">Complaint *</label>
								<textarea class="form-control" id="complaint" name="complaint" placeholder="Complaint" ng-model="complaint"></textarea>
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
