<?php

namespace App\Controller;

use App\Entity\Album;
use Skies\QRcodeBundle\Generator\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends AbstractController
{
    /**
     * @Route("/event/{eventId}/qr", name="qr", requirements={"id"="\d+"})
     */
    public function index($eventId)
    {
        $albums = $this->getDoctrine()
            ->getRepository(Album::class)
            ->findBy([
                'eventId' => $eventId
            ]);

        $barCodes = '';

        foreach ($albums as $album)
        {
            $options = array(
                'code'   => strval($album->getId()),
                'type'   => 'qrcode',
                'format' => 'svg',
                'width'  => 10,
                'height' => 10,
                'color'  => 'green',
            );
            $generator = new Generator();
            $barCode = $generator->generate($options);
            $barCodes .= $barCode;
        }

        return $this->render('qr/index.html.twig', [
            'barCodes' => $barCodes,
        ]);
    }
}
