<?php

use App\Modules\Visitor\Models\VisitorModel;

function track_visitor($page = '')
{
    $request = service('request');
    $visitorModel = new VisitorModel();

    $ip = $request->getIPAddress();
    $agent = $request->getUserAgent();
    $date = date('Y-m-d');

    // ======================
    // DEVICE TYPE
    // ======================
    if ($agent->isMobile()) {
        $device = 'Mobile';
    } elseif ($agent->isBrowser()) {
        $device = 'Desktop';
    } else {
        $device = 'Other';
    }

    // ======================
    // BROWSER
    // ======================
    $browser = $agent->getBrowser() . ' ' . $agent->getVersion();

    // ======================
    // REFERER
    // ======================
    $referer = $request->getServer('HTTP_REFERER') ?? 'Direct';

    // ======================
    // SEARCH ENGINE DETECTION
    // ======================
    $searchEngine = null;
    if ($referer !== 'Direct') {
        $host = parse_url($referer, PHP_URL_HOST);

        if (str_contains($host, 'google')) {
            $searchEngine = 'Google';
        } elseif (str_contains($host, 'bing')) {
            $searchEngine = 'Bing';
        } elseif (str_contains($host, 'yahoo')) {
            $searchEngine = 'Yahoo';
        } elseif (str_contains($host, 'duckduckgo')) {
            $searchEngine = 'DuckDuckGo';
        }
    }

    // ======================
    // BOT DETECTION
    // ======================
    $userAgentString = $agent->getAgentString();
    $botKeywords = ['bot', 'crawl', 'slurp', 'spider', 'mediapartners'];

    $isBot = 0;
    $botName = null;

    foreach ($botKeywords as $keyword) {
        if (stripos($userAgentString, $keyword) !== false) {
            $isBot = 1;
            $botName = $userAgentString;
            break;
        }
    }

    // ======================
    // COUNTRY DETECTION (FREE API)
    // ======================
    $country = null;

    if ($ip !== '127.0.0.1') {
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}");
            if ($response) {
                $data = json_decode($response);
                if ($data && $data->status == 'success') {
                    $country = $data->country;
                }
            }
        } catch (\Exception $e) {
            $country = null;
        }
    }

    // ======================
    // CEK DUPLIKAT HARI INI
    // ======================
    $exist = $visitorModel
        ->where('ip_address', $ip)
        ->where('page', $page)
        ->where('visit_date', $date)
        ->first();

    if (!$exist) {
        $visitorModel->insert([
            'ip_address'   => $ip,
            'user_agent'   => $userAgentString,
            'page'         => $page,
            'device_type'  => $device,
            'browser'      => $browser,
            'referer'      => $referer,
            'search_engine'=> $searchEngine,
            'is_bot'       => $isBot,
            'bot_name'     => $botName,
            'country'      => $country,
            'visit_date'   => $date,
            'visit_time'   => date('Y-m-d H:i:s')
        ]);
    }
}