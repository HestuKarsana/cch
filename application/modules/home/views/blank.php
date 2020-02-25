<script>
	init.push(function (){
		localStorage.clear();

		$('#dashboard-support-tickets .panel-body > div').slimScroll({ height: 300, alwaysVisible: true, color: '#888',allowPageScroll: true });
		var dashboard = Morris.Line({
			element: 'hero-graph',
			//data: uploads_data,
			xkey: 'day',
			ykeys: ['v','x'],
			labels: ['Total Aduan','Total Info'],
			lineColors: ['#fff','#f4b04f'],
			lineWidth: 2,
			pointSize: 4,
			gridLineColor: 'rgba(255,255,255,.5)',
			resize: true,
			gridTextColor: '#fff',
			xLabels: "day",
			xLabelFormat: function(d) {
				return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov', 'Dec'][d.getMonth()] + ' ' + d.getDate(); 
			},
		});
		var uploads_data = {};
		$.post(site_url + 'home/getTotalTicketWeekly', function(uploads_data){
			dashboard.setData(uploads_data);
		},"json");
		
		/* ticket */
		
		
		//$.post( site_url + 'home/getTicketAll', function(data){
			//bar_total_ticket.setData(data.ticket);
			
		//},'json');
		
		$.post( site_url + 'home/getDashboardStat', function(result){
			$("#avg_hour_info > strong").text(result.info);
			$("#avg_hour_complaint > strong").text(result.complaint);
			$("#avg_hour_order > strong").text(result.order);
			$("#avg_hour_register > strong").text(result.register);
			
			$("#stat_all_in > strong").text(result.stat.all_ticket_in);
			$("#stat_solved_in > strong").text(result.stat.all_ticket_solved_in);
			$("#stat_unsolved_in > strong").text(result.stat.all_ticket_unsolved_in);
			$("#stat_avg_in > strong").text((result.stat.avg_ticket_in));

			$("#stat_all_out > strong").text(result.stat.all_ticket_out);
			$("#stat_solved_out > strong").text(result.stat.all_ticket_solved_out);
			$("#stat_unsolved_out > strong").text(result.stat.all_ticket_unsolved_out);
			$("#stat_avg_in > strong").text((result.stat.avg_ticket_out));

			//$("#stat_AvgTicketSolved > strong").text((result.dashboard.all_ticket_unsolved / result.dashboard.total_day).toFixed(0));
		},"json");
		/*
		socket.on('totalTicket', function(result){
			dashboard.setData(result.resultData);
		});
		
		socket.on('dashboardStat', function(result){
			angular.element('#dashboardStat').scope().reloadCounter();
			
			$("#stat_AllTicket > strong").text(result.dashboard[0].all_ticket);
			$("#stat_SolvedTicket > strong").text(result.dashboard[0].total_closed_system);
			$("#stat_UnsolvedTicket > strong").text(result.dashboard[0].all_ticket_unsolved);
			$("#stat_AvgTicket > strong").text((result.dashboard[0].all_ticket_unsolved / result.dashboard[0].total_day).toFixed(0));
			$("#stat_AvgTicketSolved > strong").text((result.dashboard[0].all_ticket_unsolved / result.dashboard[0].total_day).toFixed(0));
		});
		
		$('#print').click(function () {
			printMe();
		});
		*/
	});
	
	
	// This will render SVG only as PDF and download
	function printMe() {
		xepOnline.Formatter.Format('chart1', {render:'download', srctype:'svg'});
	}
	
	
	
</script>
<section id="page-dashboard">
<div class="dashboard setMargin" ng-controller="totalTicketCategories" id="dashboardStat">
<div class="row padding-dashboard">
	<div class="col-md-8">
		<script>
			
		</script>
		<!-- / Javascript -->

		<div class="stat-panel">
			<div class="stat-row">
				<div class="stat-cell col-sm-4 padding-sm-hr bordered no-border-r valign-top">
					
					<h4 class="padding-sm no-padding-t padding-xs-hr"><i class="fa fa-ticket text-primary"></i>&nbsp;&nbsp;Pengaduan Harian</h4>
					
					<ul class="list-group no-margin" ng-model="data.model" ng-cloak>
						<li class="list-group-item no-border-hr padding-xs-hr no-bg no-border-radius" ng-repeat="option in data">
							{{option.name}} <span class="label label-pa-purple pull-right">{{option.total_ticket}}</span>
						</li>
					</ul>
				</div>
				
				<div class="stat-cell col-sm-8 bg-primary padding-sm valign-middle" id="chart1">
					<div id="hero-graph" class="graph" style="height: 230px;"></div>
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="col-md-4">
		<div class="col-sm-4 col-md-12">
						
				<div class="stat-panel">
					<div class="stat-row">
						<!-- Success darker background -->
						<div class="stat-cell bg-success darker">
							<!-- Stat panel bg icon -->
							<i class="fa fa-ticket bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
							<!-- Big text -->
							<span class="text-bg">CCH PT POS</span><br>
							<!-- Small text -->
							<span class="text-sm">Statistik</span>
						</div>
					</div> <!-- /.stat-row -->
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_all_in"><strong>0</strong></span><br>
								<span class="text-xs">(IN) Semua</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_solved_in"><strong>0</strong></span><br>
								<span class="text-xs">(IN) Diselesaikan</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_unsolved_in"><strong>0</strong></span><br>
								<span class="text-xs">(IN) Terbuka</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_avg_in"><strong>0</strong></span><br>
								<span class="text-xs">(IN) Rata Rata</span>
							</div>
							
						</div>
					</div>
					<div class="stat-row">
						<div class="stat-counters bg-warning no-border-b no-padding text-center">							
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_all_out"><strong>0</strong></span><br>
								<span class="text-xs">(OUT) Semua</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_solved_out"><strong>0</strong></span><br>
								<span class="text-xs">(OUT) Diselesaikan</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_unsolved_out"><strong>0</strong></span><br>
								<span class="text-xs">(OUT) Terbuka</span>
							</div>
							<div class="stat-cell col-xs-3 padding-sm no-padding-hr">
								<span class="text-bg" id="stat_avg_out"><strong>0</strong></span><br>
								<span class="text-xs">(OUT) Rata Rata</span>
							</div>	
						</div>
					</div>
					<!--
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<a href="javascript:void(0);" class="stat-cell col-xs-4 bg-success padding-sm no-padding-hr valign-middle">
								<span class="text-xs">Average Time to Close </span>
							</a>
						</div>
					</div>
					
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							
							<div class="stat-cell col-xs-12 padding-sm no-padding-hr">
								<span class="text-bg" id="avg_hour">Waktu Rata Rata Selesai : <strong>0</strong></span><br>
							</div>
						</div>
					</div>
					-->
					<!--
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg" id="avg_hour_info"><strong>0</strong></span><br>
								<span class="text-xs">Hour Info</span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg" id="avg_hour_complaint"><strong>0</strong></span><br>
								<span class="text-xs">Hour Complaint</span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg" id="avg_hour_order"><strong>0</strong></span><br>
								<span class="text-xs">Order MUM</span>
							</div>							
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg" id="avg_hour_register"><strong>0</strong></span><br>
								<span class="text-xs">Register MUM</span>
							</div>	
						</div>
					</div>
					-->
					<!--
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<a href="javascript:void(0);" class="stat-cell col-xs-4 bg-success padding-sm no-padding-hr valign-middle" id="print">
								<span class="text-xs">MORE&nbsp;&nbsp;<i class="fa fa-caret-right"></i></span>
							</a>
						</div>
					</div>
					-->
				</div>
		</div>
	</div>
</div>
<div class="row padding-dashboard">
	<div class="col-md-12"><label>Aduan Masuk</label></div>
	<div class="col-md-4">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Pencapaian</span>
			</div>
			<div class="panel-body bg-success">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-success padding-sm valign-middle">
							<canvas id="chart_service_type" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		
		
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Jenis Produk</span>
			</div>
			<div class="panel-body bg-success">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-success padding-sm valign-middle">
							<canvas id="chart_jenis_layanan" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Masalah Pengaduan</span>
			</div>
			<div class="panel-body bg-success">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-success padding-sm valign-middle">
							<canvas id="chart_status" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
</div>

<div class="row padding-dashboard">
	<div class="col-md-12"><label>Aduan Keluar</label></div>
	<div class="col-md-4">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Pencapaian</span>
			</div>
			<div class="panel-body bg-danger">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-danger padding-sm valign-middle">
							<canvas id="chart_service_type_out" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		
		
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Jenis Produk</span>
			</div>
			<div class="panel-body bg-danger">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-danger padding-sm valign-middle">
							<canvas id="chart_jenis_layanan_out" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Masalah Pengaduan</span>
			</div>
			<div class="panel-body bg-danger">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-danger padding-sm valign-middle">
							<canvas id="chart_status_out" style="height: 150px;"></canvas>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
</div>
<!--
<div class="row padding-dashboard">
	
	
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">QA Ticket</span>
			</div>
			<div class="panel-body bg-danger">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-danger padding-sm valign-middle">
							<div id="qa_ticket" style="height: 250px;"></div>
						</div>
					</div>	
				</div>
			</div>
		</div>
		
		
	</div>
	
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Order Ticket</span>
			</div>
			<div class="panel-body bg-danger">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-danger padding-sm valign-middle">
							<div id="order_ticket" style="height: 250px;"></div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row padding-dashboard">
	
	
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Ticket </span>
			</div>
			<div class="panel-body bg-info">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-info padding-sm valign-middle">
							<div id="bar_total_ticket" style="height: 250px;"></div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">MUM Order </span>
			</div>
			<div class="panel-body bg-warning">
				<div class="stat-panel">
					<div class="stat-row">
						<div class=" stat-cell bg-warning padding-sm valign-middle">
							<div id="bar_total_mum" style="height: 250px;"></div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
-->
</div>
</section>