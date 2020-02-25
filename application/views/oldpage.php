<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html ng-app="cirmapp">
<head>
	<meta charset="utf-8">
	
	<title><?php echo $this->config->item('system_name');?></title>
	<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	
	<!-- Open Sans font from Google CDN -->
	<!--
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&amp;subset=latin" rel="stylesheet" type="text/css">
	-->
	<!-- LanderApp's stylesheets -->
	<link href="<?php echo base_url('assets/css/bootstrap.min.v3.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/landerapp.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/widgets.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/pages.min.css');?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo base_url('assets/css/themes.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/datagrid.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/jquery.fileupload.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/select2.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/daterangepicker.css');?>" rel="stylesheet" type="text/css"/>
	
	<!-- loading.io -->
    <link href="<?php echo base_url('assets/css/loading.css');?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url('assets/css/loading-btn.css');?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo base_url('assets/css/custom.css');?>" rel="stylesheet" type="text/css"/>
	<!-- Fullcalendar -->
	<link href="<?php echo base_url('assets/plugins/core/main.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/daygrid/main.min.css');?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/list/main.min.css');?>" rel="stylesheet" type="text/css"/>
	
	<style>
	/* jQuery Growl
 * Copyright 2015 Kevin Sylvestre
 * 1.3.5
 */


	</style>
	<!--[if lt IE 9]>
		<script src="<?php echo base_url('assets/js/ie.min.js');?>"></script>
	<![endif]-->
	
	
	
	<script src="<?php echo base_url('assets/js/angular.min.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-avatar.min.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-sanitize.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-summernote.js');?>"></script>
	
	<script src="<?php echo base_url('assets/js/socket.io.js');?>"></script>
	
	
</head>

<body class="theme-default main-menu-animated page-mail main-navbar-fixed main-menu-fixed mmc ">

<script>
var init = []; 
var base_url = '<?php echo base_url();?>'; 
var site_url = '<?php echo site_url();?>'; 
var dgheight = 0, uid = '<?php echo $this->session->userdata('pos_office');?>';
var myuid	= '<?php echo $this->session->userdata('ses_cid');?>';
var socket 	= io.connect("http://178.128.55.194:810/?kantor_pos="+myuid+"-"+uid);
</script>
<script src="<?php echo base_url('assets/js/ngapp.js');?>"></script>

<div id="main-wrapper">


<!-- 2. $MAIN_NAVIGATION ===========================================================================

	Main navigation
-->
	<div id="main-navbar" class="navbar navbar-inverse" role="navigation">
		<!-- Main menu toggle -->
		<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>
		
		<div class="navbar-inner">
			<!-- Main navbar header -->
			<div class="navbar-header">

				<!-- Logo -->
				<a href="<?php echo site_url();?>" class="navbar-brand">
					<?php echo $this->config->item('system_name');?>
				</a>

				<!-- Main navbar toggle -->
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

			</div>

			<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
				<div class="notif-top-page bg-danger hidden">
					
				</div>
				<div>
					<div class="right clearfix">
						<ul class="nav navbar-nav pull-right right-navbar-nav">
							<!-- NOTIF -->
							<li class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle user-menu" data-toggle="dropdown">
									<!--<img src="<?php echo base_url('assets/images/avatars/1.jpg');?>" alt="">-->
									<span><?php echo $this->session->userdata('ses_fullname');?></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo site_url('user/profile/'.$this->session->userdata('ses_cid'));?>">Profile </a></li>

									<li class="divider"></li>
									<li><a href="<?php echo site_url('logout');?>"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Keluar</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>


	

	<div id="main-menu" role="navigation">
		
		<div id="main-menu-inner">
			
			<ul class="navigation">
				<?php
					$query	= $this->mglobals->showMainMenu();
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$dropdown	= ($row->total_child > 0) ? 'mm-dropdown' : '';
							echo '<li class="'.$dropdown.'">';
								echo '<a href="'.site_url($row->url).'"><i class="menu-icon fa '.$row->icon.'"></i><span class="mm-text">'.$row->name.'</span></a>';
								if($row->total_child > 0)
								{
									$query	= $this->mglobals->showChildMenu($row->id);
									if($query->num_rows() > 0)
									{
										echo '<ul>';
										foreach($query->result() as $row)
										{
											$icon	= ($row->icon != '') ? '<i class="menu-icon fa '.$row->icon.'"></i>' : '';
											echo '<li><a tabindex="-1" href="'.site_url($row->url).'">'.$icon.'<span class="mm-text">'.$row->name.'</span></a></li>';
										}
										echo '</ul>';
									}
								}
							echo '</li>';
						}
					}
				?>
			</ul>
		</div>
	</div>

	<div id="content-wrapper">
		<?php echo $content; 
		#print_r($this->session->all_userdata());
		?>
	</div>
	
	<div id="main-menu-bg"></div>
</div>

<!-- Get jQuery from Google CDN -->
<!--[if !IE]> -->
	<script type="text/javascript"> window.jQuery || document.write('<script src="<?php echo base_url('assets/js/jquery.min.js');?>">'+"<"+"/script>"); </script>
<!-- <![endif]-->
<!--[if lte IE 9]>
	<script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>"); </script>
<![endif]-->


<!-- LanderApp's javascripts -->
<script src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.v3.js');?>"></script>

<script src="<?php echo base_url('assets/js/landerapp.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery.easyui.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/sweetalert.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/select2.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/daterangepicker.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/clipboard.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/highcharts.js');?>"></script>
<script src="<?php echo base_url('assets/js/wordcloud.js');?>"></script>
<script src="<?php echo base_url('assets/js/treemap.js');?>"></script>
<script src="<?php echo base_url('assets/js/chart.js@2.8.0.js');?>"></script>

<script src="<?php echo base_url('assets/plugins/core/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/daygrid/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/list/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/interaction/main.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/jquery.iframe-transport.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery.fileupload.js');?>"></script>
<script src="<?php echo base_url('assets/js/custom.js');?>"></script>
<script src="<?php echo base_url('assets/js/myreport.js');?>"></script>


<script type="text/javascript">
	function closeIndexSide(){
		$('#grid-body > div:first').addClass('col-md-12').removeClass('col-md-8');
		$('#grid-body > div.info-side-index').remove();
		//$('#grid-body').datagrid('unselectAll');
		$('#dgContact, #dgTicket').datagrid('resize');
	}
	function isEmpty(obj) {
		for(var key in obj) {
			if(obj.hasOwnProperty(key))
				return false;
		}
		return true;
	}
	function createTicket(data){
		//console.log(data.marketplace);
		localStorage.cTicket_channel 	= 'ccd1e1f97e8c55e5f9967ec259d92b5fafdcccc9';
		localStorage.cTicket_category 	= '0716d9708d321ffb6a00818614779e779925365c';
		localStorage.cTicket_requester 	= data.marketplace;
		localStorage.cTicket_address 	= data.penerima_alamat;
		localStorage.cTicket_phone 		= data.penerima_telp;
		localStorage.cTicket_awb 		= data.awb;
		localStorage.cTicket_notes 		= "Nomor Pesanan : "+data.no_pesanan+"\nPENERIMA :\n"+data.penerima+"\n"+data.penerima_alamat+"\n"+data.penerima_telp+"\nPENGIRIM :\n"+data.pengirim+"\n"+data.pengirim_alamat+"\n"+data.pengirim_telp;
		window.location.href 			= site_url + 'ccare/form';
		
	}
	function copyToClipboard(element) {

			

var $temp = $("<textarea>");
$("body").append($temp);
$temp.val($(element).text()).select();
document.execCommand("copy");
$temp.remove();
}

socket.on('newTicket', function(res){
			console.log(res.kantor_pos);
			console.log(uid);
			if(res.kantor_pos == uid){
				if(res.type == 'new'){
					$.growl.warning({ title: "Tiket Baru", message: res.message });
				}
			}
			
			
		})

var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		var dg_min		= $('.mail-container-header').height() + $('.mail-controls').height() + 55;
		var dg_mail		= $('.page-mail .mail-nav').height() - dg_min;
		var dg_mail_sc	= dg_mail - 70;
	init.push(function () {
		
		
		var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		var dg_min		= $('.mail-container-header').height() + $('.mail-controls').height() + 55;
		var dg_mail		= $('.page-mail .mail-nav').height() - dg_min;
		var dg_mail_sc	= dg_mail - 70;


		

		// NOTIF
		if($('#page-notification').length > 0)
		{
			$('#dgMacro').datagrid({
				url: site_url + 'notification/get_datalist',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					//start: $('#start').val(),
					//end: $('#end').val()
				},
				columns:[[
					{field:'title',title:'Nama', width:200, sortable:true, align:'left',
						formatter:function(v,r,i){
							return "<strong>"+v+"</strong><br/>"+r.description;
						}
					},
					{field:'event_date',title:'Tgl Acara',width:50, sortable:true, align:'center'},
					{field:'id',title:'Action',width:50, sortable:true, align:'center',
						formatter:function(v,r,i){
							var edit 	= '<a href="javascript:void(0);" onclick="javascript:_editForm(\'notification/form/'+v+'\',\''+v+'\');" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>';
							var remove 	= '<a href="javascript:void(0);" onclick="javascript:_removeData(\'notification/delete\',\''+v+'\',\'dgMacro\',);" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>';
							
							return edit +' '+ remove;
						}
					}
				]]
			})
		}

		if($('#page-notification-form').length > 0)
		{
			$('#end').removeAttr('disabled');
			$.post( site_url + 'notification/form_load',{uid : getUri(site_url,3)},function(res){
				
				$('#notification-form').form('load', res.form);
				if(res.form.uid != undefined){
					$('#end').attr('disabled','disabled');
				}
			},"json");

			$('#notification-form').validate({
				rules:{
					title:{required:true},
					description:{required:true},
					start:{required:true}
				},
				submitHandler: function(form) {
					$.post(site_url + 'notification/save', $('#notification-form').serialize(), function(res){
						if(res.status){
							swal({
								title: "Success!",
								text: res.message,
								content:"success",
								//timer: 2000,
								buttons: {
									cancel: "Kembali",
									addnew: "Tambah Lagi"
								},
								closeOnEsc: false,
								closeOnClickOutside: false
							}).then((value) => {
								switch (value) {
									case "addnew":
									window.location.reload();
									break;
									default:
										window.location.href = site_url + 'notification';
								}
							});
						}else{
							swal("Sorry",res.message, "error");
						}	
						
					},"json");
				}
			});
		}

		// -- END NOTIF 


		if($('#page-report-dashboard').length > 0)
		{
			/*
			var chart_kpi_event = document.getElementById('chart_kpi');
			chart_kpi_event.onclick = function(evt) {
				
				var activePoints = myChart.getElementsAtEvent(evt);
				if (activePoints[0]) {
					var chartData = activePoints[0]['_chart'].config.data;
					var idx = activePoints[0]['_index'];

					var label = chartData.labels[idx];
					var value = chartData.datasets[0].data[idx];
					console.log(activePoints[0]);
				}
				
			}
			*/
			function update_chart(kpi){
				$.post( site_url + 'report/update_chart',{kpi:kpi, month:$('#month').val(), year:$('#year').val()},function(res){
					kantorAsal.data = res.asal;
					kantorAsal.update();
					
					kantorTujuan.data = res.tujuan;
					kantorTujuan.update();

					cProduct.data = res.product;
					cProduct.update();
				},"json");
			}

			function update_chart_asal(kpi, asal){
				$.post( site_url + 'report/update_chart',{kpi:kpi, asal:asal, month:$('#month').val(), year:$('#year').val()},function(res){
					
					kantorTujuan.data = res.tujuan;
					kantorTujuan.update();

					cProduct.data = res.product;
					cProduct.update();

				},"json");
			}
			var kpi_selected = "";
			var asal_selected = "";
			var tujuan_selected = "";
			var kpi_default_radius;
			
			var ctx = document.getElementById('chart_kpi').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'pie',
				options: {
					onClick: function(evt, activeElements) {
						var elementIndex = activeElements[0]._index;
						var clickedLabel = this.data.labels[elementIndex];
						
						
						if (kpi_selected.toUpperCase() == "") {
							kpi_selected = clickedLabel.toUpperCase();
						}else if (clickedLabel.toUpperCase() == kpi_selected.toUpperCase()){
							kpi_selected = "";
						}else{
							kpi_selected = clickedLabel.toUpperCase();
						}

						update_chart(kpi_selected);

						
						
						//var clickedElementindex = activePoints[0]["_index"];
						/*
						if( ($('#kpi_value').val() == '') || ($('#kpi_value').val() != this.data.labels[elementIndex]) ){
							var org_color 	 = this.data.datasets[0].backgroundColor[elementIndex];
							this.data.datasets[0].backgroundColor[elementIndex] = 'black';
							$('#kpi_org_color').val(org_color);
							$('#kpi_value').val(this.data.labels[elementIndex]);
							this.update();
						}else{
							this.data.datasets[0].backgroundColor[elementIndex] = $('#kpi_org_color').val();
							$('#kpi_org_color').val('');
							$('#kpi_value').val('');
							this.update();
						}
						*/
					},
					legend: {
						position : 'right',
						labels: { fontColor: 'black', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								var currentLabel = data.labels[tooltipItem[0].index];
								//return currentLabel;
							},
							label: function(tooltipItem, data) {
								
								
								var currentLabel = data.labels[tooltipItem.index]
								//return percentage + " % ";
								return "Aduan diselesaikan "+currentLabel+" :";
								//var multistringText = ["Diselesaikan "+currentLabel+" :"];
								//multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
								//return multistringText;
							},
							footer: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem[0].datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem[0].index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

								var multistringText = ["Jumlah : "+currentValue+" "];
								multistringText.push("Persentase : "+percentage+" %");
								return multistringText;
							}
						}
					}
				}
			});
			
			
			var ctx = document.getElementById('chart_kantor_asal').getContext('2d');
			var kantorAsal = new Chart(ctx, {
				type: 'pie',
				options: {
					onClick: function(evt, activeElements) {
						var elementIndex = activeElements[0]._index;
						var clickedLabel = this.data.labels[elementIndex];
						
						
						if (asal_selected == "") {
							asal_selected = clickedLabel;
						}else if (clickedLabel == asal_selected){
							asal_selected = "";
						}else{
							asal_selected = clickedLabel;
						}

						update_chart_asal(kpi_selected, asal_selected);
						/*
						$.pos( site_url + 'report/update_chart', function(res){

						},"json");
						
						if( ($('#kpi_value').val() == '') || ($('#kpi_value').val() != this.data.labels[elementIndex]) ){
							var org_color 	 = this.data.datasets[0].backgroundColor[elementIndex];
							this.data.datasets[0].backgroundColor[elementIndex] = 'black';
							$('#kpi_org_color').val(org_color);
							$('#kpi_value').val(this.data.labels[elementIndex]);
							this.update();
						}else{
							this.data.datasets[0].backgroundColor[elementIndex] = $('#kpi_org_color').val();
							$('#kpi_org_color').val('');
							$('#kpi_value').val('');
							this.update();
						}
						*/
					},
					legend: {
						position : 'right',
						labels: { fontColor: 'black', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								var currentLabel = data.labels[tooltipItem[0].index];
								//return currentLabel;
							},
							label: function(tooltipItem, data) {
								
								
								var currentLabel = data.labels[tooltipItem.index]
								//return percentage + " % ";
								return " "+currentLabel+" :";
								//var multistringText = ["Diselesaikan "+currentLabel+" :"];
								//multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
								//return multistringText;
							},
							footer: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem[0].datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem[0].index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

								var multistringText = ["Jumlah : "+currentValue+" "];
								multistringText.push("Persentase : "+percentage+" %");
								return multistringText;
							}
						}
					}
				}
			});


			var ctx = document.getElementById('chart_kantor_tujuan').getContext('2d');
			var kantorTujuan = new Chart(ctx, {
				type: 'pie',
				options: {
					onClick: function(evt, activeElements) {
						var elementIndex = activeElements[0]._index;
						/*
						if( ($('#kpi_value').val() == '') || ($('#kpi_value').val() != this.data.labels[elementIndex]) ){
							var org_color 	 = this.data.datasets[0].backgroundColor[elementIndex];
							this.data.datasets[0].backgroundColor[elementIndex] = 'black';
							$('#kpi_org_color').val(org_color);
							$('#kpi_value').val(this.data.labels[elementIndex]);
							this.update();
						}else{
							this.data.datasets[0].backgroundColor[elementIndex] = $('#kpi_org_color').val();
							$('#kpi_org_color').val('');
							$('#kpi_value').val('');
							this.update();
						}
						*/
					},
					legend: {
						position : 'right',
						labels: { fontColor: 'black', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								var currentLabel = data.labels[tooltipItem[0].index];
								//return currentLabel;
							},
							label: function(tooltipItem, data) {
								
								
								var currentLabel = data.labels[tooltipItem.index]
								//return percentage + " % ";
								return ""+currentLabel+" :";
								//var multistringText = ["Diselesaikan "+currentLabel+" :"];
								//multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
								//return multistringText;
							},
							footer: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem[0].datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem[0].index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

								var multistringText = ["Jumlah : "+currentValue+" "];
								multistringText.push("Persentase : "+percentage+" %");
								return multistringText;
							}
						}
					}
				}
			});


			var ctx = document.getElementById('chart_product').getContext('2d');
			var cProduct = new Chart(ctx, {
				type: 'horizontalBar',
				options: {
					legend: {  display : false },
				}
				//data: [{"x":"Surat Kilat Khusus","y":3},{"x":"POS Express","y":3},{"x":"Paket Biasa Internasional","y":1},{"x":"Express Mail Services","y":1}]
			});
			$.post( site_url + 'report/load_chart', function(data){
				myChart.data = data.report;	
				myChart.update();

				kantorAsal.data = data.asal;	
				kantorAsal.update();

				kantorTujuan.data = data.tujuan;	
				kantorTujuan.update();

				cProduct.data = data.product;
				cProduct.update();
				
			},'json');

			$('.select2min').select2({minimumResultsForSearch:Infinity});
			$('#month').on('select2:select', function (e) {
				var data = e.params.data;
				console.log(data.id);
				reload_chart();
			});

			function reload_chart()
			{
				$.post( site_url + 'report/load_chart',{month:$('#month').val(), year:$('#year').val()}, function(data){
					myChart.data = data.report;	
					myChart.update();

					kantorAsal.data = data.asal;	
					kantorAsal.update();

					kantorTujuan.data = data.tujuan;	
					kantorTujuan.update();

					cProduct.data = data.product;
					cProduct.update();
					
				},'json');
			}
			
		}
		if($('#page-dashboard').length > 0)
		{
			
			var ctx = document.getElementById('chart_service_type').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					}
				}
			});

			var c_jenis_layanan = document.getElementById('chart_jenis_layanan').getContext('2d');
			var myCJenisLayanan = new Chart(c_jenis_layanan, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					}
				}
			});

			var cstatus = document.getElementById('chart_status').getContext('2d');
			var myCStatus = new Chart(cstatus, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							label: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem.datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem.index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
								return percentage + " % ";
							}
						}
					}
				}
			});

			// 
			var pencapaian_out = document.getElementById('chart_service_type_out').getContext('2d');
			var kpi_out = new Chart(pencapaian_out, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					}
				}
			});

			var c_jenis_layanan_out = document.getElementById('chart_jenis_layanan_out').getContext('2d');
			var myCJenisLayanan_out = new Chart(c_jenis_layanan_out, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					}
				}
			});

			var cstatus_out = document.getElementById('chart_status_out').getContext('2d');
			var myCStatus_out = new Chart(cstatus_out, {
				type: 'pie',
				options: {
					legend: {
						position : 'right',
						labels: { fontColor: 'white', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							label: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem.datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem.index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         
								return percentage + " % ";
							}
						}
					}
				}
			});

			$.post( site_url + 'home/getTicketMonthly', function(data){
				myChart.data = data.kiriman;	
				myChart.update();
				
				myCJenisLayanan.data = data.product;
				myCJenisLayanan.update();

				myCStatus.data = data.status;
				myCStatus.update();
				//
				kpi_out.data = data.kiriman_out;	
				kpi_out.update();
				
				myCJenisLayanan_out.data = data.product_out;
				myCJenisLayanan_out.update();

				myCStatus_out.data = data.status_out;
				myCStatus_out.update();

				$('#avg_hour > strong').text(data.avg_time);
				/*
				var ctx = document.getElementById('chart_service_type').getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'pie',
					data: {"datasets":[{"data":[8,1]}],"labels":["domestik","internasional"]}
				});

				*/
				
			},'json');
			
		}

		// Gagal Xray
		if($('#page_form_xray').length > 0)
		{
			$('#myxray-form').validate({
				rules:{
					kantor_penerbangan:{required:true},
					id_kiriman:{required:true},
					isi_kiriman:{required:true},
					keterangan:{required:true}
				},
				submitHandler: function(form) {
					$.post( site_url + 'xray/save_data', $('#myxray-form').serialize(), function(res){
						if(res){
							swal({
								title: "Success!",
								text: res.message,
								content:"success",
								//timer: 2000,
								buttons: {
									cancel: "Back to list",
									addnew: "Add More"
								},
								closeOnEsc: false,
								closeOnClickOutside: false
							}).then((value) => {
								switch (value) {
									case "addnew":
									  window.location.reload();
									  break;

									default:
										window.location.href = site_url + 'xray';
								}
							});
						}else{
							swal("Sorry",res.message, "error");
						}
					},"json");
				}
			});
			
			$('.input-search-kantorpos').select2({
				ajax: {
					url: site_url + 'app/kantor_posdd_new',
					dataType: 'json',
					method:"POST",
					delay: 250,
					data: function (params) {
						var query = {
							city: params.term
						}
						return query;
					},
					processResults: function (data) {
						return {
							results : data
						}
					},
					cache: true
				},
				placeholder: 'Kantor Pos',
				minimumInputLength: 1,
			})
		}
		if($('#page-dg-xray').length > 0)
		{
			var interval = '';
			$(window).on('resize', function(){
				$('#dgContact').datagrid('resize');
			})

			$('#dgContact').datagrid({
				url: site_url + 'xray/get_data_list',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				//toolbar:"#toolbar",
				queryParams: {
				},
				columns:[[
					
					{field:'kode_kantor_aduan',title:'kantor Penerbangan',width:45,
						formatter:function(v,r,i){
							return r.kantor_aduan;
						}
					},
					{field:'tgl_input_id',title:'Tgl Input', width:20, sortable:true, align:'left'},
					{field:'kode_kantor_asal',title:'Kantor Asal',width:45,
						formatter:function(v,r,i){
							return r.kantor_asal;
						}
					},
					{field:'kode_kantor_tujuan',title:'Kantor Tujuan',width:45,
						formatter:function(v,r,i){
							return r.kantor_tujuan;
						}
					},
					{field:'id_kiriman',title:'ID Kiriman',width:25, sortable:true},
					{field:'kantong_lama',title:'Ktng Lama',width:25, sortable:true},
					{field:'kantong_baru',title:'ktng Baru',width:25, sortable:true},
					
					{field:'isi_kiriman',title:'Isi Kiriman',width:30, sortable:true},
					{field:'berat',title:'Berat (Kg)',width:20, sortable:true, align:'right'},
					{field:'keterangan',title:'Keterangan',width:30, sortable:true},
					//{field:'user_cch',title:'User Input',width:10, sortable:true}
				]],
				/*
				onSelect: function(i, r){
					var count = $("#grid-body").children().length;
					console.log(count);
					if(count > 1){
						//$('#grid-body div:last').remove();
						$('#grid-body > div.info-side-index').remove();
					}
					$('#grid-body > div:first').removeClass('col-md-12').addClass('col-md-8');
					
					var html_loader = '<div class="block-loader" id="loader-element" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;"><div class="lds-ripple"><div></div><div></div></div></div>';
					
					$('#grid-body').append(html_loader);
					$.post( site_url + 'ccare/load_detail_info',{ uid: r.id}, function(res){
						$('#grid-body #loader-element').remove();
						$('#grid-body').append('<div class="col-md-4 info-side-index bg-info" style="height:'+dg_height+'px; margin-left:-11px;">'+res+'</div>');
					});
					$('#dgContact').datagrid('resize');
                }
				*/
			})
			
			$('#btn-search').on('click',function(){
				$('#dgContact').datagrid('reload',{search:$('#key-search').val()});
			});
			$('#key-search').on('keyup', function(){
				if($(this).val() ==''){
					$('#dgContact').datagrid('reload', {search:$('#key-search').val()});
				}
			});
			
			$('#fileupload').fileupload({
                url: site_url + 'xray/do_upload',
                dataType: 'json',
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        $('<p/>').text(file.name).appendTo('#files');
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css('width', progress + '%');
                    $('#progress #percentValue').text(progress + '% Complete');
                }
            });
            $('#fileupload').bind('fileuploadsend', function (e, data) {
                //alert("X");
                $('#modal_progress').modal({
                  keyboard: false,
                  backdrop : 'static',
                  show : true
                });
                //Cookies.set('sap-upload',0);
            }).bind('fileuploaddone', function (e, data) {
                
                if(data.result[0].status == 'OK'){
                    
                    updateProgress(interval);
                    $.post( site_url + 'xray/do_read_file',{file : data.result[0].server_path}, function(data){
						if(data.status == 'OK'){
                            console.log("Read excel success");
							
							$('#modal_progress').modal('hide');
							
							$('#dgContact').datagrid('reload');
                            //Cookies.remove('sap_upload');
                        }
                    },"json");
                    
                }else{
                    
                }
                //console.log(data.result[0].status);
                
            })
			
			
			function updateProgress(interval){
		//var percentUpload   = Cookies.get('sap-upload');
		//var interval = null;
		interval = setInterval( function() 
		{
			
			$.getJSON(base_url + 'progress.json',function(data){
				if(data.percentComplete == 1){
					clearInterval(interval);
					$('#modal_progress').modal('hide');
				}
				var progress = (data.percentComplete * 100).toFixed(1);
				
				$('#progress-excel .progress-bar').css('width', progress + '%');
				$('#progress-excel #percentValue-excel').text(progress + '% Complete');
				$('#readExcel').text('Read excel rows ' + (data.percentComplete*100).toFixed(2) + ' complete');
				//console.log(Cookies.get('pal_sap_upload'));
				
				//clearInterval(interval);
			});
		}, 1000);
	}
	
		}


		//
		if($('#page-dg-feeder-mp').length > 0)
		{
			var interval = '';
			$(window).on('resize', function(){
				$('#dgContact').datagrid('resize');
			})

			$('#dgContact').datagrid({
				url: site_url + 'feeder/get_data_list',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				//toolbar:"#toolbar",
				queryParams: {
				},
				columns:[[
					
					{field:'tgl_upload',title:'Tgl Upload',width:20},
					{field:'marketplace',title:'Nama MP', width:20, sortable:true, align:'left'},
					{field:'no_pesanan',title:'No Pesanan',width:45},
					{field:'awb',title:'No Barcode / AWB',width:45},
					{field:'no_ticket',title:'No Tiket',width:45},
					{field:'status',title:'Informasi',width:45,
						formatter:function(v,r,i){
							if(r.isi_kiriman != null){
								return 'GAGAL XRAY '+r.isi_kiriman;
							}
						}
					},
					{field:'auto_id',title:'Aksi',width:45,
						formatter:function(v,r,i){
							if(r.no_ticket != null){
								return "RESPON";
							}else{
								return "<a href='javascript:void(0);' onclick='createTicket("+JSON.stringify(r)+");'> Buat Tiket </a>";
							}
							
						}
					},

					
				]],
				/*
				onSelect: function(i, r){
					var count = $("#grid-body").children().length;
					console.log(count);
					if(count > 1){
						//$('#grid-body div:last').remove();
						$('#grid-body > div.info-side-index').remove();
					}
					$('#grid-body > div:first').removeClass('col-md-12').addClass('col-md-8');
					
					var html_loader = '<div class="block-loader" id="loader-element" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;"><div class="lds-ripple"><div></div><div></div></div></div>';
					
					$('#grid-body').append(html_loader);
					$.post( site_url + 'ccare/load_detail_info',{ uid: r.id}, function(res){
						$('#grid-body #loader-element').remove();
						$('#grid-body').append('<div class="col-md-4 info-side-index bg-info" style="height:'+dg_height+'px; margin-left:-11px;">'+res+'</div>');
					});
					$('#dgContact').datagrid('resize');
                }
				*/
			})

			
			
			$('#btn-search').on('click',function(){
				$('#dgContact').datagrid('reload',{search:$('#key-search').val()});
			});
			$('#key-search').on('keyup', function(){
				if($(this).val() ==''){
					$('#dgContact').datagrid('reload', {search:$('#key-search').val()});
				}
			});
			
			$('#fileupload').fileupload({
                url: site_url + 'feeder/do_upload',
                dataType: 'json',
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        $('<p/>').text(file.name).appendTo('#files');
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css('width', progress + '%');
                    $('#progress #percentValue').text(progress + '% Complete');
                }
            });
            $('#fileupload').bind('fileuploadsend', function (e, data) {
                //alert("X");
                $('#modal_progress').modal({
                  keyboard: false,
                  backdrop : 'static',
                  show : true
                });
                //Cookies.set('sap-upload',0);
            }).bind('fileuploaddone', function (e, data) {
                
                if(data.result[0].status == 'OK'){
                    
                    updateProgress(interval);
                    $.post( site_url + 'feeder/do_read_file',{file : data.result[0].server_path}, function(data){
						if(data.status){
                            $('#modal_progress').modal('hide');
							$('#dgContact').datagrid('reload');
                        }
                    },"json");
                    
                }else{
                    
                }
                //console.log(data.result[0].status);
                
            })
			
			
			function updateProgress(interval){
		//var percentUpload   = Cookies.get('sap-upload');
		//var interval = null;
		interval = setInterval( function() 
		{
			
			$.getJSON(base_url + 'progress.json',function(data){
				if(data.percentComplete == 1){
					clearInterval(interval);
					$('#modal_progress').modal('hide');
				}
				var progress = (data.percentComplete * 100).toFixed(1);
				
				$('#progress-excel .progress-bar').css('width', progress + '%');
				$('#progress-excel #percentValue-excel').text(progress + '% Complete');
				$('#readExcel').text('Read excel rows ' + (data.percentComplete*100).toFixed(2) + ' complete');
				//console.log(Cookies.get('pal_sap_upload'));
				
				//clearInterval(interval);
			});
		}, 1000);
	}
	
		}


		if($('#page-dg-kprk').length > 0)
		{
			$(window).on('resize', function(){
				$('#dgContact').datagrid('resize');
			});

			$.post ( site_url + 'kprk/load_grid_data', function(res){
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#btn-search').on('click', function(){
				$('#dgContact').datagrid('reload',{search:$('#key').val(), start:$('#start').val(), end:$('#end').val()});
			});

			$('#dgContact').datagrid({
				url: site_url + 'kprk/get_data_list',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				//toolbar:"#toolbar",
				queryParams: {
					start:$('#start').val(),
					end:$('#end').val()
				},
				frozenColumns:[[
					
				]],
				columns:[[
					{field:'code',title:'No Dirian', width:10, sortable:true, align:'left'},
					{field:'name',title:'Nama Kantor Pos', width:40, sortable:true, align:'left'},
					{field:'regional',title:'Regional', width:30, sortable:true, align:'left'},
					{field:'total_ticket_keluar',title:'Tiket Keluar',width:10, sortable:true, align:'left'},
					{field:'total_ticket_masuk',title:'Tiket Masuk',width:10, sortable:true, align:'left'},
					
				]],
				
				onSelect: function(i, r){
					var count = $("#grid-body").children().length;
					if(count > 1){
						$('#grid-body > div.info-side-index').remove();
					}
					$('#grid-body > div:first').removeClass('col-md-12').addClass('col-md-8');
					
					var html_loader = '<div class="block-loader" id="loader-element" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;"><div class="lds-ripple"><div></div><div></div></div></div>';
					
					$('#grid-body').append(html_loader);
					$.post( site_url + 'kprk/load_detail_info',{ uid: r.code, start:$('#start').val(), end:$('#end').val()}, function(res){
						$('#grid-body #loader-element').remove();
						$('#grid-body').append('<div class="col-md-4 info-side-index bg-info" style="overflow:auto; height:'+dg_height+'px; margin-left:-11px;">'+res+'</div>');
					});
					$('#dgContact').datagrid('resize');
                }
			})
		}
		

		//ticket 
		if($('#page-ticket').length > 0)
		{
			$(window).on('resize', function(){
				$('#dgTicket').datagrid('resize');
			})
			
			$('ul.sections > li').removeClass('active');
			$('.sections > li > a[href="' + page + '"]').parent().addClass('active');
			
			$('ul.sections > li').on('click', function(){
				$('ul.sections > li').removeClass('active');
				$(this).addClass('active');
				page = $(this).children("a").attr('href');
				
				$('#dgTicket').datagrid('reload',{search:$('#search').val(), view:page});
			});
	
			var page = $(location).attr('hash');
			$('#dgTicket').datagrid({
				url: site_url + 'ticket/datalist',
				//title:'Data Ticket',
				height: dg_mail,
				nowrap: true,
				striped: true,
				remoteSort: true,
				singleSelect: false,
				fitColumns: true,
				pagination:true,
				checkbox:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					start: $('#start').val(),
					end: $('#end').val(),
					view:page
				},
				onCheck: function(node,checked){
					getTotalCheckbox();
				},
				onCheckAll: function(){
					getTotalCheckbox();
				},
				onUncheckAll:function(){
					getTotalCheckbox();
				},
				onUncheck:function(node, unchecked){
					getTotalCheckbox();
				},
				columns:[[
					{field:'ck', checkbox:true},
					{field:'no_ticket',title:'No Ticket', width:100, sortable:false, align:'center',
						formatter:function(v,r,i){
							return '<a href="'+site_url+'ticket/d/'+r.id+'" class="text-danger">'+v+'</a>';
						}
					},
					{field:'contact_name',title:'Contact',width:100, sortable:true,
						formatter:function(v,r,i){
							//<a href="'+site_url+'contacts/d/'+data[8]+'"
							return '<a href="'+site_url+'contacts/d/'+r.cid+'" class="text-danger">'+v+'</a>';
						}
					},
					{field:'subject',title:'Subject',width:100, sortable:true},
					{field:'category_name',title:'Category',width:100, sortable:true, align:'center'},
					/*
					{field:'priority',title:'Priority',width:100, sortable:true, align:'center',
						formatter:function(v,r,i){
								if(v == 1){
									return "Urgent";
								}else if(v == 2){
									return "High";
								}else if(v == 3){
									return "Medium";
								}else if(v == 4){
									return "Low";
								}else if(v == 5){
									return "-";
								}
							}
					},
					*/
					{field:'date',title:'Date',width:100, sortable:true, align:'center'},
					{field:'status',title:'Status',width:100, sortable:true, align:'center',
						formatter:function(v,r,i){
							var vclass;
							
							/*
							if(v == 1){
								vclass = 'primary';
							}else{
								vclass = 'info';
							}
							*/
							if(v == 1){
								vclass = 'warning';
							}else if(v == 12){
								vclass = 'info';
							}else if(v == 17){
								vclass = 'primary';
							}else if(v == 99){
								vclass = 'success';
							}
							return '<span class="label label-ticket label-'+vclass+'">'+r.status_name+'</span>';
						}
					}
				]]
			});
		}

		// Response 
		if( $('#page-response').length > 0)
		{
			var ticket = getUri(site_url, 3);
			$.post( site_url + 'app/get_ticket_history', {tid:ticket}, function(res){
				//console.log(res);
				//copyToClipboard('#toCopy');
				$('#toCopy').html(res);
			});

			$('#fileupload').fileupload({
                url: site_url + 'app/do_upload_response_tmp',
				dataType: 'json',
				formData:{ ticket_id : getUri(site_url, 3)},
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        $('<p/>').text(file.name).appendTo('#files');
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css('width', progress + '%');
                    $('#progress #percentValue').text(progress + '% Complete');
                }
            });
            $('#fileupload').bind('fileuploadsend', function (e, data) {
                /*
				$('#modal_progress').modal({
                  keyboard: false,
                  backdrop : 'static',
                  show : true
                });
				*/
                //Cookies.set('sap-upload',0);
            }).bind('fileuploaddone', function (e, data) {
                console.log(data);
                if(data.result.status){
                    
                    load_media_response();
                    
                }else{
                    swal("Sorry",data.msg, "error");
                }
                //console.log(data.result[0].status);
                
			})
	
			if (! $('html').hasClass('ie8')) {
				$('#response').summernote({
					toolbar: [
						// [groupName, [list of button]]
						['style', ['bold', 'italic', 'underline', 'clear']],
						//['font', ['strikethrough', 'superscript', 'subscript']],
						['fontsize', ['fontsize']],
						['color', ['color']],
						['para', ['ul', 'ol', 'paragraph']],
						//['height', ['height']]
					],
					height: 200,
					tabsize: 2,
					codemirror: {
						theme: 'monokai'
					},
					onImageUpload: function(files, editor, welEditable) {
						sendFile(files[0], editor, welEditable);
					},
					callbacks: {
						onChange: function (contents, $editable) {
							//console.log(contents);
							// Note that at this point, the value of the `textarea` is not the same as the one
							// you entered into the summernote editor, so you have to set it yourself to make
							// the validation consistent and in sync with the value.
							$('#response_val').val($('#response').summernote('isEmpty') ? "" : contents);
							
							if($('#response').summernote('isEmpty')){
								$('#btn-process').attr('disabled','disabled');
							}else{
								$('#btn-process').removeAttr('disabled');
							}
							//console.log("===>"+$('#response').val());
							// You should re-validate your element after change, because the plugin will have
							// no way to know that the value of your `textarea` has been changed if the change
							// was done programmatically.
						}
					}
				});
			}
			
			/*
			$.post ( site_url + 'app/ticketDetail', { cid : getUri(site_url, 3)}, function(res){
				if(res.assignee_val != ''){
					var html = '<option value="'+res.assignee_val+'">'+res.assignee_text+'</option>';
					$('#assignee').html(html);
				}
			},"json");
			*/
			
			$.post( site_url + 'ticket/form_response', { cid : getUri(site_url, 3)}, function(res){
				
				var assignee_html = "";
				if(res.data.assignee_val != ''){
					
					var opt 	= res.data.assignee_val.split(',');
					
					for(var i = 0; i<opt.length; i++){
						var s    = opt[i].split('|');
						var text_show		= opt[i].replace("|", " - ");
						//assignee_html		+= '<option selected="selected" value="'+s[0]+'">'+s[1]+'</option>';
						assignee_html		+= '<option selected="selected" value="'+opt[i]+'">'+text_show+'</option>';
					}		
					//var html = '<option value="'+res.data.assignee_val+'">'+res.data.assignee_text+'</option>';
					$('#assignee').html(assignee_html);
				}
				
				$('#status').select2({data:res.ticket_status, minimumResultsForSearch:Infinity}).val("").trigger('change');
				/*
				$('#status').on('select2:select', function (e) {
					var data = e.params.data;
					if(data.id <= res.data.status){
						$('#btn-process').attr('disabled','disabled');
					}else{
						$('#btn-process').removeAttr('disabled');
					}
				});
				*/
				$('#response-sroll-id').css({"height":dg_mail_sc+'px',"overflow":"auto"});
				$('#assignee').prop("disabled", true);
				//console.log(res.data.user_type);
				if(res.data.user_type == 'origin'){
					//$('#assignee').prop("disabled", false);
					
					$("#request option[value='0']").remove();
					$("#request option[value='1']").remove();
					
				}else{
					//$('#assignee').prop("disabled", true);
					$("#request option[value='2']").remove();
					$("#request option[value='3']").remove();
				}
				
				$('.select2min').select2({minimumResultsForSearch:Infinity});
				
				$('#request').on('select2:select', function (e) {
					var data = e.params.data;
					console.log(data.id);
					if(data.id == 3){
						//$('.statusSelect').removeClass('col-md-12').addClass('col-md-4');
						$('.selesaiOnly').removeClass('hidden');
						
					}else{
						//$('.statusSelect').addClass('col-md-12').removeClass('col-md-4');
						$('.selesaiOnly').addClass('hidden');
					}
				});
				//$('#no_resi').val(res.awb);
				//$('#asal_kiriman').val(res.sender);
				//$('#tujuan_kiriman').val(res.receiver);
			},"json");

			$('#assignee').select2({
				ajax: {
					url: site_url + 'app/kantor_posdd_new',
					dataType: 'json',
					method:"POST",
					delay: 250,
					processResults: function (data) {
						return {
							results : data
						}
					},
					cache: true
				},
				placeholder: 'Assignee',
				minimumInputLength: 1,
				tags: true,
				allowClear: false,
				multiple:true,
				escapeMarkup: function (markup) { return markup; },
			})
			
			
			
			$('#tags').select2({
				tags:true,
				multiple:true,
				//placeholder:"Tags",
				tokenSeparators: [',', ' ']
			})
			$('#priority').select2();
			
			var response_form = $("#ticket-form");
			response_form.validate({
				ignore:"",
				rules:{
					response_val:{required:true}
				},
				message:{
					response_val:{required:"Harus diisi"},
					response:{required:"Harus diisi"}
				},
				focusInvalid: true, 
				//errorPlacement: function () {},
				submitHandler: function(form) {
					
					//var $btn = $('#btn-submit').button('loading').attr('disabled','disabled');
					$('#btn-process').addClass('running').attr('disabled','disabled');
					$.post( site_url + 'ticket/saveResponses',$("#ticket-form").serialize(),function(data){
						
						if(data.status){
							
							swal({
								title: "Berhasil,",
								text: data.message,
								content:"success",
								//timer: 2000,
								buttons: {
									cancel: "Kembali ke Data Tiket",
									addnew: "Muat Ulang"
								},
								closeOnEsc: false,
								closeOnClickOutside: false
							}).then((value) => {
								switch (value) {
									case "addnew":
									  window.location.reload();
									  break;
									default:
										window.location.href = site_url + 'ticket';
								}
							});
							
							//$('#btn-process').removeClass('running').removeAttr('disabled');
						}else{
							//$.growl.error({ message: data.msg });
							swal("Sorry",data.message, "error");
						}
						$('#btn-process').removeClass('running').removeAttr('disabled');
					},'json').fail(function(jqXHR, textStatus, errorThrown) {
						$('#btn-process').removeClass('running').removeAttr('disabled');
						if(jqXHR.status == 500){
							swal("Sorry",res.message, "error");
						}else if(textStatus == 'parseerror'){
							//alert('Parse error');
							swal("Sorry","Error Parsing", "error");
							//howError();
						}
						
					});
					
				}
			});
			
		}
		
		// Report 
		if($('#page-rep-ticket').length > 0)
		{
			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/getTicketData',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'no_ticket',title:'No Ticket', width:100, sortable:false, align:'center',
						formatter:function(v,r,i){
							return '<a href="'+site_url+'ticket/d/'+r.id+'" class="text-danger">'+v+'</a>';
						}
					},
					{field:'contact_name',title:'Nama Pengadu',width:100, sortable:true,
						formatter:function(v,r,i){
							//<a href="'+site_url+'contacts/d/'+data[8]+'"
							return '<a href="'+site_url+'contacts/d/'+r.cid+'" class="text-danger">'+v+'</a>';
						}
					},
					{field:'complaint_origin',title:'Asal Pengaduan',width:100, sortable:true},
					{field:'kantor_tujuan_name',title:'Tujuan Pengaduan',width:100, sortable:true},
					{field:'sender',title:'Asal Kiriman',width:100, sortable:true},
					{field:'receiver',title:'Tujuan Kiriman',width:100, sortable:true},
					{field:'product',title:'Layanan',width:100, sortable:true},
					//{field:'category_name',title:'Category',width:100, sortable:true, align:'center'},
					{field:'date',title:'Date',width:100, sortable:true, align:'center'},
					{field:'duration_id',title:'Duration(hour)',width:100, sortable:true, align:'center'},
					{field:'awb',title:'No Barcode / AWB',width:100, sortable:true, align:'center',
						styler: function(v,row,index){
							if(row.status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
				]]
			})
		}
		

		if($('#page-rep-ticket-incoming').length > 0)
		{
			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_incoming_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:true,
				pagination:false,
				rownumbers:true,
				//pageSize:25,
				//pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'regional',title:'Regional', width:35, sortable:false, align:'left', rowspan:2,
						formatter:function(v,r,i){
							if(!r.isFooter)
							{
								return '<a href="'+site_url+'report/incoming_regional/'+r.id+'" class="text-danger">'+r.city+' ( '+v+' )</a>';
							}else{
								return v;
							}
							
						}
					},
					{field:'total_ticket',title:'Jumlah Pengaduan',width:10, sortable:true, rowspan:2, align:'center',
						formatter:function(v,r,i){
							return v;
							//<a href="'+site_url+'contacts/d/'+data[8]+'"
							//return '<a href="'+site_url+'contacts/d/'+r.cid+'" class="text-danger">'+v+'</a>';
						}
					},
					{title:'Selesai',width:10, sortable:true, align:'center', colspan:2},
					{title:'Terbuka',width:10, sortable:true, align:'center', colspan:2},
				],[
					{field:'total_selesai',title:'Jumlah',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
							
						}
					},
					{field:'percent_selesai',title:'%',width:10, sortable:true, align:'center'},
					{field:'total_terbuka',title:'Jumlah',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{field:'percent_terbuka',title:'%',width:10, sortable:true, align:'center'},
				]]
			})
		}

		if($('#page-rep-ticket-incoming-regional').length > 0)
		{
			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_incoming_regional_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:true,
				pagination:false,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					regid:getUri(site_url, 3),
					status:getUri(site_url, 4),
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'fullname',title:'KPRK', width:35, sortable:false, align:'left', rowspan:2,
						formatter:function(v,r,i){
							return '<a href="'+site_url+'report/incoming_kprk/'+r.code+'" class="text-danger">'+v+'</a>';
							//return v;
						}
					},
					{field:'total_ticket',title:'Jumlah Pengaduan',width:15, sortable:true, rowspan:2, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{title:'Selesai',width:10, sortable:true, align:'center', colspan:4},
					{title:'Terbuka',width:10, sortable:true, align:'center', colspan:4},
				],[
					{field:'total_selesai',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_selesai',title:'%',width:10, sortable:true, align:'center'},
					{field:'selesai_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
						}
					},
					{field:'selesai_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
					{field:'total_terbuka',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_terbuka',title:'%',width:10, sortable:true, align:'center'},
					{field:'terbuka_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
							
						}
					},
					{field:'terbuka_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
				]]
			})
		}

		if($('#page-rep-ticket-incoming-kprk').length > 0)
		{
			$('#status').select2({minimumResultsForSearch:Infinity});
			$('#status').on('select2:select', function (e) {
				var data = e.params.data;
				
				$('#dgTicket').datagrid('reload', {kprk:getUri(site_url, 3), start:$('#start').val(), end:$('#end').val(), filter:$('#status').val()})
			});
			$.post ( site_url + 'report/load_grid_data', function(res){
				//$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_incoming_kprk_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:false,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					kprk:getUri(site_url, 3),
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'no_ticket',title:'No Tiket', width:35, sortable:false, align:'left',
						formatter:function(v,r,i){
							return '<a href="'+site_url+'ticket/d/'+r.id+'" class="text-danger">'+v+'</a>';
							//return v;
						}
					},
					{field:'product_name',title:'Produk',width:15, sortable:true, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{field:'awb',title:'No Barcode / AWB',width:15, sortable:false, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{field:'status_text',title:'Status Akhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'office_name',title:'Update Terakhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'last_response',title:'Tgl Akhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'duration',title:'Durasi',width:15, sortable:false, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					}
				]]
			})
		}

		// outgoing
		if($('#page-rep-ticket-outgoing').length > 0)
		{
			$.post ( site_url + 'report/load_grid_data', function(res){
				//$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_outgoing_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				pagination:false,
				rownumbers:true,
				showFooter:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'regional',title:'Regional', width:35, sortable:false, align:'left', rowspan:2,
						formatter:function(v,r,i){
							if( !r.isFooter){
								return '<a href="'+site_url+'report/outgoing_regional/'+r.id+'" class="text-danger">'+r.city+' ( '+v+' )</a>';
							}else{
								return v;
							}
						}
					},
					{field:'total_ticket',title:'Jumlah Pengaduan',width:10, sortable:true, rowspan:2,
						formatter:function(v,r,i){
							return v;
						}
					},
					{title:'Selesai',width:10, sortable:true, align:'center', colspan:2},
					{title:'Terbuka',width:10, sortable:true, align:'center', colspan:2},
				],[
					{field:'total_selesai',title:'Jumlah',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
							
						}
					},
					{field:'percent_selesai',title:'%',width:10, sortable:true, align:'center'},
					{field:'total_terbuka',title:'Jumlah',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
						}
					},
					{field:'percent_terbuka',title:'%',width:10, sortable:true, align:'center'},
					
				]]
			})
		}

		if($('#page-rep-ticket-outgoing-regional').length > 0)
		{
			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_outgoing_regional_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:true,
				pagination:false,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					regid:getUri(site_url, 3),
					status:getUri(site_url, 4),
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'fullname',title:'KPRK', width:35, sortable:false, align:'left', rowspan:2,
						formatter:function(v,r,i){
							return '<a href="'+site_url+'report/outgoing_kprk/'+r.code+'" class="text-danger">'+v+'</a>';
							//return v;
						}
					},
					{field:'total_ticket',title:'Jumlah Pengaduan',width:15, sortable:true, rowspan:2, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{title:'Selesai',width:10, sortable:true, align:'center', colspan:4},
					{title:'Terbuka',width:10, sortable:true, align:'center', colspan:4},
				],[
					{field:'total_selesai',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_selesai',title:'%',width:10, sortable:true, align:'center'},
					{field:'selesai_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
						}
					},
					{field:'selesai_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
					{field:'total_terbuka',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_terbuka',title:'%',width:10, sortable:true, align:'center'},
					{field:'terbuka_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							
								return v;
							
							
						}
					},
					{field:'terbuka_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
				]]
			})
		}

		if($('#page-rep-ticket-outgoing-kprk').length > 0)
		{
			$('#status').select2({minimumResultsForSearch:Infinity});
			$('#status').on('select2:select', function (e) {
				var data = e.params.data;
				
				$('#dgTicket').datagrid('reload', {kprk:getUri(site_url, 3), start:$('#start').val(), end:$('#end').val(), filter:$('#status').val()})
			});
			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_outgoing_kprk_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:false,
				pagination:true,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					kprk:getUri(site_url, 3),
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'no_ticket',title:'No Tiket', width:35, sortable:false, align:'left',
						formatter:function(v,r,i){
							return '<a href="'+site_url+'ticket/d/'+r.id+'" class="text-danger">'+v+'</a>';
							//return v;
						}
					},
					{field:'product_name',title:'Produk',width:15, sortable:true, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{field:'awb',title:'No Barcode / AWB',width:15, sortable:false, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					},
					{field:'status_text',title:'Status Akhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'office_name',title:'Update Terakhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'last_response',title:'Tgl Akhir',width:15, sortable:false, align:'center',
						styler: function(v,row,index){
							
							if(row.last_status == 1){
								return 'background-color:#f4bb04;color:#FFF;';
							}else if(row.last_status == 12){
								return 'background-color:#138496;color:#FFF;';
							}else if(row.last_status == 17){
								return 'background-color:#1d89cf;color:#FFF;';
							}else if(row.last_status == 99){
								return 'background-color:#218838;color:#FFF;';
							}
						}
					},
					{field:'duration',title:'Durasi',width:15, sortable:false, align:'center',
						formatter:function(v,r,i){
							return v;
						}
					}
				]]
			})
		}

		if($('#page-rep-ticket-product').length > 0)
		{
			
			$('#btn-search').on('click', function(){
				$('#dgTicket').datagrid('reload',{search:$('#key').val(), start:$('#start').val(), end:$('#end').val()});
			});

			$.post ( site_url + 'report/load_grid_data', function(res){
				$('#status').select2({minimumResultsForSearch:Infinity, data:res.status});
				$('#start').val(res.date_start);
				$('#end').val(res.date_end);
			},"json");


			var ctx_all_product = document.getElementById('chart_product').getContext('2d');
			var	all_product = new Chart(ctx_all_product, {
				type: 'pie',
				/*
				type:'outlabeledPie',
				plugins: {
					display:false,
					legend: false,
					outlabels: {
						text: '%l %p',
						color: 'white',
						stretch: 1,
						font: {
							resizable: true,
							minSize: 12,
							maxSize: 18
						}
					}
				},
				*/
				options: {
					onClick: function(evt, activeElements) {
						var elementIndex = activeElements[0]._index;
						
					},
					title : {
						display: true,
						position : 'top',
						text : 'Pengaduan by Produk'
					},
					legend: {
						display:false,
						position : 'bottom',
						labels: { fontColor: 'black', boxWidth:20},			
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								var currentLabel = data.labels[tooltipItem[0].index];
								//return currentLabel;
							},
							label: function(tooltipItem, data) {
								
								
								var currentLabel = data.labels[tooltipItem.index]
								//return percentage + " % ";
								return ""+currentLabel+" :";
								//var multistringText = ["Diselesaikan "+currentLabel+" :"];
								//multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
								//return multistringText;
							},
							footer: function(tooltipItem, data) {
								var dataset = data.datasets[tooltipItem[0].datasetIndex];
								var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
									return previousValue + currentValue;
								});
								var currentValue = dataset.data[tooltipItem[0].index];
								var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

								var multistringText = ["Jumlah : "+currentValue+" "];
								multistringText.push("Persentase : "+percentage+" %");
								return multistringText;
							}
						}
					}
				}
			});

			$.post( site_url + 'report/get_product_ticket', {start:$('#start').val(), end:$('end').val()}, function(res){
				all_product.data = res.chart_product;
    			all_product.update();
			},"json");

			$('#dgTicket').datagrid({
				url: site_url + 'report/get_product_ticket',
				//title:'Data Ticket',
				height: dg_height,
				nowrap: false,
				striped: true,
				remoteSort: true,
				singleSelect: true,
				fitColumns: true,
				showFooter:true,
				pagination:false,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				toolbar:"#toolbar",
				queryParams: {
					regid:getUri(site_url, 3),
					status:getUri(site_url, 4),
					start: $('#start').val(),
					end: $('#end').val()
				},
				columns:[[
					{field:'name',title:'Nama Produk', width:35, sortable:false, align:'left',
						formatter:function(v,r,i){
							//return '<a href="'+site_url+'report/outgoing_kprk/'+r.code+'" class="text-danger">'+v+'</a>';
							return v;
						}
					},
					{field:'total_ticket',title:'Jumlah Pengaduan',width:15, sortable:true, align:'center',
						formatter:function(v,r,i){
							return v;
							//<a href="'+site_url+'contacts/d/'+data[8]+'"
							//return '<a href="'+site_url+'contacts/d/'+r.cid+'" class="text-danger">'+v+'</a>';
						}
					},
					//{title:'Selesai',width:10, sortable:true, align:'center', colspan:4},
					//{title:'Terbuka',width:10, sortable:true, align:'center', colspan:4},
					/*
					{field:'category_name',title:'Category',width:10, sortable:true, align:'center'},
					{field:'date',title:'Date',width:10, sortable:true, align:'center'},
					{field:'duration_id',title:'Duration(hour)',width:10, sortable:true, align:'center'},
					{field:'status',title:'Status',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							var vclass;
							if(v == 1){
								vclass = 'warning';
							}else if(v == 12){
								vclass = 'info';
							}else if(v == 17){
								vclass = 'primary';
							}else if(v == 99){
								vclass = 'success';
							}
							return '<span class="label label-ticket label-'+vclass+'">'+r.status_name+'</span>';
						}
					}
					*/
				]
				/*,[
					{field:'total_selesai',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_selesai',title:'%',width:10, sortable:true, align:'center'},
					{field:'selesai_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							if(v > 0){
								return '<a href="'+site_url+'report/incoming_detail/'+r.id+'/A" class="text-danger">'+v+'</a>';
							}else{
								return v;
							}
						}
					},
					{field:'selesai_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
					{field:'total_terbuka',title:'Jumlah',width:10, sortable:true, align:'center'},
					{field:'percent_terbuka',title:'%',width:10, sortable:true, align:'center'},
					{field:'terbuka_a',title:'<= 24 Jam',width:10, sortable:true, align:'center',
						formatter:function(v,r,i){
							if(v > 0){
								return '<a href="'+site_url+'report/incoming_regional/'+r.id+'/selesai" class="text-danger">'+v+'</a>';
							}else{
								return v;
							}
							
						}
					},
					{field:'terbuka_b',title:'> 24 Jam',width:10, sortable:true, align:'center'},
					
				]*/],
				onSelect: function(i, r){
					var count = $("#grid-body").children().length;
					if(count > 1){
						$('#grid-body > div.info-side-index').remove();
					}
					$('#grid-body > div:first').removeClass('col-md-12').addClass('col-md-8');
					
					var html_loader = '<div class="block-loader" id="loader-element" style="text-align: center; vertical-align: middle; height: 200px; margin-top: 25%; margin-bottom: auto;"><div class="lds-ripple"><div></div><div></div></div></div>';
					
					$('#grid-body').append(html_loader);
					$.post( site_url + 'report/load_detail_info_product',{ uid: r.code, start:$('#start').val(), end:$('#end').val()}, function(res){
						$('#grid-body #loader-element').remove();
						$('#grid-body').append('<div class="col-md-4 info-side-index bg-info" style="overflow:auto; height:'+dg_height+'px; margin-left:-11px;">'+res+'</div>');
					});
					$('#dgTicket').datagrid('resize');
                }
			})
		}
		/*
		socket.on('ticketNew', function(result){
			$.growl({ title: "New Ticket", message: "Ticket No : #"+result, size: 'large' });
		});
		socket.on('ticketResponse', function(result){
			$.growl({ title: "Ticket Response", message: "Ticket No : #"+result, size: 'large' });
		});
		*/

		
	})

	
	

	function openFile(id){
		$.post( site_url + 'app/download_media', {mid : id}, function(res){
			if(res.status){
				SaveToDisk(res.path, res.name);
			}else{
				swal("Kesalahan",res.message, "error");
			}
		},"json");
	}
	
	function openPage(url){
		window.location.href = site_url + url;
	}

	function load_media_response(ticket_id){
		
		$.post( site_url + 'app/load_media_response_uploaded', {ticket_id:$('#ticket_id').val()}, function(res){
			var lfile = '';
			lfile += '<ul class="list-unstyled">';
			$.each(res, function( key, value ) {
				lfile += '<li><a href="javascript:void(0);" onclick="javascript:openFile(\''+value.id+'\')"><i class="fa '+value.icon+'"></i> '+value.file_name+'</a> <a href="javascript:void(0);" onclick="javascript:removeFile(\''+value.id+'\');"><i class="fa fa-times" title="Hapus"></i></a></li>';
			});
			lfile += '</ul>';
			$('#list-media').html(lfile);
		},"json");
	}

	function removeFile(id){
		swal({
			title: "Yakin anda akan menghapus dokumen ini ?",
			text: "Sekali anda menghapus maka tidak akan bisa dikembalikan lagi",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				$.post( site_url + 'app/remove_media',{mid : id}, function(res){
					if(res.status){
						swal("Berhasil! file telah dihapus!", {
							icon: "success",
							timer: 2000
						});
						load_media_response();
					}else{
						swal("Kesalahan",res.message, "error");
					}
				},"json");
			} else {
				//swal("Your imaginary file is safe!");
			}
		});
		
	}

	function SaveToDisk(fileURL, fileName) {
		// for non-IE
		if (!window.ActiveXObject) {
			var save = document.createElement('a');
			save.href = fileURL;
			save.target = '_blank';
			save.download = fileName || 'unknown';

			var evt = new MouseEvent('click', {
				'view': window,
				'bubbles': true,
				'cancelable': false
			});
			save.dispatchEvent(evt);

			(window.URL || window.webkitURL).revokeObjectURL(save.href);
		}

		// for IE < 11
		else if ( !! window.ActiveXObject && document.execCommand)     {
			var _window = window.open(fileURL, '_blank');
			_window.document.close();
			_window.document.execCommand('SaveAs', true, fileName || fileURL)
			_window.close();
		}
	}

	function _editForm(url){
		window.location = site_url + url;
	}	
	
	function _removeData(url, id, reloadElement){
		bootbox.confirm({
			message: "Anda yakin akan menghapus data ini ?",
			callback: function(result) {
				if(result){
					$.post( site_url + url,{uid:id}, function(res){
						if(res.status){
							$('#'+reloadElement).datagrid('reload');
							swal("Success",res.message, "success");
						}else{
							swal("Sorry",res.message, "error");
						}
						
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}

	window.LanderApp.start(init);
</script>

</body>
</html>