<?php

namespace App\Controller;

use App\Entity\Album;
use Skies\QRcodeBundle\Generator\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends AbstractController
{
    /**
     * @Route("/event/{id}/qr", name="qr", requirements={"id"="\d+"})
     */
    public function index($id)
    {
        $albums = $this->getDoctrine()
            ->getRepository(Album::class)
            ->findBy([
                'eventId' => $id
            ]);

        $barcodes = '';

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
            $barcode = $generator->generate($options);
            $barcodes .= $barcode;
        }

        return $this->render('qr/index.html.twig', [
            'barcodes' => $barcodes,
        ]);
    }
}
