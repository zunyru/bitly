@extends('frontend.app')

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet"
          media="all">
    <style>
        .error{
            color: red;
        }
    </style>
@endpush
@section('content')

    <section class="text-gray-600 body-font">
        <header class="text-gray-600 body-font">
            <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
                <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                    <img class="w-16" src="{{ Storage::url(setting('site.logo')) }}">
                    <span class="ml-3 text-xl">{{ setting('site.title') }}</span>
                </a>
                <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">

                </nav>
                <a href="{{ route('voyager.login') }}"
                   class="inline-flex text-white items-center bg-red-500 border-0 py-1 px-3 focus:outline-none hover:bg-red-700 rounded text-base mt-4 md:mt-0">
                    Login By admin
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" class="w-4 h-4 ml-1" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </header>
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    THE LINK KNOWS ALL. SO CAN YOU.
                </h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base">

                </p>
            </div>
            <form method="POST"
                  id="Form"
                  action="{{ route('shorturl.url.store') }}">
                @csrf
                <div class="flex lg:w-2/3 w-full sm:flex-row flex-col mx-auto sm:space-y-0 space-y-4 sm:px-0 items-end">
                    <div class="relative flex-grow w-full">
                        <label for="code" class="font-bold">URL</label>
                        <input type="text"
                               id="url"
                               name="url"
                               placeholder="Paste a link to shorten it."
                               aria-label="Paste a link to shorten it."
                               value="{{ old('url') }}"
                               class="w-full bg-gray-50 lg:rounded-l rounded border border-gray-300 focus:border-red-500  focus:ring-2 focus:ring-red-200 text-base outline-none text-gray-700 py-1 px-4 py-3 leading-8 transition-colors duration-200 ease-in-out {{ $errors->has('url') ? 'border-red-500' : '' }}">
                    </div>
                    <button
                            type="submit"
                            class="text-white bg-red-500 border-0 py-4 px-8 focus:outline-none hover:bg-red-600 lg:rounded-r  rounded text-lg">
                        SHORTEN
                    </button>
                </div>
                <div class="flex lg:w-2/3 w-full mx-auto">
                <label id="url-error" class="error" for="url"></label>
                </div>
                @if ($errors->has('url'))
                    <div class="flex lg:w-2/3 w-full mx-auto">
                        <small id="url-error"
                               class="flex flex-col text-red-600 text-xl">
                            {{ $errors->first('url') }}
                        </small>
                    </div>
                @endif
                <div class="flex flex-col my-8 lg:w-2/3 w-full mx-auto">
                    <label for="code" class="font-bold">Custom alias (optional)</label>
                    <input type="text"
                           id="code"
                           name="code"
                           placeholder="Set your custom alias."
                           aria-label="Set your custom alias."
                           value="{{ old('code') }}"
                           class="w-full bg-gray-50 rounded-l border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 text-base outline-none text-gray-700 py-1 px-4 py-3 leading-8 transition-colors duration-200 ease-in-out {{ $errors->has('code') ? 'border-red-500' : '' }}">
                </div>
                @if ($errors->has('code'))
                    <div class="flex lg:w-2/3 w-full mx-auto">
                        <small id="url-error"
                               class="flex flex-col text-red-600 text-xl">
                            {{ $errors->first('code') }}
                        </small>
                    </div>
                @endif
            </form>
            @if (session('short_url'))
                <div class="flex lg:flex-row flex-col mt-8 mb-3 lg:w-2/3 w-full mx-auto">
                    <div class="pr-3">
                        Your shortened url is:
                    </div>
                    <div>
                        <a class="text-xl font-bold text-blue-500"
                           target="_blank"
                           href="{{ session('short_url') }}"
                           title="your shortened url">{{ session('short_url') }}</a>
                    </div>
                    <div class="mt-8 lg:mt-0">
                        <a class="copy-clipboard ml-4 w-1/5 bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 border border-red-500 rounded shadow"
                           href="javascript:void(0);"
                           data-clipboard-text="{{ session('short_url') }}">
                            Copy link</a>
                    </div>
                </div>
                <div class="flex lg:w-2/3 w-full mx-auto text-sm text-red-700">
                    Url นี้ จะหมดอายุอีก 1 เดือน
                </div>
        @endif
    </section>
@endsection
@push('scripts')
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script src="{{ asset('plugin/js-toast/toast.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        var clipboard = new ClipboardJS('.copy-clipboard');

        clipboard.on('success' , function (e) {
            e.trigger.innerText = 'Copied!';
            const options = {
                style: {
                    main: {
                        background: "#10b981" ,
                        color: "#fff" ,
                    } ,
                } ,
            };
            iqwerty.toast.toast('Copied successfully!' , options);
        });

        $(document).ready(function () {
            $("#Form").validate({
                rules: {
                    url: {
                        required: true ,
                        url: true ,
                    }
                }
            });
        });
    </script>
@endpush