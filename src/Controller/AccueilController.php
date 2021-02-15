<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("", name="accueil")
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('accueil/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }
}
