<?php

namespace App\Controller;

use App\Models\ShootProof\spStudio;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $studio;

    public function __construct(spStudio $studio)
    {
        $this->studio = $studio;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
