<?php

namespace ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="ExperienceBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 * @see ORM\
 * @see VICH\
 */
class Image
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
     * @Vich\UploadableField(mapping="image", fileNameProperty="imageName")
     * @var File|UploadedFile
     */
    private $imageFile;

    /**
     * @ORM\Column(name="imageName", type="string", length=255)
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(name="imageLastUpdate", type="datetime")
     * @var \DateTime
     */
    private $imageLastUpdate;

    /**
     * @var Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience", inversedBy="images", cascade={"persist"})
     */
    private $experience;

    /**
     * @var Support
     *
     * @ORM\ManyToOne(targetEntity="Support", inversedBy="images", cascade={"persist"})
     */
    private $support;

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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $image
     *
     * @return Image
     */

    public function setImageFile($image = null)
    {
      $this->imageFile = $image;

      if ($image) {
        // It is required that at least one field changes if you are using doctrine
        // otherwise the event listeners won't be called and the file is lost
        $this->setImageLastUpdate(new \DateTime('now'));
      }

      return $this;
    }

    /**
     * @return File|UploadedFile
     */
    public function getImageFile()
    {
      return $this->imageFile;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Image
     */
    public function setImageName($imageName)
    {
      $this->imageName = $imageName;

      return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
      return $this->imageName;
    }

    /**
     * Set imageLastUpdate
     *
     * @param \DateTime $imageLastUpdate
     *
     * @return Image
     */
    public function setImageLastUpdate($imageLastUpdate)
    {
      $this->imageLastUpdate = $imageLastUpdate;

      return $this;
    }

    /**
     * Get imageLastUpdate
     *
     * @return \DateTime
     */
    public function getImageLastUpdate()
    {
      return $this->imageLastUpdate;
    }

    /**
     * Set experience
     *
     * @param Experience $experience
     *
     * @return Image
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
     * Set support
     *
     * @param Support $support
     *
     * @return Image
     */
    public function setSupport(Support $support = null)
    {
      $this->support = $support;

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
}

