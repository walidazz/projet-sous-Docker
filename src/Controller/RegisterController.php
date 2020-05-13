<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em,  UserPasswordEncoderInterface $encoder, MailerService $mailerService, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setTokenConfirmation($this->generateToken());
            $em->persist($user);

            $em->flush();
            $token = $user->getTokenConfirmation();
            $username = $user->getUsername();
            $from = 'hisokath12@gmail.com';
            $email = $user->getEmail();

            $mailerService->sendToken($token, $email, $username, 'validateAccount.html.twig');

            $this->addFlash('success', 'vous allez recevoir un email de confirmation pour activer votre compte et pouvoir vous connecté');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/confirmAccount/{token}/{username}", name="confirmAccount")
     */
    public function confirmAccount($token, $username): Response
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(['email' => $username]);
        if ($token === $user->getTokenConfirmation()) {
            $user->setTokenConfirmation(null);
            $user->setEnable(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte est désormais actif ! ');
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('register/token-expire.html.twig');
        }
    }



    /**
     * Permet de genener un token
     *@return string
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
