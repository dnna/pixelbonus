<?php
namespace Pixelbonus\UserBundle\Entity;

use Pixelbonus\SiteBundle\Entity\Dog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="Pixelbonus\UserBundle\Entity\Repositories\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;
    /**
     * @ORM\Column(name="surname", type="string", nullable=true)
     */
    protected $surname;
    /**
     * @ORM\Column(name="institution", type="string", nullable=true)
     */
    protected $institution;

    public function __construct() {
        parent::__construct();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    function getInstitution() {
        return $this->institution;
    }

    function setInstitution($institution) {
        $this->institution = $institution;
    }
}