<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
<script type="text/javascript">
	
	init.push(function () {
		
		$.post( site_url + 'answer/getFAQDetail', {faqid:getUri(base_url,3)}, function(data){
			if(data){
				$('#contacts-form').form('load', data);
				$('#uid').val(data.uniq_id);
				$('#answer').summernote({
					height: 200,
					tabsize: 2,
					codemirror: {
						theme: 'monokai'
					},
					onImageUpload: function(files, editor, welEditable) {
						sendFile(files[0], editor, welEditable);
					}
				});
			}
			
		},"json");
		
		if (! $('html').hasClass('ie8')) {
			
		}
		
		$("#contacts-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'answer/save',$("#contacts-form").serialize(),function(data){
					if(data.status == 'OK'){
						bootbox.dialog({
							message: "Successfuly save data",
							title: "Notification",
							buttons: {
								success: {
									label: "OK",
									className: "btn-success",
									callback: function() {
										window.location = site_url + 'answer/datalist';
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
		
		
		
		//$("#question").rules("add", { required: true });
		//$("#answer").rules("add", { required: true });
		//$("#categories").rules("add", { required: true });
		//$scope.editor.summernote('insertImage', result.data.url, 'filename');

		$('#tableList tbody td a.categoriesList').on('click', function(data){
			var cid = $(this).attr('id');
			bootbox.confirm({
				message: "Are you sure?",
				callback: function(result) {
					if(result){
						$.post( site_url + 'answer/remove_categories',{id:cid}, function(data){
							if(data.status == 'OK'){
								bootbox.dialog({
									message: "Successfuly remove category data",
									title: "Notification",
									buttons: {
										success: {
											label: "OK",
											className: "btn-success",
											callback: function() {
												//window.location = site_url + 'answer/datalist';
												angular.element($("#tableList")).scope().clearSearch();
												angular.element($("#categories")).scope().clearSearch();
											}
										}
									},
									className: "bootbox-sm"
								});
							}
						},'json');
					}
				},
				className: "bootbox-sm"
			});
		});
		
		$("#categories-form").validate({ 
			focusInvalid: true, 
			errorPlacement: function () {},
			submitHandler: function(form) {
				
				$.post( site_url + 'answer/create_Categories',$("#categories-form").serialize(),function(data){
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
	})
	
	
	function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                data: data,
                type: "POST",
				dataType: "json",
                url: site_url + 'answer/uploader',
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
	<!--ng-controller="faqData"-->
	<div class="mail-containers" >
		<div class="mail-container-header">
			Form F.A.Q <loading></loading>	
		</div>
		<br/>
		<div class="col-md-6">
			<form medthod="post" id="contacts-form"  ng-init="faqid('<?php echo $this->uri->segment(3);?>')">
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="question">Question *</label>
							<input type="text" class="form-control" id="question" name="question" placeholder="Question" ng-model="row.question">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="answer">Answer *</label>
							
							<textarea class="form-control" id="answer" rows="10" name="answer" ng-model="row.answer"></textarea>
							
							<!--
							<summernote on-image-upload="imageUpload(files)" editable="editable" editor="editor" ng-model="row.answer"></summernote>
							-->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group" >
							<label for="categories">Categories *</label>
							<!--ng-options="option.id as option.name for option in data"-->
							<select name="categories" id="categories" ng-controller="faqCategories" class="form-control">
								
								<option value=""> Select </option>
								<option ng-repeat="option in data" value="{{option.id}}"> {{option.name}} </option>
								
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group" ng-controller="statusData">
							<label for="status">Status *</label>
							<select name="status" class="form-control" id="status">
								<option value=""> Status </option>
								<option ng-repeat="option in data.availableOptions" value="{{option.id}}">{{option.name}}</option>
							</select>
						</div>
					</div>
					
				</div>
				<hr>
				<input type="hidden" ng-value="row.uniq_id" name="uid" id="uid"  />
				<input type="hidden" ng-value="row.uniq_id" name="uniq_id" id="uniq_id"  />
				<input type="submit" name="submit" class="btn btn-primary" value="Save">
				<input type="reset" name="cancel" class="btn btn-danger" value="Cancel">
			</form>
		</div>
		<div class="col-md-4 col-md-offset-1" ng-controller="faq">
			<div class="panel colourable">
				<div class="panel-heading">
					<span class="panel-title">FAQ Categories</span>
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
				<table class="table" ng-controller="faqCategories" id="tableList">
					<tbody>
						<tr ng-repeat="option in data"><td><span id="{{option.id}}" ecategories>{{option.name}}</span> <a href="javascript:void(0);"  class="pull-right categoriesList" id="{{option.id}}"><i class="fa fa-times"></i></a></td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>