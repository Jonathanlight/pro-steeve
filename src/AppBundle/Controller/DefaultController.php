<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            return $this->redirectToRoute('experienceList');
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_STD_USER')){
             return $this->redirectToRoute('publicExperienceList');
        }

    }
}
