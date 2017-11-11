<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 10.11.17
 * Time: 20:41
 */
namespace OSEL\ScoreBundle\Service;

class FileUploader
{
    private $uploadRootDir;

    public function __construct($rootDir)
    {
        $this->targetDir = $rootDir;
    }

    public function upload(UploadedFile $file, $uploadDir, $nameRand)
    {
        if($nameRand){
            $fileName = md5(uniqid()).'.'. $file->guessExtension();
        }
        else{
            $fileName = $file->getClientOriginalName() . $file->guessExtension();
        }

        $file->move($this->getUploadRootDir() . $uploadDir, $fileName);

        return $fileName;
    }

    public function getUploadRootDir()
    {
        return $this->uploadRootDir;
    }
}