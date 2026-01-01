<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationRecord extends Model
{
    protected $fillable = [
        'application_id',
        'institution_name',
        'level_or_class',
        'program',
        'year_of_study',
        'cgpa',
        'graduation_year',
        'jamb_reg_no',
        'fees_amount',
        'fees_doc_url',
        'term_result_url',
        'transcript_url',
    ];

    protected function casts(): array
    {
        return [
            'cgpa' => 'decimal:2',
            'fees_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the application for this education record.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
