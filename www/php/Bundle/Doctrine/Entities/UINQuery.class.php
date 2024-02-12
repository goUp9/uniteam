<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/UINQuery.php
/**
 * @Entity @Table(name="UINQuery") @HasLifecycleCallbacks
 **/

class UINQuery {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
        
    /**  
     *  @ManyToOne(targetEntity="Users", inversedBy="UINQuery", cascade={"persist", "remove"})
     *  @JoinColumn(name="idUser", referencedColumnName="id", nullable=TRUE) 
    **/
    protected $idUser;
    
    /**  
     *  @OneToMany(targetEntity="QueryWhat", mappedBy="idQuery", cascade={"persist", "remove"}) 
    **/
    public $whats;
    
    /**  
     *  @OneToMany(targetEntity="Advices", mappedBy="idQuery", cascade={"persist", "remove"}) 
    **/
    public $Advices;
    
    /**  
     *  @OneToMany(targetEntity="Advices", mappedBy="idQuery", cascade={"persist", "remove"}) 
    **/
    public $AdvicedBy;
    
    /**  
     *  @OneToMany(targetEntity="UserNotifications", mappedBy="idQuery", cascade={"persist", "remove"}) 
    **/
    public $UserNotifications;
    
    /**  
     *  @OneToMany(targetEntity="QueryWhere", mappedBy="idQuery", cascade={"persist", "remove"}) 
    **/
    public $wheres;
    
    /** @Column(type="string") */
    protected $type;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $status;
    
    /** @Column(type="boolean", nullable=TRUE) */
    protected $supplierPaid;
    
    /** @Column(type="float", nullable=TRUE) */
    protected $supplierPaidAmount;
    
    /** @Column(type="boolean", nullable=TRUE) */
    protected $adviserPaid;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isExpired;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isAwaitingEscrow;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isFinished;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isPending;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":TRUE}) */
    protected $isIncomplete;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":FALSE}) */
    protected $isArchived;
    
    /** @Column(type="float", nullable=TRUE) */
    protected $adviserPaidAmount;
    
    /** @Column(type="float", nullable=TRUE) */
    protected $uinPaidAmount;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /** @Column(type="datetime", name="date_escrowed", nullable=TRUE) */
    protected $dateEscrowed;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $timezone;
    
    /**  
     *  @ManyToMany(targetEntity="QueryWhenSchedule", inversedBy="UINQuery", cascade={"persist", "remove"})
     *  @JoinTable(name="uinqueries_schedules",joinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="schedule", referencedColumnName="id")})
    **/
    private $QueryWhenSchedule;
    
    /**  
     *  @ManyToMany(targetEntity="QueryWhenAsker", inversedBy="UINQuery", cascade={"persist", "remove"})
     *  @JoinTable(name="uinqueries_whens",joinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="idWhen", referencedColumnName="id")})
    **/
    private $QueryWhenAsker;
    
    /**
     * @OneToOne(targetEntity="FinalSupplier", mappedBy="UINQuery")
     * @JoinColumn(nullable=true)
     */
    private $finalSupplier;
    
    /**
     * @ManyToOne(targetEntity="Users", inversedBy="UINQuery")
     * @JoinColumn(nullable=true)
     */
    private $chosenSupplier;
    
    /**
     * @ManyToOne(targetEntity="Users", inversedBy="UINQuery")
     * @JoinColumn(nullable=true)
     */
    private $chosenAdviser;
    
    /**  
     *  @ManyToMany(targetEntity="Users", inversedBy="UINQuery")
     *  @JoinTable(name="uinqueries_competing_suppliers",joinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="supplier", referencedColumnName="id")})
    **/
    private $cSuppliers;
    
    /**  
     *  @ManyToMany(targetEntity="Users", inversedBy="UINQuery")
     *  @JoinTable(name="uinqueries_competing_advisers",joinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="adviser", referencedColumnName="id")})
    **/
    private $cAdvisers;
    
    /**
     * @OneToOne(targetEntity="FinalAsker", mappedBy="UINQuery")
     * @JoinColumn(name="finalAsker_id", referencedColumnName="id", nullable=true)
     */
    private $finalAsker;
    
    /**  
     *  @ManyToMany(targetEntity="SuppliersFeedback", inversedBy="UINQuery")
     *  @JoinTable(name="uinqueries_suppliers_feedback",joinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="supplier", referencedColumnName="id")})
    **/
    private $SuppliersFeedback;
    
    /**
     * @OneToOne(targetEntity="SuppliersFeedback", mappedBy="UINQuery")
     * @JoinColumn(nullable=true, name="Feedback_id", onDelete="SET NULL")
     */
    private $LeftFeedback;
    
    public function __construct() {
        $this->QueryWhenSchedule = new \Doctrine\Common\Collections\ArrayCollection();
        $this->QueryWhenAsker = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cSuppliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cAdvisers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->SuppliersFeedback = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Advices = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }
    
    public function getSupplierPaid()
    {
        return $this->supplierPaid;
    }

    public function setSupplierPaid($paid=TRUE)
    {
        $this->supplierPaid = $paid;
    }
    
    public function getAdvices()
    {
        return $this->Advices;
    }

    public function setAdvices(Advices $Advices)
    {
        $this->Advices[] = $Advices;        
    }
    
    public function getAdvicedBy()
    {
        return $this->AdvicedBy;
    }

    public function setAdvicedBy(Advices $AdvicedBy)
    {
        $this->AdvicedBy[] = $AdvicedBy;        
    }
    
    public function getSupplierPaidAmount()
    {
        return $this->supplierPaidAmount;
    }
    
    public function setSupplierPaidAmount($amount)
    {
        $this->supplierPaidAmount = $amount;
    }
    
    public function getAdviserPaid()
    {
        return $this->adviserPaid;
    }
    
    public function getAdviserPaidAmount()
    {
        return $this->adviserPaidAmount;
    }
    
    public function setAdviserPaidAmount($amount)
    {
        $this->adviserPaidAmount = $amount;
    }    
    
    public function getUinPaidAmount()
    {
        return $this->uinPaidAmount;
    }
    
    public function setUinPaidAmount($amount)
    {
        $this->uinPaidAmount = $amount;
    }

    public function setAdviserPaid($paid=TRUE)
    {
        $this->adviserPaid = $paid;
    }
    
    public function setIsExpired($isExpired=FALSE)
    {
        $this->isExpired = $isExpired;
        if($isExpired){
            $this->setIsIncomplete(FALSE);
            $this->setIsPending(FALSE);
        }
    }
    
    public function getIsExpired()
    {
        return $this->isExpired;
    }
    
    public function setIsFinished($isFinished=FALSE)
    {
        $this->isFinished = $isFinished;
        if($isFinished){
            $this->setIsAwaitingEscrow(FALSE);
        }
    }
    
    public function getIsFinished()
    {
        return $this->isFinished;
    }
    
    public function setIsPending($isPending=FALSE)
    {
        $this->isPending = $isPending;
        if($isPending){
            $this->isIncomplete=FALSE;
        }
    }
    
    public function getIsPending()
    {
        return $this->isPending;
    }
    
    public function setIsArchived($isArchived=FALSE)
    {
        $this->isArchived = $isArchived;
    }
    
    public function getIsArchived()
    {
        return $this->isArchived;
    }
    
    public function setIsIncomplete($isIncomplete=TRUE)
    {
        $this->isIncomplete = $isIncomplete;
    }
    
    public function getIsIncomplete()
    {
        return $this->isIncomplete;
    }
    
    public function setIsAwaitingEscrow($isAwaitingEscrow=FALSE)
    {
        $this->isAwaitingEscrow = $isAwaitingEscrow;
        if($isAwaitingEscrow){
            $this->setIsIncomplete(FALSE);
            $this->setIsPending(FALSE);
        }
    }
    
    public function getIsAwaitingEscrow()
    {
        return $this->isAwaitingEscrow;
    }
    
    
    
    public function getUser(){
        return $this->idUser;
    }
    
    public function setIdUser(Users $idUser){
        $this->idUser=$idUser;
    }
    
    public function setWhats(QueryWhat $idWhat = NULL){        
        $this->whats = $idWhat;
        return $this;
    }
    
    public function getWhats(){        
        return $this->whats;
    }
    
    public function getWheres(){
        return $this->wheres;
    }
    
    public function setWheres(QueryWhere $idWhere = NULL){        
        $this->wheres = $idWhere;
        return $this;
    }
    
    public function getUserNotifications(){
        return $this->UserNotifications;
    }
    
    public function setUserNotificaions(UserNotifications $UserNotification = NULL){        
        $this->UserNotifications = $UserNotification;
        return $this;
    }
    
    public function setSchedules(QueryWhenSchedule $Schedules=NULL){
        $Schedules->addUinQuery($this); // synchronously updating inverse side
        $this->QueryWhenSchedule[] = $Schedules;
        return $this;
    }
    
    public function getWhens(){
        return $this->QueryWhenAsker;
    }
    
    public function setWhens(QueryWhenAsker $When=NULL){
        $When->addUinQuery($this); // synchronously updating inverse side
        $this->QueryWhenAsker[] = $When;
        return $this;
    }
    
    public function getCSuppliers()
    {
        return $this->cSuppliers;
    }
    
    public function setCSuppliers(Users $User=NULL){
        $this->cSuppliers[] = $User;
        return $this;
    }
    
     public function getCAdvisers()
    {
        return $this->cAdvisers;
    }
    
    public function setCAdvisers(Users $User=NULL){
        $this->cAdvisers[] = $User;
        return $this;
    }
    
    public function getQueryWhenAsker()
    {
        return $this->QueryWhenAsker;
    }
    
    public function setQueryWhenAsker(QueryWhenAsker $QueryWhenAsker){
        $this->QueryWhenAsker[] = $QueryWhenAsker;
        return $this;
    }
    
    public function getSuppliersFeedback()
    {
        return $this->SuppliersFeedback;
    }
    
    public function setSuppliersFeedback(SuppliersFeedback $SuppliersFeedback=NULL){
        $this->SuppliersFeedback[] = $SuppliersFeedback;
        return $this;
    }
    
    public function setLeftFeedback(SuppliersFeedback $LeftFeedback){
        $LeftFeedback->setAskQuery($this);
        $this->LeftFeedback=$LeftFeedback;
    }
    
    public function getLeftFeedback(){
        return $this->LeftFeedback;
    }
    
    public function removeSchedules()
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->QueryWhenSchedule->clear();
    }
    
    public function removeWhens()
    {
        //optionally add a check here to see that $group exists before removing it.
        return $this->QueryWhenAsker->clear();
    }
    
    public function get_finalAsker(){
        return $this->finalAsker;
    }
    
    public function set_finalAsker(FinalAsker $finalAsker){
        $this->finalAsker=$finalAsker;
    }
    
    public function get_finalSupplier(){
        return $this->finalSupplier;
    }
    
    public function set_finalSupplier(FinalSupplier $finalSupplier){
        $this->finalSupplier=$finalSupplier;
    }
    
    public function get_chosenSupplier(){
        return $this->chosenSupplier;
    }
    
    public function set_chosenSupplier(Users $chosenSupplier){
        $this->chosenSupplier=$chosenSupplier;
    }
    
    public function get_chosenAdviser(){
        return $this->chosenAdviser;
    }
    
    public function set_chosenAdviser(Users $chosenAdviser){
        $this->chosenAdviser=$chosenAdviser;
    }
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }
    
    public function getDateEscrowed(){
        return $this->dateEscrowed;
    }
    
    public function setDateEscrowed(){
        $this->dateEscrowed = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }
    
    /** @PrePersist */
    public function preCreate(){
        $this->setDateCreated();
        $this->setIsExpired();
        $this->setIsFinished();
        $this->setIsIncomplete();
        $this->setIsPending();
        $this->setIsAwaitingEscrow();
    }
    
       
    public function check_isExpired(){
        $When=$this->getQueryWhenAsker()[0];
        $expDate=$When->getExpDate();
        if(isset($expDate)&&empty($expDate)){
            $now=new \DateTime("now", new \DateTimeZone('Europe/London'));
            if($expDate>$now){
                $this->setIsExpired(TRUE);
            }
        }
    }
    
    public function archive(){
        $UserNotifications=$this->getUserNotifications();
        foreach($UserNotifications as $Un){
            $Un->setIsArchived(TRUE);
        }
        $this->setIsArchived(TRUE);
    }
    
    public function unarchive(){
        $UserNotifications=$this->getUserNotifications();
        foreach($UserNotifications as $Un){
            $Un->setIsArchived(FALSE);
        }
        $this->setIsArchived(FALSE);
    }
    
}

?>
