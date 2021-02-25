<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Images;
use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\User;
use App\Form\FilmType;
use App\Form\ReservationType;
use App\Repository\FilmRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/film")
 */
class FilmController extends AbstractController
{
    /**
     * @Route("/", name="film_index", methods={"GET"})
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="film_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();
            $fichier = md5(uniqid()). '.' .$image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Images();
            $img->setNom($fichier);
            $film->addImage($img);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_show", methods={"GET"})
     */
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="film_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Film $film): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();
            $fichier = md5(uniqid()). '.' .$image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Images();
            $img->setNom($fichier);
            $film->addImage($img);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="film_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_index');
    }


    /**
     * @param FilmRepository $filmRepository
     * @param $nom
     * @return Response
     * @Route("/{nom}/description", name="film_description")
     */
    public function description(FilmRepository $filmRepository, $nom): Response
    {

        return $this->render('film/description.html.twig',[
            'descriptions' => $filmRepository->findBy(
                ['Nom' => $nom]
            )
        ]);
    }

    /**
         * @Route("/supprime/image/{id}", name="delete_image", methods={"DELETE"})
     */
    public function deleteimage(Images $images, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if($this->isCsrfTokenValid('delete'.$images->getId(), $data['_token']))
        {
            $nom = $images->getNom();
            unlink($this->getParameter('images_directory').'/'.$nom);
            $em = $this->getDoctrine()->getManager();
            $em->remove($images);
            $em->flush();

            return new JsonResponse(['success'=>1]);
        }
        else
        {
            return new JsonResponse(['error' => 'Token invalide'],400);
        }
    }
}
