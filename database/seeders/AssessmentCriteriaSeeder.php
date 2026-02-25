<?php

namespace Database\Seeders;

use App\Models\AssessmentCriteria;
use Illuminate\Database\Seeder;

class AssessmentCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'code' => 'punctuality',
                'name' => 'Punctuality',
                'description' => 'Measures if the tutor joins sessions on time',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [],
                'display_order' => 1,
            ],
            [
                'code' => 'video',
                'name' => 'Video-on Etiquette',
                'description' => 'Measures if the tutor keeps video on during sessions',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [],
                'display_order' => 2,
            ],
            [
                'code' => 'network',
                'name' => 'Network/Internet Quality',
                'description' => 'Measures the quality and stability of tutor\'s internet connection',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [
                    'Needs Improvement' => ['countThreshold' => 3, 'label' => 'Recurrent network failure']
                ],
                'display_order' => 3,
            ],
            [
                'code' => 'professional',
                'name' => 'Professional Conduct',
                'description' => 'Measures the tutor\'s professional behavior and attitude',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement', 'Unacceptable'],
                'penalty_rules' => [
                    'Needs Improvement' => ['amount' => 1000, 'label' => 'Unprofessional (₦1,000)'],
                    'Unacceptable' => ['action' => 'Immediate dismissal', 'label' => 'Immediate dismissal']
                ],
                'display_order' => 4,
            ],
            [
                'code' => 'curriculum',
                'name' => 'Compliance with Curriculum',
                'description' => 'Measures adherence to the prescribed curriculum',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [
                    'Needs Improvement' => ['countThreshold' => 3, 'label' => 'Curriculum non-compliance']
                ],
                'display_order' => 5,
            ],
            [
                'code' => 'content',
                'name' => 'Quality of Content & Delivery',
                'description' => 'Measures the quality of teaching content and delivery methods',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [
                    'Needs Improvement' => ['countThreshold' => 3, 'label' => 'Poor delivery']
                ],
                'display_order' => 6,
            ],
            [
                'code' => 'time',
                'name' => 'Full Class Time Usage',
                'description' => 'Measures if the tutor uses the full allocated class time',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [
                    'Needs Improvement' => ['halfPay' => true, 'label' => 'Half pay deduction']
                ],
                'display_order' => 7,
            ],
            [
                'code' => 'efficiency',
                'name' => 'Efficient Use of Class Time',
                'description' => 'Measures how efficiently the tutor uses class time for productive teaching',
                'options' => ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'],
                'penalty_rules' => [
                    'Needs Improvement' => ['halfPay' => true, 'label' => 'Half pay deduction']
                ],
                'display_order' => 8,
            ],
        ];

        foreach ($criteria as $criteriaData) {
            AssessmentCriteria::updateOrCreate(
                ['code' => $criteriaData['code']],
                $criteriaData
            );
        }
    }
}
