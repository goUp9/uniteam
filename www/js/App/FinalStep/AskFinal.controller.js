App.controller('AskFinalCtrl', function($http,Get,Spinner,$filter){
        var self=this;
        
        Get.set();
        this.headingFlag=typeof(Get.bag['query_id'])==='undefined';
        
        this.budget=1;
        this.prev_budget=this.budget;
        this.msg='';
        this.currency='GBP';
        
        this.adviseMsg='';
        this.adviseOnBudget=false;
        this.adviseBtnFlag=false;
        
        this.currencyList=[
//            {'name':'','code':''},
            {'name':'Australian Dollar','code':'AUD'},
            {'name':'Brazilian Real','code':'BRL'},
            {'name':'Canadian Dollar','code':'CAD'},
            {'name':'Czech Koruna','code':'CZK'},
            {'name':'Danish Krone','code':'DKK'},
            {'name':'Euro','code':'EUR'},
            {'name':'Hong Kong Dollar','code':'HKD'},
            {'name':'Hungarian Forint','code':'HUF'},
            {'name':'Israeli New Sheqel','code':'ILS'},
            {'name':'Japanese Yen','code':'JPY'},
            {'name':'Malaysian Ringgit','code':'MYR'},
            {'name':'Mexican Peso','code':'MXN'},
            {'name':'Norwegian Krone','code':'NOK'},
            {'name':'New Zealand Dollar','code':'NZD'},
            {'name':'Philippine Peso','code':'PHP'},
            {'name':'Polish Zloty','code':'PLN'},
            {'name':'Pound Sterling','code':'GBP'},
            {'name':'Singapore Dollar','code':'SGD'},
            {'name':'Swedish Krona','code':'SEK'},
            {'name':'Swiss Franc','code':'CHF'},
            {'name':'Taiwan New Dollar','code':'TWD'},
            {'name':'Thai Baht','code':'THB'},
            {'name':'Turkish Lira','code':'TRY'},
            {'name':'U.S. Dollar','code':'USD'}
        ];
        
        this.validate_budget=function(){           
            if(self.budget<0){
                self.budget=self.prev_budget;
            }
            var patt = new RegExp(/[0-9]+/);
            var is_number = patt.test(self.budget);
            if(!is_number&&self.budget!==null){
                self.budget=self.prev_budget;
            }
            self.prev_budget=self.budget;
        };
        
        this.submit=function(auto){
            auto=auto|false;
            if(self.budget!==null){ 
                Spinner.spin();
                var postData={'budget':self.budget, 'msg':self.msg, 'currency':self.currency};
                if(typeof(Get.bag['query_id'])!=='undefined'){
                    postData.query_id=Get.bag['query_id'];
                }
                 $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/ask/final/',
                        data    : $.param(postData),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                                window.console.log(data);
                            if(!auto){ 
                                if(typeof(Get.bag['query_id'])!=='undefined'){
                                     window.location='http://'+document.domain+'/ajax/finalise/?type=ask';
    //                                self.find_matches(postData.query_id);    
                                }
                                else {
                                    self.find_matches();  
                                }
                            }
                            else {
                                self.submit_adviser();
                            }
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
        };
        
        this.switch_adviseBtn=function(){ 
            if((self.adviseMsg!==''&&self.adviseMsg!==null)||self.adviseOnBudget===true){
                self.adviseBtnFlag=true;
            }
            else {
                self.adviseBtnFlag=false;
            } 
        };
        
        this.switch_adviseBtn_checkbox=function(){
            self.switch_adviseBtn();            
        };
        
        this.find_matches=function(id){
            id=id|null;
            Spinner.spin();
            var url;
            if(id===null){
                url='http://'+document.domain+'/ajax/match_suppliers/';
            }
            else {
                url='http://'+document.domain+'/ajax/match_suppliers/'+id+'/';
            }
            $http({
                    method  : 'GET',
                    url     : url,
                    //data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data);
                         window.location='http://'+document.domain+'/ajax/finalise/?type=ask';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        this.submit_adviser=function(){            
            if(self.budget!==null){ 
                Spinner.spin();
                var postData={'adviceMsg':self.adviseMsg, 'isAdviseOnBudgetNeeded':self.adviseOnBudget};
                if(typeof(Get.bag['query_id'])!=='undefined'){
                    postData.query_id=Get.bag['query_id'];
                }
                 $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/ask/for-advise/',
                        data    : $.param(postData),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                                window.console.log(data);
                                self.ask_adviser();
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
        }
        
        this.ask_adviser=function(id){
            id=id|null;
            Spinner.spin();
            var url;
            if(id===null){
                url='http://'+document.domain+'/ajax/match_advisers/';
            }
            else {
                url='http://'+document.domain+'/ajax/match_advisers/'+id+'/';
            }
            $http({
                    method  : 'GET',
                    url     : url,
                    //data    : $.param({'id':id}),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {
//                            window.console.log(data);
                         window.location='http://'+document.domain+'/ajax/finalise/?type=ask';
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                });
        }
        
});
