<script>
	function act_search(){
		$('#dgTicket').datagrid('reload',{start:$('#start').val(), end:$('#end').val(), search:$('#search').val(), status : $('#status').val()});
	}
	
	function act_createXLS(){
		$.post( site_url + 'report/get_incoming_kprk_ticket',{export:'xls', kprk:getUri(site_url, 3), start:$('#start').val(), end:$('#end').val(), search:$('#search').val(), filter : $('#status').val()}, function(data){
			if(data.status){
				window.location = data.path;
			}
		},"json");
		
	}
</script>
<section id="page-rekap-layanan">
	<div class="mail-containers">
		<div class="mail-container-header">
			Laporan Layanan
			<div class="pull-right col-md-6">
				<div class="row">
					<div class="col-md-3">
                        
					</div>
					<div class="col-md-9">
                        <!--
						<div class="input-group input-group-sm">
							<div class="input-daterange input-group" id="datepicker">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="date" class="form-control" name="start" id="start" style="padding:0 10px;" />
								<span class="input-group-addon mid">to</span>
								<input type="date" class="form-control" name="end" id="end" style="padding:0 10px;" />
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
							<span class="input-group-btn">
								<button class="btn btn-info" type="button" onclick="javascript:act_search();"><i class="fa fa-search"></i>.</button>
								
								<button class="btn btn-info" type="button" onclick="javascript:act_createXLS();"><i class="fa fa-download"></i>.</button>
								
							</span>
						</div>
                        -->
					</div>
				</div>
				
			</div>
		</div>
        <div class="col-md-6">
            <h4>Laporan Rekapitulasi Informasi Layanan POS</h4>
            <table id="dgTicket" class="dgResize"></table>
            <h4>Laporan Rekapitulasi Jumlah Pengaduan Yang Diterima Dalam Applikasi CCH</h4>
            <table id="dgTicketAduan" class="dgResize"></table>
            <h4>Laporan Rekapitulasi Jumlah Pengaduan Yang Diterima Perjenis Masalah & Produk</h4>
            <table id="dgTicketMasalahProduk" class="dgResize"></table>
        </div>
		<div class="col-md-6">
            
        </div>
        
	</div>
</section>