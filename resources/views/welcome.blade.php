@extends('layouts.guest')

@section('title', 'Welcome to LeadGen')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg py-20 text-center text-white">
        <div class="max-w-4xl mx-auto">
            <h1 class="section-title mb-4">Welcome to Lead Generation Software</h1>
            <p class="section-subtitle mb-8">Effortlessly generate high-quality leads with powerful tools designed to boost your business growth.</p>
            <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-300">Login to Start</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="max-w-6xl mx-auto text-center mb-12 section-container">
            <h2 class="text-3xl font-bold mb-4">Our Key Features</h2>
            <p class="text-gray-600 text-lg">Explore the features that make us a top choice for businesses looking to improve their lead generation process.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Feature Card 1 -->
            <div class="rounded-card">
                <div class="flex justify-center items-center bg-blue-100 p-6 rounded-full mb-6">
                    <i class="fas fa-search fa-3x text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Powerful Search</h3>
                <p class="text-gray-600">Quickly search and filter leads by industry, region, and more to find your ideal customers.</p>
            </div>

            <!-- Feature Card 2 -->
            <div class="rounded-card">
                <div class="flex justify-center items-center bg-green-100 p-6 rounded-full mb-6">
                    <i class="fas fa-user-plus fa-3x text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Lead Capture</h3>
                <p class="text-gray-600">Capture and store lead information with ease using our integrated forms and workflows.</p>
            </div>

            <!-- Feature Card 3 -->
            <div class="rounded-card">
                <div class="flex justify-center items-center bg-yellow-100 p-6 rounded-full mb-6">
                    <i class="fas fa-chart-line fa-3x text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Analytics Dashboard</h3>
                <p class="text-gray-600">Track your lead generation efforts in real-time with our dynamic, easy-to-understand analytics dashboard.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gray-50 border-b border-black">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Why Choose LeadGen?</h2>
            <p class="text-gray-600 text-lg">We provide a comprehensive, user-friendly platform for generating, managing, and nurturing leads that helps you grow your business faster and more efficiently.</p>
        </div>
    </section>
@endsection