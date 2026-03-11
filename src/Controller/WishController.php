<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/wishes', name: 'wish_')]
final class WishController extends AbstractController
{

    // ------------  liste complète ----------------
    #[Route('/list/{page}', name: 'list', requirements: ['id' => '\d+']), ]
    public function list(WishRepository $wishRepository, int $page = 1): Response
    {

        $wishesPublished = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        $nbWishes = count($wishesPublished);
        $maxPage = ceil($nbWishes / 10);

        if ($page > $maxPage) {
            return $this->redirectToRoute('wish_list', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return $this->redirectToRoute('wish_list', ['page' => 1]);
        }


        $wishes = $wishRepository->findWishByDate($page);
        return $this->render('wish/list.html.twig', ['wishes' => $wishes, 'currentPage' => $page, 'maxPage' => $maxPage]);
    }


    // ------------  détails d'un souhait ----------------
    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {

        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException('pas de souhait ici...');
        }


        return $this->render('wish/detail.html.twig', ['wish_id' => $wish]);
    }
}
