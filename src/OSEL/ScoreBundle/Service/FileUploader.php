<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 10.11.17
 * Time: 20:41
 */
namespace OSEL\ScoreBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $uploadRootDir;

    public function __construct($rootDir)
    {
        $this->uploadRootDir = $rootDir;
    }

    public function upload(UploadedFile $file, $uploadDir, $fileName)
    {

        $dir = $this->getUploadRootDir() . "/" . $uploadDir;

        $file->move($dir, $fileName);

        return $fileName;
    }

    public function getUploadRootDir()
    {
        return $this->uploadRootDir;
    }
}