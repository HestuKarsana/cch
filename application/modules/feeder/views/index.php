<section id="page-dg-feeder-mp">
	<div class="mail-containers">
		<div class="mail-container-header">
			Feeder Market Place <span id="page-loading"></span>
			<div class="pull-right col-md-8">
				<div class="row">
					<div class="col-md-6">
						
					</div>
					<div class="col-md-6 col-md-offset-6">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="key-search" id="key-search" >
							
							<span class="input-group-btn">
								<button class="btn" type="button" id="btn-search">
									<span class="fa fa-search"></span>
								</button>
								<!--<a href="<?php echo site_url('xray/form');?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Data Gagal Xray</a>-->
								<!--<a href="javascript:void(0);" class="btn btn-primary" id="upload-xray"><i class="fa fa-upload"></i>Upload</a>-->
								<span class="btn btn-success fileinput-button">
									<span><i class="fa fa-upload"></i> Upload</span>
									<input id="fileupload" type="file" name="files[]">
								</span>
								
							</span>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
		<div class="row" id="grid-body">
			<div class="col-md-12">
				<table id="dgContact" class="dgResize"></table>
			</div>
			
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_progress">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Upload Progress</h4>
				</div>
				
				<div class="modal-body">
					<h3> Upload Process</h3>
					<div id="progress" class="progress">
						<div class="progress-bar progress-bar-success">
							<span id="percentValue">0% Complete</span>
						</div>
					</div>
			
					<h3> File Reader </h3>
					<div id="progress-excel" class="progress">
						<div class="progress-bar progress-bar-success">
							<span id="percentValue-excel">0% Complete</span>
						</div>
					</div>
					<div id="readExcel">Read Excel</div>
				</div>
				<!--
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
				-->
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_tracking">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Tracking Barcode / AWB : <span id="tracking_awb_no"></span></h4>
				</div>
				
				<div class="modal-body" id="timeline-tracking" style="height:300px; overflow:auto;">
					<!-- TIMELINES 
					<div class="container">
						<div class="timeline">
							<div class="timeline-month">
								August, 2018 <span>3 Entries</span>
							</div>
							<div class="timeline-section">
								<div class="timeline-date">
									21, Tuesday
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="timeline-box">
											<div class="box-title">
											<i class="fa fa-asterisk text-success" aria-hidden="true"></i> Job Created
											</div>
											<div class="box-content">
											<a class="btn btn-xs btn-default pull-right">Details</a>
											<div class="box-item"><strong>Loss Type</strong>: A/C Leak</div>
											<div class="box-item"><strong>Loss Territory</strong>: Texas</div>
											<div class="box-item"><strong>Start Date</strong>: 08/22/2018</div>
											</div>
											<div class="box-footer">- Tyler</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="timeline-box">
											<div class="box-title">
											<i class="fa fa-pencil text-info" aria-hidden="true"></i> Job Edited
											</div>
											<div class="box-content">
											<a class="btn btn-xs btn-default pull-right">Details</a>
											<div class="box-item"><strong>Project Manager</strong>: Marlyn</div>
											<div class="box-item"><strong>Supervisor</strong>: Carol</div>
											</div>
											<div class="box-footer">- Tyler</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					 END TIMELINES -->
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					<!--<button type="button" class="btn btn-primary">Save changes</button>-->
				</div>
				
			</div>
		</div>
	</div>
</section>