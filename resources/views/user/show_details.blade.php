@php
    $custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
<div class="row">
	<div class="col-md-12">
		<div class="col-md-12">
			<h4>@lang('lang_v1.more_info')</h4>
		</div>
		<div class="col-md-4">
			<p><strong>@lang( 'lang_v1.dob' ):</strong> @if(!empty($user->dob)) {{@format_date($user->dob)}} @endif</p>
			<p><strong>@lang( 'lang_v1.gender' ):</strong> @if(!empty($user->gender)) @lang('lang_v1.' .$user->gender) @endif</p>
			<p><strong>@lang( 'lang_v1.marital_status' ):</strong> @if(!empty($user->marital_status)) @lang('lang_v1.' .$user->marital_status) @endif</p>
			<p><strong>@lang( 'lang_v1.blood_group' ):</strong> {{$user->blood_group ?? ''}}</p>
			<p><strong>@lang( 'lang_v1.contact_no' ):</strong> {{$user->contact_number ?? ''}}</p>
		</div>
		<div class="col-md-4 text-center">
			<h4> @lang( 'lang_v1.social_media_accounts' ) </h4>
			<a href="#">
				<svg style="width:24px;height:24px" viewBox="0 0 24 24">
					<path fill="currentColor" d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
				</svg>
			</a>
			<a href="#">
				<svg style="width:24px;height:24px" viewBox="0 0 24 24">
					<path fill="currentColor" d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z" />
				</svg>
			</a>
			{{-- <p><strong>@lang( 'lang_v1.fb_link' ):</strong> {{$user->fb_link ?? ''}}</p>
			<p><strong>@lang( 'lang_v1.twitter_link' ):</strong> {{$user->twitter_link ?? ''}}</p>
			<p><strong>@lang( 'lang_v1.social_media', ['number' => 1] ):</strong> {{$user->social_media_1 ?? ''}}</p>
			<p><strong>@lang( 'lang_v1.social_media', ['number' => 2] ):</strong> {{$user->social_media_2 ?? ''}}</p> --}}
		</div>
		<div class="col-md-4">
			<p><strong>{{ $custom_labels['user']['custom_field_1'] ?? __('lang_v1.user_custom_field1' )}}:</strong> {{$user->custom_field_1 ?? ''}}</p>
			<p><strong>{{ $custom_labels['user']['custom_field_2'] ?? __('lang_v1.user_custom_field2' )}}:</strong> {{$user->custom_field_2 ?? ''}}</p>
			<p><strong>{{ $custom_labels['user']['custom_field_3'] ?? __('lang_v1.user_custom_field3' )}}:</strong> {{$user->custom_field_3 ?? ''}}</p>
			<p><strong>{{ $custom_labels['user']['custom_field_4'] ?? __('lang_v1.user_custom_field4' )}}:</strong> {{$user->custom_field_4 ?? ''}}</p>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-4">
			<p><strong>@lang('lang_v1.id_proof_name'):</strong>
			{{$user->id_proof_name ?? ''}}</p>
		</div>
		<div class="col-md-4">
			<p><strong>@lang('lang_v1.id_proof_number'):</strong>
			{{$user->id_proof_number ?? ''}}</p>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="col-md-6">
			<strong>@lang('lang_v1.permanent_address'):</strong><br>
			<p>{{$user->permanent_address ?? ''}}</p>
		</div>
		<div class="col-md-6">
			<strong>@lang('lang_v1.current_address'):</strong><br>
			<p>{{$user->current_address ?? ''}}</p>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="col-md-12">
			<h4>@lang('lang_v1.bank_details'):</h4>
		</div>
		@php
			$bank_details = !empty($user->bank_details) ? json_decode($user->bank_details, true) : [];
		@endphp
		<div class="col-md-4">
			<p><strong>@lang('lang_v1.account_holder_name'):</strong> {{$bank_details['account_holder_name'] ?? ''}}</p>
			<p><strong>@lang('lang_v1.account_number'):</strong> {{$bank_details['account_number'] ?? ''}}</p>
		</div>
		<div class="col-md-4">
			<p><strong>@lang('lang_v1.bank_name'):</strong> {{$bank_details['bank_name'] ?? ''}}</p>
			<p><strong>@lang('lang_v1.bank_code'):</strong> {{$bank_details['bank_code'] ?? ''}}</p>
		</div>
		<div class="col-md-4">
			<p><strong>@lang('lang_v1.branch'):</strong> {{$bank_details['branch'] ?? ''}}</p>
			<p><strong>@lang('lang_v1.tax_payer_id'):</strong> {{$bank_details['tax_payer_id'] ?? ''}}</p>
		</div>
		@if(!empty($view_partials))
	      @foreach($view_partials as $partial)
	        {!! $partial !!}
	      @endforeach
	    @endif
	</div>
</div>