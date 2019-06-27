<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {

        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        
        $faker = Factory::create('fr-FR');

        //Nous gérons les rôles
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Jack')
                  ->setLastName('Sparrow')
                  ->setEmail('jacksparrow@yahoo.fr')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://vignette.wikia.nocookie.net/pirates/images/e/ea/DMTNT_Jack_Sparrow_cropped.png/revision/latest?cb=20170507052033')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);

            $manager->persist($adminUser);
        

        //Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i < 11; $i++) {

            $user = new User();
            
            $genre = $faker->randomElement($genres);
            $firstName = $faker->firstName($genre);
            $lastName = $faker->lastName;
            $email = $faker->email;

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99);

            $picture = $picture . ($genre == 'male' ? 'men/' : 'women/') . $pictureId . '.jpg';

            $hash = $this->encoder->encodePassword($user, 'password');
            
            $user->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setEmail($email)
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                    ->setHash($hash)
                    ->setPicture($picture)
                    ->getRoles();

                $manager->persist($user);
            
            $users[] = $user;
        }

        //Nous gérons les annonces
        for($i = 1; $i < 31; $i++) {

            $title = $faker->sentence();
            
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad = new Ad();

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setPrice(mt_rand(40,200))
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCoverImage($coverImage)
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

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
