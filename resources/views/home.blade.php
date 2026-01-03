@extends('layouts.app')

@section('content')
<div class="owl-carousel owl-single px-0">
      <div class="site-blocks-cover overlay" style="background-image: url('{{ asset('assets/images/hero_bg.jpg') }}');">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 mx-auto align-self-center">
              <div class="site-block-cover-content text-center">
                <h1 class="mb-0"><strong class="text-primary">Pharmative</strong> Opens 24 Hours</h1>

                <div class="row justify-content-center mb-5">
                  <div class="col-lg-6 text-center">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis ex perspiciatis non quibusdam vel quidem.</p>
                  </div>
                </div>
                
                <p><a href="javascript:void(0);" class="btn btn-primary px-5 py-3">Shop Now</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="site-blocks-cover overlay" style="background-image: url('{{ asset('assets/images/hero_bg_2.jpg') }}');">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 mx-auto align-self-center">
              <div class="site-block-cover-content text-center">
                <h1 class="mb-0">New Medicine <strong class="text-primary">Everyday</strong></h1>
                <div class="row justify-content-center mb-5">
                  <div class="col-lg-6 text-center">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis ex perspiciatis non quibusdam vel quidem.</p>
                  </div>
                </div>
                <p><a href="javascript:void(0);" class="btn btn-primary px-5 py-3">Shop Now</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

     <div class="site-section py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="feature">
              <span class="wrap-icon flaticon-24-hours-drugs-delivery"></span>
              <h3><a href="#">Free Delivery</a></h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa laborum voluptates excepturi neque labore .</p>
              <p><a href="#" class="d-flex align-items-center"><span class="mr-2">Learn more</span> <span class="icon-keyboard_arrow_right"></span></a></p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="feature">
              <span class="wrap-icon flaticon-medicine"></span>
              <h3><a href="javascript:void(0);">New Medicine Everyday</a></h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa laborum voluptates excepturi neque labore .</p>
              <p><a href="javascript:void(0);" class="d-flex align-items-center"><span class="mr-2">Learn more</span> <span class="icon-keyboard_arrow_right"></span></a></p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="feature">
              <span class="wrap-icon flaticon-test-tubes"></span>
              <h3><a href="javascript:void(0);">Medicines Guaranteed</a></h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa laborum voluptates excepturi neque labore .</p>
              <p><a href="javascript:void(0);" class="d-flex align-items-center"><span class="mr-2">Learn more</span> <span class="icon-keyboard_arrow_right"></span></a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">
            <div class="row">
            <div class="title-section text-center col-12">
                <h2>Pharmacy <strong class="text-primary">Products</strong></h2>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12 block-3 products-wrap">
                <div class="nonloop-block-3 owl-carousel">
                 @foreach($products as $product)

                    @if($product->image === 'paracetamol.jpg')

                      @php $product_image = 'https://cpimg.tistatic.com/09370937/b/4/Paracetamol-tablet-500mg.jpeg'; @endphp

                      @elseif($product->image === 'amoxicillin.jpg')

                          @php $product_image = 'https://5.imimg.com/data5/SELLER/Default/2024/1/380901647/LE/FV/BC/2383293/amopar-500-1000x1000.jpg'; @endphp

                      @elseif($product->image === 'cetirizine.jpg')

                          @php $product_image = 'https://5.imimg.com/data5/ANDROID/Default/2024/12/470969203/IO/FP/SR/150750645/product-jpeg-1000x1000.jpg'; @endphp

                      @else

                          @php $product_image = 'https://cdn01.pharmeasy.in/dam/products_otc/E34698/pure-nutrition-vitamin-c-l-vitamin-c-tablets-for-immunity-glowing-skin-l-60-tablets-2-1676281059.jpg'; @endphp

                @endif
                <div class="text-center item mb-4 item-v2 prod-card">
                    <span class="onsale">Sale</span>
                    <div class=image>
                      <a href="javascript:void(0);"> 
                    <img src="{{ $product_image }}" alt="{{ $product->name }}">
                    </a>
                    </div>
                    
                    <h3 class="text-dark mt-3"><a href="javascript:void(0);">{{ $product->name }}</a></h3>
                    <p class="price mb-2">${{ number_format($product->price, 2) }}</p>
                    <button class="btn btn-sm btn-primary px-4 py-2" onclick="window.location.href='{{ url('/checkout?product_id=' . $product->id) }}'">Buy Now</button>
                </div>
                @endforeach
              </div>
          </div>
          </div>
      </div>
  </div>

    <div class="site-section bg-image overlay" style="background-image: url('{{ asset('assets/images/hero_bg_2.jpg') }}');">
      <div class="container">
        <div class="row justify-content-center text-center">
         <div class="col-lg-7">
           <h3 class="text-white">Sign up for discount up to 55% OFF</h3>
           <p class="text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam.</p>
           <!-- <p class="mb-0"><a href="javascript:void(0);" class="btn btn-outline-white">Sign up</a></p> -->
         </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        
        <div class="row justify-content-between">
          <div class="col-lg-6">
            <div class="title-section">
              <h2>Happy <strong class="text-primary">Customers</strong></h2>
            </div>
            <div class="block-3 products-wrap">
            <div class="owl-single no-direction owl-carousel">
        
              <div class="testimony">
                <blockquote>
                  <img src="{{ asset('assets/images/person_1.jpg') }}" alt="Image" class="img-fluid">
                  <p>&ldquo;Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore obcaecati maiores voluptate aspernatur iusto eveniet, placeat ab quod tenetur ducimus. Minus ratione sit quaerat unde.&rdquo;</p>
                </blockquote>

                <p class="author">&mdash; Kelly Holmes</p>
              </div>
        
              <div class="testimony">
                <blockquote>
                  <img src="{{ asset('assets/images/person_2.jpg') }}" alt="Image" class="img-fluid">
                  <p>&ldquo;Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore
                    obcaecati maiores voluptate aspernatur iusto eveniet, placeat ab quod tenetur ducimus. Minus ratione sit quaerat
                    unde.&rdquo;</p>
                </blockquote>
              
                <p class="author">&mdash; Rebecca Morando</p>
              </div>
        
              <div class="testimony">
                <blockquote>
                  <img src="{{ asset('assets/images/person_3.jpg') }}" alt="Image" class="img-fluid">
                  <p>&ldquo;Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore
                    obcaecati maiores voluptate aspernatur iusto eveniet, placeat ab quod tenetur ducimus. Minus ratione sit quaerat
                    unde.&rdquo;</p>
                </blockquote>
              
                <p class="author">&mdash; Lucas Gallone</p>
              </div>
        
              <div class="testimony">
                <blockquote>
                  <img src="{{ asset('assets/images/person_4.jpg') }}" alt="Image" class="img-fluid">
                  <p>&ldquo;Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore
                    obcaecati maiores voluptate aspernatur iusto eveniet, placeat ab quod tenetur ducimus. Minus ratione sit quaerat
                    unde.&rdquo;</p>
                </blockquote>
              
                <p class="author">&mdash; Andrew Neel</p>
              </div>
        
            </div>
          </div>
          </div>
          <div class="col-lg-5">
            <div class="title-section">
              <h2 class="mb-5">Why <strong class="text-primary">Us</strong></h2>
              <div class="step-number d-flex mb-4">
                <span>1</span>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore</p>
              </div>

              <div class="step-number d-flex mb-4">
                <span>2</span>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore</p>
              </div>

              <div class="step-number d-flex mb-4">
                <span>3</span>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo omnis voluptatem consectetur quam tempore</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endsection