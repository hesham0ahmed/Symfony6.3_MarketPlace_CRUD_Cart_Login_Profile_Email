<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\EmailType;
use Symfony\Component\HttpFoundation\Request;

use App\Form\EmailFormType; // Update the import
use Symfony\Component\HttpFoundation\RedirectResponse;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(EmailFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $mail = (new Email())
                ->from($data['from'])
                ->to($data['to'])
                ->subject($data['subject'])
                ->text($data['text']);

            try {
                $mailer->send($mail);
                $this->addFlash('success', 'Email sent successfully!');
            } catch (TransportExceptionInterface $error) {
                $this->addFlash('error', 'Error sending email: ' . $error->getMessage());
            } {
                return $this->redirectToRoute('app_email_send1'); // Redirect user to /user
            }
        }

        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mailer/mailSendNotice', name: 'app_email_send1')]
    public function noticeSended(Request $request): Response
    {

        return $this->render('mailer/mailSendNotice.html.twig', [
            'controller_name' => 'MailerController',

        ]);
    }

    #[Route('/mailer/mailSend', name: 'app_email_send')]
    public function sended(Request $request): Response
    {


        $roles = $this->getUser()->getRoles(); // Get the roles of the current user

        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('app_product_index'); // Redirect admin to /products
        } elseif (in_array('ROLE_USER', $roles)) {
            return $this->redirectToRoute('app_user_access'); // Redirect user to /user
        }
    }

    // #[Route('/user', name: 'app_user_access')] // Create this route
    // public function userPage(Request $request): Response
    // {
    //     return $this->render('mailer/userPage.html.twig', [
    //         'controller_name' => 'MailerController',
    //     ]);
    // }

    // #[Route('/products', name: 'app_product_index')] // Create this route
    // public function adminProductsPage(Request $request): Response
    // {
    //     $products = []; // Fetch products from a database or other source

    //     return $this->render('mailer/adminProductsPage.html.twig', [
    //         'controller_name' => 'MailerController',
    //         'products' => $products, // Pass the products data to the template
    //     ]);
    // }
}
