<?php

namespace Pixelbonus\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pixelbonus\ApiBundle\Controller\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\UserBundle\Model\UserInterface;

class CourseController extends ApiController {
    /**
     * @ApiDoc(
     *   resource=true,
     *   description="Get the user's courses",
     *   output="Pixelbonus\SiteBundle\Entity\Course"
     * )
     * @Secure(roles="ROLE_USER")
     */
    public function getCoursesAction() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $this->api_response($user->getCourses(), 200);
    }
}