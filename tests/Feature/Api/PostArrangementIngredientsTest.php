<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostArrangementIngredientsTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $arrangement;
    protected $request;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create('App\User');
        $this->account = $this->user->account;

        $this->arrangement = create('App\Arrangement', ['account_id' => $this->account->id]);

        $item = create('App\Item', [
            'account_id' => $this->account->id,
            'use_default_markup' => false,
        ]);
        $flowerVariety = create('App\FlowerVariety', [
        	'account_id' => $this->account->id,
        	'flower_id' => create('App\Flower', ['account_id' => $this->account->id])->id,
            'use_default_markup' => false,
        ]);
        $this->request = [
    		[
        		'id' => $item->id,
        		'type' => 'item',
        		'quantity' => 5,
        	],
        	[
        		'id' => $flowerVariety->id,
        		'type' => 'flowervariety',
        		'quantity' => 10,
        	],
        ];
    }

    protected function getUrl($id)
    {
    	return 'api/arrangements/' . $id . '/ingredients';
    }

    /** @test */
    public function a_user_can_add_ingredients_to_an_arrangement()
    {
        $this->assertEquals($this->arrangement->ingredients()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->getUrl($this->arrangement->id), $this->request)
    		->assertStatus(200);

        $this->assertEquals($this->arrangement->ingredients()->count(), 2);
    }

    /** @test */
    public function users_can_add_multiple_entries_of_the_same_item()
    {
        $item = create('App\Item', ['account_id' => $this->account->id]);

        $request = [
            [
                'id' => $item->id,
                'type' => $item->arrangeableType,
                'quantity' => 10,
            ],
            [
                'id' => $item->id,
                'type' => $item->arrangeableType,
                'quantity' => 7,
            ],
        ];

        $this->assertEquals($this->arrangement->ingredients()->count(), 0);

        $this->signIn($this->user)
            ->postJson($this->getUrl($this->arrangement->id), $request)
            ->assertStatus(200);

        $this->assertEquals($this->arrangement->ingredients()->count(), 2);
    }

    /** @test */
    public function users_can_only_add_ingredients_to_arrangements_in_their_account()
    {
    	$someOtherArrangement = create('App\Arrangement');

    	$this->signIn($this->user)
            ->postJson($this->getUrl($someOtherArrangement->id), $this->request)
    		->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_arrangeable_ingredients_to_arrangements()
    {
    	$badRequest = [
    		[
    			'id' => create('App\User')->id,
        		'type' => 'user',
        		'quantity' => 5,
    		]
    	];

    	$this->signIn($this->user)
            ->postJson($this->getUrl($this->arrangement->id), $badRequest)
    		->assertStatus(422);
    }

    /** @test */
    public function users_can_only_add_ingredients_from_their_account_to_arrangements()
    {
    	$badRequest = [
    		[
    			'id' => create('App\Item')->id,
    			'type' => 'item',
    			'quantity' => 5,
    		]
    	];

    	$this->signIn($this->user)
            ->postJson($this->getUrl($this->arrangement->id), $badRequest)
    		->assertStatus(403);
    }

    /** @test */
    public function users_can_only_add_existing_ingredients_to_arrangements()
    {
        $clearlyInvalidId = 123;
    	$badRequest = [
    		[
    			'id' => $clearlyInvalidId,
    			'type' => 'item',
    			'quantity' => 5,
    		]
    	];

    	$this->signIn($this->user)
            ->postJson($this->getUrl($this->arrangement->id), $badRequest)
    		->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_cannot_add_ingredients_to_arrangements()
    {
    	$this->postJson($this->getUrl($this->arrangement->id), $this->request)
    		->assertStatus(401);
    }
}
