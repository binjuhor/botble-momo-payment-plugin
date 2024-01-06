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
                'payment_momo_name',
                'payment_momo_description',
                'payment_momo_client_id',
                'payment_momo_client_secret',
                'payment_momo_mode',
                'payment_momo_status',
            ])
            ->delete();
    }
}
