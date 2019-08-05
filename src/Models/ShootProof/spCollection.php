<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Models\ShootProof;

/**
 * Used to store the output from the ShootProof API response on a collection request.
 *
 * Class spCollection
 * @package App\Models\ShootProof
 */
class spCollection
{
    private $_items;
    private $_meta;
    private $_links;

    public function __construct($response)
    {
        $this->populateModel($response);
    }

    /**
     * @param string $response Raw HTTP response from an API request.
     */
    private function populateModel($response)
    {
        $response = json_decode($response);
        $this->_items = $response->items;
        $this->_meta = $response->meta;
        $this->_links = $response->links;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function getItemByName($name)
    {
        foreach ($this->_items as $item)
        {
            if ($item->name == $name)
            {
                return $item;
            }
        }
        return null;
    }
}