<?php

namespace SpoolerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ExperienceBundle\Entity\Experience;
use UserBundle\Entity\User;

/**
 * SpoolerItem
 *
 * @ORM\Table(name="spooler_item")
 * @ORM\Entity(repositoryClass="SpoolerBundle\Repository\SpoolerItemRepository")
 * @see ORM\
 */
class SpoolerItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    static $statusAvailable = array(
        0 => 'En attente',
        1 => 'En cours',
        2 => 'Exécuté'
    );

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="\ExperienceBundle\Entity\Experience", inversedBy="spoolerItems", cascade={"persist"})
     */
    private $experience;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="RequestedParameter", mappedBy="spoolerItem", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $requestedParameters;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", cascade={"persist"}, inversedBy="spoolerItems")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="launch_date", type="datetime", nullable=true)
     */
    private $launchDate;

    /**
     * @var ResultFile
     *
     * @ORM\OneToMany(targetEntity="ResultFile", mappedBy="spooleritem", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resultfile;


    /**
     * Constructor
     */
    public function __construct()
    {
      $this->requestedParameters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return SpoolerItem
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdDate
     *
     * @param integer $createdDate
     *
     * @return SpoolerItem
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set experience
     *
     * @param Experience $experience
     *
     * @return SpoolerItem
     */
    public function setExperience(Experience $experience = null)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return Experience
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return SpoolerItem
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add requestedParameters
     *
     * @param RequestedParameter $requestedParameter
     *
     * @return SpoolerItem
     */
    public function addRequestedParameter(RequestedParameter $requestedParameter)
    {
        $this->requestedParameters[] = $requestedParameter;

        return $this;
    }

    /**
     * Remove requestedParameter
     *
     * @param RequestedParameter $requestedParameter
     */
    public function removeRequestedParameter(RequestedParameter $requestedParameter)
    {
        $this->requestedParameters->removeElement($requestedParameter);
        $requestedParameter->setSpooleritem(null);
    }

    /**
     * Get requestedParameter
     *
     * @return Collection
     */
    public function getRequestedParameters()
    {
        return $this->requestedParameters;
    }

    /**
     * Set launchDate
     *
     * @param \DateTime $launchDate
     *
     * @return SpoolerItem
     */
    public function setLaunchDate($launchDate)
    {
        $this->launchDate = $launchDate;

        return $this;
    }

    /**
     * Get launchDate
     *
     * @return \DateTime
     */
    public function getLaunchDate()
    {
        return $this->launchDate;
    }

    /**
     * Add resultfile
     *
     * @param ResultFile $resultfile
     *
     * @return SpoolerItem
     */
    public function addResultfile(ResultFile $resultfile)
    {
        $this->resultfile[] = $resultfile;

        return $this;
    }

    /**
     * Remove resultfile
     *
     * @param SpoolerItem $resultfile
     */
    public function removeResultfile(ResultFile $resultfile)
    {
        $this->resultfile->removeElement($resultfile);
    }

    /**
     * Get resultfile
     *
     * @return Collection
     */
    public function getResultfile()
    {
        return $this->resultfile;
    }
}
