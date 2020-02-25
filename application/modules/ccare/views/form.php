<script>

</script>
<section id="page-form-customer-care">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Pengaduan Pelanggan <span class="h6"><?php echo $page_title;?></span><span  id="page-loading"></span>
		</div>
		<br />
		
		<form method="post" action="<?php echo site_url('ccare/save');?>" id="ccare-form">
			<div class="col-md-6">
                <div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="channel">Chanel Pengaduan</label>
							<select class="form-control" id="channel" name="channel" placeholder="Name">
                            </select>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_ktp">No KTP <span class="required hidden">*</span></label>
									<input type="text" name="id_ktp" id="id_ktp" class="form-control"/>
									<small id="sm_id_ktp"></small>
                                </div>        
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">No Telepon <span class="required">*</span></label>
									<select name="phone" id="phone" class="form-control">
									</select>
                                </div>        
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email <span class="required hidden">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="email@domain.com"/>
									<small id="sm_email"></small>
                                </div>        
                            </div>
                        </div>
						
						<div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="facebook">Facebook <span class="required hidden">*</span></label>
									<input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook"/>
									<small id="sm_facebook"></small>
                                </div>        
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram">Instagram <span class="required hidden">*</span></label>
									<input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram"/>
									<small id="sm_instagram"></small>
                                </div>        
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <label for="twitter">Twitter <span class="required hidden">*</span></label>
									<input type="text" class="form-control" id="twitter" name="twitter" placeholder="twitter"/>
									<small id="sm_twitter"></small>
                                </div>        
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label for="requester">Nama Pengadu <span>*</span></label>
                            <input type="text" class="form-control" id="requester" name="requester" placeholder="Name Pemohon">
							<small id="sm_requester"></small>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Alamat Lengkap"></textarea>
							<small id="sm_address"></small>
                        </div>
                        
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-12">
						<div class="form-group">
							<label for="type_of_request">Jenis Informasi / Pengaduan <span>*</span></label>
							<select name="type_of_request" id="type_of_request" class="form-control">
                                <option value=""> - Select - </option>
                            </select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					
					<input type="hidden" name="uid" id="uid" />
					<input type="hidden" name="newCustomer" id="newCustomer" />
					<!--
					<button type="submit" name="submit" id="btn-submit-form" class="btn btn-primary ld-ext-right">
						Simpan
						<div class="ld ld-ring ld-spin"></div>
					</button>
					<input type="button" name="cancel" class="btn btn-danger" value="Cancel" onclick="javascript:history.back();">
					-->
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
				<section id="forms-helper">
					<div class="block-loader hidden" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;">
						<div class="lds-ripple"><div></div><div></div></div>
					</div>
				</section>
			</div>
		</form>
	</div>
</section>