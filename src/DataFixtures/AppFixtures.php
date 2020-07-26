<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $genre = ['homme', 'femme'];
        $slugify = new Slugify();
        $tabRaison = ['Spam', 'Arnaque', 'Comportement irrespectueux', 'Racisme', 'Incitation à la haine'];
        $tabCategory = ['Actualité', 'Films', 'Séries', 'Anime'];
        $tabTag = ['Horreur', 'Adulte', 'Science-Fiction'];


        $date = new \DateTime();
        // $admin = new User();
        // $admin->setEmail('walidazzimani@gmail.com')
        //     ->setPassword($this->encoder->encodePassword($admin, 'sharingan.'))
        //     ->setCreatedAt(new \DateTime('now'))
        //     ->setBirthday($date->setDate(1994, 11, 06))
        //     ->setSexe('homme')
        //     ->setAvatar("https://picsum.photos/200")
        //     ->setPseudo('Admin')
        //     ->setRoles(['ROLE_ADMIN'])
        //     ->setEnable(true);
        // $manager->persist($admin);


        $user = new User();
        $user->setEmail($faker->email())
            ->setPassword($this->encoder->encodePassword($user, 'sharingan.'))
            ->setCreatedAt(new \DateTime('now'))
            ->setBirthday($date->setDate(1994, 11, 06))
            ->setSexe($genre[mt_rand(0, count($genre) - 1)])
            ->setAvatar("https://picsum.photos/200")
            ->setPseudo($faker->userName())
            ->setRoles(['ROLE_USER'])
            ->setEnable(true);
        $manager->persist($user);


        $tags = new Tag();
        $tags->setLibelle($tabTag[mt_rand(0, count($tabTag) - 1)]);
        $manager->persist($tags);

        $category = new Category();
        $category->setLibelle($tabCategory[mt_rand(0, count($tabCategory) - 1)]);
        $manager->persist($category);



        for ($i = 0; $i < 25; $i++) {




            $article = new Article();
            $article->setAuteur($user)
                ->setContent($faker->paragraph())
                ->setCreatedAt(new \DateTime('now'))
                ->setIntroduction($faker->paragraph())
                ->setTitle('Article de ' . $article->getAuteur())
                ->setImage('https://picsum.photos/200')
                ->setSlug($slugify->slugify($article->getTitle()))
                ->addTag($tags)
                ->setCategory($category);
            $manager->persist($article);





            //     $commentaire = new Comment();
            //     $commentaire->setArticle($article)
            //         ->setAuteur($user)
            //         ->setContent($faker->paragraph())
            //         ->setCreatedAt(new \DateTime('now'));
            //     $manager->persist($commentaire);

            //     $tabTag = ['Comique', 'Horreur', 'Documentaire', 'Science-fiction', 'Ecchi', 'Drame', 'Adulte', 'Jeux-Vidéos'];





            // }



            // $manager->flush();
        }
    }
}
