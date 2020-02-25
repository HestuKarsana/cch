<script>
	init.push(function () {
		var dg_height	= screen.height - 260;
			
		$('#dgContact').datagrid({
			url: site_url + 'knowledge/datalist',
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
				{field:'title',title:'Title', width:300, sortable:true, align:'left',
					formatter:function(v,r,i){
						return '<a href="'+site_url+'knowledge/e/'+r.uniq_id+'" class="text-danger">'+v+'</a>';
					}
				},
				{field:'categories',title:'Categories',width:80, sortable:true,
					formatter:function(v,r,i){
						return r.categories_name;
					}
				},
				{field:'last_update',title:'Last update',width:80, sortable:true, align:'center'},
				{field:'uniq_id',title:'Action',width:80, sortable:true, align:'center',
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
		window.location = site_url + 'knowledge/e/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'knowledge/delete',{id:id}, function(data){
						if(data.status == 'OK'){
							$('#dgContact').datagrid('reload');
							//var table = $('#example').DataTable();
							//table.ajax.reload();
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
	
	function act_search()
	{
		$('#dgContact').datagrid('reload',{search:$('#key').val()});
	}
</script>
<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Product Knowledge</span>
				<div class="panel-heading-controls" style="width:30%">
					<form action="#" style="width:100%">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="s" id="key">
							<span class="input-group-btn">
								<button class="btn" type="button" onclick="javascript:act_search()">
									<span class="fa fa-search"></span>
								</button>
								<a href="<?php echo site_url('knowledge/create');?>" class="btn btn-primary">
									<span class="fa fa-plus"></span> Create
								</a>
							</span> <!-- / .input-group -->
						</div>
					</form>
				</div> 
			</div>
			<table id="dgContact" class="dgResize"></table>
		</div>
	</div>
</div>