<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\UpdateUserPointsRequest;

class UpdateUserPointsRequestTest extends TestCase
{
    use WithFaker;

    public function testValidRequest()
    {
        $request = new UpdateUserPointsRequest();

        $request->merge([
            'points' => 10,
            'operation' => 'add',
        ]);

        $this->assertTrue($request->authorize());
        $this->assertEquals([], $request->validateResolved());
    }

    public function testInvalidRequest()
    {
        $request = new UpdateUserPointsRequest();

        $request->merge([
            'points' => 'invalid',
            'operation' => 'subtract', // Invalid operation
        ]);

        $this->assertTrue($request->authorize());

        $this->expectException(ValidationException::class);
        $request->validateResolved();
    }
}
