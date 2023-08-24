<?php

namespace DTApi\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use DTApi\Models\User;
use DTApi\Repository\UserRepository;
class UserRepositoryTest
{
    // DatabaseTransactions trait helps to automatically rollback database changes after each test,
    // ensuring a clean state for each test method.
    use DatabaseTransactions;

    protected $userRepository;

    public function setUp(){
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
    }

    public function testCreateOrUpdateUser(){

        $fakeRequestData = [
            'role' => 'customer',
            'name' => 'Hamaad Kaleem'
        ];

        //call createOrUpdate method of UserRepository class
        $user = $this->userRepository->createOrUpdate(null, $fakeRequestData);

        // Assert that if a user was created or updated
        $this->assertInstanceOf(User::class, $user);
    }

    public function testEnable(){
        // create a fake user
        $user = factory(User::class)->create();

        // call the enable method of UserRepository class to enable user
        $this->userRepository->enable($user->id);

        // find the user that is enabled by
        $enabledUser = User::findOrFail($user->id);

        // Assert that user status was changed from '0' to '1'
        $this->assertEquals('1', $enabledUser->status);
    }

    public function testDisable(){
        // create a fake user
        $user = factory(User::class)->create();

        // call the disable method of UserRepository class to disable user
        $this->userRepository->disable($user->id);

        // find the user that is enabled by
        $enabledUser = User::findOrFail($user->id);

        // Assert that user status was changed from '1' to '0'
        $this->assertEquals('0', $enabledUser->status);
    }

    public function testGetTranslators(){
        // create fake users with user_type id
        factory(User::class, 3)->create(['user_type' => 3]);
        factory(User::class, 2)->create(['user_type' => 1]);
        factory(User::class, 5)->create(['user_type' => 2]);

        // get translators i.e. user_type => 2
        // call the getTranslators method of UserRepository class to get users with translator type
        $translators = $this->userRepository->getTranslators();

        // Assert that translators are 5
        $this->assertCount(5, $translators->count());

        // Assert that each return user is a translator
        foreach ($translators as $translator) {
            $this->assertEquals(2, $translator->user_type);
        }

    }

}