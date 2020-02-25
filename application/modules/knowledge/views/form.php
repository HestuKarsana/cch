<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
<script type="text/javascript">
	
	init.push(function () {
		
		$.post ( site_url + 'knowledge/getKnowledgeDetail',{uid:getUri(base_url,3)}, function(data){
			
			$('#knowledge-form').form('load', data);
			$('#uid').val(data.uniq_id);
			
			$('#detail').summernote({
				height: 200,
				tabsize: 2,
				codemirror: {
					theme: 'monokai'
				},
				onImageUpload: function(files, editor, welEditable) {
					sendFile(files[0], editor, welEditable);
				},
				/*
				callbacks: {
					onInit: function() {
					  console.log('Summernote is launched');
					  //console.log($('#hidden').val());
					  //angular.element($("#knowledge-form")).scope().init('68178438851bc20a824e48bd537979b4ec4d1350');
					  
					 // $('#detail').summernote('insertText', 'asdsadas');
					},
					onChange: function(contents, $editable) {
					  //console.log('onChange:', contents, $editable);
					}
				  }
				  */
			});
		},"json");
		
		if (! $('html').hasClass('ie8')) {
			
		}
		
		$("#knowledge-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'knowledge/save',$("#knowledge-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'knowledge';
									}
								}
							},
							className: "bootbox-sm"
						});
					}else{
						$.growl.error({ message: data.msg });
					}
					
				},'json').fail(function(jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 500)
					{
						alert('Error 500');
					}else if(textStatus == 'parseerror'){
						alert('Parse error');
						howError();
					}
					
				});
				
			}
		});
		/*
		$('#ecat25').editable({
			url: '/post',
			pk: 1
		});
		*/
		$("#tags").select2({
			tags: [],
			multiple: true,
			tokenSeparators: [',', ' '],
			formatNoMatches: null
		});
		
		$("#title").rules("add", { required: true });
		$("#detail").rules("add", { required: true });
		
		$("#categories-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'knowledge/create_Categories',$("#categories-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										angular.element($("#tableList")).scope().clearSearch();
												angular.element($("#categories")).scope().clearSearch();
									}
								}
							},
							className: "bootbox-sm"
						});
					}else{
						$.growl.error({ message: data.msg });
					}
					
				},'json').fail(function(jqXHR, textStatus, errorThrown) {
					if(jqXHR.status == 500)
					{
						alert('Error 500');
					}else if(textStatus == 'parseerror'){
						alert('Parse error');
						howError();
					}
					
				});
				
			}
		});
		
		$('#tableList tbody td a.categoriesList').on('click', function(data){
			var cid = $(this).attr('id');
			
		});
		
	})
	
	
	function sendFile(file, editor, welEditable) {
		data = new FormData();
		data.append("file", file);
		$.ajax({
			data: data,
			type: "POST",
			dataType: "json",
			url: site_url + 'knowledge/uploader',
			cache: false,
			contentType: false,
			processData: false,
			success: function(url) {
				editor.insertImage(welEditable, url.url);
			}
		});
	}
</script>
<div>
	<div class="mail-containers">
		<div class="mail-container-header">
			Form Knowledge Base <loading></loading>	
		</div>
		<br/>
		<div class="col-md-8">
			<form medthod="post" action="<?php echo site_url('answer/save');?>" id="knowledge-form" ng-init="init('<?php echo $this->uri->segment(3);?>')" ><!--ng-init="init('<?php echo $this->uri->segment(3);?>')"-->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="title">Title *</label>
								<input type="text" class="form-control" id="title" name="title" placeholder="Title" ng-model="row.title">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="detail">Information *</label>
								<!--
								<textarea class="form-control" id="detail" rows="10" name="detail" placeholder="Detail product information" editor="editor" on-image-upload="imageUpload(files)" ng-model="row.detail" summernote></textarea>
								-->
								<textarea class="form-control" id="detail" rows="10" name="detail" ng-model="row.detail" loadsummernote></textarea>
								<input type="hidden" id="hidden" ng-value="row.detail">
								<!--
								<summernote ng-model="row.detail"></summernote>
								-->
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group" ng-controller="kbaseCategories">
								<label for="categories">Categories</label>
								<select name="categories" class="form-control" id="categories" ng-model="row.categories">
									<option value="">- Categories -</option>
									<option ng-repeat="option in data" value="{{option.id}}"> {{option.name}} </option>
									
									<!--
									<optgroup ng-repeat="(key,value) in listcategory" label="{{key}}">
										<option ng-repeat="dlist in value" value="{{dlist.id}}"> {{dlist.name}} </option>
									</optgroup>
									-->
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Status </label>
								<select name="status" id="status" ng-model="row.status" class="form-control">
									<option value=""> - Status - </option>
									<option value="1"> Active </option>
									<option value="0"> Not Active </option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="tags">Tags </label>
								<input type="text" class="form-control" id="tags" name="tags" placeholder="Tags" ng-model="row.tags">
							</div>
						</div>
					</div>
					
					<hr>
					<input type="hidden" name="uid" id="uid" ng-value="row.uniq_id" />
					<input type="submit" name="submit" class="btn btn-primary" value="Save">
					<input type="reset" name="cancel" class="btn btn-danger" value="Cancel">
				</form>
		</div>
		
		<div class="col-md-4" ng-controller="faq">
			<div class="panel colourable">
				<div class="panel-heading">
					<span class="panel-title" delete>Knowledge Categories</span>
					<div class="panel-heading-controls" style="width:50%">
						<form styele="width:100%;"  id="categories-form">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" placeholder="Add Categories..." name="name" id="name">
								<span class="input-group-btn">
									<button class="btn" type="submit">
										<span class="fa fa-plus"></span>
									</button>
								</span>
							</div> <!-- / .input-group -->
						</form>
					</div> <!-- / .panel-heading-controls -->
				</div>
				<table class="table" ng-controller="kbaseCategories" id="tableList">
					<tbody>
						<tr ng-repeat="option in data"><td><span id="{{option.id}}" ecategories>{{option.name}}</span> <a href="javascript:void(0);"  class="pull-right"><i class="fa fa-times" id="{{option.id}}" delcategories></i></a></td></tr>
					</tbody>
				</table>
			</div>
		</div>
		
	</div>
</div>
