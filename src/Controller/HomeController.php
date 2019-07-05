<?php

namespace App\Controller;

use App\Models\ShootProof\spStudio;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        if (!$this->isGranted('ROLE_USER'))
        {
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }
        else
        {
            return $this->render('home/dashboard.html.twig');
        }

    }
}
