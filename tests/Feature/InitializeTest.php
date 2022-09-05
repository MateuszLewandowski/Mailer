<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\HeaderBuilder;

class InitializeTest extends TestCase
{
    use HeaderBuilder, RefreshDatabase;

    private string $uri = 'api/ms/email/initialize';

    private const OPERATIONS = [
        1 => 'ADDITION',
        2 => 'SUBTRACTION',
        3 => 'DIVISION',
        4 => 'MULTIPLICATION',
    ];

    public function test_expects_success()
    {
        $response = $this->withHeaders($this->getHeaders())->get($this->uri);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.uuid', fn ($uuid) => Str::isUuid($uuid));
        $response->assertJsonPath('data.captcha.operation', fn ($operation) => in_array($operation, self::OPERATIONS));

        $response->assertJsonPath('data.captcha.x', fn ($x) => (is_int($x) and $x > 0));
        $response->assertJsonPath('data.captcha.y', fn ($y) => (is_int($y) and $y > 0));
        $response->assertJsonPath('data.captcha.result', fn ($result) => (is_int($result) and $result > 0));

        $captcha = $response->decodeResponseJson()['data']['captcha'];

        switch ($captcha) {
            case 'ADDITION':
                $this->assertTrue($captcha['x'] + $captcha['y'] === $captcha['result']);
                break;
            case 'SUBTRACTION':
                $this->assertTrue($captcha['x'] - $captcha['y'] === $captcha['result']);
                break;
            case 'DIVISION':
                $this->assertTrue($captcha['x'] / $captcha['y'] === $captcha['result']);
                break;
            case 'MULTIPLICATION':
                $this->assertTrue($captcha['x'] * $captcha['y'] === $captcha['result']);
                break;
        }
    }
}
