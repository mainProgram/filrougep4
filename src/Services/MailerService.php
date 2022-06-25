<?php

namespace App\Services;

use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService  
{
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmail($user, $objet='Création de compte')
    {
        $email = (new Email())
            ->from('fazeyfadiop@gmail.com') //ki key yonei
            ->to($user->getEmail()) // kign key yonei
            ->subject($objet) // objet mail bi
            ->html($this->twig->render("mail/index.html.twig", [
                "user" => $user
            ])); // Contenu mail bi

        $this->mailer->send($email);
    }
}