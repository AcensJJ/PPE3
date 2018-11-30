<?php

namespace App\Controller;

use App\AJ\UserBundle\TokenConfirm;
use App\Entity\User;
use App\Entity\Panier;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, ObjectManager $manager) {
        // build the form
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        // handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // $cart = new Cart();
            $panier = new Panier();
            $panier->setUser($user);
            $manager->persist($panier);
            $manager->flush();

            //Token Confirm
            $tokenClass = new TokenConfirm();
            $token = $tokenClass->generateTokenConfirm();
            $user->setConfirmationToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            // $entityManager->persist($cart);

            //SendMailToken
            $linkConfirm = "127.0.0.1/activateAccount/".$token;
            $message = (new \Swift_Message('Activez votre compte ! - AcensSell'))
            ->setFrom('no-reply@AcensSell.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/confirmToken.html.twig',
                    array('linkConfirm' => $linkConfirm)
                ),
                'text/html'
            );
            $mailer->send($message);

            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'Votre compte à bien été enregistré, activez le grâce au lien que nous vous avons envoyé par mail !');
            //return $this->redirectToRoute('login');
        }
        return $this->render('connexion/inscription.html.twig', [
            'controller_name' => 'Inscription',
            'form' => $form->createView(),
            'mainNavRegistration' => true,
            'title' => 'Inscription'
        ]);
    }

}
