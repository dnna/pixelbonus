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
    protected $preferredGradingModel = 'curved_grading';
    /**
     * @ORM\Column(name="grade_multiplier", type="float", nullable=false)
     */
    protected $gradeMultiplier = 9;
    /**
     * @ORM\Column(name="max_grade", type="integer", nullable=false)
     */
    protected $maxGrade = 10;
    /**
     * @ORM\Column(name="min_grade", type="integer", nullable=false)
     */
    protected $minGrade = 10;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $locale = 'el';

    public function __construct() {
        parent::__construct();
        $this->courses = new ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getSurname() {
        return $this->surname;
    }

    function getOrganization() {
        return $this->organization;
    }

    function getDepartment() {
        return $this->department;
    }

    function getCourses() {
        return $this->courses;
    }

    function getPreferredGradingModel() {
        return $this->preferredGradingModel;
    }

    function getGradeMultiplier() {
        return $this->gradeMultiplier;
    }

    function getMaxGrade() {
        return $this->maxGrade;
    }

    function getMinGrade() {
        return $this->minGrade;
    }

    function getLocale() {
        return $this->locale;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSurname($surname) {
        $this->surname = $surname;
    }

    function setOrganization($organization) {
        $this->organization = $organization;
    }

    function setDepartment($department) {
        $this->department = $department;
    }

    function setCourses($courses) {
        $this->courses = $courses;
    }

    function setPreferredGradingModel($preferredGradingModel) {
        $this->preferredGradingModel = $preferredGradingModel;
    }

    function setGradeMultiplier($gradeMultiplier) {
        $this->gradeMultiplier = $gradeMultiplier;
    }

    function setMaxGrade($maxGrade) {
        $this->maxGrade = $maxGrade;
    }

    function setMinGrade($minGrade) {
        $this->minGrade = $minGrade;
    }

    function setLocale($locale) {
        $this->locale = $locale;
    }
}