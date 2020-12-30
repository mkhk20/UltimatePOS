<!-- Edit Shipping Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="posShippingModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang('sale.shipping')</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_details_modal', __('sale.shipping_details') . ':*' ) !!}
				            {!! Form::textarea('shipping_details_modal', !empty($transaction->shipping_details) ? $transaction->shipping_details : '', ['class' => 'form-control','placeholder' => __('sale.shipping_details'), 'required' ,'rows' => '4']); !!}
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_address_modal', __('lang_v1.shipping_address') . ':' ) !!}
				            {!! Form::textarea('shipping_address_modal',!empty($transaction->shipping_address) ? $transaction->shipping_address : '', ['class' => 'form-control','placeholder' => __('lang_v1.shipping_address') ,'rows' => '4']); !!}
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_charges_modal', __('sale.shipping_charges') . ':*' ) !!}
				            <div class="input-group">
				                <span class="input-group-addon">
				                    <i class="fa fa-info"></i>
				                </span>
				                {!! Form::text('shipping_charges_modal', !empty($transaction->shipping_charges) ? @num_format($transaction->shipping_charges) : 0, ['class' => 'form-control input_number','placeholder' => __('sale.shipping_charges')]); !!}
				            </div>
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('shipping_status_modal', __('lang_v1.shipping_status') . ':' ) !!}
				            {!! Form::select('shipping_status_modal',$shipping_statuses, !empty($transaction->shipping_status) ? $transaction->shipping_status : null, ['class' => 'form-control','placeholder' => __('messages.please_select')]); !!}
				        </div>
				    </div>

				    <div class="col-md-6">
				        <div class="form-group">
				            {!! Form::label('delivered_to_modal', __('lang_v1.delivered_to') . ':' ) !!}
				            {!! Form::text('delivered_to_modal', !empty($transaction->delivered_to) ? $transaction->delivered_to : null, ['class' => 'form-control','placeholder' => __('lang_v1.delivered_to')]); !!}
				        </div>
				    </div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="posShippingModalUpdate">@lang('messages.update')</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.cancel')</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->