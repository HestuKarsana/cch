<script>
	init.push(function () {
		var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		$('#dgUserMan').datagrid({
			url: site_url + 'role/dgDataListRole',
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
			columns:[[
				{field:'name',title:'Role Name', width:400, sortable:true, align:'left',
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
				{field:'total_user',title:'Total User',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						return v;
					}
				},
				//{field:'last_update',title:'Last update',width:80, sortable:true, align:'center'},
				{field:'id',title:'Action',width:50, sortable:true, align:'center',
					formatter:function(v,r,i){
						var edit 	= '<a href="javascript:void(0);" onclick="javascript:editForm(\''+v+'\');" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>';
						var remove 	= '<a href="javascript:void(0);" onclick="javascript:removeData(\''+v+'\');" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>';
						if(r.default > 0){
							return edit;
						}else{
							return edit +' '+ remove;
						}
						
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
							$('#dgUserMan').datagrid('reload');
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
</script>
<div>
	<div class="mail-containers">
		<div class="mail-container-header">
			Role Manager <loading></loading>
			<a href="<?php echo site_url('role/create');?>" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Create</a>
		</div>
		<table id="dgUserMan" class="dgResize"></table>
	</div>
</div>