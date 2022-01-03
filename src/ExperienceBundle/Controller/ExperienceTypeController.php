<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\ExperienceType;
use ExperienceBundle\Form\ExperienceTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ExperienceType controller.
 *
 * @Route("experience-type")
 * @see Route
 */
class ExperienceTypeController extends Controller {

  /**
   * Creates a new experienceType entity.
   *
   * @param Request $request
   * @return RedirectResponse|Response
   *
   * @Route("/add", name="experienceTypeAdd")
   */
  public function experienceTypeAdd(Request $request) {
    $experienceType = new ExperienceType();
    $form    = $this->createForm(ExperienceTypeType::class, $experienceType);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($experienceType);
      $em->flush();

      return $this->redirectToRoute('experienceTypeDetail', ['id' => $experienceType->getId()]);
    }

    return $this->render('@Experience/ExperienceType/edit.html.twig', [
      'experienceType' => $experienceType,
      'form'    => $form->createView(),
    ]);
  }

  /**
   * Displays a form to edit an existing experienceType entity.
   *
   * @param Request $request
   * @param ExperienceType $experienceType
   * @return RedirectResponse|Response
   *
   * @Route("/edit/{id}", name="experienceTypeEdit")
   */
  public function experienceTypeEdit(Request $request, ExperienceType $experienceType) {
    $editForm = $this->createForm(ExperienceTypeType::class, $experienceType);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('experienceTypeDetail', ['id' => $experienceType->getId()]);
    }

    return $this->render('@Experience/ExperienceType/edit.html.twig', [
      'experienceType'   => $experienceType,
      'form' => $editForm->createView(),
    ]);
  }

  /**
   * @param Request $request
   * @param ExperienceType $experienceType
   * @return RedirectResponse|Response
   *
   * @Route("/delete/{id}", name="experienceTypeDelete")
   */
  public function clientDelete(Request $request, ExperienceType $experienceType) {
    $form = $this->createFormBuilder($experienceType)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($experienceType);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Le type d\'expérience <strong>' . $experienceType->getName() . '</strong> a bien été supprimé.');
      return $this->redirect($this->generateUrl('experienceTypeList'));
    }

    return $this->render('@Experience/ExperienceType/delete.html.twig', [
      'experienceType' => $experienceType,
      'form'           => $form->createView(),
      'title'          => 'Confirmer la suppression',
      'subtitle'       => 'Ceci entrainera la suppression définitive du type d\'expérience.',
    ]);
  }

  /**
   * @param ExperienceType $experienceType
   * @return Response
   * @Route("/detail/{id}", name="experienceTypeDetail")
   */
  public function experienceTypeDetail(ExperienceType $experienceType) {
    return $this->render('@Experience/ExperienceType/show.html.twig', [
      'experienceType' => $experienceType,
      'title'   => 'Détail du type d\'expérience',
    ]);
  }

  /**
   * @return Response
   *
   * @Route("/list", name="experienceTypeList")
   */
  public function experienceTypeList() {
    $experienceTypes = $this->getDoctrine()
                     ->getRepository('ExperienceBundle:ExperienceType')
                     ->findBy([], ['id' => 'DESC']);
    return $this->render('@Experience/ExperienceType/list.html.twig', [
      'title'    => 'Types d\'expérience',
      'subtitle' => 'Retrouvez ici les types d\'expérience',
      'experienceTypes' => $experienceTypes,
    ]);
  }


}
