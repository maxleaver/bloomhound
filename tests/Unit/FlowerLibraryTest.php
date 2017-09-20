<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowerLibraryTest extends TestCase
{
    use RefreshDatabase;

    protected $library;

	protected function setUp()
    {
    	parent::setUp();

        $this->library = create('App\FlowerLibrary');
        create('App\Flower', ['flower_library_id' => $this->library->id], 10);
    }

    /** @test */
    public function a_flower_library_has_a_type()
    {
        $this->assertNotNull($this->library->type);
    }

    /** @test */
    public function a_flower_library_has_a_name()
    {
        $this->assertNotNull($this->library->name);
    }

    /** @test */
    public function a_flower_library_has_a_description()
    {
        $this->assertNotNull($this->library->description);
    }

    /** @test */
    public function a_flower_library_has_flowers()
    {
        $this->assertInstanceOf('App\Flower', $this->library->flowers->first());
    }
}
