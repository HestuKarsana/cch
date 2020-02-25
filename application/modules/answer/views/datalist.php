<script>
	init.push(function () {
		
		var dg_height	= screen.height - 260;
		
		
		$('#dgFAQ').datagrid({
			url: site_url + 'answer/getDataList',
			//title:'Data Ticket',
			height: dg_height,
			nowrap: true,
			striped: true,
			remoteSort: true,
			singleSelect: true,
			fitColumns: true,
			pagination:false,
			rownumbers:true,
			columns:[[
				{field:'question',title:'Question', width:300, sortable:true, align:'left',
					formatter:function(v,r,i){
						return v;
					}
				},
				
				{field:'categories_name',title:'Category',width:200, sortable:true,
					formatter:function(v,r,i){
						return v;
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
			]],
			onDblClickRow: function(i, r){
				//var $modal  = $('#uidemo-modals-effects-template').clone(true);
				var $modal  = $('#faq-detail');
				$modal.find('> div').addClass('modal-dialog modal-lg animated bounceIn');
				$modal.modal({
					show:true,
					background:'static'
				});
				$('h4#question-header').text(r.question);
				//$('#faq-detail .modal-body').load( site_url + 'answer/d/'+ r.uniq_id);
				$.post( site_url + 'answer/d/'+ r.uniq_id, function(data){
					$('#faq-detail .modal-body').html(data);
				});
				
			}
		})
		
		//$("#main-menu-toggle").on("click", function () {
		//	setTimeout(function(){$('.dgResize').datagrid('resize')}, 1000);
		//})
		
	});
	
	function editForm(id){
		window.location = site_url + 'answer/e/'+id;
	}	
	
	function removeData(id){
		bootbox.confirm({
			message: "Are you sure want to delete this record ?",
			callback: function(result) {
				if(result){
					$.post( site_url + 'answer/delete',{id:id}, function(data){
						if(data.status == 'OK'){
							$('#dgFAQ').datagrid('reload');
						}
					},"json");
				}
			},
			className: "bootbox-sm"
		});
	}
	
	function nl2br (str, is_xhtml) {   
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
		return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
	}
	
	function act_search(){
		$('#dgFAQ').datagrid('reload',{search:$('#key').val()});
	}
	
</script>
<div class="row setMargin">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<span class="panel-title">Data F.A.Q </span>
				<div class="panel-heading-controls" style="width:30%">
					<form action="#" style="width:100%">
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" placeholder="Search..." name="s" id="key">
							<span class="input-group-btn">
								<button class="btn" type="button" onclick="javascript:act_search()">
									<span class="fa fa-search"></span>
								</button>
								<a href="<?php echo site_url('answer/create');?>" class="btn btn-primary">
									<span class="fa fa-plus"></span> Create
								</a>
							</span> <!-- / .input-group -->
						</div>
					</form>
				</div> 
			</div>
			<table id="dgFAQ" class="dgResize"></table>
		</div>
	</div>
</div>

<div id="faq-detail" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
	<div>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="question-header">F.A.Q</h4>
			</div>
			<div class="modal-body">...</div>
		</div><!-- / .modal-content -->
	</div><!-- / .modal-dialog -->
</div>