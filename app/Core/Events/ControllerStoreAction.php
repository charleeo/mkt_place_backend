<?php

namespace Core\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

class ControllerStoreAction
{

    use SerializesModels, Dispatchable, InteractsWithSockets;

    /**
     * The controller action
     * 
     * @var string $action
     */
    public $action;

    /**
     * The controller model
     * 
     * @var Model $model
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $action, Model $model)
    {
        $this->action = $action;
        $this->model = $model;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("channel-name");
    }
}
