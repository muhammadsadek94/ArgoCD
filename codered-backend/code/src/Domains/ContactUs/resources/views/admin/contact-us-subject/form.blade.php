<div class="panel-body row">
    <div class="form-group mb-3 col-lg-6 col-12 {{ $errors->has("subject_en") ? ' has-error' : '' }}">
        {!! Form::label('subject_en', "Subject in English", ['class'=>'col-form-label']) !!}
        <div>
            {!! Form::text('subject_en', null, ['class'=>'form-control', 'required']) !!}
            <span class="m-form__help form-validation-help">
                {{ $errors->first('subject_en') }}
            </span>
        </div>
    </div>
    <div class="form-group mb-3 col-lg-6 col-12 {{ $errors->has("subject_ar") ? ' has-error' : '' }}">
        {!! Form::label('subject_ar', "Subject in Arabic", ['class'=>'col-form-label']) !!}
        <div>
            {!! Form::text('subject_ar', null, ['class'=>'form-control', 'required']) !!}
            <span class="m-form__help form-validation-help">
            {{ $errors->first('subject_ar') }}
            </span>
        </div>
    </div>
    <div class="form-group mb-3 col-lg-6 col-12 {{ $errors->has("activation") ? ' has-error' : '' }}">
        {!! Form::label('activation', "Activation", ['class'=>'col-form-label']) !!}
        <div>
            {!! Form::select('activation', ["1" => "Activate", "0" => "Suspend" ],null, ['class'=>'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">{{ $submitButton }}</button>
    </div>
</div>


