App.controller('AdviseSearchCtrl', function($http,$scope, Get, Spinner){
        var self=this;
        
        Get.set();
        
        this.quickSelectFlag=false; //show/hide quick select menu
        
        this.closedFlags=[];        
        
        this.closed=[];
        
        $scope.weekDays=[
            {"title":"Monday"},
            {"title":"Tuesday"},
            {"title":"Wednesday"},
            {"title":"Thursday"},
            {"title":"Friday"},
            {"title":"Saturday"},
            {"title":"Sunday"}
        ];
        
        this.formData=[];
        
        function init_default(){
            for(var i=0;i<7;i++){
                if(typeof(self.formData[i])=='undefined'){
                    self.formData[i]={};
                }
                self.formData[i].from={hours:9,minutes:0};
                self.formData[i].to={hours:17,minutes:0};
            }
        }
        init_default();
        
        set_weekDays_formData=function(){            
            for(var i=0; i<$scope.weekDays.length;i++){
                var day=$scope.weekDays[i].title;                 
                self.formData[i]['weekday']=day;
            }
        };
        set_weekDays_formData();
                
        
        remove_closed_formData=function(){
            var data=self.formData.slice();
            for(var i=0; i<data.length;i++){                
                if(data[i].closed){                    
                    data.splice(i, 1);
                    i--;
                }
            }
            return data;
        };
        
        init_formData=function(schedules){            
            if(typeof(schedules)!=='undefined'){
                for(var i=0;i<schedules.length;i++){
                    for(var y=0;y<self.formData.length;y++){                        
                        if(self.formData.weekday===schedules[i].weekday){
                            var fromBroken=schedules[i].from.split(':');
                            var toBroken=schedules[i].to.split(':');
                            self.formData[i].from={};
                            self.formData[i].from.hours=parseInt(fromBroken[0]);
                            self.formData[i].from.minutes=parseInt(fromBroken[1]);
                            self.formData[i].to={};
                            self.formData[i].to.hours=parseInt(toBroken[0]);
                            self.formData[i].to.minutes=parseInt(toBroken[1]);
                        }                    
                    }
                }
            }
        };
        
        init_closed=function(){
            for(var y=0;y<self.formData.length;y++){                
                if(!self.formData[y].hasOwnProperty('from')){
                    self.closedFlags[y]=true;
                    self.setClosedFlag(y);
                    self.formData[y].from={};
                    self.formData[y].from.hours=0;
                    self.formData[y].from.minutes=0;
                    self.formData[y].to={};
                    self.formData[y].to.hours=23;
                    self.formData[y].to.minutes=59;
                }
            }
        };
        
        this.get_schedule=function(){
            if(typeof(Get.bag['query_id'])!=='undefined'){
                $http({
                        method  : 'POST',
                        url     : 'http://'+document.domain+'/ajax/supply/get-schedule/',
                        data    : $.param({'query_id':Get.bag['query_id']}),
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                    })
                        .success(function(data) {
//                                window.console.log(data); 
                                init_formData(data[0].QueryWhenSchedule);
                                init_closed();
                        }).error(function(data, status, headers, config) {
                            window.console.log(status);
                    });
            }
        };
        self.get_schedule();
        
        this.submit=function(){
            Spinner.spin();            
            set_weekDays_formData();
            var data=remove_closed_formData();
            
            if(typeof(Get.bag['query_id'])!=='undefined'){
                var postData={'schedule':data,'query_id':Get.bag['query_id']};
            }
            else {
                var postData={'schedule':data,};
            }
            $http({
                    method  : 'POST',
                    url     : 'http://'+document.domain+'/ajax/advise/when/',
                    data    : $.param(postData),
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest' }
                })
                    .success(function(data) {                        
                            window.location='http://'+document.domain+'/ajax/finalise/?type=advice'; 
                        
                    }).error(function(data, status, headers, config) {
                        window.console.log(status);
                }); 
        };
        
        set_closed=function(index,value){
            self.formData[index].closed=value;            
        };
        
        this.setClosedFlag=function($index){
            $index=$index|null;
            if($index!==null){                
                if(!self.closedFlags[$index]){
                    $('#input-from-hours-'+$index).removeAttr('disabled');
                    $('#input-from-minutes-'+$index).removeAttr('disabled');
                    $('#input-to-hours-'+$index).removeAttr('disabled');
                    $('#input-to-minutes-'+$index).removeAttr('disabled');
                    set_closed($index,false);
                }
                else if(self.closedFlags[$index]){
                    $('#input-from-hours-'+$index).attr('disabled','disabled');
                    $('#input-from-minutes-'+$index).attr('disabled','disabled');
                    $('#input-to-hours-'+$index).attr('disabled','disabled');
                    $('#input-to-minutes-'+$index).attr('disabled','disabled');
                    set_closed($index,true);
                }
            }
        };
        
        this.set_alldays=function(){ // 24/7            
            for(var i=0;i<self.closedFlags.length;i++){
                self.closedFlags[i]=false;                
                if(!self.closedFlags[i]){
                    $('#input-from-hours-'+i).removeAttr('disabled');
                    $('#input-from-minutes-'+i).removeAttr('disabled');
                    $('#input-to-hours-'+i).removeAttr('disabled');
                    $('#input-to-minutes-'+i).removeAttr('disabled');
                    set_closed(i,false);
                }
                else if(self.closedFlags[i]){
                    $('#input-from-hours-'+i).attr('disabled','disabled');
                    $('#input-from-minutes-'+i).attr('disabled','disabled');
                    $('#input-to-hours-'+i).attr('disabled','disabled');
                    $('#input-to-minutes-'+i).attr('disabled','disabled');
                    set_closed(i,true);
                }
            } 
            set_allday_time();
        };
        
        var set_allday_time=function(){
            for(var i=0;i<7;i++){
                self.formData[i]={from:{hours:0,minutes:0},to:{hours:0,minutes:0}};
            }
        };
        
        this.weekends=function(){
            for(var i=0;i<self.closedFlags.length;i++){
                if(i<5){
                    self.closedFlags[i]=true;
                }
                else {
                    self.closedFlags[i]=false; 
                }
                if(!self.closedFlags[i]){
                        $('#input-from-hours-'+i).removeAttr('disabled');
                        $('#input-from-minutes-'+i).removeAttr('disabled');
                        $('#input-to-hours-'+i).removeAttr('disabled');
                        $('#input-to-minutes-'+i).removeAttr('disabled');
                        set_closed(i,false);
                    }
                    else if(self.closedFlags[i]){
                        $('#input-from-hours-'+i).attr('disabled','disabled');
                        $('#input-from-minutes-'+i).attr('disabled','disabled');
                        $('#input-to-hours-'+i).attr('disabled','disabled');
                        $('#input-to-minutes-'+i).attr('disabled','disabled');
                        set_closed(i,true);
                    }
            } 
            init_default();
        };
        
        this.weekdays=function(){
            for(var i=0;i<self.closedFlags.length;i++){
                if(i<5){
                    self.closedFlags[i]=false;
                }
                else {
                    self.closedFlags[i]=true; 
                }
                if(!self.closedFlags[i]){
                        $('#input-from-hours-'+i).removeAttr('disabled');
                        $('#input-from-minutes-'+i).removeAttr('disabled');
                        $('#input-to-hours-'+i).removeAttr('disabled');
                        $('#input-to-minutes-'+i).removeAttr('disabled');
                        set_closed(i,false);
                    }
                    else if(self.closedFlags[i]){
                        $('#input-from-hours-'+i).attr('disabled','disabled');
                        $('#input-from-minutes-'+i).attr('disabled','disabled');
                        $('#input-to-hours-'+i).attr('disabled','disabled');
                        $('#input-to-minutes-'+i).attr('disabled','disabled');
                        set_closed(i,true);
                    }
            } 
            init_default();
        };
});
