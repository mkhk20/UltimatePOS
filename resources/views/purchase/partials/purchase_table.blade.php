<div class="table-responsive">
    <table class="table table-bordered table-striped ajax_view" id="purchase_table">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>
                <th>@lang('messages.date')</th>
                <th>@lang('purchase.ref_no')</th>
                <th>@lang('purchase.location')</th>
                <th>@lang('purchase.supplier')</th>
                <th>@lang('purchase.purchase_status')</th>
                <th>@lang('purchase.payment_status')</th>
                <th>@lang('purchase.grand_total')</th>
                <th>@lang('purchase.payment_due') &nbsp;&nbsp;<i class="fa fa-info-circle text-info no-print" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="{{ __('messages.purchase_due_tooltip')}}" aria-hidden="true"></i></th>
                <th>@lang('lang_v1.added_by')</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                <td id="footer_status_count"></td>
                <td id="footer_payment_status_count"></td>
                <td><span class="display_currency" id="footer_purchase_total" data-currency_symbol ="true"></span></td>
                <td class="text-left"><small>@lang('report.purchase_due') - <span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span><br>
                @lang('lang_v1.purchase_return') - <span class="display_currency" id="footer_total_purchase_return_due" data-currency_symbol ="true"></span>
                </small></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>