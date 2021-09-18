<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedirectController extends Controller
{
    protected $url_repo;

    public function __construct(
        UrlRepository $urlRepository
    )
    {
        $this->url_repo = $urlRepository;
    }

    public function redirect($code)
    {
        $url = Cache::rememberForever("url.$code", function () use ($code) {
            return $this->url_repo->findUrlCode($code);
        });

        if ($url !== null) {

            if ($url->hasExpired()) {
                abort(410);
            }

            $url->increment('counter');

            return redirect()->away($url->url, $url->couldExpire() ? 302 : 301);
        }

        abort(404);
    }
}
