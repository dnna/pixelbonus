<?php
namespace Pixelbonus\SiteBundle\Command;

use Pixelbonus\SiteBundle\Entity\QrRequest;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class GenerateQrCodesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pixelbonus:generateqr')
            ->setDescription('Generate QR code pdfs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = new LockHandler('pixelbonus:generateqr');
        if (!$lock->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $qrRequests = $this->getContainer()->get('doctrine')->getRepository('Pixelbonus\SiteBundle\Entity\QrRequest')->findBy(array(
            'status' => QrRequest::STATUS_PENDING,
        ));
        foreach($qrRequests as $curQrRequest) {
            $output->writeln('Generating for qr request '.$curQrRequest->getId());
            $this->getContainer()->get('pixelbonus.qrrequest.manager')->generateQrCodes($curQrRequest);
            $locale = $curQrRequest->getQrSet()->getCourse()->getUser()->getLocale();
            // Email
            $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject($this->getContainer()->get('translator')->trans('email.qr.subject', array(), 'messages', $locale))
                    ->setFrom('info@pixelbonus.com', 'Pixelbonus')
                    ->setTo($curQrRequest->getQrSet()->getCourse()->getUser()->getEmail())
                    ->setBody($this->getContainer()->get('translator')->trans('email.qr.body', array('%link%' => $this->getContainer()->getParameter('host').$this->getContainer()->get('router')->generate('download_generated_qr', array('qrrequest' => $curQrRequest->getId()))), 'messages', $locale));
            $this->getContainer()->get('mailer')->send($message);
            $curQrRequest->setStatus(QrRequest::STATUS_FULFILLED);
            $this->getContainer()->get('doctrine')->getManager()->persist($curQrRequest);
            $this->getContainer()->get('doctrine')->getManager()->flush($curQrRequest);
        }

        $lock->release();
    }
}