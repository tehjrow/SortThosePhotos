<?php


namespace App\Models\ViewModels;


use App\Entity\Event;

class EventViewModel
{
    private $_event;
    private $_serviceDetails;

    public function __construct(Event $event)
    {
        $this->_event = $event;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->_event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->_event = $event;
    }

    /**
     * @return ServiceDetails
     */
    public function getServiceDetails(): ServiceDetails
    {
        return $this->_serviceDetails;
    }

    /**
     * @param ServiceDetails $serviceDetails
     */
    public function setServiceDetails(ServiceDetails $serviceDetails): void
    {
        $this->_serviceDetails = $serviceDetails;
    }
}