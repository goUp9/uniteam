<?php
namespace Modules\UserNotifications;
class Notification extends \Modules\Modules{
    public $repository="UserNotifications";
    
    public $Entity;
    
    public static $types=[
        0=>'supplier selected',
        1=>'funds escrowed',
        2=>'new advice request',
        3=>'new advice',
        4=>'new asker request',
        5=>'confirmation for asker',
        6=>'give feedback',
        7=>'zero feedback',
        8=>'supplier paid',
        9=>'adviser paid'
    ];
    const TYPE_SUPPLIER_SELECTED=['code'=>0,'title'=>'supplier selected','template'=>'supplier_selected'];
    const TYPE_FUNDS_ESCROWED=['code'=>1,'title'=>'funds escrowed','template'=>'funds_escrowed'];    
    const TYPE_NEW_ADVICE_REQUEST=['code'=>2,'title'=>'new advice request','template'=>'new_advice_request'];
    const TYPE_NEW_ADVICE=['code'=>3,'title'=>'new advice','template'=>'new_advice'];
    const TYPE_NEW_ASKER_REQUEST=['code'=>4,'title'=>'new asker request','template'=>'new_asker_request'];
    const TYPE_REASSURE_ASKER=['code'=>5,'title'=>'confirmation for asker','template'=>'asker_confirmation'];
    const TYPE_GIVE_FEEDBACK=['code'=>6,'title'=>'give feedback','template'=>'give_feedback'];
    const TYPE_ZERO_FEEDBACK=['code'=>7,'title'=>'zero feedback','template'=>'zero_feedback'];
    const TYPE_SUPPLIER_PAID=['code'=>8,'title'=>'supplier paid','template'=>'supplier_paid'];
    const TYPE_ADVISER_PAID=['code'=>9,'title'=>'adviser paid','template'=>'adviser_paid'];
    
    public function __construct(\Core\Kernel $Kernel, $Entity) {
        parent::__construct($Kernel);
        $this->Entity=$Entity;
    }
    
    public static function new_notification(\Core\Kernel $Kernel, \Bundle\Doctrine\Entities\Users $Recipient, \Bundle\Doctrine\Entities\UINQuery $Query, $type) {
        $Entity=new \Bundle\Doctrine\Entities\UserNotifications();
        $Entity->setIdRecipient($Recipient);
        $Entity->setIdQuery($Query);
        $Entity->setType($type['code']);
        
        return new Notification($Kernel, $Entity);
    }
    
    public function send(){
        $this->Kernel->entityManager->persist($this->Entity);
        $this->Kernel->entityManager->flush();
    }
}
