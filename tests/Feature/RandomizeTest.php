<?php

namespace Tests\Feature;

use App\Helpers\Randomize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RandomizeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $randomize = Randomize::quickRandom(10, true);
        $this->assertEquals(10, strlen($randomize));
    }
}
