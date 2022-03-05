@extends('layouts.app', ['title' => __('Manage Header Text')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    @if(count($countries))
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Country') }}</th>
                                    <th scope="col">{{ __('Header Text Restaurant') }}</th>
                                    <th scope="col">{{ __('Header Text Restaurant (Arabic)') }}</th>
                                    <th scope="col">{{ __('Header Text Salon') }}</th>
                                     <th scope="col">{{ __('Header Text Salonr (Arabic)') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($countries as $country)
                                <tr>
                                    <td>{{ $country->name }} </td>
                                     <td>
                                       <form action="{{ route('settings.add_header_text_r') }}" method="post">
                                            @csrf
                                            @method('post')
                                        <input type="hidden" value="{{$country->id}}" name="id" />
                                        <input type="hidden" value="en" name="lang" />
                                         <input type="hidden" value="rest" name="type" />
                                         <textarea class="form-control" name="h_text">{{ $country->header_text }}</textarea>
                                          <button type="submit" class="btn mt-1" >
                                            {{ __('Save') }}
                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                        <form action="{{ route('settings.add_header_text_r') }}" method="post">
                                            @csrf
                                            @method('post')
                                        <input type="hidden" value="{{$country->id}}" name="id" />
                                        <input type="hidden" value="ar" name="lang" />
                                          <input type="hidden" value="rest" name="type" />
                                         <textarea class="form-control" name="h_text">{{ $country->header_text_ar }}</textarea>
                                          <button type="submit" class="btn mt-1" >
                                            {{ __('Save') }}
                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                     <form action="{{ route('settings.add_header_text_r') }}" method="post">
                                            @csrf
                                            @method('post')
                                        <input type="hidden" value="{{$country->id}}" name="id" />
                                        <input type="hidden" value="en" name="lang" />
                                          <input type="hidden" value="salon" name="type" />
                                         <textarea class="form-control" name="h_text">{{ $country->header_text_salon }}</textarea>
                                          <button type="submit" class="btn mt-1" >
                                            {{ __('Save') }}
                                         </button>
                                         </form>
                                     </td>
                                      <td>
                                      <form action="{{ route('settings.add_header_text_r') }}" method="post">
                                            @csrf
                                            @method('post')
                                        <input type="hidden" value="{{$country->id}}" name="id" />
                                        <input type="hidden" value="ar" name="lang" />
                                        <input type="hidden" value="salon" name="type" />
                                         <textarea class="form-control" name="h_text">{{ $country->header_text_salon_ar }}</textarea>
                                          <button type="submit" class="btn mt-1" >
                                            {{ __('Save') }}
                                         </button>
                                         </form>
                                     </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="card-footer py-4">
                        @if(count($countries))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $countries->links() }}
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any pages') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
