<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $uploadBaseDirectory;

    public function __construct($uploadBaseDirectory)
    {
        $this->uploadBaseDirectory = $uploadBaseDirectory;
    }

    /**
     * @param UploadedFile $file File being uploaded.
     * @param string $directory Directory to store file in.
     * @param string|null $extension Optional extension, if null the system will guess.
     * @return string Returns filename.
     */
    public function upload(UploadedFile $file, string $directory, string $extension = null)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . (is_null($extension) ? $file->guessExtension() : $extension);

        try
        {
            $file->move($this->getTargetDirectory($directory), $fileName);
        } catch (FileException $e)
        {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory(string $directory)
    {
        return $this->uploadBaseDirectory . "/" . $directory;
    }
}