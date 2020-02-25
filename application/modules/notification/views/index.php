<script>
	/*
	init.push(function () {
		var dg_height	= screen.height - 230;
		$('#dgMacro').datagrid({
			url: site_url + 'macro/datalist',
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
				//start: $('#start').val(),
				//end: $('#end').val()
			},
			columns:[[
				{field:'name',title:'Nama', width:200, sortable:true, align:'left',
					formatter:function(v,r,i){
						return '<a href="'+site_url+'macro/d/'+r.id+'" class="text-danger">'+v+'</a>';
					}
				},
				{field:'categories_name',title:'Categories',width:50, sortable:true, align:'left'},
				{field:'create_date',title:'Create',width:50, sortable:true, align:'center'},
				{field:'status',title:'Status',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						if(v == 1){
							return 'Active';
						}else{
							return 'Not Active';
						}
					}
				},
				{field:'id',title:'Action',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						var edit 	= '<a href="javascript:void(0);" onclick="javascript:editForm(\''+v+'\');" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>';
						var remove 	= '<a href="javascript:void(0);" onclick="javascript:removeData(\''+v+'\');" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>';
						
						return edit +' '+ remove;
					}
				}
			]]
		})
		$("#main-menu-toggle").on("click", function () {
			setTimeout(function(){$('.dgResize').datagrid('resize')}, 1000);
		})

	});
	
	function editForm(id){
		window.location = site_url + 'macro/e/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'macro/delete',{id:id}, function(data){
						if(data.status == 'OK'){
							$('#dgMacro').datagrid('reload');
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
	*/
</script>
<section id="page-notification">

</section>
<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">List Notification</span>
				<div class="panel-heading-controls" style="width:30%">
					<form action="#" style="width:100%">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="s" id="key">
							<span class="input-group-btn">
								<button class="btn" type="button" onclick="javascript:act_search()">
									<span class="fa fa-search"></span>
								</button>
								<a href="<?php echo site_url('notification/form');?>" class="btn btn-primary"><i class="fa fa-plus"></i> Create</a>
							</span>
						</div>
					</form>
				</div> 
			</div>
			<table id="dgMacro" class="dgResize"></table>
		</div>
	</div>
</div>