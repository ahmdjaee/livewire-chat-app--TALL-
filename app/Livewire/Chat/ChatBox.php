<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;
    public $paginateVar = 10;

    #[On('loadMore')]
    public function loadMore(): void
    {
        // increment
        $this->paginateVar += 10;

        // call LoadMesages()
        $this->loadMessages();

        // update chat height
        $this->dispatch('update-chat-height');
    }

    public function getListeners()
    {
        $authId = auth()->user()->id;

        return [
            "echo-private:users.{$authId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatch('scroll-bottom');

                $newMessage = Message::find($event['message_id']);

                // push message
                $this->loadedMessages->push($newMessage);

                // mark message as read
                $newMessage->read_at = now();
                $newMessage->save();

                // broadcast
                $this->selectedConversation->getReceiver()
                    ->notify(new MessageRead(
                        $this->selectedConversation->id
                    ));
            }
        }
    }

    public function loadMessages()
    {
        // Get Count
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($count - $this->paginateVar)
            ->take($this->paginateVar)
            ->get();

        return $this->loadedMessages;
    }
    public function sendMessage()
    {
        $this->validate([
            'body' => 'required|string'
        ]);


        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body
        ]);

        $this->reset('body');

        // Scroll to bottom
        $this->dispatch('scroll-bottom');

        // Push Message
        $this->loadedMessages->push($createdMessage);

        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        // Refresh chat list
        $this->dispatch('refresh');

        // Broadcast
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                auth()->user(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));
    }

    public function mount()
    {
        $this->loadMessages();
        // broadcast
        $this->selectedConversation->getReceiver()
            ->notify(new MessageRead(
                $this->selectedConversation->id
            ));
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
