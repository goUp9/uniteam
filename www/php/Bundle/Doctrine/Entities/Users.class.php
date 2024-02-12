<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Users.php
/**
 * @Entity @Table(name="Users") @HasLifecycleCallbacks
 * */
class Users {

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;    
    
    /** @Column(type="string", nullable=TRUE) */
    protected $googlePlusId;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $twitterId;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $facebookId;

    /** @Column(type="string", nullable=TRUE) */
    protected $username;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $password; 
    
    /** @Column(type="string", name="first_name", nullable=TRUE) */
    protected $fName;
    
    /** @Column(type="string", name="last_name", nullable=TRUE) */
    protected $lName;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $email;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $phone;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $mobile;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $address;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $city;    
    
    /** @Column(type="string", nullable=TRUE) */
    protected $state;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $country;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $zip;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $paypal;
    
    /** @Column(type="boolean", nullable=TRUE, options={"default":0}) */
    protected $status;
    
    /** @Column(type="string", nullable=TRUE, options={"default":NULL}) */
    protected $varificationEmail;
    
    /** @Column(type="string", nullable=TRUE) */
    protected $salt;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /** @Column(type="boolean", nullable=TRUE) */
    protected $feedbackBlocked;
    
    /** @Column(type="boolean", nullable=TRUE) */
    protected $newsletterSubscribed=NULL;
    
    /**  
     *  @OneToMany(targetEntity="UINQuery", mappedBy="idUser", cascade={"persist", "remove"}) 
    **/
    public $queries;
    
    /**  
     *  @OneToMany(targetEntity="UserNotifications", mappedBy="idRecipient", cascade={"persist", "remove"}) 
    **/
    public $UserNotifications;
    
    /*
     * @OneToMany(targetEntity="Tags", mappedBy="idUser")
     */
    protected $tags;   
    
    /**  
     *  @ManyToMany(targetEntity="UINQuery", mappedBy="cSuppliers")
     *  @JoinTable(name="uinqueries_competing_suppliers",joinColumns={@JoinColumn(name="supplier", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="uinquery", referencedColumnName="id",nullable=true)})  
     **/
    private $cSuppliersQueries;
    
        
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now", new \DateTimeZone('Europe/London'));
    }

    public function getId() {
        return $this->id;
    }
    
    public function getGooglePlusId(){
        return $this->googlePlusId;
    }
    
    public function setGooglePlusId($googlePlusId){
        $this->googlePlusId=$googlePlusId;
    }
    
    public function getTwitterId(){
        return $this->twitterId;
    }
    
    public function setTwitterId($twitterId){
        $this->twitterId=$twitterId;
    }
    
    public function getFacebookId(){
        return $this->facebookId;
    }
    
    public function setFacebookId($facebookId){
        $this->facebookId=$facebookId;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $password=\Modules\Users\Registration::generate_password($password);
        $this->password = $password['hash'];
        $this->setSalt($password['salt']);
    }
    
    public function getFName() {
        return $this->fName;
    }

    public function setFName($fName) {
        $this->fName = $fName;
    }
    
    public function getLName() {
        return $this->lName;
    }

    public function setLName($lName) {
        $this->lName = $lName;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }
    
    public function getMobile() {
        return $this->mobile;
    }

    public function setMobile($mobile) {
        $this->mobile = $mobile;
    }
    
    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }
    
    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }
    
    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }
    
    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }
    
    public function getZip() {
        return $this->zip;
    }

    public function setZip($zip) {
        $this->zip = $zip;
    }
    
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getPaypal() {
        return $this->paypal;
    }

    public function setPaypal($paypal) {
        $this->paypal = $paypal;
    }
    
    public function getVarificationEmail() {
        return $this->varificationEmail;
    }

    public function setVarificationEmail($vEmail) {
        $this->varificationEmail = $vEmail;
    }
    
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt= $salt;
    }
    
    public function getFeedbackBlocked()
    {
        return $this->feedbackBlocked;
    }
    
    public function setFeedbackBlocked($blocked=TRUE)
    {
        $this->feedbackBlocked = $blocked;
    }
    
    public function getNewsletterSubscribed()
    {
        return $this->newsletterSubscribed;
    }
    
    public function setNewsletterSubscribed($newsletterSubscribed)
    {
        $this->newsletterSubscribed = $newsletterSubscribed;
    }
    
    public function getUserNotifications(){
        return $this->UserNotifications;
    }
    
    public function setUserNotificaions(UserNotifications $UserNotification = NULL){        
        $this->UserNotifications = $UserNotification;
        return $this;
    }
    
    /** @PrePersist */
    public function preCreate(){
        $this->setDateCreated();
    }

}

?>
