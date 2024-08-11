<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Services\UserService;

class UserServiceTest extends TestCase
{
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function testAddPoints()
    {
        $user = User::factory()->create(['points' => 10]);
        $updatedUser = $this->userService->addPoints($user, 5);
        $this->assertEquals(15, $updatedUser->points);
    }

    public function testRemovePoints()
    {
        $user = User::factory()->create(['points' => 10]);
        $updatedUser = $this->userService->removePoints($user, 5);
        $this->assertEquals(5, $updatedUser->points);
    }

    public function testRemovePointsDoesNotGoNegative()
    {
        $user = User::factory()->create(['points' => 10]);
        $updatedUser = $this->userService->removePoints($user, 15);
        $this->assertEquals(0, $updatedUser->points);
    }

    public function testGetUsersGroupedByScore()
    {
        User::factory()->create(['points' => 10, 'age' => 20, 'name' => 'User1']);
        User::factory()->create(['points' => 10, 'age' => 30, 'name' => 'User2']);
        User::factory()->create(['points' => 5, 'age' => 25, 'name' => 'User3']);

        $result = $this->userService->getUsersGroupedByScore();

        $this->assertEquals(['User1', 'User2'], $result[10]['names']);
        $this->assertEquals(25, $result[10]['average_age']);
        $this->assertEquals(['User3'], $result[5]['names']);
        $this->assertEquals(25, $result[5]['average_age']);
    }
}
