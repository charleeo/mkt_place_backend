<?php

namespace Core\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    /**
     * @var string
     */
    public $message;
    /**
     * Create a new resource instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct([]);
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @inheritDoc
     */
    public function with($request)
    {
        return [
            'message' => $this->message,
        ];
    }
}
