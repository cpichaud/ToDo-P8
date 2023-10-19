<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail("test@example.com");
        $this->assertSame("test@example.com", $user->getEmail());
    }

    public function testRoles()
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testPassword()
    {
        $user = new User();
        $user->setPassword("password123");
        $this->assertSame("password123", $user->getPassword());
    }

    public function testUserIdentifierAndUsername()
    {
        $user = new User();
        $user->setEmail("test@example.com");
        $this->assertSame("test@example.com", $user->getUserIdentifier());
        $this->assertSame("test@example.com", $user->getUsername());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        // This method is expected to be empty for now
        $user->eraseCredentials();
        $this->assertTrue(true); // Simply to show the test passed
    }

    public function testHasRole()
    {
        $user = new User();
        $this->assertFalse($user->hasRole('ROLE_ADMIN'));

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertTrue($user->hasRole('ROLE_ADMIN'));
    }

    public function testTasks()
    {
        $user = new User();
        $this->assertEmpty($user->getTasks());

        $task = new Task();
        $user->getTasks()->add($task);
        $this->assertContains($task, $user->getTasks());
    }
}
