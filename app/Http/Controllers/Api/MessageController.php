<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $validatedData = $request->validated();

        // Create a new message using the validated data
        $message = Message::create($validatedData);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message,
        ], 201);
    }
}
