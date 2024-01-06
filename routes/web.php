<?php

use Binjuhor\MoMo\Http\Controllers\MoMoController;

Route::group(['controller' => MoMoController::class, 'middleware' => ['web', 'core']], function () {
    Route::get('payment/momo/callback', 'getCallback')->name('payments.momo.callback');
    Route::get('payment/momo/ipn', 'getIPN')->name('payments.momo.ipn');
});
