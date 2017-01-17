<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Files
{
    private $saveDir;

    /**
     * Files constructor.
     * @param $rootDir
     * @param string $saveDir
     */
    public function __construct($rootDir, $saveDir = '/../web')
    {
        $this->saveDir = $rootDir . $saveDir;
    }

    /**
     * @param UploadedFile $file
     * @param null $prefix
     * @return string
     */
    public function getRandomFileName(UploadedFile $file, $prefix = null)
    {
        $extension = $file->guessExtension();
        if (!$extension) {
            $extension = 'bin';
        }

        if ($prefix) {
            return $prefix . '_' . time() . '_' . rand(1, 99999) . '.' . $extension;
        }

        return time() . '_' . rand(1, 99999) . '.' . $extension;
    }

    /**
     * @param UploadedFile $file
     * @param $savePath
     *
     * @return string
     */
    public function saveFileWithRandomName(UploadedFile $file, $savePath)
    {
        $randomFileName = $this->getRandomFileName($file);
        $file->move($this->saveDir . $savePath, $randomFileName);

        return $randomFileName;
    }
}
