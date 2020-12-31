<!doctype html>
<html lang="{{ app()->getLocale() }}" @if ( app()->getLocale() == 'ar' ) dir='rtl' @endif >

    <head>
        @include('minible.title-meta')
        @include('minible.head')
  </head>

    <body class="authentication-bg">
        {{-- <div class="col-md-3 col-xs-4" style="text-align: left;"> --}}
            <select class=" input-sm" id="change_lang" style="margin: 10px;">
            @foreach(config('constants.langs') as $key => $val)
                <option value="{{$key}}" 
                    @if( (empty(request()->lang) && config('app.locale') == $key) 
                    || request()->lang == $key) 
                        selected 
                    @endif
                >
                    {{$val['full_name']}}
                </option>
            @endforeach
            </select>
        {{-- </div> --}}
        @inject('request', 'Illuminate\Http\Request')
        @if (session('status'))
            <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
        @endif


        @yield('content')
        @include('minible.vendor-scripts')
        
        @yield('javascript')
    </body>
</html>