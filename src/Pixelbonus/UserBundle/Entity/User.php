<?php
namespace Pixelbonus\UserBundle\Entity;

use Pixelbonus\SiteBundle\Entity\Dog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use FOS\UserBundle\Entity\User as BaseUser;
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
     * @ORM\Column(name="organization", type="string", nullable=true)
     */
    protected $organization;
    /**
     * @ORM\Column(name="department", type="string", nullable=true)
     */
    protected $department;
     /**
     * @ORM\OneToMany(targetEntity="Pixelbonus\SiteBundle\Entity\Course", mappedBy="user")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    protected $courses;
    /**
     * @ORM\Column(name="preferred_grading_model", type="string", nullable=true)
     */
    protected $preferredGradingModel = 'reduction';

    public function __construct() {
        parent::__construct();
        $this->courses = new ArrayCollection();
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

    function getOrganization() {
        return $this->organization;
    }

    function getDepartment() {
        return $this->department;
    }

    function setOrganization($organization) {
        $this->organization = $organization;
    }

    function setDepartment($department) {
        $this->department = $department;
    }

    function getCourses() {
        return $this->courses;
    }

    function setCourses($courses) {
        $this->courses = $courses;
    }

    function getPreferredGradingModel() {
        return $this->preferredGradingModel;
    }

    function setPreferredGradingModel($preferredGradingModel) {
        $this->preferredGradingModel = $preferredGradingModel;
    }
}