<?php



namespace Core\Contracts;



use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;



interface Repository

{

    /**

     * Apply a given eloquent local scope on the query.

     *

     * @param string $scope

     * @param mixed ...$arg

     * @return $this

     */

    public function applyScope($scope, ...$arg);



    /**

     * Filter the target query by the specified attributes.

     *

     * @param array $attributes

     * @return $this

     */

    public function filter(array $attributes = []);



    /**

     * Apply the search scope on the query.

     *

     * @param string|null $search

     * @return $this

     */

    public function search($search = null);



    /**

     * Force assign the given attributes to the given model.

     *

     * @param Model $model

     * @param array $attributes

     * @return $this

     */

    public function forceFill($model, $attributes);



    /**

     * Make a model using the given array of data as its attributes.

     *

     * @param array $data

     * @return Model

     */

    public function wrapWithModel(array $data);



    /**

     * Create a new resource in storage.

     *

     * @param array $attributes

     * @return Model

     */

    public function create(array $attributes);



    /**

     * Update the specified resource in storage.

     *

     * If $id is null, the $model attribute of the repository

     * will be updated.

     *

     * @param int $id

     * @param array $attributes

     * @return Model

     */

    public function update($id, array $attributes);



    /**

     * Forcefully Update the specified resource in storage.

     *

     * @param int $id

     * @param array $attributes

     * @return Model

     */

    public function forceUpdate($id, $attributes);



    /**

     * Apply a where constraint on the query.

     *

     * @param mixed ...$args

     * @return $this

     */

    public function where(...$args);



    /**

     * Determine if the specified child belongs to the given parent.

     *

     * @param Model $parent

     * @param int $childId

     * @param string $relation

     * @return boolean

     */

    public function has(Model $parent, $childId, $relation);



    /**

     * Update the matched resource in storage.

     *

     * @param array $filter

     * @param array $attributes

     * @return Model

     */

    public function updateWhere(array $filter, array $attributes);



    /**

     * Update the specified resource of create

     * new one if it does not exist.

     *

     * @param array $attributes

     * @param array $values

     * @return Model|bool|null

     */

    public function updateOrCreate(array $attributes, array $values);



    /**

     * Delete the specified resource from storage.

     *

     * If $id is null, the $model attribute of the repository

     * will be deleted.

     *

     * @param int|null $id

     * @param array $attributes

     * @return int

     */

    public function delete($id = null, $attributes = []);



    /**

     * Attach one model to another.

     *

     * @param Model $model

     * @param int $relationId

     * @param string $relation

     * @param array $data

     * @return void

     */

    public function attach($model, $relationId, $relation, $data = []);



    /**

     * Detach one model from another.

     *

     * @param Model $model

     * @param int $relationId

     * @param string $relation

     * @return void

     */

    public function detach($model, $relationId, $relation);



    /**

     * Delete all records that match the specified condition(s).

     *

     * @param array $conditions

     * @return int

     */

    public function deleteWhere($conditions);



    /**

     * Retrieve the specified resource from storage.

     *

     * @param int $id Resource identifier.

     * @return Model|null.

     */

    public function find($id);



    /**

     * Retrieve the specified resource or throw an exception if it's not found.

     *

     * @param int $id

     * @return Model.

     * @throws Exception

     */

    public function findOrFail($id);



    /**

     * Find all record with any of the given ids

     *

     * @param array $ids

     * @return Collection

     */

    public function findMany($ids): Collection;



    /**

     * Retrieve the first record matching the given criteria.

     *

     * @param mixed $args Array with array key as query field

     * and array value as search value. Or two arguments, first

     * is query field and second is search value value.

     * @return Model

     */

    public function firstWhere(...$args);



    /**

     * Show a paginated listing of all specified resources.

     *

     * @param int|null $limit

     * @return LengthAwarePaginator.

     */

    public function paginate($limit = null): LengthAwarePaginator;



    /**

     * Sort by latest to oldest.

     *

     * @return $this

     */

    public function latest();



    /**

     * Show a list of all requested records.

     *

     * @param array $fields.

     * @return Collection

     */

    public function all($fields = '*'): Collection;



    /**

     * Run a query in chunks.

     *

     * @param int $count

     * @param callable $callback

     * @return bool

     */

    public function chunk($count, callable $callback);



    /**

     * Pluck the specified table field from the database.

     *

     * @param string $field

     * @param string|null $key

     * @return Collection

     */

    public function pluck($field, $key = null): Collection;



    /**

     * Count all matched records.

     *

     * @return int

     */

    public function count(): int;



    /**

     * Get the first database record matching the query.

     *

     * @return Model

     */

    public function first();



    /**

     * Set the default model for the repository.

     *

     * @param Model $model

     * @return $this

     */

    public function setModel($model): Repository;



    /**

     * Convert the given model to an array.

     *

     * @param Model $model

     * @return array

     */

    public function toArray($model): array;



    /**

     * Transform the model to an object.

     *

     * @param mixed $model

     * @return object|null

     */

    public function toObject($model);



    /**

     * Associate two given models with each other.

     *

     * @param Model $child

     * @param Model $parent

     * @param string|null $relation

     * @return Repository

     */

    public function associate($child, $parent, $relation = null);



    /**

     * Create another model associated with the given model.

     *

     * @param Model $model

     * @param string $relation

     * @param array $data

     * @return Model

     */

    public function createRelation($model, $relation, $data = []);



    /**

     * Get a paginated list of the specified of the given model.

     *

     * @param Model $model

     * @param string $relation

     * @param array $filters

     * @param integer|null $limit

     * @return LengthAwarePaginator

     */

    public function paginateRelation(

        Model $model,

        string $relation,

        array $filters = [],

        ?int $limit = null

    ): LengthAwarePaginator;



    /**

     * Delete the specified related model.

     *

     * @param Model $model

     * @param string $relation

     * @return void

     */

    public function deleteRelation($model, $relation);



    /**

     * Get all records that are not in the specified ids.

     *

     * @param array $ids

     * @param array $constraints

     * @return Collection

     */

    public function getInverse($ids, $constraints);



    /**

     * Delete all records of this repositories model

     * whose ids are not contained in the given ids

     *

     * @param array $ids

     * @param array $constraints

     * @return void

     */

    public function deleteInverse($ids, $constraints);



    /**

     * Update matched items, create new items and delete

     * missing items from this repository's models.

     *

     * @param array $attributes

     * @param array $additionalAttributes

     * @param string $idKey

     * @return void

     */

    public function syncUpdate(

        array $attributes,

        array $additionalAttributes = [],

        $idKey = 'id',

        $idField = 'id'

    );



    /**

     * Determine if a model is the owner of another.

     *

     * @param Model $parent

     * @param Model $child

     * @return bool

     */

    public function owns($parent, $child): bool;



    /**

     * Load the specified model relations.

     *

     * @param array|...args $relations

     * @return Builder

     */

    public function with(...$relations);



    /**

     * Get the morph alias of the given model.

     *

     * @param Model|string $model

     * @return string

     */

    public static function getMorphClass($model);



    /**

     * Get new instance of the repository's model.

     *

     * @param array $data

     * @return Model

     */

    public function newModel($data = []);
}
