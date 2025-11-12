<?php

namespace Illuminate\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Fruitcake\Cors\CorsService;

class HandleCors
{
    protected $cors;

    public function __construct(CorsService $cors)
    {
        $this->cors = $cors;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // tangani preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return $this->cors->handle($request, $next);
        }

        $response = $next($request);

        // tambahkan header CORS ke response
        return $this->cors->addActualRequestHeaders($response, $request);
    }
}
