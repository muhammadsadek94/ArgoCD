
<form class="px-3">
    <div class="row align-item-end justify-content-end pr-2 mb-3">
        @include('admin.components.inputs.text-placeholder', ['name'=>'search', 
        'label' =>' ', 'cols' => 'col-12 col-xl-3 col-md-9 col-xs-12 d-flex align-items-center m-0'])
        <button id="Search"  type="submit"  class="btn btn-primary radius-5 waves-effect waves-light width-sm">
            Search
            <i class="fa fa-search"></i>
        </button>
    </div>
</form>
