<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductInCart;
use App\Entity\User;
use App\Form\CheckoutType;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Stripe;
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
        SessionInterface $session
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

        // Set your Stripe API key (replace with your actual Stripe API key)
        \Stripe\Stripe::setApiKey('sk_test_51NqzFzGR0qiHEMczUfzPtMDasaxfuRHwVzpmDH52nou7i04binIIbgK7D7Ia8m1rz6hSguvnqd2Ni6uoERhpLgWk00IOD3fyWE'); // Add your Stripe Secret Key here

        $clientSecret = null; // Initialize the $clientSecret variable
        // Set the total price in the session
        $session->set('totalPrice', $totalPrice);
        $session->set('fkUserId', $userid);
        if ($request->isMethod('POST')) {
            // Create a PaymentIntent with your own price
            $amount = $totalPrice * 100; // Replace with your desired amount in cents
            $currency = 'usd';

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
            ]);

            // Pass the client secret to your template
            $clientSecret = $intent->client_secret;
        }

        return $this->render('order/index.html.twig', [
            'products' => $products,
            'cartItems' => $cartItemsWithDetails,
            'totalPrice' => $totalPrice,
            'originalTotalPrice' => $totalPrice, // Assuming no discounts initially
            'promoCode' => $promoCode,
            'fkUserId' => $fkUserId,
            'discountPrice' => $discountPrice,
            'clientSecret' => $clientSecret, // Add the client secret to your template
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }


    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, EntityManagerInterface $entityManager, SessionInterface $session)
    {
        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        // Retrieve the total price from the session
        $totalPrice = $session->get('totalPrice');
        $userid = $session->get('fkUserId');

        \Stripe\Charge::create([
            "amount" => $totalPrice * 100, // You may need to adjust this amount
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => $userid,
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );

        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
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
