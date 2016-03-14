<?php
namespace Pixelbonus\SiteBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class LocaleListener
{
    protected $securityContext;
    protected $em;

    public function __construct($securityContext, $em)
    {
        $this->securityContext = $securityContext;
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token = $this->securityContext->getToken();
        if(is_object($token) && is_object($token->getUser()) && $token->getUser()->getLocale() != $request->getLocale()) {
            $token->getUser()->setLocale($request->getLocale());
            $this->em->persist($token->getUser());
            $this->em->flush($token->getUser());
        }
    }
}