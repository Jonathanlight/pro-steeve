<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\ExperienceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use ExperienceBundle\Entity\Support;

/**
 * Route public student
 */
class PublicViewController extends Controller
{
    /**
     * @Route("/publicSupportList", name="publicSupportList")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function publicSupportListAction(Request $request){
        $supports = $this->getDoctrine()
            ->getRepository('ExperienceBundle:Support')
            ->findBy([], ['id' => 'DESC']);
        return $this->render('@Experience/Student/support.html.twig', [
            'title'    => 'Support',
            'subtitle' => 'Retrouvez ici les supports',
            'supports' => $supports,
        ]);
    }

    /**
     * @Route("/publicExperience/{id}", name="publicExperience")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function publicExperienceAction(Request $request, Support $support){

        return $this->render('@Experience/Student/experienceDetails.html.twig', [
            'title'    => 'Expériences',
            'subtitle' => 'Expérience liées aux supports',
            'experiences' => $support->getExperiences(),
        ]);
    }

    /**
     * @Route("/publicExperienceTypeList", name="publicExperienceTypeList")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function publicExperienceTypeListAction(Request $request){
        $experienceTypes = $this->getDoctrine()
            ->getRepository('ExperienceBundle:ExperienceType')
            ->findBy([], ['id' => 'DESC']);
        return $this->render('@Experience/Student/experienceType.html.twig', [
            'title'    => 'Types d\'experiences',
            'subtitle' => 'Retrouvez ici les Types d\'experiences ',
            'experienceTypes' => $experienceTypes,
        ]);
    }

    /**
     * @Route("/publicExperienceTypeDetail/{id}", name="publicExperienceTypeDetail")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function publicExperienceTypeDetailAction(Request $request, ExperienceType $experienceType){
        $experienceTypes = $this->getDoctrine()
            ->getRepository('ExperienceBundle:Experience')->findBy(array(
                'experienceType' => $experienceType->getId()
            ));
        return $this->render('@Experience/Student/experienceTypeDetail.html.twig', [
            'title'    => 'Experiences',
            'subtitle' => 'Retrouvez ici les Experiences ',
            'experienceTypes' => $experienceTypes,
        ]);
    }

    /**
     * @Route("/publicExperienceList", name="publicExperienceList")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function publicExperienceListAction(Request $request){
        $experiences = $this->getDoctrine()
            ->getRepository('ExperienceBundle:Experience')
            ->findBy(['published' => true], ['id' => 'DESC']);
        return $this->render('@Experience/Student/experience.html.twig', [
            'title'    => 'Expériences',
            'subtitle' => 'Retrouvez ici les expériences',
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/monCompte", name="monCompte")
     * @Security("is_granted('ROLE_STD_USER') or has_role('ROLE_SUPER_ADMIN')")
     */
    public function monCompteAction(Request $request){
        return $this->render('@Experience/Student/moncompte.html.twig', []);
    }

    /**
     * @Route("/publicContact", name="publicContact")
     */
    public function publicContactAction(Request $request){
        return $this->render('@Experience/Public/contact.html.twig', []);
    }

    /**
     * @Route("/remerciements", name="remerciements")
     */
    public function remerciementsAction(Request $request){
        return $this->render('@Experience/Public/remerciements.html.twig', []);
    }

    /**
     * @Route("/mentionsLegales", name="mentionsLegales")
     */
    public function mentionsLegalesAction(Request $request){
        return $this->render('@Experience/Public/mentionsLegales.html.twig', []);
    }

}

?>
