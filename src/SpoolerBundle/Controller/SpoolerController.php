<?php

namespace SpoolerBundle\Controller;

use ExperienceBundle\Entity\Experience;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use SpoolerBundle\Entity\SpoolerItem;
use SpoolerBundle\Entity\ResultFile;
use Symfony\Component\HttpFoundation\Request;


/**
 * SpoolerController controller.
 *
 * @see Route
 */
class SpoolerController extends Controller
{
    /**
     * @param SpoolerItem $spoolerItem
     * @return RedirectResponse|Response
     *
     * @Route("/spooler/waiting/{id}", name="spoolerWaiting")
     */
    public function spoolerWatingAction(SpoolerItem $spoolerItem) {
        if (!$spoolerItem){
            return $this->redirectToRoute('experienceListPublic');
        }
        return $this->render('@Spooler/Spooler/spoolerItemLoad.html.twig', array('spoolerItemId' => $spoolerItem->getId()));
    }

    /**
     * @param SpoolerItem $spoolerItem
     * @return RedirectResponse|Response
     *
     * @Route("/spooler/anwser/{id}", name="spoolerAnwser")
     */
    public function spoolerAnwserAction(SpoolerItem $spoolerItem) {
        if (!$spoolerItem){
            return $this->redirectToRoute('experienceListPublic');
        }
        return $this->render('@Spooler/Spooler/spoolerItemAnwser.html.twig', array('spoolerItemAnwsers' => $spoolerItem));
    }

    /**
     * @Route(path="/experience-api-simulator.json")
     */
    public function experienceApiSimulator()
    {
        print json_encode(array(
            'documents' => array(
                1 => array(
                    'docName' => 'Résultat 1',
                    'docUrl' => "http://pbil.univ-lyon1.fr/members/mbailly/Comm_Scientifique/redaction_memoire_MBB.pdf"
                ),
                2 => array(
                    'docName' => 'Résultat 2',
                    'docUrl' => "http://www.enssib.fr/bibliotheque-numerique/documents/48553-l-ecriture-scientifique.pdf"
                ),
                3 =>  array(
                    'docName' => 'Vidéo',
                    'docUrl' => "http://c108.clink.to/yt/8/00/chris_brown_high_end_official_video_ft._future_young_thug_h264_61256.mp4"
                )
            ),
        ));
        exit;
    }

  /**
   * @param Request $request
   * @param SpoolerItem $spoolerItem
   * @return RedirectResponse|Response
   *
   * @Route("/answer/delete/{id}", name="spoolerAnswerDelete")
   */
  public function spoolerAnswerDelete(Request $request, SpoolerItem $spoolerItem) {
    $form = $this->createFormBuilder($spoolerItem)
                 ->add('confirm', SubmitType::class, [
                   'label' => 'Confirmer la suppression',
                   'attr'  => ['class' => 'btn-default pull-right'],
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($spoolerItem);
      $em->flush();

      $this->addFlash('success', '<i class="fa fa-trash"></i> Le résultat a bien été supprimé.');
      return $this->redirect($this->generateUrl('experienceListPublic'));
    }

    return $this->render('@Spooler/Spooler/delete.html.twig', [
      'spoolerItem'     => $spoolerItem,
      'form'        => $form->createView(),
      'title'       => 'Confirmer la suppression',
      'subtitle'    => 'Ceci entrainera la suppression définitive du résultat.',
    ]);
  }
}
