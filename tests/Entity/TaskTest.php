<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testId()
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    public function testCreateAt()
    {
        $task = new Task();
        $date = new \DateTime('2023-01-01');
        $task->setCreateAt($date);
        $this->assertEquals($date, $task->getCreateAt());
    }

    public function testTitle()
    {
        $task = new Task();
        $task->setTitle("Test Title");
        $this->assertSame("Test Title", $task->getTitle());
    }

    public function testContent()
    {
        $task = new Task();
        $task->setContent("Test Content");
        $this->assertSame("Test Content", $task->getContent());
    }

    public function testIsDone()
    {
        $task = new Task();
        $this->assertFalse($task->isDone());

        $task->setIsDone(true);
        $this->assertTrue($task->isDone());

        $task->toggle(false);
        $this->assertFalse($task->isDone());
    }

    public function testUser()
    {
        $task = new Task();
        $user = new User();

        $task->setUser($user);
        $this->assertSame($user, $task->getUser());
    }
}
