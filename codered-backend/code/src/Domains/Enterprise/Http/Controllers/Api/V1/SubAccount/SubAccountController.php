<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\SubAccount;

use App\Domains\Enterprise\Features\Api\V1\SubAccount\AddNewSubAccountLearnPathFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\AddNewSubAccountLicneseFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\CreateSubAccountFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\DeleteSubAccountLearnPathFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\GetSubAccountByIdFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\GetSubAccountFeature;
use App\Domains\Enterprise\Features\Api\V1\SubAccount\UpdateSubAccountProfileFeature;
use App\Domains\Enterprise\Features\Api\V1\User\AddLearnPathsToUserFeature;
use App\Domains\Enterprise\Features\Api\V1\User\DeleteSubscriptionFeature;
use App\Domains\Enterprise\Features\Api\V1\User\GetUsersByIdFeature;
use App\Domains\Enterprise\Features\Api\V1\User\UpdateUserFeature;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Http\Controller;

class SubAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->serve(GetSubAccountFeature::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return $this->serve(CreateSubAccountFeature::class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->serve(GetSubAccountByIdFeature::class);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function update()
    {
        return $this->serve(UpdateSubAccountProfileFeature::class);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addNewSubAccountLicense()
    {
//        return $this->serve(AddLearnPathsToUserFeature::class);
        return $this->serve(AddNewSubAccountLicneseFeature::class);
    }

    public function addNewSubAccountLearnPath()
    {
//        return $this->serve(AddLearnPathsToUserFeature::class);
        return $this->serve(AddNewSubAccountLearnPathFeature::class);
    }


    public function deleteSubAccountLearnPath()
    {
        return $this->serve(DeleteSubAccountLearnPathFeature::class);
    }
}
