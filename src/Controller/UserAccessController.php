<?php

namespace App\Controller;

use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserProfileType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserAccessController extends AbstractController
{
    #[Route('/', name: 'app_user_access')]
    public function index(ProductRepository $productRepo): Response
    {
        // $user = $this->getUser();

        return $this->render('user_access/index.html.twig', [
            'products' => $productRepo->findAll()
        ]);
    }

    #[Route('user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $user = $this->getUser();
        return $this->render('show.html.twig', [
            'product' => $product,
            'user' => $user,
        ]);
    }


    // #[Route('/profile', name: 'app_user_profile', methods: ['GET'])]
    // public function profile(Product $product): Response
    // {
    //     $user = $this->getUser();

    //     return $this->render('profile/profile.html.twig', [
    //         'product' => $product,
    //         'user' => $user,
    //     ]);
    // }

    /**
     * @Route("/edit-profile", name="edit_profile")
     */

    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    // ...
    public function profile(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        $roles = $this->getUser()->getRoles();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();


            if (in_array('ROLE_ADMIN', $roles)) {
                return $this->redirectToRoute('admin'); // Redirect admin to /products
            } elseif (in_array('ROLE_USER', $roles)) {
                return $this->redirectToRoute('app_user_access'); // Redirect user to /user
            }
        }

        return $this->render('profile/profile.html.twig', [
            'editForm' => $form->createView(),
            'form' => $form,
        ]);
    }
    // ...

}
