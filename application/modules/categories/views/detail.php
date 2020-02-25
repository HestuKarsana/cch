<script>
	init.push(function () {
		
		
		$("#responseticket-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'ticket/saveResponse',$("#responseticket-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										location.reload();
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
		$("#response").rules("add", { required: true });
		
	})
</script>
<style>

</style>
<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">View Ticket : #<?php echo $no_ticket;?></span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-title"><h3><?php echo $subject;?></h3></div>
				<small>Date Create Ticket : <?php echo $date;?></small>
			</div>
			<div class="panel-heading bg-primary">
				<div class="row">
					<div class="col-md-3">
						<p><strong>Department</strong></p>
						<p><?php echo $category_name;?></p>
					</div>
					<div class="col-md-3">
						<p><strong>Owner</strong></p>
						<p><a href="<?php echo site_url('contacts/d/'.$uid);?>"><?php echo $phone_number;?></a></p>
					</div>
					<div class="col-md-3">
						<p><strong>Status</strong></p>
						<p><?php echo $status_name;?></p>
					</div>
					<div class="col-md-3">
						<p><strong>Priority</strong></p>
						<p><?php echo $priority;?></p>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="row" ng-controller="formReplyCont">
	<div class="col-md-12">
		<button name="replay" ng-disabled="buttonDisabled" class="btn btn-info" ng-click="ShowHide()"><i class="fa fa-reply"></i> Replay</button>
	
		<div ng-show="formReply">
			<form medthod="post" id="responseticket-form" >
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="response">Your Response</label>
							<textarea class="form-control" id="response" name="response" placeholder="Response" row="6"></textarea>
						</div>
					</div>
				</div>
				<input type="hidden" value="<?php echo $tid;?>" name="uid" id="uid" />
				<input type="submit" name="submit" class="btn btn-primary" value="Save">
				<input type="reset" name="cancel" class="btn btn-danger" value="Cancel" ng-click="ShowHide()">
			</form>
		</div>
	</div>
</div>
<br/>
<?php
if($detail->num_rows() > 0)
{
	foreach($detail->result() as $row)
	{
		
		?>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
								<h3><?php echo $row->staff_name;?></h3>
								<small> Steff </small>
							</div>
							<div class="col-md-9">
								<small>Posted On : <?php echo $row->date;?></small>
								<p><?php echo nl2br($row->response);?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		
	}
}
?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<h3><?php echo $phone_number;?></h3>
						<small> USER </small>
					</div>
					<div class="col-md-9">
						<small>Posted On : <?php echo $date;?></small>
						<p><?php echo nl2br($complaint);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>