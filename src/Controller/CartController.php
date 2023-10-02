<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use App\Entity\ProductInCart;
use App\Entity\User;
use App\Form\CheckoutType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\EntityResult;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

class CartController extends AbstractController
{
    #[Route('/add-to-cart/{userid}/{productid}/{quantity}', name: 'app_add_to_cart')]
    public function addToCart(
        int $userid,
        int $productid,
        int $quantity,
        EntityManagerInterface $entityManager,
    ): Response {
        // Find the product by ID // Find the user by ID
        $product = $entityManager->getRepository(Product::class)->find($productid);
        $user = $entityManager->getRepository(User::class)->find($userid);
        if (!$product) {
            // Handle the case when the product is not found (optional)
            return $this->redirectToRoute('your_error_route');
        }
        if (!$user) {
            return $this->redirectToRoute('your_error_route');
        }
        // Check if a cart item with the same fkUserId and fkProductId already exists
        $existingCartItem = $entityManager->getRepository(ProductInCart::class)->findOneBy([
            'fkUserId' => $userid,
            'fkProductId' => $productid,
        ]);

        if ($existingCartItem) {
            // If it exists, update the quantity instead of creating a new entry
            $existingQuantity = $existingCartItem->getQuantity();
            $existingCartItem->setQuantity($existingQuantity + $quantity);
        } else {
            // Create a new cart item
            $cartItem = new ProductInCart();
            // Set the user/product ID - Set the quantity in the cart for the item
            $cartItem->setFkUserId($userid);
            $cartItem->setFkProductId($productid);
            $cartItem->setQuantity($quantity);
            // Calculate the price based on the product's price
            $price = $product->getPrice();
            $cartItem->setPrice($price);
            // Persist the cart item - Add to Database
            $entityManager->persist($cartItem);
        }
        $entityManager->flush();
        // Redirect to the cart page
        return $this->redirectToRoute('app_cart', ['userid' => $userid]);
    }

    #[Route('/cart/{userid}', name: 'app_cart')]
    public function viewCart(
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
        return $this->render('cart/index.html.twig', [
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

    /**
     * @Route("/update-cart/{productid}/{quantity}", name="app_update_cart")
     */
    public function updateCart(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        Request $request,
        int $userid,
        int $productid,
        int $quantity
    ): Response {
        $userid = $request->attributes->get('userid');
        // Retrieve the cart data from the session
        $cart = $session->get('cart', []);

        // Update the quantity for the specified product ID
        if (isset($cart[$productid])) {
            $cart[$productid]['quantity'] = $quantity;
        }

        $this->updateCartInSession($session, $cart);
        // Calculate the total price using the calculateTotalPrice method
        $totalPrice = $this->calculateTotalPrice($cart);
        $promoCode = $request->query->get('promoCode');
        // Return the updated cart data as JSON

        return $this->json([
            'cart' => $cart,
            'totalPrice' => $totalPrice,
            'promoCode' => $promoCode,
            // 'totalQuantity' => $totalQuantity,
        ]);
    }

    /**
     * @Route("/remove-from-cart/{productId}", name="app_remove_from_cart")
     */
    public function removeFromCart(
        EntityManagerInterface $entityManager,

        Request $request
    ): Response {
        // Get the userid from the query parameters
        $userid = $request->query->get('userid');

        // Get the productId from the route parameters
        $productId = $request->attributes->get('productId');

        // Find the ProductInCart entity by its ID
        $cartItem = $entityManager->getRepository(ProductInCart::class)->find($productId);

        // Check if the entity exists
        if (!$cartItem) {
            throw new NotFoundHttpException('Product not found in cart.');
        }

        // Attempt to remove the cart item
        try {
            $entityManager->remove($cartItem);
            $entityManager->flush();
        } catch (\Exception $e) {
            // Log the exception or handle it in some way
            throw new NotFoundHttpException('Error removing product from cart: ' . $e->getMessage());
        }

        // Redirect back to the cart page for the specific user
        return $this->redirectToRoute('app_cart', ['userid' => $userid]);
    }







    /**
     * @Route("/increase-quantity/{fkProductId}", name="app_increase_quantity")
     */
    public function increaseQuantity(
        EntityManagerInterface $entityManager,

        Request $request
    ): Response {
        // Get the userid from the query parameters
        $userid = $request->query->get('userid');

        // Get the productId from the route parameters
        $productId = $request->attributes->get('fkProductId');

        // Find the ProductInCart entity by fkProductId and additional criteria if needed
        $cartItem = $entityManager->getRepository(ProductInCart::class)
            ->findOneBy([
                'fkProductId' => $productId,
                'fkUserId' => $userid
                // You can add additional criteria if needed, e.g., 'fkUserId' => $userid
            ]);

        // Check if the entity exists
        if (!$cartItem) {
            throw new NotFoundHttpException('Product not found in cart.');
        }

        // Update the cart item quantity (increase by 1, for example)
        $newQuantity = $cartItem->getQuantity() + 1;
        $cartItem->setQuantity($newQuantity);

        // Persist the changes
        $entityManager->persist($cartItem);
        $entityManager->flush();

        // Redirect back to the cart page for the specific user
        return $this->redirectToRoute('app_cart', ['userid' => $userid]);
    }



    /**
     * @Route("/decrease-quantity/{fkProductId}", name="app_decrease_quantity")
     */
    public function decreaseQuantity(
        EntityManagerInterface $entityManager,

        Request $request
    ): Response {
        // Get the userid from the query parameters
        $userid = $request->query->get('userid');

        // Get the productId from the route parameters
        $productId = $request->attributes->get('fkProductId');

        // Find the ProductInCart entity by fkProductId and additional criteria if needed
        $cartItem = $entityManager->getRepository(ProductInCart::class)
            ->findOneBy([
                'fkProductId' => $productId,
                // You can add additional criteria if needed, e.g., 'fkUserId' => $userid
            ]);

        // Check if the entity exists
        if (!$cartItem) {
            throw new NotFoundHttpException('Product not found in cart.');
        }

        // Update the cart item quantity (decrease by 1, if greater than 1)
        $newQuantity = $cartItem->getQuantity() - 1; // Ensure quantity is at least 1
        if ($newQuantity <= 0) {
            // Quantity is less than or equal to 0, remove the item from the cart
            $entityManager->remove($cartItem);
        } else {
            // Update the quantity
            $cartItem->setQuantity($newQuantity);
        }


        // Persist the changes

        $entityManager->flush();

        // Redirect back to the cart page for the specific user
        return $this->redirectToRoute('app_cart', ['userid' => $userid]);
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
     * @Route("/redeem-code/{userid}", name="app_redeem_code" )
     */
    public function redeemCode(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $promoCode = $request->request->get('promo_code'); // Get the promo code from the form submission
        $userid = $request->attributes->get('userid');

        // Get the productId from the route parameters
        $productId = $request->attributes->get('fkProductId');
        if ($session->get('promoCodeApplied')) {
            // Promo code has already been applied, handle the error accordingly
            $this->addFlash('error', 'Promo code has already been used.');
            return $this->redirectToRoute('app_cart', ['userid' => $userid, 'promo_code' => $promoCode]);
        }
        // Check if the promo code is valid (you can validate against your database or any other source)
        if ($promoCode === 'PROMO2023SYMFONY') {
            // Check if the promo code has already been applied in the session
            if (!$session->get('promoCodeApplied')) {
                // Retrieve the cart from the session
                $cart = $this->getCartFromSession($session);

                // Calculate the original total price before applying the discount
                $originalTotalPrice = $this->calculateTotalPrice($cart);
                $cartItems = $entityManager->getRepository(ProductInCart::class)->findBy(['fkUserId' => $userid]);
                // Calculate the discount for each item in the cart (10% discount on each item's price)
                foreach ($cartItems as $cartItem) {
                    $itemPrice = $cartItem->getPrice();

                    $discount = $itemPrice * 0.1; // 10% discount for each item
                    $itemPrice -= $discount;
                    $cartItem->setPrice($itemPrice); // Update the item's price in the cart
                }
                // Update the cart data in the session
                $this->updateCartInSession($session, $cart);


                // Set the originalTotalPrice in the session
                $session->set('originalTotalPrice', $originalTotalPrice);

                // Set the promoCode in the session
                $session->set('promoCode', $promoCode);

                // Set a flag in the session to indicate that the promo code has been applied
                $session->set('promoCodeApplied', true);

                $entityManager->persist($cartItem);
                $entityManager->flush();


                // Redirect to the cart page with a success message or display it on the current page
                $this->addFlash('success', 'Promo code applied successfully.');
                return $this->redirectToRoute('app_cart', [
                    'userid' => $userid,
                    'promo_code' => $promoCode, // Pass the promo code to the template
                ]);
            } else {
                // Promo code has already been applied, handle the error accordingly (e.g., show an error message)
                $this->addFlash('error', 'Promo code has already been used.');
                return $this->redirectToRoute('app_cart', ['userid' => $userid, 'promo_code' => $promoCode]);
            }
        } else {
            // Promo code is not valid, handle the error accordingly (e.g., show an error message)
            $this->addFlash('error', 'Invalid promo code.');
            return $this->redirectToRoute('app_cart', ['userid' => $userid]);
        }
    }
}
