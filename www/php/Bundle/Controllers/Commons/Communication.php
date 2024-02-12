<?php
namespace Bundle\Controllers\Commons;
class Communication extends \Modules\Modules {
    
    function get_unread_notifications(){
        if(isset($this->Kernel->Session->access->user)&&!empty($this->Kernel->Session->access->user)){
            $Notification=new \Bundle\Controllers\Communication\Notifications($this->Kernel);
            $num_unread=$Notification->api_count_unread();
            $this->Kernel->Content->set_data($num_unread,"unread_notifications");
        }
    }
    
    function get_unread_msgs(){
        if(isset($this->Kernel->Session->access->user)&&!empty($this->Kernel->Session->access->user)){
            $Msgs=new \Bundle\Controllers\Communication\Messages($this->Kernel);
            $num_unread=$Msgs->api_count_unread();
            $this->Kernel->Content->set_data($num_unread,"unread_msgs");
        }
    }
    
}
