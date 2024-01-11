<?php

namespace Binjuhor\MoMo;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::query()
            ->whereIn('key', [
                'payment_via_momo',
                'momo_description',
                'after_service_registration_msg',
                'enter_partner_code_and_secret',
                'momo_access_key',
                'momo_secret_key',
                'momo_public_key',
                'momo_mode',
            ])
            ->delete();
    }
}
