<?php

namespace App\Repositories;

use App\Models\ShorturlUrl;
use App\ShortUrl\Facades\Hasher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UrlRepository
{
    public function find($id)
    {
        return ShorturlUrl::query()->findOrFail($id);
    }

    public function store($request): ShorturlUrl
    {
        $data = new ShorturlUrl();
        return $this->valuable($data, $request);
    }

    public function valuable(ShorturlUrl $shorturlUrl, $request): ShorturlUrl
    {
        $request = (object)$request;

        $shorturlUrl->title = $request->title ?? $shorturlUrl->title;
        $shorturlUrl->description = $request->description ?? $shorturlUrl->description;
        $shorturlUrl->url = $request->url ?? $shorturlUrl->url;
        $shorturlUrl->code = $request->code ?? $shorturlUrl->code
                ? generateSlug($request->code) //แปลงเป็น url
                : Hasher::generate(); // generate code
        $shorturlUrl->user_id = optional(Auth::user())->id;

        if ($request->filled('expires_at'))
            $shorturlUrl->expires_at = Carbon::parse($request->expires_at)->toDateTimeString();
        else
            $shorturlUrl->expires_at = Carbon::now()->addMonth(1);

        $shorturlUrl->save();

        return $shorturlUrl;
    }

    public function findUrlCode($code)
    {
        return ShorturlUrl::whereCode($code)->first();
    }

}
