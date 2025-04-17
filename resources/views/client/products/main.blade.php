@extends('client.layout')

@section('main')

{{-- Product Detail --}}
<div>
    <div class="mb-xl-14 mb-6">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <img class="img-fluid" src="{{ Storage::url($product->image) }}" width="90%" alt="Image Description">
            </div>
            <div class="col-md-7 mb-md-6 mb-lg-0 mt-6">
                <div class="border-bottom mb-3 pb-md-1 pb-3">
                    <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">Laptops {{ $product->category->name }}</a>
                    <h2 class="font-size-25 text-lh-1dot2">{{ $product->name }}</h2>
                    <div class="mb-2">
                        <div class="text-warning mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="far fa-star text-muted"></small>
                        </div>
                        <span class="text-secondary font-size-13">(3 customer reviews)</span>
                    </div>
                    <div class="ml-md-3 text-gray-9 font-size-14">Availability:
                        <span class="text-green font-weigh-bold">{{ $product->quantity }} in stock</span>
                    </div>
                </div>

                <div class="mb-2">
                    <ul class="font-size-14 pl-3 ml-1 text-gray-110">
                        {{ $product->description }}
                    </ul>
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-baseline">
                        <ins class="font-size-36 text-decoration-none text-red mr-3">
                            <span id="variant-price">{{ number_format($variants->first()->price) }} VND</span>
                        </ins>
                        @if($discountedPrice < $originalPrice)
                            <del class="font-size-16 text-gray-6">{{ number_format($originalPrice) }} VND</del>
                        @endif
                    </div>
                </div>

                <h3>Chọn cấu hình:</h3>
                <div class="variant-grid">
                    @foreach ($variants as $variant)
                        <div class="variant-box {{ $loop->first ? 'selected' : '' }}" data-variant-id="{{ $variant->id }}">
                            <div class="variant-values">
                                @foreach($variant->options as $option)
                                    <span class="variant-value">({{ $option->value }})</span>
                                    @if(!$loop->last) - @endif
                                @endforeach
                            </div>
                            <div class="price">{{ number_format($variant->price) }} VND</div>
                        </div>
                    @endforeach
                </div>

                <div class="max-width-150 my-4">
                    <h6 class="font-size-14">Số lượng</h6>
                    <div class="border rounded-pill py-2 px-3 border-color-1">
                        <div class="js-quantity row align-items-center">
                            <div class="col">
                                <input class="js-result form-control h-auto border-0 rounded p-0 shadow-none" type="text" value="1">
                            </div>
                            <div class="col-auto pr-1">
                                <a class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                    <small class="fas fa-minus btn-icon__inner"></small>
                                </a>
                                <a class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                    <small class="fas fa-plus btn-icon__inner"></small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="variant-id-input" value="{{ $variants->first()->id }}">
                    <input type="hidden" name="quantity" id="quantity-input" value="1">

                    <button type="submit" class="btn px-5 btn-primary-dark transition-3d-hover mr-2">
                        <i class="ec ec-add-to-cart mr-2 font-size-20"></i> Add to Cart
                    </button>
                </form>
               <div class ="d-flex mt-3">
               <form action="{{ route('wishlist.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="quantity-input" value="1">
                    

                    <button type="submit" class="btn px-5 btn-primary-dark transition-3d-hover mr-2">
                        <i class="ec ec-favorites mr-2 font-size-20"></i> Add to Wishlist
                    </button>
                </form>
               </div>
               

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex mt-3">
                    <a href="#" class="btn px-5 btn-success transition-3d-hover">
                        <i class="ec ec-credit-card mr-2 font-size-20"></i> Mua ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Product Detail --}}

<!-- CSS cho biến thể -->
<style>
    .variant-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.variant-box {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.variant-box.selected {
    border-color: #a49e20;
    background-color: #f8f9fa;
}

.variant-box:hover {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

    .variant-box .price {
        font-size: 15px;
        color: #d32f2f;
    }
</style>

<!-- JS -->
<script>
    document.querySelectorAll('.variant-box').forEach(box => {
        box.addEventListener('click', function () {
            // Xóa lớp 'selected' khỏi tất cả các variant-box
            document.querySelectorAll('.variant-box').forEach(b => b.classList.remove('selected'));
            // Thêm lớp 'selected' vào variant được chọn
            this.classList.add('selected');

            // Lấy giá của variant được chọn và cập nhật giá trên giao diện
            let variantPrice = this.querySelector('.price').textContent.trim();
            document.getElementById('variant-price').textContent = variantPrice;

            // Lấy ID variant và đưa vào input hidden
            document.getElementById('variant-id-input').value = this.getAttribute('data-variant-id');
        });
    });

    // Cập nhật số lượng mỗi khi thay đổi
    document.querySelector('.js-plus').addEventListener('click', () => {
        let input = document.querySelector('.js-result');
        input.value = parseInt(input.value) + 1;
        document.getElementById('quantity-input').value = input.value;
    });

    document.querySelector('.js-minus').addEventListener('click', () => {
        let input = document.querySelector('.js-result');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            document.getElementById('quantity-input').value = input.value;
        }
    });

    // Nếu người dùng gõ trực tiếp vào input
    document.querySelector('.js-result').addEventListener('input', (e) => {
        document.getElementById('quantity-input').value = e.target.value;
    });
</script>

@endsection
