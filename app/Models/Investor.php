<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_logo',
        'sector',
        'problem_opportunity',
        'solution_technology',
        'current_stage',
        'product_demo',
        'unique_value_proposition',
        'competitive_advantage',
        'target_customer_segment',
        'channels_strategies',
        'revenue_streams',
        'costs_expenditures',
        'plan_24_month',
        'why_applying',
        'pitch_deck',
        'certificate_incorporation',
        'capital_required',
        'investment_preferred',
        'equity_amount',
        'debt_amount',
        'equity_offered',
    ];
    public function founders()
    {
        return $this->hasMany(Founder::class);
    }
}
