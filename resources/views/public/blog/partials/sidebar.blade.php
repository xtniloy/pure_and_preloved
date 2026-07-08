<div class="left-sidebar shop-sidebar-wrap">
    <!-- Sidebar single item -->
    <div class="sidebar-widget">
        <h3 class="sidebar-title"><span>Search</span></h3>
        <div class="search-widget">
            <form action="{{ route('blog.index') }}" method="get">
                <input placeholder="Search blog posts ..." type="text" name="q" value="{{ request('q') }}" />
                <button type="submit"><i class="ion-ios-search-strong"></i></button>
            </form>
        </div>
    </div>
    <!-- Sidebar single item -->
    @if($sidebarCategories->count())
        <div class="sidebar-widget mt-40px">
            <h3 class="sidebar-title"><span>Categories</span></h3>
            <div class="category-post">
                <ul>
                    @foreach($sidebarCategories as $sidebarCategory)
                        <li><a href="{{ route('blog.index', ['category' => $sidebarCategory->slug]) }}">{{ $sidebarCategory->name }} ({{ $sidebarCategory->posts_count }})</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!-- Sidebar single item -->
    @if($recentPosts->count())
        <div class="sidebar-widget mt-40px">
            <h3 class="sidebar-title"><span>Recent Post</span></h3>

            <div class="recent-post-widget">
                @foreach($recentPosts as $recentPost)
                    <div class="recent-single-post d-flex">
                        <div class="thumb-side {{ $loop->last ? 'm-0px' : '' }}">
                            <a href="{{ route('blog.show', $recentPost->slug) }}"><img src="{{ $recentPost->featuredImage->public_url ?? asset('assets/images/blog-image/blog-1.jpg') }}" alt="{{ $recentPost->title }}" /></a>
                        </div>
                        <div class="media-side">
                            <h5><a href="{{ route('blog.show', $recentPost->slug) }}">{{ $recentPost->title }}</a></h5>
                            <span class="date">{{ strtoupper($recentPost->display_date->format('F d, Y')) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <!-- Sidebar single item -->
    @if($sidebarTags->count())
        <div class="sidebar-widget mt-40px">
            <h3 class="sidebar-title"><span>Tags</span></h3>

            <div class="sidebar-widget-tag">
                <ul>
                    @foreach($sidebarTags as $sidebarTag)
                        <li><a href="{{ route('blog.index', ['tag' => $sidebarTag->slug]) }}">{{ $sidebarTag->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!-- Sidebar single item -->
</div>
