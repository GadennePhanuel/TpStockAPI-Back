<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Belong;
use App\Entity\Stock;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encoder de password
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 10; $u++){
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setUsername($faker->firstName);
            $user->setFirstName($user->getUsername());
            $user->setLastName($faker->lastName);
            $user->setPassword($hash);
            $user->setRoles([]);

            $manager->persist($user);

            $articles = array();
            $stocks = array();

            for ($a = 0; $a < mt_rand(20, 40); $a++){
                $article = new Article();
                $article->setLabel($faker->sentence(4, true));
                $article->setPrice($faker->randomFloat(2, 10, 100000));
                $article->setRef($faker->postcode);
                $article->setUser($user);

                $articles[] = $article;

                $manager->persist($article);
            }

            for ($s = 0; $s < mt_rand(2, 5); $s++){
                $stock = new Stock();
                $stock->setUser($user);
                $stock->setLabel($faker->city);

                $stocks[] = $stock;

                $manager->persist($stock);


            }

            for ($i = 0; $i < count($articles); $i++){
                $belong = new Belong();
                $belong->setArticle($faker->randomElement($articles));
                $belong->setStock($faker->randomElement($stocks));
                $belong->setQty($faker->randomFloat(0, 0, 150));

                $manager->persist($belong);
            }

        }



        $manager->flush();
    }
}
