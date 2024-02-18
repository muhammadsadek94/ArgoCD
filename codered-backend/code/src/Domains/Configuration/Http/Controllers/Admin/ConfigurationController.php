<?php

namespace App\Domains\Configuration\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Domains\Configuration\Models\Configuration;
use App\Foundation\Http\Controllers\Admin\CoreController;

class ConfigurationController extends CoreController
{
    public $domain = 'configuration';

    public function __construct(Configuration $model)
    {
        $this->model = $model;
        //        $this->auto_set_property = false;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Foundation\Http\Controllers\Admin\View
     */
    public function index()
    {
        $configurations = $this->model->get();
        return $this->view('index', [
            'row'        => $configurations,
            'breadcrumb' => $this->breadcrumb,
        ]);
    }

    public function onStore()
    {
        return $this->validate($this->request, [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Foundation\Http\Controllers\Admin\Voild
     */
    public function store()
    {
        $this->ifMethodExistCallIt('onStore');
        $insert = $this->model->set($this->request->except([
            '_token',
            '_method'
        ]));

        $this->ifMethodExistCallIt('isStored', $insert);
        return $this->returnMessage(true, 2);
    }

    protected function setCustomProperty()
    {
        # generate class's breadcrumb
        $class_breadcrumb = $this->getControllerClassName();
        $route = $this->createRoute($class_breadcrumb);

        $this->createModuleName($class_breadcrumb);
        $this->createViewPath($route);
        $this->setBaseBreadcrumb($class_breadcrumb);

    }

}
