<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\Serializer\SerializationContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Pixelbonus\SiteBundle\Entity\Course;
use Pixelbonus\SiteBundle\Form\Type\CourseType;
use Pixelbonus\SiteBundle\Form\Type\ImportType;

class CourseController extends Controller {
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
                $course->setHashedUrl(md5(time().'_'.$course->getUser()->getId().'_'.$course->getName()));
                $this->container->get('doctrine')->getManager()->persist($course);
                $this->container->get('doctrine')->getManager()->flush($course);

                return new RedirectResponse($this->container->get('router')->generate('courses'));
            }
        }
        return $this->render('PixelbonusSiteBundle:Courses:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/course/{course}/delete", name="delete_course")
     * @Secure(roles="ROLE_USER")
     */
    public function deleteCourse(Course $course) {
        $this->container->get('doctrine')->getManager()->remove($course);
        $this->container->get('doctrine')->getManager()->flush($course);
        return new RedirectResponse($this->container->get('router')->generate('courses'));
    }

    /**
     * @Route("/course/{course}/export", name="export_course")
     * @Secure(roles="ROLE_USER")
     */
    public function exportCourse(Course $course) {
        $serializer = $this->container->get('jms_serializer');
        $serializationContext = SerializationContext::create();
        $serializationContext->setGroups(array('list'));
        $response = new Response($serializer->serialize($course, 'xml'));
        $response->headers->set('Content-Type', 'application/xml');
        $response->headers->set('Content-Disposition', 'attachment; filename=pixelbonus_course_'.$course->getId().'.xml');
        return $response;
    }

    /**
     * @Route("/course_import", name="import_course")
     * @Secure(roles="ROLE_USER")
     */
    public function importCourse() {
        $form = $this->createForm(new ImportType());
        if ('POST' == $this->getRequest()->getMethod()) {
            // parameter handling
            $form->bind($this->getRequest());

            if(!$this->getRequest()->files->has($form->getName())) {
                echo 'No form fields specified'; die();
            } else if($form->isValid()) {
                $data = $form->getData();
                if($data['courseFile']->getMimeType() != 'application/xml') { echo 'File is not application/xml'; die(); }
                $path = $data['courseFile']->getPath().DIRECTORY_SEPARATOR.$data['courseFile']->getFilename();
                $course = $this->container->get('jms_serializer')->deserialize(file_get_contents($path), 'Pixelbonus\SiteBundle\Entity\Course', 'xml');
                $course->setUser($this->container->get('security.context')->getToken()->getUser());
                $origCourse = $this->container->get('doctrine')->getRepository(get_class($course))->find($course->getId());
                if(isset($origCourse)) { echo 'This course already exists.'; die(); }
                $toFlush = array($course);
                $this->container->get('doctrine')->getManager()->persist($course);
                if($course->getQrSets() != null) {
                    foreach($course->getQrSets() as $curQrSet) {
                        $curQrSet->setCourse($course);
                        $this->container->get('doctrine')->getManager()->persist($curQrSet);
                        $toFlush[] = $curQrSet;
                        if($curQrSet->getQrCodes() == null) { continue; }
                        foreach($curQrSet->getQrCodes() as $curQrCode) {
                            $curQrCode->setQrSet($curQrSet);
                            $this->container->get('doctrine')->getManager()->persist($curQrCode);
                            $toFlush[] = $curQrCode;
                            if($curQrCode->getRedemptions() == null) { continue; }
                            foreach($curQrCode->getRedemptions() as $curRedemption) {
                                $curRedemption->setQrcode($curQrCode);
                                $this->container->get('doctrine')->getManager()->persist($curRedemption);
                                $toFlush[] = $curRedemption;
                            }
                        }
                    }
                }
                $this->container->get('doctrine')->getManager()->flush($toFlush);

                return new RedirectResponse($this->container->get('router')->generate('courses'));
            }
        }
        return $this->render('PixelbonusSiteBundle:Courses:import.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
