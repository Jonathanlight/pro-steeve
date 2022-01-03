<?php
namespace SpoolerBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use SpoolerBundle\Entity\ResultFile;
use SpoolerBundle\Entity\SpoolerItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\DateTime;

class SpoolerManager
{

    private $doctrine;
    private $waitingSpoolerItems = array();

    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $repositoryName
     * @return array
     */
    public function getResult($repositoryName){
        return $this->doctrine->getRepository($repositoryName)->findAll();
    }

    /**
     * @param SpoolerItem $spoolerItem
     * @return array
     */
    public function getChecked(SpoolerItem $spoolerItem){
        $status = $spoolerItem->getStatus();
        /**
         * @Comment: Si le statut est "en cours" on retourne simplement que le statut est "en cours"
         */
        if($status == 1){
            return ['status' => 1, 'value' => 'Expérience en cours ...', 'time' => 0, 'results' => ''];
        }
        /**
         *  @Comment: Si le statut est "exécutés" on retourne simplement que le statut est "exécutés" + Résultats
         */
        elseif($status == 2){
            return ['status' => 2,'value' => 'Expérience terminée', 'time' => 0, 'spoolerItem' => $spoolerItem->getId() ];
        }
        /**
         * @Comment: Sinon, si le statut est "En attente", On procède à différentes vérifications
         */
        else{
            ini_set('max_execution_time', 0);

            // S'il s'agit d'un calcul numérique
            $experience_type = $spoolerItem->getExperience()->getExperienceType()->getId();
            if ($experience_type == 2){
                // On récupère la mémoire totale utilisée sur le serveur
                $usedServerMemory = $this->doctrine->getRepository('SpoolerBundle:SpoolerItem')->getUsedMemoryOnServer($spoolerItem);

                // Si il n'y a pas assez de mémoire disponible sur le serveur pour lancer la tâche, on met en attente le client
                if ($usedServerMemory + $spoolerItem->getExperience()->getRequiredMemory() > $spoolerItem->getExperience()->getServer()->getMemory()){
                    return $data = ['status' => 1, 'value' => 'Calcul numérique: Memoire max du server atteinte, patientez' ,'results' => ''];
                }
                // S'il y a assez de mémoire disponible sur le serveur
                else{
                    // Si je suis premier dans la file d'attente, on exécute
                    $this->waitingSpoolerItems = $this->doctrine->getRepository('SpoolerBundle:SpoolerItem')->waitingSpoolerItems($spoolerItem);
                    if($this->waitingSpoolerItems[0]->getId() == $spoolerItem->getId()){

                        // On met à jour le statut et la date de lancement du spoolerItem
                        $spoolerItem->setStatus(1);
                        $spoolerItem->setLaunchDate(new \DateTime());
                        $this->doctrine->persist($spoolerItem);
                        $this->doctrine->flush();

                        // On exécute la requête auprès du serveur d'API
                        $this->getResultFromApi($spoolerItem);

                        // Une fois la requête exécutée on met à nouveau à jour le statut du spoolerItem
                        $spoolerItem->setStatus(2);
                        $this->doctrine->persist($spoolerItem);
                        $this->doctrine->flush();

                        return $data = ['status' => 2, 'value' => 'Calcul numérique: Expérience terminée', 'spoolerItem' => $spoolerItem->getId()];
                    }
                    // Si je ne suis pas premier, on retourne le temps d'attente et on fait patienter
                    else{
                        return $data = ['status' => 0, 'value' => 'Calcul numérique en attente', 'time' => $this->estimatedWaitingTime($spoolerItem).' secondes'];
                    }
                }
            }
            // S'il s'agit d'une expérimentation
            else{
                // Si le support est libre, on vérifie la file d'attente
                if($this->doctrine->getRepository('SpoolerBundle:SpoolerItem')->isSpoolerItemSupportAvailable($spoolerItem)){
                    // Si je suis premier dans la file d'attente, on exécute
                    $this->waitingSpoolerItems = $this->doctrine->getRepository('SpoolerBundle:SpoolerItem')->waitingSpoolerItems($spoolerItem);
                    if($this->waitingSpoolerItems[0]->getId() == $spoolerItem->getId()){

                        // On met à jour le statut et la date de lancement du spoolerItem
                        $spoolerItem->setStatus(1);
                        $spoolerItem->setLaunchDate(new \DateTime());
                        $this->doctrine->persist($spoolerItem);
                        $this->doctrine->flush();

                        // On exécute la requête auprès du serveur d'API
                        $this->getResultFromApi($spoolerItem);

                        // Une fois la requête exécutée on met à nouveau à jour le statut du spoolerItem
                        $spoolerItem->setStatus(2);
                        $this->doctrine->persist($spoolerItem);
                        $this->doctrine->flush();

                        return $data = ['status' => 2, 'value' => 'Expérimentation: Expérience terminée', 'spoolerItem' => $spoolerItem->getId()];
                    }
                    // Si je ne suis pas premier, on retourne le temps d'attente et on fait patienter
                    else{
                        return $data = ['status' => 0, 'value' => 'Expérimentation en attente' , 'time' => $this->estimatedWaitingTime($spoolerItem).' secondes'];
                    }

                }
                // Si le support n'est pas libre, on retourne le temps d'attente et on fait patienter
                else{
                    return $data = ['status' => 0, 'value' => 'Expérimentation en attente', 'time' => $this->estimatedWaitingTime($spoolerItem).' secondes'];
                }
            }
        }
    }

    public function estimatedWaitingTime(SpoolerItem $spoolerItem){
        $waitingTime = 0; // En secondes
        if($this->waitingSpoolerItems){
            $waitingSpoolerItems = $this->waitingSpoolerItems;
        }
        else{
            $waitingSpoolerItems = $this->doctrine->getRepository('SpoolerBundle:SpoolerItem')->waitingAndProcessingSpoolerItems($spoolerItem);
        }

        // Pour chaque spoolerItme en attente ou en cours
        foreach($waitingSpoolerItems as $waitingSpoolerItem){
            // Si ce n'est pas le spoolerItem en cours
            if($waitingSpoolerItem->getId() != $spoolerItem->getId()){
                // Si le statut du spoolerItem est "en attente" on incrémente le temps d'attente du temps complet nécessaire à l'exécution de la tâche
                if($waitingSpoolerItem->getStatus() == 0){
                    $waitingTime = $waitingTime + $waitingSpoolerItem->getExperience()->getRequiredTime();
                }
                // Si le statut du spoolerItem est "en cours" on incrémente le délai d'attente uniquement du temps d'exécution restant.
                else{
                    $now = new \DateTime();
                    $processedTime = $now->getTimestamp() - $waitingSpoolerItem->getLaunchDate()->getTimestamp();
                    $waitingTime = $waitingTime + $waitingSpoolerItem->getExperience()->getRequiredTime() - $processedTime;
                }
            }
        }

        return $waitingTime;
    }

    public function getResultFromApi(SpoolerItem $spoolerItem){
        // On constitue l'URL de l'API incluant les paramètres
        $apiAddress = $spoolerItem->getExperience()->getServer()->getAddress().$spoolerItem->getExperience()->getScript().'?';

        foreach($spoolerItem->getRequestedParameters() as $requestedParameter){
            $apiAddress.= $requestedParameter->getParameter()->getSystemName().'='.urlencode($requestedParameter->getValue()).'&';
        }
        $apiAddress = substr($apiAddress,0,-1);

        // On lance la requête auprès du serveur d'API
        $options=array(
            CURLOPT_URL            => $apiAddress,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false
        );

        $CURL=curl_init();
        curl_setopt_array($CURL,$options);
        $content=curl_exec($CURL);
        curl_close($CURL);

        $reponseExperience = json_decode($content, true);

        // Si le serveur retourne des données json
        if ($reponseExperience){
            // On crée et sauvegarde les documents de la réponse
            $em = $this->doctrine;
            foreach ($reponseExperience['documents'] as $document) {
                // On récupère et on stocke le document
                $filePath = 'uploads/results/'.uniqid().'.'.pathinfo($document['docUrl'], PATHINFO_EXTENSION);
                file_put_contents($filePath, file_get_contents($document['docUrl']));
                $uploadedFile = new UploadedFile($filePath, pathinfo($filePath, PATHINFO_FILENAME), null, null, null, true);

                // On crée le ResultFile correspondant
                $resultFile = new ResultFile();
                $resultFile->setFileName(pathinfo($filePath, PATHINFO_FILENAME));
                $resultFile->setFile($uploadedFile);
                $resultFile->setUpdatedAt(new \DateTime());
                $resultFile->setTitle($document['docName']);
                $resultFile->setSpooleritem($spoolerItem);

                // On sauvegarde le ResultFile
                $em->persist($resultFile);
            }
            $em->flush();


            // Une fois la requête exécutée on met à nouveau à jour le statut du spoolerItem
            $spoolerItem->setStatus(2);
            $this->doctrine->persist($spoolerItem);
            $this->doctrine->flush();

            return true;
        }

        return false;
    }
}
?>