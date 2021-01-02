<div class="col-md-12">
	<form action="{{action('\Modules\Superadmin\Http\Controllers\SubscriptionController@confirm', [$package->id])}}" method="POST">
	 	{{ csrf_field() }}
	 	<input type="hidden" name="gateway" value="{{$k}}">
		<script
		        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
		        data-key="{{env('STRIPE_PUB_KEY')}}"
		        data-amount="{{$package->price*100}}"
		        data-name="{{env('APP_NAME')}}"
		        data-description="{{$package->name}}"
		        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
		        data-locale="auto"
		        data-currency="{{strtolower($system_currency->code)}}">
		</script>
	</form>
</div>