<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiHealthController extends Controller
{
    public function index()
    {
        return response()->apiResponse(status: Response::HTTP_OK, message: 'Healthy');
    }
}
