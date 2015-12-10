<?php

namespace Pixelbonus\SiteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Base32\Base32;
use Pixelbonus\SiteBundle\Crypto\McryptCipher;

use Pixelbonus\SiteBundle\Entity\Redemption;

class RedemptionController extends Controller {
    /**
     * @Route("/redeem", name="redeem")
     */
    public function redeem() {
        return $this->render('PixelbonusSiteBundle:Redemption:redemption.html.twig', array());
    }

    /**
     * @Route("/redeem/submit", name="redeem_submit")
     */
    public function redeemSubmit() {
        if($this->getRequest()->get('participantNumber') == null) { echo 'Participant number not provided'; die(); }
        if($this->getRequest()->get('hash') == null) { echo 'Hash not provided'; die(); }
        $hash = Base32::decode(strtoupper(str_replace(' ', '', $this->getRequest()->get('hash'))).'=');
        $mcrypt = new McryptCipher($this->container->getParameter("secret"));
        $input = $mcrypt->decrypt($hash);
        if(strpos($input, '_') === false) {
            return $this->render('PixelbonusSiteBundle:Redemption:invalid_redemption.html.twig', array());
        }
        $inputParts = explode('_', $input);
        $qrset = $this->container->get('doctrine')->getManager()->getRepository('Pixelbonus\SiteBundle\Entity\QrSet')->find($inputParts[0]);
        if(!isset($qrset)) {
            return $this->render('PixelbonusSiteBundle:Redemption:invalid_redemption.html.twig', array());
        }
        $redemption = $this->container->get('doctrine')->getManager()->getRepository('Pixelbonus\SiteBundle\Entity\Redemption')->findOneBy(array(
            'qrset' => $qrset,
            'qrsetSequenceNum' => $inputParts[1],
        ));
        if(isset($redemption)) {
            return $this->render('PixelbonusSiteBundle:Redemption:already_redeemed.html.twig', array());
        }
        $redemption = new Redemption();
        $redemption->setQrset($qrset);
        $redemption->setQrsetSequenceNum($inputParts[1]);
        $redemption->setParticipantNumber($this->getRequest()->get('participantNumber'));
        $this->container->get('doctrine')->getManager()->persist($redemption);
        $this->container->get('doctrine')->getManager()->flush($redemption);

        return $this->render('PixelbonusSiteBundle:Redemption:success.html.twig', array(
            'redemption' => $redemption,
        ));
    }
}
