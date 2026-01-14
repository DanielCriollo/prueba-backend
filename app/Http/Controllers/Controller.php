<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Routing\Controller as BaseController;

#[OA\Info(
    version: "1.0.0",
    title: "Product API Documentation",
    description: "Documentation for Product Management API"
)]
#[OA\Server(
    url: "http://prueba-backend.test:8080",
    description: "Local Development Server"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Artisan Serve"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
abstract class Controller extends BaseController
{
    //
}
