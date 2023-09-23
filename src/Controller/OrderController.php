<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductInCart;
use App\Entity\User;
use App\Form\CheckoutType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/{userid}', name: 'app_order')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        SessionInterface $session,
    ): Response {
        // Get the userid from the query parameters
        $userid = $request->attributes->get('userid');
        $cart = $session->get('cart', []);
        // Find the user by ID
        $productRepository = $entityManager->getRepository(Product::class);
        $productsInCartRepository = $entityManager->getRepository(ProductInCart::class);
        $userRepository = $entityManager->getRepository(User::class);
        $products = $productRepository->findAll();
        $user = $userRepository->find($userid);
        $cartItems = $entityManager->getRepository(ProductInCart::class)->findBy(['fkUserId' => $userid]);
        // Initialize variables to store cart items with product details
        $cartItemsWithDetails = [];
        $totalPrice = 0;
        $discountPrice = 0;
        foreach ($cartItems as $cartItem) {
            $itemPrice = $cartItem->getPrice();
            $discount = $itemPrice * 0.1; // 10% discount for each item
            $itemPrice -= $discount;
            $totalPrice = $this->calculateTotalPrice($cart);
            $originalTotalPrice = $this->calculateTotalPrice($cart);
        }
        $promoCode = $request->query->get('promoCode');
        $fkUserId = $userid;
        $cartItemsWithDetails = [$cartItems !== null ? $cartItems : []];
        $session->set('cart', $cartItems);
        return $this->render('order/index.html.twig', [
            'products' => $products,
            'cartItems' => $cartItemsWithDetails,
            'totalPrice' => $totalPrice,
            'originalTotalPrice' => $totalPrice, // Assuming no discounts initially
            'promoCode' => $promoCode,
            'fkUserId' => $fkUserId,
            'discountPrice' => $discountPrice,
        ]);
        return $this->render('components/navbar.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }





    // Calculate the total price of items in the cart
    public function calculateTotalPrice(array $cartItems)
    {
        $totalPrice = 0;

        foreach ($cartItems as $cartItem) {
            $price = $cartItem->getPrice(); // Assuming you have a getPrice() method in your ProductInCart entity
            $quantity = $cartItem->getQuantity(); // Assuming you have a getQuantity() method

            $totalPrice += $price * $quantity;
        }

        return $totalPrice;
    }
}
