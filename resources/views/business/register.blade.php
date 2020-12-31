@extends('layouts.auth2')
@section('title', __('lang_v1.register'))

@section('content')
<style>
    .right-col{
        background-color: rgba(91,140,232,.25);
    }
    .wizard > .content{
        background: #2184be !important;
    }
</style>
<div class="login-form col-md-12 col-xs-12 right-col-content-register">
    
    <p class="form-header text-muted">@lang('business.register_and_get_started_in_minutes')</p>

    {!! Form::open(['url' => route('business.postRegister'), 'method' => 'post', 
                            'id' => 'business_register_form','files' => true ]) !!}
        @include('business.partials.register_form')
        {!! Form::hidden('package_id', $package_id); !!}
    {!! Form::close() !!}
</div>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('#change_lang').change( function(){
            window.location = "{{ route('business.getRegister') }}?lang=" + $(this).val();
        });
    })
</script>
@endsection