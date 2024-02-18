<?php

namespace App\Domains\Favourite\Http\Controllers\Api\V1\User;

use App\Domains\Favourite\Features\Api\V1\User\AddToFavouriteFeature;
use App\Domains\Favourite\Features\Api\V1\User\DeleteFavouriteFeature;
use App\Domains\Favourite\Features\Api\V1\User\GetMyFavouriteFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetMyFavouriteFeature::class);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->serve(AddToFavouriteFeature::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->serve(DeleteFavouriteFeature::class);
    }
}
