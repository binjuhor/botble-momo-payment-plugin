@php $payPalStatus = setting('payment_momo_status'); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img style="height: 40px; filter: grayscale(1)" src="{{ url('vendor/core/plugins/momo/images/logo.jpg') }}" alt="MOMO">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://momo.vn" target="_blank">MOMO</a>
                    <p>{{ trans('plugins/momo::momo.momo_description') }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div class="payment-name-label-group  @if ($payPalStatus== 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label class="ws-nm inline-display method-name-label">{{ setting('payment_momo_name') }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($payPalStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($payPalStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="momo-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', MOMO_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'MoMo']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'MoMo']) }}:</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href=" https://business.momo.vn" target="_blank">
                                        {{ trans('plugins/payment::payment.service_registration', ['name' => 'MoMo']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ trans('plugins/momo::momo.after_service_registration_msg') }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ trans('plugins/momo::momo.enter_partner_code_and_secret') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="momo_name">{{ trans('plugins/payment::payment.method_name', ['name'=>'MoMo']) }}</label>
                            <input type="text" class="next-input input-name" name="payment_momo_name" id="momo_name" data-counter="400" value="{{ setting('payment_momo_name', trans('plugins/payment::payment.pay_online_via', ['name' => 'MoMo'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_momo_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_momo_description" id="payment_momo_description">{{ get_payment_setting('description', 'momo', __('You will be redirected to MoMo to complete the payment.')) }}</textarea>
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://business.momo.vn">MoMo</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="momo_partner_code">{{ trans('plugins/momo::momo.momo_partner_code') }}</label>
                            <input type="text" class="next-input" name="momo_partner_code" id="momo_partner_code" value="{{ setting('momo_partner_code') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="momo_access_key">{{ trans('plugins/momo::momo.momo_access_key') }}</label>
                            <input type="text" class="next-input" name="momo_access_key" id="momo_access_key" value="{{ setting('momo_access_key') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="momo_secret_key">{{ trans('plugins/momo::momo.momo_secret_key') }}</label>
                            <input type="text" class="next-input" name="momo_secret_key" id="momo_secret_key" value="{{ setting('momo_secret_key') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_momo_mode">{{ trans('plugins/momo::momo.momo_mode') }}</label>
                            <div class="input-option">
                                <select name="payment_momo_mode" class="next-input">
                                    <option {{ setting('payment_momo_mode') == 0 ? 'selected' : ''}} value="0">{{ __('Sandbox') }}</option>
                                    <option {{ setting('payment_momo_mode') == 1 ? 'selected' : '' }} value="1">{{ __('Production') }}</option>
                                </select>
                            </div>
                        </div>
                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, 'momo') !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($payPalStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button class="btn btn-info save-payment-item btn-text-trigger-save @if ($payPalStatus == 1) hidden @endif" type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button class="btn btn-info save-payment-item btn-text-trigger-update @if ($payPalStatus == 0) hidden @endif" type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
