<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/AdminUsers.php
/**
 * @Entity @Table(name="admin_users")
 **/

class AdminUsers {
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    
    /** @Column(type="string", name="login") */
    protected $username;
    
    /** @Column(type="string") */
    protected $password; 
    
    /** @Column(type="string") */
    protected $salt;
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $password=\Modules\Users\Registration::generate_password($password);
        $this->password = $password['hash'];
        $this->setSalt($password['salt']);
    }
    
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt= $salt;
    }
    
}

?>
