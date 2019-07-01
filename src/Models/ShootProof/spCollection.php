<?php

/**
 * Album Management
 *
 * A tool to help high volume photographers manage their albums
 */

namespace App\Models\ShootProof;

class spCollection
{
    private $_items;
    private $_meta;
    private $_links;

    public function __construct($response)
    {
        $this->populateModel($response);
    }

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