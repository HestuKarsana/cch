<script>
	init.push(function () {
		var dg_height	= $(document).height() - ( $('#main-navbar').height() + $('.mail-container-header').outerHeight());
		
		$('#dgUserMan').datagrid({
			url: site_url + 'user/activeUser',
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
				{field:'kantor_pos',title:'Kantor Pos',width:150, sortable:true, align:'left'},
				{field:'regional',title:'Regional',width:100, sortable:true, align:'center'},
				{field:'role_name',title:'Role Name',width:100, sortable:true, align:'center',
					formatter:function(v,r,i){
						return v;
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

	
</script>
<section id="page-dg-user">
	<div class="mail-containers">
		<div class="mail-container-header">
			<div class="col-md-4">
				User List <span id="page-loading"></span>
			</div>
			<div class="col-md-8">
				<div class="col-md-2 col-md-offset-4">

					
				</div>
				<div class="col-md-2">

					
				</div>
				<div class="col-md-4">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" placeholder="Search..." name="s" id="key" >
						
						<span class="input-group-btn">
							<button class="btn" type="button" id="btn-search">
								<span class="fa fa-search"></span>
							</button>
						</span>
					</div>
				</div>	
			</div>
			<div class="clearfix"></div>
		</div>
		<table id="dgUserMan" class="dgResize"></table>
	</div>
</section>
