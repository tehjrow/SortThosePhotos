<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
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
     * @Route("/events", name="events")
     */
    public function index()
    {
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/events.html.twig', [
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

            return $this->redirectToRoute('event',['id' => $event->getId()]);
        }

        return $this->render('event/new.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{id}", name="event", requirements={"id"="\d+"})
     */
    public function get($id)
    {
        return $this->json(['id' => $id]);
    }
}
