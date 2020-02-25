<script>
	init.push(function () {
		var page = $(location).attr('hash');
		//alert(jQuery('body').height());
		$('ul.sections > li').removeClass('active');
		$('.sections > li > a[href="' + page + '"]').parent().addClass('active');
		
		$('ul.sections > li').on('click', function(e){
			
			$('ul.sections > li').removeClass('active');
			$(this).addClass('active');
			e.defaultPrevented;
			/*
			page = $(this).children("a").attr('href');
			console.log(page);
			$('#categories').val($(this).children("a").attr('id')).trigger('value');
			//angular.element('#faqBlock').scope().;
			var scope = angular.element($("#faqBlock")).scope();
			scope.categories = $(this).children("a").attr('id');
	
			angular.element($("#faqBlock")).scope().reloadSearch($('#key').val(), $('#categories').val());
			*/
		});
	});
	
</script>
<div ng-controller="faq">
	<div class="mail-nav">
		
		<div class="mail-container-header-left" >Categories</div>
		<div class="navigation" id="ticketNaviation" ng-controller="faqCategories">
			<ul class="sections">
				<!--href="#/{{ldata.name}}" -->
				<li class="active"><a ng-href="#/" ng-click="reloadSearch(null,null)" id="0" title="All">All</a></li>
				<li ng-repeat="ldata in data"><a ng-href="#/{{ldata.name}}"  ng-click="reloadSearch(key,ldata.id)" title="{{ldata.name}}" id="{{ldata.id}}">{{ldata.name}}<span class="label pull-right">{{ldata.total_data}}</span></a></li>
			</ul>
		</div>
		<!--<div class="mail-container-header-left">Tags</div>-->
	</div>

	<!-- mail right -->

	<div class="mail-container">
		<div class="mail-container-header">
			F.A.Q <loading></loading>	
		</div>
		<div class="col-md-12"  id="faqBlock">
			<br />
			<form class="search-form bg-primary" ng-submit="reloadSearch(key, categories)">
				<div class="input-group input-group-lg">
					<span class="input-group-addon no-background"><i class="fa fa-search"></i></span>
					<input type="hidden" name="categories" class="form-control" ng-model="categories" id="categories">
					<input type="text" name="s" class="form-control" placeholder="Type your search here..." ng-model="key" id="key">
					<span class="input-group-btn">
						<button class="btn" type="submit" >Search</button>
					</span>
				</div>
			</form>
			<label ng-if="data.total >= 1"> Search Result : {{data.total}}</label>
			<div ng-if="data.total == 0" class="panel panel-danger">
				<div class="panel-heading">
					<span class="panel-title">{{data.msg}}</span>
					<div class="panel-heading-controls">
						<a href="javascript:void(0);"><div class="panel-heading-icon" ng-click="clearSearch()"><i class="fa fa-times"></i></div></a>
					</div>
				</div>
			</div>
			<div class="panel" ng-repeat="option in data.rows" ng-if="data.total >= 1" >
				<div class="panel-heading">
					<div class="pull-right badge badge-success">{{option.categories}}</div>
					<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-example" href="#{{option.faq_id}}">
						<strong>Q : </strong> {{option.question}} 
					</a>
				</div>
				<div id="{{option.faq_id}}" class="panel-collapse collapse">
					<div class="panel-body" >
						<strong>Answer : </strong>
						<div ng-bind-html="option.answer"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>