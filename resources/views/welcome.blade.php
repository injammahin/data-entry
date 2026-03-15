@extends('layouts.guest')

@section('title', 'Welcome to LeadGen')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg bg-cover bg-center py-32 text-center text-white relative"
        style="background-image: url('https://via.placeholder.com/1500x800');">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="relative max-w-6xl mx-auto px-4">
            <h1 class="text-5xl font-extrabold mb-4 animate__animated animate__fadeInUp">Welcome to Lead Generation Software
            </h1>
            <p class="text-2xl mb-8 animate__animated animate__fadeInUp delay-1s">Effortlessly generate high-quality leads
                with powerful tools designed to boost your business growth.</p>
            <a href="{{ route('login') }}"
                class="inline-block bg-blue-600 text-white py-4 px-8 rounded-lg text-2xl font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg">Login
                to Start</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gradient-to-r from-blue-100 via-green-100 to-yellow-100">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4 animate__animated animate__fadeInUp">Our Key Features</h2>
            <p class="text-xl text-gray-600 animate__animated animate__fadeInUp delay-1s">Explore the features that make us
                a top choice for businesses looking to improve their lead generation process.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Feature Card 1 -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex justify-center items-center bg-blue-600 p-6 rounded-full mb-6">
                    <i class="fas fa-search fa-3x text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Powerful Search</h3>
                <p class="text-gray-600">Quickly search and filter leads by industry, region, and more to find your ideal
                    customers.</p>
            </div>

            <!-- Feature Card 2 -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex justify-center items-center bg-green-600 p-6 rounded-full mb-6">
                    <i class="fas fa-user-plus fa-3x text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Lead Capture</h3>
                <p class="text-gray-600">Capture and store lead information with ease using our integrated forms and
                    workflows.</p>
            </div>

            <!-- Feature Card 3 -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex justify-center items-center bg-yellow-600 p-6 rounded-full mb-6">
                    <i class="fas fa-chart-line fa-3x text-white"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Analytics Dashboard</h3>
                <p class="text-gray-600">Track your lead generation efforts in real-time with our dynamic,
                    easy-to-understand analytics dashboard.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-gray-50 border-b border-black">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4 animate__animated animate__fadeInUp">Why Choose LeadGen?</h2>
            <p class="text-xl text-gray-600 animate__animated animate__fadeInUp delay-1s">We provide a comprehensive,
                user-friendly platform for generating, managing, and nurturing leads that helps you grow your business
                faster and more efficiently.</p>
        </div>
    </section>

    <!-- USA State Business Leads Section -->
    <section class="py-20 bg-blue-50">
        <div class="max-w-6xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">USA State-wise Business Leads</h2>
            <p class="text-xl text-gray-600 mb-12">Generate high-quality leads across various states in the USA. Access
                specific business leads by state and industry to find the most relevant opportunities.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 max-w-6xl mx-auto">
            <!-- State Cards -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <p class="text-xl font-semibold text-gray-800 mb-4">California</p>
                <p class="text-gray-600">Explore top business leads in California to scale your sales process effectively.
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <p class="text-xl font-semibold text-gray-800 mb-4">Texas</p>
                <p class="text-gray-600">Access high-quality business leads across various industries in Texas.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <p class="text-xl font-semibold text-gray-800 mb-4">New York</p>
                <p class="text-gray-600">Find the best business leads in New York to boost your business success.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <p class="text-xl font-semibold text-gray-800 mb-4">Florida</p>
                <p class="text-gray-600">Discover top opportunities for business leads in sunny Florida.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                <p class="text-xl font-semibold text-gray-800 mb-4">Illinois</p>
                <p class="text-gray-600">Unlock key business leads across industries in Illinois for better conversion
                    rates.</p>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-20 bg-gray-900 text-center text-white">
        <h2 class="text-4xl font-extrabold mb-4">Start Generating Leads Today!</h2>
        <p class="text-xl mb-8">Don’t miss out on valuable business opportunities. Sign up today and start growing your
            business with LeadGen.</p>
        <a href="{{ route('register') }}"
            class="inline-block bg-yellow-600 text-white py-4 px-8 rounded-lg text-2xl font-semibold hover:bg-yellow-700 transform hover:scale-105 transition-all duration-300 shadow-lg">Sign
            Up Now</a>
    </section>
@endsection