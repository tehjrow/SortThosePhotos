<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends AbstractController
{
    /**
     * @Route("/q/r", name="q_r")
     */
    public function index()
    {
        return $this->render('qr/index.html.twig', [
            'controller_name' => 'QRController',
        ]);
    }
}
