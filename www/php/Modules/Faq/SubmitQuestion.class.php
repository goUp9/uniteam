<?php
namespace Modules\Faq;
/**
 * Description of SubmitQuestion
 *
 * @author Anastasia
 */
class SubmitQuestion {
    
    public function __construct(\Core\Kernel $Kernel) {
        $this->Kernel=$Kernel;
    }
    
    protected $question_length=255;
    
    public function set_question_length($length){
        $this->question_length=$length;
    }
    
    public function set_new_question($question){
        if (strlen($question)<=$this->question_length){            
            $entry=new \Bundle\Doctrine\Entities\Faq();
            $entry->setQuestion($question);
            $entry->setStatus(0);
            $this->Kernel->entityManager->persist($entry);
            $this->Kernel->entityManager->flush();
            return true;
        }
        else {
            return false;
        }
    }
}
