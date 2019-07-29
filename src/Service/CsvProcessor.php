<?php


namespace App\Service;


class CsvProcessor
{
    private $uploadBaseDirectory;

    public function __construct($uploadBaseDirectory)
    {
        $this->uploadBaseDirectory = $uploadBaseDirectory;
    }

    public function processCsv(int $userId, string $eventId, string $fileName)
    {
        $csvPath = $this->uploadBaseDirectory . '/csv/' . $userId . '/' . $fileName;
        $albumArray = $this->csvFileToArray($csvPath);
        dd($albumArray);
        // TODO Figure out album db schema and save it to that


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