<style>
    .banner-slide {
        display: none;
        animation: fadeEffect 1.2s;
    }
    .banner-slide.active {
        display: block;
    }
    @keyframes fadeEffect {
        from {opacity: 0.3;}
        to {opacity: 1;}
    }
    .banner-nav {
        background: rgba(0,0,0,0.4) !important;
        transition: all 0.3s ease !important;
    }
    .banner-nav:hover {
        background: rgba(0,0,0,0.8) !important;
        transform: translateY(-50%) scale(1.1) !important;
    }
    .banner-dot-item {
        transition: all 0.3s ease;
    }
    .banner-dot-item:hover {
        transform: scale(1.2);
    }
</style>
<section class="hero-area bgc-black pt-200 rpt-120 rel z-2">
    <div class="container-fluid">
        <h1 class="hero-title" data-aos="flip-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
            Tours Du Lịch</h1>

        @if(isset($banners) && count($banners) > 0)
        <!-- Banner Slider -->
        <div class="banner-slider-container" style="position:relative; overflow:hidden; border-radius:10px;">
            <div class="banner-slides" id="bannerSlides">
                @foreach($banners as $index => $banner)
                <div class="banner-slide {{ $index === 0 ? 'active' : '' }}">
                    @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" target="_blank">
                    @endif
                    <div class="main-hero-image bgs-cover"
                        style="background-image: url({{ asset('clients/assets/images/banners/' . $banner->image) }}); position:relative; min-height: 600px;">
                        <div style="position:absolute; bottom:150px; left:60px; color:#fff; text-shadow: 2px 2px 10px rgba(0,0,0,0.8); max-width: 800px; z-index:5;">
                            <h2 style="font-size:3rem; margin-bottom:10px; font-weight:700; line-height: 1.2; text-transform: uppercase;">{{ $banner->title }}</h2>
                            @if($banner->subtitle)
                            <p style="font-size:1.4rem; margin:0; font-weight: 500;">{{ $banner->subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    @if($banner->link_url)
                    </a>
                    @endif
                </div>
                @endforeach
            </div>

            @if(count($banners) > 1)
            <!-- Slider Controls -->
            <button class="banner-nav banner-prev" onclick="changeBannerSlide(-1)" style="position:absolute; left:20px; top:50%; transform:translateY(-50%); color:#fff; border:none; width:50px; height:50px; border-radius:50%; cursor:pointer; font-size:20px; z-index:10; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="banner-nav banner-next" onclick="changeBannerSlide(1)" style="position:absolute; right:20px; top:50%; transform:translateY(-50%); color:#fff; border:none; width:50px; height:50px; border-radius:50%; cursor:pointer; font-size:20px; z-index:10; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Dots -->
            <div class="banner-dots" style="position:absolute; bottom:120px; left:50%; transform:translateX(-50%); display:flex; gap:10px; z-index:10;">
                @foreach($banners as $index => $banner)
                <span class="banner-dot banner-dot-item {{ $index === 0 ? 'active' : '' }}" onclick="goToBannerSlide({{ $index }})"
                    style="width:14px; height:14px; border-radius:50%; background:{{ $index === 0 ? '#fff' : 'rgba(255,255,255,0.5)' }}; cursor:pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.5);"></span>
                @endforeach
            </div>
            @endif
        </div>
        @else
        <!-- Fallback: ảnh tĩnh mặc định nếu chưa có banner nào -->
        <div class="main-hero-image bgs-cover"
            style="background-image: url({{ asset('clients/assets/images/hero/hero.jpg') }});">
        </div>
        @endif
    </div>
    <form action="{{ route('search') }}" method="GET" id="search_form">
        <div class="container container-1400">
            <div class="search-filter-inner" data-aos="zoom-out-down" data-aos-duration="1500" data-aos-offset="50">
                <div class="filter-item clearfix">
                    <div class="icon"><i class="fal fa-map-marker-alt"></i></div>
                    <span class="title">Điểm đến</span>
                    <select name="destination" id="destination">
                        <option value="">Chọn điểm đến</option>
                        <option value="dn">Đà Nẵng</option>
                        <option value="cd">Côn Đảo</option>
                        <option value="hn">Hà Nội</option>
                        <option value="hcm">TP. Hồ Chí Minh</option>
                        <option value="hl">Hạ Long</option>
                        <option value="nb">Ninh Bình</option>
                        <option value="pq">Phú Quốc</option>
                        <option value="dl">Đà Lạt</option>
                        <option value="qt">Quảng Trị</option>
                        <option value="kh">Khánh Hòa (Nha Trang)</option>
                        <option value="ct">Cần Thơ</option>
                        <option value="vt">Vũng Tàu</option>
                        <option value="qn">Quảng Ninh</option>
                        <option value="la">Lào Cai (Sa Pa)</option>
                        <option value="bd">Bình Định (Quy Nhơn)</option>
                    </select>
                    
                </div>
                <div class="filter-item clearfix">
                    <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                    <span class="title">Ngày khởi hành</span>
                    <input type="text" id="start_date" name="start_date" class="datetimepicker datetimepicker-custom"
                        placeholder="Chọn ngày đi" readonly>
                </div>
                <div class="filter-item clearfix">
                    <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                    <span class="title">Ngày kết thúc</span>
                    <input type="text" id="end_date" name="end_date" class="datetimepicker datetimepicker-custom"
                        placeholder="Chọn ngày về" readonly>
                </div>
                <div class="search-button">
                    <button class="theme-btn" type="submit">
                        <span data-hover="Tìm kiếm">Tìm kiếm</span>
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

</section>
<!-- Hero Area End -->

@if(isset($banners) && count($banners) > 1)
<script>
    let currentBannerSlide = 0;
    const bannerSlides = document.querySelectorAll('.banner-slide');
    const bannerDots = document.querySelectorAll('.banner-dot');
    const totalBannerSlides = bannerSlides.length;
    let bannerAutoplay;

    function showBannerSlide(index) {
        bannerSlides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        bannerDots.forEach((dot, i) => {
            dot.style.background = i === index ? '#fff' : 'rgba(255,255,255,0.5)';
            dot.classList.toggle('active', i === index);
        });
        currentBannerSlide = index;
    }

    function changeBannerSlide(direction) {
        let newIndex = (currentBannerSlide + direction + totalBannerSlides) % totalBannerSlides;
        showBannerSlide(newIndex);
        resetBannerAutoplay();
    }

    function goToBannerSlide(index) {
        showBannerSlide(index);
        resetBannerAutoplay();
    }

    function startBannerAutoplay() {
        bannerAutoplay = setInterval(() => {
            changeBannerSlide(1);
        }, 5000);
    }

    function resetBannerAutoplay() {
        clearInterval(bannerAutoplay);
        startBannerAutoplay();
    }

    startBannerAutoplay();
</script>
@endif
