<script>
init.push(function () {
	/*
	var dg_min		= $('.mail-container-header').height() + $('.mail-controls').height() + 55;
	var dg_height	= jQuery('.page-mail .mail-nav').height() - dg_min;//screen.height - jQuery('body').height() - 110;
	var page = $(location).attr('hash');
	//alert(jQuery('body').height());
	
	$('ul.sections > li').removeClass('active');
	$('.sections > li > a[href="' + page + '"]').parent().addClass('active');
	
	$('ul.sections > li').on('click', function(){
		$('ul.sections > li').removeClass('active');
		$(this).addClass('active');
		page = $(this).children("a").attr('href');
		
		$('#dgTicket').datagrid('reload',{search:$('#search').val(), view:page});
	});
	
	
	$('#dgTicket').datagrid({
		url: site_url + 'ticket/datalist',
		//title:'Data Ticket',
		height: dg_height,
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
			{field:'date',title:'Date',width:100, sortable:true, align:'center'},
			{field:'status',title:'Status',width:100, sortable:true, align:'center',
				formatter:function(v,r,i){
					var vclass;
					if(v == 1){
						vclass = 'primary';
					}else{
						vclass = 'info';
					}
					return '<span class="label label-ticket label-'+vclass+'">'+r.status_name+'</span>';
				}
			}
		]]
	});
	$("#main-menu-toggle").on("click", function () {
		setTimeout(function(){$('.dgResize').datagrid('resize')}, 1000);
	})
	
	*/
	 
	
	
});

function getTotalCheckbox(){
	var row = $('#dgTicket').datagrid('getSelections');
	//console.log(row.length);
	if(row.length > 0){
		$('.actionButton').removeAttr('disabled');
	}else{
		$('.actionButton').attr('disabled','disabled');
	}
}

function moveToSpam(){
	//var row = $('#dgTicket').datagrid('getSelected');
	var row = $('#dgTicket').datagrid('getSelections');
	if (row){
		var tid 	= [];
		for(var j=0; j<row.length; j++){
			tid.push(row[j].id);
		}
		angular.element('#ticketNaviation').scope().reloadCounter();
		$.post (site_url + 'ticket/moveToSpam', {ticket_id:tid}, function(data){
			if(data.status == 'OK'){
				$.growl({ title: "Tiket Update", message: data.msg, size: 'large' });
			}else{
				$.growl({ title: "Error Message", message: data.msg, size: 'large' });
			}
			
			$('#dgTicket').datagrid('reload');
		},'json');
	}
}

function moveToInbox(){
	//var row = $('#dgTicket').datagrid('getSelected');
	var row = $('#dgTicket').datagrid('getSelections');
	if (row){
		var tid 	= [];
		for(var j=0; j<row.length; j++){
			tid.push(row[j].id);
		}
		angular.element('#ticketNaviation').scope().reloadCounter();
		$.post (site_url + 'ticket/moveToInbox', {ticket_id:tid}, function(data){
			if(data.status == 'OK'){
				$.growl({ title: "Tiket Update", message: data.msg, size: 'large' });
			}else{
				$.growl({ title: "Error Message", message: data.msg, size: 'large' });
			}
			
			$('#dgTicket').datagrid('reload');
		},'json');
	}
}

function moveToTrash(){
	var row = $('#dgTicket').datagrid('getSelections');
	if (row){
		var tid 	= [];
		for(var j=0; j<row.length; j++){
			tid.push(row[j].id);
		}
		angular.element('#ticketNaviation').scope().reloadCounter();
		$.post (site_url + 'ticket/moveToTrash', {ticket_id:tid}, function(data){
			if(data.status == 'OK'){
				$.growl({ title: "Tiket Update", message: data.msg, size: 'large' });
			}else{
				$.growl({ title: "Error Message", message: data.msg, size: 'large' });
			}
			
			$('#dgTicket').datagrid('reload');
		},'json');
	}
}

function ticketShow(){
	//var page = $(location).attr('hash');
	//$('#dgTicket').datagrid('reload',{search:$('#search').val(), view:page});
}

function reloadDatagrid(element){
	$('#'+element).datagrid('reload');
}	
</script>
<section id="page-ticket">
<div ng-controller="ticketPages">
	
	<div class="mail-nav">
		
		<div class="compose-btn">
			<a href="javascript:void(0);" onclick="openPage('ccare/form')" class="btn btn-primary btn-labeled btn-block">
				<i class="btn-label fa fa-pencil-square-o"></i>Buat Pengaduan
			</a>
		</div>
		
		
		<div class="navigation" ng-controller="ticketCounter" ng-init="reloadCounter()" id="ticketNaviation">
			<ul class="sections">
				<li class="active"><a href="#/Your_unsolved_tickets">Tiket Masuk<span class="label pull-right">{{count.unsolved}}</span></a></li>
				<li><a href="#/Outgoing_ticket">Tiket Keluar<span class="label pull-right">{{count.outgoing}}</span></a></li>
				<!--
				<li><a href="#/Unassigned_tickets">Unassigned tickets <span class="label pull-right">{{count.unassignee}}</span></a></li>
				-->
				<li><a href="#/Recently_updated_tickets">Baru diupdate<span class="label pull-right">{{count.recently_updated}}</span></a></li>
				<!--<li><a href="#/On_progress_tickets">On Progress tickets <span class="label pull-right">{{count.all_pending}}</span></a></li>-->
				<!--
				<li><a href="#/Recently_solved_tickets">Baru selesai<span class="label pull-right">{{count.recently_solved}}</span></a></li>
				<li><a href="#/Suspended_tickets">Suspended tickets <span class="label pull-right">{{count.all_suspended}}</span></a></li>-->
				<li><a href="#/Request_close">Permintaan tutup tiket <span class="label pull-right label-danger">{{count.request_close}}</span></a></li>
				
				
			</ul>
		</div>
		<div class="mail-container-header-left">Semua Tiket</div>
		<div class="navigation" ng-controller="ticketCounter" ng-init="reloadCounter()" id="ticketNaviation">
			<ul class="sections">
				<!--
				<li><a href="#/All_ticket">All Ticket<span class="label pull-right">{{count.unsolved}}</span></a></li>
				
				<li><a href="#/Deleted_tickets">All Deleted tickets <span class="label pull-right">{{count.all_deleted}}</span></a></li>
				-->
				<li><a href="#/All_incoming">Semua tiket masuk <span class="label pull-right">{{count.all_incoming}}</span></a></li>
				<li><a href="#/All_outgoing">Semua tiket keluar <span class="label pull-right">{{count.all_outgoing}}</span></a></li>
				<!--
				<li><a href="#/All_unsolved_tickets">Semua tiket belum selesai <span class="label pull-right">{{count.all_unsolved}}</span></a></li>
				-->
				
			</ul>
		</div>
	</div>
	
	<!-- mail right -->
	<div class="mail-container">
		<div class="mail-container-header">
			{{new_title}} <loading></loading>
			<!--
			<form action="javascript:void(0);" class="pull-right" style="width: 200px;margin-top: 3px;">
				<div class="form-group input-group-sm has-feedback no-margin">
					<input type="text" placeholder="Search..." class="form-control">
					<span class="fa fa-search form-control-feedback" style="top: -1px"></span>
				</div>
			</form>
			-->
		</div>
		
		<div class="mail-controls clearfix">
			
			<div class="btn-toolbar wide-btns pull-left" role="toolbar">
				<div class="btn-group">
					<button type="button" class="btn" onclick="openPage('ccare/form')"><i class="fa fa fa-file-text-o"></i></button>
					<button type="button" class="btn" data-toggle="tooltip" data-placement="top" title="Reload" ng-click="reloadPage()" onclick="javascript:reloadDatagrid('dgTicket');"><i class="fa fa-repeat"></i> </button>
					<!--
					<button type="button" class="btn actionButton" data-toggle="tooltip" data-placement="top" title="Move to Spam" id="moveToSpam" onclick="javascript:moveToSpam();" ng-show="btn_spam" disabled="disabled"><i class="fa fa-exclamation-circle"></i> </button>

					<button type="button" class="btn actionButton" id="moveToTrash" data-toggle="tooltip" data-placement="top" title="Delete Ticket" onclick="javascript:moveToTrash();" ng-show="btn_delete" disabled="disabled"><i class="fa fa-trash-o"></i></button>
					<button type="button" class="btn actionButton" id="moveToOpen" data-toggle="tooltip" data-placement="top" title="Restore to Openticket" onclick="javascript:moveToInbox();" ng-show="btn_inbox" disabled="disabled"><i class="fa fa-inbox"></i></button>
					-->
				</div>
				
			</div>
			<!--
			<div class="btn-toolbar pull-right" role="toolbar">
				<div class="btn-group">
					<button type="button" class="btn" ng-click="viewPage(count - 1)" ng-disabled="prev" init="viewPage(count)"><i class="fa fa-chevron-left"></i></button>
					<button type="button" class="btn" ng-click="viewPage(count + 1)" ng-disabled="next" init="viewPage(count)"><i class="fa fa-chevron-right"></i></button>
				</div>
			</div>
			-->
			<div class="pages pull-right">
			{{row_start}}-{{row_end}} of {{row_total}}
			</div>
			
		</div>
		
		<table id="dgTicket" class="dgResize"></table>
		
	</div>
	<!-- end mail right -->
</div>
</section>