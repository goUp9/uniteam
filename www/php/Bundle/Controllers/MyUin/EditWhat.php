<?php
namespace Bundle\Controllers\MyUin;
class EditWhat extends \Modules\Users\Profile{
    public $repository=array("UINQuery","QueryWhat","Tags");
    
    function main(){
        $this->unlogged();
        
        $queries=$this->get_current_query();
        
        $this->Kernel->Content->set_data($this->Kernel->Request->get['type'],'linkBack');
        
        $Form=new EditWhatForm($this->Kernel,"form__generic",'',$queries);
        $this->Kernel->Content->set_form($Form->form,'search');
        
        return $this->Kernel;
    }
    
    function get_current_query(){
        $QB = $this->Kernel->entityManager->createQueryBuilder();
        $QB->select('q', 'qw,t')
        ->from($this->get_repository()[0], 'q')
        ->leftJoin('q.whats', 'qw')
        ->leftJoin('qw.tag', 't')
        ->where('q.idUser='.$this->Kernel->Session->access->user['id'])                
        ->andWhere("q.id='".$this->Kernel->Request->get['query_id']."'")
        ->andWhere("t.status=1")
        ->orderBy("q.id","DESC");
        $Queries= $QB->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $Queries;
    }
    
    function action_edit(){
        $this->unlogged();
        if(\Core\Utils::is_ajax()){
            
            
            $QueriesWhat = $this->Kernel->entityManager->getRepository($this->get_repository()[1])->findByIdQuery($this->Kernel->Request->post['idQuery']);
            
            foreach($QueriesWhat as $Q){
                $this->Kernel->entityManager->remove($Q);
                $this->Kernel->entityManager->flush();
            }
            
            $Query=$this->Kernel->entityManager->getRepository($this->get_repository()[0])->findOneById($this->Kernel->Request->post['idQuery']);               

            $tags=  explode(',', trim($this->Kernel->Request->post['tags'],','));
            
            foreach($tags as $tag){
                if($tag!==''){
                    $Tag=$this->Kernel->entityManager->getRepository($this->get_repository()[2])->findOneBy(array('tag'=>$tag));
                    if($Tag===NULL){
                        $Tag=new \Bundle\Doctrine\Entities\Tags();
                        $Tag->setTag($tag);
                        $Tag->setStatus(TRUE);
                        $this->Kernel->entityManager->persist($Tag);
                    }
                    $QueryWhat=new \Bundle\Doctrine\Entities\QueryWhat();                                            
                    $QueryWhat->setTag($Tag);
                    $QueryWhat->setIdQuery($Query);
                    $this->Kernel->entityManager->persist($QueryWhat);
                    $this->Kernel->entityManager->flush();
                }
            }                

            $this->Kernel->entityManager->persist($Query);
            $this->Kernel->entityManager->flush();
//            \Dev\Debug::dump($Query);
        }
    }
    
}
