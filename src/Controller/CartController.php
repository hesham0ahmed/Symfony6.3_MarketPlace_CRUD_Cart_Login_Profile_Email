<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CartController extends AbstractController
{


    #[Route('/add-to-cart/{productid}/{quantity}', name: 'app_add_to_cart')]
    public function addToCart(int $productid, int $quantity, SessionInterface $session, Product $product, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);
        $product = $entityManager->getRepository(Product::class)->find($productid);

        // Check if the product is already in the cart
        if (isset($cart[$productid])) {
            // Increment the quantity
            $cart[$productid]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $cart[$productid] = [
                'product' => $product,
                // 'description' => $description,
                'quantity' => $quantity,
                // 'image' => $product->getImage(), // Assuming you have a method like getImage() to get the image path or URL
                'originalPrice' => $product->getPrice(), // Store the original product price

            ];
        }
        // Update the cart data in the session
        $session->set('cart', $cart);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        // Update the cart data in the session
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    public function calculateTotalPrice(array $cart)
    {
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['product']->getPrice() * $item['quantity'];
        }

        return $totalPrice;
    }


    #[Route('/cart', name: 'app_cart')]
    public function viewCart(SessionInterface $session, Request $request): Response
    {
        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);
        $session->set('cart', $cart);

        // Calculate the total price using the calculateTotalPrice method
        $totalPrice = $this->calculateTotalPrice($cart);

        // Check if a discount price exists in the cart
        $discountPrice = isset($cart['totalPrice']) ? $cart['totalPrice'] : $totalPrice;

        // Retrieve the original total price and promo code from the request parameters
        $originalTotalPrice = $request->query->get('originalTotalPrice');
        $promoCode = $request->query->get('promoCode');

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'discountPrice' => $discountPrice,
            'originalTotalPrice' => $originalTotalPrice,
            'promoCode' => $promoCode,
        ]);
    }
    private function getCartFromSession(SessionInterface $session): array
    {
        return $session->get('cart', []);
    }
    private function updateCartInSession(SessionInterface $session, array $cart): void
    {
        $session->set('cart', $cart);
    }


    /**
     * @Route("/update-cart/{productid}/{quantity}", name="app_update_cart")
     */
    public function updateCart(EntityManagerInterface $entityManager, SessionInterface $session, Request $request, int $productid, int $quantity): Response
    {
        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);

        // Update the quantity for the specified product ID
        if (isset($cart[$productid])) {
            $cart[$productid]['quantity'] = $quantity;
        }

        // Update the cart data in the session
        $this->updateCartInSession($request->getSession(), $cart);

        // Calculate the total price using the calculateTotalPrice method
        $totalPrice = $this->calculateTotalPrice($cart);
        $promoCode = $request->query->get('promoCode');
        // Return the updated cart data as JSON
        return $this->json([
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'promoCode' => $promoCode,
        ]);
    }
    /**
     * @Route("/remove-from-cart/{productid}", name="app_remove_from_cart")
     */
    public function removeFromCart(EntityManagerInterface $entityManager, SessionInterface $session, int $productId): Response
    {
        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);
        // $product = $entityManager->getRepository(Product::class)->find($productId);

        // Check if the product exists in the cart
        if (isset($cart[$productId])) {
            // Remove the product from the cart
            unset($cart[$productId]);

            // Update the cart data in the session
            $this->updateCartInSession($session, $cart);
        }

        // Redirect back to the cart page or any other page as needed
        return $this->redirectToRoute('app_cart');
    }


    public function redeemCode(Request $request, SessionInterface $session): Response
    {
        $promoCode = $request->request->get('promo_code'); // Get the promo code from the form submission

        // Check if the promo code is valid (you can validate against your database or any other source)
        if ($promoCode === 'PROMO2023SYMFONY') {
            // Check if the promo code has already been applied in the session
            if (!$session->get('promoCodeApplied')) {
                // Retrieve the cart from the session
                $cart = $this->getCartFromSession($session);

                // Calculate the original total price before applying the discount
                $originalTotalPrice = $this->calculateTotalPrice($cart);
                // Calculate the discount for each item in the cart (10% discount on each item's price)
                foreach ($cart as &$cartItem) {
                    $itemPrice = $cartItem['product']->getPrice();
                    $discount = $itemPrice * 0.1; // 10% discount for each item
                    $itemPrice -= $discount;
                    $cartItem['product']->setPrice($itemPrice); // Update the item's price in the cart
                }


                // Update the cart data in the session
                $this->updateCartInSession($session, $cart);

                // Set a flag in the session to indicate that the promo code has been applied
                $session->set('promoCodeApplied', true);
                // Inside the redeemCode action
                return $this->redirectToRoute('app_cart', [
                    'originalTotalPrice' => $originalTotalPrice,
                    'promoCode' => $promoCode,
                ]);
                // Redirect to the cart page with a success message or display it on the current page
                $this->addFlash('success', 'Promo code applied successfully.');
                return $this->redirectToRoute('app_cart', [

                    'promo_code' => $promoCode, // Pass the promo code to the template
                ]);
            } else {
                // Promo code has already been applied, handle the error accordingly (e.g., show an error message)
                $this->addFlash('error', 'Promo code has already been used.');
                return $this->redirectToRoute('app_cart');
            }
        } else {
            // Promo code is not valid, handle the error accordingly (e.g., show an error message)
            $this->addFlash('error', 'Invalid promo code.');
            return $this->redirectToRoute('app_cart');
        }
    }
}
