$(document).ready(function() {
    /**
     * Start Area of Elements Duplicator
     * Elements Duplicator By Mark Rady
     */
    $(document).on('click','[data-toggle="duplicate-input"]',function(e){
        $item_selector = $(this).data('duplicate'); // item need to duplicate
        $item = $($item_selector).last().clone(); // clone it

        let countDuplicatedElements = $($(this).data('remove')).length;
        console.log("countDuplicatedElements", countDuplicatedElements)
        console.log("$item", $item)

        // empty all inputs
        $item.find('input').val('');
        $item.find('input:not([type="checkbox"]) :not([type="radio"])').val('');
        $item.find('textarea').val('');
        $item.find('input[type="checkbox"]').prop('checked',false);
        $item.find('input[type="radio"]').prop('checked',false);
        $item.find('input[type="radio"]').val(countDuplicatedElements);

        // target will receive the data
        $target = $(this).data('target'); //get target

        // replace content of button such as icon
        $item.find(`[data-target="${$target}"]`)
            .children().first()
            .replaceWith($(this).data('toggledata'));

        // change button functionlity to remove instead of create
        $item.find(`[data-target="${$target}"]`)
            .toggleClass($(this).data('toggleclass'))
            .attr('data-toggle','remove-input');

        if ($item.find(`.select2`).attr('data-select2-id')){

        // if($item.attr('data-select2-id')){
        //     $item.attr('data-select2-id').val($item.attr('data-select2-id') +$($target).length);
        // }
            $item.find('span.select2').remove();
            $item.find('select').removeClass('select2-hidden-accessible');
            $item.find('select').removeAttr('data-select2-id');
            $item.find(`.select2`).each((i, el) => {
                let countingSelect2 = (countDuplicatedElements) + (Math.random()) + i;
                console.log('countingSelect2', countingSelect2)
                $(el).attr('data-select2-id',  countingSelect2)
            })

        }

        if ($($target).length == 1) {
            $($target).append($item);
        }
        else if ($($target).length > 1) {
            $(this).parents($item_selector).closest($target).append($item);
        }
        setTimeout(()=>{
            $(".select2").select2();

    }, 500);
    });

    $(document).on('click','[data-toggle="remove-input"]',function(e){
        $item = $(this).data('remove');
        $(this).closest($item).remove();
    });
     /**
     * End Area of Elements Duplicator
     * Elements Duplicator By Mark Rady
     */


     /**
      * basic data binder from input to target
      * input will need to have following attributes
      * [data-toggle="binder"] << To understand event listner
      * [data-target="{TARGET_NAME}"] << this item will be target to append value
      *
      * Element will be target of data
      * [data-bind="{TARGET_NAME}"] << to will be target when other input value start to change
      */
    $(document).on('keyup','[data-toggle="binder"]', function(){
       const THIS = $(this);
       $val = THIS.val();
       $target = THIS.data("target");
       $(`[data-bind="${$target}"]`).text($val);
    });


    $(document).on('click','[data-toggle="check-all"]',function(e){
        $target = $(this).data('target');
        $selector = $(document).find($target);
        if ($(this).is(':checked')) {
          $selector.prop('checked', true);
        } else {
          $selector.prop('checked', false);
        }
    });



});
