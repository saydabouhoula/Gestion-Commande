<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Bioline1Controller extends AbstractController
{
    #[Route('/bioline1', name: 'app_bioline1')]
    public function index(): Response
    {
        return $this->render('bioline1/index.html.twig', [
            'controller_name' => 'Bioline1Controller',
        ]);
    }
}
