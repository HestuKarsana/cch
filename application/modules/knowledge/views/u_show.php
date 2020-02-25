<style>
.no-panel-padding a.list-group-item{ border:none; }
</style>
<div>
	<!-- mail right -->
	<div class="mail-containers" ng-controller="knowledgeIndex">
		<div class="mail-container-header">
			<?php echo $title;?> 
			<form action="javascript:void(0);" class="pull-right" style="width: 200px;margin-top: 3px;">
				
			</form>
		</div>
		
		
		<div class="col-md-8">
			<div style="padding:10px 10px;">
				<div class="panel colourable">
					<div class="panel-body">
						<?php echo nl2br($detail);?>
					</div>
				</div>
				
				<div class="h6">Post by <?php echo $username;?> on <?php echo $last_update;?></div>
			</div>
		</div>
		<div class="col-md-4">
			<div style="padding:10px 0px;">
				<div class="panel colourable widget-support-tickets" id="dashboard-support-tickets">
					<div class="panel-heading">
						<span class="panel-title ng-binding"><i class="panel-title-icon fa fa-clock-o"></i>Recent Post</span>
						<div class="panel-heading-controls">
							<div class="panel-heading-text"></div>
						</div>
					</div>
					<div class="ng-scope">
						<div class="no-panel-padding no-padding-vr">
							
							<?php
							if($recent->num_rows() > 0)
							{
							
								foreach($recent->result() as $row)
								{
									echo '<a href="'.site_url("knowledge/show/".$row->uniq_id).'" class="list-group-item">'.$row->title.'</a> ';
								}
							}
							?>
						</div>
					</div>
				</div>
				
				<div class="panel colourable widget-support-tickets" id="dashboard-support-tickets">
					<div class="panel-heading">
						<span class="panel-title ng-binding"><i class="panel-title-icon fa fa-tags"></i>Tags</span>
						<div class="panel-heading-controls">
							<div class="panel-heading-text"></div>
						</div>
					</div>
					<div class="panel-body tab-content-padding ng-scope">
						<div class="panel-padding no-padding-vr">
							<?php
							$xtags	= explode(',',$tags);
							foreach($xtags as $k=>$v)
							{
								echo '<a href="javascript:void(0);" class="label label label-default label-tag">'.$v.'</a> ';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>