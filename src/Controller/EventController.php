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
use App\Service\CsvProcessor;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handle requests for the event resource
 *
 * Class EventController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class EventController extends AbstractController
{
    /**
     * Returns events view with list of events
     *
     * @Route("/events", name="events")
     */
    public function index()
    {
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findBy([
                'userId' => $this->getUser()->getId()
            ]);

        return $this->render('event/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * Create new event for user
     *
     * @Route("/event/new", name="event_new")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(EventFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $event = $form->getData();
            $event->setUserId($this->getUser()->getId());

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event',['eventId' => $event->getId()]);
        }

        return $this->render('event/new.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }

    /**
     * Return single even and view
     *
     * @Route("/event/{eventId}", name="event", requirements={"id"="\d+"})
     */
    public function event($eventId)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($eventId);

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
     * Performs upload of CSV file from form
     *
     * @Route("/event/{eventId}/csv", name="event_csv", requirements={"id"="\d+"})
     */
    public function eventUploadCsv(EntityManagerInterface $em, Request $request, FileUploader $fileUploader, $eventId)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($eventId);

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $csvFile = $form['csv']->getData();

            if ($csvFile)
            {
                $csvFilename = $fileUploader->upload($csvFile, "csv/" . $this->getUser()->getId(), 'csv');
                $event->setCsvFilename($csvFilename);
                $event->setHasUploadedCsv(true);

                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute('event_csv_process',['eventId' => $event->getId()]);
            }
        }

        return $this->render('csv/csv.html.twig',[
            'eventForm' => $form->createView()
        ]);
    }

    /**
     * Processes CSV by turning it into an array and storing in the database
     *
     * @Route("/event/{eventId}/processcsv", name="event_csv_process", requirements={"id"="\d+"})
     */
    public function eventProcessCsv($eventId, CsvProcessor $csvProcessor)
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($eventId);

        $csvProcessor->processCsv($this->getUser()->getId(), $eventId, $event->getCsvFilename());

        return $this->redirectToRoute('event',['eventId' => $event->getId()]);
    }
}
