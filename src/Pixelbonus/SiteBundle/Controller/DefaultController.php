<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Pixelbonus\SiteBundle\Entity\Course;
use Pixelbonus\SiteBundle\Form\Type\CourseType;

use Pixelbonus\SiteBundle\Entity\QrSet;
use Pixelbonus\SiteBundle\Form\Type\QrSetType;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function indexAction() {
        return $this->render('PixelbonusSiteBundle:Default:index.html.twig', array());
    }

    /**
     * @Route("/courses", name="courses")
     * @Secure(roles="ROLE_USER")
     */
    public function coursesAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user->getCourses()->count() > 0) {
            return $this->render('PixelbonusSiteBundle:Courses:courses.html.twig', array(
                'courses' => $user->getCourses(),
            ));
        } else {
            $course = new Course();
            $course->setUser($user);
            $form = $this->createForm(new CourseType(), $course,  array());
            return $this->render('PixelbonusSiteBundle:Courses:no_courses.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/courses_new", name="new_course")
     * @Secure(roles="ROLE_USER")
     */
    public function newCourseAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $course = new Course();
        $course->setUser($user);
        $form = $this->createForm(new CourseType(), $course,  array());
        if ('POST' == $this->getRequest()->getMethod()) {
            // parameter handling
            $form->bind($this->getRequest());

            if(!$this->getRequest()->request->has($form->getName())) {
                echo 'No form fields specified'; die();
            } else if($form->isValid()) {
                $this->container->get('doctrine')->getManager()->persist($course);
                $this->container->get('doctrine')->getManager()->flush($course);

                if($this->getRequest()->request->has('_submit_another')) {
                    return new RedirectResponse($this->container->get('router')->generate('new_course'));
                } else {
                    return new RedirectResponse($this->container->get('router')->generate('courses'));
                }
            }
        }
        return $this->render('PixelbonusSiteBundle:Courses:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/courses/{course}", name="course")
     * @Secure(roles="ROLE_USER")
     */
    public function course(Course $course) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($course->getQrSets()->count() > 0) {
            return $this->render('PixelbonusSiteBundle:QR:qr_sets.html.twig', array(
                'course' => $course,
                'qrsets' => $course->getQrSets(),
            ));
        } else {
            $qrset = new QrSet();
            $qrset->setCourse($course);
            $form = $this->createForm(new QrSetType(), $qrset,  array());
            return $this->render('PixelbonusSiteBundle:QR:no_qr_sets.html.twig', array(
                'course' => $course,
                'form' => $form->createView(),
            ));
        }
    }
}
