<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('contact@bbadev.fr')
                ->to('contact@bbadev.fr')
                ->subject($data['subject'])
                ->text(
                    'Nom: ' . $data['name'] . "\n" .
                        'Email: ' . $data['email'] . "\n\n" .
                        $data['message']
                )
                ->html(
                    '<p>Nom: ' . $data['name'] . '</p>' .
                        '<p>Email: ' . $data['email'] . '</p>' .
                        '<p>Message: ' . nl2br($data['message']) . '</p>'
                );

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('/shared/_contact.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/test-email', name: 'test_email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('contact@bbadev.fr')
            ->to('contact@bbadev.fr')
            ->subject('Test Email')
            ->text('This is a test email.');

        try {
            $mailer->send($email);
            return new Response('Email sent successfully.');
        } catch (\Exception $e) {
            return new Response('Failed to send email: ' . $e->getMessage());
        }
    }
}
