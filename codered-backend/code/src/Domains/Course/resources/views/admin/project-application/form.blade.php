<div class="panel-body row">
    @include('admin.components.inputs.file', [
        'name' => 'voucher',
        'label' => 'Voucher',
        'form_options'=> [
          'required'
        ],
        'cols' => 'col-12',
        'help' => "<a href='" . url('assets/sheets/lesson-vouchers-examples.xlsx') . "'> Download Example</a>"
    ])


    {!! Form::hidden('lesson_id', $lesson->id) !!}
</div>
