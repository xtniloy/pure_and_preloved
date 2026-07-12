@php
    $footerContent = \App\Models\Setting::getJson('footer_content', []);
    $footerSocialLinks = \App\Support\Socials::links();
    $footerLinkHref = function ($url) {
        $url = (string) $url;
        return \Illuminate\Support\Str::startsWith($url, ['http://', 'https://', '#', 'mailto:', 'tel:']) ? $url : url($url);
    };
@endphp
<div class="footer-area">
    <div class="footer-container">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-md-30px mb-lm-30px">
                        <div class="single-wedge">
                            <div class="footer-logo">
                                <a href="{{route('home')}}"><img class="img-responsive-footer" src="{{asset('assets/images/logo/logo.jpg.png')}}" alt="Pure & Preloved" /></a>
                            </div>
                            @if(!empty($footerContent['about_text']))
                                <p class="text-infor">{{ $footerContent['about_text'] }}</p>
                            @endif
                            <div class="need_help">
                                @if(!empty($footerContent['address']))
                                    <p class="add"><span class="address">Address:</span> {{ $footerContent['address'] }}</p>
                                @endif
                                @if(!empty($footerContent['email']))
                                    <p class="mail"><span class="email">Email:</span> <a href="mailto:{{ $footerContent['email'] }}">{{ $footerContent['email'] }}</a></p>
                                @endif
                                @if(!empty($footerContent['phone']))
                                    <p class="phone"><span class="call us">Call Us:</span> <a href="tel:{{ $footerContent['phone'] }}"> {{ $footerContent['phone'] }}</a></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!empty($footerContent['info_links']))
                        <div class="col-md-6 col-lg-2 col-sm-6 mb-md-30px mb-lm-30px">
                            <div class="single-wedge">
                                <h4 class="footer-herading">Information</h4>
                                <div class="footer-links">
                                    <ul>
                                        @foreach($footerContent['info_links'] as $footerLink)
                                            <li><a href="{{ $footerLinkHref($footerLink['url'] ?? '#') }}">{{ $footerLink['label'] ?? '' }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($footerContent['custom_links']))
                        <div class="col-md-6 col-lg-2 col-sm-6 mb-sm-30px mb-lm-30px">
                            <div class="single-wedge">
                                <h4 class="footer-herading">CUSTOM LINKS</h4>
                                <div class="footer-links">
                                    <ul>
                                        @foreach($footerContent['custom_links'] as $footerLink)
                                            <li><a href="{{ $footerLinkHref($footerLink['url'] ?? '#') }}">{{ $footerLink['label'] ?? '' }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @php
                        $footerBlogPosts = \App\Models\BlogPost::published()
                            ->with(['featuredImage', 'author'])
                            ->orderByDesc('published_at')
                            ->take(4)
                            ->get();
                    @endphp
                    @if($footerBlogPosts->count())
                        <div class="col-md-6 col-lg-4 ">
                            <div class="single-wedge">
                                <h4 class="footer-herading">From Our Blog</h4>
                                <div class="footer-blog-slider">
                                    <div class="footer-blog-slider-wrapper slider-nav-style-3 ">
                                        @foreach($footerBlogPosts->chunk(2) as $footerPostChunk)
                                            <!-- Single-item -->
                                            <div class="single-slider-item">
                                                @foreach($footerPostChunk as $footerPost)
                                                    <div class="footer-blog-post {{ $loop->first ? 'd-flex mb-30px' : '' }}">
                                                        <div class="footer-blog-post-top">
                                                            <div class="post-thumbnail">
                                                                <a href="{{ route('blog.show', $footerPost->slug) }}">
                                                                    <img src="{{ $footerPost->featuredImage->public_url ?? asset('assets/images/blog-image/blog-1.jpg') }}" alt="{{ $footerPost->title }}" loading="lazy" decoding="async">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="footer-blog-content">
                                                            <h4><a href="{{ route('blog.show', $footerPost->slug) }}">{{ $footerPost->title }}</a></h4>
                                                            <div class="footer-blog-meta">
                                                                <span class="autor">Posted by <a href="#">{{ $footerPost->author->name ?? 'Admin' }}</a> </span>
                                                                <span class="date">{{ $footerPost->display_date->format('M d,Y') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- Single-item end -->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="footer-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="footer-paymet-warp d-flex">
                            <div class="heading-info">Payment:</div>
                            <div class="payment-way"><img class="payment-img img-responsive" src="{{ asset('assets/images/icons/payment.png') }}" alt="" loading="lazy" decoding="async" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if(count($footerSocialLinks))
                            <div class="footer-social-icon d-flex">
                                <div class="heading-info">Follow Us:</div>
                                <div class="social-icon">
                                    <ul>
                                        @foreach($footerSocialLinks as $social)
                                            <li class="{{ $social['platform'] }}">
                                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener"><i class="{{ $social['icon'] }}"></i></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-tags">
            <div class="container">
                <div class="row">
                    @if(!empty($footerContent['tag_links']))
                        <div class="col-md-12">
                            <div class="tag-content">
                                <ul>
                                    @foreach($footerContent['tag_links'] as $footerLink)
                                        <li><a href="{{ $footerLinkHref($footerLink['url'] ?? '#') }}">{{ $footerLink['label'] ?? '' }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 text-center">
                        <p class="copy-text">{!! $footerContent['copyright'] ?? '' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
