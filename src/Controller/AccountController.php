<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegisterType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {   
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'error' => $error,
            'lastUserName' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout() {

    }
    
    /**
     * @Route("/inscription", name="new_user")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {


            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte <strong>{$user->getEmail()}</strong> a été créer avec succès !");

            return $this->redirectToRoute('account_login');

            
        }

        return $this->render('account/inscription.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/account/profile", name="edit_user")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, ObjectManager $manager) {

        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Les modifications ont bien été enregistrées");
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/account/update-password", name="edit_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                $form->get('oldPassword')->addError(new FormError("Votre mot de passe n'est pas celui que vous avez actuellement"));

            }
            else 
            {

                $password = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $password);
                
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Votre mot de passe a bien été modifié !");

                return $this->redirectToRoute('homepage');

            }

        }

        return $this->render('account/editpassword.html.twig', [

            'form' => $form->createView(),
        ]);

        
    }


    /**
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount() {

        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);

    }

}
