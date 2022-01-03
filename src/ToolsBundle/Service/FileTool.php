<?php
namespace ToolsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTool {

  private $tmp_directory;

  public function __construct($tmp_directory) {
    $this->tmp_directory = $tmp_directory;
  }

  public function prepareDirectory(){
    if(!is_dir($this->tmp_directory)){
      mkdir($this->tmp_directory);
    }
    /**
     * Suppression des fichiers temporaires plus vieux de 30 minutes
     */
    foreach (glob($this->tmp_directory."*") as $file) {
      /*** if file is 30 minutes (1800 seconds) old then delete it ***/
      if(time() - filectime($file) > 1800){
        unlink($file);
      }
    }
  }

  public function prepareUploadedFile(UploadedFile $uploaded_file){
    $this->prepareDirectory();
    $file = $uploaded_file->move($this->tmp_directory, $uploaded_file->getPathname());
    $f = fopen($file->getPathname(), 'r');
    return $f;
  }
}