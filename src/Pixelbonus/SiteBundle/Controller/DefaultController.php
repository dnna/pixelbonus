<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {
    /**
     * @Route("/", name="home")
     */
    public function indexAction() {
        return $this->render('PixelbonusSiteBundle:Default:index.html.twig', array());
    }
}
