<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $faker = Factory::create('fr-FR');
        

        for($i = 1; $i < 31; $i++) {

            $title = $faker->sentence();
            
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad = new Ad();
            $ad->setTitle($title)
                ->setPrice(mt_rand(40,200))
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCoverImage($coverImage)
                ->setRooms(mt_rand(1, 5));

            for($j = 1; $j <= mt_rand(2, 5); $j++) {

                $url = $faker->imageUrl();

                $image = new Image();
                $image->setUrl($url)
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                $manager->persist($image);

            }

            $manager->persist($ad);

        }
      
        $manager->flush();
    }
}
