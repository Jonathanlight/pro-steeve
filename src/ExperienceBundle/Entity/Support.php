<?php

namespace ExperienceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Support
 *
 * @ORM\Table(name="support")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\SupportRepository")
 * @see ORM\
 */
class Support
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
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="support")
     */
    private $experiences;

    /**
     * @var ArrayCollection|Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="support", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $images;

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
     * Set name
     *
     * @param string $name
     *
     * @return Support
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
     * @return Support
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
     * Add experience
     *
     * @param Experience $experience
     *
     * @return Support
     */
    public function addExperience(Experience $experience)
    {
      $this->experiences[] = $experience;
  
      return $this;
    }
  
    /**
     * Remove experience
     *
     * @param Experience $experience
     */
    public function removeExperience(Experience $experience)
    {
      $this->experiences->removeElement($experience);
    }
  
    /**
     * Get experiences
     *
     * @return Collection
     */
    public function getExperiences()
    {
      return $this->experiences;
    }

    /**
     * Add images
     *
     * @param Image $image
     *
     * @return Support
     */
    public function addImage(Image $image)
    {
      $this->images[] = $image;

      $image->setSupport($this);

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
}

