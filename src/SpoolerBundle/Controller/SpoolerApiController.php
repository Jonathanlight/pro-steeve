<?php

namespace SpoolerBundle\Controller;

use SpoolerBundle\Entity\SpoolerItem;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\User\UserInterface;
use UserBundle\Entity\User;

/**
 * ExperienceType controller.
 */
class SpoolerApiController extends FOSRestController {
    /**
     * Commentaire: verifie l'etat le spoolerItem de l'id sur www.domaine.com/api/spooleritem/{id}
     */
    public function getSpooleritemStateAction(Request $request, SpoolerItem $spoolerItem) {
        return $this->get('spoolermanager')->getChecked($spoolerItem);
    }

}

