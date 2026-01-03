@php
    $states = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming'
    ];
@endphp

@extends('layouts.app')

@section('content')
<div class="bg-light py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-12 mb-0">
        <a href="{{ url('/') }}">Home</a> <span class="mx-2 mb-0">/</span>
        <strong class="text-black">Checkout</strong>
      </div>
    </div>
  </div>
</div>

<div class="site-section">
  <div class="container">
    <div class="row">
      <!-- Billing & Shipping Form -->
      <div class="col-md-6 mb-5 mb-md-0">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h2 class="h3 mb-3 text-black">Billing Details</h2>
        <form action="{{ route('checkout.submit') }}" method="post" id="checkoutForm">
          @csrf
          <input type="hidden" name="offer_id" value="{{ $product->crm_product_id }}">
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="p-3 p-lg-5 border">
            <!-- Shipping Details -->
            <div class="form-group row">
              <div class="col-md-6">
                <label for="first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
              </div>
              <div class="col-md-6">
                <label for="last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" value="{{ old('last_name') }}">
              </div>
            </div>

            <div class="form-group">
              <label for="shipping_state" class="text-black">State <span class="text-danger">*</span></label>
              <select id="shipping_state" name="shipping_state" class="form-control">
                <option value="">Select State</option>
                @foreach($states as $code => $name)
                    <option value="{{ $code }}" {{ old('shipping_state') == $code ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group row">
              <div class="col-md-12">
                <label for="shipping_address" class="text-black">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="shipping_address" name="shipping_address" placeholder="Street address" value="{{ old('shipping_address') }}">
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-6">
                <label for="shipping_city" class="text-black">City <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="City" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}">
              </div>
              <div class="col-md-6">
                <label for="shipping_zip_code" class="text-black">Zip Code <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" placeholder="Zip Code" id="shipping_zip_code" name="shipping_zip_code" value="{{ old('shipping_zip_code') }}" maxlength="5" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" inputmode="numeric">
              </div>
            </div>

            <div class="form-group row mb-5">
              <div class="col-md-6">
                <label for="email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" placeholder="Email Address" id="email_address" name="email_address" value="{{ old('email_address') }}">
              </div>
              <div class="col-md-6">
                <label for="phone_number" class="text-black">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}" maxlength="10"  data-min-length="10" data-max-length="10" pattern="[0-9]{10}"  oninput="this.value = this.value.replace(/[^0-9]/g, '')"autocomplete="off" inputmode="numeric">
              </div>
            </div>

            <div class="form-group row mb-5">
              <div class="col-md-6">
                <label for="date_of_birth" class="text-black">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
              </div>
              <div class="col-md-6">
                <label for="gender" class="text-black">Gender <span class="text-danger">*</span></label>
                <select id="gender" name="gender" class="form-control">
                  <option value="">Select Gender</option>
                  <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                  <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                  <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
            </div>

            <!-- Billing Address -->
            <div class="form-group">
              <!-- <input type="hidden" name="c_ship_different_address" value="0"> -->
              <input type="checkbox" name="c_ship_different_address" id="c_ship_different_address" data-toggle="collapse" data-target="#ship_different_address" value="1" {{ old('c_ship_different_address') ? '' : 'checked' }}>
              <label for="c_ship_different_address" class="text-black">Same Shipping and Billing Address</label>

              <div class="collapse" id="ship_different_address">
                <div class="py-2">
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="billing_first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="billing_first_name" name="billing_first_name" value="{{ old('billing_first_name') }}">
                    </div>
                    <div class="col-md-6">
                      <label for="billing_last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="billing_last_name" name="billing_last_name" value="{{ old('billing_last_name') }}">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="billing_state" class="text-black">State <span class="text-danger">*</span></label>
                    <select id="billing_state" name="billing_state" class="form-control">
                        <option value="">Select State</option>
                        @foreach($states as $code => $name)
                            <option value="{{ $code }}" {{ old('billing_state') == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-12">
                      <label for="billing_address" class="text-black">Address <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="billing_address" name="billing_address" placeholder="Street address" value="{{ old('billing_address') }}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="billing_city" class="text-black">City <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="billing_city" name="billing_city" value="{{ old('billing_city') }}">
                    </div>
                    <div class="col-md-6">
                      <label for="billing_zip_code" class="text-black">Zip Code <span class="text-danger">*</span></label>
                      <input type="tel" class="form-control" id="billing_zip_code" maxlength="5"  name="billing_zip_code" value="{{ old('billing_zip_code') }}" onkeyup="javascript: this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" inputmode="numeric">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Credit Card Payment -->
            <div class="border mb-5 payment-sec">
              <h3 class="h6 mb-3 text-black">Credit Card Payment</h3>
              <div class="p-3 bg-light rounded">
                <div class="form-group mb-3">
                  <label for="card_number" class="text-black">Card Number <span class="text-danger">*</span></label>
                  <input type="text" id="card_number" name="card_number" placeholder="•••• •••• •••• ••••" class="form-control" maxlength="19" value="{{ old('card_number') }}" autocomplete="off" inputmode="numeric">
                </div>

                <div class="form-group mb-3" style="display: none;">
                  <label for="card_type" class="text-black">Card Type <span class="text-danger">*</span></label>
                  <select id="card_type" name="card_type" class="form-control">
                    <option value="">Select Card Type</option>
                    <option value="visa" {{ old('card_type') == 'visa' ? 'selected' : '' }}>Visa</option>
                    <option value="mastercard" {{ old('card_type') == 'mastercard' ? 'selected' : '' }}>MasterCard</option>
                    <option value="amex" {{ old('card_type') == 'amex' ? 'selected' : '' }}>American Express</option>
                    <option value="discover" {{ old('card_type') == 'discover' ? 'selected' : '' }}>Discover</option>
                  </select>
                </div>

                <div class="form-group row">
                  <div class="col-md-6 mb-3">
                    <label for="card_expiry" class="text-black">Expiry Date <span class="text-danger">*</span></label>
                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5" value="{{ old('card_expiry') }}">
                    <small id="expiry_error" style="color:red; display:none;"></small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="card_cvv" class="text-black">CVV <span class="text-danger">*</span></label>
                    <input type="password" id="card_cvv" name="card_cvv" placeholder="•••" class="form-control" value="{{ old('card_cvv') }}" maxlength="3" pattern="^[0-9]{3,4}$" onkeypress="return event.charCode >= 48 && event.charCode <= 57" autocomplete="off" inputmode="numeric">
                  </div>
                </div>
              </div>
            </div>

            <!-- Place Order Button -->
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Place Order</button>
            </div>

          </div>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="col-md-6">
        <div class="row mb-5">
          <div class="col-md-12">
            <h2 class="h3 mb-3 text-black">Your Order</h2>
            <div class="p-3 p-lg-5 border">
              <table class="table site-block-order-table mb-5">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $product->name }} <strong class="mx-2">x</strong> 1</td>
                    <td>${{ $product->price }}</td>
                  </tr>
                  <tr>
                    <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                    <td class="text-black font-weight-bold"><strong>${{ $product->price }}</strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<script src="{{ asset('assets/js/checkout.js') }}"></script>
@endsection
