<?php

namespace Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface PerformsCrudOperation
{
    /**
     * Display a listing the resources
     *
     * @param array $requestAttributes
     * @return LengthAwarePaginator
     */
    public function index($requestAttributes);

    /**
     * Show details of the specified resource.
     *
     * @param int $id
     * @param array $requestAttributes
     * @return mixed
     */
    public function show($id, $requestAttributes);

    /**
     * Get a list of all records.
     *
     * @param array $requestAttributes
     * @param array|string $fields
     * @return Collection
     */
    public function all($requestAttributes, $fields = '*'): Collection;

    /**
     * Create a new resource in storage.
     *
     * @param array $attributes
     * @return Model
     */
    public function store($attributes);

    /**
     * Create new, update existing and delete missing records
     * in the specified $attributes.
     *
     * @param array $attributes
     * @param array $constraints
     * @param string $key
     * @return void
     */
    public function sync($attributes, $constraints, $key = 'id');

    /**
     * Update the specified resource.
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, $attributes);

    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @param array $requestAttributes
     * @return void
     */
    public function destroy($id, $requestAttributes = []);
}
