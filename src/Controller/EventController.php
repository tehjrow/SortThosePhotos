<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Models\ViewModels\EventViewModel;
use App\Models\ViewModels\ServiceDetails;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EventController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 *
 * Handle requests for the even resource
 */
class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     *
     * Returns events view with list of events
     */
    public function index()
    {
        // TODO Limit events to current user
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     *
     * Create new event for user
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
     *
     * Return single even and view
     */
    public function event($id)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);

        if (!$event)
        {
            return $this->redirectToRoute('events');
        }

        $eventViewModel = new EventViewModel($event);

        return $this->render('event/event.html.twig',[
            'eventViewModel' => $eventViewModel
        ]);
    }

    /**
     * @Route("/event/{id}/csv", name="event_csv", requirements={"id"="\d+"})
     */
    public function eventUploadCsv(EntityManagerInterface $em, Request $request, FileUploader $fileUploader, $id)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($id);

        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var UploadedFile $csvFile */
            $csvFile = $form['csv']->getData();
            if ($csvFile)
            {
                $csvFilename = $fileUploader->upload($csvFile, 'csv');
                $event->setCsvFilename($csvFilename);
                $event->setHasUploadedCsv(true);

                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute('event',['id' => $event->getId()]);
            }
        }

        return $this->render('csv/csv.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }
}
