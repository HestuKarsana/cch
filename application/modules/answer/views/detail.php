<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">F.A.Q</span>
			</div>

			<div class="panel-body">
				<div class="panel">
					<div class="panel-heading">
						<span class="panel-title"><?php echo $row->question;?></span>
					</div>
					<div class="panel-body">
						<?php echo nl2br($row->answer);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
