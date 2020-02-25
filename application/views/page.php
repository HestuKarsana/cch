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
	
	<link href="<?php echo base_url('assets/css/summernote.css');?>" rel="stylesheet">
	
	<!--[if lt IE 9]>
		<script src="<?php echo base_url('assets/js/ie.min.js');?>"></script>
	<![endif]-->
	<script src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>
	
	
	<script src="<?php echo base_url('assets/js/angular.min.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-avatar.min.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-sanitize.js');?>"></script>
	<script src="<?php echo base_url('assets/js/angular-summernote.js');?>"></script>
	
	<script src="<?php echo base_url('assets/js/socket.io.js');?>"></script>
	
	<style>
	
.timeline{
  margin-top:20px;
  position:relative;
  
}

.timeline:before{
  position:absolute;
  content:'';
  width:4px;
  height:calc(100% + 50px);
background: rgb(138,145,150);
background: -moz-linear-gradient(left, rgba(138,145,150,1) 0%, rgba(122,130,136,1) 60%, rgba(98,105,109,1) 100%);
background: -webkit-linear-gradient(left, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
background: linear-gradient(to right, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8a9196', endColorstr='#62696d',GradientType=1 );
  left:14px;
  top:5px;
  border-radius:4px;
}

.timeline:after{
	background:#FFF !important;
}

.timeline-month{
  position:relative;
  padding:4px 15px 4px 35px;
  background-color:#444950;
  display:inline-block;
  width:auto;
  border-radius:40px;
  border:1px solid #17191B;
  border-right-color:black;
  margin-bottom:30px;
}

.timeline-month span{
  position:absolute;
  top:-1px;
  left:calc(100% - 10px);
  z-index:-1;
  white-space:nowrap;
  display:inline-block;
  background-color:#111;
  padding:4px 10px 4px 20px;
  border-top-right-radius:40px;
  border-bottom-right-radius:40px;
  border:1px solid black;
  box-sizing:border-box;
}

.timeline-month:before{
  position:absolute;
  content:'';
  width:20px;
  height:20px;
background: rgb(138,145,150);
background: -moz-linear-gradient(top, rgba(138,145,150,1) 0%, rgba(122,130,136,1) 60%, rgba(112,120,125,1) 100%);
background: -webkit-linear-gradient(top, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
background: linear-gradient(to bottom, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8a9196', endColorstr='#70787d',GradientType=0 );
  border-radius:100%;
  border:1px solid #17191B;
  left:5px;
}

.timeline-section{
  padding-left:35px;
  display:block;
  position:relative;
  margin-bottom:30px;
}

.timeline-date{
  margin-bottom:15px;
  padding:2px 15px;
  background:linear-gradient(#74cae3, #5bc0de 60%, #4ab9db);
  position:relative;
  display:inline-block;
  border-radius:20px;
  border:1px solid #17191B;
  color:#fff;
text-shadow:1px 1px 1px rgba(0,0,0,0.3);
}
.timeline-section:before{
  content:'';
  position:absolute;
  width:30px;
  height:1px;
  background-color:#444950;
  top:12px;
  left:20px;
}

.timeline-section:after{
  content:'';
  position:absolute;
  width:10px;
  height:10px;
  background:linear-gradient(to bottom, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
  top:7px;
  left:11px;
  border:1px solid #17191B;
  border-radius:100%;
}

.timeline-section .col-sm-4{
  margin-bottom:15px;
}

.timeline-box{
  position:relative;
  
 background-color:#FFF;
  border-radius:15px;
  border-top-left-radius:0px;
  border-bottom-right-radius:0px;
  border:1px solid #17191B;
  transition:all 0.3s ease;
  overflow:hidden;
}

.box-icon{
  position:absolute;
  right:5px;
  top:0px;
}

.box-title{
  padding:5px 15px;
  border-bottom: 1px solid #17191B;
}

.box-title i{
  margin-right:5px;
}

.box-content{
  padding:5px 15px;
  background-color:#FFF;
}

.box-content strong{
  color:#666;
  font-style:italic;
  margin-right:5px;
}

.box-item{
  margin-bottom:5px;
}

.box-footer{
 padding:5px 15px;
  border-top: 1px solid #17191B;
  background-color:#444950;
  text-align:right;
  font-style:italic;
}

	</style>
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

<script src="<?php echo base_url('assets/js/summernote.min.js');?>"></script>

<script src="<?php echo base_url('assets/plugins/core/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/daygrid/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/list/main.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/interaction/main.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/jquery.iframe-transport.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery.fileupload.js');?>"></script>
<script src="<?php echo base_url('assets/js/custom.js');?>"></script>
<script src="<?php echo base_url('assets/js/myreport.js');?>"></script>




<script type="text/javascript">
	var IDLE_TIMEOUT        = 3600; //seconds
	var _idleSecondsCounter = 0;
        document.onclick = function() {
            _idleSecondsCounter = 0;
        };
        document.onmousemove = function() {
            _idleSecondsCounter = 0;
        };
        document.onkeypress = function() {
            _idleSecondsCounter = 0;
        };
        window.setInterval(CheckIdleTime, 1000);
		
	function CheckIdleTime() {
        _idleSecondsCounter++;
        //var oPanel = document.getElementById("SecondsUntilExpire");
        //if (oPanel)
        //oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
        if (_idleSecondsCounter >= IDLE_TIMEOUT) {
			//$('#lock-screen').modal({ backdrop:'static', keyboard:false, show:true });
			window.location.href = site_url + 'logout';
        }
	}
	
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

	function openTracking(awb){
		$('#modal_tracking').modal({
			  keyboard: false,
			  backdrop : 'static',
			  show : true
			});
		$('#modal_tracking #tracking_awb_no').text(awb);
		$('#modal_tracking #timeline-tracking').html('');
		$.post( site_url + 'app/trackandtrace',{resi:awb}, function(res){
			var html = '';
				html += '<div class="xcontainer">';
					html += '<div class="timeline">';
			$.each( res, function( key, value ) {
						//console.log(value.date)
						//html += '<div class="timeline-month">';
								//August, 2018 <span>3 Entries</span>
						//html += '</div>';
						html += '<div class="timeline-section">';
							html += '<div class="timeline-date">';
								//21, Tuesday
								html += value.date +' '+value.time;
							html += '</div>';
							html += '<div class="row">';
								html += '<div class="col-sm-12">';
									html += '<div class="timeline-box">';
										html += '<div class="box-title">';
											html += '<i class="fa fa-asterisk text-success" aria-hidden="true"></i> '+ value.status + ' ( ' + value.office +' ) ';
										html += '</div>';
										html += '<div class="box-content">';
											//html += '<a class="btn btn-xs btn-default pull-right">Details</a>';
											//html += '<div class="box-item"><strong>Loss Type</strong>: A/C Leak</div>';
											//html += '<div class="box-item"><strong>Loss Territory</strong>: Texas</div>';
											html += '<div class="box-item"><strong></strong>'+value.info+'</div>';
										html += '</div>';
										//html += '<div class="box-footer">- Tyler</div>';
									html += '</div>';
								html += '</div>';
							html += '</div>';
						html += '</div>';
					
			});
					html += '</div>';
				html += '</div>';
				$('#modal_tracking #timeline-tracking').html(html);
			//console.log(res);
		},"json");
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

	function _editFormPopUp(element, url, id, form){
		$('#'+element).modal({
			keyboard: false,
			backdrop : 'static',
			show : true
		});
		$.post( site_url + url, {uid:id}, function(res){
			$('#'+form).form('load', res.form);
		},"json");
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