<script>
	init.push(function () {
		
		var dg_height	= screen.height - 260;
		
		$('#dgContact').datagrid({
			url: site_url + 'contacts/datalistMUM',
			//title:'Data Ticket',
			height: dg_height,
			nowrap: true,
			striped: true,
			remoteSort: true,
			singleSelect: true,
			fitColumns: false,
			pagination:true,
			rownumbers:true,
			pageSize:25,
			pageList:[25,50,75,100],
			//toolbar:"#toolbar",
			queryParams: {
			},
			columns:[[
				{field:'name',title:'Nama', width:200, sortable:true, align:'left',
					formatter:function(v,r,i){
						return '<a href="'+site_url+'contacts/d/'+r.id+'" class="text-danger">'+v+'</a>';
					}
				},
				{field:'address',title:'Address',width:500},
				{field:'delivery_address',title:'Delivery Address',width:500},
				{field:'propinsi',title:'Propinsi',width:200, sortable:true},
				{field:'kota',title:'Kota',width:200, sortable:true},
				{field:'kecamatan',title:'Kecamatan',width:200, sortable:true},
				{field:'kelurahan',title:'Kelurahan',width:200, sortable:true},
				
				{field:'phone_number',title:'Phone',width:150, sortable:true},
				{field:'email',title:'Email',width:200, sortable:true, align:'left'},
				
				{field:'type_cust_name',title:'Contact Type',width:200, sortable:true, align:'left'},
				{field:'parent_name',title:'Parent Name',width:200, sortable:true, align:'left'},
				{field:'last_update',title:'Last update',width:150, sortable:true, align:'center'},
				{field:'id',title:'Action',width:100, sortable:true, align:'center',
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
		
		$('#key').on('keydown', function(e){
			if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
				act_search();
			}
		});
		
		
		
		$('#type_customer').on('change', function(){
			act_search();
		});
	});
	
	function editForm(id){
		window.location = site_url + 'contacts/e/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'contacts/delete',{id:id}, function(data){
						if(data.status == 'OK'){
							$('#dgContact').datagrid('reload');
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
	
	
	function act_search(){
		$('#dgContact').datagrid('reload',{search:$('#key').val(), cust:$('#type_customer').val()});
	}
</script>

<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">MUM Contact List</span>
				<div class="panel-heading-controls" style="width:40%">
					
					<div style="width:100%">
						
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="s" id="key" style="width:70%;">
							<select name="type_customer" class="form-control" id="type_customer" style="width:30%;">
								<option value="">ALL</option>
								<option value="1">Member MUM</option>
								<option value="2">TSS MUM</option>
								<option value="3">Distributor MUM</option>
							</select>
							<span class="input-group-btn">
							
								

								<button class="btn" type="button" onclick="javascript:act_search()">
									<span class="fa fa-search"></span>
								</button>
								
								
							</span> <!-- / .input-group -->
						</div>
					</div>
				</div> 
			</div>
			<table id="dgContact" class="dgResize"></table>
		</div>
	</div>
</div>