<?php namespace EvolutionCMS\Example;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Seiger\sCommerce\Facades\sCommerce;

class Helper
{
    public function backcallExample()
    {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            die(json_encode(['errors' => $validator->errors()->messages()]));
        }

        sCommerce::notifyEmail(
            explode(',', sCommerce::config('notifications.email_addresses', '')),
            "notifications/email/adminCallback.blade.php",
            $validator->validated()
        );

        die(json_encode(['success' => true]));
    }
}