<div class="banner-container d-flex justify-content-center">
    <!-- Slider 1 bên trái -->
    <div id="leftBanner" class="carousel slide" style="width: 45%; margin-right: 10px; border-radius: 10px; overflow: hidden;">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('client/assets/img/slider1/anh1.webp') }}" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider1/anh2.webp') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider1/anh3.webp') }}" class="d-block w-100" alt="Banner 3">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider1/anh4.webp') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider1/anh5.webp') }}" class="d-block w-100" alt="Banner 3">
            </div>
        </div>
    </div>

    <!-- Slider 2 bên phải -->
    <div id="rightBanner" class="carousel slide" style="width: 45%; margin-left: 10px; border-radius: 10px; overflow: hidden;">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('client/assets/img/slider2/anh6.webp') }}" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider2/anh7.webp') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider2/anh8.webp') }}" class="d-block w-100" alt="Banner 3">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider2/anh9.webp') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('client/assets/img/slider2/anh10.webp') }}" class="d-block w-100" alt="Banner 3">
            </div>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const sliders = ['#leftBanner', '#rightBanner'];

    sliders.forEach(sliderId => {
      const slider = document.querySelector(sliderId);
      const items = slider.querySelectorAll('.carousel-item');
      let currentIndex = 0;

      function showSlide(index) {
        items.forEach((item, i) => {
          item.classList.toggle('active', i === index);
        });
      }

      function nextSlide() {
        currentIndex = (currentIndex + 1) % items.length;
        showSlide(currentIndex);
      }

      // Auto-slide every 3 seconds
      setInterval(nextSlide, 3000);
    });
  });
</script>

<style>
  .banner-container {
      width: 90%; /* Thu nhỏ tổng chiều rộng container */
      margin: 20px auto; /* Căn giữa container */
      display: flex;
      justify-content: center;
  }

  .carousel-inner {
      position: relative;
      display: flex;
      transition: transform 0.5s ease-in-out; /* Hiệu ứng chuyển động mượt mà */
  }

  .carousel-inner img {
      object-fit: cover;
      height: 110px; /* Giảm chiều cao banner */
      border-radius: 10px; /* Bo tròn góc ảnh */
  }

  .carousel-item {
      flex: 0 0 100%; /* Mỗi ảnh chiếm toàn bộ chiều rộng */
  }

  .carousel-item:not(.active) {
      opacity: 0; /* Ẩn ảnh không active */
      pointer-events: none; /* Vô hiệu hóa tương tác với ảnh không active */
  }

  .carousel-caption {
      display: none; /* Ẩn tiêu đề và mô tả */
  }
</style>
