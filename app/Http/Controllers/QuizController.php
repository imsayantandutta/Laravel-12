<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UserAnswer;
use App\Models\Customer;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Http;

class QuizController extends Controller
{
    // Show one question at a time
    public function show($productId, $questionIndex = 0)
    {
        $product = Product::with('questions.answers')->findOrFail($productId);
        $questions = $product->questions;
        $email = session('email');

        if ($questionIndex >= count($questions)) {

                $payload = session('payload');
                $payload['action'] = 'process';

                $vrioResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'X-Api-Key' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6ImFkbWluIiwib3JnYW5pemF0aW9uIjoiY29kZWNsb3Vkcy52cmlvIiwiaWQiOiIxYTY0Njc4Yi0yYmY4LTRjYWItYWNlYi1lYjJiZWQ1ZTk4YzIiLCJpYXQiOjE3NjI1NTIxMjQsImF1ZCI6InVybjp2cmlvOmFwaTp1c2VyIiwiaXNzIjoidXJuOnZyaW86YXBpOmF1dGhlbnRpY2F0b3IiLCJzdWIiOiJ1cm46dnJpbzphcGk6MzkifQ.xX8wQe7hYwzPCqFM2iQgje9WkdvGD0nkXww0kDfzJn4'
                ])->timeout(10)->post('https://api.vrio.app/orders', $payload);

                // echo "<pre>";
                // print_r($vrioResponse->json());
                // exit();

                if (!$vrioResponse->successful()) {
                    return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Vrio API request failed. Please try again.');
                }

                $vrioResult = $vrioResponse->json();
                $orderId = $vrioResponse['order_id'] ?? null;
        
                 $authResponse = Http::withHeaders([
                    'Authorization' => 'Basic ZGV2QGNvZGVjbG91ZHMuYml6OngxQ3J6QVozdDZQVldFWlE='
                ])->timeout(10)->post('https://dev-core-ias-rest.telegramd.com/auth/client');

                if (!$authResponse->successful()) {
                    return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Telegram Auth failed. Please try again.');
                }
                $authToken = $authResponse->json()['token'] ?? null;

                if (!$authToken) {
                    return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Failed to retrieve Telegram token.');
                }

                $patientCheck = Http::withToken($authToken)
        ->get('https://dev-core-ias-rest.telegramd.com/patients/actions/getByEmail/' . $payload['email']);

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
                                'productVariation' => 'pvt::4332bc52-5f54-4c8f-bf5c-f1ef2bb95fa0',
                                'quantity' => 1
                            ]
                        ],
                    ]);

                    if (!$orderResponse->successful()) {
                        return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Failed to create Telegram order.');
                    }
                    $orderResult = $orderResponse->json();

                    $customer = Customer::where('email', $email)->first();
                    $customerId = $customer->id ?? null;

                    CustomerOrder::create([
                        'customer_id' => $customerId,
                        'product_id' => $productId,
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
                    ])->timeout(20)->post('https://dev-core-ias-rest.telegramd.com/patients', [
                        'dateOfBirth' => $payload['birthday'],
                        'email' => $payload['email'],
                        'firstName' => $payload['first_name'],
                        'lastName' => $payload['last_name'],
                        'gender' => $payload['gender'],
                        'genderBiological' =>$payload['gender'],
                        'phone' => $payload['phone'],
                    ]);

                    if (!$patientResponse->successful()) {
                        return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Failed to create Telegram patient.');
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
                                'productVariation' => 'pvt::4332bc52-5f54-4c8f-bf5c-f1ef2bb95fa0',
                                'quantity' => 1
                            ]
                        ],
                    ]);

                    if (!$orderResponse->successful()) {
                        return redirect()->route('checkout', ['product_id' => $productId])->with('error', 'Failed to create Telegram order.');
                    }
                    $orderResult = $orderResponse->json();

                    $customer = Customer::where('email', $email)->first();
                    $customerId = $customer->id ?? null;

                    CustomerOrder::create([
                        'customer_id' => $customerId,
                        'product_id' => $productId,
                        'order_id'    => $orderId ?? null,
                        'vrio_api_response' => json_encode($vrioResult) ?? null,
                        'telegra_patient_api_response' => json_encode($patient) ?? null,
                        'telegra_patient_order_api_response' => json_encode($orderResult) ?? null,
                    ]);

                    session()->forget('payload');
                    return redirect()->route('thankyou');
                        
                }
        }

        $question = $questions[$questionIndex];

        return view('quiz', compact('product', 'question', 'questionIndex', 'email'));
    }

    public function store(Request $request, $productId, $questionIndex)
    {

        $customer = Customer::where('email', $request->email)->first();
        $customerId = $customer->id ?? null;

        $request->validate([
            'question_id' => 'required',
            'answer_id' => 'required',
        ]);

        UserAnswer::create([
            'customer_id' =>  $customerId,
            'product_id' => $productId,
            'question_id' => $request->question_id,
            'answer_id' => $request->answer_id,
        ]);

        return redirect()->route('quiz.show', [
            'productId' => $productId,
            'questionIndex' => $questionIndex + 1,
        ]);
    }

    // Final Thank You page
    public function thankyou()
    {
        return view('thankyou');
    }
}
