<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    /**
     * @Route("/category/{categoryName}", name="show_category")
     */
    public function showCategory($categoryName, EntityManagerInterface $entityManager): Response
    {
        // Fetch products for the specified category from your database

        $products = $entityManager->getRepository(Product::class)
            ->findBy(['categorie' => $categoryName]);
        $categories = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
            ->select('DISTINCT p.categorie')
            ->getQuery()
            ->getResult();
        return $this->render('category/show.html.twig', [
            'categoryName' => $categoryName,
            'products' => $products, // Pass the products to the template
            'categories' => $categories,
        ]);
    }
}
