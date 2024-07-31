@extends('layouts.app')

@section('title')
    {{ __('Edit Investor') }}
@endsection

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection

@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>{{ __('Edit Investor') }}</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('investors.index') }}" class="btn btn-secondary mb-3">Back to Investors List</a>
                            <form action="{{ route('investors.update', $investor->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Section 1 - About Founder/s -->
                                <div class="form-section mb-3">
                                    <h4>About Founder/s</h4>
                                    <div id="founders">
                                        @foreach ($investor->founders as $index => $founder)
                                            <div class="founder card card-body mb-3">
                                                <div class="form-group mb-3">
                                                    <label for="founder-name-{{ $index }}">Name</label>
                                                    <input type="text" class="form-control" name="founders[{{ $index }}][name]" id="founder-name-{{ $index }}" value="{{ $founder->name }}" placeholder="Name">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="founder-qualification-{{ $index }}">Qualification</label>
                                                    <input type="text" class="form-control" name="founders[{{ $index }}][qualification]" id="founder-qualification-{{ $index }}" value="{{ $founder->qualification }}" placeholder="Qualification">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="founder-experience-{{ $index }}">Experience Summary</label>
                                                    <textarea class="form-control" name="founders[{{ $index }}][experience_summary]" id="founder-experience-{{ $index }}" placeholder="Experience Summary">{{ $founder->experience_summary }}</textarea>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="founder-skills-{{ $index }}">Key Skills</label>
                                                    <textarea class="form-control" name="founders[{{ $index }}][key_skills]" id="founder-skills-{{ $index }}" placeholder="Key Skills">{{ $founder->key_skills }}</textarea>
                                                </div>
                                                <button type="button" class="btn btn-danger removeFounder" onclick="removeFounder(this)">Remove</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-primary" id="addFounder">Add Another Founder</button>
                                </div>

                                <!-- Section 2 - Concept of Venture -->
                                <div class="form-section mb-3">
                                <h4>Name of venture </h4>
                                <div class="form-group mb-3">
                                    <label for="company_logo">Company Logo</label>
                                    @if($investor->company_logo)
                                        <div>
                                            <img src="{{ asset('uploads/' . $investor->company_logo) }}" alt="Company Logo" width="70" height="40">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="company_logo" id="company_logo" accept="image/*">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sector">Sector</label>
                                    <select class="form-control" name="sector" id="sector">
                                        <option value="Financial services" {{ $investor->sector == 'Financial services' ? 'selected' : '' }}>Financial services</option>
                                        <option value="Retail" {{ $investor->sector == 'Retail' ? 'selected' : '' }}>Retail</option>
                                        <option value="Consumer goods/fmcg" {{ $investor->sector == 'Consumer goods/fmcg' ? 'selected' : '' }}>Consumer goods/FMCG</option>
                                        <option value="Healthcare" {{ $investor->sector == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                        <option value="Software services" {{ $investor->sector == 'Software services' ? 'selected' : '' }}>Software services</option>
                                        <option value="Transportation and logistics" {{ $investor->sector == 'Transportation and logistics' ? 'selected' : '' }}>Transportation and logistics</option>
                                        <option value="Education" {{ $investor->sector == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Media/entertainment" {{ $investor->sector == 'Media/entertainment' ? 'selected' : '' }}>Media / Entertainment</option>
                                        <option value="Consumer niche services" {{ $investor->sector == 'Consumer niche services' ? 'selected' : '' }}>Consumer niche services</option>
                                        <option value="Hospitality" {{ $investor->sector == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                                        <option value="AI/VR" {{ $investor->sector == 'AI/VR' ? 'selected' : '' }}>AI / VR</option>
                                        <option value="Agriculture and tech" {{ $investor->sector == 'Agriculture and tech' ? 'selected' : '' }}>Agriculture and tech</option>
                                        <option value="Aeronautics and aerospace tech" {{ $investor->sector == 'Aeronautics and aerospace tech' ? 'selected' : '' }}>Aeronautics and aerospace tech</option>
                                        <option value="Defense" {{ $investor->sector == 'Defense' ? 'selected' : '' }}>Defense</option>
                                    </select>
                                </div>
                                    <div class="form-group mb-3">
                                        <label for="problem_opportunity">Problem/Opportunity</label>
                                        <textarea class="form-control" name="problem_opportunity" id="problem_opportunity" placeholder="Problem/Opportunity">{{ $investor->problem_opportunity }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="solution_technology">Solution/Technology</label>
                                        <textarea class="form-control" name="solution_technology" id="solution_technology" placeholder="Solution/Technology">{{ $investor->solution_technology }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="current_stage">Current Stage</label>
                                        <textarea class="form-control" name="current_stage" id="current_stage" placeholder="Current Stage">{{ $investor->current_stage }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="product_demo">Product Demo</label>
                                        <input type="file" class="form-control" name="product_demo" id="product_demo">
                                        @if ($investor->product_demo)
                                            <a href="{{ asset('storage/' . $investor->product_demo) }}" target="_blank">View Current Demo</a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Section 3 - Business Model and Specification -->
                                <div class="form-section mb-3">
                                    <h4>Business Model and Specification</h4>
                                    <div class="form-group mb-3">
                                        <label for="unique_value_proposition">Unique Value Proposition</label>
                                        <textarea class="form-control" name="unique_value_proposition" id="unique_value_proposition" placeholder="Unique Value Proposition">{{ $investor->unique_value_proposition }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="competitive_advantage">Competitive Advantage</label>
                                        <textarea class="form-control" name="competitive_advantage" id="competitive_advantage" placeholder="Competitive Advantage">{{ $investor->competitive_advantage }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="target_customer_segment">Target Customer Segment/Market Size</label>
                                        <textarea class="form-control" name="target_customer_segment" id="target_customer_segment" placeholder="Target Customer Segment/Market Size">{{ $investor->target_customer_segment }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="channels_strategies">Channels and Strategies</label>
                                        <textarea class="form-control" name="channels_strategies" id="channels_strategies" placeholder="Channels and Strategies">{{ $investor->channels_strategies }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="revenue_streams">Revenue Streams</label>
                                        <textarea class="form-control" name="revenue_streams" id="revenue_streams" placeholder="Revenue Streams">{{ $investor->revenue_streams }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="costs_expenditures">Costs and Expenditures</label>
                                        <textarea class="form-control" name="costs_expenditures" id="costs_expenditures" placeholder="Costs and Expenditures">{{ $investor->costs_expenditures }}</textarea>
                                    </div>
                                </div>

                                <!-- Section 4 - Vision and Future Prospects -->
                                <div class="form-section mb-3">
                                    <h4>Vision and Future Prospects</h4>
                                    <div class="form-group mb-3">
                                        <label for="plan_24_month">24 Month Plan</label>
                                        <textarea class="form-control" name="plan_24_month" id="plan_24_month" placeholder="24 Month Plan">{{ $investor->plan_24_month }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="why_applying">Why Are You Applying</label>
                                        <textarea class="form-control" name="why_applying" id="why_applying" placeholder="Why Are You Applying">{{ $investor->why_applying }}</textarea>
                                    </div>
                                </div>

                                <!-- Section 5 - Pitch Deck and Other Official Documents -->
                                <div class="form-section mb-3">
                                    <h4>Pitch Deck and Other Official Documents</h4>
                                    <div class="form-group mb-3">
                                        <label for="pitch_deck">Upload Your Pitch Deck</label>
                                        <input type="file" class="form-control" name="pitch_deck" id="pitch_deck">
                                        @if ($investor->pitch_deck)
                                            <a href="{{ asset('storage/' . $investor->pitch_deck) }}" target="_blank">View Current Pitch Deck</a>
                                        @endif
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="certificate_incorporation">Upload Your Certificate of Incorporation</label>
                                        <input type="file" class="form-control" name="certificate_incorporation" id="certificate_incorporation">
                                        @if ($investor->certificate_incorporation)
                                            <a href="{{ asset('storage/' . $investor->certificate_incorporation) }}" target="_blank">View Current Certificate</a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Section 6 - Investment Ask -->
                                <div class="form-section mb-3">
                                    <h4>Investment Ask</h4>
                                    <div class="form-group mb-3">
                                        <label for="capital_required">Capital Required</label>
                                        <input type="number" class="form-control" name="capital_required" id="capital_required" value="{{ $investor->capital_required }}" placeholder="Capital Required">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="investment_preferred">Form of Investment Preferred</label>
                                        <select class="form-control" name="investment_preferred" id="investment_preferred">
                                            <option value="equity" {{ $investor->investment_preferred == 'equity' ? 'selected' : '' }}>Equity</option>
                                            <option value="debt" {{ $investor->investment_preferred == 'debt' ? 'selected' : '' }}>Debt</option>
                                            <option value="equity_debt_split" {{ $investor->investment_preferred == 'equity_debt_split' ? 'selected' : '' }}>Equity/Debt Split</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="equity_amount_container" style="display: {{ $investor->investment_preferred == 'equity' || $investor->investment_preferred == 'equity_debt_split' ? 'block' : 'none' }};">
                                        <label for="equity_amount">Equity Amount</label>
                                        <input type="number" class="form-control" name="equity_amount" id="equity_amount" value="{{ $investor->equity_amount }}" placeholder="Equity Amount">
                                    </div>
                                    <div class="form-group mb-3" id="debt_amount_container" style="display: {{ $investor->investment_preferred == 'equity_debt_split' ? 'block' : 'none' }};">
                                        <label for="debt_amount">Debt Amount</label>
                                        <input type="number" class="form-control" name="debt_amount" id="debt_amount" value="{{ $investor->debt_amount }}" placeholder="Debt Amount">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="equity_offered">Equity Offered Against Capital Ask</label>
                                        <input type="number" class="form-control" name="equity_offered" id="equity_offered" value="{{ $investor->equity_offered }}" placeholder="Equity Offered">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
@endsection

@section('scripts')
<script>
    document.getElementById('addFounder').addEventListener('click', function () {
        let founderIndex = document.querySelectorAll('.founder').length;
        let founderDiv = document.createElement('div');
        founderDiv.classList.add('founder', 'card', 'card-body', 'mb-3');
        founderDiv.innerHTML = `
            <div class="form-group mb-3">
                <label for="founder-name-${founderIndex}">Name</label>
                <input type="text" class="form-control" name="founders[${founderIndex}][name]" id="founder-name-${founderIndex}" placeholder="Name">
            </div>
            <div class="form-group mb-3">
                <label for="founder-qualification-${founderIndex}">Qualification</label>
                <input type="text" class="form-control" name="founders[${founderIndex}][qualification]" id="founder-qualification-${founderIndex}" placeholder="Qualification">
            </div>
            <div class="form-group mb-3">
                <label for="founder-experience-${founderIndex}">Experience Summary</label>
                <textarea class="form-control" name="founders[${founderIndex}][experience_summary]" id="founder-experience-${founderIndex}" placeholder="Experience Summary"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="founder-skills-${founderIndex}">Key Skills</label>
                <textarea class="form-control" name="founders[${founderIndex}][key_skills]" id="founder-skills-${founderIndex}" placeholder="Key Skills"></textarea>
            </div>
            <button type="button" class="btn btn-danger removeFounder" onclick="removeFounder(this)">Remove</button>
        `;
        document.getElementById('founders').appendChild(founderDiv);
    });

    function removeFounder(button) {
        button.parentElement.remove();
    }

    document.getElementById('investment_preferred').addEventListener('change', function () {
        let investmentPreferred = this.value;
        document.getElementById('equity_amount_container').style.display = investmentPreferred === 'equity' || investmentPreferred === 'equity_debt_split' ? 'block' : 'none';
        document.getElementById('debt_amount_container').style.display = investmentPreferred === 'equity_debt_split' ? 'block' : 'none';
    });
</script>
@endsection
