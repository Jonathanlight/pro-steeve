<?php

namespace ExperienceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use SpoolerBundle\Entity\SpoolerItem;

/**
 * Experience
 *
 * @ORM\Table(name="experience")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ExperienceRepository")
 * @see ORM\
 */
class Experience
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
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="experience")
     * @ORM\JoinColumn(nullable=false)
     */
    private $support;

    /**
     * @ORM\ManyToOne(targetEntity="ExperienceType", inversedBy="experience")
     * @ORM\JoinColumn(nullable=false)
     */
    private $experienceType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="experience")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    /**
     * @var string
     *
     * @ORM\Column(name="script", type="string", length=255)
     */
    private $script;

    /**
     * @var integer
     *
     * @ORM\Column(name="requiredMemory", type="integer")
     */
    private $requiredMemory;

    /**
     * @var integer
     *
     * @ORM\Column(name="requiredTime", type="integer")
     */
    private $requiredTime;

    /**
     * @var Parameter
     *
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="experience", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    private $parameters;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="\SpoolerBundle\Entity\SpoolerItem", mappedBy="experience", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $spoolerItems;

    /**
     * @var Image
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="experience", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * Constructor
     */
    public function __construct()
    {
      $this->parameters = new ArrayCollection();
      $this->spoolerItems = new ArrayCollection();
      $this->images = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Experience
     */
    public function setId($id)
    {
      $this->id = $id;
      return $this;
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
     * Set support
     *
     * @param Support $support
     *
     * @return Experience
     */
    public function setSupport(Support $support = null)
    {
      $this->support = $support;
      if($support){
        $support->addExperience($this);
      }

      return $this;
    }

    /**
     * Get support
     *
     * @return Support
     */
    public function getSupport()
    {
      return $this->support;
    }

    /**
     * Set experienceType
     *
     * @param ExperienceType $experienceType
     *
     * @return Experience
     */
    public function setExperienceType(ExperienceType $experienceType = null)
    {
      $this->experienceType = $experienceType;
      if($experienceType){
        $experienceType->addExperience($this);
      }

      return $this;
    }

    /**
     * Get experienceType
     *
     * @return ExperienceType
     */
    public function getExperienceType()
    {
      return $this->experienceType;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Experience
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Experience
     */
    public function setDescription($description)
    {
      $this->description = $description;

      return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
      return $this->description;
    }

    /**
     * Set server
     *
     * @param Server $server
     *
     * @return Experience
     */
    public function setServer(Server $server = null)
    {
      $this->server = $server;
      if($server){
        $server->addExperience($this);
      }

      return $this;
    }

    /**
     * Get server
     *
     * @return Server
     */
    public function getServer()
    {
      return $this->server;
    }

    /**
     * Set script
     *
     * @param string $script
     *
     * @return Experience
     */
    public function setScript($script)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Get script
     *
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Set requiredMemory
     *
     * @param integer $requiredMemory
     *
     * @return Experience
     */
    public function setRequiredMemory($requiredMemory)
    {
      $this->requiredMemory = $requiredMemory;

      return $this;
    }

    /**
     * Get requiredMemory
     *
     * @return integer
     */
    public function getRequiredMemory()
    {
      return $this->requiredMemory;
    }


    /**
     * Set requiredTime
     *
     * @param integer $requiredTime
     *
     * @return Experience
     */
    public function setRequiredTime($requiredTime)
    {
      $this->requiredTime = $requiredTime;

      return $this;
    }

    /**
     * Get requiredTime
     *
     * @return integer
     */
    public function getRequiredTime()
    {
      return $this->requiredTime;
    }

    /**
     * Add parameters
     *
     * @param Parameter $parameter
     *
     * @return Experience
     */
    public function addParameter(Parameter $parameter)
    {
      $this->parameters[] = $parameter;

      $parameter->setExperience($this);

      return $this;
    }

    /**
     * Remove parameters
     *
     * @param Parameter $parameter
     */
    public function removeParameter(Parameter $parameter)
    {
      $this->parameters->removeElement($parameter);
    }

    /**
     * Get parameters
     * Return only first level parameters (who have not parent)
     *
     * @return Collection
     */
    public function getParameters()
    {
      $collection = new ArrayCollection();
      foreach ($this->parameters as $parameter):
        if($parameter->getParent() === null):
          $collection->add($parameter);
        endif;
      endforeach;
      return $collection;
    }

    /**
     * Add images
     *
     * @param Image $image
     *
     * @return Experience
     */
    public function addImage(Image $image)
    {
      $this->images[] = $image;

      $image->setExperience($this);

      return $this;
    }

    /**
     * Remove images
     *
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
      $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return Collection
     */
    public function getImages()
    {
      return $this->images;
    }

    /**
     * Add spoolerItem
     *
     * @param SpoolerItem $spoolerItem
     *
     * @return Experience
     */
    public function addSpoolerItem(SpoolerItem $spoolerItem)
    {
        $this->spoolerItems[] = $spoolerItem;

        return $this;
    }

    /**
     * Remove spoolerItem
     *
     * @param SpoolerItem $spoolerItem
     */
    public function removeSpoolerItem(SpoolerItem $spoolerItem)
    {
        $this->spoolerItems->removeElement($spoolerItem);
    }

    /**
     * Get spoolerItem
     *
     * @return Collection
     */
    public function getSpoolerItems()
    {
        return $this->spoolerItems;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Experience
     */
    public function setPublished($published)
    {
      $this->published = $published;

      return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
      return $this->published;
    }
}
