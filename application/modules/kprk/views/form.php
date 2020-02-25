<section id="page-form-customer-care">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Pengaduan Customer <span class="h6"><?php echo $page_title;?></span><span  id="page-loading"></span>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">No Telepon <span class="required">*</span></label>
									<!--
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="No Telepon"/>
									-->
									<select name="phone" id="phone" class="form-control">
									</select>
                                </div>        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="required hidden">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="email@domain.com"/>
                                </div>        
                            </div>
                        </div>
						
						<div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="facebook">Facebook <span class="required hidden">*</span></label>
									<!--
									<select name="facebook" id="facebook" class="form-control">
									</select>
									-->
									<input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook"/>
                                </div>        
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram">Instagram <span class="required hidden">*</span></label>
									<input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram"/>
                                </div>        
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <label for="twitter">Twitter <span class="required hidden">*</span></label>
									<input type="text" class="form-control" id="twitter" name="twitter" placeholder="twitter"/>
                                </div>        
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label for="requester">Nama Pemohon <span>*</span></label>
                            <input type="text" class="form-control" id="requester" name="requester" placeholder="Name Pemohon">
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Alamat Lengkap"></textarea>
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
					
					<button type="submit" name="submit" id="btn-submit-form" class="btn btn-primary ld-ext-right">
						Simpan
						<div class="ld ld-ring ld-spin"></div>
					</button>
					<input type="button" name="cancel" class="btn btn-danger" value="Cancel" onclick="javascript:history.back();">
					
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
				<section id="forms-helper">
					<div class="block-loader hidden" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;">
						<div class="lds-ripple"><div></div><div></div></div>
					</div>
					<!--
					<div id="block-ticket hidden">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="sender_name">Nama Pengirim</label>
								<input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="Nama Pengirim">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="receiver_name">Nama Penerima</label>
								<input type="text" class="form-control" id="receiver_name" name="receiver_name" placeholder="Nama Penerima" >
							</div>
							
						</div>
					</div>
					<div class="form-group">
						<label> No Resi / AWB</label>
						<div class="input-group">
							<input type="text" name="resi" id="resi" class="form-control">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default" id="form-helper-btn-check" type="button">Cek</button>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label for="note"> Catatan</label>
						<textarea name="note" id="note" class="form-control"></textarea>
					</div>
					</div>
					-->
				</section>
			</div>
		</form>
	</div>
</section>