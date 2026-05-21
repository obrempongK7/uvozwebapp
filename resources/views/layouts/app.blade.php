<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'UVOZ')) – Speak. Connect. Earn.</title>
    <meta name="description" content="@yield('meta_description', 'Voice-first social network where you earn from authentic engagement.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|montserrat:500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN (production should use compiled) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            purple: '#8B5CF6',
                            pink:   '#06B6D4',
                            amber:  '#10B981',
                        },
                        dark: {
                            bg:      '#0b1326',
                            surface: '#131b2e',
                            card:    '#171f33',
                            border:  '#222a3d',
                            hover:   '#2d3449',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    borderRadius: { '2xl': '16px', '3xl': '24px', '4xl': '32px' },
                    backgroundImage: {
                        'brand-gradient': 'linear-gradient(135deg, #8B5CF6 0%, #06B6D4 50%, #10B981 100%)',
                        'card-gradient':  'linear-gradient(145deg, #131b2e 0%, #171f33 100%)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .gradient-text { font-family: 'Montserrat', sans-serif !important; }
        body { background: #0b1326; color: #FFFFFF; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0b1326; }
        ::-webkit-scrollbar-thumb { background: #222a3d; border-radius: 4px; }
        .gradient-text { background: linear-gradient(135deg, #8B5CF6, #06B6D4, #10B981); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-bg  { background: linear-gradient(135deg, #8B5CF6 0%, #06B6D4 50%, #10B981 100%); }
        .glass { background: rgba(19,27,46,0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.05); }
        .card { background: #131b2e; border: 1px solid #222a3d; border-radius: 24px; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { background: #171f33; border-color: #8B5CF6; transform: translateY(-2px); box-shadow: 0 8px 40px rgba(139,92,246,0.15); }
        .btn-primary { background: linear-gradient(135deg, #8B5CF6, #06B6D4); color: white; border-radius: 16px; padding: 10px 24px; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(139,92,246,0.4); }
        .btn-secondary { background: #222a3d; color: #CBD5E1; border-radius: 16px; padding: 10px 24px; font-weight: 500; transition: all 0.3s ease; border: 1px solid #222a3d; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .btn-secondary:hover { background: #2d3449; border-color: #8B5CF6; color: white; }
        .input-field { background: #222a3d; border: 1px solid #2d3449; border-radius: 14px; padding: 12px 16px; color: white; width: 100%; transition: all 0.3s ease; outline: none; }
        .input-field:focus { border-color: #8B5CF6; box-shadow: 0 0 0 3px rgba(139,92,246,0.15); }
        .input-field::placeholder { color: #64748B; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 16px; color: #CBD5E1; transition: all 0.3s ease; font-weight: 500; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(139,92,246,0.15); color: white; }
        .sidebar-link.active { color: #d0bcff; }
        .audio-player { background: #131b2e; border: 1px solid #222a3d; border-radius: 20px; padding: 16px; }
        .progress-bar { height: 4px; background: #222a3d; border-radius: 4px; cursor: pointer; position: relative; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #8B5CF6, #06B6D4); border-radius: 4px; transition: width 0.1s linear; }
        .skeleton { background: linear-gradient(90deg, #222a3d 25%, #2d3449 50%, #222a3d 75%); background-size: 200% 100%; animation: skeleton-loading 1.5s infinite; border-radius: 12px; }
        @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-purple { background: rgba(139,92,246,0.2); color: #d0bcff; border: 1px solid rgba(139,92,246,0.3); }
        .badge-green { background: rgba(16,185,129,0.2); color: #4edea3; border: 1px solid rgba(16,185,129,0.3); }
        .badge-amber { background: rgba(16,185,129,0.2); color: #6ffbbe; border: 1px solid rgba(16,185,129,0.3); }
        .badge-pink { background: rgba(6,182,212,0.2); color: #acedff; border: 1px solid rgba(6,182,212,0.3); }
        .points-display { background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(6,182,212,0.2)); border: 1px solid rgba(139,92,246,0.3); border-radius: 20px; padding: 4px 14px; font-weight: 700; color: #d0bcff; font-size: 14px; }
        .toast { position: fixed; bottom: 24px; right: 24px; z-index: 9999; max-width: 380px; }
        .notification-dot { width: 8px; height: 8px; background: #06B6D4; border-radius: 50%; position: absolute; top: 2px; right: 2px; }
        @media (max-width: 767px) { .sidebar-desktop { display: none; } }
        @media (min-width: 1024px) { .sidebar-mobile-overlay { display: none; } }
    </style>

    @yield('head')
</head>
<body x-data="{ sidebarOpen: false, notifOpen: false }">

<!-- Toast Notifications -->
@if(session('success'))
<div class="toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show=false, 5000)" x-transition>
    <div class="card p-4 flex items-center gap-3 border-l-4 border-green-500">
        <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-sm text-slate-200">{{ session('success') }}</p>
        <button @click="show=false" class="ml-auto text-slate-400 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
</div>
@endif
@if(session('error') || $errors->any())
<div class="toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show=false, 7000)" x-transition>
    <div class="card p-4 flex items-center gap-3 border-l-4 border-red-500">
        <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-sm text-slate-200">{{ session('error') ?? $errors->first() }}</p>
        <button @click="show=false" class="ml-auto text-slate-400 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
</div>
@endif

<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 bg-black/60 z-40 lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-50 glass border-b border-dark-border h-16">
    <div class="flex items-center justify-between h-full px-4 lg:px-6">
        <!-- Left: Logo + hamburger -->
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden p-2 text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 gradient-bg rounded-xl flex items-center justify-center font-black text-white text-sm">U</div>
                <span class="text-xl font-black gradient-text hidden sm:block">UVOZ</span>
            </a>
        </div>

        <!-- Center: Search -->
        <div class="hidden md:flex flex-1 max-w-md mx-6">
            <form action="{{ route('search') }}" class="w-full relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input name="q" value="{{ request('q') }}" placeholder="Search voices, people, podcasts..." class="input-field pl-10 py-2.5 text-sm">
            </form>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
            @auth
            <!-- Points balance -->
            <div class="hidden sm:flex points-display">
                <svg class="w-4 h-4 mr-1 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                {{ number_format(auth()->user()->wallet?->points_balance ?? 0) }}
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open=!open" class="relative p-2 text-slate-400 hover:text-white rounded-xl hover:bg-dark-hover transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="notification-dot"></span>
                    @endif
                </button>
                <div x-show="open" @click.outside="open=false" x-transition class="absolute right-0 top-12 w-80 card shadow-2xl z-50 overflow-hidden">
                    <div class="p-4 border-b border-dark-border flex items-center justify-between">
                        <h3 class="font-semibold text-white">Notifications</h3>
                        <a href="{{ route('notification.read-all') }}" class="text-xs text-purple-400 hover:text-purple-300" onclick="event.preventDefault();document.getElementById('mark-all-form').submit()">Mark all read</a>
                        <form id="mark-all-form" action="{{ route('notification.read-all') }}" method="POST" class="hidden">@csrf</form>
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-dark-border">
                        @forelse(auth()->user()->notifications()->limit(5)->get() as $notif)
                        @php $data = $notif->data; @endphp
                        <a href="{{ $data['url'] ?? '#' }}" class="flex items-start gap-3 p-3 hover:bg-dark-hover transition-colors {{ $notif->read_at ? 'opacity-60' : '' }}">
                            <div class="w-8 h-8 gradient-bg rounded-full flex-shrink-0 flex items-center justify-center text-xs text-white font-bold mt-0.5">
                                {{ strtoupper(substr($data['title'] ?? 'N', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $data['title'] ?? 'Notification' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $data['message'] ?? '' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        @empty
                        <div class="p-6 text-center text-slate-400 text-sm">No notifications yet</div>
                        @endforelse
                    </div>
                    <div class="p-3 border-t border-dark-border">
                        <a href="{{ route('notification.index') }}" class="block text-center text-sm text-purple-400 hover:text-purple-300">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open=!open" class="flex items-center gap-2 p-1 rounded-xl hover:bg-dark-hover transition-colors">
                    <img src="{{ auth()->user()->avatar_url }}" alt="avatar" class="w-8 h-8 rounded-full object-cover border border-dark-border">
                    <span class="hidden sm:block text-sm font-medium text-white max-w-24 truncate">{{ auth()->user()->display_name }}</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.outside="open=false" x-transition class="absolute right-0 top-12 w-56 card shadow-2xl z-50 overflow-hidden py-2">
                    <a href="{{ route('profile.show', auth()->user()->username) }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-dark-hover text-sm text-slate-200 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    <a href="{{ route('wallet.index') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-dark-hover text-sm text-slate-200 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Wallet
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-dark-hover text-sm text-slate-200 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Settings
                    </a>
                    @if(auth()->user()->isAdmin())
                    <div class="border-t border-dark-border my-1"></div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-dark-hover text-sm text-purple-400 hover:text-purple-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Admin Panel
                    </a>
                    @endif
                    <div class="border-t border-dark-border my-1"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-dark-hover text-sm text-red-400 hover:text-red-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-secondary text-sm">Login</a>
            <a href="{{ route('register') }}" class="btn-primary text-sm">Get Started</a>
            @endauth
        </div>
    </div>
</header>

<!-- Main Layout -->
<div class="flex pt-16 min-h-screen">
    @auth
    <!-- Left Sidebar -->
    <aside class="sidebar-desktop fixed left-0 top-16 bottom-0 w-64 glass border-r border-dark-border overflow-y-auto z-30 flex flex-col py-4 px-3"
           :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
           style="transition: transform 0.3s ease; @media(min-width:1024px){transform:none;}">
        <nav class="flex flex-col gap-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('feed.index') }}" class="sidebar-link {{ request()->routeIs('feed.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2"/></svg>
                Feed
            </a>
            <a href="{{ route('explore') }}" class="sidebar-link {{ request()->routeIs('explore') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                Explore
            </a>

            <div class="mt-4 mb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Create</div>

            <a href="{{ route('voice.create') }}" class="sidebar-link {{ request()->routeIs('voice.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                Voice Hub
            </a>
            <a href="{{ route('status.index') }}" class="sidebar-link {{ request()->routeIs('status.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Status Hub
            </a>
            <a href="{{ route('podcast.index') }}" class="sidebar-link {{ request()->routeIs('podcast.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414m2.828-9.9a9 9 0 0112.728 0"/></svg>
                Podcasts
            </a>
            <a href="{{ route('live.index') }}" class="sidebar-link {{ request()->routeIs('live.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                Live Rooms
            </a>
            <a href="{{ route('campaign.index') }}" class="sidebar-link {{ request()->routeIs('campaign.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Campaigns
            </a>

            <div class="mt-4 mb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Earnings</div>

            <a href="{{ route('wallet.index') }}" class="sidebar-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Wallet
            </a>
            <a href="{{ route('withdrawal.index') }}" class="sidebar-link {{ request()->routeIs('withdrawal.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Withdraw
            </a>
            <a href="{{ route('referrals.index') }}" class="sidebar-link {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Referrals
            </a>

            <div class="mt-4 mb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Social</div>

            <a href="{{ route('message.index') }}" class="sidebar-link {{ request()->routeIs('message.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Messages
            </a>
            <a href="{{ route('search') }}" class="sidebar-link {{ request()->routeIs('search') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Search
            </a>
        </nav>

        <!-- Upgrade CTA (for free users) -->
        @if(!auth()->user()->subscription || auth()->user()->subscription->plan->isFree())
        <div class="mt-auto mx-3 mb-4 p-4 rounded-2xl" style="background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(6,182,212,0.2)); border: 1px solid rgba(139,92,246,0.3);">
            <p class="text-xs font-semibold text-purple-300 mb-1">Upgrade to Pro</p>
            <p class="text-xs text-slate-400 mb-3">5000 pts/day + 70% boost</p>
            <a href="{{ route('subscribe.plans') }}" class="btn-primary text-xs py-2 px-3 w-full justify-center">Upgrade Now</a>
        </div>
        @endif
    </aside>
    @endauth

    <!-- Main Content -->
    <main class="flex-1 {{ auth()->check() ? 'lg:ml-64' : '' }} min-h-screen">
        <div class="@yield('container_class', 'max-w-5xl mx-auto px-4 py-6')">
            @yield('content')
        </div>
    </main>
</div>

<!-- Global Audio Player (sticky bottom) -->
<div id="global-audio-player" class="hidden fixed bottom-0 left-0 right-0 z-50 glass border-t border-dark-border p-3"
     x-data="audioPlayer()" x-show="playing || paused">
    <div class="max-w-5xl mx-auto flex items-center gap-4">
        <img id="player-cover" src="" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 hidden">
        <div class="flex-1 min-w-0">
            <p id="player-title" class="text-sm font-semibold text-white truncate"></p>
            <p id="player-artist" class="text-xs text-slate-400 truncate"></p>
            <div class="progress-bar mt-2" @click="seek($event)">
                <div class="progress-fill" :style="{ width: progress + '%' }"></div>
            </div>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <span class="text-xs text-slate-400" x-text="currentTime"></span>
            <button @click="togglePlay()" class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center">
                <svg x-show="!playing" class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                <svg x-show="playing" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>
            <span class="text-xs text-slate-400" x-text="duration"></span>
            <button @click="close()" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
</div>

<audio id="global-audio"></audio>

<script>
function audioPlayer() {
    return {
        playing: false, paused: false, progress: 0, currentTime: '0:00', duration: '0:00',
        audio: null,
        init() {
            this.audio = document.getElementById('global-audio');
            this.audio.addEventListener('timeupdate', () => {
                if (this.audio.duration) {
                    this.progress = (this.audio.currentTime / this.audio.duration) * 100;
                    this.currentTime = this.formatTime(this.audio.currentTime);
                }
            });
            this.audio.addEventListener('loadedmetadata', () => {
                this.duration = this.formatTime(this.audio.duration);
            });
            this.audio.addEventListener('ended', () => { this.playing = false; this.paused = true; });
        },
        formatTime(s) { return Math.floor(s/60)+':'+(Math.floor(s%60)+'').padStart(2,'0'); },
        togglePlay() { if (this.playing) { this.audio.pause(); this.playing=false; this.paused=true; } else { this.audio.play(); this.playing=true; this.paused=false; } },
        seek(e) { const rect = e.currentTarget.getBoundingClientRect(); const x=(e.clientX-rect.left)/rect.width; this.audio.currentTime = x * this.audio.duration; },
        close() { this.audio.pause(); this.playing=false; this.paused=false; document.getElementById('global-audio-player').classList.add('hidden'); },
    }
}

function playVoice(url, title, artist, cover) {
    const player = document.getElementById('global-audio-player');
    const audio = document.getElementById('global-audio');
    document.getElementById('player-title').textContent = title;
    document.getElementById('player-artist').textContent = artist;
    const img = document.getElementById('player-cover');
    if (cover) { img.src = cover; img.classList.remove('hidden'); } else { img.classList.add('hidden'); }
    audio.src = url;
    audio.play();
    player.classList.remove('hidden');
}
</script>

@yield('scripts')
</body>
</html>
