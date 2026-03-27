@extends('layouts.guest')

@section('title', 'Welcome to LeadGen')

@section('content')
    @php
        $features = [
            [
                'title' => 'Smart Lead Search',
                'desc' => 'Find highly targeted business leads by state, city, industry, and company attributes with a fast and intuitive search experience.',
                'icon' => 'search',
                'gradient' => 'from-cyan-500 to-blue-600',
            ],
            [
                'title' => 'Easy Lead Management',
                'desc' => 'Save, organize, and manage your prospect lists smoothly so your team can focus on conversion instead of manual tracking.',
                'icon' => 'users',
                'gradient' => 'from-fuchsia-500 to-pink-600',
            ],
            [
                'title' => 'Insightful Analytics',
                'desc' => 'Track usage, growth, and performance trends from a visually rich dashboard built to help you make better decisions.',
                'icon' => 'chart',
                'gradient' => 'from-amber-400 to-orange-500',
            ],
            [
                'title' => 'Fast CSV Export',
                'desc' => 'Export selected records quickly and build sales-ready lead lists for outreach, campaigns, or internal distribution.',
                'icon' => 'download',
                'gradient' => 'from-emerald-400 to-teal-500',
            ],
            [
                'title' => 'Role Based Access',
                'desc' => 'Control who can view, search, export, and manage data with secure permission-based access for your whole team.',
                'icon' => 'lock',
                'gradient' => 'from-violet-500 to-indigo-600',
            ],
            [
                'title' => 'Campaign Ready Data',
                'desc' => 'Work with well-structured business lead data that helps your sales and marketing team move faster with confidence.',
                'icon' => 'rocket',
                'gradient' => 'from-rose-500 to-red-500',
            ],
        ];

        $states = [
            [
                'name' => 'California',
                'desc' => 'Strong opportunities across startups, tech firms, agencies, and service businesses.',
                'tag' => 'High Demand',
            ],
            [
                'name' => 'Texas',
                'desc' => 'A powerful market for B2B, local services, industrial, and growing companies.',
                'tag' => 'Fast Growing',
            ],
            [
                'name' => 'New York',
                'desc' => 'Premium lead opportunities for finance, media, legal, and enterprise sectors.',
                'tag' => 'Premium Market',
            ],
            [
                'name' => 'Florida',
                'desc' => 'Great coverage for hospitality, healthcare, local services, and retail businesses.',
                'tag' => 'Hot Region',
            ],
            [
                'name' => 'Illinois',
                'desc' => 'Useful data for logistics, manufacturing, consultancy, and urban business clusters.',
                'tag' => 'Stable Market',
            ],
            [
                'name' => 'Georgia',
                'desc' => 'A growing lead source for logistics, construction, healthcare, and business services.',
                'tag' => 'Rising State',
            ],
            [
                'name' => 'Arizona',
                'desc' => 'Promising opportunities in real estate, medical, trade, and local business niches.',
                'tag' => 'Opportunity',
            ],
            [
                'name' => 'North Carolina',
                'desc' => 'Valuable lead sources for tech, education, distribution, and growing businesses.',
                'tag' => 'Emerging',
            ],
        ];
    @endphp

    <style>
        .leadgen-page {
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, 0.22), transparent 28%),
                radial-gradient(circle at top right, rgba(168, 85, 247, 0.18), transparent 26%),
                radial-gradient(circle at bottom left, rgba(34, 197, 94, 0.16), transparent 25%),
                linear-gradient(180deg, #06131f 0%, #0b1220 28%, #0f172a 100%);
            overflow: hidden;
            position: relative;
        }

        .leadgen-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent);
            pointer-events: none;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow:
                0 10px 35px rgba(15, 23, 42, 0.30),
                inset 0 1px 0 rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .glass-card-light {
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.50);
            box-shadow:
                0 18px 50px rgba(15, 23, 42, 0.10),
                inset 0 1px 0 rgba(255, 255, 255, 0.60);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .hero-orb,
        .floating-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(18px);
            opacity: 0.55;
            pointer-events: none;
            animation: floatY 8s ease-in-out infinite;
        }

        .hero-orb.one {
            width: 320px;
            height: 320px;
            top: 80px;
            left: -80px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.65), rgba(34, 211, 238, 0.40));
        }

        .hero-orb.two {
            width: 260px;
            height: 260px;
            top: 40px;
            right: -60px;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.70), rgba(236, 72, 153, 0.35));
            animation-delay: 1.5s;
        }

        .hero-orb.three {
            width: 240px;
            height: 240px;
            bottom: 30px;
            left: 20%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.60), rgba(59, 130, 246, 0.35));
            animation-delay: 0.8s;
        }

        .floating-orb.small {
            width: 130px;
            height: 130px;
            background: rgba(255, 255, 255, 0.10);
            filter: blur(8px);
            opacity: 0.25;
        }

        .shine-border {
            position: relative;
            overflow: hidden;
        }

        .shine-border::after {
            content: "";
            position: absolute;
            top: 0;
            left: -130%;
            width: 70%;
            height: 100%;
            background: linear-gradient(100deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.16) 45%,
                    rgba(255, 255, 255, 0.38) 50%,
                    rgba(255, 255, 255, 0.12) 55%,
                    transparent 100%);
            transform: skewX(-20deg);
            transition: left 0.9s ease;
        }

        .shine-border:hover::after {
            left: 170%;
        }

        .feature-card,
        .state-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .feature-card:hover,
        .state-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
            border-color: rgba(99, 102, 241, 0.26);
        }

        .hero-title {
            background: linear-gradient(90deg, #ffffff 0%, #c4f1ff 35%, #e9d5ff 70%, #ffffff 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gradient-text {
            background: linear-gradient(90deg, #06b6d4 0%, #8b5cf6 55%, #ec4899 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .section-glow {
            position: absolute;
            inset: auto 0 0 0;
            height: 180px;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.18), transparent 60%);
            pointer-events: none;
        }

        .animate-fade-up {
            opacity: 0;
            transform: translateY(28px);
            animation: fadeUp 0.9s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.15s;
        }

        .delay-2 {
            animation-delay: 0.3s;
        }

        .delay-3 {
            animation-delay: 0.45s;
        }

        .delay-4 {
            animation-delay: 0.6s;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-18px);
            }
        }

        @keyframes pulseSoft {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.9;
            }

            50% {
                transform: scale(1.08);
                opacity: 1;
            }
        }

        .pulse-soft {
            animation: pulseSoft 3.5s ease-in-out infinite;
        }

        .stats-chip {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .cta-gradient {
            background:
                radial-gradient(circle at top left, rgba(34, 211, 238, 0.25), transparent 30%),
                radial-gradient(circle at bottom right, rgba(236, 72, 153, 0.18), transparent 28%),
                linear-gradient(135deg, #0f172a 0%, #111827 50%, #0b1220 100%);
        }
    </style>

    <div class="leadgen-page min-h-screen text-white">
        <!-- Floating Orbs -->
        <div class="hero-orb one"></div>
        <div class="hero-orb two"></div>
        <div class="hero-orb three"></div>
        <div class="floating-orb small top-32 right-[16%] hidden lg:block"></div>
        <div class="floating-orb small bottom-[28rem] left-[10%] hidden lg:block" style="animation-delay: 1s;"></div>

        <!-- Hero -->
        <section class="relative pt-16 pb-24 lg:pt-24 lg:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm text-cyan-100 backdrop-blur-md animate-fade-up">
                            <span class="h-2.5 w-2.5 rounded-full bg-cyan-400 pulse-soft"></span>
                            Modern Business Lead Generation Platform
                        </div>

                        <h1
                            class="hero-title mt-6 text-5xl md:text-6xl xl:text-7xl font-black leading-[1.05] animate-fade-up delay-1">
                            Find Better Leads.
                            <span class="block">Grow Faster.</span>
                        </h1>

                        <p class="mt-6 max-w-2xl text-lg md:text-xl text-slate-200/90 leading-8 animate-fade-up delay-2">
                            Beautifully organized lead data, powerful search, smart exports, and a premium user experience
                            built to help your team discover real business opportunities across the USA.
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row gap-4 animate-fade-up delay-3">
                            <a href="{{ route('login') }}"
                                class="shine-border inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-600 px-8 py-4 text-lg font-bold text-white shadow-2xl shadow-cyan-500/20 transition duration-300 hover:-translate-y-1 hover:shadow-cyan-500/30">
                                Login to Start
                            </a>
                        </div>

                        <div class="mt-10 grid grid-cols-2 md:grid-cols-3 gap-4 animate-fade-up delay-4">
                            <div class="stats-chip rounded-2xl p-4">
                                <div class="text-2xl font-black text-white">50+</div>
                                <div class="mt-1 text-sm text-slate-300">States Coverage</div>
                            </div>
                            <div class="stats-chip rounded-2xl p-4">
                                <div class="text-2xl font-black text-white">Fast</div>
                                <div class="mt-1 text-sm text-slate-300">Search Experience</div>
                            </div>
                            <div class="stats-chip rounded-2xl p-4 col-span-2 md:col-span-1">
                                <div class="text-2xl font-black text-white">Secure</div>
                                <div class="mt-1 text-sm text-slate-300">Role Based Access</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative animate-fade-up delay-2">
                        <div class="glass-card rounded-[2rem] p-4 sm:p-6 border border-white/10">
                            <div
                                class="rounded-[1.7rem] bg-gradient-to-br from-white/10 to-white/5 p-5 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-cyan-200">LeadGen Dashboard</p>
                                        <h3 class="mt-1 text-2xl font-bold text-white">Sales Intelligence Panel</h3>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                        <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
                                        <span class="h-3 w-3 rounded-full bg-green-400"></span>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4">
                                    <div class="glass-card rounded-2xl p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Lead Search</p>
                                                <p class="mt-2 text-lg font-semibold text-white">California • Healthcare</p>
                                            </div>
                                            <div
                                                class="rounded-xl bg-cyan-400/20 px-3 py-2 text-cyan-200 text-sm font-semibold">
                                                2,340 Results
                                            </div>
                                        </div>
                                        <div class="mt-4 h-2 rounded-full bg-white/10 overflow-hidden">
                                            <div
                                                class="h-full w-[82%] rounded-full bg-gradient-to-r from-cyan-400 to-blue-500">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div class="glass-card rounded-2xl p-4">
                                            <p class="text-sm text-slate-300">Saved Lists</p>
                                            <p class="mt-2 text-3xl font-black text-white">128</p>
                                            <p class="mt-2 text-sm text-emerald-300">+12 this week</p>
                                        </div>
                                        <div class="glass-card rounded-2xl p-4">
                                            <p class="text-sm text-slate-300">Export Ready Leads</p>
                                            <p class="mt-2 text-3xl font-black text-white">18.6K</p>
                                            <p class="mt-2 text-sm text-cyan-300">Optimized for campaigns</p>
                                        </div>
                                    </div>

                                    <div class="glass-card rounded-2xl p-4">
                                        <p class="text-sm text-slate-300 mb-4">Popular States</p>
                                        <div class="space-y-3">
                                            <div>
                                                <div class="flex items-center justify-between text-sm mb-1">
                                                    <span class="text-white font-medium">California</span>
                                                    <span class="text-slate-300">92%</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                    <div
                                                        class="h-full w-[92%] rounded-full bg-gradient-to-r from-fuchsia-500 to-violet-500">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center justify-between text-sm mb-1">
                                                    <span class="text-white font-medium">Texas</span>
                                                    <span class="text-slate-300">84%</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                    <div
                                                        class="h-full w-[84%] rounded-full bg-gradient-to-r from-cyan-400 to-blue-500">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center justify-between text-sm mb-1">
                                                    <span class="text-white font-medium">Florida</span>
                                                    <span class="text-slate-300">76%</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                    <div
                                                        class="h-full w-[76%] rounded-full bg-gradient-to-r from-emerald-400 to-teal-500">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -left-6 glass-card rounded-2xl px-5 py-4 hidden md:block">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Data Quality</p>
                            <p class="mt-1 text-xl font-black text-white">Clean. Fast. Actionable.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-glow"></div>
        </section>

        <!-- Features -->
        <section class="relative py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span
                        class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-cyan-200 border border-white/10 backdrop-blur-md">
                        Premium Features
                    </span>
                    <h2 class="mt-5 text-4xl md:text-5xl font-black text-white">
                        Designed for <span class="gradient-text">speed, clarity, and growth</span>
                    </h2>
                    <p class="mt-5 text-lg text-slate-300 leading-8">
                        Every section is crafted to feel modern, polished, and conversion-focused so your software looks
                        premium from the very first screen.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-7">
                    @foreach ($features as $index => $feature)
                        <div class="feature-card glass-card-light shine-border rounded-[1.8rem] p-7 text-slate-800 animate-fade-up"
                            style="animation-delay: {{ 0.08 * ($index + 1) }}s;">
                            <div
                                class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br {{ $feature['gradient'] }} text-white shadow-xl">
                                @if ($feature['icon'] === 'search')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0a7 7 0 0114 0z" />
                                    </svg>
                                @elseif($feature['icon'] === 'users')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 20a4 4 0 00-8 0m8 0H5m12 0h2m-9-9a4 4 0 110-8a4 4 0 010 8zm7 1a3 3 0 100-6a3 3 0 000 6z" />
                                    </svg>
                                @elseif($feature['icon'] === 'chart')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 5-5M5 19h14" />
                                    </svg>
                                @elseif($feature['icon'] === 'download')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                                    </svg>
                                @elseif($feature['icon'] === 'lock')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 10V7a4 4 0 10-8 0v3m-1 0h10a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @endif
                            </div>

                            <h3 class="text-2xl font-bold text-slate-900">{{ $feature['title'] }}</h3>
                            <p class="mt-4 text-slate-600 leading-7">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Why Choose -->
        <section class="relative py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="glass-card rounded-[2rem] p-8 md:p-12">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <span
                                class="inline-flex rounded-full bg-cyan-400/10 px-4 py-2 text-sm font-medium text-cyan-200 border border-cyan-300/10">
                                Why Choose LeadGen
                            </span>
                            <h2 class="mt-5 text-4xl md:text-5xl font-black text-white leading-tight">
                                A premium experience for serious lead generation
                            </h2>
                            <p class="mt-6 text-lg text-slate-300 leading-8">
                                LeadGen is built to make lead discovery feel smooth, powerful, and professional. From
                                elegant search to clean management and faster exports, every part of the platform helps
                                your business move with confidence.
                            </p>

                            <div class="mt-8 space-y-4">
                                <div class="flex gap-4">
                                    <div class="mt-1 h-6 w-6 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500"></div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">Modern and trustworthy UI</h4>
                                        <p class="text-slate-300">Glassy cards, gradients, elegant spacing, and polished
                                            interactions.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 h-6 w-6 rounded-full bg-gradient-to-r from-fuchsia-500 to-violet-500">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">Faster business workflow</h4>
                                        <p class="text-slate-300">Search, save, filter, and export leads with less friction
                                            and more control.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="mt-1 h-6 w-6 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-white">Built to impress clients and teams</h4>
                                        <p class="text-slate-300">A landing page that makes your software look premium and
                                            more valuable.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="glass-card rounded-3xl p-6">
                                <p class="text-sm text-slate-300">Search Speed</p>
                                <h3 class="mt-3 text-3xl font-black text-white">Lightning Fast</h3>
                                <p class="mt-3 text-slate-300">Get to the right lead list quickly and keep your workflow
                                    moving.</p>
                            </div>
                            <div class="glass-card rounded-3xl p-6">
                                <p class="text-sm text-slate-300">User Experience</p>
                                <h3 class="mt-3 text-3xl font-black text-white">Premium Feel</h3>
                                <p class="mt-3 text-slate-300">A clean visual experience that feels modern and high-end.</p>
                            </div>
                            <div class="glass-card rounded-3xl p-6">
                                <p class="text-sm text-slate-300">Data Handling</p>
                                <h3 class="mt-3 text-3xl font-black text-white">Well Organized</h3>
                                <p class="mt-3 text-slate-300">Perfect for campaign preparation, team collaboration, and
                                    follow-up.</p>
                            </div>
                            <div class="glass-card rounded-3xl p-6">
                                <p class="text-sm text-slate-300">Scalability</p>
                                <h3 class="mt-3 text-3xl font-black text-white">Growth Ready</h3>
                                <p class="mt-3 text-slate-300">An interface built to grow with your software and your users.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- States -->
        <section class="relative py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span
                        class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-fuchsia-200 border border-white/10 backdrop-blur-md">
                        USA Market Coverage
                    </span>
                    <h2 class="mt-5 text-4xl md:text-5xl font-black text-white">
                        State-wise business lead opportunities
                    </h2>
                    <p class="mt-5 text-lg text-slate-300 leading-8">
                        Presenting states as premium cards makes the landing page more visual, more useful, and much more
                        attractive for first-time visitors.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    @foreach ($states as $index => $state)
                        <div class="state-card glass-card-light rounded-[1.7rem] p-6 animate-fade-up"
                            style="animation-delay: {{ 0.06 * ($index + 1) }}s;">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-2xl font-bold text-slate-900">{{ $state['name'] }}</h3>
                                <span
                                    class="rounded-full bg-slate-900 text-white text-xs font-semibold px-3 py-1.5 whitespace-nowrap">
                                    {{ $state['tag'] }}
                                </span>
                            </div>
                            <p class="mt-4 text-slate-600 leading-7">{{ $state['desc'] }}</p>

                            <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-indigo-600">
                                <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                                Ready for targeted search
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="cta-gradient rounded-[2rem] border border-white/10 px-6 py-12 md:px-12 md:py-16 text-center shadow-2xl">
                    <span
                        class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-cyan-200 border border-white/10 backdrop-blur-md">
                        Get Started Today
                    </span>

                    <h2 class="mt-6 text-4xl md:text-5xl font-black text-white">
                        Start generating better leads today
                    </h2>

                    <p class="mt-5 max-w-3xl mx-auto text-lg md:text-xl text-slate-300 leading-8">
                        Turn your platform into a premium experience that feels modern, attractive, and business-ready from
                        the very first impression.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/10 px-8 py-4 text-lg font-semibold text-white backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-white/15">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection