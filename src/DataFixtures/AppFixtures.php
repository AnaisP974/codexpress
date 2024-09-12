<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Like;
use App\Entity\Network;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{
    private $slug = null;
    private $hash = null;

    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->slug = $slugger;
        $this->hash = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création des Catégories
        $categories = [
            'HTML' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/html5/html5-plain.svg',
            'CSS' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/css3/css3-plain.svg',
            'JavaScript' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/javascript/javascript-plain.svg',
            'PHP' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/php/php-plain.svg',
            'SQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/postgresql/postgresql-plain.svg',
            'JSON' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/json/json-plain.svg',
            'Python' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/python/python-plain.svg',
            'Ruby' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/ruby/ruby-plain.svg',
            'C++' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/cplusplus/cplusplus-plain.svg',
            'Go' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/go/go-wordmark.svg',
            'bash' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/bash/bash-plain.svg',
            'Markdown' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/markdown/markdown-original.svg',
            'Java' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/java/java-original-wordmark.svg',
        ];

        $categoryArray = [];
        foreach($categories as $title => $icon){
            $category = new Category();
            $category->setTitle($title);
            $category->setIcon($icon);
            array_push($categoryArray, $category);
            $manager->persist($category);
        }

       
        //10 Utilisateurs
        for($i = 0 ; $i < 10 ; $i++){
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setUsername($faker->name())
                ->setPassword($this->hash->hashPassword($user, 'admin'))
                ->setRoles(['ROLE_USER'])
                ->setImage($faker->imageUrl(360, 360, 'User', true, 'Picture')) // source : https://fakerphp.org/formatters/image/
            ;
            $manager->persist($user);

            $noteArray = []; // tableau de notes initialisé à vide
            // 10 notes pour chaque utilisateur
            for($j = 0 ; $j < 10 ; $j++){
                $note = new Note();
                $note
                    ->setTitle($faker->sentence())
                    ->setSlug($this->slug->slug($note->getTitle()))
                    ->setContent($faker->paragraph(4, true))
                    ->setPublic($faker->boolean(50))
                    ->setViews($faker->numberBetween(100, 10000))
                    ->setCreator($user)
                    ->setCategory($faker->randomElement($categoryArray))
                ;
                array_push($noteArray, $note); // chaque note créée est ajoutée dans le tableau $noteArray
                $manager->persist($note);
            }

            //Pour chaque utilisateur, je leur crée un nombre aléatoire de "Network" faisant partit de la liste $social 
            $social = ["Facebook", "Twitter", "Linkedin", "Discord", "Whatsapp", "Snapchat", "TikTok", "Website", "Autre"]; // Liste de réseaux social
            $n = $faker->randomDigit(); // nombre aléatoire entre 0 et 9, généré pour la création des liens, source : https://fakerphp.org/formatters/numbers-and-strings/
            for($l = 0 ; $l < $n ; $l++){
                $network = new Network();
                $network
                    ->setName($faker->randomElement($social))
                    ->setUrl($faker->url()) // source: https://fakerphp.org/formatters/internet/
                    ->setCreator($user)
                ;
                $manager->persist($network);
            }

            // Chaque utilisateur va liker 10 notes aléatoires. 
            for ($k = 0; $k < 10 ; $k++) {
                    $like = new Like();
                    $like
                        ->setNote($faker->randomElement($noteArray))
                        ->setCreator($user)
                    ;
                    $manager->persist($like);
            }
        }

        $manager->flush();
    }
}
