<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AvisRepository;
use App\Repository\FilmRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccueilController extends AbstractController
{
    /**
     * @Route("", name="accueil")
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function index(FilmRepository $filmRepository, AvisRepository $avisRepository, Security $security): Response
    {
//        $avis = $avisRepository->moyenne(1);
        return $this->render('accueil/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }
}
