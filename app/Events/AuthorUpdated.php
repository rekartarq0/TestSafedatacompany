<?php
// app/Events/AuthorUpdated.php

namespace App\Events;

use App\Models\Authors;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AuthorUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $author;

    public function __construct(Authors $author)
    {
        $this->author = $author->load('user');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('authors');
    }

    public function broadcastAs(): string
    {
        return 'author.updated';
    }
}
