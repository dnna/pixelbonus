<?php
namespace Pixelbonus\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class QrSet {
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\Course", inversedBy="qrSets", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $course;
    /**
     * @ORM\Column(type="string")
     */
    protected $salt;
    /**
     * @ORM\Column(type="integer")
     */
    protected $firstFree;

    protected $quantity; // Non-persistent

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getCourse() {
        return $this->course;
    }

    function getSalt() {
        return $this->salt;
    }

    function getFirstFree() {
        return $this->firstFree;
    }

    function setCourse($course) {
        $this->course = $course;
    }

    function setSalt($salt) {
        $this->salt = $salt;
    }

    function setFirstFree($firstFree) {
        $this->firstFree = $firstFree;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}

?>