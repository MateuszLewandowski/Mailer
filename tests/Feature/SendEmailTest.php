<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\HeaderBuilder;

class SendEmailTest extends TestCase
{
    use HeaderBuilder;

    private string $initialize_uri = 'api/ms/email/initialize';

    private string $send_email_uri = 'api/ms/email/send';

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
        $captcha = $auth_result['captcha'];
        switch ($captcha['operation']) {
            case 'ADDITION':
                $math = $captcha['x'] + $captcha['y'];
                break;
            case 'SUBTRACTION':
                $math = $captcha['x'] - $captcha['y'];
                break;
            case 'DIVISION':
                $math = $captcha['x'] / $captcha['y'];
                break;
            case 'MULTIPLICATION':
                $math = $captcha['x'] * $captcha['y'];
                break;
        }
        $send_email_result = $this->withHeaders(array_merge(
            $this->getHeaders(), ['uuid' => $auth_result['uuid']]
        ))->post($this->send_email_uri, [
            'captcha' => $math,
            'to' => 'lewyy2501@gmail.com',
            'name' => 'Mateusz Lewandowski',
            'phone' => '111-111-111',
            'email' => 'lewyy2501@gmail.com',
            'text' => 'Lorem ipsum dolores.',
        ]);
        $send_email_result->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('requests', [
            'uuid' => $auth_result['uuid'],
        ]);
        $this->assertDatabaseHas('captcha', [
            'operation_id' => array_flip(self::OPERATIONS)[$auth_result['captcha']['operation']],
            'x' => $auth_result['captcha']['x'],
            'y' => $auth_result['captcha']['y'],
            'result' => $auth_result['captcha']['result'],
        ]);
    }
}
