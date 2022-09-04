<?php

namespace Tests\Feature;

use App\Mail\ContactFormEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\HeaderBuilder;

class SendEmailTest extends TestCase
{
    use HeaderBuilder;

    private string $initialize_uri = 'api/ms/email/initialize';
    private string $send_email_uri = 'api/ms/email/send';

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
    }
}
