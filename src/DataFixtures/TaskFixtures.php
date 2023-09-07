<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $task = new Task();
        $task->setCreateAt(new \DateTimeImmutable());
        $task->setTitle('Task');
        $task->setContent('This is an example task content.');
        $task->setIsDone(false);

        $manager->persist($task);
        $manager->flush();
    }
}
