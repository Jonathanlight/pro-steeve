<?php

namespace ExperienceBundle\Controller;

use ExperienceBundle\Entity\Experience;
use ExperienceBundle\Form\ExperienceDynamicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SpoolerBundle\Entity\SpoolerItem;
use SpoolerBundle\Entity\RequestedParameter;

/**
 * ExperiencePublicController controller.
 *
 * @Route("experience")
 * @see Route
 */
class ExperiencePublicController extends Controller {

    /**
     * @Route("/list", name="experienceListPublic")
     */
    public function experienceList(Request $request) {
        $experiences = $this->getDoctrine()
            ->getRepository('ExperienceBundle:Experience')
            ->findBy(['published' => true], ['id' => 'DESC']);
        return $this->render('@Experience/Student/list_experience.html.twig', [
            'title'    => 'Expériences',
            'subtitle' => 'Retrouvez ici les expériences',
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/{id}/configuration", name="experienceConfigurationPublic")
     */
    public function experienceGenerate(Request $request, Experience $experience) {

        $form = $this->createForm(ExperienceDynamicType::class, $experience);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $RepositorySpooler   = $this->getDoctrine()->getRepository('SpoolerBundle:SpoolerItem');

            //On stocke dans un tableau les paramètres de la requête
            $params = $request->request->get('experience');

            //Vérifie l'exhaustivité des paramètres
            //Seul les checkbox à Off n'existe pas, par conséquent, il s'agit de paramètre booléen à False
            $last_key = 0;
            foreach ($form->all() as $key => $field):
              if(!array_key_exists($key, $params)):
                if($last_key == 0):
                  array_merge(array($key => '0'), $params);//Uniquement si le premier paramètre manquant est un parent booléen à false
                else:
                  $params = $this->array_insert_after($last_key, $params, $key, "0");//Gestion des ordres des parametres enfants manquants
                endif;
              endif;
              $last_key = $key;
            endforeach;
            unset($params['submit']);
            unset($params['_token']);

            //On vérifie s'il existe un résultat pour cette configuraiton d'expérience
            $spoolerItemId = $RepositorySpooler->findAnswer($params);
            if($spoolerItemId !== false):
              return $this->redirectToRoute('spoolerAnwser', ['id'=> $spoolerItemId]);
            endif;

            // On vérifie qu'il n'y a pas déjà en base un spollerItem non achevé, pour le même user et la même expérience
            if(!$existingSpoolerItem = $RepositorySpooler->getUserWaitingSpoolerItem($user)) {
                // On crée un nouveau spoolerItem
                $spoolerItem = new SpoolerItem();
                $spoolerItem
                    ->setCreatedDate(new \DateTime())
                    ->setUser($user)
                    ->setExperience($experience)
                    ->setStatus(0);

                // Pour chaque paramètre de la requête, on crée un RequestedParameter qu'on associe au spoolerItem et au paramètre correspondant de l'expérience
                foreach ($params as $key => $paramValue) {
                    $RepositoryParameter = $this->getDoctrine()->getRepository('ExperienceBundle:Parameter');
                    $parameter = $RepositoryParameter->find($key);
                    $paramValueParent = $parameter->getParent()!==null?$params[$parameter->getParent()->getId()]:null;

                    if($this->get('experience.parameter_checker')->isAuthorizeParameter($parameter, $paramValue, $paramValueParent)):
                      $RequestedParameter = new RequestedParameter();
                      $RequestedParameter
                          ->setParameter($parameter)
                          ->setSpooleritem($spoolerItem)
                          ->setValue($paramValue);
                    endif;
                }

                // On sauvegarde le tout en base
                $em = $this->getDoctrine()->getManager();
                $em->persist($spoolerItem);
                $em->flush();
            }
                // Sinon, si un spolerItem existe déjà en base
            else{
                $spoolerItem = $existingSpoolerItem[0];
            }
            return $this->redirectToRoute('spoolerWaiting', ['id'=> $spoolerItem->getId()]);
        }

        return $this->render('@Experience/Student/generate_form.html.twig', [
            'title'    => 'Expériences',
            'subtitle' => 'Retrouvez ici les expériences',
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

  /*
  * Inserts a new key/value after the key in the array.
  *
  * @param $key
  *   The key to insert after.
  * @param $array
  *   An array to insert in to.
  * @param $new_key
  *   The key to insert.
  * @param $new_value
  *   An value to insert.
  *
  * @return
  *   The new array if the key exists, FALSE otherwise.
  *
  * @see array_insert_before()
*/
  function array_insert_after($key, array &$array, $new_key, $new_value) {
    if (array_key_exists ($key, $array)) {
      $new = array();
      foreach ($array as $k => $value) {
        $new[$k] = $value;
        if ($k === $key) {
          $new[$new_key] = $new_value;
        }
      }
      return $new;
    }
    return FALSE;
  }
}