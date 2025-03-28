<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get unique conversations
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first();
            });

        return response()->json($conversations->load(['sender', 'receiver', 'product']));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $authUser = auth()->user();
        
        $messages = Message::where(function ($query) use ($authUser, $user) {
                $query->where('sender_id', $authUser->id)
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($authUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages->load(['sender', 'receiver', 'product']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
            'product_id' => 'required|exists:products,id'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'product_id' => $request->product_id,
            'message' => $request->message,
        ]);

        return response()->json($message->load(['sender', 'receiver', 'product']), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);
        
        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }
}
