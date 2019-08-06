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
 * Class EventController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 *
 * Handle requests for the event resource
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
     * @Route("/event/{eventId}", name="event", requirements={"id"="\d+"})
     *
     * Return single even and view
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
     * @Route("/event/{eventId}/csv", name="event_csv", requirements={"id"="\d+"})
     *
     * Performs upload of CSV file from form
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
     * @Route("/event/{eventId}/processcsv", name="event_csv_process", requirements={"id"="\d+"})
     *
     * Processes CSV by turning it into an array and storing in the database
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
