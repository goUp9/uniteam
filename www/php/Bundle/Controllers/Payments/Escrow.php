<?php
namespace Bundle\Controllers\Payments;
class Escrow {
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;        
    }
    
    public function main(){
        $this->init_payment_button(100,'USD','eng.szappala-facilitator@gmail.com');
        return $this->Kernel;
    }
    
    public function init_payment_button($amount, $currencyCode,$toBusiness){
        $this->Kernel->Content->set_data($amount,'amount');
        $this->Kernel->Content->set_data($currencyCode,'currency_code');
        $this->Kernel->Content->set_data($toBusiness,'business');        
    }
    
    public function insert_payment_button($amount, $currencyCode,$toBusiness){
        return [
            'amount'=>$amount,
            'currency_code'=>$currencyCode,
            'business'=>$toBusiness
        ];
    }
    
}
