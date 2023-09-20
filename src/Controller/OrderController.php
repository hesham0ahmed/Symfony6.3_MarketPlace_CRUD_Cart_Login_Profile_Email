<?php

namespace App\Controller;

use App\Form\CheckoutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(SessionInterface $session, Request $request): Response
    {

        $session = $request->getSession();


        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);
        // $session->set('cart', $cart);
        $promoCode = $request->query->get('promoCode');
        $totalPrice = $this->calculateTotalPrice($cart);

        // Retrieve the originalTotalPrice, promoCode, and DiscountPercentage from the session
        $originalTotalPrice = $session->get('originalTotalPrice');
        $promoCode = $session->get('promoCode');
        $discountPercentage = 10; // Set the default discount percentage, change as needed

        // Check if the promo code has been applied
        $promoCodeApplied = $session->get('promoCodeApplied', false);
        // Check if a discount price exists in the cart
        $discountPrice = isset($cart['totalPrice']) ? $cart['totalPrice'] : $totalPrice;
        // If the promo code has been applied, adjust the original total price and discount percentage
        if ($promoCodeApplied) {
            // Calculate the original total price based on the cart without discounts
            $originalTotalPrice = $this->calculateTotalPrice($cart);
            // Set the discount code and percentage (adjust as needed)
            $promoCode = 'PROMO2023SYMFONY';
            $discountPercentage = 10; // Assuming a 10% discount
        }

        return $this->render('order/index.html.twig', [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'originalTotalPrice' => $originalTotalPrice,
            'discountPrice' => $discountPrice,
            'discountPercentage' => $discountPercentage,
            'promoCode' => $promoCode,

        ]);
    }


    public function calculateTotalPrice(array $cart)
    {
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['product']->getPrice() * $item['quantity'];
        }
        return $totalPrice;
    }
}
