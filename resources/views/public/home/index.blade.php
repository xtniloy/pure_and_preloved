@extends('public.layouts.main')
@section('title', $seo['meta_title'] ?? 'Pure & Preloved')
@section('meta_description', $seo['meta_description'] ?? '')
@section('meta')
    @if(!empty($seo['meta_keywords']))
        <meta name="keywords" content="{{ $seo['meta_keywords'] }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $seo['meta_title'] ?? 'Pure & Preloved' }}">
    @if(!empty($seo['meta_description']))
        <meta property="og:description" content="{{ $seo['meta_description'] }}">
    @endif
    @if(!empty($seo['meta_image_url']))
        <meta property="og:image" content="{{ $seo['meta_image_url'] }}">
    @endif
@endsection
@section('content')
    {{-- Sections are managed in Admin > Homepage. Hero stays first; body sections
         are ordered/hidden there. --}}
    @foreach($sections as $section)
        @includeIf('public.sections.' . $section->type, ['data' => $section->data ?? []])
    @endforeach

    {{-- Newsletter (fixed above the footer) --}}
    <div class="news-letter-area bg-black">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4 mb-md-30px mb-lm-30px">
                    <div class="title-newsletter">
                        <h2>Sign Up For Newsletters</h2>
                        <p class="des">Be the First to Know. Sign up for newsletter today !</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div id="mc_embed_signup" class="subscribe-form">
                        <form
                            id="mc-embedded-subscribe-form"
                            class="validate"
                            novalidate=""
                            target="_blank"
                            name="mc-embedded-subscribe-form"
                            method="post"
                            action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                        >
                            <div id="mc_embed_signup_scroll" class="mc-form">
                                <input class="email" type="email" required="" placeholder="Enter your email here.." name="EMAIL" value="" />
                                <div class="mc-news" aria-hidden="true">
                                    <input type="text" value="" tabindex="-1" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef" />
                                </div>
                                <div class="clear">
                                    <input id="mc-embedded-subscribe" class="button" type="submit" name="subscribe" value="Sign Up" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
