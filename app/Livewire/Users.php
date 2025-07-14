<?php

namespace App\Livewire;

use Livewire\Component;

class Users extends Component
{
    public function message($userId)
    {
        $authenticatedUserId = auth()->user()->id;
    
        // Check if conversation exist 
        $existingConversation = \App\Models\Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
            $query->where('sender_id', $authenticatedUserId)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($authenticatedUserId, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $authenticatedUserId);
        })->first();

        if ($existingConversation) {
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }
        
        // Otherwise, create a new conversation
        $createConversation = \App\Models\Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId
        ]);

        return redirect()->route('chat', ['query' => $createConversation->id]);
    }

    public function render()
    {
        return view('livewire.users', ['users' => \App\Models\User::where('id', '!=', auth()->user()->id)->get()]);
    }
}
