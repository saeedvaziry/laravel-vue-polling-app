<?php

function handle_front_exception(\Exception $e)
{
    if (env('APP_ENV') == 'local') {
        return response()->json([
            'error'       => 'error',
            'message'     => $e->getMessage(),
            'file'        => $e->getFile(),
            'code'        => $e->getCode(),
            'line'        => $e->getLine(),
            'description' => $e->getTrace()
        ], 500);

    }

    return response()->json([
        'error'   => 'error',
        'message' => __('خطا')
    ], 500);
}

function ip_to_country($ip)
{
    $ip = ip2long($ip);
    $country = DB::table('ip_addresses')->where('ip_from', '<=', $ip)->where('ip_to', '>=', $ip)->first();

    if ($country) {
        return $country->country_code;
    }

    return '-';
}

function poll_token_generator($poll, $user)
{
    return md5($poll->id . '_' . $poll->created_at . '_' . $user->id . '_' . $user->created_at);
}