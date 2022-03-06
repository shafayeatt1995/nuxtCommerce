<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppController extends Controller
{
	public function app()
	{
		$appName = env('APP_NAME');
		$activeCurrency = Currency::where('default', true)->first();
		return response()->json(compact('appName', 'activeCurrency'));
	}
}
