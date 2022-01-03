<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\Support;
use ExperienceBundle\Form\SupportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Support controller.
 *
 * @Route("support")
 * @see Route
 */
class SupportController extends Controller {

  /**
   * Creates a new support entity.
   *
   * @param Request $request
   * @return RedirectResponse|Response
   *
   * @Route("/add", name="supportAdd")
   */
  public function supportAdd(Request $request) {
    $support = new Support();
    $form    = $this->createForm(SupportType::class, $support);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($support);
      $em->flush();

      return $this->redirectToRoute('supportDetail', ['id' => $support->getId()]);
    }

    return $this->render('@Experience/Support/edit.html.twig', [
      'support' => $support,
      'form'    => $form->createView(),
    ]);
  }

  /**
   * Displays a form to edit an existing support entity.
   *
   * @param Request $request
   * @param Support $support
   * @return RedirectResponse|Response
   *
   * @Route("/edit/{id}", name="supportEdit")
   */
  public function supportEdit(Request $request, Support $support) {
    $editForm = $this->createForm(SupportType::class, $support);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('supportDetail', ['id' => $support->getId()]);
    }

    return $this->render('@Experience/Support/edit.html.twig', [
      'support'   => $support,
      'form' => $editForm->createView(),
    ]);
  }

  /**
   * @param Request $request
   * @param Support $support
   * @return RedirectResponse|Response
   *
   * @Route("/delete/{id}", name="supportDelete")
   */
  public function supportDelete(Request $request, Support $support) {
    $form = $this->createFormBuilder($support)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($support);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Le support <strong>' . $support->getName() . '</strong> a bien été supprimé.');
      return $this->redirect($this->generateUrl('supportList'));
    }

    return $this->render('@Experience/Support/delete.html.twig', [
      'support'     => $support,
      'form'        => $form->createView(),
      'title'       => 'Confirmer la suppression',
      'subtitle'    => 'Ceci entrainera la suppression définitive du support.',
    ]);
  }

  /**
   * @param Support $support
   * @return Response
   *
   * @Route("/detail/{id}", name="supportDetail")
   */
  public function supportDetail(Support $support) {
    return $this->render('@Experience/Support/show.html.twig', [
      'support' => $support,
      'title'   => 'Détail du support',
    ]);
  }

  /**
   * @return Response
   *
   * @Route("/list", name="supportList")
   */
  public function supportList() {
    $supports = $this->getDoctrine()
                     ->getRepository('ExperienceBundle:Support')
                     ->findBy([], ['id' => 'DESC']);
    return $this->render('@Experience/Support/list.html.twig', [
      'title'    => 'Supports',
      'subtitle' => 'Retrouvez ici les supports',
      'supports' => $supports,
    ]);
  }


}
