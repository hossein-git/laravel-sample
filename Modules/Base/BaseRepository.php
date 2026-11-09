<?php

namespace Modules\Base;

use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository
{
    protected ?Model $model = null;

    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Make Model instance.
     * @throws Exception
     */
    public function makeModel(): Model
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }

        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \RuntimeException("Class {$this->model()} must be an instance of " . Model::class);
        }

        return $this->model = $model;
    }

    /**
     * Configure the Model.
     */
    abstract public function model(): string;

    /**
     * Paginate records for scaffold.
     */
    public function paginate(
        string $search = '',
        int $perPage = 15,
        array $relations = [],
        array $columns = ['*']
    ): LengthAwarePaginator {
        return $this->allQuery($search, $relations)->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     */
    public function allQuery(string $search = '', array $relations = [], ?int $skip = null, ?int $limit = null): Builder
    {
        $query = $this->model->newQuery()->with($relations);

        if (!empty($search)) {
            $searchable = $this->getFieldsSearchable();
            $query->where(
                function ($q) use ($searchable, $search) {
                    foreach ($searchable as $field) {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            );
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /**
     * Get searchable fields array.
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Retrieve all records with given filter criteria.*.
     */
    public function all(
        string $search = '',
        array $relations = [],
        ?int $skip = null,
        ?int $limit = null,
        array $columns = ['*']
    ): Collection {
        $query = $this->allQuery($search, $relations, $skip, $limit);

        return $query->get($columns);
    }

    public function trash(array $columns = ['*'])
    {
        return $this->model->newQuery()->onlyTrashed()->get($columns);
    }

    /**
     * Create model record.
     */
    public function create(array $input): Model
    {
        $this->clearCache();

        $model = $this->model->newInstance($input);
        $model->save();

        return $model;
    }

    /**
     *  if cache key exists this will delete it after CRUD.
     */
    public function clearCache(): void
    {
        if (!isset($this->cacheKey)) {
            return;
        }

        if (is_array($this->cacheKey)) {
            foreach ($this->cacheKey as $cache) {
                Cache::forget($cache);
            }

            return;
        }

        Cache::forget($this->cacheKey);
    }

    /**
     * Update model record for given id.
     */
    public function update(array $input, int $id): Model
    {
        $this->clearCache();

        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);
        $model->fill($input);
        $model->save();

        return $model;
    }

    /**
     * duplicate model.
     */
    public function duplicate(int $id): Model
    {
        $model = $this->find($id);
        $this->clearCache();

        return $model->replicate();
    }

    /**
     * Find model record for given id.
     */
    public function find(int $id, array $load = [], array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();

        return $query->findOrFail($id, $columns)->load($load);
    }

    public function delete(int $id): ?bool
    {
        $this->clearCache();

        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);

        return $model->delete();
    }

    /**
     * delete an item which has Media
     * all associated files will be preserved.
     */
    public function deleteWithoutMedia(int $id): mixed
    {
        $model = $this->model->newQuery()->findOrFail($id);
        $this->clearCache();

        return $model->deletePreservingMedia();
    }

    /**
     * delete completely.
     */
    public function forceDelete(int $id): bool
    {
        $this->clearCache();
        $model = $this->findWithTrash($id);

        return $model->forceDelete();
    }

    public function findWithTrash(int $id): ?Model
    {
        $query = $this->model->newQuery();

        return $query->withTrashed()->findOrFail($id);
    }

    public function restore(int $id): bool
    {
        $query = $this->model->newQuery();
        $model = $query->withTrashed()->findOrFail($id);

        return $model->restore();
    }

    public function where(...$conditions): Builder
    {
        return $this->makeModel()->newQuery()->where(...$conditions);
    }

    public function updateWhere(array $input, ...$conditions): Model
    {
        $this->clearCache();

        $query = $this->model->newQuery();
        $model = $query->where(...$conditions)->firstOrFail();
        $model->fill($input);
        $model->save();

        return $model;
    }
}
