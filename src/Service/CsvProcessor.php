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

    public function processCsv(int $userId, int $eventId, string $fileName)
    {
        $csvPath = $this->uploadBaseDirectory . '/csv/' . $userId . '/' . $fileName;
        $albumArray = $this->csvFileToArray($csvPath);
        $this->storeAlbumsInDatabase($userId, $eventId, $albumArray);
    }

    private function storeAlbumsInDatabase(int $userId, int $eventId, array $albumArray)
    {
        foreach ($albumArray as $albumInArray)
        {
            $album = new Album();
            $album->setUserId($userId);
            $album->setEventId($eventId);
            $album->setName($albumInArray["Album Name"]);
            $album->setPassword($albumInArray["Password"]);

            $albumFromDatabase = $this->albumExistsInDatabase($album);

            if ($albumFromDatabase)
            {
                $albumFromDatabase->setName($albumInArray["Album Name"]);
                $albumFromDatabase->setPassword($albumInArray["Password"]);
                $this->em->persist($albumFromDatabase);
            }
            else
            {
                $this->em->persist($album);
            }
        }
        $this->em->flush();
    }

    private function albumExistsInDatabase(Album $album)
    {
        $albumsInEvent = $this->albumRepository->findBy([
            'eventId' => $album->getEventId(),
            'userId' => $album->getUserId()
        ]);

        foreach ($albumsInEvent as $albumInEvent)
        {
            if ($albumInEvent->getName() == $album->getName())
            {
                return $albumInEvent;
            }
        }
        return null;
    }

    private function csvFileToArray($csvPath): array
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