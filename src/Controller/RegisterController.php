<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
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
    public function register(Request $request, EntityManagerInterface $em,  UserPasswordEncoderInterface $encoder)
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
            $email = $user->getEmail();

            $this->addFlash("success", "Un mail de confirmation a été envoyé sur votre adresse mail");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function confirmAccount($token, $username): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        $tokenExist = $user->getTokenConfirmation();
        if ($token === $tokenExist) {
            $user->setTokenConfirmation(null);
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();
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
