<?php

namespace App\Foundation\Repositories;


use INTCore\OneARTFoundation\Model;

/**
 * Class Repository
 * @package App\Foundation\Repositories
 */
interface RepositoryInterface
{
    /**
     * Get all instances of model
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all();

    /**
     * create a new record in the database
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|Model
     */
    public function create(array $data);

    /**
     * Fills out an instance of the model
     * and saves it, pretty much like mass assignment.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fillAndSave($attributes);

    /**
     * update record in the database
     * @param array $data
     * @param int|string $id
     * @return bool
     */
    public function update($id, array $data);

    /**
     * remove record from the database
     * @param int|string|array $id
     * @return int
     */
    public function delete($id);

    /**
     * show the record with the given id
     * @param int|string $id
     * @return Model
     */
    public function find($id);

    /**
     * @return Model
     */
    public function getModel();

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel($model);

    /**
     * Eager load database relationships
     * @param mixed $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations);

    /**
     * Paginate
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $page = 12);
}
