@extends('layouts.app', ['title' => 'Edit Transporter | Free Public Transport System', 'pageBadge' => 'Transporter Directory'])

@section('content')
    <div class="page-hero d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
        <div>
            <p class="page-eyebrow">Transporter Directory</p>
            <h1 class="page-title">Edit Transporter</h1>
            <p class="page-subtitle">Update the selected transporter record and save the latest contact details.</p>
        </div>
    </div>

    <section class="row g-4 stats-overlap">
        <div class="col-12">
            <div class="card section-card">
                <div class="card-header">
                    <h3 class="section-title">Transporter Details</h3>
                    <p class="section-copy">Adjust transporter information below.</p>
                </div>
                <div class="card-body">
                    @include('operators.partials.form')
                </div>
            </div>
        </div>
    </section>
@endsection
