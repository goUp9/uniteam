<?php
namespace Bundle\Doctrine\Entities;
// php/bundle/doctrine/entities/Places.php
/**
 * @Entity @Table(name="Places")
 **/

class Places {
    /** @Id @Column(type="string",unique=TRUE) **/
    protected $placeId;
    
    /** @Column(type="string") */
    protected $lng;
    
    /** @Column(type="string") */
    protected $lat;
    
    /** @Column(type="string") */
    protected $formattedAddress;
    
    /** @Column(type="datetime", name="date_created") */
    protected $dateCreated;
    
    /*
     * @OneToMany(targetEntity="QueryWhat", mappedBy="tag", cascade={"persist", "remove"})
     */
    protected $querieswhat;
    
    public function getPlaceId(){
        return $this->placeId;
    }
    
    public function setPlaceId($placeId){
        $this->placeId=$placeId;
    }

    public function getLng(){
        return $this->lng;
    }

    public function setLng($lng){
        $this->lng = $lng;
    }
    
    public function getLat(){
        return $this->lat;
    }

    public function setLat($lat){
        $this->lat = $lat;
    }
    
    public function getFormattedAddress(){
        return $this->formattedAddress;
    }

    public function setFormattedAddress($formattedAddress){
        $this->formattedAddress = $formattedAddress;
    }
    
    public function getDateCreated(){
        return $this->dateCreated;
    }
    
    public function setDateCreated(){
        $this->dateCreated = new \DateTime("now");
    }
    
}

?>
