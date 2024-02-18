<?php


namespace App\Foundation\Http\CoreHelpers;


use App\Foundation\Traits\HasAuthorization;

trait CoreEvents
{
    use HasAuthorization;


    /**
     * Event that's called before before view list from database
     * called from @index method
     */
    public function onIndex()
    {
        if(@$this->permitted_actions['index']) {
            $this->hasPermission($this->permitted_actions['index']);
        }
    }

    /**
     * Event that's called before  create page viewed
     * called from @create method
     */
    public function onCreate()
    {
        if(@$this->permitted_actions['create']) {
            $this->hasPermission($this->permitted_actions['create']);
        }
    }

    /**
     * Event that's called before  create query intodatabase
     * called from @store method
     */
    public function onStore()
    {
        if(@$this->permitted_actions['create']) {
            $this->hasPermission($this->permitted_actions['create']);
        }
    }

    /**
     * Event that's called before update page viewed
     * called from @edit method
     */
    public function onEdit()
    {
        if(@$this->permitted_actions['edit']) {
            $this->hasPermission($this->permitted_actions['edit']);
        }
    }

    /**
     * Event that's called before update page viewed
     * called from @show method
     */
    public function onShow()
    {
        if(@$this->permitted_actions['show']) {
            $this->hasPermission($this->permitted_actions['show']);
        }
    }



    /**
     * Event that's called before before update record in database
     * called from @update method
     */
    public function onUpdate()
    {
        if(@$this->permitted_actions['edit']) {
            $this->hasPermission($this->permitted_actions['edit']);
        }
    }

    /**
     * Event that's called before before delete record from database
     * called from @destroy method
     */
    public function onDestroy()
    {
        if(@$this->permitted_actions['delete']) {
            $this->hasPermission($this->permitted_actions['delete']);
        }
    }

}
