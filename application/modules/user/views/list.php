<script>
	init.push(function () {
		var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		$('#dgUserMan').datagrid({
			url: site_url + 'user/dgDataListUser',
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
				{field:'title',title:'Full Name', width:100, sortable:true, align:'left',
					formatter:function(v,r,i){
						return v;
					}
				},
				{field:'username',title:'Username',width:100, sortable:true,
					formatter:function(v,r,i){
						return v;
					}
				},
				{field:'email',title:'Email',width:100, sortable:true, align:'left'},
				{field:'phone',title:'Phone / Telegram',width:100, sortable:true, align:'left'},
				{field:'kantor_pos',title:'Kantor Pos',width:150, sortable:true, align:'left'},
				{field:'regional',title:'Regional',width:100, sortable:true, align:'center'},
				{field:'role_name',title:'Role Name',width:100, sortable:true, align:'center',
					formatter:function(v,r,i){
						return v;
					}
				},
				{field:'status',title:'Status',width:100, sortable:true, align:'center',
					formatter:function(v,r,i){
						if(v == 1){
							return '<i class="fa fa-check"></i>';
						}else{
							return '<i class="fa fa-times"></i>';
						}
					}
				},
				//{field:'last_update',title:'Last update',width:80, sortable:true, align:'center'},
				{field:'id',title:'Action',width:80, sortable:true, align:'center',
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

		$('#btn-search').on('click', function(){
			$('#dgUserMan').datagrid('reload', {search:$('#key').val(), regional:$('#regional').val(), role : $('#role').val()});
		});

		$('.select2min').select2();
	});

	
	
	function editForm(id){
		window.location = site_url + 'user/edit/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'user/delete',{id:id}, function(data){
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
<section id="page-dg-user">
	<div class="mail-containers">
		<div class="mail-container-header">
			<div class="col-md-4">
				User Manager <span id="page-loading"></span>
			</div>
			<div class="col-md-8">
				<div class="col-md-2 col-md-offset-4">

					<select name="regional" class="form-control input-sm select2min" id="regional">
						<option value="">Semua Regional</option>
						<option value="Regional 1">Regional 1</option>
						<option value="Regional 2">Regional 2</option>
						<option value="Regional 3">Regional 3</option>
						<option value="Regional 4">Regional 4</option>
						<option value="Regional 5">Regional 5</option>
						<option value="Regional 6">Regional 6</option>
						<option value="Regional 7">Regional 7</option>
						<option value="Regional 8">Regional 8</option>
						<option value="Regional 9">Regional 9</option>
						<option value="Regional 10">Regional 10</option>
						<option value="Regional 11">Regional 11</option>
						<option value="Kantor Pusat">Kantor Pusat</option>
					</select>
				</div>
				<div class="col-md-2">

					<select name="role" class="form-control input-sm select2min" id="role">
						<option value="">Semua Role</option>
						<option value="2">Agent</option>
						<option value="4">Entri XRay</option>
						<option value="5">Management</option>
						<option value="7">Marketplace</option>
					</select>
				</div>
				<div class="col-md-4">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" placeholder="Search..." name="s" id="key" >
						
						<span class="input-group-btn">
							<button class="btn" type="button" id="btn-search">
								<span class="fa fa-search"></span>
							</button>
							<a href="<?php echo site_url('user/create');?>" class="btn btn-primary"><i class="fa fa-plus"></i> Create</a>
						</span>
					</div>
				</div>	
			</div>
			<div class="clearfix"></div>
		</div>
		<table id="dgUserMan" class="dgResize"></table>
	</div>
</section>
