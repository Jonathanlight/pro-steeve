<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\Server;
use ExperienceBundle\Form\ServerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Server controller.
 *
 * @Route("server")
 * @see Route
 */
class ServerController extends Controller {

  /**
   * Creates a new server entity.
   * @param Request $request
   * @return RedirectResponse|Response
   *
   * @Route("/add", name="serverAdd")
   */
  public function serverAdd(Request $request) {
    $server = new Server();
    $form    = $this->createForm(ServerType::class, $server);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($server);
      $em->flush();

      return $this->redirectToRoute('serverDetail', ['id' => $server->getId()]);
    }

    return $this->render('@Experience/Server/edit.html.twig', [
      'server' => $server,
      'form'    => $form->createView(),
    ]);
  }

  /**
   * Displays a form to edit an existing server entity.
   *
   * @param Request $request
   * @param Server $server
   * @return RedirectResponse|Response
   *
   * @Route("/edit/{id}", name="serverEdit")
   */
  public function serverEdit(Request $request, Server $server) {
    $editForm = $this->createForm(ServerType::class, $server);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('serverDetail', ['id' => $server->getId()]);
    }

    return $this->render('@Experience/Server/edit.html.twig', [
      'server'   => $server,
      'form' => $editForm->createView(),
    ]);
  }

  /**
   * @param Request $request
   * @param Server $server
   * @return RedirectResponse|Response
   *
   * @Route("/delete/{id}", name="serverDelete")
   */
  public function serverDelete(Request $request, Server $server) {
    $form = $this->createFormBuilder($server)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($server);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Le serveur <strong>' . $server->getName() . '</strong> a bien été supprimé.');
      return $this->redirect($this->generateUrl('serverList'));
    }

    return $this->render('@Experience/Server/delete.html.twig', [
      'server'     => $server,
      'form'        => $form->createView(),
      'title'       => 'Confirmer la suppression',
      'subtitle'    => 'Ceci entrainera la suppression définitive du serveur.',
    ]);
  }

  /**
   * @param Server $server
   * @return Response
   *
   * @Route("/detail/{id}", name="serverDetail")
   */
  public function serverDetail(Server $server) {
    return $this->render('@Experience/Server/show.html.twig', [
      'server' => $server,
      'title'   => 'Détail du serveur',
    ]);
  }

  /**
   * @return Response
   *
   * @Route("/list", name="serverList")
   */
  public function serverList() {
    $servers = $this->getDoctrine()
                     ->getRepository('ExperienceBundle:Server')
                     ->findBy([], ['id' => 'DESC']);
    return $this->render('@Experience/Server/list.html.twig', [
      'title'    => 'Serveurs',
      'subtitle' => 'Retrouvez ici les serveurs',
      'servers' => $servers,
    ]);
  }


}
