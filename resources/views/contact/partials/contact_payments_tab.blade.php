<table class="table table-bordered" 
id="contact_payments_table">
    <thead>
        <tr>
            <th>@lang('lang_v1.paid_on')</th>
            <th>@lang('purchase.ref_no')</th>
            <th>@lang('sale.amount')</th>
            <th>@lang('lang_v1.payment_method')</th>
            <th>@lang('account.payment_for')</th>
            <th>@lang('messages.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
            @php
                $count_child_payments = count($payment->child_payments);
            @endphp
            @include('contact.partials.payment_row', compact('payment', 'count_child_payments', 'payment_types'))

            @if($count_child_payments > 0)
                @foreach($payment->child_payments as $child_payment)
                    @include('contact.partials.payment_row', ['payment' => $child_payment, 'count_child_payments' => 0, 'payment_types' => $payment_types, 'parent_payment_ref_no' => $payment->payment_ref_no])
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
<div class="text-right" style="width: 100%;" id="contact_payments_pagination">{{ $payments->links() }}</div>

