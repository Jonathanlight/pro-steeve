<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\Experience;
use ExperienceBundle\Form\ExperienceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Experience controller.
 *
 * @Route("admin/experience")
 * @see Route
 */
class ExperienceController extends Controller {

  /**
   * Creates a new experience entity.
   *
   * @param Request $request
   * @param Experience $experienceClone
   * @return RedirectResponse|Response
   * @Route("/add", name="experienceAdd")
   * @Route("/add/{id}", name="experienceClone")
   */
  public function experienceAdd(Request $request, Experience $experienceClone = null) {
    if($experienceClone === null):
      $experience = new Experience();
    else:
      $experience = clone $experienceClone;
      $experience->setId(null);
    endif;

    $form    = $this->createForm(ExperienceType::class, $experience);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->get('experience.presave')->prepareRelatedEntities($experience);
      $em = $this->getDoctrine()->getManager();
      $em->persist($experience);
      $em->flush();

      return $this->redirectToRoute('experienceDetail', ['id' => $experience->getId()]);
    }

    return $this->render('@Experience/Experience/add.html.twig', [
      'experience' => $experience,
      'form'    => $form->createView(),
    ]);
  }

  /**
   * Displays a form to edit an existing experience entity.
   *
   * @param Request $request
   * @param Experience $experience
   * @return RedirectResponse|Response
   *
   * @Route("/edit/{id}", name="experienceEdit")
   */
  public function experienceEdit(Request $request, Experience $experience) {
    $editForm = $this->createForm(ExperienceType::class, $experience);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->get('experience.presave')->prepareRelatedEntities($experience);
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('experienceDetail', ['id' => $experience->getId()]);
    }

    return $this->render('@Experience/Experience/edit.html.twig', [
      'experience'   => $experience,
      'form' => $editForm->createView(),
    ]);
  }

  /**
   * @param Request $request
   * @param Experience $experience
   * @return RedirectResponse|Response
   *
   * @Route("/delete/{id}", name="experienceDelete")
   */
  public function experienceDelete(Request $request, Experience $experience) {
    $form = $this->createFormBuilder($experience)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($experience);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Le experience <strong>' . $experience->getName() . '</strong> a bien été supprimé.');
      return $this->redirect($this->generateUrl('experienceList'));
    }

    return $this->render('@Experience/Experience/delete.html.twig', [
      'experience'     => $experience,
      'form'        => $form->createView(),
      'title'       => 'Confirmer la suppression',
      'subtitle'    => 'Ceci entrainera la suppression définitive de l\'expérience.',
    ]);
  }

  /**
   * @param Experience $experience
   * @return Response
   *
   * @Route("/detail/{id}", name="experienceDetail")
   */
  public function experienceDetail(Experience $experience) {
    return $this->render('@Experience/Experience/show.html.twig', [
      'experience' => $experience,
      'title'   => 'Détail de l\'expérience',
    ]);
  }

  /**
   * @return Response
   *
   * @Route("/list", name="experienceList")
   */
  public function experienceList() {
    $experiences = $this->getDoctrine()
                     ->getRepository('ExperienceBundle:Experience')
                     ->findBy([], ['id' => 'DESC']);
    return $this->render('@Experience/Experience/list.html.twig', [
      'title'    => 'Expériences',
      'subtitle' => 'Retrouvez ici les expériences',
      'experiences' => $experiences,
    ]);
  }

  /**
   * @param Request $request
   * @param Experience $experience
   * @return RedirectResponse|Response
   *
   * @Route("/answer/delete/{id}", name="experienceAnswersDelete")
   */
  public function experienceAnswersDelete(Request $request, Experience $experience) {
    $form = $this->createFormBuilder($experience)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $experience->getSpoolerItems()->clear();
      $em->persist($experience);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Les résultats ont bien été supprimés.');
      return $this->redirect($this->generateUrl('experienceList'));
    }

    return $this->render('@Experience/Experience/deleteAnswers.html.twig', [
      'experience'  => $experience,
      'form'        => $form->createView(),
      'title'       => 'Confirmer la suppression',
      'subtitle'    => 'Ceci entrainera la suppression définitive des résultats associés.',
    ]);
  }

}
