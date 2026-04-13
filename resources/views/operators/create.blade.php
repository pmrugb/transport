@extends('layouts.app', ['title' => 'Add new Transporters | Free Public Transport System', 'pageBadge' => 'Transporter Registration'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Transporter Setup</p>
            <h1 class="page-title">Add New Transporters</h1>
            <p class="page-subtitle">Register a new transporter and keep contact and district information in one place.</p>
        </div>
    </div>


    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card" id="company-form">
                <div class="card-header">
                    <h3 class="section-title">Transporter Registration</h3>
                    <p class="section-copy">Add either a company or a private transporter profile.</p>
                </div>
                <div class="card-body">
                    @include('operators.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
