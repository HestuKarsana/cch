<script>
	function act_search(){
		$('#dgTicket').datagrid('reload',{start:$('#start').val(), end:$('#end').val(), search:$('#search').val(), status : $('#status').val()});
	}
	
	function act_createXLS(){
		$.post( site_url + 'report/get_outgoing_regional_ticket',{export:'xls', regid:getUri(site_url, 3), start:$('#start').val(), end:$('#end').val(), search:$('#search').val(), status : $('#status').val()}, function(data){
			if(data.status){
				window.location = data.path;
			}
		},"json");
	}
</script>
<section id="page-rep-ticket-outgoing-regional">
	<div class="mail-containers">
		<div class="mail-container-header">
			Laporan Tiket Keluar - <?php echo $reg->regional.' '.$reg->city.' '.$status;?><loading></loading>
			<div class="pull-right col-md-6">
				<div class="row">
					<div class="col-md-3">
					</div>
					<div class="col-md-9">
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
								<!--<button class="btn btn-info btn-flat btn-sm" type="button" onclick="javascript:act_chart();"><i class="fa fa-line-chart fa-lg"></i> Show Chart </button>-->
								
								<button class="btn btn-info" type="button" onclick="javascript:act_createXLS();"><i class="fa fa-download"></i>.</button>
								
							</span>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<table id="dgTicket" class="dgResize"></table>
	</div>
</section>