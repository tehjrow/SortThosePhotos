<?php


namespace App\Models\ShootProof;

use App\Helpers\spApi;

class spStudio
{
    private $_spApi;

    public function __construct(spApi $spApi)
    {
        $this->_spApi = $spApi;
    }

    private function getCollection(String $path)
    {
        $response = $this->_spApi->get($path);
        $collection = new spCollection($response);
        return $collection;
    }

    private function getResource(String $path)
    {
        $response = $this->_spApi->get($path);
        return json_decode($response);
    }

    private function postResource(String $path, $item)
    {
        $response = $this->_spApi->post($path, $item);
        return $response;
    }

    public function isKeyValid()
    {
        $response = json_decode($this->_spApi->get('/studio'));
        if((isset($response->status)) && ($response->status == 401))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getCurrentUser()
    {
        $response = $this->_spApi->get('/studio/me');
        return json_decode($response);
    }

    public function getBrands()
    {
        return $this->getCollection('/studio/brand');
    }

    public function getEvents($brandId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/event');
    }

    public function getEvent($brandId, $eventId)
    {
        return $this->getResource('/studio/brand/' . $brandId . '/event/' . $eventId);
    }

    public function getAlbums($brandId, $eventId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/event/' . $eventId . '/album');
    }

    public function createAlbum($brandId, $eventId, $album)
    {
        return $this->postResource('/studio/brand/' . $brandId . '/event/' . $eventId . '/album', $album);
    }

    public function getContacts($brandId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/contact');
    }

    public function createContact($brandId, $contact)
    {
        return $this->postResource('/studio/brand/' . $brandId . '/contact', $contact);
    }

    public function getOrders($brandId)
    {
        return $this->getCollection('/studio/brand/' .$brandId . '/order');
    }

    public function getOrderItems($brandId, $orderId)
    {
        return $this->getCollection('/studio/brand/' .$brandId . '/order/' . $orderId . '/item');
    }

    /*public function getBrand($brandId)
    {
        $response = json_decode($this->_spApi->get('/studio/brand/' . $brandId));
        return $response;
    }*/
}