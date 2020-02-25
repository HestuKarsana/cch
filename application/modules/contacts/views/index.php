<script>
	init.push(function () {
		
		//(screen.height);
		//alert( $('.mail-container-header').outerHeight() );
		
		//var dg_height	= screen.height - 260;
		$(window).on('resize', function(){
			$('#dgContact').datagrid('resize');
		})
		var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		$('#dgContact').datagrid({
			url: site_url + 'contacts/datalist',
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
			queryParams: {
			},
			columns:[[
				{field:'name_requester',title:'Nama', width:200, sortable:true, align:'left',
					formatter:function(v,r,i){
						return '<a href="'+site_url+'contacts/d/'+r.id+'" class="text-danger">'+v+'</a>';
					}
				},
				{field:'address',title:'Address',width:500},
				{field:'email',title:'Email',width:200, sortable:true, align:'left'},
				{field:'phone',title:'Phonee',width:150, sortable:true, align:'center'},
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
<div ng-controller="contacts">
	<div class="mail-containers">
		<div class="mail-container-header">
			Data Pelanggan <loading></loading>
			<div class="pull-right col-md-6">
				<div class="row">
					<div class="col-md-3">
						<select name="type_customer" class="form-control input-sm" id="type_customer" >
							<option value="">ALL</option>
							<option ng-repeat="opt in row" value="{{opt.id}}">{{opt.name}}</option>
						</select>
					</div>
					<div class="col-md-9">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="s" id="key" >
							
							<span class="input-group-btn">
								
								
								
								<button class="btn" type="button" onclick="javascript:act_search()">
									<span class="fa fa-search"></span>
								</button>
								<!--
								<a href="<?php echo site_url('contacts/create');?>" class="btn btn-primary"><i class="fa fa-plus"></i>.</a>
								<a href="<?php echo site_url('contacts/upload_csv');?>" class="btn btn-info"><i class="fa fa-upload"></i>.</a>
								-->	
							</span>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<table id="dgContact" class="dgResize"></table>
	</div>
</div>