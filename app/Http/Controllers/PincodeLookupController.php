<?php

namespace App\Http\Controllers;

use App\Support\PincodeResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PincodeLookupController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pincode' => ['required', 'string', 'max:12'],
        ]);

        $resolved = PincodeResolver::resolve($data['pincode']);
        if (! $resolved) {
            return response()->json([
                'message' => 'Location details not found for the provided pincode.',
            ], 404);
        }

        return response()->json($resolved);
    }
}