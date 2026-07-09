{{-- Social follow strip — links are managed in Admin > Social Links --}}
@php $socialLinks = \App\Support\Socials::links(); @endphp
@if(count($socialLinks))
    <div class="follow-us-area">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="px-3"> <strong>FOLLOW</strong> US ON SOCIAL!</h2>
            </div>

            <div class="social-icon">
                <ul class="d-flex justify-content-center align-items-center">
                    @foreach($socialLinks as $social)
                        <li class="{{ $social['platform'] }}">
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"><i class="{{ $social['icon'] }}"></i></a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
