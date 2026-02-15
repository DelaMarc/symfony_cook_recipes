<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\ContactFormDTO;
use App\Form\ContactFormDTOType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactFormDTO();
        $form = $this->createForm(ContactFormDTOType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            
            try {
                $email = (new TemplatedEmail())
                    ->from($contact->email)
                    ->to($contact->service)
                    ->text($contact->message)
                    ->subject('Demande de contact')
                    ->htmlTemplate('mail/contact.html.twig')
                    ->context(['data' => $contact]);
                $mailer->send($email);

                $mailer->send($email);
            } catch (\Exception $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
                $this->addFlash('danger', 'Votre email n\'a pas pu être envoyé');
                return $this->redirectToRoute('contact');
            }

            $this->addFlash('success', 'Votre email a bien été envoyé');
            // dd($contact);
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
