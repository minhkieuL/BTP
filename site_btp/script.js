/* PUBLIC */
var app = angular.module('publicApp', []);

app.controller("publicCtrl", function($scope,$http) {
	
	$scope.modalOpened=false;
	$scope.inputImg=null;
	$scope.scanedArticle=null;
	$scope.scanLoading = false;
	$scope.newSuivi= {refArt:"", refEmp:"", refUt:"", dateMouv:"", refEtat:1};
	//~ $scope.article = {};
	$scope.entrepotRef = [];
	//~ $scope.entrepot = [];
	$scope.e=null;
	$scope.listChantierContenu = [];
	
	$scope.plurial = function(c){
		if(c>1){
			return 's';
		}
	}
	
	$scope.getChantiers = function () {
		console.log('getchantiers');
		return $scope.getEmplacements(1, false);
		
	}	
	
	/********Liste les chantiers contenant des articles (utilisé pour rapporter un materiel)****/
	$scope.getChantiersWithArticles = function () {
		console.log('getchantierswithArticle');
		
		return $scope.getEmplacements(1, true);
		
	}	
	
	/*modal menu*/
	
	$scope.openModal = function() {
		console.log("$scope.openModal");
		$scope.modalOpened=true;
	}
	
	$scope.closeModal = function(){
		console.log("$scope.closeModal");
		if($scope.modalOpened){
			$scope.modalOpened=false;
			//~ console.log('closeModal');
		}
		
		
	}
	
	$scope.openCloseModal = function(){
		console.log("$scope.openCloseModal");
		if(!$scope.modalOpened){
			$scope.openModal();
		}else{
			$scope.closeModal();
		}
	}
	
	$scope.getDepots = function () {
		console.log('getdepot');
		return $scope.getEmplacements(0,false);
	}
	
	$scope.getEmplacements = function (type_emplacement, onlyWithArticles) {
		console.log('getEmplacements');
		$http.post("/?type=json&route=get_liste_emplacements",{"type":type_emplacement, "onlyWithArticles":onlyWithArticles},{})
		.success(function (dataFromServer) {
			if(dataFromServer.emplacementParent.length){
				$scope.entrepot = dataFromServer.emplacementParent;
				console.log('entrepot',$scope.entrepot);
				$scope.selectArticle= {refArt:$scope.selectArticle.refArt, refEmp:$scope.entrepot[0].ref, refUt:"", dateMouv:"", refEtat:$scope.selectArticle.refEtat};
			}else{
				$scope.entrepot = null;
				console.log('here')
			}
		});
	}
	
	$scope.getCategs = function(){
		console.log('getCateg');
		$http.post("/?type=json&route=get_liste_categories",{},{})
		.success(function (dataFromServer) {
			$scope.categorieParent = dataFromServer.categorieParent;
			console.log('categorie',$scope.categorieParent);
		});
	}
	//~ $scope.getChantierApp = function (type_emplacement, ref) {
		//~ console.log('getEmpApp');
		//~ $http.post("/?type=json&route=get_liste_emplacements",{"type":type_emplacement, "ref": ref},{})
		//~ .success(function (dataFromServer) {
			//~ $scope.chantierApp=dataFromServer;
			//~ $scope.listChantierContenu.push($scope.chantierApp);
			
		//~ });
			
		
	//~ }
	
	$scope.getArticle = function (refArt, ref_emplacement, ref_categorie, typeEmp) {
		
		console.log("getArticle, refArt", refArt);
		//~ console.log("getArticle, refEmp", ref_emplacement);
		//~ console.log("getArticle, refCateg", ref_categorie);
		$scope.refeArt = false;
		if(refArt)$scope.refeArt = true;
		$http.post("/?type=json&route=get_liste_article",{"refArt":refArt, "emplacement":ref_emplacement, "categ":ref_categorie, "typeEmp":typeEmp},{})
		.success(function (dataFromServer) {
			console.log(dataFromServer);
			if($scope.refeArt){
				if(dataFromServer.articles.length){
					$scope.article = dataFromServer.articles[0];
					console.log('article :',$scope.article);
				}else{
					$scope.article = null;
					console.log('article :',$scope.article);
					
				}
			}else{
				
				$scope.article = dataFromServer.articles;
				console.log('la', $scope.article);
				if($scope.article.length){
					
					$scope.selectArticle= {refArt:$scope.article[0].ref, refEmp:$scope.article[0].refEmp, refUt:"", dateMouv:""};
				}else{
					$scope.article=null;
					console.log('pas d\'article');
					}
				//~ console.log('ref premier article :',$scope.article[0].ref);
				//~ console.log('refemp premier article :',$scope.article[0].refEmp);
				
			}
			//~ console.log('ll',$scope.article);
			
	});
			//~ if($scope.article!=null){
					//~ $scope.entrepotRef.push(ref_emplacement);
				
			//~ }
					//~ console.log($scope.entrepotRef);
		
	}
	
	$scope.getEtat = function(){
		console.log('getEtat');
		$http.get("/?type=json&route=get_liste_etat",{},{})
		.success(function (dataFromServer) {
			$scope.etat = dataFromServer.etat;
			console.log('listetat',$scope.etat);
		});
	}
	
	$scope.onInputImgChange = function (){
		console.log($scope.inputImg);
	}
	
	
	$scope.updateSuiviArticle = function (refArt, refEmp, refEtat) {
		console.log("open function updateSuiviArticle", refArt);
		$http.post("/?type=json&route=action_suivi_article",{suivi: [$scope.newSuivi], refArt: refArt, refEmp: refEmp, refEtat: refEtat},{})
		.success(function (dataFromServer) {
			console.log('datas Updated');
			//~ console.log('la', refArt);
			console.log('suivi refEmp', refEmp);
			console.log('suivi refArt', refArt);
			console.log('suivi refEtat', refEtat);
			if($scope.emprunterChoix==true){
				$scope.closeEmprunterChoix();
				$scope.openEmprunterConfirmation();
				$scope.getArticle(refArt, null, null, null);
			}
			if($scope.rapporterChoixDepot==true){
				$scope.closeRapporterChoixDepot();
				$scope.openRapporterConfirmation();
				$scope.getArticle(refArt, null, null, null);
			}
			if($scope.rapporterChoixDepot2==true){
				$scope.closeRapporterChoixDepot2();
				$scope.closeTitle();
				$scope.openRapporterConfirmation();
				$scope.getArticle(refArt, null, null, null);
			}
			if($scope.rapporterChoixEtat==true){
				$scope.closeRapporterChoixEtat();
				$scope.closeTitle();
				$scope.openRapporterConfirmation2();
				$scope.getArticle(refArt, null, null, null);
			}
			if($scope.rapporterChoixEtat2==true){
				$scope.closeRapporterChoixEtat2();
				$scope.closeTitle();
				$scope.openRapporterConfirmation3();
				$scope.getArticle(refArt, null, null, null);
			}
			if($scope.rapporterChoixEtat3==true){
				$scope.closeRapporterChoixEtat3();
				$scope.closeTitle();
				$scope.openRapporterConfirmation3();
				$scope.getArticle(refArt, null, null, null);
			}
			
	})};
	
	
	/*---------------------------------------------------------------------------------------------------EMPRUNTER-------------------------------------------------------------------------------------------------------------------*/
	$scope.emprunterIntro = false;
	$scope.emprunterValid= false;
	$scope.emprunterChoix= false;
	$scope.emprunterConfirmation= false;
	$scope.boutonCodeOpened = true;
	$scope.articleEan = null;
	
	/*---------------------------------------------intro---------------------------------------------*/
	$scope.openEmprunterIntro = function (){
		$scope.emprunterIntro = true;
		console.log('emprunterIntro =',$scope.emprunterIntro);
	}
	
	$scope.articleTap = function (ean){
		$scope.closeEmprunterIntro();
		console.log('articleTap,  $scope.articleEan=',ean);
		$scope.openEmprunterValid(ean);
	}
	
	$scope.$watch('inputImg', function(newValue, oldValue){
		if(!newValue) return;
		$scope.scanLoading = true;
		//~ console.log($scope.inputImg);
		console.log('img',newValue);
		$http.post("/?type=json&route=reconnaissance_image",{inputImg:newValue},{})
		.success(function (dataFromServer) {
			//~ console.log('la', dataFromSever);
			$scope.scanLoading = false;
			console.log('tableau renvoyé',dataFromServer);
			if(!dataFromServer.errno){
				$scope.scanedArticle = dataFromServer;
				console.log('article de la fonction watch',$scope.scanedArticle.ean);
				$scope.closeEmprunterIntro();
				$scope.openEmprunterValid($scope.scanedArticle.ean);
			}else{
				$scope.scanedArticle= null;
				console.log('ean', $scope.scanedArticle);
				$scope.closeEmprunterIntro();
				$scope.openEmprunterValid('989'); //si pas de code ean renvoyé, code 989(code pris au hazard) pour permettre de renvoyer quelque chose au template
			}
				
		});
	});
	
	$scope.closeEmprunterIntro = function (){
		$scope.emprunterIntro = false;
	}
	
	$scope.closeBoutonCode = function(){
		$scope.boutonCodeOpened = false;
		
	}
	$scope.openBoutonCode = function(){
		$scope.boutonCodeOpened = true;
		
	}
	
	/*---------------------------------------------valid---------------------------------------------*/
	
	$scope.openEmprunterValid = function (ean){
		console.log('ean', ean);
		$scope.emprunterValid = true;
		$scope.scanedArticle=null;
		console.log('emprunter valide');
		$scope.getArticle(ean, null, null, null);
	}
	
	$scope.closeEmprunterValid = function (){
		$scope.emprunterValid = false;
	}
	
	/*---------------------------------------------choixChantier----------------------------------------*/
	
	$scope.openEmprunterChoix = function (){
		$scope.emprunterChoix = true;
		console.log('emprunterChoix',$scope.emprunterChoix);
		$scope.getChantiers();
		$scope.getEtat();
	}
	
	$scope.closeEmprunterChoix = function (){
		$scope.emprunterChoix = false;
	}
	
	$scope.annulerEmprunterChoix = function(){
		$scope.articleEan = null;
		$scope.closeEmprunterChoix();
		$scope.openEmprunterIntro();
	}
	
	/*---------------------------------------------confirmation--------------------------------------*/
	$scope.openEmprunterConfirmation = function (){
		$scope.emprunterConfirmation = true;
		console.log($scope.emprunterConfirmation);
	}
	
	$scope.closeEmprunterConfirmation = function (){
		$scope.emprunterConfirmation = false;
	}
	
	/*---------------------------------------------------------------------------------------------------RAPPORTER-------------------------------------------------------------------------------------------------------------------*/
	$scope.rapporterChoixChantier = false;
	$scope.rapporterChoixMateriel = false;
	$scope.rapporterChoixEtat = false;
	$scope.rapporterChoixEtat2 = false;
	$scope.rapporterChoixEtat3 = false;
	$scope.rapporterChoixDepot = false;
	$scope.rapporterChoixDepot2 = false;
	$scope.rapporterConfirmation = false;
	$scope.rapporterConfirmation2 = false;
	$scope.rapporterConfirmation3 = false;
	$scope.title = true;
	
	$scope.openTitle = function (){
		$scope.title = true;
	}
	$scope.closeTitle = function (){
		$scope.title = false;
	}
	$scope.selectArticle = {refArt:null, refEmp:null, refUt:"", dateMouv:"", refEtat: ''};
	
	
	$scope.updateSelectArticle = function (refArt, refEmp, refUt, refEtat) {
		$scope.selectArticle = {refArt:refArt, refEmp:refEmp, refUt:refUt, dateMouv:"", refEtat: refEtat};
		console.log('scope.selectArticle : ',$scope.selectArticle);
		if(refEmp && !refArt && !refEtat){
			$scope.closeRapporterChoixChantier();
			$scope.openRapporterChoixMateriel();
		}
		if(refArt && !refEtat){
			$scope.closeRapporterChoixMateriel();
			$scope.openRapporterChoixEtat();
		}
		if(refEtat){
			$scope.closeRapporterChoixEtat();
			$scope.closeRapporterChoixEtat3();
			$scope.openRapporterChoixDepot();
		}
		
	}
	/*---------------------------------------------ChoixChantier---------------------------------------------*/
	
	$scope.openRapporterChoixChantier = function (){
		$scope.rapporterChoixChantier = true;
		//~ console.log('openRapporterChoixChantier');
		$scope.getChantiersWithArticles();
	}
	
	//faire un getArticle pour récupérer le premier élément puis faire u getArticle dans openRaporterChoixMateriel
	$scope.closeRapporterChoixChantier = function (){
		$scope.rapporterChoixChantier = false;
		console.log('closeRapporterChoixChantier');
	}
	/*---------------------------------------------ChoixMateriel---------------------------------------------*/
	$scope.openRapporterChoixMateriel = function (){
		$scope.rapporterChoixMateriel = true;
		console.log('openRapporterChoixMateriel',$scope.rapporterChoixMateriel );
		console.log('scope.selectArticle : ',$scope.selectArticle);
		
		$scope.getArticle(null, $scope.selectArticle.refEmp,null, 1);
	}
	
	$scope.closeRapporterChoixMateriel = function (){
		$scope.rapporterChoixMateriel = false;
		console.log('closeRapporterChoixMateriel');
	}
	/*---------------------------------------------ChoixEtat---------------------------------------------*/
	$scope.openRapporterChoixEtat = function (){
		$scope.rapporterChoixEtat = true;
		console.log('openRapporterChoixEtat',$scope.rapporterChoixEtat );
		$scope.getArticle($scope.selectArticle.refArt,null,null, null);
	}
	
	$scope.closeRapporterChoixEtat = function (){
		console.log('closeRapporterChoixEtat');
		$scope.rapporterChoixEtat = false;
	}
	/*---------------------------------------------ChoixEtat2---------------------------------------------*/
	$scope.openRapporterChoixEtat2 = function (){
		$scope.closeRapporterChoixEtat();
		console.log('openRapporterChoixEtat2',$scope.rapporterChoixEtat2 );
		$scope.rapporterChoixEtat2 = true;
	}
	
	$scope.closeRapporterChoixEtat2 = function (){
		console.log('closeRapporterChoixEtat2');
		$scope.rapporterChoixEtat2 = false;
	}
	/*---------------------------------------------ChoixEtat3---------------------------------------------*/
	$scope.openRapporterChoixEtat3 = function (etat){
		//~ $scope.selectArticle = {refEtat: etat};
		console.log('etat', etat);
		$scope.rapporterChoixEtat3 = true;
		console.log('openRapporterChoixEtat3',$scope.rapporterChoixEtat3 );
		$scope.closeRapporterChoixEtat();
		$scope.getEtat();
	}
	
	$scope.closeRapporterChoixEtat3 = function (){
		console.log('closeRapporterChoixEtat3');
		$scope.rapporterChoixEtat3 = false;
	}
	/*---------------------------------------------ChoixDepot---------------------------------------------*/
	$scope.openRapporterChoixDepot = function (){
		$scope.rapporterChoixDepot = true;
		console.log('openRapporterChoixDepot');
		console.log('scope.selectArticle : ',$scope.selectArticle);
		$scope.getArticle($scope.selectArticle.refArt,null,null, null);
	}
	$scope.closeRapporterChoixDepot = function (){
		$scope.rapporterChoixDepot = false;
		console.log('closeRapporterChoixDepot');
	}
	/*---------------------------------------------ChoixDepot2---------------------------------------------*/
	$scope.openRapporterChoixDepot2 = function (etat){
		$scope.rapporterChoixDepot2 = true;
		$scope.closeRapporterChoixDepot();
		console.log('openRapporterChoixDepot2');
		console.log('selectAr',$scope.selectArticle);
		
		//~ $scope.selectArticle = {refArt:$scope.article.ref, refEmp:$scope.article.dernierEmp, refEtat:etat, refUt:"", dateMouv:""};
		//~ console.log('selectAr',$scope.selectArticle);
		$scope.getDepots();
		$scope.getArticle($scope.selectArticle.refArt,null,null, null);
		console.log('selectAr',$scope.selectArticle);
	}
	$scope.closeRapporterChoixDepot2 = function (){
		$scope.rapporterChoixDepot2 = false;
		console.log('closeRapporterChoixDepot2');
	}
	/*---------------------------------------------Confirmation---------------------------------------------*/
	$scope.openRapporterConfirmation = function (){
		$scope.rapporterConfirmation = true;
		console.log('openRapporterConfirmation');
		$scope.closeTitle();
	}
	$scope.closeRapporterConfirmation = function (){
		$scope.rapporterConfirmation = false;
		console.log('closeRapporterConfirmation');
	}
	/*---------------------------------------------Confirmation2---------------------------------------------*/
	$scope.openRapporterConfirmation2 = function (){
		$scope.rapporterConfirmation2 = true;
		console.log('openRapporterConfirmation2');
		$scope.closeTitle();
	}
	$scope.closeRapporterConfirmation2 = function (){
		$scope.rapporterConfirmation2 = false;
		console.log('closeRapporterConfirmation2');
	}
	/*---------------------------------------------Confirmation3---------------------------------------------*/
	$scope.openRapporterConfirmation3 = function (){
		$scope.rapporterConfirmation3 = true;
		console.log('openRapporterConfirmation3');
		$scope.closeTitle();
	}
	$scope.closeRapporterConfirmation3 = function (){
		$scope.rapporterConfirmation3 = false;
		console.log('closeRapporterConfirmation3');
	}

	
	/*---------------------------------------------------------------------------------------------------RECHERCHER-------------------------------------------------------------------------------------------------------------------*/
	$scope.rechercherIntro = false;
	$scope.rechercherArticleFound= false;
	$articleSearch = {ref:"", cat:null};
	
	/*---------------------------------------------Intro---------------------------------------------*/
	$scope.openRechercherIntro = function (){
		$scope.rechercherIntro = true;
		console.log('openRechercherIntro');
		$scope.getCategs();
	}
	
	$scope.updateArticleSearch = function (categ){
		$articleSearch = {ref:"", cat:categ};
		console.log('article search', $articleSearch);
		$scope.getArticle(null, null, categ, null);
		$scope.closeRechercherIntro();
		$scope.openRechercherArticleFound();
	}
	
	$scope.closeRechercherIntro = function (){
		$scope.rechercherIntro = false;
		console.log('closeRechercherIntro');
	}
	/*---------------------------------------------articleFound---------------------------------------------*/
	$scope.openRechercherArticleFound = function (){
		$scope.rechercherArticleFound = true;
		console.log('openRechercherArticleFound');
		
	}
	$scope.closeRechercherArticleFound = function (){
		$scope.rechercherArticleFound = false;
		console.log('closeRechercherArticleFound');
	}
	   /**********************************************************************************LOGIN******************************************************************************************/
	$scope.login={email: null, pass:null};
	$scope.message="";
	
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
	

	$scope.connectMe = function(){
		console.log('here');
		$scope.login.email = $scope.login.email.toLowerCase().sansAccent();
		$http.post("/?type=json&route=login",{	"action":"login", "email":$scope.login.email,"pass":$scope.login.pass }, {})
			.success(function (dataFromServer) {
					console.log(dataFromServer);
				if(!dataFromServer.errno){
					$scope.user = dataFromServer.user;
					$scope.message="";
					location.reload(); 
				}else{
					$scope.message = dataFromServer.message;
					console.log($scope.message);
				}
			});
	}
	$scope.deconnectMe = function(){
		console.log('logout');
		$http.post("/?type=json&route=login",{"action":"logout" }, {})
			.success(function (dataFromServer) {
				if(!dataFromServer.errno)
					location.reload(); 
			
			});
	}
});

app.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                var img = new Image();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
						console.log('resizing picture');
                        //~ scope.fileread = loadEvent.target.result;
                        img.src = loadEvent.target.result;
                        console.log(img);

						img.onload = function() {
							var maxWidth = 400,
								maxHeight = 400,
								imageWidth = img.width,
								imageHeight = img.height;
								console.log(scope);


							if (imageWidth > imageHeight) {
							  if (imageWidth > maxWidth) {
								imageHeight *= maxWidth / imageWidth;
								imageWidth = maxWidth;
							  }
							}
							else {
							  if (imageHeight > maxHeight) {
								imageWidth *= maxHeight / imageHeight;
								imageHeight = maxHeight;
							  }
							}

							var canvas = document.createElement('canvas');
							canvas.width = imageWidth;
							canvas.height = imageHeight;
							img.width = imageWidth;
							img.height = imageHeight;
							var ctx = canvas.getContext("2d");
							ctx.drawImage(this, 0, 0, imageWidth, imageHeight);

							scope.fileread = canvas.toDataURL("image/jpeg");
							scope.$apply();
							console.log(scope);
						}

                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }

}]);



//~ function openNav() {
    //~ document.getElementById("nav-content").style.width = "250px";
//~ }
 
//~ function closeNav() {
    //~ document.getElementById("nav-content").style.width = "0";
    //~ document.getElementById("main").style.marginLeft = "0";}




