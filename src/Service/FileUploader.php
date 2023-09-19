<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
  private $targetDirectory;
  //defined below in the services.yaml file
  private $slugger;

  public function __construct($targetDirectory, SluggerInterface $slugger)
  {
    $this->targetDirectory = $targetDirectory;
    $this->slugger = $slugger;
  }

  public function upload(UploadedFile $file)
  {
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $safeFilename = $this->slugger->slug($originalFilename);
    //the slugger convert the file name into a safe string value
    $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

    try {
      $file->move($this->getTargetDirectory(), $fileName);
    } catch (FileException $e) {
      //... handle exception if something happens during file upload
      //return;
    }

    return $fileName;
  }

  public function getTargetDirectory()
  {
    return $this->targetDirectory;
  }
}
