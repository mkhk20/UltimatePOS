<!-- Custom Tabs -->
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach($templates as $key => $value)
            <li @if($loop->index == 0) class="active" @endif>
                <a href="#cn_{{$key}}" data-toggle="tab" aria-expanded="true">
                {{$value['name']}} </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($templates as $key => $value)
            <div class="tab-pane @if($loop->index == 0) active @endif" id="cn_{{$key}}">
                <div class="row">
                <div class="col-md-12">
                    @if(!empty($value['extra_tags']))
                        <strong>@lang('lang_v1.available_tags'):</strong>
                    <p class="text-primary">{{implode(', ', $value['extra_tags'])}}</p>
                    @endif
                    @if(!empty($value['help_text']))
                    <p class="help-block">{{$value['help_text']}}</p>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label($key . '_subject',
                        __('lang_v1.email_subject').':') !!}
                        {!! Form::text('template_data[' . $key . '][subject]', 
                        $value['subject'], ['class' => 'form-control'
                        , 'placeholder' => __('lang_v1.email_subject'), 'id' => $key . '_subject']); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label($key . '_cc',
                        'CC:') !!}
                        {!! Form::email('template_data[' . $key . '][cc]', 
                        $value['cc'], ['class' => 'form-control'
                        , 'placeholder' => 'CC', 'id' => $key . '_cc']); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label($key . '_bcc',
                        'BCC:') !!}
                        {!! Form::email('template_data[' . $key . '][bcc]', 
                        $value['bcc'], ['class' => 'form-control'
                        , 'placeholder' => 'BCC', 'id' => $key . '_bcc']); !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label($key . '_email_body',
                        __('lang_v1.email_body').':') !!}
                        {!! Form::textarea('template_data[' . $key . '][email_body]', 
                        $value['email_body'], ['class' => 'form-control ckeditor'
                        , 'placeholder' => __('lang_v1.email_body'), 'id' => $key . '_email_body', 'rows' => 6]); !!}
                    </div>
                </div>
                <div class="col-md-12 @if($key == 'send_ledger') hide @endif">
                    <div class="form-group">
                        {!! Form::label($key . '_sms_body',
                        __('lang_v1.sms_body').':') !!}
                        {!! Form::textarea('template_data[' . $key . '][sms_body]', 
                        $value['sms_body'], ['class' => 'form-control'
                        , 'placeholder' => __('lang_v1.sms_body'), 'id' => $key . '_sms_body', 'rows' => 6]); !!}
                    </div>
                </div>
                @if($key == 'new_sale')
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="checkbox-inline">
                                {!! Form::checkbox('template_data[' . $key . '][auto_send]', 1, $value['auto_send'], ['class' => 'input-icheck']); !!} @lang('lang_v1.autosend_email')
                            </label>
                            <label class="checkbox-inline">
                                {!! Form::checkbox('template_data[' . $key . '][auto_send_sms]', 1, $value['auto_send_sms'], ['class' => 'input-icheck']); !!} @lang('lang_v1.autosend_sms')
                            </label>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        @endforeach
    </div>
</div>