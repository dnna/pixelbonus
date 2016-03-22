<?php
namespace Pixelbonus\SiteBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class QrRequest {
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\QrSet", inversedBy="qrcodes", fetch="EAGER")
     * @ORM\JoinColumn(name="qrset_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $qrset;
     /**
     * @ORM\OneToMany(targetEntity="Pixelbonus\SiteBundle\Entity\QrCode", mappedBy="qrrequest")
     */
    protected $qrcodes;
    /**
     * @ORM\Column(type="string")
     */
    protected $status = self::STATUS_PENDING;
    const STATUS_PENDING = 'PENDING';
    const STATUS_FULFILLED = 'FULFILLED';
    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    public function __construct() {
        $this->qrcodes = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getQrset() {
        return $this->qrset;
    }

    function getQrcodes() {
        return $this->qrcodes;
    }

    function getStatus() {
        return $this->status;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setQrset($qrset) {
        $this->qrset = $qrset;
    }

    function setQrcodes($qrcodes) {
        $this->qrcodes = $qrcodes;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}

?>