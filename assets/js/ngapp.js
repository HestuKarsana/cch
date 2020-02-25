//var cirm = angular.module('cirmapp',['ngAvatar','ngSanitize','rt.select2','summernote']);
var cirm = angular.module('cirmapp',['ngAvatar','ngSanitize','summernote']);
//var cirm = angular.module('cirmapp',['ngAnimate']).config(['$animateProvider', function($animateProvider){
//  $animateProvider.classNameFilter(/^((?!(fa-spinner)).)*$/);
//}]);
//
/* Contact Reason */
cirm.config(function($provide) {
   
});

cirm.controller('contactReason', ['$scope', '$http', function ($scope, $http) {
	
	$scope.init = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/getContactReason',
			data: $.param({cid:key})
		})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);
/* end contact reason */

// ticket & mum categories 
cirm.controller('ticket-categories', ['$scope', '$http', function ($scope, $http) {
	
	var key = '';
	Query(key);
	
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	
	$scope.reloadSearch = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'macro/getCategories',
			data: $.param({search:key})
		})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);


/* Start Angular JS FAQ */
cirm.controller('faq', ['$scope', '$http', function ($scope, $http) {
	var key 		= '';
	var categories	= '';
	Query(key, categories);
	
	$scope.clearSearch = function() {
		$scope.key 			= null;
		$scope.categories	= null;
		Query($scope.key, $scope.categories);
	}
	
	$scope.reloadSearch = function(key, categories){
		Query(key, categories);
	}
	
	$scope.addCategories = function(name){
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'answer/create_Categories',
			data: $.param({name:name})
		})
		.success(function(data){
			$scope.row = data;
		})
		.error(function() {
			$scope.row = "error in fetching data";
		});
	}
	
	function Query(key, categories){
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'answer/getFAQ',
			data: $.param({q:key, category:categories})
		})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

cirm.controller('faqCategories', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	Query(key);
	
	/*
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	
	$scope.reloadSearch = function(key){
		Query(key);
	}
	*/
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'answer/getFAQCategories',
			data: $.param({q:'faq'})
		})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

cirm.controller('faqData', ['$scope', '$http', function ($scope, $http) {
	
	$scope.faqid = function(key){
		Query(key);		
	}
	
	$scope.imageUpload = function(files) {
		console.log('image upload:', files);
		console.log('image upload\'s editable:', $scope.editable);
	}
	
	function Query(key){		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'answer/getFAQDetail',
			data: $.param({faqid:key})
		})
		.success(function(data){
			$scope.row = data;
		})
		.error(function() {
			$scope.row = "error in fetching data";
		});
	}
}]);

/* end FAQ */
/* Knowledge base */
cirm.controller('knowledgeData', ['$scope', '$http', 'fileUpload', function ($scope, $http, fileUpload) {
	
	$scope.init = function(key){
		Query(key);
	}
	
	$scope.imageUpload = function(files) {
		
		//console.log('image upload:', files);
		
		//var file = $scope.myFile;
		//console.log('file is ' );
		//console.dir(file);

		//var uploadUrl = site_url + "knowledge/uploader";
		//fileUpload.uploadFileToUrl(file, uploadUrl);
	}
	
	function Query(key){	
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getKnowledgeDetail',
			data: $.param({uid:key})
		})
		.success(function(data){
			$scope.row = data;
		})
		.error(function() {
			$scope.row = "error in fetching data";
		});
	}
}]);

cirm.controller('kbaseCategories', ['$scope', '$http', function ($scope, $http) {
	
	var key = '';
	Query(key);
	
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	/*
	$scope.deleteCategorie = function(id){
		
	}
	*/
	$scope.reloadSearch = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getCategories',
			data: $.param({q:'faq'})
		})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

cirm.controller('knowledgeDetail', ['$scope', 'ModelKnowledgeDetail', function ($scope, ModelKnowledgeDetail) {
	
	$scope.init = function(id)
	{
		getDetail(id);
	}
	function getDetail(id){
		ModelKnowledgeDetail.getDetail(id)
			.success(function (data) {
				
				$scope.data	= data;
				
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);
cirm.factory('ModelKnowledgeDetail', ['$http', function ($http) {

    var ModelKnowledgeDetail = {};
    ModelKnowledgeDetail.getDetail = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getDetail',
			data: $.param({ngid: id})
        });
    };
    return ModelKnowledgeDetail;
}]);

cirm.controller('knowledgeCategory', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	Query(key);
	
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	
	$scope.reloadSearch = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getKnowledgeCategory',
			data: $.param({q:key})
		})
		.success(function(data){
			console.log(data.data);
			$scope.listcategory = data.data;
			$scope.status 		= data.status;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

// index knowledge
cirm.controller('knowledgeIndex', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	Query(key);
	
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	
	$scope.reloadSearch = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getKnowledgeIndex',
			data: $.param({q:key})
		})
		.success(function(data){
			//console.log(data.data);
			$scope.data = data;
			//$scope.status 		= data.status;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);
// index knowledge by categories
cirm.controller('kbaseList', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	//Query(key);
	
	
	$scope.categories = function(key){
		Query(key);
	}
	
	function Query(key){
		console.log(key);
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'knowledge/getKbaseByCategories',
			data: $.param({categories:key})
		})
		.success(function(data){
			//console.log(data.data);
			$scope.datakbase = data;
			//$scope.status 		= data.status;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);
/* end knowledge base */

/* start macro */
cirm.controller('getMacroDetail', ['$scope', '$http', function ($scope, $http) {
	
	$scope.init = function(key){
		Query(key);
	}
	
	function Query(key){		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'macro/getMacroDetail',
			data: $.param({uid:key})
		})
		.success(function(data){
			$scope.row = data;
		})
		.error(function() {
			$scope.row = "error in fetching data";
		});
	}
}]);

cirm.controller('macroData', ['$scope', '$http', function ($scope, $http) {
	
	var key	= '';
	Query(key);
	
	$scope.searchKey = function(key){
		Query(key);
	}
	
	function Query(key){		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'macro/macroData',
			data: $.param({search:key})
		})
		.success(function(data){
			$scope.row = data;
		})
		.error(function() {
			$scope.row = "error in fetching data";
		});
	}
}]);
/**
* Controls all other Ticket Pages
*/
cirm.controller('ticketPages', ['$scope', '$http', '$location', function ($scope, $http, $location) {
	
	$scope.count	= 1;
	var statusButton= true;
	var url 	 	= $location.url();
	$scope.base_url	= $location.protocol()+'://'+$location.host()+'/crm-tps';
	
	//$scope.btn_spam	= true;
	if(url == ''){
		$location.path('/Your_unsolved_tickets'); 
	}
	
	
	
	$scope.viewPage = function(page){
		
		$scope.currentPage	= page;
		if(page == 1){
			$scope.prev = true;
			$scope.next = false;
		}else if(page == $scope.last_page){
			$scope.prev = false;
			$scope.next = true;
		}else{
			$scope.prev = false;
			$scope.next = false;
		}
		var url 	 	= $location.url();
		
		$scope.count 	= $scope.currentPage;
		
		Query(url, $scope.currentPage);
	}
	
	$scope.$on('$locationChangeStart', function(event, next, current) {
		var url 	 	= $location.url();
		$scope.title 	= url.replace(/[/_]/g, " ");
		$scope.count	= 1;
		$scope.prev 	= true;
		
		var new_title 	= url.replace(/[/_]/g, " ");
		if(new_title.trim() == "Your unsolved tickets"){
			$scope.new_title 	= "Tiket Masuk";
		}else if(new_title.trim() == "Outgoing ticket"){
			$scope.new_title 	= "Tiket Keluar";
		}else if(new_title.trim() == "Recently updated tickets"){
			$scope.new_title 	= "Baru di update";
		}else if(new_title.trim() == "Request close"){
			$scope.new_title 	= "Permintaan tutup";
		}else if(new_title.trim() == "All incoming"){
			$scope.new_title 	= "Tiket masuk";
		}else if(new_title.trim() == "All outgoing"){
			$scope.new_title 	= "Tiket keluar";
		}else{
			$scope.new_title 	= "-";
		}
		

		if($scope.title.trim() == "Suspended tickets"){
			$scope.btn_spam 	= false;
			$scope.btn_inbox 	= true;
			$scope.btn_delete	= true;
		}else if( $scope.title.trim() == "Deleted tickets"){
			$scope.btn_spam 	= true;
			$scope.btn_delete	= false;
			$scope.btn_inbox	= true;
		}else{
			$scope.btn_spam		= true;
			$scope.btn_inbox	= false;
			$scope.btn_delete	= true;
		}
		
		Query(url, $scope.currentPage);
		
	});
	
	$scope.$on('profile-updated', function(event, profileObj) {
        // profileObj contains; name, country and email from emitted event
    });
	
	function Query(url, page){
		
		$scope.loading	= true;
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/showTickets',
			data: $.param({view:url, page:page})
        }).success(function(data){
			//loadMyFunction();
			$scope.data 	= data.data;
			
			$scope.row_start = data.info.row_start;
			$scope.row_end 	 = data.info.row_end;
			$scope.row_total = data.info.total_rows;
			$scope.last_page = data.info.last_page;
			if(data.info.last_page = 1){
				$scope.next = true;
			}
			
			//counter checkbox
			/*
			if($scope.data)
			{
				$scope.$watch('data', function() {
					var no = 0;
					$scope.selected	= [];
					for(var i = 0; i < $scope.data.length; i++) {
						
						if($scope.data[i].selected === true){
							$scope.selected.push($scope.data[i].id);
							no++;
						}
					}
					var statusButton = (no > 0) ? false : true;
					$scope.haveCheckboxChecked = statusButton;
					
					console.log($scope.selected);
					
				}, true);
			}
			*/
		
			$scope.loading	= false;
		}).error(function (error) {
			//loadMyFunction();
			$scope.status = 'Unable to load model data: ' + error.message;
			$scope.loading	= false;
			console.log($scope.status);
		});
	}
	
	$scope.reloadPage = function(){
		Query($location.url(),$scope.currentPage);
	}
	
	/*
	$scope.moveToSpam = function(){
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/moveToSpam',
			data: $.param({ticket_id:$scope.selected})
		}).success(function(data){
			reloadPage();
		}).error(function (error) {
		});
	}
	
	$scope.moveToTrash = function(){
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/moveToTrash',
			data: $.param({ticket_id:$scope.selected})
		}).success(function(data){
			reloadPage();
		}).error(function (error) {
		});
	}
	*/
}]);

/* MUM */
cirm.controller('ticketMumPages', ['$scope', '$http', '$location', function ($scope, $http, $location) {
	
	$scope.count	= 1;
	var statusButton= true;
	var url 	 	= $location.url();
	$scope.base_url	= $location.protocol()+'://'+$location.host()+'/';
	
	//$scope.btn_spam	= true;
	if(url == ''){
		$location.path('/Your_unsolved_tickets'); 
	}
	
	$scope.viewPage = function(page){
		
		$scope.currentPage	= page;
		if(page == 1){
			$scope.prev = true;
			$scope.next = false;
		}else if(page == $scope.last_page){
			$scope.prev = false;
			$scope.next = true;
		}else{
			$scope.prev = false;
			$scope.next = false;
		}
		var url 	 	= $location.url();
		
		$scope.count 	= $scope.currentPage;
		
		Query(url, $scope.currentPage);
	}
	
	$scope.$on('$locationChangeStart', function(event, next, current) {
		var url 	 	= $location.url();
		$scope.title 	= url.replace(/[/_]/g, " ");
		$scope.count	= 1;
		$scope.prev 	= true;
		
		if($scope.title.trim() == "Suspended tickets"){
			$scope.btn_spam 	= false;
			$scope.btn_inbox 	= true;
			$scope.btn_delete	= true;
		}else if( $scope.title.trim() == "Deleted tickets"){
			$scope.btn_spam 	= true;
			$scope.btn_delete	= false;
			$scope.btn_inbox	= true;
		}else{
			$scope.btn_spam		= true;
			$scope.btn_inbox	= false;
			$scope.btn_delete	= true;
		}
		
		Query(url, $scope.currentPage);
		
	});
	
	$scope.$on('profile-updated', function(event, profileObj) {
        // profileObj contains; name, country and email from emitted event
    });
	
	function Query(url, page){
		
		$scope.loading	= true;
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'mum/showTickets',
			data: $.param({view:url, page:page})
        }).success(function(data){
			//loadMyFunction();
			$scope.data 	= data.data;
			
			$scope.row_start = data.info.row_start;
			$scope.row_end 	 = data.info.row_end;
			$scope.row_total = data.info.total_rows;
			$scope.last_page = data.info.last_page;
			if(data.info.last_page = 1){
				$scope.next = true;
			}
			
		
			$scope.loading	= false;
		}).error(function (error) {
			//loadMyFunction();
			$scope.status = 'Unable to load model data: ' + error.message;
			$scope.loading	= false;
			console.log($scope.status);
		});
	}
	
	$scope.reloadPage = function(){
		Query($location.url(),$scope.currentPage);
	}
	
}]);
/* END MUM */

// ticket status
cirm.controller('ticket-status', ['$scope', '$http', function ($scope, $http) {
	//var key = '';
	//Query(key);
	
	$scope.clearSearch = function() {
		$scope.key = null;
		Query($scope.key);
	}
	
	$scope.ticket_type = function(key){
		Query(key);
	}
	
	function Query(key){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/getTicketStatus',
		})
		.success(function(data){
			//console.log(data.data);
			$scope.data	= data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

cirm.controller('viewLink', function ($scope, $location) {
	$scope.openPage = function(url) {
		console.log(url);
        //$location.url(site_url + url);
    };
});
cirm.controller('ticketCounter', ['$scope', '$http', function ($scope, $http) {
	
	
	$scope.reloadCounter = function(){
		Query();
	}
	
	
	function Query(){
		
		//$scope.loading	= true;
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/showTicketsCounter',
			//data: $.param({view:url})
		}).success(function(data){
			$scope.count = data;
		}).error(function (error) {
			$scope.status = 'Unable to load model data: ' + error.msg;
			console.log($scope.status);
		});	
		
	}
	
}]);

cirm.controller('ticketMUMCounter', ['$scope', '$http', function ($scope, $http) {
	
	
	$scope.reloadCounter = function(){
		Query();
	}
	
	
	function Query(){
		
		//$scope.loading	= true;
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'mum/showTicketsCounter',
			//data: $.param({view:url})
		}).success(function(data){
			$scope.count = data;
		}).error(function (error) {
			$scope.status = 'Unable to load model data: ' + error.msg;
			console.log($scope.status);
		});	
		
	}
	
}]);

cirm.controller('goClickCtrl', function($scope, $location) {
	$scope.$watch( function () { 
		return $location.path(); 
	}, function (path) {
		$scope.path = path;
	});
});


	
// Directive
cirm.directive( 'goClick', function ( $location ) {
  return function ( scope, element, attrs ) {
	var path;
	
	attrs.$observe( 'goClick', function (val) {
	  path = val;
	});
	
	element.bind( 'click', function () {
	  scope.$apply( function () {
		$location.path( path );
	  });
	});
  };
});

cirm.controller('totalTicketCategories', ['$scope', '$http', function ($scope, $http) {
	
	
	Query();
	
	$scope.reloadCounter = function(){
		Query();
	}
	
	
	function Query(){
		
		$http.post( site_url + "app/totalticketCategory","",{'headers': { 
			'X-Requested-With' :'XMLHttpRequest'
			
		}})
		.success(function(data){
			$scope.data = data.category;
			$scope.top_complaint = data.most_complaint;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
}]);

// ticket list
cirm.controller('TicketList', ['$scope', 'ModelTicketList', function ($scope, ModelTicketList) {
	
	$scope.init = function(category)
	{
		getTicket(category);
	}
	
	function getTicket(cid) {
		
		ModelTicketList.getTicket(cid)
			.success(function (data) {
				$scope.ticket = data;
				
				console.log(data);
				//$scope.name		= data.name;
				//$scope.phone	= data.phone;
				//$scope.status	= data.status;
				//$scope.id		= data.id;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);
cirm.factory('ModelTicketList', ['$http', function ($http) {
	
    var ModelTicketList = {};
    ModelTicketList.getTicket = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/ticket_list',
			data: $.param({cid: id})
        });
    };
    return ModelTicketList;
}]);

// ticket history 
cirm.controller('ticketHistory', ['$scope', 'ModelTicketHistoryList', function ($scope, ModelTicketHistoryList) {
	$scope.init = function(user){
		getTicketHistory(user);	
	}
	function getTicketHistory(cid) {	
		ModelTicketHistoryList.getTicketHistory(cid)
			.success(function (data) {
				$scope.ticket = data;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);

cirm.factory('ModelTicketHistoryList', ['$http', function ($http) {
    var ModelTicketHistoryList = {};
    ModelTicketHistoryList.getTicketHistory = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/ticket_history',
			data: $.param({cid: id})
        });
    };
    return ModelTicketHistoryList;
}]);

// contact history
cirm.controller('contactHistory', ['$scope', 'ModelContactHistoryList', function ($scope, ModelContactHistoryList) {
	$scope.init = function(user){
		getContactsHistory(user);	
	}
	function getContactsHistory(cid) {	
		ModelContactHistoryList.getContactsHistory(cid)
			.success(function (data) {
				$scope.ticket = data;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);

cirm.factory('ModelContactHistoryList', ['$http', function ($http) {
    var ModelContactHistoryList = {};
    ModelContactHistoryList.getContactsHistory = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/ticket_history',
			data: $.param({cid: id})
        });
    };
    return ModelContactHistoryList;
}]);

//
cirm.controller('statusData', ['$scope', function($scope) {
   $scope.data = {
		model: null,
		availableOptions: [
			{id: '1', name: 'Active'},
			{id: '0', name: 'Not Active'}
		]
	};
	
	$scope.forceUnknownOption = function() {
		$scope.data.availableOptions = 'Not Active';
	};
	
	$scope.getContactStatus = function(status) {
		var filteredBank = $filter('filter')($scope.data.availableOptions[0], status);
		return filteredBank[0].name;
	};
}]);

cirm.controller('priorityData', ['$scope', function($scope) {
   $scope.data = {
		model: null,
		availableOptions: [
			{id: '5', name: '-'},
			{id: '4', name: 'Low'},
			{id: '3', name: 'Medium'},
			{id: '2', name: 'High'},
			{id: '1', name: 'Urgent'}
		]
	};
	
	$scope.forceUnknownOption = function() {
		$scope.data.availableOptions = 'Low';
	};
	
	$scope.getContactStatus = function(status) {
		var filteredBank = $filter('filter')($scope.data.availableOptions[0], status);
		return filteredBank[0].name;
	};
	//$scope.selectedOption = $scope.options[1];
}]);

cirm.controller('ticketStatusData', ['$scope', '$http', function ($scope, $http) {
	$http.post( site_url + "app/getticketStatus","",{'headers': { 
		'X-Requested-With' :'XMLHttpRequest'
	}})
	.success(function(data){
		$scope.data = data;
	})
	.error(function() {
		$scope.data = "error in fetching data";
	});
}]);

cirm.controller('accountType', ['$scope', function($scope) {
   $scope.data = {
		model: null,
		availableOptions: [
			{id: '1', name: 'Customer'},
			{id: '2', name: 'Investor'},
			{id: '3', name: 'Partner'},
			{id: '4', name: 'Reseller'}
		]
	};
}]);

cirm.controller('industryType', ['$scope', function($scope) {
   $scope.data = {
		model: null,
		availableOptions: [
			{id: '1', name: 'Apparel'},
			{id: '2', name: 'Banking'},
			{id: '3', name: 'Computer Software'},
			{id: '4', name: 'Education'},
			{id: '5', name: 'Electronic'},
			{id: '6', name: 'Finance'},
			{id: '7', name: 'Insurance'}
		]
	};
}]);
// Load Data User
cirm.controller('userDataForm', ['$scope', 'UserFactory', function ($scope, UserFactory) {	
	$scope.init = function(id){
		$scope.cid = id;
		getUsers(id);
	}
	
	function getUsers(cid) {
		
		UserFactory.getUsers(cid)
			.success(function (data) {
				$scope.username		= data.username;
				$scope.is_admin		= data.is_admin;
				$scope.role			= data.role;
				$scope.status		= data.status;
				$scope.fullname		= data.fullname;
				$scope.id			= data.id;
				$scope.email		= data.email;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				//console.log($scope.status);
				//UserFactory
			});
	}
}]);
cirm.factory('UserFactory', ['$http', function ($http) {
    var UserFactory = {};
    UserFactory.getUsers = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/getUser',
			data: $.param({cid: id})
        });
    };
    return UserFactory;
}]);
// Role Data 
cirm.controller('roleDataForm', ['$scope','$location', 'RoleFactory', function ($scope, $location, RoleFactory) {	
	$scope.init = function(id){
		getRole(id);
	}
	function getRole(cid) {
		
		RoleFactory.getRole(cid)
			.success(function (data) {
				$scope.name		= data.name;
				$scope.status	= data.status;
				$scope.id		= data.id;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);
cirm.factory('RoleFactory', ['$http', function ($http) {

    var RoleFactory = {};
    RoleFactory.getRole = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/getRoleData',
			data: $.param({cid: id})
        });
    };
    return RoleFactory;
}]);


//var fetch = angular.module('fetch', []);
// User Role
cirm.controller('userRole', ['$scope', '$http', function ($scope, $http) {
	$http.post( site_url + "app/getUserRole","",{'headers': { 
		'X-Requested-With' :'XMLHttpRequest'
	}})
	.success(function(data){
		$scope.data = data;
	})
	.error(function() {
		$scope.data = "error in fetching data";
	});
}]);

/* Area */
cirm.controller('generatePropinsi', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
	$http.post( site_url + "general/getProvince","",{'headers': { 
		'X-Requested-With' :'XMLHttpRequest'
	}})
	.success(function(data){
		$scope.data = data;
	})
	.error(function() {
		$scope.data = "error in fetching data";
	});
	
	
}]);

cirm.controller('generateKota', ['$scope', '$http', function ($scope, $http) {
	
	$scope.getKota = function(key){
		console.log(key);
		QueryKota(key);
	}
	
	function QueryKota(key)
	{
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'general/getCity',
			data: $.param({prov: key})
        }).success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
	
}]);
cirm.controller('generateArea', ['$scope', '$http', function ($scope, $http) {
	
	//Query(0);
	/*
	$scope.getAllArea	= function(level){
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'general/generateArea',
			data: $.param({level:level})
		})
		.success(function(data){
			$scope.dAllArea = data;
		});
	}
	*/
	$scope.getPropinsi	= function(level){
		Query(level);
	}
	
	$scope.getKota	= function(level){
		Query(level);
	}
	
	$scope.getKecamatan	= function(level){
		Query(level);
	}
	
	$scope.getKelurahan	= function(level){
		Query(level);
	}
	
	function Query(level){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'general/generateArea',
			data: $.param({level:level})
		}).success(function(data){
			$scope.data = data;
			//return data;
			//console.log(data);
		});
	}
	
}]);
/* End Area */
// Ticket Category
cirm.controller('ticketCategory', ['$scope', '$http', function ($scope, $http) {
	
	Query();
	
	$scope.getContactReason	= function(category){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/getContactReason',
			data: $.param({cid:category})
		})
		.success(function(data){
			$scope.dContact = data;
			//return data;
		});
	}
	
	function Query(){
		$http.post( site_url + "app/ticketCategory","",{'headers': { 
			'X-Requested-With' :'XMLHttpRequest'
		}})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
	
}]);

// MUM Ticket Category
cirm.controller('ticketMumCategory', ['$scope', '$http', function ($scope, $http) {
	
	Query();
	
	$scope.getContactReason	= function(category){
		
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/getContactReason',
			data: $.param({cid:category})
		})
		.success(function(data){
			$scope.dContact = data;
			//return data;
		});
		
	}
	
	function Query(){
		
		$http.post( site_url + "app/ticketMumCategory","",{'headers': { 
			'X-Requested-With' :'XMLHttpRequest'
		}})
		.success(function(data){
			$scope.data = data;
		})
		.error(function() {
			$scope.data = "error in fetching data";
		});
	}
	
	
}]);

// Load Data Ticket Form
cirm.controller('ticketDataForm', ['$scope', 'ModelCategoryTicket','ModelMediaTicket', function ($scope, ModelCategoryTicket, ModelMediaTicket) {
	$scope.init = function(id)
	{
		getDetailTicket(id);
	}
	$scope.openFile = function(id) {
		getDetailData(id);
	};

	function getDetailData(id){
		ModelMediaTicket.getDetailData(id)
		.success(function (data){
			SaveToDisk(data.path, data.name);
		})
		.error(function (error){
			$scope.status = 'Unable to load model data: ' + error.message;
		})
	}

	function getDetailTicket(id){
		ModelCategoryTicket.getDetailTicket(id)
			.success(function (data) {
				
				
				
				$scope.row				= data;
				//$scope.priority			= data.data.priority;
				$scope.subject			= data.data.subject;
				$scope.no_ticket		= data.data.no_ticket;
				$scope.produk 			= data.data.produk;
				$scope.tujuan_pengaduan	= data.data.tujuan_pengaduan;
				$scope.asal_pengaduan	= data.data.asal_pengaduan;
				$scope.tgl_entry		= data.data.tgl_entry;
				
				$scope.complaint		= data.data.complaint;
				$scope.category			= data.data.category;
				$scope.status			= data.data.status;
				$scope.id				= data.data.id;
				$scope.tags				= data.data.tags;
				$scope.date_new			= data.data.new_date;
				$scope.date_ago			= data.data.date_ago;
				
				$scope.class_first 		= data.data.class_first;

				$scope.contact_id		= data.data.contact_id;
				$scope.contact_name		= data.data.contact_name;
				$scope.address			= data.data.address;
				$scope.phone_number		= data.data.phone_number;
				$scope.user_create		= data.data.username_title;
				$scope.user_type 		= data.data.user_type;
				$scope.username 		= data.data.user_cch;
				$scope.foto				= data.data.avatar;
				
				$scope.assignee_id		= data.data.assignee;
				//$scope.assignee_name	= data.data.assignee_name;
				//$scope.assignee_val 	= data.data.assignee_val;
				//$scope.assignee_text 	= data.data.assignee_text;
				
				//$scope.assignee_opt 	= {id:data.assignee_val, text:data.assignee_text};
				//$scope.assignee_opt 	= data.data.assignee_opt;
				$scope.awb 				= (data.data.awb != null) ? data.data.awb : "INFO";
				$scope.sender 			= data.data.sender;
				$scope.receiver 		= data.data.receiver;
				$scope.notes 			= data.data.notes;

				$scope.info_channel 	= data.data.info_channel;
				$scope.total_file 		= data.data.total_file;

				$scope.media 			= data.media;
				$scope.history 			= data.history;
				
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				//console.log($scope.status);
			});
	}
}]);

cirm.factory('ModelCategoryTicket', ['$http', function ($http) {
    var ModelCategoryTicket = {};
    ModelCategoryTicket.getDetailTicket = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/ticketDetail',
			data: $.param({cid: id})
        });
    };
    return ModelCategoryTicket;
}]);

cirm.factory('ModelMediaTicket', ['$http', function ($http) {
    var ModelMediaTicket = {};
    ModelMediaTicket.getDetailData = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'app/download_media',
			data: $.param({mid: id})
        });
    };
    return ModelMediaTicket;
}]);

cirm.controller('ticketResponse', ['$scope','$sce', 'ModelTicketResponse', function ($scope,$sce, ModelTicketResponse) {
	$scope.init = function(id)
	{
		getTicketResponse(id);
	}
	function getTicketResponse(id){
		ModelTicketResponse.getTicketResponse(id)
			.success(function (data) {
				$scope.ticket 		 	= data.ticket;
				//$scope.total_file 		= data.ticket.total_file;
				$scope.mediaresponse 	= data.media;
				//$scope.htmlResponse		= $sce.trustAsHtml(data.ticket.response);
				//console.log($scope.htmlResponse);
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
			});
	}
}]);
cirm.factory('ModelTicketResponse', ['$http', function ($http) {

    var ModelTicketResponse = {};
    ModelTicketResponse.getTicketResponse = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'ticket/ticketResponse',
			data: $.param({tid: id})
        });
    };
    return ModelTicketResponse;
}]);

// Account Type
cirm.controller('dbCtrlAccountType', ['$scope', '$http', function ($scope, $http) {
	$http.post( site_url + "app/accountType","",{'headers': { 
		'X-Requested-With' :'XMLHttpRequest'
	}})
	.success(function(data){
		$scope.data = data;
		//alert(data);
	})
	.error(function() {
		$scope.data = "error in fetching data";
	});
}]);


cirm.controller('dbCtrlIndustryType', ['$scope', '$http', function ($scope, $http) {
	$http.post( site_url + "app/industryType","",{'headers': { 
		'X-Requested-With' :'XMLHttpRequest'
	}})
	.success(function(data){
		$scope.data = data;
		//alert(data);
	})
	.error(function() {
		$scope.data = "error in fetching data";
	});
}]);

// SHOW HIDE 
cirm.controller('formReplyCont', function($scope) {
	//$scope.formReply = false;
	$scope.ShowHide = function () {
		//If DIV is visible it will be hidden and vice versa.
		$scope.formReply = $scope.formReply ? false : true;
	}
});



cirm.directive('fileModel', ['$parse', function ($parse) {
	return {
	   restrict: 'A',
	   link: function(scope, element, attrs) {
		  var model = $parse(attrs.fileModel);
		  var modelSetter = model.assign;
		  
		  element.bind('change', function(){
			 scope.$apply(function(){
				modelSetter(scope, element[0].files[0]);
			 });
		  });
	   }
	};
 }]);


cirm.service('fileUpload', ['$http', function ($http) {
	
	this.uploadFileToUrl = function(file, uploadUrl){
		var fd = new FormData();
		fd.append('csv', file);
		
		return $http.post(site_url + 'contacts/do_upload_csv', fd, {
			transformRequest: angular.identity,
			headers: {'Content-Type': undefined}
		});
	}
 }]);
      
 cirm.controller('doUploadCsv', ['$scope', 'fileUpload', function($scope, fileUpload){
	
	$scope.doUpload = function(){
		$scope.loading	= true;
		$scope.buttonDisabled = true;
		var file = $scope.csv_file;
	   
		var uploadUrl = "/fileUpload";
		fileUpload.uploadFileToUrl(file, uploadUrl).success(function(result) {
			//$scope.result = result //or whatever else.
			//var css_class		= (result.status == 'OK') ? 'success' : 'danger';
			
			$scope.ResultMsg		= (result.status == 'OK') ? 'Total Data : '+result.total.all+', Duplicated : '+result.total.duplicate+', Success : '+result.total.success+', Failed : '+result.total.failed+' ' : result.error;
			$scope.css_class		= (result.status == 'OK') ? 'success' : 'danger';
			$scope.loading			= false;
			$scope.buttonDisabled 	= false;
			//console.log(result);
		});
		
	   //alert(result)
	};
 }]);
 
 
 cirm.directive('loading', function () {
  return {
	restrict: 'E',
	replace:true,
	template: '<i class="fa fa-spinner fa-spin"></i>',
	link: function (scope, element, attr) {
		  scope.$watch('loading', function (val) {
			  if (val)
				  $(element).show();
			  else
				  $(element).hide();
		  });
	}
  }
})

 cirm.directive('delcategories', function () {
  return {
	restrict: 'A',
	link: function(scope, element, attrs) {
            element.bind('click', function() {
				
				bootbox.confirm({
					message: "Are you sure want to delete this category ?",
					callback: function(result) {
						if(result){
							scope.loading	= true;
							$.post( site_url + 'general/remove_categories',{id:attrs.id}, function(data){
								if(data.status == 'OK'){
									bootbox.dialog({
										message: "Successfuly remove category data",
										title: "Notification",
										buttons: {
											success: {
												label: "OK",
												className: "btn-success",
												callback: function() {
													angular.element($("#tableList")).scope().clearSearch();
													angular.element($("#categories")).scope().clearSearch();
													scope.loading	= false;
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
        }
  }
})
cirm.directive('ecategories', function () {
  return {
	restrict: 'A',
	link: function(scope, element, attrs) {
            element.bind('click', function() {
				$('#'+attrs.id).editable({
					validate: function(value) {
						if($.trim(value) == '') return 'This field is required';
					},
					pk:attrs.id,
					ajaxOptions:{
						type:'post'
					},
					url: site_url + 'general/update_categories',
					success: function(response, newValue) {
						angular.element($("#tableList")).scope().clearSearch();
						angular.element($("#categories")).scope().clearSearch();
													
						if(!response.success) 
							return response.msg;
					}
				});
            });
        }
  }
})

cirm.directive('loadsummernote', function(){
	
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
				
				
				/*
				if( scope.row != undefined ){
					console.log(scope.row.detail);
				}
				*/
				
				//element.summernote('insertText',scope.row.detail);
				
			}
	}
});

// START //
// Contact Type 
cirm.controller('contacts', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	Query(key);
	
	$scope.reloadSearch = function(key){
		Query(key);
	}
	
	function Query(key){
		$scope.loading = true;
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'contacts/contact_type',
			data: $.param({q:key})
		})
		.success(function(data){
			//console.log(data.data);
			$scope.row 		= data;
			$scope.status 	= "OK";
			$scope.loading 	= false;
		})
		.error(function() {
			$scope.status 	= "error in fetching data";
			$scope.loading 	= false;
		});
	}
}]);

cirm.controller('contacts_type', ['$scope', '$http', function ($scope, $http) {
	var key = '';
	Query(key);
	
	
	function Query(key){
		$scope.loading = true;
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'contacts/contact_type',
			data: $.param({q:key})
		})
		.success(function(data){
			//console.log(data.data);
			$scope.option	= data;
			$scope.status 	= "OK";
			$scope.loading 	= false;
		})
		.error(function() {
			$scope.status 	= "error in fetching data";
			$scope.loading 	= false;
		});
	}
}]);

// Contact Detail Form //
cirm.controller('contactsDataForm', ['$scope', '$http', '$location', function ($scope, $http, $location) {
	
	$scope.init = function(id){
		$scope.cid = id;
		Query(id);
	}
	
	$scope.viewForm = function(state){
		var newState = state == true ? false : true;
		$scope.fedit = newState;
	}
	
	function Query(key){
		$scope.loading = true;
		$http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'contacts/form_data',
			data: $.param({cid: key})
		})
		.success(function(data){
			$scope.row		= data.data;
			$scope.source	= data.source;
			$scope.status 	= "OK";
			$scope.loading 	= false;
			$scope.fedit	= true;
		})
		.error(function() {
			$scope.status 	= "error in fetching data";
			$scope.loading 	= false;
		});
	}
}]);

// Load Data Contacts Form //
/*
cirm.controller('contactsDataForm', ['$scope', '$http', 'ModelFactory', function ($scope, $http, ModelFactory) {	
	
	$scope.init = function(id){
		$scope.cid = id;
		getModels(id);
	}
	//$scope.fedit 	= true;
	$scope.viewForm = function(state){
		var newState = state == true ? false : true;
		$scope.fedit = newState;
	}
	
	function getModels(cid) {
		$scope.loading = true;
		ModelFactory.GetModels(cid)
			.success(function (data) {
				$scope.loading 	= false;
				$scope.row		= data.data;
				$scope.status	= data.status;
				$scope.fedit	= true;
			})
			.error(function (error) {
				$scope.status = 'Unable to load model data: ' + error.message;
				console.log($scope.status);
				$scope.loading = false;
			});
	}
}]);
cirm.factory('ModelFactory', ['$http', function ($http) {

    var ModelFactory = {};
    ModelFactory.GetModels = function (id) {
		return $http({
			headers : {
				'X-Requested-With' :'XMLHttpRequest',
				'Content-Type':'application/x-www-form-urlencoded'
			},
			method: 'POST',
			url: site_url + 'contacts/getContactsDetail',
			data: $.param({cid: id})
        });
    };
    return ModelFactory;
}]);
*/