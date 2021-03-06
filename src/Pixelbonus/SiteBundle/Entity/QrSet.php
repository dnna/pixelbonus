<?php
namespace Pixelbonus\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\Type;

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
     * @ORM\ManyToMany(targetEntity="Pixelbonus\SiteBundle\Entity\Tag", cascade={"persist"}, fetch="EAGER", inversedBy="qrsets")
     * @ORM\JoinTable(name="qrset_tags",
     *      joinColumns={@ORM\JoinColumn(name="qrset_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;
     /**
     * @ORM\OneToMany(targetEntity="Pixelbonus\SiteBundle\Entity\QrCode", mappedBy="qrset")
     */
    protected $qrcodes;
    /**
     * @Type("integer")
     */
    protected $quantity; // Non-persistent
    const DEFAULT_QUANTITY = 10;

    public function __construct() {
        $this->tags = new ArrayCollection();
        $this->qrcodes = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getCourse() {
        return $this->course;
    }

    function setCourse($course) {
        $this->course = $course;
    }

    function getTags() {
        return $this->tags;
    }

    function setTags($tags) {
        $this->tags = $tags;
    }

    function setTagsFromString($tagsFromString) {
        $this->tagsFromString = $tagsFromString;
    }

    function getTagsFromString() {
        $string = array();
        foreach($this->tags as $curTag) {
            $string[] = $curTag->getName();
        }
        return $string;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function getQrcodes() {
        return $this->qrcodes;
    }

    function setQrcodes($qrcodes) {
        $this->qrcodes = $qrcodes;
    }

    function getRedemptions() {
        $redemptions = new ArrayCollection();
        foreach($this->qrcodes as $curQrCode) {
            foreach($curQrCode->getRedemptions() as $curRedemption) {
                $redemptions->add($curRedemption);
            }
        }
        return $redemptions;
    }
}

?>