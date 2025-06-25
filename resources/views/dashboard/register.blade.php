@extends('layouts.app')

@section('title', 'Event Registration')

@section('content')
<!-- Page Banner -->
<x-page-banner 
    title="Event Registration" 
    subtitle="Join the excitement and book your tickets for upcoming boxing events"
    :breadcrumbs="[
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'Registration']
    ]" />

<div class="registration-container">
    <div class="container">
        <div class="registration-content">
            <!-- Registration Header -->
            <div class="registration-header">
                <h1 class="registration-title">Boxing Event Registration</h1>
                <p class="registration-subtitle">Secure your spot at the next championship bout</p>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection 