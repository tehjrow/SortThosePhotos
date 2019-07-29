<?php


namespace App\Service;


use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;

class CsvProcessor
{
    private $uploadBaseDirectory;
    private $em;
    private $albumRepository;

    public function __construct(AlbumRepository $albumRepository, EntityManagerInterface $em, $uploadBaseDirectory)
    {
        $this->uploadBaseDirectory = $uploadBaseDirectory;
        $this->em = $em;
        $this->albumRepository = $albumRepository;
    }

    public function processCsv(int $userId, string $eventId, string $fileName)
    {
        $csvPath = $this->uploadBaseDirectory . '/csv/' . $userId . '/' . $fileName;
        $albumArray = $this->csvFileToArray($csvPath);
        $this->storeAlbumsInDatabase($userId, $albumArray, $eventId);


    }

    public function storeAlbumsInDatabase($userId, $albumArray, $eventId)
    {
        $albumsInEvent = $this->albumRepository->findBy([
            'eventId' => $eventId
        ]);
        dd($albumsInEvent, $albumArray);
        // TODO make sure album isn't already in db before adding
        foreach ($albumArray as $albumInArray)
        {
            $album = new Album();
            $album->setUserId($userId);
            $album->setEventId($eventId);
            $album->setName($albumInArray["Album Name"]);
            $album->setPassword($albumInArray["Password"]);
            $this->em->persist($album);
        }
        $this->em->flush();
    }

    public function csvFileToArray($csvPath)
    {
        $rows = array_map('str_getcsv', file($csvPath));

        $header = array_shift($rows);
        $header = array_map('trim', $header);

        $csv = [];
        foreach($rows as $row)
        {
            $csv[] = array_combine($header, $row);
        }

        $trimmedArray = [];
        foreach ($csv as $array)
        {
            $trimmedArray[] = array_map('trim', $array);
        }

        return $trimmedArray;
    }
}