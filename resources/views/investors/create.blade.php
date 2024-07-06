@extends('layouts.app')

@section('title')
    {{ __('Create Investor') }}
@endsection

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
    <style>
        .form-section {
            margin-bottom: 30px;
        }

        .form-section h4 {
            margin-bottom: 20px;
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 10px;
        }

        .removeFounder {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .removeFounder:hover {
            background: #c82333;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid page__container">
    <div class="animated fadeIn main-table">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                        <div class="user-header d-flex align-items-center justify-content-between">
                            <div class="pull-left page__heading me-3 my-2">
                                {{ __('Create Investor') }}
                            </div>
                        </div>
                        <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                            <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto"></div>
                            <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto"></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('investors.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Section 1 - About Founder/s -->
                                <div class="form-section">
                                    <h4>About Founder/s</h4>
                                    <div id="founders">
                                        <div class="founder card card-body mb-3">
                                            <div class="form-group mb-3">
                                                <label for="founder-name-0">Name</label>
                                                <input type="text" class="form-control" name="founders[0][name]" id="founder-name-0" placeholder="Name">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="founder-qualification-0">Qualification</label>
                                                <input type="text" class="form-control" name="founders[0][qualification]" id="founder-qualification-0" placeholder="Qualification">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="founder-experience-0">Experience Summary</label>
                                                <textarea class="form-control" name="founders[0][experience_summary]" id="founder-experience-0" placeholder="Experience Summary"></textarea>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="founder-skills-0">Key Skills</label>
                                                <textarea class="form-control" name="founders[0][key_skills]" id="founder-skills-0" placeholder="Key Skills"></textarea>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="addFounder">Add Another Founder</button>
                                </div>

                                <!-- Section 2 - Concept of Venture -->
                                <div class="form-section">
                                    <h4>Concept of Venture</h4>
                                    <div class="form-group mb-3">
                                        <label for="problem_opportunity">Problem/Opportunity</label>
                                        <textarea class="form-control" name="problem_opportunity" id="problem_opportunity" placeholder="Problem/Opportunity"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="solution_technology">Solution/Technology</label>
                                        <textarea class="form-control" name="solution_technology" id="solution_technology" placeholder="Solution/Technology"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="current_stage">Current Stage</label>
                                        <textarea class="form-control" name="current_stage" id="current_stage" placeholder="Current Stage"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="product_demo">Product Demo</label>
                                        <input type="file" class="form-control" name="product_demo" id="product_demo">
                                    </div>
                                </div>

                                <!-- Section 3 - Business Model and Specification -->
                                <div class="form-section">
                                    <h4>Business Model and Specification</h4>
                                    <div class="form-group mb-3">
                                        <label for="unique_value_proposition">Unique Value Proposition</label>
                                        <textarea class="form-control" name="unique_value_proposition" id="unique_value_proposition" placeholder="Unique Value Proposition"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="competitive_advantage">Competitive Advantage</label>
                                        <textarea class="form-control" name="competitive_advantage" id="competitive_advantage" placeholder="Competitive Advantage"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="target_customer_segment">Target Customer Segment/Market Size</label>
                                        <textarea class="form-control" name="target_customer_segment" id="target_customer_segment" placeholder="Target Customer Segment/Market Size"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="channels_strategies">Channels and Strategies</label>
                                        <textarea class="form-control" name="channels_strategies" id="channels_strategies" placeholder="Channels and Strategies"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="revenue_streams">Revenue Streams</label>
                                        <textarea class="form-control" name="revenue_streams" id="revenue_streams" placeholder="Revenue Streams"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="costs_expenditures">Costs and Expenditures</label>
                                        <textarea class="form-control" name="costs_expenditures" id="costs_expenditures" placeholder="Costs and Expenditures"></textarea>
                                    </div>
                                </div>

                                <!-- Section 4 - Vision and Future Prospects -->
                                <div class="form-section">
                                    <h4>Vision and Future Prospects</h4>
                                    <div class="form-group mb-3">
                                        <label for="plan_24_month">24 Month Plan</label>
                                        <textarea class="form-control" name="plan_24_month" id="plan_24_month" placeholder="24 Month Plan"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="why_applying">Why Are You Applying</label>
                                        <textarea class="form-control" name="why_applying" id="why_applying" placeholder="Why Are You Applying"></textarea>
                                    </div>
                                </div>

                                <!-- Section 5 - Pitch Deck and Other Official Documents -->
                                <div class="form-section">
                                    <h4>Pitch Deck and Other Official Documents</h4>
                                    <div class="form-group mb-3">
                                        <label for="pitch_deck">Upload Your Pitch Deck</label>
                                        <input type="file" class="form-control" name="pitch_deck" id="pitch_deck">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="certificate_incorporation">Upload Your Certificate of Incorporation</label>
                                        <input type="file" class="form-control" name="certificate_incorporation" id="certificate_incorporation">
                                    </div>
                                </div>

                                <!-- Section 6 - Investment Ask -->
                                <div class="form-section">
                                    <h4>Investment Ask</h4>
                                    <div class="form-group mb-3">
                                        <label for="capital_required">Capital Required</label>
                                        <input type="number" class="form-control" name="capital_required" id="capital_required" placeholder="Capital Required">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="investment_preferred">Form of Investment Preferred</label>
                                        <select class="form-control" name="investment_preferred" id="investment_preferred">
                                            <option value="equity">Equity</option>
                                            <option value="debt">Debt</option>
                                            <option value="equity_debt_split">Equity/Debt Split</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="equity_amount_container" style="display: none;">
                                        <label for="equity_amount">Equity Amount</label>
                                        <input type="number" class="form-control" name="equity_amount" id="equity_amount" placeholder="Equity Amount">
                                    </div>
                                    <div class="form-group mb-3" id="debt_amount_container" style="display: none;">
                                        <label for="debt_amount">Debt Amount</label>
                                        <input type="number" class="form-control" name="debt_amount" id="debt_amount" placeholder="Debt Amount">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="equity_offered">Equity Offered Against Capital Ask</label>
                                        <input type="number" class="form-control" name="equity_offered" id="equity_offered" placeholder="Equity Offered">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
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
