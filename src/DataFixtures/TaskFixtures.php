<?php
namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    // ... le reste de votre code ...

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
    public function load(ObjectManager $manager)
    {
        // Titres et contenus prédéfinis
        $titles = ["Faire les courses", "Nettoyer la maison", "Étudier", "Lire un livre", "Aller courir"];
        $contents = [
            "Acheter des légumes et des fruits.",
            "Nettoyer le salon et la cuisine.",
            "Étudier pour l'examen de demain.",
            "Lire le chapitre 4 du livre.",
            "Courir 5km dans le parc."
        ];

        // Récupérer tous les utilisateurs
        $users = $manager->getRepository(User::class)->findAll();     

        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setCreateAt(new \DateTimeImmutable());
            
            // Choisir un titre et un contenu aléatoirement
            $task->setTitle($titles[array_rand($titles)]);
            $task->setContent($contents[array_rand($contents)]);
            
            $task->setIsDone(rand(0, 1) == 1);
            
            // Attribuer un utilisateur aléatoire à la tâche
            if (!empty($users)) {
                $randomUser = $users[array_rand($users)];
                $task->setUser($randomUser);
            }

            $manager->persist($task);
        }

        $manager->flush();
    }
}

