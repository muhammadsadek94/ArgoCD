<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/css/froala_editor.pkgd.min.css" rel="stylesheet"
    type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.5/css/froala_style.min.css" rel="stylesheet"
    type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/js/froala_editor.pkgd.min.js">
</script>

<div class="panel-body row">

    <div class="col-12"></div>
    {!! Form::hidden('course_type', \App\Domains\Course\Enum\CourseType::COURSE_CERTIFICATION) !!}

    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => 'Challenge Name',
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.text', [
        'name' => 'slug',
        'label' => trans('Slug Url (Prefer \'-\' as separator)'),
        'form_options' => ['required', 'placeholder' => 'new-challenge'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.text', [
        'name' => 'competition_id',
        'label' => 'Challenge ID',
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.number', [
        'name' => 'duration',
        'label' => 'Duration (in minutes)',
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans('course::lang.status'),
        'form_options' => ['required'],
        'select_options' => ['1' => 'Active', '0' => 'Disabled'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.date', [
        'name' => 'end_date',
        'label' => trans('End Date'),
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])


    <div class="col-12"></div>

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => 'Description',
        'form_options' => ['required', 'rows' => 4],
        'cols' => 'col-md-6 col-12',
    ])

    @include('admin.components.inputs.textarea', [
        'name' => 'competition_scenario',
        'label' => 'Competition Scenario *',
        'form_options' => ['', 'rows' => 2, 'class' => 'fr-view'],
        'cols' => 'col-12',
    ])



    @include('admin.components.inputs.select-multiple-tags', [
        'name' => 'tags[]',
        'label' => trans('Tags'),
        'form_options' => ['required'],
        'cols' => 'col-12',
        'select_options' => $row->tags ?? [],
        'value' => $row->tags ?? [],
    ])

    <div class="col-12"></div>



    <hr>

    @if (!isset($row))
        @include("{$view_path}.partials.create-flags")
    @endif

    <div id="flags-multiple" class="col-12">
        @include("{$view_path}.partials.update-flags")
    </div>




    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>



@push('script')
    <script>
        $(document).ready(function() {
            var editor = new FroalaEditor('.fr-view', {
                key: 'CTD5xB1C2G1G1A16B3wc2DBKSPJ1WKTUCQOd1OURPE1KDc1C-7J2A4D4A3C6E2G2F4E1F1=='
            });
        });
    </script>
@endpush



@push('script')
    <script>
        $(document).ready(function() {
            $('#user_id').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/micro-degree-course/actions/get-trainers`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });


        });

        $(document).ready(function() {
            /**
             * Start Area of Elements Duplicator
             * Elements Duplicator By Mark Rady
             */
            $(document).on(
                "click",
                '[data-toggle="duplicate-input-custom"]',
                function(e) {
                    $item_selector = $(this).data("duplicate"); // item need to duplicate
                    $item = $($item_selector).last().clone(); // clone it

                    let countDuplicatedElements = $($(this).data("remove")).length;
                    console.log("countDuplicatedElements", countDuplicatedElements);
                    console.log("$item", $item);

                    // empty all inputs
                    $item.find("input").val("");
                    $item
                        .find('input:not([type="checkbox"]) :not([type="radio"])')
                        .val("");
                    $item.find("textarea").val("");
                    $item.find('input[type="checkbox"]').prop("checked", false);
                    $item.find('input[type="radio"]').prop("checked", false);
                    $item.find('input[type="radio"]').val(countDuplicatedElements);

                    // target will receive the data
                    $target = $(this).data("target"); //get target

                    // replace content of button such as icon
                    $item
                        .find(`[data-target="${$target}"]`)
                        .children()
                        .first()
                        .replaceWith($(this).data("toggledata"));

                    // change button functionlity to remove instead of create
                    $item
                        .find(`[data-target="${$target}"]`)
                        .toggleClass($(this).data("toggleclass"))
                        .attr("data-toggle", "remove-input-custom");

                    if ($item.find(`.select2`).attr("data-select2-id")) {
                        // if($item.attr('data-select2-id')){
                        //     $item.attr('data-select2-id').val($item.attr('data-select2-id') +$($target).length);
                        // }
                        $item.find("span.select2").remove();
                        $item.find("select").removeClass("select2-hidden-accessible");
                        $item.find("select").removeAttr("data-select2-id");
                        $item
                            .find(`.select2`)
                            .attr(
                                "data-select2-id",
                                countDuplicatedElements +
                                $item.find(`.select2`).attr("data-select2-id")
                            );
                    }

                    if ($($target).length == 1) {
                        $($target).append($item);
                    } else if ($($target).length > 1) {
                        $(this).parents($item_selector).closest($target).append($item);
                    }
                }
            );

            $(document).on(
                "click",
                '[data-toggle="remove-input-custom"]',
                function(e) {
                    $item = $(this).data("remove");
                    $(this).closest($item).remove();
                }
            );
        });
    </script>
@endpush
