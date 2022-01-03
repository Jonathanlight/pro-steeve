<?php

namespace SpoolerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ResultFiles
 *
 * @ORM\Table(name="result_files")
 * @ORM\Entity(repositoryClass="SpoolerBundle\Repository\ResultFileRepository")
 * @Vich\Uploadable
 * @Vich\Uploadable
 * @see ORM\
 * @see VICH\
 */
class ResultFile
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @Vich\UploadableField(mapping="results", fileNameProperty="fileName")
     * @var File|UploadedFile
     */
    private $file;

    /**
     * @ORM\Column(name="fileName", type="string", length=255)
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var SpoolerItem
     *
     * @ORM\ManyToOne(targetEntity="SpoolerItem", inversedBy="resultFile", cascade={"persist"})
     */
    private $spooleritem;


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
     * Set title
     *
     * @param string $title
     *
     * @return ResultFile
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param File|UploadedFile $file
     *
     * @return ResultFile
     */

    public function setFile($file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedAt(new \DateTime('now'));
        }

        return $this;
    }

    /**
     * @return File|UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return ResultFile
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Set spooleritem
     *
     * @param SpoolerItem $spooleritem
     *
     * @return ResultFile
     */
    public function setSpooleritem(SpoolerItem $spooleritem = null)
    {
        $this->spooleritem = $spooleritem;
        $spooleritem->addResultfile($this);

        return $this;
    }

    /**
     * Get spooleritem
     *
     * @return SpoolerItem
     */
    public function getSpooleritem()
    {
        return $this->spooleritem;
    }

}

