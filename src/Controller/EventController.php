<?php

namespace App\Controller;

use App\Form\EventFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index()
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function new()
    {
        $form = $this->createForm(EventFormType::class);

        return $this->render('event/new.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }
}
