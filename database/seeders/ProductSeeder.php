<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Question;
use App\Models\Answer;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'image' => 'paracetamol.jpg',
                'price' => 45.00,
                'questions' => [
                    [
                        'question_text' => 'How often can you take Paracetamol 500mg in a day?',
                        'answers' => ['Every 4–6 hours', 'Once a day', 'Every hour', 'Twice a week'],
                    ],
                    [
                        'question_text' => 'What is Paracetamol mainly used for?',
                        'answers' => ['Fever and pain relief', 'Cough', 'Allergy', 'Weight gain'],
                    ],
                    [
                        'question_text' => 'Can Paracetamol be taken on an empty stomach?',
                        'answers' => ['Yes, it can', 'No, always after food', 'Only with milk', 'Only at night'],
                    ],
                ],
            ],
            [
                'name' => 'Amoxicillin 250mg',
                'image' => 'amoxicillin.jpg',
                'price' => 120.00,
                'questions' => [
                    [
                        'question_text' => 'What type of medicine is Amoxicillin?',
                        'answers' => ['Antibiotic', 'Painkiller', 'Vitamin', 'Antacid'],
                    ],
                    [
                        'question_text' => 'Can Amoxicillin be used for viral infections?',
                        'answers' => ['No, only bacterial', 'Yes, for all infections', 'Only for cold', 'None of these'],
                    ],
                    [
                        'question_text' => 'What is a common side effect of Amoxicillin?',
                        'answers' => ['Diarrhea', 'Hair loss', 'Dizziness', 'High blood sugar'],
                    ],
                ],
            ],
            [
                'name' => 'Cetirizine 10mg',
                'image' => 'cetirizine.jpg',
                'price' => 25.00,
                'questions' => [
                    [
                        'question_text' => 'What is Cetirizine used for?',
                        'answers' => ['Allergy relief', 'Headache', 'Fever', 'Infection'],
                    ],
                    [
                        'question_text' => 'When should Cetirizine be taken?',
                        'answers' => ['At night', 'Morning only', 'After meals', 'Before meals'],
                    ],
                    [
                        'question_text' => 'Is Cetirizine a non-drowsy antihistamine?',
                        'answers' => ['Yes', 'No', 'Only for children', 'Only with milk'],
                    ],
                ],
            ],
        ];

        foreach ($medicines as $med) {
            $product = Product::create([
                'name' => $med['name'],
                'image' => $med['image'],
                'price' => $med['price'],
            ]);

            foreach ($med['questions'] as $qData) {
                $question = Question::create([
                    'product_id' => $product->id,
                    'question_text' => $qData['question_text'],
                ]);

                foreach ($qData['answers'] as $ans) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $ans,
                    ]);
                }
            }
        }
    }
}
