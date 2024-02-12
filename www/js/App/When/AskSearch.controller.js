App.filter('formatDateCal', function() {
  return function(input) {
    return input.format('ddd MMM DD YYYY');
  };
});

App.controller('AskSearchCtrl', function($http,$scope,Spinner,Get,$interval){
        var self=this;
        $scope.day = moment();
        $scope.day2 = moment();
        $scope.day3 = moment(); 
        var timezone = '';
        
//        $scope.dayFormatted1=$scope.day.format('ddd MMM DD YYYY');
//        $scope.dayFormatted2=$scope.day2.format('ddd MMM DD YYYY');
//        $scope.dayFormatted3=$scope.day3.format('ddd MMM DD YYYY');
        
        $scope.showCalsFlags=[false,false, false];
                
        self.initData={};
        
        Get.set();
        this.headingFlag=typeof(Get.bag['query_id'])==='undefined';
        
        this.errorMsgFlag=false;
        
        var get_current_UK_time=function(){
            var now_uk=moment().tz('Europe/London');
            
            $scope.now_uk_hours=now_uk.hours();
            $scope.now_uk_minutes=now_uk.format('mm'); 
        };
        get_current_UK_time();
        $interval(get_current_UK_time,1000);        
                
        var get_timeZone=function(){
            $http({
                        method  : 'GET',
                        url     : 'http://'+document.domain+'/ajax/get-timezone/',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {                                
                                /* setting up default times from NOW */
                              
                                data=data.replace('\\','');
                                data = data.replace(/"/g, "");
                                $scope.timezone=data;
                                
                                timezone=data;
                                $scope.day = moment().tz(data);
                                $scope.day2 = moment().tz(data);
                                $scope.day3 = moment().tz(data);
                               
                                var now=moment().tz(data); 
                                
                                var dates_now=now.clone();
                                var exp_now=now.clone();
                                
                                
                                dates_now.add(15,'minutes');
                                exp_now.add(5,'minutes');

                                self.formData={
                                    'date1':{
                                        'hours':dates_now.hours(),
                                        'prev_hours':dates_now.hours(),
                                        'minutes':dates_now.minutes(),
                                        'prev_mins':dates_now.minutes()
                                    },
                                    'date2':{
                                        'hours':dates_now.hours(),
                                        'prev_hours':dates_now.hours(),
                                        'minutes':dates_now.minutes(),
                                        'prev_mins':dates_now.minutes()
                                    },
                                    'exp':{
                                        'hours':exp_now.hours(),
                                        'prev_hours':exp_now.hours(),
                                        'minutes':exp_now.minutes(),
                                        'prev_mins':exp_now.minutes()
                                    }
                                };
                                
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
        };
        
        
        this.type="on this day";
        this.secondFlag=false;
        
        
        get_timeZone();
        
        
        
        this.show_hide_second=function(){
            if(self.type==='range'){
                self.secondFlag=true;
            }
            else {
                self.secondFlag=false;
            }
        };
        
        this.prepare_time=function(level1,level2){
            if(self.formData[level1][level2]===0){
                self.formData[level1][level2]=null;
            }
        };
        
        this.refresh_time=function(level1,level2){
            if(self.formData[level1][level2]===null){
                self.formData[level1][level2]=0;
            }
        };
        
        validate_range=function(){
            if($scope.day3.isBefore($scope.day) && $scope.day3.isAfter(moment())){
                if(self.secondFlag){
                    if($scope.day.isBefore($scope.day2)){
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        };
        
        this.get_when=function(){
            if(typeof(Get.bag['query_id'])!=='undefined'){
                $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/ask/get-when/',
                        data    : $.param({'query_id':Get.bag['query_id']}),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                                self.initData=data[0].QueryWhenAsker[0];
//                                window.console.log(data.QueryWhenAsker);                                
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
        };
        self.get_when();
        
        this.submit=function(){
            
            $scope.day.hours(self.formData.date1.hours).minutes(self.formData.date1.minutes);
            $scope.day2.hours(self.formData.date2.hours).minutes(self.formData.date2.minutes);
            $scope.day3.hours(self.formData.exp.hours).minutes(self.formData.exp.minutes);
            
            if(validate_range()){
                var postData={};
                postData.date1=$scope.day.format('ddd MMM D YYYY HH:mm:ss');
                
                if(self.secondFlag){
                    postData.date2=$scope.day2.format('ddd MMM D YYYY HH:mm:ss');
                }
                postData.date3=$scope.day3.format('ddd MMM D YYYY HH:mm:ss');
                Spinner.spin(); 
                $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/ask/when/',
                        data    : $.param({'dates':postData,'type':self.type,'query_id':Get.bag['query_id']}),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
                            if(typeof(Get.bag['query_id'])==='undefined'){
                                window.location='http://'+document.domain+'/ask/final/'; 
                            }
                            else {
                                window.location='http://'+document.domain+'/myuin/asking/'; 
                            }
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
            else {
//                window.console.log('dates not valid');
                self.errorMsgFlag=true;
            }
        };
        
});
