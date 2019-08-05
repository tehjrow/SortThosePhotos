<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Service;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CsvProcessor
 * @package App\Service Processes CSV album files and stores them in the database.
 */
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

    /**
     * Processes a stored csv file and stores the entries in the database.
     *
     * @param int $userId Current UserId.
     * @param int $eventId Current EventId.
     * @param string $fileName Name of stored CSV file to process.
     */
    public function processCsv(int $userId, int $eventId, string $fileName)
    {
        $csvPath = $this->uploadBaseDirectory . '/csv/' . $userId . '/' . $fileName;
        $albumArray = $this->csvFileToArray($csvPath);
        $this->storeAlbumsInDatabase($userId, $eventId, $albumArray);
    }

    /**
     * Stores the albums in the database, updating albums if they already exist.
     *
     * @param int $userId Current UserId.
     * @param int $eventId Current EventId.
     * @param array $albumArray Array generated from csv file.
     */
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

            // If the album is already stored in the database
            // we should update that one instead of making a new one.
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

    /**
     * Checks to see if the passed Album is stored in the database by name, user, and eventid.
     *
     * @param Album $album Album to check database for.
     * @return Album|null Returns album if exists, null if not.
     */
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

    /**
     * Takes the path to a stored csv file and returns an array of those entries
     *
     * @param string $csvPath Path to stored csv file.
     * @return array Returns array created from stored csv file.
     */
    private function csvFileToArray(string $csvPath): array
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