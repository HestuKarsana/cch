<div>

<!-- mail right -->
<div class="mail-containers" ng-controller="knowledgeIndex">
	<div class="mail-container-header">
		Knowledge Base <loading></loading>
	</div>
	<br/>
	
	<div class="col-md-10 col-md-offset-1">
		<div class="col-md-5 col-md-offset-1" ng-repeat="cat in data">
			<div class="panel colourable">
				<div class="panel-heading">
					<span class="panel-title"><i class="fa fa-book"></i> <strong>{{cat.name}}</strong></span>
					<div class="panel-heading-controls">
						<div class="panel-heading-icon">{{cat.total}}</div>
					</div>
				</div>
				
				<div class="list-group" ng-controller="kbaseList" ng-init="categories(cat.id)">
					<a ng-repeat="list in datakbase" href="{{list.url}}" class="list-group-item">{{list.title}}</a>
				</div>
				
			</div>
		</div>		
	</div>
</div>
</div>