<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\RequestAttributeValueSame;
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


    #[Route('/add', name: 'add', methods: ['POST', 'GET'])]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            /**
             * @var UploadedFile $file
             */
            $file = ($wishForm->get('image')->getData());

            if ($file != null) {
                $newFileName = $wish->getTitle() . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move('images', $newFileName);
                $wish->setImage($newFileName);
            } else {
                $wish->setImage('stars.png');
            }

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Your wish ' . $wish->getTitle() . ' has been created');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);


        }


        return $this->render('wish/create.html.twig', ['wishForm' => $wishForm]);

    }

    #[Route('/update/{id}', name: 'update', methods: ['POST', 'GET'])]
    public function update(int $id, WishRepository $wishRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $wish = $wishRepository->find($id);
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            /**
             * @var UploadedFile $file
             */
            $file = ($wishForm->get('image')->getData());

            if ($file != null) {
                $newFileName = $wish->getTitle() . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move('images', $newFileName);
                $wish->setImage($newFileName);
            } else {
                $wish->setImage('stars.png');
            }

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Your wish ' . $wish->getTitle() . ' has been updated');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);


        }


        return $this->render('wish/update.html.twig', ['wishForm' => $wishForm]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, WishRepository $wishRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $wish = $wishRepository->find($id);
        $entityManager->remove($wish);
        $entityManager->flush();

        $this->addFlash('success', 'Your wish has been deleted');
        return $this->redirectToRoute('wish_list');

    }
}
