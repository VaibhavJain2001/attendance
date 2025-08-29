@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📊 Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white mb-3">
                <div class="card-body">
                    <h4>{{ $totalEmployees }}</h4>
                    <p>Total Employees</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-success text-white mb-3">
                <div class="card-body">
                    <h4>{{ $presentToday }}</h4>
                    <p>Present Today</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-danger text-white mb-3">
                <div class="card-body">
                    <h4>{{ $absentToday }}</h4>
                    <p>Absent Today</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center bg-warning text-dark mb-3">
                <div class="card-body">
                    <h4>{{ $missingClockOut }}</h4>
                    <p>Missing Clock-Out</p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    
</div>
@endsection
