<section id="page-notification-form">
	
		
		<div class="mail-containers" id="container_ticket">
			<div class="mail-container-header">Form Pengumuman</div>
			
				<div class="new-mail-form">
					<form medthod="post" id="notification-form">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="title">Judul *</label>
									<input type="text" class="form-control" id="title" name="title" placeholder="Judul">
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="detail">Detail *</label>
									<textarea class="form-control" id="detail" name="detail"></textarea>
									
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="start">Tgl Mulai *</label>

									<div class="input-group">
										<input type="date" name="start" id="start" class="form-control">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="end">Tgl Selesai</label>
									<div class="input-group">
										<input type="date" name="end" id="end" class="form-control">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
									<small> Biarkan kosong jika pengumuman hanya 1 hari</small>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="hidden" name="uid" id="uid">
									<button id="submit" class="btn btn-primary">Simpan</button>
								</div>
							</div>
						</div>
					</div>	
					</form>
				</div>
			
			
		</div>
		
</section>