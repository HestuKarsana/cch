<script>
document.addEventListener('DOMContentLoaded', function() {
	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: [ 'dayGrid','interaction'],
		events : site_url + 'app/get_events',
		selectable: true,
		dateClick: function(info) {
			//alert('clicked ' + info.dateStr);
			$('#start').val(info.dateStr);
			//$.post( site_url + 'holiday/load_event',{start:info.dateStr}, function(res){

			//},"json");
			
			$('#modal_form_holiday').modal({
			  keyboard: false,
			  backdrop : 'static',
			  show : true
			});
		},
		//select: function(info) {
		//	alert('selected ' + info.startStr + ' to ' + info.endStr);
		//}
		
	});
	calendar.render();
});
</script>
<section id="page-holiday">
	<div class="mail-containers" id="container_ticket">
		<div class="mail-container-header">Data Libur</div>
		<div class="new-mail-form">
	  		<div class="row">
	  			
				<div class="col-md-6">
	  				<table id="dgMacro"></table>
				</div>
				<div class="col-md-6">
				  	<div id="calendar"></div>
				</div>
			</div>
	  		
		</div>		
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal_form_holiday">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form medthod="post" id="holiday-form">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Form Holiday</h4>
				</div>
				<div class="modal-body">
				
					<div class="col-md-12">
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
								<label>Notifikasi</label>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="notifikasi" value="1"> Tambahkan notifikasi pada tanggal tersebut
									</label>
								</div>
								<!--
								<div class="form-group">
									<label for="end">Tgl Selesai</label>
									<div class="input-group">
										<input type="date" name="end" id="end" class="form-control">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
									<small> Biarkan kosong jika pengumuman hanya 1 hari</small>
								</div>
								-->
							</div>
							
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="hidden" name="uid" id="uid">
									<!--<button id="submit" class="btn btn-primary">Simpan</button>-->
								</div>
							</div>
						</div>
					</div>	
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</section>