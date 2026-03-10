<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/bucket', name: 'main_bucket')]
    public function bucket(): Response
    {
        return $this->render('bucket.html.twig');
    }


    #[Route('/about', name: 'main_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
}
