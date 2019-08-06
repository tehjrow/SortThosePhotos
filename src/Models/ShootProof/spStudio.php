<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Models\ShootProof;

use App\Helpers\spApi;

/**
 * Helper class to get/post studio data to the ShootProof API.
 *
 * Class spStudio
 * @package App\Models\ShootProof
 */
class spStudio
{
    private $_spApi;

    public function __construct($accessToken)
    {
        $this->_spApi = new spApi($accessToken);
    }

    /**
     * Does a basic request to the ShootProof API to test the access_token.
     *
     * @return bool Is access_token valid
     */
    public function isKeyValid()
    {
        $response = json_decode($this->_spApi->get('/studio'));
        if (is_null($response))
        {
            return false;
        }

        if((isset($response->status)) && ($response->status == 401))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Gets the current studio user
     *
     * @return mixed
     */
    public function getCurrentUser()
    {
        $response = $this->_spApi->get('/studio/me');
        return json_decode($response);
    }

    /**
     * Gets a collection of brands
     *
     * @return spCollection
     */
    public function getBrands()
    {
        return $this->getCollection('/studio/brand');
    }

    /**
     * Gets a collection of events in a brand
     *
     * @param $brandId
     * @return spCollection
     */
    public function getEvents($brandId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/event');
    }

    /**
     * Gets a specific event in a brand
     *
     * @param $brandId
     * @param $eventId
     * @return mixed
     */
    public function getEvent($brandId, $eventId)
    {
        return $this->getResource('/studio/brand/' . $brandId . '/event/' . $eventId);
    }

    /**
     * Gets a collection of albums in an event
     *
     * @param $brandId
     * @param $eventId
     * @return spCollection
     */
    public function getAlbums($brandId, $eventId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/event/' . $eventId . '/album');
    }

    /**
     * Creates a single album in an event
     *
     * @param $brandId
     * @param $eventId
     * @param $album
     * @return bool|string
     */
    public function createAlbum($brandId, $eventId, $album)
    {
        return $this->postResource('/studio/brand/' . $brandId . '/event/' . $eventId . '/album', $album);
    }

    /**
     * Get contacts for a brand
     * @param $brandId
     * @return spCollection
     */
    public function getContacts($brandId)
    {
        return $this->getCollection('/studio/brand/' . $brandId . '/contact');
    }

    /**
     * Create contact in a brand
     *
     * @param $brandId
     * @param $contact
     * @return bool|string
     */
    public function createContact($brandId, $contact)
    {
        return $this->postResource('/studio/brand/' . $brandId . '/contact', $contact);
    }

    /**
     * Get orders from a brand
     *
     * @param $brandId
     * @return spCollection
     */
    public function getOrders($brandId)
    {
        return $this->getCollection('/studio/brand/' .$brandId . '/order');
    }

    /**
     * Get items in an order for a brand
     *
     * @param $brandId
     * @param $orderId
     * @return spCollection
     */
    public function getOrderItems($brandId, $orderId)
    {
        return $this->getCollection('/studio/brand/' .$brandId . '/order/' . $orderId . '/item');
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

    /*public function getBrand($brandId)
    {
        $response = json_decode($this->_spApi->get('/studio/brand/' . $brandId));
        return $response;
    }*/
}