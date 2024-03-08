<?php

namespace App\Http\Controllers;

use App\Services\DenominationService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private DenominationService $denominationService;

    public function __construct(DenominationService $denominationService)
    {
        $this->denominationService = $denominationService;
    }

    public function findCombinations(Request $request): Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|gt:0'
        ]);

        if($validator->fails()) {
            return response(['error' => $validator->errors()->first()], 422);
        }

        $combinations = $this->denominationService->getCombinations($request->input('amount'));

        return response()->json(['data' => $combinations]);
    }
}
