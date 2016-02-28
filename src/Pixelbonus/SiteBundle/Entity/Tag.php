<?php
namespace Pixelbonus\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Tag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;
    /**
     * @ORM\ManyToMany(targetEntity="Pixelbonus\SiteBundle\Entity\QrSet", mappedBy="tags", cascade={"persist"})
     * @Exclude
     */
    private $qrsets;

    function __construct() {
        $this->qrsets = new ArrayCollection();
    }

    function getId() {
        return $this->id;
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

    function getQrsets() {
        return $this->qrsets;
    }

    function setQrsets($qrsets) {
        $this->qrsets = $qrsets;
    }
}