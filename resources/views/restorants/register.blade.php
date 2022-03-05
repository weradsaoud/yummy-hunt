@extends('layouts.front', ['title' => __('User Profile')])
@if (strlen(config('settings.recaptcha_site_key'))>2)
    @section('head')
    {!! htmlScriptTagJsApi([]) !!}
    @endsection
@endif

 

@section('content')
    @include('users.partials.header', [
        'title' => "",
    ])

       
    <div class="container-fluid mt--7">
        
        <div class="row">
 <div class="dropdown  ml-4 mb-4">
                <a href="#" class="btn btn-neutral dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                    {{ $currentLanguage }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="">
                    <li>
                        <a class="dropdown-item" href="?lang=en">
                          English
                        </a>
                    </li>
                     <li>
                        <a class="dropdown-item" href="?lang=ar">
                            Arabic
                        </a>
                    </li>
                </ul>
        </div>
            </div>
            <div class="col-xl-8 offset-xl-2">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('Register your restaurant') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form  id="registerform" method="post" action="{{ route('newrestaurant.store') }}" autocomplete="off">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Restaurant information') }}</h6>
                         @if ($errors->has('RecaptchError'))
                                       <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  {{ $errors->first('RecaptchError') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    @endif
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name">{{ __('Restaurant Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Restaurant Name here') }} ..." value="{{ isset($_GET["name"])?$_GET['name']:""}}" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <hr class="my-4" />
                            <h6 class="heading-small text-muted mb-4">{{ __('Owner information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name_owner">{{ __('Owner Name') }}</label>
                                    <input type="text" name="name_owner" id="name_owner" class="form-control form-control-alternative{{ $errors->has('name_owner') ? ' is-invalid' : '' }}" placeholder="{{ __('Owner Name here') }} ..." value="{{ isset($_GET["name"])?$_GET['name']:""}}" required autofocus>

                                    @if ($errors->has('name_owner'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name_owner') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('email_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="email_owner">{{ __('Owner Email') }}</label>
                                    <input type="email" name="email_owner" id="email_owner" class="form-control form-control-alternative{{ $errors->has('email_owner') ? ' is-invalid' : '' }}" placeholder="{{ __('Owner Email here') }} ..." value="{{ isset($_GET["email"])?$_GET['email']:""}}" required autofocus>

                                    @if ($errors->has('email_owner'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email_owner') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('phone_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone_owner">{{ __('Owner Phone') }}</label>
                                    <input type="text" name="phone_owner" id="phone_owner" class="form-control form-control-alternative{{ $errors->has('phone_owner') ? ' is-invalid' : '' }}" placeholder="{{ __('Owner Phone here') }} ..." value="{{ isset($_GET["phone"])?$_GET['phone']:""}}" required autofocus>

                                    @if ($errors->has('phone_owner'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone_owner') }}</strong>
                                        </span>
                                    @endif
                                </div>
                               <div class="form-check">
                                  <input class="form-check-input" type="checkbox"  id="tandd">
                                  <label class="form-check-label" for="tandd">
                                    <a href="https://dealont.com/privacy-policy/" style="color:blue;">{{__('Accept Terms and Conditions')}}</a>
                                  </label>
                                </div>
      

                                <div class="text-center">
                                    @if (strlen(config('settings.recaptcha_site_key'))>2)
                                        @if ($errors->has('g-recaptcha-response'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                        @endif

                                        {!! htmlFormButton(__('Save'), ['id'=>'thesubmitbtn','class' => 'btn btn-success mt-4']) !!}
                                    @else
                                        <button type="button" data-sitekey="6Lf61aYcAAAAAKqrSMe_ex_OmMyeZonzy8Yxaprm" data-callback='onSubmit' id="thesubmitbtn" class="btn btn-success mt-4 g-recaptcha">{{__('Save')}}</button>
                                        <button type="submit" style="display:none" id="fSubmit">{{__('Save')}}</button>

                                    @endif


                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    </div>
@endsection

@section('js')
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script>
       function onSubmit(token) {
         $("input[name='g_recaptcha_response']").remove();
        $('#registerform').prepend('<input type="hidden" name="g_recaptcha_response" value="' + token + '">');
        //  document.getElementById("registerform").submit();
         console.log(document.getElementById('tandd').checked);
         if(document.getElementById('tandd').checked)
         $("#fSubmit").click();
         else
         alert("{{__('Please Accept Terms and Conditions')}}")
        // alert("Hi")
       }
      
     </script>

@if (isset($_GET['name'])&&$errors->isEmpty())
<script>
    "use strict";
    document.getElementById("thesubmitbtn").click();
</script>
@endif
@endsection
