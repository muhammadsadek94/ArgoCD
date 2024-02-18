$(document).ready(function() {
    // init ajax
    $.ajaxSetup({
        cache: false,
        headers: {
            '_token': window.app_variables.csrfToken
        },
        data: {_token: window.app_variables.csrfToken },
        beforeSend: function () {
           $('button[type="submit"]').append(' <i class="fa fa-spinner fa-pulse"></i> ').attr('disabled', 'disabled');
        },
        complete: function () {
            $('button[type="submit"]').find('.fa').remove();
            $('button[type="submit"]').prop('disabled', false);
        },
    });
    $(document).on('click', '.loading', function(event) {
        let href = $(this).attr('href')
        if(href === null || href === undefined) return false;
        $(this).removeAttr('href');
        $(this).attr('disabled','disabled');
        $(this).find('i').remove();
        $(this).append(' <i class="fa fa-spinner fa-pulse"></i> ').attr('disabled', 'disabled');
        window.location = href;
    });

    /*$(document).on('click','.pagination a',function(event){
        event.preventDefault();
        if ($(this).parent().parent().parent().hasClass('dataTables_paginate')) {
            return false;
        }
        var str =window.location.href;
        var res = str.split("?");
        var pageinate = $(this).attr('href')+"&"+res[1];
        $.get(pageinate, function(data) {
            $('table tbody').html(data);
        });
        return false;
    });*///pagination

    $(document).on('keyup', '[data-toggle="search-table"]', function(event) {
        event.preventDefault();
        var thisInput = $(this);
        var thisForm = $(this).closest('form');
        var formAction = thisForm.attr('action');
        var formMethod = thisForm.attr('method');
        var formData = thisForm.serialize();
        $.ajax({
            url: formAction,
            type: formMethod,
            dataType: 'json',
            data: formData,
            success: function(data) {
                $('table tbody').html(data);
            },
        });
        return false;
    });

    $(document).on('click', '[data-toggle="delete-row"]', function(e){
        e.preventDefault();
        var $this = $(this)
        $href = $this.attr('href');
        $method = $this.data('method');

        $message = $this.data('message');
        $confirm = confirm($message);
        if ($confirm == true) {
            $.ajax({
                url: $href,
                type: $method,
                dataType: 'JSON',
            })
            .done(function() {
                $this.parent().parent().hide(500);
            })
            .fail(function() {
                alert('Please Refresh page');
            })
            .always(function() {
                console.log("complete");
            });
        }
        return false;
    });

    $(document).on('submit','[data-toggle="ajax"]',function(event){
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
                        $("select.select2").val('').trigger('change')

                    }
                    if (thisForm.data('close-modal')) {
                        setTimeout(function(){
                            $('.modal').modal('hide');
                        },1000)
                    }

                    if (thisForm.data('refresh-page')) {
                        setTimeout(() => window.location.reload(), 2000);

                    }
                    if (thisForm.data('redirect')) {
                        setTimeout(() => window.location = thisForm.data('redirect'), 2000);
                    }
                    if(data.redirect == true){
                        window.location = data.url;
                    }
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
                try {
                    $.each(errors.errors, function(index, val) {
                        try {
                            if (loopCounter == 0) {
                                $('html, body').animate({
                                    scrollTop: $("[name='"+index+"']").offset().top - 255
                                }, 200);
                            }
                            $(thisForm).find('[name='+''+index+''+']').parent().addClass('has-danger');
                            $(thisForm).find('[name='+''+index+''+']').addClass('is-invalid');
                            $(thisForm).find('[name='+''+index+''+']').parent().append('<span class="invalid-feedback"> <strong> '+val+' </strong> </span>');
                        } catch (e) {
                            console.error(e);
                        }
                        loopCounter++;
                    });
                }
                catch (error) {
                    console.error(error);
                }


            },
        });//ajax
        return false;
    });//ajax-form-request

});


