<script>
	init.push(function () {
		var dg_height	= screen.height - jQuery('body').height() - 110;
		$('#start').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			todayBtn: "linked",
			todayHighlight: true,
			//endDate: getCurrentDate()
		}).on('change',function(){
		}).val(getDate('first'));
		
		$('#end').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			todayBtn: "linked",
			todayHighlight: true,
			//endDate: getCurrentDate()
		}).on('change',function(){
		}).val(getDate('last'));
		
		$('#dgContacts').datagrid({
			url: site_url + 'report/getContactsData',
			//title:'Data Ticket',
			height: dg_height,
			nowrap: true,
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
				{field:'name',title:'Name', width:100, sortable:true,
					formatter:function(v,r,i){
						return '<a href="'+site_url+'contacts/d/'+r.id+'" class="text-danger">'+v+'</a>';
					}
				},
				{field:'phone_number',title:'Phone',width:100, sortable:true},
				{field:'email',title:'Email',width:100, sortable:true},
				{field:'total_ticket',title:'Total Ticket',width:100, sortable:true},
				/*
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
				*/
			]]
		})
		$("#main-menu-toggle").on("click", function () {
			setTimeout(function(){$('.dgResize').datagrid('resize')}, 1000);
		})
		
		
		
		//alert(getDate('first'));
	});
	//getDate('first');
	
	function act_search(){
		$('#dgContacts').datagrid('reload',{search:$('#search').val()});
	}
</script>
<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Contacts Report </span>
			</div>
			<table id="dgContacts" class="dgResize"></table>
		</div>
	</div>
</div>
<div class="toolbar" id="toolbar">
	<div class="col-md-3 col-xs-6">
	</div>
	<div class="col-md-3 col-xs-6">
		
	</div>
	<div class="col-md-6 col-xs-12">
		<div class="input-group">
			<input type="text" class="form-control" id="search" name="search" placeholder="Search" autocomplete="off">
			
      		<span class="input-group-btn">
        		<button class="btn btn-info btn-flat" type="button" onclick="javascript:act_search();"><i class="fa fa-search"></i></button>
      		</span>
    	</div>
    </div>
	<div class="clearfix"></div>
</div>