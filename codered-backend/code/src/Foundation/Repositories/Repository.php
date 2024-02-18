<?php


namespace App\Foundation\Repositories;

use INTCore\OneARTFoundation\Model;

/**
 * Class Repository
 * @package App\Foundation\Repositories
 */
class Repository implements RepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor to bind model to repo.
     * @param Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get all instances of model
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * create a new record in the database
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Fills out an instance of the model
     * and saves it, pretty much like mass assignment.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fillAndSave($attributes)
    {
        $model = $this->model->fill($attributes);
        $model->save();

        return $this->model;
    }

    /**
     * update record in the database
     * @param array $data
     * @param int|string $id
     * @return bool
     */
    public function update($id, array $data)
    {
        $record = $this->model->find($id);
        return $record->update($data);
    }

    /**
     * remove record from the database
     * @param int|string|array $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * show the record with the given id
     * @param int|string $id
     * @return Model
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Eager load database relationships
     * @param mixed $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    /**
     * Paginate
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($page = 12)
    {
        return $this->model->paginate($page);
    }

    /**
     * Implement a convenience call to findBy
     * which allows finding by an attribute name
     * as follows: findByName or findByAlias.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        /*
         * findBy convenience calling to be available
         * through findByName and findByTitle etc.
         */

        if(preg_match('/^findBy/', $method)) {
            $attribute = strtolower(substr($method, 6));
            array_unshift($arguments, $attribute);

            return call_user_func_array([$this, 'findBy'], $arguments);
        }
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }
}
