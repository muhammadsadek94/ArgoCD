<div class="form-group {{ $cols ?? 'col-12' }} {{ $wrap_class ?? '' }}">
    <button id="{{$name?? ''}}"  type="submit" {{ $button_attr ?? '' }}  class="btn {{ $button_color_class ?? 'btn-primary radius-5' }} waves-effect waves-light width-sm {{ $button_extra_class ?? '' }}">{{ $button_text ?? trans('lang.save') }}
        @isset($icon)
            <i class="{{ $icon }}"></i>
        @endisset
    </button>
</div>

