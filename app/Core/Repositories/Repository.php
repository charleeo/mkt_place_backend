<?php

namespace Core\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\LazyCollection;
use Core\Contracts\Repository as RepositoryContract;

abstract class Repository implements RepositoryContract
{
    /**
     * The query builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * The default model for the repository.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create new instance of the repository.
     *
     * @param Model $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
        $this->setBuilder($this->newBuilder());
    }

    /**
     * Apply a given eloquent local scope on the query.
     *
     * @param string $scope
     * @param mixed $arg
     * @return $this
     */
    public function applyScope($scope, ...$arg)
    {
        $this->builder->{$scope}(...$arg);

        return $this;
    }

    /**
     * Filter the target query by the specified attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function filter(array $attributes = [])
    {
        $this->builder->filter($attributes);

        return $this;
    }

    /**
     * Apply the search scope on the query.
     *
     * @param string|null $search
     * @return $this
     */
    public function search($search = null)
    {
        $this->builder->search($search);

        return $this;
    }

    /**
     * Force assign the given attributes to the given model.
     *
     * @param Model $model
     * @param array $attributes
     * @return $this
     */
    public function forceFill($model, $attributes)
    {
        $model->forceFill($attributes);

        return $this;
    }

    /**
     * Create a new resource in storage.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        $model = $this->newBuilder()->create($attributes);

        return $this->toObject($model);
    }

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
    public function update($id, array $attributes)
    {
        $model = $this->getModelById($id);

        $model->fill($attributes)->save();

        return $this->toObject($model->refresh());
    }

    /**
     * Forcefully Update the specified resource in storage.
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function forceUpdate($id, $attributes)
    {
        $model = $this->getModelById($id);

        $model->forceFill($attributes)->save();

        return $this->toObject($model->refresh());
    }

    /**
     * Apply a where constraint on the query.
     *
     * @param mixed ...$args
     * @return $this
     */
    public function where(...$args)
    {
        $this->builder->where(...$args);

        return $this;
    }

    /**
     * Determine if the specified child belongs to the given parent.
     *
     * @param Model $parent
     * @param int $childId
     * @param string $relation
     * @return boolean
     */
    public function has(Model $parent, $childId, $relation)
    {
        return !!$this->newBuilder()->whereHas($relation, function (Builder $builder) use ($childId) {
            $builder->whereKey($childId);
        })->find($parent->getKey());
    }

    /**
     * Update the matched resource in storage.
     *
     * @param array $filter
     * @param array $attributes
     * @return int
     */
    public function updateWhere(array $filter, array $attributes)
    {
        return $this->newBuilder()
            ->filter($filter)
            ->update($attributes);
    }

    /**
     * Delete all records that match the specified condition(s).
     *
     * @param array $conditions
     * @return int
     */
    public function deleteWhere($conditions)
    {
        return $this->newBuilder()
            ->filter($conditions)
            ->delete();
    }

    /**
     * Retrieve the first record matching the given criteria.
     *
     * @param mixed $args Array with array key as query field
     * and array value as search value. Or two arguments, first
     * is query field and second is search value value.
     * @return Model
     */
    public function firstWhere(...$args)
    {
        if (is_array($args[0])) {
            return $this->toObject(
                $this->newBuilder()->where($args[0])->first()
            );
        }

        return $this->toObject(
            $this->newBuilder()->where($args[0], $args[1])->first()
        );
    }

    /**
     * Update the specified resource of create
     * new one if it does not exist.
     *
     * @param array $attributes
     * @param array $values
     * @return Model|bool|null
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->toObject(
            $this->newBuilder()->updateOrCreate($attributes, $values)
        );
    }

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
    public function delete($id = null, $attributes = [])
    {
        $model = $this->getModelById($id);

        return optional($model)->delete();
    }

    /**
     * Attach one model to another.
     *
     * @param Model $model
     * @param int $relationId
     * @param string $relation
     * @param array $data
     * @return void
     */
    public function attach($model, $relationId, $relation, $data = [])
    {
        $model->$relation()->attach($relationId, $data);
    }

    /**
     * Detach one model from another.
     *
     * @param Model $model
     * @param int $relationId
     * @param string $relation
     * @return void
     */
    public function detach($model, $relationId, $relation)
    {
        $model->$relation()->detach($relationId);
    }

    /**
     * Retrieve the specified resource from storage.
     *
     * @param int $id Resource identifier.
     * @return Model|null.
     */
    public function find($id)
    {
        return $this->toObject($this->builder->find($id));
    }

    /**
     * Retrieve the specified resource or throw an exception if it's not found.
     *
     * @param int $id
     * @return Model.
     * @throws Exception
     */
    public function findOrFail($id)
    {
        return $this->toObject($this->builder->findOrFail($id));
    }

    /**
     * Find all record with any of the given ids
     *
     * @param array $ids
     * @return Collection
     */
    public function findMany($ids): Collection
    {
        return $this->builder->findMany($ids);
    }

    /**
     * Show a paginated listing of all specified resources.
     *
     * @param int|null $limit
     * @return LengthAwarePaginator.
     */
    public function paginate($limit = null): LengthAwarePaginator
    {
        return $this->builder->paginate($limit);
    }

    /**
     * Sort by latest to oldest.
     *
     * @return $this
     */
    public function latest()
    {
        $this->setBuilder($this->builder->orderBy(
            $this->model->getTable() . '.created_at',
            'desc'
        ));

        return $this;
    }

    /**
     * Show a list of all requested records.
     *
     * @param array $fields.
     * @return Collection
     */
    public function all($fields = '*'): Collection
    {
        return $this->builder
            ->select($fields)
            ->get();
    }

    /**
     * Run a query in chunks.
     *
     * @param int $count
     * @param callable $callback
     * @return bool
     */
    public function chunk($count, callable $callback)
    {
        return $this->newBuilder()->chunk($count, $callback);
    }

    /**
     * Get a lazy collection for the query.
     *
     * @return LazyCollection
     */
    public function cursor(): LazyCollection
    {
        return $this->newBuilder()->cursor();
    }

    /**
     * Pluck the specified table field from the database.
     *
     * @param string $field
     * @param string|null $key
     * @return Collection
     */
    public function pluck($field, $key = null): Collection
    {
        return $this->newBuilder()->pluck($field, $key);
    }

    /**
     * Count all matched records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->newBuilder()
            ->count();
    }

    /**
     * Get the first database record matching the query.
     *
     * @return Model
     */
    public function first()
    {
        return $this->toObject($this->builder->first());
    }

    /**
     * Make a model using the given array of data as its attributes.
     *
     * @param array $data
     * @return Model
     */
    public function wrapWithModel(array $data)
    {
        return (new Model)->forceFill($data);
    }

    /**
     * Set the default model for the repository.
     *
     * @param Model $model
     * @return $this
     */
    public function setModel($model): RepositoryContract
    {
        $this->model = $this->newModel($model);

        return $this;
    }

    /**
     * Convert the given model to an array.
     *
     * @param Model $model
     * @return array
     */
    public function toArray($model): array
    {
        return $model->toArray();
    }

    /**
     * Transform the model to an object.
     *
     * @param mixed $model
     * @return object|null
     */
    public function toObject($model)
    {
        return $model;
    }

    /**
     * Associate two given models with each other.
     *
     * @param Model $child
     * @param Model $parent
     * @param string $relation
     * @return Repository
     */
    public function associate($child, $parent, $relation = null)
    {
        $child->$relation()->associate($parent);

        return $this;
    }

    /**
     * Create another model associated with the given model.
     *
     * @param Model $model
     * @param string $relation
     * @param array $data
     * @return Model
     */
    public function createRelation($model, $relation, $data = [])
    {
        return $model->$relation()->create($data);
    }

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
    ): LengthAwarePaginator {
        return $model->$relation()->filter($filters)->paginate($limit);
    }

    /**
     * Delete the specified related model.
     *
     * @param Model $model
     * @param string $relation
     * @return void
     */
    public function deleteRelation($model, $relation)
    {
        return $model->$relation()->delete();
    }

    /**
     * Get all records that are not in the specified ids.
     *
     * @param array $ids
     * @param array $constraints
     * @return Collection
     */
    public function getInverse($ids, $constraints)
    {
        $builder = $this->newBuilder();

        foreach ($constraints as $field => $value) {
            $builder->where($field, $value);
        }

        return $builder->whereNotIn('id', $ids)->get();
    }

    /**
     * Delete all records of this repositories model
     * whose ids are not contained in the given ids
     *
     * @param array $ids
     * @param array $constraints
     * @return void
     */
    public function deleteInverse($ids, $constraints)
    {
        $builder = $this->newBuilder();

        foreach ($constraints as $field => $value) {
            $builder->where($field, $value);
        }

        $builder->whereNotIn('id', $ids)->delete();
    }

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
    ) {
        $ids = collect($attributes)->pluck($idKey)->toArray();

        $this->deleteInverse($ids, $additionalAttributes);

        foreach ($attributes as $attribute) {
            $attribute = array_merge($attribute, $additionalAttributes);
            $this->newModel()->updateOrCreate(
                [$idField => $attribute[$idKey]],
                $attribute
            );
        }
    }

    /**
     * Determine if a model is the owner of another.
     *
     * @param Model $parent
     * @param Model $child
     * @return bool
     */
    public function owns($parent, $child): bool
    {
        $foreignKey = $parent->getForeignKey();

        $localKey = $parent->getKeyName();

        return $parent->$localKey === $child->$foreignKey;
    }

    /**
     * Load the specified model relations.
     *
     * @param mixed ...$relations
     * @return Builder
     */
    public function with(...$relations)
    {
        return $this->builder->with(...$relations);
    }

    /**
     * Get the morph alias of the given model.
     *
     * @param Model|string $model
     * @return string
     */
    public static function getMorphClass($model)
    {
        $model = is_string($model) ? (new $model) : $model;
        return $model->getMorphClass();
    }

    /**
     * Get new query builder instance.
     *
     * @return Builder
     */
    protected function newBuilder()
    {
        return $this->newModel()->newQuery();
    }

    /**
     * Get the model specified by $id.
     *
     * If $id is null, get the repository's default model.
     *
     * @param int $id
     * @return Model
     */
    protected function getModelById($id)
    {
        return $this->newBuilder()->find($id);
    }

    /**
     * Get new instance of the repository's model.
     *
     * @param array $data
     * @return Model
     */
    public function newModel($data = [])
    {
        $class = get_class($this->model);

        return new $class($data);
    }

    /**
     * Set the query builder instance.
     *
     * @param Builder $builder
     * @return void
     */
    protected function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Get the query builder instance.
     *
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->newBuilder();
    }
}
