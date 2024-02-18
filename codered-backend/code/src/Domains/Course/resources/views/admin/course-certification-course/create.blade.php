@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
	@include('admin.layouts.breadcrumb', [
		'page_title' => end($breadcrumb)->title,
		'crumbs' => $breadcrumb
	])
@endsection


@section('form')
	{!! Form::open(['method'=>'POST','url' => "$route", 'files'=>true,'data-toggle'=> 'createCourse', 'reset'=>"true"]) !!}
	@include ("{$view_path}.form",['submitButton' => trans('lang.create')])
	{!! Form::close() !!}
@endsection


@push('script-bottom')
	<script>
		$(document).ready(function() {
            $(document).on('submit','[data-toggle="createCourse"]',function(event){
                event.preventDefault();
                $('form div.has-danger').removeClass('has-danger');
                $('form input.is-invalid, form textarea.is-invalid, form select.is-invalid ').removeClass('is-invalid');
                $('span.invalid-feedback').remove();
                var thisForm   = $(this) ;
                var formAction = thisForm.attr('action');
                var formMethod = thisForm.attr('method');
                var formData    = new FormData(this);
                $.ajax({
                    url: formAction,
                    type: formMethod,
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status == 'true')
                        {
                            toastr['success'](data.message);
                            if (thisForm.attr('refresh')) {
                                setTimeout(function(){
                                    window.location.reload();
                                }, 2500);
                            }
                            if (thisForm.attr('reset')) {
                                $(thisForm).trigger("reset");
                                $(".dropify-clear").trigger("click");
                                if (typeof(CKEDITOR) != 'undefined') {
                                    for (instance in CKEDITOR.instances) {
                                        CKEDITOR.instances[instance].setData("")
                                    }
                                }

                            }
                            setTimeout(function() {
                                let id = data.model.id;
                                let url = `{{ url(Constants::ADMIN_BASE_URL. "/course-certification-course") }}/${id}/edit`;
                                window.location = url;
                            }, 2000);
                        }
                        else
                        {
                            toastr['error'](data.message);
                        }

                    },
                    error:function(data){
                        var errors = data.responseJSON;
                        var loopCounter = 0;
                        let firstErrMessage = Object.values(errors.errors)[0];
                        toastr['error'](firstErrMessage)
                        $.each(errors.errors, function(index, val) {
                            if (loopCounter == 0) {
                                $('html, body').animate({
                                    scrollTop: $("[name='"+index+"']").offset().top - 255
                                }, 200);
                            }
                            $(thisForm).find('[name='+''+index+''+']').parent().addClass('has-danger');
                            $(thisForm).find('[name='+''+index+''+']').addClass('is-invalid');
                            $(thisForm).find('[name='+''+index+''+']').parent().append('<span class="invalid-feedback"> <strong> '+val+' </strong> </span>');
                            loopCounter++;
                        });

                    },
                });//ajax
                return false;
            });//ajax-form-request
        });
	</script>
@endpush