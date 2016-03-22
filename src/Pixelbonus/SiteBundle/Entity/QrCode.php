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
class QrCode {
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $code;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\QrSet", inversedBy="qrcodes", fetch="EAGER")
     * @ORM\JoinColumn(name="qrset_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $qrset;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\QrRequest", inversedBy="qrcodes", fetch="EAGER")
     * @ORM\JoinColumn(name="qrrequest_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $qrrequest;
     /**
     * @ORM\OneToMany(targetEntity="Pixelbonus\SiteBundle\Entity\Redemption", mappedBy="qrcode")
     * @ORM\OrderBy({"participantNumber" = "ASC"})
     */
    protected $redemptions;

    public function __construct() {
        $this->redemptions = new ArrayCollection();
    }

    function getCode() {
        return $this->code;
    }

    function getQrset() {
        return $this->qrset;
    }

    function getQrrequest() {
        return $this->qrrequest;
    }

    function getRedemptions() {
        return $this->redemptions;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setQrset($qrset) {
        $this->qrset = $qrset;
    }

    function setQrrequest($qrrequest) {
        $this->qrrequest = $qrrequest;
    }

    function setRedemptions($redemptions) {
        $this->redemptions = $redemptions;
    }
}

?>