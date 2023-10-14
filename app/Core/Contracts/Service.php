<?php

namespace Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

interface Service extends Identifiable, Configurable, PerformsCrudOperation
{
    /**
     * Create an instance of an api resource.
     *
     * @param Model $model
     * @param string|null $type
     * @return JsonResource
     */
    public function makeApiResource(Model $model, ?string $type = null): JsonResource;
}
