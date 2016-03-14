<?php
namespace Pixelbonus\SiteBundle\Manager;

use Pixelbonus\SiteBundle\Entity\QrCode;
use Pixelbonus\SiteBundle\Entity\QrRequest;

use iio\libmergepdf\Merger;

class QrRequestManager {
    protected $doctrine;
    protected $templating;
    protected $snappyPdf;
    protected $router;
    protected $host;
    protected $appRoot;
    protected $cacheDir;

    public function __construct($doctrine, $templating, $snappyPdf, $router, $host, $rootDir, $cacheDir) {
        $this->doctrine = $doctrine;
        $this->templating = $templating;
        $this->snappyPdf = $snappyPdf;
        $this->router = $router;
        $this->host = $host;
        $this->appRoot = realpath($rootDir . '/../app');
        $this->cacheDir = $cacheDir;
    }

    public function generateQrCodes(QrRequest $qrRequest) {
        $qrset = $qrRequest->getQrset();
        $qrImages = array();
        $toFlush = array($qrset);
        $count = $qrset->getQrcodes()->count();
        for($i = $count; $i < $count+$qrRequest->getQuantity(); $i++) {
            // Create the QR Entity
            $qrCode = new QrCode();
            $qrCode->setQrset($qrset);
            $hash = hash_hmac('sha1', $qrset->getId().'_'.$i, $qrset->getCourse()->getUser()->getPassword());
            $qrCode->setCode($hash);
            $this->doctrine->getManager()->persist($qrCode);
            $toFlush[] = $qrCode;
            $qrset->getQrcodes()->add($qrCode);

            // Generate QR image based on the created entity
            $qrImage = array();
            $fileName = tempnam($this->cacheDir, 'qrimg');
            $link = $this->host.$this->router->generate('redeem', array('hash' => $qrCode->getCode()));
            \QRcode::svg($link, $fileName);
            $qrImage['link'] = $qrCode->getCode();
            $qrImage['svg'] = file_get_contents($fileName);
            $xml = simplexml_load_string($qrImage['svg']);
            $xml['width'] = 135;
            $xml['height'] = 135;
            $qrImage['svg'] = $xml->asXML();
            $qrImages[] = $qrImage;
        }
        $this->doctrine->getManager()->persist($qrset);
        $this->doctrine->getManager()->flush($toFlush);
        $m = new Merger();
        foreach(array_chunk($qrImages, 306) as $curQrImages) {
            $html = $this->templating->render('PixelbonusSiteBundle:QR:qr_template.html.twig', array(
                'qrImages' => $curQrImages,
            ));
            $pdf = $this->snappyPdf->getOutputFromHtml($html);
            $m->addRaw($pdf);
        }
        file_put_contents($this->appRoot.DIRECTORY_SEPARATOR.'qruploads'.DIRECTORY_SEPARATOR.'qr_'.$qrRequest->getId().'.pdf', $m->merge());
    }

    public function getPdf(QrRequest $qrRequest) {
        return file_get_contents($this->appRoot.DIRECTORY_SEPARATOR.'qruploads'.DIRECTORY_SEPARATOR.'qr_'.$qrRequest->getId().'.pdf');
    }
}

?>