<?php
namespace Bundle\Controllers\Commons;
class NewsletterPromt extends \Modules\Modules{
    
    function promt(){
        if(isset($_SESSION['promtNewsletter'])&&$_SESSION['promtNewsletter']===TRUE){             
            $this->Kernel->Content->set_data(TRUE,'newsletterPromtFlag');
        }
    }
    
}
