<?php
namespace Pixelbonus\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Redemption {
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\QrCode", inversedBy="redemptions", fetch="EAGER")
     * @ORM\JoinColumn(name="qrcode_code", referencedColumnName="code", onDelete="CASCADE")
     */
    protected $qrcode;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $participantNumber;

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getQrcode() {
        return $this->qrcode;
    }

    function setQrcode($qrcode) {
        $this->qrcode = $qrcode;
    }

    function getParticipantNumber() {
        return $this->participantNumber;
    }

    function setParticipantNumber($participantNumber) {
        $this->participantNumber = $participantNumber;
    }
}

?>