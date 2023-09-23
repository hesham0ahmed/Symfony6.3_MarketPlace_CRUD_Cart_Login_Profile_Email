<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceCalculatorController extends AbstractController
{
    // #[Route('/price/calculator', name: 'app_price_calculator')]
    // public function index(): Response
    // {
    //     return $this->render('price_calculator/index.html.twig', [
    //         'controller_name' => 'PriceCalculatorController',
    //     ]);
    // }





    public function calculateTotalPrice(array $cart)
    {
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['product']->getPrice() * $item['quantity'];
        }

        return $totalPrice;
    }
}
