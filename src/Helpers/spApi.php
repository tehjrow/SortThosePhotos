<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Helpers;

class spApi
{
    private $_curl;
    private $_accessToken;
    private $_response;
    private $_httpStatusCode;

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function get(String $path)
    {
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $this->_accessToken,
            "User-Agent: JeremiahApp/0.0.1"
        ));
        curl_setopt($this->_curl, CURLOPT_URL, $_ENV['BASE_SP_API_URL'] . $path);
        $this->_response = curl_exec($this->_curl);
        $this->_httpStatusCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);

        /*if ($this->_httpStatusCode == 401)
        {
            dd("401 from SP api");
        }*/

        return $this->_response;
    }

    public function post(String $path, $item)
    {
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $this->_accessToken,
            "User-Agent: JeremiahApp/0.0.1",
            "Content-Type: application/vnd.shootproof+json"
        ));
        curl_setopt($this->_curl, CURLOPT_URL, $_ENV['BASE_SP_API_URL'] . $path);
        curl_setopt($this->_curl, CURLOPT_POST, count($item));
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, json_encode($item));
        $this->_response = curl_exec($this->_curl);
        return $this->_response;
    }
}