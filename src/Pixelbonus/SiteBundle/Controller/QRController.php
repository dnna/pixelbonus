<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Base32\Base32;
use Pixelbonus\SiteBundle\Crypto\McryptCipher;

use Pixelbonus\SiteBundle\Entity\Course;
use Pixelbonus\SiteBundle\Form\Type\CourseType;

use Pixelbonus\SiteBundle\Entity\QrSet;
use Pixelbonus\SiteBundle\Entity\Tag;
use Pixelbonus\SiteBundle\Form\Type\QrSetType;

class QRController extends Controller {
    /**
     * @Route("/courses/{course}", name="course")
     * @Secure(roles="ROLE_USER")
     */
    public function course(Course $course) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($course->getUser() != $user) { throw new \Exception('User not authorized to access this course'); }
        if($course->getQrSets()->count() > 0) {
            return $this->render('PixelbonusSiteBundle:QR:course.html.twig', array(
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

    /**
     * @Route("/course/{course}/qrset/grades", name="course_grades")
     * @Secure(roles="ROLE_USER")
     */
    public function courseGrades(Course $course) {
        $redemptions = $this->container->get('doctrine')->getManager()->createQuery('SELECT r.participantNumber, COUNT(r) rcount FROM Pixelbonus\SiteBundle\Entity\Redemption r JOIN r.qrset qr WHERE qr.course = :course')->setParameter('course', $course)->getResult();
        return $this->render('PixelbonusSiteBundle:QR:course_grades.html.twig', array(
            'course' => $course,
            'redemptions' => $redemptions,
        ));
    }

    /**
     * @Route("/qrset_new/{course}", name="new_qrset")
     * @Secure(roles="ROLE_USER")
     */
    public function newQrSetAction(Course $course) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($course->getUser() != $user) { throw new \Exception('User not authorized to access this course'); }
        $qrset = new QrSet();
        $qrset->setCourse($course);
        $qrset->setQuantity($course->getEnrolledParticipants());
        $form = $this->createForm(new QrSetType(), $qrset,  array());
        if ('POST' == $this->getRequest()->getMethod()) {
            // parameter handling
            $form->bind($this->getRequest());

            if(!$this->getRequest()->request->has($form->getName())) {
                echo 'No form fields specified'; die();
            } else if($form->isValid()) {
                // Set the tags
                $qrset->getTags()->clear();
                foreach($qrset->tagsFromString as $curTag) {
                    $curTagEntity = $this->container->get('doctrine')->getManager()->getRepository('Pixelbonus\SiteBundle\Entity\Tag')->findOneBy(array(
                        'name' => $curTag,
                    ));
                    if(!isset($curTagEntity)) {
                        $curTagEntity = new Tag();
                        $curTagEntity->setName($curTag);
                        $this->container->get('doctrine')->getManager()->persist($curTagEntity);
                        $this->container->get('doctrine')->getManager()->flush($curTagEntity);
                    }
                    $qrset->getTags()->add($curTagEntity);
                }

                $this->container->get('doctrine')->getManager()->persist($qrset);
                $this->container->get('doctrine')->getManager()->flush($qrset);

                if($this->getRequest()->request->has('_print')) {
                    return new RedirectResponse($this->container->get('router')->generate('print_qr', array('qrset' => $qrset->getId(), 'quantity' => $qrset->getQuantity())));
                } else {
                    return new RedirectResponse($this->container->get('router')->generate('download_qr', array('qrset' => $qrset->getId(), 'quantity' => $qrset->getQuantity())));
                }
            }
        }
        // Get existing tags
        $existingTags = array();
        foreach($course->getQrSets() as $curQrSet) {
            foreach($curQrSet->getTags() as $curTag) {
                $existingTags[] = $curTag;
            }
        }
        return $this->render('PixelbonusSiteBundle:QR:new.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
            'existingTags' => $existingTags,
        ));
    }

    /**
     * @Route("/qrset/{qrset}", name="qrset")
     * @Secure(roles="ROLE_USER")
     */
    public function qrset(QrSet $qrset) {
        return $this->render('PixelbonusSiteBundle:QR:qr_set.html.twig', array(
            'qrset' => $qrset,
        ));
    }

    /**
     * @Route("/qrset/{qrset}/print", name="print_qr")
     * @Secure(roles="ROLE_USER")
     */
    public function printQr(QrSet $qrset) {
        if($this->getRequest()->get('quantity') == null) { echo 'Quantity is required'; die(); }
        return $this->render('PixelbonusSiteBundle:QR:print.html.twig', array(
            'qrset' => $qrset,
            'quantity' => $this->getRequest()->get('quantity'),
        ));
    }

    /**
     * @Route("/qrset/{qrset}/download", name="download_qr")
     * @Secure(roles="ROLE_USER")
     */
    public function downloadQr(QrSet $qrset) {
        if($this->getRequest()->get('quantity') == null) { echo 'Quantity is required'; die(); }
        return $this->render('PixelbonusSiteBundle:QR:download.html.twig', array(
            'qrset' => $qrset,
            'quantity' => $this->getRequest()->get('quantity'),
        ));
    }

    /**
     * @Route("/qrset/{qrset}/generate", name="generate_qr")
     * @Secure(roles="ROLE_USER")
     */
    public function generateQr(QrSet $qrset) {
        if($this->getRequest()->get('quantity') == null) { echo 'Quantity is required'; die(); }
        $end = $qrset->getFirstFree() + (int)$this->getRequest()->get('quantity');
        $qrImages = array();
        $mcrypt = new McryptCipher($this->container->getParameter("secret"));
        for($i = $qrset->getFirstFree(); $i < $end; $i++) {
            $qrImage = array();
            $fileName = tempnam($this->container->getParameter("kernel.cache_dir"), 'qrimg');
            $hash = $mcrypt->encrypt($qrset->getId().'_'.$i);
            $hash = strtolower(substr(Base32::encode($hash), 0, strlen(Base32::encode($hash))-1));
            $link = $this->container->get('router')->generate('redeem', array('hash' => $hash), true);
            \QRcode::svg($link, $fileName);
            $qrImage['link'] = $hash;
            $qrImage['svg'] = file_get_contents($fileName);
            $qrImages[] = $qrImage;
        }
        $qrset->setFirstFree($end);
        $this->container->get('doctrine')->getManager()->persist($qrset);
        $this->container->get('doctrine')->getManager()->flush($qrset);
        $html = $this->container->get('templating')->render('PixelbonusSiteBundle:QR:qr_template.html.twig', array(
            'qrImages' => $qrImages,
        ));
        if($this->getRequest()->get('html') == 'true') {
            return new Response($html);
        } else {
            $pdf = $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html);
            return new Response($pdf, 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment;filename="%s.pdf"', 'qr'),
            ));
        }
    }

    /**
     * @Route("/qrset/{qrset}/delete", name="delete_qrset")
     * @Secure(roles="ROLE_USER")
     */
    public function deleteQr(QrSet $qrset) {
        $course = $qrset->getCourse()->getId();
        $this->container->get('doctrine')->getManager()->remove($qrset);
        $this->container->get('doctrine')->getManager()->flush($qrset);
        return new RedirectResponse($this->container->get('router')->generate('course', array('course' => $course)));
    }
}
