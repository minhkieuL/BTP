/* ADMIN */
var app = angular.module('admin', []);

//~ console.log("Hey !");
//~ angular.module('ng').filter('tel', function (){});
app.controller("adminCtrl", function($scope,$http, $window) {
	
	
	//~ console.log("Hey !");
	
	
	function DatetoSQL(dateObj) {
		var month = '' + (dateObj.getMonth() + 1),
			day = '' + dateObj.getDate(),
			year = dateObj.getFullYear();

		if (month.length < 2) month = '0' + month;
		if (day.length < 2) day = '0' + day;

		return [year, month, day].join('-');
	}
	
	
	
	
	/* -------------------------------------------------------------------------------------------------------USER--------------------------------------------------------------------------------------------------------------- */
	$scope.userSearch= "";
	$scope.userModalOpened= false;
	$scope.newUser = {nom:"", prenom:"", mail:"", type:10};
	$scope.classementUsers = 2;
	$scope.classementAscendantUsers = 1;
	$scope.departUser = 0;
	$scope.nbUserParPage = 15;
	
	$scope.selectAllUsers = function () {
		console.log("selectAllUsers");
		for ( var i = 0 ; i<$scope.users.length ; i++ ) {
			var u = $scope.users[i];
			// on le coche
			u.selected = true;
		}
	}
		$scope.unselectAllUsers= function () {
		console.log("unselectAllUsers");
		for ( var i = 0 ; i<$scope.users.length ; i++ ) {
			var u = $scope.users[i];
			// on le decoche
			u.selected = false;
		}
	}
	
	$scope.choiceSelectAllUsers= function () {
		var selectedList = [];
		for ( var i = 0 ; i<$scope.users.length ; i++ ) {
			var u = $scope.users[i];
			if(u.selected == true){
				selectedList.push(u);
			}
		}
		if(selectedList.length == $scope.users.length){
			$scope.unselectAllUsers();
		}else{
			$scope.selectAllUsers();
		}
	}
	
	$scope.getUsers = function () {
		//~ console.log("deparUser -> ", $scope.departUser);
		//~ console.log('asc :',$scope.classementAscendantUsers);
		console.log('getusers');
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_liste_users",{classement: $scope.classementUsers, ascendant: $scope.classementAscendantUsers, depart: $scope.departUser, userParPage: $scope.nbUserParPage},{})
		.success(function (dataFromServer) {
			$scope.users = dataFromServer.users;
			$scope.typeCompte = dataFromServer.typeCompte;
			$scope.colonne = dataFromServer.colonne;
			$scope.nbUsers = dataFromServer.nbUsers;
			//~ console.log("user par page",$scope.nbUserParPage);
			//~ console.log(dataFromServer);
		});
		
	}
	$scope.changeClassementUsers = function (c){
		$scope.classementUsers = c.num_col;
		$scope.classementAscendantUsers = 1;
		$scope.getUsers();
	}
	
	$scope.changeAscUsers = function (){
		if($scope.classementAscendantUsers == 1){
			$scope.classementAscendantUsers = 0;
		}
		$scope.getUsers();
		console.log('asc :',$scope.classementAscendantUsers);
	}
	$scope.changeDescUsers = function (){
		if($scope.classementAscendantUsers == 0){
			$scope.classementAscendatUsers = 1;
		}
		$scope.getUsers();
		console.log('asc :',$scope.classementAscendantUsers);
	}
	
	
	$scope.nextPageUser = function (){
		if(($scope.departUser+$scope.nbUserParPage)<=$scope.nbUsers){
			$scope.departUser = $scope.departUser + $scope.nbUserParPage;
			
			$scope.getUsers();
		}
			//~ console.log("nextpage -> departUser :",$scope.departUser);
	}
	
	$scope.previousPageUser = function (){
		if(($scope.departUser-$scope.nbUserParPage)>=0){
			$scope.departUser = $scope.departUser - $scope.nbUserParPage;
			$scope.getUsers();
		}
		//~ console.log("previousPage -> departUser :",$scope.departUser);
	}
	
	
	$scope.saveUser = function (u){
		console.log("saveUser", u);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_users",{action:"edit", users: [u]},{})
		.success(function (dataFromServer) {
			console.log("modif ok", dataFromServer);
			$scope.getUsers();
		});
		
	}
		
	$scope.delUser = function () {
		console.log("delUser");
		var listDelUsers=[];
		for ( var i = 0 ; i<$scope.users.length ; i++ ) {
			var u = $scope.users[i];
			if(u.selected){
				listDelUsers.push(u);
				}
		}
		console.log('listDelUsers', listDelUsers);
		
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_users",{action:"del", users: listDelUsers},{})
		.success(function (dataFromServer) {
			console.log("Delete ok", dataFromServer);
			$scope.getUsers();
		});
	}
	
	$scope.addUser = function () {
		console.log("addUser");
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_users",{action:"add", users: [$scope.newUser]},{})
		.success(function (dataFromServer) {
			console.log("Add ok");
			$scope.getUsers();
			$scope.closeUserModal();
		});
	}
	
	$scope.openUserModal = function () {
		$scope.userModalOpened= true;
	}
	
	$scope.closeUserModal = function (){
		$scope.userModalOpened= false;
	}
	

	
	/*------------------------------------------------------------------------------------------------ARTICLE--------------------------------------------------------------------------------------------------------------------*/

	$scope.articleSearch= "";
	$scope.articleModalOpened= false;
	$scope.newArticle = {design:"", etat:'', dernierEntretien:"", derniereReparation:"", categorie:1, base:null, rep:null};
	$scope.currentZoomArticle = null; // référence de l'article sur lequel on fait le détail de l'historique
	$scope.currentZoomLoading = false;
	$scope.articleFiltreCateg = 0;
	$scope.articleDuplicModalOpened = false;
	$scope.currentDuplicArticle = null; //reférence de l'article duquel on duplique ses propriétées
	$scope.erreurDup = null;
	$scope.classementArticle = 2;
	$scope.classementAscendantArticles = 1;
	$scope.departArticle = 0;
	$scope.nbArticleParPage = 12;
	$scope.articleSuivi = {etat:null};
	$scope.showButtonPerdu = true;
	
	$scope.enterSearch = function () {
		$scope.getArticles();
	}
	
	$scope.changeArticleFiltreCateg= function (categ){
		$scope.articleFiltreCateg = categ;
		$scope.getArticles();
	}
	
	$scope.selectAllArticles = function () {
		console.log("selectAllArticles");
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var a = $scope.articles[i];
			// on le coche
			a.selected = true;
		}
	}
	
	$scope.unselectAllArticles= function () {
		console.log("unselectAllArticles");
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var a = $scope.articles[i];
			// on le decoche
			a.selected = false;
		}
	}
	
	$scope.choiceSelectAllArticles= function () {
		var selectedList = [];
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var a = $scope.articles[i];
			if(a.selected == true){
				selectedList.push(a);
			}
		}
		if(selectedList.length == $scope.articles.length){
			$scope.unselectAllArticles();
		}else{
			$scope.selectAllArticles();
		}
	}
	
	
	
	$scope.getArticles = function () {
		//~ console.log("getArticles", {refCateg: $scope.articleFiltreCateg, classement: $scope.classementArticle});
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_liste_articles",{refCateg: $scope.articleFiltreCateg, classement: $scope.classementArticle, ascendant: $scope.classementAscendantArticles, depart: $scope.departArticle, articleParPage: $scope.nbArticleParPage},{})
		.success(function (dataFromServer) {
			//~ console.log('ascendant article :',$scope.classementAscendantArticles);
			//~ console.log('colonne article :',$scope.classementArticle);
			$scope.articles = [];
			for( var i = 0 ; i<dataFromServer.articles.length ; i++){
				var a = dataFromServer.articles[i];
				a.dernierEntretienDate =( a.dernierEntretien ? new Date(a.dernierEntretien) : new Date()  );
				a.derniereRepDate = ( a.derniereRep ? new Date(a.derniereRep) : new Date()  );
				$scope.articles.push(a);
				a.dernierEntretienShowButton = true;
				a.derniereRepShowButton = true;
				$scope.nbUsers = dataFromServer.nbUsers;
				
			}
			
			//~ $scope.articles = dataFromServer.articles;
			if(!$scope.articleFiltreCateg){
				$scope.categorie = dataFromServer.categorie;
				$scope.etat = dataFromServer.etat;
				$scope.categorieParent = dataFromServer.categorieParent;
				$scope.emplacement = dataFromServer.emplacement;
				$scope.colonne = dataFromServer.colonne;
				$scope.nbArticles = dataFromServer.nbArticles;
			}
			$scope.article = dataFromServer.articles;
			$scope.showButtonPerdu = true;
		});
		
	}
	
	/********fonction qui liste les éléments perdus**********/
	
	$scope.takeArticlesPerdus = function(){
		$scope.articles = [];
		$scope.varArticlesPerdus = true;
		for( var i = 0 ; i<$scope.article.length ; i++){
				var a = $scope.article[i];
				if(a.suivi.refEmp==31){
					$scope.articles.push(a);
				}				
			}
		console.log('lostArticle', $scope.articles);
		$scope.showButtonPerdu = false;
		return $scope.articles;
	}
	
	$scope.closePerdu = function(){
		$scope.showButtonPerdu = true;
	}
	
	
	$scope.changeAscArticles = function (){
		if($scope.classementAscendantArticles == 1){
			$scope.classementAscendantArticles = 0;
		}else{
			$scope.classementAscendantArticles = 1;
		}
		
		$scope.getArticles();
		
	}
	
	$scope.nextPageArticle = function (){
		if(($scope.departArticle+$scope.nbArticleParPage)<=$scope.nbArticles){
			$scope.departArticle = $scope.departArticle + $scope.nbArticleParPage;
			
			$scope.getArticles();
		}
		console.log("nextpage -> departArticle :",$scope.departArticle);
		console.log("nextpage -> nbArticleParPage :",$scope.nbArticleParPage);
		console.log("nextpage -> nbArticles :",$scope.nbArticles);
	}
	
	$scope.previousPageArticle = function (){
		if(($scope.departArticle-$scope.nbArticleParPage)>=0){
			$scope.departArticle = $scope.departArticle - $scope.nbArticleParPage;
			$scope.getArticles();
		}console.log("previousPage -> departArticle :",$scope.departArticle);
	}
	
	$scope.changeClassementArticle = function (c){
		$scope.classementArticle =c.num_col ;
		console.log(c.num_col);
		$scope.getArticles();
		
		
	}
	
	$scope.saveArticle = function (a){
	
		
		if ( !a.dernierEntretienShowButton ) a.dernierEntretien = DatetoSQL(a.dernierEntretienDate);
		if ( !a.derniereRepShowButton ) a.derniereRep= DatetoSQL(a.derniereRepDate);
		console.log("saveArticle", a);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_articles",{action:"edit", articles: [a]},{})
		.success(function (dataFromServer) {
			console.log("modif ok", dataFromServer);
			$scope.getArticles();
		});
		
	}
		
	$scope.delArticle = function () {
		console.log("delArticle");
		var listDelArticles=[];
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var a = $scope.articles[i];
			if(a.selected){
				listDelArticles.push(a);
				}
		}
		console.log('listDelArticles', listDelArticles);
		
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_articles",{action:"del", articles: listDelArticles},{})
		.success(function (dataFromServer) {
			console.log("Delete ok", dataFromServer);
			$scope.getArticles();
		});
	}
	
	$scope.addArticle = function () {
		console.log("addArticle");
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var b = $scope.articles[i];
			var c = null;
			if(b.design.toUpperCase().sansAccent() == $scope.newArticle.design.toUpperCase().sansAccent()){
				c = 1;
				console.log(b.design.toUpperCase().sansAccent(),'=', $scope.newArticle.design.toUpperCase().sansAccent());
				console.log("erreur de clonage de designation");
				console.log(c);
				break;
				}
		}
		if(c==1){
			console.log("ERROR : clonage de designation");
			$scope.erreurDup = 'Erreur de clonage de désignation';
			c = null;
		}else{
			if(!$scope.newArticle.base){
				console.log("ERROR : pas d'emplacement de base de renseigné");
				$scope.erreurBase = "pas d'emplacement de base de renseigné";
			}else{
				$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_articles",{action:"add", articles: [$scope.newArticle]},{})
				.success(function (dataFromServer) {
					console.log("Add ok", dataFromServer);
					$scope.getArticles();
					$scope.closeArticleModal();
				});
			}
		}
		
	}
	
	/*méthode pour renvoyer une chaine de caractère sans accent*/
	String.prototype.sansAccent = function(){
		var accent = [
			/[\300-\306]/g, /[\340-\346]/g, // A, a
			/[\310-\313]/g, /[\350-\353]/g, // E, e
			/[\314-\317]/g, /[\354-\357]/g, // I, i
			/[\322-\330]/g, /[\362-\370]/g, // O, o
			/[\331-\334]/g, /[\371-\374]/g, // U, u
			/[\321]/g, /[\361]/g, // N, n
			/[\307]/g, /[\347]/g, // C, c
		];
		var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];
		 
		var str = this;
		for(var i = 0; i < accent.length; i++){
			str = str.replace(accent[i], noaccent[i]);
		}
		 
		return str;
	}	
	/***************************************************************/
	
	$scope.duplicArticle = function (a) {
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var b = $scope.articles[i];
			var c = null;
			if(b.design.toUpperCase().sansAccent() == $scope.newArticle.design.toUpperCase().sansAccent()){
				c = 1;
				console.log(b.design.toUpperCase().sansAccent(),'=', $scope.newArticle.design.toUpperCase().sansAccent());
				console.log("erreur de clonage de designation");
				console.log(c);
				break;
				}
		}
		if(c==1){
			console.log("ERROR : clonage de designation");
			$scope.erreurDup = 'Erreur de clonage de désignation';
			c = null;
		}else{
			$http.post("/?type=json&route=action_articles",{action:"duplic", articles: [$scope.newArticle]},{})
			.success(function (dataFromServer) {
				console.log("duplication ok", dataFromServer);
				$scope.getArticles();
				$scope.erreurDup = null;
				$scope.closeDuplicArticleModal();
				
			});
		}
	}
	/*****************************************************************SuiviArticle**********************************************************/
	
	
	$scope.getSuiviArticle= function(a) {
		$scope.currentZoomLoading = true;
		if(a)$scope.currentZoomArticle = a;
		// Récupération en JSON des données de suivi de l'article a
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_precision_articles",{"refArt":$scope.currentZoomArticle.ref, depart: $scope.departSuivi, suiviParPage: $scope.nbSuiviParPage },{})
		.success(function (dataFromServer) {
			if(dataFromServer.suivi.length)$scope.currentZoomArticle.suivi = dataFromServer.suivi;
			console.log(dataFromServer);
			//~ $scope.nbSuivi = $scope.currentZoomArticle.suivi.length;
			$scope.currentZoomLoading = false;
		});
	
	}
	
	/*********Pagination module de suivi *********/
	
	$scope.previousPageSuivi = function (){
		if(($scope.departSuivi-$scope.nbSuiviParPage)>=0){
			$scope.departSuivi = $scope.departSuivi - $scope.nbSuiviParPage;
			$scope.getSuiviArticle(null);
		}console.log("previousPage -> departSuivi :",$scope.departSuivi);
	}
	$scope.nextPageSuivi = function (){
		if($scope.currentZoomArticle.suivi.length==$scope.nbSuiviParPage){
			$scope.departSuivi = $scope.departSuivi + $scope.nbSuiviParPage;
			
			$scope.getSuiviArticle(null);
		}
		console.log("nextpage -> departSuivi :",$scope.departSuivi);
		console.log("nextpage -> nbSuiviParPage :",$scope.nbSuiviParPage);
	}
	

	/*********Met à jour le suivi lors qu'il y a un changement d'état *********/
	
	$scope.updateSuiviArticle = function(a, etat, refEmp) {
		console.log('updateSuivi');
		console.log('refEmp', refEmp);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_articles",{action:"updateSuivi", articles: [a], etat: etat, refEmp: refEmp},{})
		.success(function (dataFromServer) {
			console.log('dataUpdated');
			console.log(dataFromServer);
			$scope.getArticles();
		});
	}
	
	$scope.openArticleModal = function () {
		$scope.articleModalOpened= true;
	}
	
	$scope.closeArticleModal = function (){
		$scope.articleModalOpened= false;
	}
	$scope.openArticleZoomModal = function () {
		$scope.articleZoomModalOpened = true;
		$scope.departSuivi = 0;
		$scope.nbSuiviParPage = 5;
	}
	
	$scope.closeArticleZoomModal = function (){
		$scope.articleZoomModalOpened= false;
		if(!$scope.varArticlesPerdus){
			$scope.getArticles();
		}
	}
	
	$scope.openDuplicArticleModal = function (a) {
		$scope.articleDuplicModalOpened = true;
		$scope.erreurDup = null;
		$scope.currentDuplicArticle = a;
		$scope.newArticle ={design:"", etat:a.etat, dernierEntretien:"", derniereReparation:"", categorie:a.categorie, base:a.base, rep:a.rep};
	}
	
	$scope.closeDuplicArticleModal = function (){
		$scope.articleDuplicModalOpened = false;
		$scope.erreurDup = null;
	}
	

	
	
	/* ------------------------------------------------------------------------------------------------CATEGORIE-----------------------------------------------------------------------------------------------------------------*/
	
	$scope.currentCateg = null;
	$scope.currentSousCateg = null;
	$scope.currentEditCateg = {nom:'', ref:0, type:0};
	$scope.newCateg = {nom:'', ref: 0, refParent:null, type:0};
	$scope.newCategP = {nom:'', ref: 0, refParent:null, type:0};
	$scope.articleFiltreParent = 0;
	$scope.supCategModalOpened= false;
	
	$scope.getCateg = function () {
		console.log("getCategories");
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_liste_categories",{},{})
		.success(function (dataFromServer) {
			$scope.categorieParent = dataFromServer.categorieParent;
		});
	}
	
	$scope.getSousCateg = function (cp) {
		$scope.currentCateg = cp;
		$scope.currentEditCateg=  {nom: cp.nom, ref: cp.ref, type:1};
		
		console.log($scope.newCateg);
	}
	$scope.newCateg = function () {
		$scope.currentSousCateg = null;
		$scope.currentEditCateg=  {nom: 'Nouvelle catégorie', ref: null, type:0};
	}
	$scope.newSousCateg = function (cp) {
		$scope.currentCateg = cp;
		$scope.currentEditCateg=  {nom:'Nouvelle sous-catégorie', ref:0, refParent: cp.ref, type:1};
	}
	$scope.selectSousCateg = function (ce) {
		$scope.currentSousCateg = ce;
		$scope.currentEditCateg=  {nom: ce.nom, ref: ce.ref, type: 1};
	}
	$scope.currentEditCategNull = function (){
		$scope.currentEditCateg = {nom:'', ref:0, type:0};
	}
	
	$scope.saveCategorie = function (c){
		console.log("saveCategorie", c);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_categories",{action:"edit", categories: [c]},{})
		.success(function (dataFromServer) {
			console.log("modif ok", dataFromServer);
		});
		
	}
	
	$scope.addCategorie = function () {
		console.log("addCategorie");
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_categories",{action:"add", categories: [$scope.currentEditCateg], refParent: $scope.currentEditCateg.refParent},{})
		.success(function (dataFromServer) {
			console.log("Add ok", dataFromServer);
			console.log($scope.currentCateg);
			
		});
	}
	
	$scope.delCateg = function (c) {
		console.log("delCategorie", c);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_categories",{action:"del", categories: [c]},{})
		.success(function (dataFromServer) {
			console.log("del ok", dataFromServer);
			$scope.currentEditCategNull();
			$scope.getCateg();
			});
	}
	
	$scope.valider = function () {
		if(!$scope.currentEditCateg.ref){
			$scope.addCategorie($scope.currentEditCateg);
		}else{
			$scope.saveCategorie($scope.currentEditCateg);
		}
		$scope.currentEditCategNull();
		$scope.getCateg();
	}
	
	$scope.openSupCategModal = function () {
		$scope.supCategModalOpened = true;
	}
	
	$scope.closeSupCategModal = function (){
		$scope.supCategModalOpened= false;
	}
	
	/* ---------------------------------------------------------------------------------------------EMPLACEMENTS----------------------------------------------------------------------------------------------------------------- */
		
	$scope.currentEmp = null;
	$scope.currentSousEmp = null;
	$scope.currentEditEmp = {nom:'', ref:0, depot: null, dateDebut:	null, dateDebutDate: null, dateFin: null, dateFinDate: null, type:0};
	$scope.newEmp = {nom:'', ref: 0, refParent:null, dateDebut: null, dateFin: null, type:0};
	$scope.newEmpP = {nom:'', ref: 0, refParent:null, type:0};
	$scope.articleFiltreParent = 0;
	$scope.supEmpModalOpened= false;
	$scope.dateEmp=false;
	$scope.erreurDate=false;
	$scope.empParent=null;
	//~ $scope.lastGetEmpType = null;
	
	$scope.getChantiers = function () {
		return $scope.getEmp(1);
	}
	$scope.getDepots = function () {
		console.log('getDepot');
		return $scope.getEmp(0);
	}
	$scope.getEmp = function (type) {
		//~ $scope.lastGetEmpType = type;
		$scope.dateEmp=false;
		console.log("getEmp");
		$scope.erreurDate=false;
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_liste_emplacements",{type:type},{})
		.success(function (dataFromServer) {
			$scope.emplacementParent = [];
			for( var i = 0 ; i<dataFromServer.emplacementParent.length ; i++){
				var e = dataFromServer.emplacementParent[i];
				e.dateDebutDate =( e.dateDebut ? new Date(e.dateDebut) : new Date()  );
				e.dateFinDate = ( e.dateFin ? new Date(e.dateFin) : new Date()  );
				
				$scope.emplacementParent.push(e);
				//~ console.log(e);
				
			}
		});
		
	}
	
	$scope.getSousEmp = function (ep) {
		console.log('getSousEmp');
		$scope.currentEmp = ep;
		$scope.empParent = ep;
		$scope.erreurDate=false;
		$scope.currentEditEmp=  {
			intitule: 		ep.intitule, 
			placeholder: 	ep.intitule, 
			ref: 			ep.ref, 
			depot: 			null, 
			dateDebut:		ep.dateDebut, 
			dateDebutDate: 	ep.dateDebutDate, 
			dateFin: 		ep.dateFin,
			dateFinDate: 	ep.dateFinDate,
			type:			0,
			dateDebutShowButton : true,
			dateFinShowButton : true
			};
		console.log('$scope.currentEditEmp', $scope.currentEditEmp);
		
	}
	$scope.newDepot = function () {
		$scope.currentSousEmp = null;
		$scope.currentEmp = null;
		
		$scope.currentEditEmp=  {
			intitule:'',
			placeholder: 'Nouveau dépôt', 
			ref: null, 
			depot:0, 
			dateDebutDate: null, 
			dateFinDate: null, 
			type:0, 
			dateDebutShowButton : true,
			dateFinShowButton : true};
	}
	$scope.newChantier = function () {
		$scope.currentSousEmp = null;
		$scope.currentEmp = null;
		$scope.currentEditEmp=  {
			intitule:'',
			placeholder:  'Nouveau chantier', 
			ref: null, 
			depot:1, 
			dateDebutDate: null, 
			dateFinDate: null, 
			type:0, 
			dateDebutShowButton : true,
			dateFinShowButton : true};
	}
	$scope.newSousEmp = function (ep) {
		console.log('newSousEmp');
		$scope.currentSousEmp = null;
		$scope.currentEmp = ep;
		$scope.currentEditEmp=  {intitule:'',
			placeholder: 'Nouvel emplacement', 
			ref:0, 
			refParent: ep.ref, 
			depot: null, 
			dateDebutDate: null, 
			dateFinDate: null, 
			type:1,
			dateDebutShowButton : true,
			dateFinShowButton : true};
	}
	$scope.selectSousEmp = function (s) {
		console.log('selecSousEmp');
		$scope.currentSousEmp = s;
		$scope.selectedSousEmp = s;
		$scope.currentEditEmp=  {
			intitule: 		s.intitule, 
			placeholder: 	s.intitule, 
			ref: 			s.ref, 
			depot:			null, 
			dateDebut: 		s.dateDebut, 
			dateDebutDate: 	s.dateDebutDate, 
			dateFin: 		s.dateFin, 
			dateFinDate: 	s.dateFinDate,
			type: 			1,
			dateDebutShowButton : true,
			dateFinShowButton : true
			};
	}
	$scope.currentEditEmpNull = function (){
		$scope.currentEditEmp = {
			intitule:		'',
			ref:			0, 
			depot:			null,
			dateDebutDate: 	null,
			dateFinDate: 	null, 
			type:			0, 
			dateDebutShowButton : true,
			dateFinShowButton : true};
	}
	$scope.date = function(){
		$scope.dateEmp=true;
	}
	$scope.dateNon = function(){
		$scope.dateEmp=false;
	}
	
	$scope.saveEmp = function (){
		var e = $scope.currentEditEmp;
		console.log("saveEmp", e);
		if ( e.dateDebut || !e.dateDebutShowButton ) e.dateDebut = DatetoSQL(e.dateDebutDate);
		if ( e.dateFin || !e.dateFinShowButton ) e.dateFin= DatetoSQL(e.dateFinDate);
		console.log("saveEmp", e);
		if(e.dateDebut>e.dateFin){
			$scope.erreurDate=true;
		}else{
			$scope.erreurDate=false;
			$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_emplacements",{action:"edit", emplacements: [e]},{})
			.success(function (dataFromServer) {
				console.log("modif ok", dataFromServer);
				if(e.dateDebut){
					$scope.getEmp(1);
				}else{
					$scope.getEmp(0);
				}
				
				
			});
		}
	}
	
	$scope.addEmp = function () {
		var e = $scope.currentEditEmp;
		if(e.dateDebut>e.dateFin){
			$scope.erreurDate=true;
		}else{
			$scope.erreurDate=false;
			$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_emplacements",{action:"add", emplacements: [$scope.currentEditEmp], refParent: $scope.currentEditEmp.refParent, typeEmp: $scope.currentEditEmp.depot, dateDebutShowButton : true,
				dateFinShowButton : true},{})
			.success(function (dataFromServer) {
				console.log("Add ok", dataFromServer);
				//~ $scope.currentEditEmpNull();
				//~ $scope.getDepots();
				//~ $scope.getSousEmp($scope.empParent);
			});
				
		}
	}
	
	$scope.delEmp = function (e) {
		console.log("delEmp", e);
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_emplacements",{action:"del", emplacements: [e]},{})
		.success(function (dataFromServer) {
			console.log("del ok", dataFromServer);
			$scope.currentEditEmpNull();
			$scope.closeSupEmpModal();
			$scope.currentEmp = null;
			});
	}
	
	$scope.validerEmp = function () {
		if(!$scope.currentEditEmp.ref){
			$scope.addEmp($scope.currentEditEmp);
		
		}else{
			$scope.saveEmp($scope.currentEditEmp);
		}
		//~ $scope.selectSousEmp($scope.selectedSousEmp);
	}
	$scope.addDepotValider= function(){
		$scope.validerEmp();
		$scope.currentEditEmpNull();
		$scope.getDepots();
		$scope.getSousEmp($scope.currentEmp);
	}
	$scope.addChantierValider= function(){
		$scope.validerEmp();
		$scope.currentEditEmpNull();
		$scope.getChantiers();
	}
	
	
	$scope.openSupEmpModal = function () {
		$scope.supEmpModalOpened = true;
	}
	
	$scope.closeSupEmpModal = function (){
		$scope.supEmpModalOpened= false;
		
	
	}

/**********************************************************************************LOGIN******************************************************************************************/
	$scope.login={email: null, pass:null};
	$scope.message="";
	$scope.connectedUser= $scope.user;
	console.log('$scope.connectedUser',$scope.connectedUser);
	
	$scope.connectMe = function(){
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=login",{	"action":"login", "email":$scope.login.email,"pass":$scope.login.pass }, {})
			.success(function (dataFromServer) {
					console.log(dataFromServer);
				if(!dataFromServer.errno){
					$scope.user = dataFromServer.user;
					console.log('user : ', $scope.user.nom);
					$scope.message="";
					location.reload(); 
				}else{
					$scope.message = dataFromServer.message;
					console.log($scope.message);
				}
			});
	}
	
	
	$scope.deconnectMe = function(){
		console.log('logout')
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=login",{"action":"logout" }, {})
			.success(function (dataFromServer) {
				if(!dataFromServer.errno)
					location.reload(); 
			
			});
	}
	
	
	/**********************************************************************************ETAT******************************************************************************************/

	$scope.getListeEtats = function () {
		
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=get_liste_etats",{},{})
		.success(function (dataFromServer) {
			$scope.listeEtat = dataFromServer.etats;
			$scope.colors = dataFromServer.colors;
		});
		
	}
	$scope.saveEtat = function (e) {
		
		$http.post("http://localhost/btp/site_btp_admin/?type=json&route=action_etats",e,{})
		.success(function (dataFromServer) {
			$scope.getListeEtats();
		});
		
	}
	
	/*********************************************************************************BARCODE*****************************************************************************************/
	
	$scope.getBarcode = function(){
		//~ $http.post("/?type=json&route=EAN13",{"numArticle":'8715946388625'},{})
		//~ .success(function (dataFromServer) {
			//~ $scope.imageEan = dataFromServer;
			console.log('$scope.getbarcode');
			$scope.showBarcode = true;
		var listEAN='';
		for ( var i = 0 ; i<$scope.articles.length ; i++ ) {
			var a = $scope.articles[i];
			if(a.selected){
				listEAN+=a.ref+';';
				}
		}
		$window.open('http://localhost/btp/site_btp_admin/?type=json&route=gen_etiquettes_PDF&ean='+listEAN, '_blank');
		
	}
	

});



