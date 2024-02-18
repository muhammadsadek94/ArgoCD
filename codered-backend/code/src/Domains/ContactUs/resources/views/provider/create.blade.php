@extends("provider.layouts.master")
@section("title", trans("{$domain}::lang.".end($breadcrumb)))
@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
@endpush
@section("content")
    @if(request("success"))
        <div class="alert alert-success success mt-2">Sent successfully</div>
    @endif
    <div id="app">
        <div class="row">
            <div class="col-12 my-2">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url("/provider") }}">@lang("{$domain}::lang.Provider")</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('provider/service') }}">@lang("careers::lang.Careers")</a></li>
                            <li class="breadcrumb-item">@lang("careers::lang.create")</li>
                        </ol>
                    </div>
                    <h4 class="page-title"> @lang("careers::lang.create")</h4>
                </div>
            </div>
        </div>
        <div class="card-box col-12">
            {{Form::open(["url" => "/provider/careers", "method" => "POST", "class" => "row"])}}
            <div class="col-md-6 col-12 my-2">
                <label>@lang("contact_us::lang.Email")</label>
                <input class="form-control" type="email" name="email" v-model="contact_us_form.email" placeholder="Email">
            </div>
            <div class="col-md-6 col-12 my-2">
                <label>@lang("contact_us::lang.subject")</label>
                <select name="subject_id" class="form-control" v-model="contact_us_form.subject_id" placeholder="Choose subject">
                    @foreach($subjects as $id => $subject)
                        <option value="{{$id}}">{{$subject}}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-12 my-2">
                <label>@lang("contact_us::lang.message")</label>
                <textarea name="body" cols="30" rows="10" class="form-control" v-model="contact_us_form.body" placeholder="type your message"></textarea>
            </div>
            <div class="col-12">
                <button @click="CreateCareer" class="btn btn-success">@lang("careers::lang.Submit")</button>
            </div>
            {{Form::close()}}

        </div>
    </div>
@endsection

@push('script')
    <script>
        window.onload = function() {
            var app = new Vue({
                el: '#app',
                data: {
                    contact_us_form : {
                        email : null,
                        body: null,
                        subject_id : null
                    },
                    base_url: "{{url('')}}",
                },
                methods: {
                    CreateCareer(e){
                        e.preventDefault();
                        http.post(`${this.base_url}/provider/contact-us`, this.getFormData())
                            .then((response) => {
                                    window.location = "/provider/contact-us/create?success=sent-successfully"
                                }
                            )
                            .catch((errors)  => {
                                let i = 0;
                                $('.error').remove()
                                $('.success').remove()
                                for (i; i< errors.response.data.errors.length; i++) {
                                    $('[name="'+ errors.response.data.errors[i].name + '"]').parent().append('<small class="error text-danger">' + errors.response.data.errors[i].message + '</small>')
                                }
                            })

                    },
                    getFormData(){
                        let data = new FormData;
                        this.contact_us_form.email !== null ? data.append("email", this.contact_us_form.email) : '';
                        this.contact_us_form.subject_id !== null ? data.append("subject_id", this.contact_us_form.subject_id) : '';
                        this.contact_us_form.body !== null ? data.append("body", this.contact_us_form.body) : '';
                        data.append("app_type", 2)
                        return data;
                    }
                }
            })
        }
    </script>
@endpush
