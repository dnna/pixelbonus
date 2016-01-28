<?php
namespace Pixelbonus\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\Exclude;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Course {
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\UserBundle\Entity\User", inversedBy="courses", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @Exclude
     */
    protected $user;
    /**
     * @ORM\Column(type="string")
     */
    protected $name;
     /**
     * @ORM\OneToMany(targetEntity="Pixelbonus\SiteBundle\Entity\QrSet", mappedBy="course")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @Exclude
     */
    protected $qrSets;

    public function __construct() {
        $this->qrSets = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function getName() {
        return $this->name;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getQrSets() {
        return $this->qrSets;
    }

    function setQrSets($qrSets) {
        $this->qrSets = $qrSets;
    }
}

?>