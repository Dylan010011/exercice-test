<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository)
    {   
        $ads = $repository->findAll();

        return $this->render('ad/index.html.twig', [

            'annonces' => $ads

        ]);
    }


    /**
     * @Route("/ads/new", name="new_ads")
     */
    public function new(Request $request, ObjectManager $manager) {

        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            foreach($ad->getImages() as $image) {

                $image->setAd($ad);
                $manager->persist($image);

            }

            $user = $this->getUser();
            $ad->setAuthor($user);

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a été créer avec succès !");

            return $this->redirectToRoute('view', array('slug' => $ad->getSlug()));
        
        }
        
        return $this->render('ad/form.html.twig', [

            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/ads/{slug}", name="view")
     */
    public function view(Ad $ad) {


        return $this->render('ad/view.html.twig', [

            'annonce' => $ad

        ]);

    }


    /**
     * @Route("/ads/{slug}/edit", name="edit")
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            foreach($ad->getImages() as $image) {

                $image->setAd($ad);
                $manager->persist($image);

            }

            $manager->persist($ad);
            $manager->flush();


            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a été modifiée avec succès !");

            return $this->redirectToRoute('view', array('slug' => $ad->getSlug()));
        
        }

        return $this->render('ad/edit.html.twig', [

            'form' => $form->createView(),
            'annonce' => $ad

        ]);

    }


}
