<script>
init.push(function () {
	$('#csv_file').pixelFileInput({ placeholder: 'No file selected...' });
})
</script>
<div  ng-controller="doUploadCsv">
	<div class="mail-containers">
		<div class="mail-container-header">
			Upload Contact List <loading></loading>
		</div>
		<br>
		<div class="col-md-6 col-md-offset-3">
		<form>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-{{css_class}}" ng-show="css_class">
						<div ng-show="ResultMsg">{{ResultMsg}}</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="exampleInputFile">CSV File</label>
					<input type="file" name="csv" file-model = "csv_file" id="csv_file" />
				</div>
			</div>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary" ng-disabled="buttonDisabled" ng-click="doUpload()" ><loading></loading> Upload </button>
				<a href="<?php echo site_url('contacts/templateDownload');?>" class="pull-right">Download File Template CSV</a>
			</div>
		</form>
		</div>
	</div>
</div>
