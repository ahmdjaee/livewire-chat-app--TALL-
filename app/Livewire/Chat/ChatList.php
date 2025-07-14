<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $query;


    public function deleteByUser(string $id)
    {
        $userId = auth()->user()->id;
        $conversation = Conversation::find(decrypt($id));

        $conversation->messages()->each(function($message) use ($userId) {
            if ($message->sender_id == $userId) {
                $message->update(['sender_deleted_at' => now()]);
            }else if ($message->receiver_id == $userId) {
                $message->update(['receiver_deleted_at' => now()]);
            }
        });

        $receiverAlsoDeleted = $conversation->messages()
        ->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        })->where(function ($query) use ($userId) {
            $query->whereNull('sender_deleted_at')
                ->orWhereNull('receiver_deleted_at');
        })->doesntExist();

        if ($receiverAlsoDeleted) {
            $conversation->forceDelete();
        }

        return redirect(route('chat.index'));
    }




    #[On('refresh')]
    public function render()
    {
        $user = auth()->user();

        return view('livewire.chat.chat-list', [
            'conversations' => $user->conversations()->latest('updated_at')->get()
        ]);
    }

}
