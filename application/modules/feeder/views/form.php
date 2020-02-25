<section id="page_form_xray">
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Gagal Data X Ray <span class="h6"><?php echo $page_title;?></span><span  id="page-loading"></span>
		</div>
		<br />
		
		<form method="post" id="myxray-form">
			<div class="col-md-6">
                <div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="kantor_penerbangan">Kantor Penerbangan</label>
							<select class="form-control input-search-kantorpos" id="kantor_penerbangan" name="kantor_penerbangan" placeholder="Name">
                            </select>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="kantor_asal">Kantor Asal</label>
                            <select class="form-control input-search-kantorpos" id="kantor_asal" name="kantor_asal" placeholder="Kantor Asal">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kantor_tujuan">Kantor Tujuan</label>
                            <select class="form-control input-search-kantorpos" id="kantor_tujuan" name="kantor_tujuan" placeholder="Kantor Tujuan">
                            </select>
                        </div>
						<div class="form-group">
                            <label for="id_kiriman">ID Kiriman</label>
                            <input type="text" name="id_kiriman" id="id_kiriman" class="form-control required" placeholder="ID Kiriman">
                        </div>
						<div class="form-group">
                            <label for="isi_kiriman">Isi Kiriman</label>
                            <input type="text" name="isi_kiriman" id="isi_kiriman" class="form-control" placeholder="Isi Kiriman">
                        </div>
						<div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
				</div>
				<div class="row">
					<div class="col-md-12">
					
					<input type="hidden" name="uid" id="uid" />
					
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
				</section>
			</div>
		</form>
	</div>
</section>