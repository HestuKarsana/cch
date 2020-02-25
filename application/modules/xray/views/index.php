<section id="page-dg-xray">
	<div class="mail-containers">
		<div class="mail-container-header">
			Data Gagal X Ray <span id="page-loading"></span>
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
								<?php
								if($this->session->userdata('ses_role') == '004'){
									?>
									<a href="<?php echo site_url('xray/form');?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Data Gagal Xray</a>
									<span class="btn btn-primary fileinput-button">
										<span>Upload</span>
										<input id="fileupload" type="file" name="files[]">
									</span>
									<?php
								}
								?>
								
								
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
          <!--
        <div class="progress">
            
		  <div class="progress-bar progress-bar-striped active" id="uploadProgressBar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
		    <span id="percentValue">0% Complete</span>
		  </div>
            
            
		</div>
		-->
		<div id="readExcel">Read Excel</div>
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
      -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->   
</section>