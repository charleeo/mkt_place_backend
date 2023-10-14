<?php

namespace Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait PerformsCrudOperation
{
    /**
     * Display a listing the resources
     *
     * @param array $requestAttributes
     * @return LengthAwarePaginator
     */
    public function index($requestAttributes)
    {
        return $this->repository
            ->applyScope('index')
            ->search(@$requestAttributes['search'])
            ->filter($requestAttributes)
            ->latest()
            ->paginate(@$requestAttributes['limit']);
    }

    /**
     * Show details of the specified resource.
     *
     * @param int $id
     * @param array $requestAttributes
     * @return mixed
     */
    public function show($id, $requestAttributes)
    {
        return $this->repository
            ->applyScope('show')
            ->find($id);
    }

    /**
     * Get a list of all records.
     *
     * @param array $requestAttributes
     * @param array|string $fields
     * @return Collection
     */
    public function all($requestAttributes, $fields = '*'): Collection
    {
        return $this->repository
            ->applyScope('all')
            ->search(@$requestAttributes['search'])
            ->filter($requestAttributes)
            ->all($fields);
    }

    /**
     * Create a new resource in storage.
     *
     * @param array $attributes
     * @return Model
     */
    public function store($attributes)
    {
        return $this->repository
            ->create($attributes);
    }

    /**
     * Create new, update existing and delete missing records
     * in the specified $attributes.
     *
     * @param array $attributes
     * @param array $constraints
     * @param string $key
     * @return void
     */
    public function sync($attributes, $constraints, $key = 'id')
    {
        $ids = collect($attributes)->pluck($key)->toArray();

        $recordsToBeDeleted = $this->repository
            ->getInverse($ids, $constraints);

        // Delete missing records.
        foreach ($recordsToBeDeleted as $model) {
            $this->destroy($model->id);
        }

        // Upate and create existing and new records.
        foreach ($attributes as $attribute) {
            if ($this->repository->find(@$attribute[$key])) {
                $this->update($attribute[$key], $attribute);
            } else {
                $this->store(array_merge($attribute, $constraints));
            }
        }
    }

    /**
     * Update the specified resource.
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, $attributes)
    {
        return $this->repository
            ->update($id, $attributes);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @param array $requestAttributes
     * @return void
     */
    public function destroy($id, $requestAttributes = [])
    {
        return $this->repository
            ->delete($id, $requestAttributes);
    }
}
