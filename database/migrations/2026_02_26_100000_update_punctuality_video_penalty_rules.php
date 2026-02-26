<?php

use App\Models\AssessmentCriteria;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update punctuality penalty rules
        AssessmentCriteria::where('code', 'punctuality')->update([
            'penalty_rules' => json_encode([
                'Needs Improvement' => ['amount' => 500, 'perIncident' => true, 'label' => '₦500 per late incident']
            ]),
        ]);

        // Update video penalty rules
        AssessmentCriteria::where('code', 'video')->update([
            'penalty_rules' => json_encode([
                'Needs Improvement' => ['amount' => 1000, 'perIncident' => true, 'label' => '₦1,000 per video-off incident']
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        AssessmentCriteria::where('code', 'punctuality')->update([
            'penalty_rules' => json_encode([]),
        ]);

        AssessmentCriteria::where('code', 'video')->update([
            'penalty_rules' => json_encode([]),
        ]);
    }
};
