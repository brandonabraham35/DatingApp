<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\UserMatch;
use App\Events\MessageSent;
use App\Http\Resources\MessageResource;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function index(Request $request, $userId)
    {
        $user = $request->user();

        // Optional: Ensure they are matched before allowing to view messages
        $isMatched = UserMatch::where(function($q) use ($user, $userId) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $userId);
        })->orWhere(function($q) use ($user, $userId) {
            $q->where('user_one_id', $userId)->where('user_two_id', $user->id);
        })->exists();

        if (!$isMatched) {
            return response()->json(['message' => 'You are not matched with this user.'], 403);
        }

        $direction = $request->query('order') === 'desc' ? 'desc' : 'asc';

        $messages = Message::where(function ($q) use ($user, $userId) {
            $q->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($user, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->orderBy('created_at', $direction)->paginate(50)->withQueryString();

        return MessageResource::collection($messages);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user = $request->user();
        $receiverId = $validated['receiver_id'];

        if ($user->id == $receiverId) {
            throw ValidationException::withMessages([
                'receiver_id' => ['You cannot message yourself.'],
            ]);
        }

        // Optional: Ensure they are matched before allowing to send messages
        $isMatched = UserMatch::where(function($q) use ($user, $receiverId) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $receiverId);
        })->orWhere(function($q) use ($user, $receiverId) {
            $q->where('user_one_id', $receiverId)->where('user_two_id', $user->id);
        })->exists();

        if (!$isMatched) {
            return response()->json(['message' => 'You are not matched with this user.'], 403);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return new MessageResource($message);
    }

    public function markAsRead(Request $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->receiver_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $message->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }
}
