@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Employee Attendance List ({{ now()->format('F Y') }})</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Email</th>
                <th>Present Days</th>
                <th>Absent Days</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->present_count }}</td>
                    <td>{{ $emp->absent_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
