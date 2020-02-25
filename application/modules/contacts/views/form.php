<section id="page-form-customer-care">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Pengaduan Customer <span class="h6"><?php echo $page_title;?></span><span  id="page-loading"></span>
		</div>
		<br />
		
		<form medthod="post" action="<?php echo site_url('contacts/save');?>" id="contacts-form">
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
                        <div class="form-group">
                            <label for="nama_pemohon">Nama Pemohon</label>
                            <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" placeholder="Name Pemohon"  ng-model="row.name_complaint">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_telepon">No Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="No Telepon"/>
                                </div>        
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="email@address.com"/>
                                </div>        
                            </div>
                        </div>
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group">
							<label for="jenis_permohonan">Jenis Informasi / Pengaduan</label>
							<select name="jenis_permohonan" id="jenis_permohonan" class="form-control">
                                <option value=""> Pilih </option>
                            </select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="nama_pengirim">Nama Pengirim</label>
							<input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" placeholder="Nama Pengirim"  ng-model="row.phone_number">
						</div>
                        <!--
                        <div class="form-group">
							<label for="alamat_pengirim">Alamat Pengirim</label>
							<textarea class="form-control" id="alamat_pengirim" name="alamat_pengirim" placeholder="Alamat Pengirim"></textarea>
						</div>
                        <div class="form-group">
							<label for="kode_pengirim">Kode Pos</label>
							<input type="text" class="form-control" id="kode_pengirim" name="kode_pengirim" placeholder="Alamat Pengirim"/>
						</div>
                        <div calss="col-md-6">
                            <div class="form-group">
                                <label for="telp_pengirim">No Handphone</label>
                                <input type="text" class="form-control" id="telp_pengirim" name="telp_pengirim" placeholder="No Telepon"/>
                            </div>        
                        </div>
                        <div calss="col-md-6">
                            <div class="form-group">
                                <label for="email_pengirim">Email</label>
                                <input type="text" class="form-control" id="email_pengirim" name="email_pengirim" placeholder="Email Pengirim"/>
                            </div>        
                        </div>
                        -->
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="nama_penerima">Nama Penerima</label>
							<input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="Nama Penerima"  ng-model="row.phone_number">
						</div>
                        <!--
                        <div class="form-group">
							<label for="alamat_penerima">Alamat Penerima</label>
							<textarea class="form-control" id="alamat_penerima" name="alamat_penerima" placeholder="Alamat Penerima"></textarea>
						</div>
                        <div class="form-group">
							<label for="kode_penerima">Kode Pos</label>
							<input type="text" class="form-control" id="kode_penerima" name="kode_penerima" placeholder="Alamat Penerima"/>
						</div>
                        <div calss="col-md-6">
                            <div class="form-group">
                                <label for="telp_penerima">No Handphone</label>
                                <input type="text" class="form-control" id="telp_penerima" name="telp_penerima" placeholder="No Telepon"/>
                            </div>        
                        </div>
                        <div calss="col-md-6">
                            <div class="form-group">
                                <label for="email_penerima">Email</label>
                                <input type="text" class="form-control" id="email_penerima" name="email_penerima" placeholder="Email"/>
                            </div>        
                        </div>
                        -->
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					
					<input type="hidden" name="uid" id="uid" ng-value="row.id" />
					<!--
					<button type="button" name="cancel" class="btn btn-info" value="Cancel" onclick="javascript:history.back();"><i class="fa fa-backward"></i></button>
					-->
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="button" name="cancel" class="btn btn-danger" value="Cancel" onclick="javascript:history.back();">
					
					</div>
				</div>
				
			</div>
		</form>
	</div>
</section>