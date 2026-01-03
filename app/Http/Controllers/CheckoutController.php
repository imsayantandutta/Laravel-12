<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->query('product_id'); 
        $product = Product::find($productId); 

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('checkout', compact('product'));
    }

    public function submit(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            abort(404, 'Product not found');
        }

    
        [$month, $year] = explode('/', $request->card_expiry);
        $year = '20' . $year;

    
        $sameBilling = !$request->has('c_ship_different_address') || $request->c_ship_different_address != 1;

    
        $payload = [
            "connection_id" => "1",
            "campaign_id" => "65",
            "offers" => [
                [
                    "offer_id" => $request->offer_id,
                    "order_offer_quantity" => 1
                ]
            ],
            "email" => $request->email_address,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "phone" => $request->phone_number,
            "birthday" => $request->date_of_birth,
            "gender" => $request->gender,
            "same_address" => $sameBilling ? 'false' : 'true',
            "ship_fname" => $request->first_name,
            "ship_lname" => $request->last_name,
            "ship_address1" => $request->shipping_address,
            "ship_city" => $request->shipping_city,
            "ship_country" => "US",
            "ship_state" => $request->shipping_state,
            "ship_zipcode" => $request->shipping_zip_code,
            "bill_fname" => $sameBilling ? $request->billing_first_name : $request->first_name,
            "bill_lname" => $sameBilling ? $request->billing_last_name : $request->last_name,
            "bill_address1" => $sameBilling ? $request->billing_address : $request->shipping_address,
            "bill_city" => $sameBilling ? $request->billing_city : $request->shipping_city,
            "bill_country" => "US",
            "bill_state" => $sameBilling ? $request->billing_state : $request->shipping_state,
            "bill_zipcode" => $sameBilling ? $request->billing_zip_code : $request->shipping_zip_code,
            "payment_method_id" => 1,
            "card_type_id" => 2,
            "card_number" => preg_replace('/\s+/', '', $request->card_number),
            "card_cvv" => $request->card_cvv,
            "card_exp_month" => $month,
            "card_exp_year" => $year,
        ];

        // echo "<pre>";
        // print_r($payload);
        // exit();

        if (!$product->questions()->exists()) {
            $payload['action'] = 'process';

            try {
                
                $vrioResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'X-Api-Key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6ImFkbWluIiwib3JnYW5pemF0aW9uIjoiY29kZWNsb3Vkcy52cmlvIiwiaWQiOiIxYTY0Njc4Yi0yYmY4LTRjYWItYWNlYi1lYjJiZWQ1ZTk4YzIiLCJpYXQiOjE3NjI1NTIxMjQsImF1ZCI6InVybjp2cmlvOmFwaTp1c2VyIiwiaXNzIjoidXJuOnZyaW86YXBpOmF1dGhlbnRpY2F0b3IiLCJzdWIiOiJ1cm46dnJpbzphcGk6MzkifQ.xX8wQe7hYwzPCqFM2iQgje9WkdvGD0nkXww0kDfzJn4'
                ])->timeout(10)->post('https://api.vrio.app/orders', $payload);

                // echo "<pre>";
                // print_r($vrioResponse->json());
                // exit();

                if (!$vrioResponse->successful()) {
                    return back()->with('error', 'Vrio API request failed. Please try again.');
                }
                $vrioResult = $vrioResponse->json();
                $orderId = $vrioResponse['order_id'] ?? null;

                $existingCustomer = Customer::where('email', $request->email_address)->first();

                if (!$existingCustomer) {

                    Customer::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => $request->email_address,
                            'phone' => $request->phone_number,
                            'birthday' => $request->date_of_birth ?? null,
                            'gender' => $request->gender ?? null,
                        ]);
                }
                
                $authResponse = Http::withHeaders([
                    'Authorization' => 'Basic ZGV2QGNvZGVjbG91ZHMuYml6OngxQ3J6QVozdDZQVldFWlE='
                ])->timeout(10)->post('https://dev-core-ias-rest.telegramd.com/auth/client');

                if (!$authResponse->successful()) {
                    return back()->with('error', 'Telegram Auth failed. Please try again.');
                }
                $authToken = $authResponse->json()['token'] ?? null;

                if (!$authToken) {
                    return back()->with('error', 'Failed to retrieve Telegram token.');
                }

                $patientCheck = Http::withToken($authToken)
        ->get('https://dev-core-ias-rest.telegramd.com/patients/actions/getByEmail/' . $request->email_address);

            $patientData = $patientCheck->json();
            $existingPatientId = $patientData['id'] ?? null;

                if (!empty($existingPatientId)) {

                    $orderResponse = Http::withHeaders([
                        'accept' => 'application/json',
                        'authorization' => 'Bearer ' . $authToken,
                        'content-type' => 'application/json'
                    ])->timeout(10)->post('https://dev-core-ias-rest.telegramd.com/orders', [
                        'patient' => $existingPatientId,
                        'productVariations' => [
                            [
                                'productVariation' => 'pvt::36d24aa9-177a-4b85-ae31-b477e32b62d9',
                                'quantity' => 1
                            ]
                        ],
                    ]);

                    if (!$orderResponse->successful()) {
                        return back()->with('error', 'Failed to create Telegram order.');
                    }
                    $orderResult = $orderResponse->json();

                    $customer = Customer::where('email',  $request->email_address)->first();
                    $customerId = $customer->id ?? null;

                    CustomerOrder::create([
                        'customer_id' => $customerId,
                        'product_id' => $request->product_id,
                        'order_id'    => $orderId ?? null,
                        'vrio_api_response' => json_encode($vrioResult) ?? null,
                        'telegra_patient_api_response' =>  null,
                        'telegra_patient_order_api_response' => json_encode($orderResult) ?? null,
                    ]);
        
                    return redirect()->route('thankyou');

                } else {

                    $patientResponse = Http::withHeaders([
                        'accept' => 'application/json',
                        'authorization' => 'Bearer ' . $authToken,
                        'content-type' => 'application/json'
                    ])->timeout(10)->post('https://dev-core-ias-rest.telegramd.com/patients', [
                        'dateOfBirth' => $request->date_of_birth,
                        'email' => $request->email_address,
                        'firstName' => $request->first_name,
                        'lastName' => $request->last_name,
                        'gender' => $request->gender,
                        'genderBiological' => $request->gender,
                        'phone' => $request->phone_number,
                    ]);

                    if (!$patientResponse->successful()) {
                        return back()->with('error', 'Failed to create Telegram patient.');
                    }
                    $patient = $patientResponse->json();
                    $patientId = $patient['id'] ?? null;

                    $orderResponse = Http::withHeaders([
                        'accept' => 'application/json',
                        'authorization' => 'Bearer ' . $authToken,
                        'content-type' => 'application/json'
                    ])->timeout(10)->post('https://dev-core-ias-rest.telegramd.com/orders', [
                        'patient' => $patientId,
                        'productVariations' => [
                            [
                                'productVariation' => 'pvt::36d24aa9-177a-4b85-ae31-b477e32b62d9',
                                'quantity' => 1
                            ]
                        ],
                    ]);

                    if (!$orderResponse->successful()) {
                        return back()->with('error', 'Failed to create Telegram order.');
                    }
                    $orderResult = $orderResponse->json();

                    $customer = Customer::where('email',  $request->email_address)->first();
                    $customerId = $customer->id ?? null;

                    CustomerOrder::create([
                        'customer_id' => $customerId,
                        'product_id' => $request->product_id,
                        'order_id'    => $orderId ?? null,
                        'vrio_api_response' => json_encode($vrioResult) ?? null,
                        'telegra_patient_api_response' => json_encode($patient) ?? null,
                        'telegra_patient_order_api_response' => json_encode($orderResult) ?? null,
                    ]);

                    return redirect()->route('thankyou');
                        
                }

            } catch (\Throwable $e) {
                return back()->with('error', 'Something went wrong: ' . $e->getMessage());
            }

        } else{

            $vrioResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'X-Api-Key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6ImFkbWluIiwib3JnYW5pemF0aW9uIjoiY29kZWNsb3Vkcy52cmlvIiwiaWQiOiIxYTY0Njc4Yi0yYmY4LTRjYWItYWNlYi1lYjJiZWQ1ZTk4YzIiLCJpYXQiOjE3NjI1NTIxMjQsImF1ZCI6InVybjp2cmlvOmFwaTp1c2VyIiwiaXNzIjoidXJuOnZyaW86YXBpOmF1dGhlbnRpY2F0b3IiLCJzdWIiOiJ1cm46dnJpbzphcGk6MzkifQ.xX8wQe7hYwzPCqFM2iQgje9WkdvGD0nkXww0kDfzJn4'
                ])->timeout(10)->post('https://api.vrio.app/orders', $payload);

                // echo "<pre>";
                // print_r($vrioResponse->json());
                // exit();

                if (!$vrioResponse->successful()) {
                    return back()->with('error', 'Vrio API request failed. Please try again.');
                }

                $vrioResult = $vrioResponse->json();
                $orderId = $vrioResponse['order_id'] ?? null;

                $existingCustomer = Customer::where('email', $request->email_address)->first();

                if (!$existingCustomer) {

                    Customer::create([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => $request->email_address,
                            'phone' => $request->phone_number,
                            'birthday' => $request->date_of_birth ?? null,
                            'gender' => $request->gender ?? null,
                        ]);
                }
            
            session()->put('payload', $payload);
            session()->put('email', $request->email_address);
            return redirect()->route('quiz.show', $product->id);
        }
       
    }
}

