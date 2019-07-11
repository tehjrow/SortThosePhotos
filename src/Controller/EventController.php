<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index()
    {
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(EventFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Event $event */
            $event = $form->getData();
            $event->setUserId($this->getUser()->getId());

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event');
        }

        return $this->render('event/new.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }
}
