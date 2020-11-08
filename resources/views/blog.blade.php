@extends('layouts.base')

@section('title', 'Blog\'s feed')

@section('content')
    <section class="blog-feed">
        <div class="filter-button drop-down-trigger">
            <img src="{{ asset('/images/icons/filter_icon.svg')}}" alt="Filter icon">
        </div>
        <aside class="filter">
            <form action="/posts">
                @csrf
                <div class="filter-sections">
                    <div class="categories-filter-container">
                        <span class="filter-section-title">Categories</span>
                        @foreach ($categories as $category)
                            <label>
                                <input @if (in_array($category->id, $filterData['categories']))
                                    checked
                                @endif type="checkbox" class="category-filter"  name="categories[]" value="{{ $category->id }}">
                                <span>{{ $category->title }}</span>
                            </label>
                        @endforeach
                    </div>
    
                    <div>
                        <span class="filter-section-title">Order by</span>
                        <label>
                            <input @if ($filterData['order']['by'] === 'votes')
                                checked
                            @endif type="radio" name="order[by]" value="votes">
                            <span>Votes</span>
                        </label>

                        <label>
                            <input @if ($filterData['order']['by'] === 'created_at')
                                checked
                            @endif type="radio" name="order[by]" value="created_at">
                            <span>Post date</span>
                        </label>

                        <label>
                            <input @if ($filterData['order']['by'] === 'user_id')
                                checked
                            @endif type="radio" name="order[by]" value="user_id">
                            <span>Author</span>
                        </label>
                    </div>

                    <div>
                        <span class="filter-section-title">Order type</span>
                        <label>
                            <input @if ($filterData['order']['type'] === 'asc')
                                checked
                            @endif type="radio" name="order[type]" value="asc">
                            <span>Ascending</span>
                        </label>

                        <label>
                            <input @if ($filterData['order']['type'] === 'desc')
                                checked
                            @endif type="radio" name="order[type]" value="desc">
                            <span>Descending</span>
                        </label>
                    </div>
                </div>
                <div class="form-submit-button">
                    <button type="submit">Filter</button>
                </div>
            </form>
        </aside>

        @foreach ($posts as $post)
        <article>
        <a class="blog-title" href="/posts/{{ $post->id }}"><h2>{{ $post->title }}</h2></a>
            
            @if ($post->image !== null)
                <div class="post-image">
                    <img src="{{ asset('images/blog/' . $post->image) }}" alt="">
                </div>
            @endif
            
            <div class="post-meta">
                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/like_icon.svg') }}" alt="Heart icon" title="Like">
                    </div>
                    <span>{{ $post->votes }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/user_icon.svg') }}" alt="User icon" title="Posted by">
                    </div>
                    <span>{{ $post->user->full_name }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/calendar_icon.svg') }}" alt="Heart icon" title="Posted on">
                    </div>
                    
                    <span>{{ $post->date }}</span>
                </div>

                <div>
                    <div class="post-meta-icon">
                        <img src="{{ asset('images/icons/category_icon.svg') }}" alt="Grid icon" title="Category">
                    </div>
                    
                    <span>{{ $post->category->title }}</span>
                </div>
            </div>
            <p>{{ $post->excerpt }}</p>
            <div class="read-more-button">
                <a href="{{ $post->specificResourcePath() }}">Read more</a>
            </div>
        </article>
        @endforeach

        {{ $posts->withQueryString()->onEachSide(4)->links('vendor.pagination.default') }}


    </section>

@endsection

@section('scripts')
    <script type="text/javascript">
        function downloadJSAtOnload() {

            const element = document.createElement("script");
            element.src = "{{ asset('assets/js/main.js') }}";
            document.body.appendChild(element);

        }

        if (window.addEventListener)
            window.addEventListener("load", downloadJSAtOnload, false);
        else if (window.attachEvent)
            window.attachEvent("onload", downloadJSAtOnload);
        else window.onload = downloadJSAtOnload;
    </script>
@endsection