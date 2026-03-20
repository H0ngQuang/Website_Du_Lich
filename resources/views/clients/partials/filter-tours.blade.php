@foreach ($tours as $tour)
    <div class="col-xl-4 col-md-6" style="margin-bottom: 30px">
        <div class="destination-item tour-grid style-three bgc-lighter block_tours equal-block-fix" data-aos="fade-up"
            data-aos-duration="1500" data-aos-offset="50">
            <div class="image" style="position: relative;">
                @if($tour->active_discount > 0)
                    <span class="badge flash-sale-badge" style="position:absolute; top:15px; left:15px; z-index:10; background: linear-gradient(135deg, #ff416c, #ff4b2b); color:#fff; padding: 6px 12px; font-size: 13px; border-radius: 20px; box-shadow: 0 2px 8px rgba(255,65,108,0.4); animation: flashPulse 1.5s ease-in-out infinite;">
                        ⚡ Flash Sale -{{ $tour->active_discount }}%
                    </span>
                @else
                    <span class="badge bgc-pink">Featured</span>
                @endif
                <a href="#" class="heart"><i class="fas fa-heart"></i></a>
                @if($tour->images->isNotEmpty())
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0] . '') }}" alt="Tour List">
                @else
                    <img src="{{ asset('admin/assets/images/gallery-tours/default.jpg') }}" alt="Tour List">
                @endif
            </div>
            <div class="content equal-content-fix">
                <div class="destination-header">
                    <span class="location"><i class="fal fa-map-marker-alt"></i>
                        {{ $tour->destination }}</span>
                    <div class="ratting">
                        @for ($i = 0; $i < 5; $i++)
                            @if ($tour->rating && $i < $tour->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor

                    </div>
                </div>
                <h6><a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a> </h6>
                <ul class="blog-meta">
                    <li><i class="far fa-clock"></i>{{ $tour->time }}</li>
                    <li><i class="far fa-user"></i>{{ $tour->quantity }}</li>
                </ul>
                <div class="destination-footer">
                    <span class="price">
                        @if($tour->active_discount > 0)
                            @php
                                $discountedPrice = $tour->priceAdult - ($tour->priceAdult * ($tour->active_discount / 100));
                            @endphp
                            <span style="text-decoration: line-through; font-size: 0.8em; color: #888; margin-right: 5px;">{{ number_format($tour->priceAdult, 0, ',', '.') }}</span>
                            <span style="color: #e53e3e; font-weight: bold;">{{ number_format($discountedPrice, 0, ',', '.') }}</span>
                        @else
                            <span>{{ number_format($tour->priceAdult, 0, ',', '.') }}</span>
                        @endif
                        VND / người
                    </span>
                    <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}"
                        class="theme-btn style-two style-three">
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="col-lg-12">
    <ul class="pagination justify-content-center pt-15 flex-wrap pagination-tours" data-aos="fade-up"
        data-aos-duration="1500" data-aos-offset="50">
        <!-- Previous Page Link -->
        @if ($tours->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="far fa-chevron-left"></i></span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $tours->previousPageUrl() }}"><i class="far fa-chevron-left"></i></a>
            </li>
        @endif

        <!-- Page Numbers -->
        @for ($i = 1; $i <= $tours->lastPage(); $i++)
            <li class="page-item @if ($i == $tours->currentPage()) active @endif">
                <a class="page-link" href="{{ $tours->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <!-- Next Page Link -->
        @if ($tours->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $tours->nextPageUrl() }}"><i class="far fa-chevron-right"></i></a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link"><i class="far fa-chevron-right"></i></span>
            </li>
        @endif
    </ul>
</div>
