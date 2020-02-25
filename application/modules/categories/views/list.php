<script>
	init.push(function () {
		var dg_height	= screen.height - jQuery('body').height() - 110;
		
		$('#dgCategories').datagrid({
			url: site_url + 'categories/dgDataListCategories',
			//title:'Data Ticket',
			height: dg_height,
			nowrap: true,
			striped: true,
			remoteSort: true,
			singleSelect: true,
			fitColumns: true,
			pagination:false,
			rownumbers:true,
			pageSize:25,
			pageList:[25,50,75,100],
			toolbar:"#toolbar",
			queryParams: {
				start: $('#start').val(),
				end: $('#end').val()
			},
			columns:[[
				{field:'name',title:'Category Name', width:400, sortable:true, align:'left',
					formatter:function(v,r,i){
						return v;
					}
				},
				//{field:'email',title:'Email',width:100, sortable:true, align:'left'},
				
				{field:'status',title:'Status',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						if(v == 1){
							return '<i class="fa fa-check"></i>';
						}else{
							return '<i class="fa fa-times"></i>';
						}
					}
				},
				{field:'total_ticket',title:'Total Ticket',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						return v;
					}
				},
				//{field:'last_update',title:'Last update',width:80, sortable:true, align:'center'},
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
		window.location = site_url + 'role/edit/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'role/delete',{id:id}, function(data){
						if(data.status == 'OK'){
							$('#dgCategories').datagrid('reload');
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
</script>
<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title pull-left">Ticket Category</span>
				<div class="clearfix"></div>
				
			</div>
			<table id="dgCategories" class="dgResize"></table>
		</div>
	</div>
</div>