<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlResponse;
use App\Repositories\UrlRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UrlController extends Controller
{
    protected $url_repo;

    public function __construct(
        UrlRepository $urlRepository
    )
    {
        $this->url_repo = $urlRepository;
    }

    /**
     * หน้าสร้าง
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        return view('frontend.urls.create');
    }

    /**
     * บันทึกข้อมูล Short Url
     * @param UrlRequest $request
     * @return UrlResponse
     */
    public function store(UrlRequest $request): UrlResponse
    {
        $url = $this->url_repo->store($request);

        return new UrlResponse($url);
    }

    /**
     * แก้ไขข้อมูล Short Url
     * @param UrlRequest $request
     * @param $id
     * @return UrlResponse
     */
    public function update(UrlRequest $request, $id): UrlResponse
    {
        $shortUrl = $this->url_repo->find($id);

        Cache::forget("url.{$shortUrl['code']}");

        $url = $this->url_repo->valuable($shortUrl, $request);

        return new UrlResponse($url);
    }

    /**
     * ลบข้อมูล Short Url
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $shortUrl = $this->url_repo->find($id);

        Cache::forget("url.{$shortUrl['code']}");

        $shortUrl->delete();

        return back()
            ->with('short_url', true);
    }
}
