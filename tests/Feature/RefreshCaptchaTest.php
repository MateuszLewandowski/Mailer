<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\HeaderBuilder;

class RefreshCaptchaTest extends TestCase
{
    use HeaderBuilder, RefreshDatabase;

    private string $initialize_uri = 'api/ms/email/initialize';

    private string $refresh_captcha_uri = 'api/ms/captcha/refresh';

    private const OPERATIONS = [
        1 => 'ADDITION',
        2 => 'SUBTRACTION',
        3 => 'DIVISION',
        4 => 'MULTIPLICATION',
    ];

    public function test_expects_success()
    {
        $auth_result = $this->withHeaders($this->getHeaders())->get($this->initialize_uri);
        $auth_result = $auth_result->decodeResponseJson()['data'];

        $first_result = $this->withHeaders(array_merge(
            $this->getHeaders(), ['uuid' => $auth_result['uuid']]
        ))->get($this->refresh_captcha_uri);

        $first_result->assertStatus(Response::HTTP_RESET_CONTENT);
        $first_result->assertJsonPath('data.captcha.operation', fn ($operation) => in_array($operation, self::OPERATIONS));
        $first_result->assertJsonPath('data.captcha.x', fn ($x) => (is_int($x) and $x > 0));
        $first_result->assertJsonPath('data.captcha.y', fn ($y) => (is_int($y) and $y > 0));
        $first_result->assertJsonPath('data.captcha.result', fn ($result) => (is_int($result) and $result > 0));

        $second_result = $this->withHeaders(array_merge(
            $this->getHeaders(), ['uuid' => $auth_result['uuid']]
        ))->get($this->refresh_captcha_uri);

        $first_result = $first_result->decodeResponseJson()['data']['captcha'];
        $second_result = $second_result->decodeResponseJson()['data']['captcha'];

        $this->assertFalse(
            $first_result['operation'] === $second_result['operation'] and
            $first_result['x'] === $second_result['x'] and
            $first_result['y'] === $second_result['y'] and
            $first_result['result'] === $second_result['result']
        );
    }
}
