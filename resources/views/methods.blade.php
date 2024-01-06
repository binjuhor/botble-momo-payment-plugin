@if (setting('payment_momo_status') == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_momo"
               @if ($selecting == MOMO_PAYMENT_METHOD_NAME) checked @endif
               value="momo" data-bs-toggle="collapse" data-bs-target=".payment_momo_wrap" data-toggle="collapse" data-target=".payment_momo_wrap" data-parent=".list_payment_method">
        <label for="payment_momo" class="text-start">{{ setting('payment_momo_name', trans('plugins/momo::momo.payment_via_momo')) }}</label>
        <div class="payment_momo_wrap payment_collapse_wrap collapse @if ($selecting == MOMO_PAYMENT_METHOD_NAME) show @endif" style="padding: 15px 0;">
            <p>{!! BaseHelper::clean(setting('payment_momo_description')) !!}</p>
        </div>
    </li>
@endif
