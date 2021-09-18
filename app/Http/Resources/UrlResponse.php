<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Responsable;

class UrlResponse implements Responsable
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function toResponse($request)
    {
        $shortUrl = route('shorturl.redirect', ['code' => $this->url->code]);

        if ($request->wantsJson()) {
            return response([
                'id'          => $this->url->id,
                'code'        => $this->url->code,
                'title'       => $this->url->title,
                'description' => $this->url->description,
                'url'         => $this->url->url,
                'short_url'   => $shortUrl,
                'counter'     => $this->url->counter,
                'expires_at'  => $this->url->expires_at,
                'user_id'     => optional($this->url->user)->id,
            ], 201);
        }

        return back()
            ->with('short_url', $shortUrl);
    }
}
