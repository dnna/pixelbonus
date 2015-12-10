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
     * @ORM\ManyToOne(targetEntity="Pixelbonus\SiteBundle\Entity\QrSet", inversedBy="redemptions", fetch="EAGER")
     * @ORM\JoinColumn(name="qrset_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $qrset;
    /**
     * @ORM\Column(type="string")
     */
    protected $qrsetSequenceNum;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $participantNumber;

    function getId() {
        return $this->id;
    }

    function getQrset() {
        return $this->qrset;
    }

    function getQrsetSequenceNum() {
        return $this->qrsetSequenceNum;
    }

    function getParticipantNumber() {
        return $this->participantNumber;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setQrset($qrset) {
        $this->qrset = $qrset;
    }

    function setQrsetSequenceNum($qrsetSequenceNum) {
        $this->qrsetSequenceNum = $qrsetSequenceNum;
    }

    function setParticipantNumber($participantNumber) {
        $this->participantNumber = $participantNumber;
    }
}

?>