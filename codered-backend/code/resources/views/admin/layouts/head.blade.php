        <link href="{{ asset('admin/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/assets/css/app.min.css?v=.01') }}" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css" />

        <style>
            .phone-container.has-danger .iti__flag-container{
                top: -20px !important;
            }

            @media (min-width: 1200px) AND (max-width:1600px) {
                .mx-ml-3{
                    margin-left : 2.25rem;
                    margin-right : 2.25rem;
                }
            }


            .select2-container {
                width: 100% !important;
            }


            .faq-question-q-box {
                color:#e83c30;
                background-color: #f1556c2e;
            }
            a {
                color:#e83c30;
            }

            .quick-navigation .card-box {
                height: 260px;
            }





        </style>

        @stack('head')
