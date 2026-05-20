<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') – UVOZ Admin</title>
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','sans-serif']}}}}</script>
<style>*{font-family:'Inter',sans-serif;}body{background:#0B1020;color:#fff;}::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-track{background:#0B1020;}::-webkit-scrollbar-thumb{background:#1E293B;border-radius:4px;}.admin-link{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;color:#CBD5E1;transition:all .2s;font-size:.875rem;}.admin-link:hover,.admin-link.active{background:rgba(124,58,237,.15);color:white;}.card{background:#121A2E;border:1px solid #1E293B;border-radius:20px;}.gradient-text{background:linear-gradient(135deg,#7C3AED,#EC4899,#F59E0B);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}.gradient-bg{background:linear-gradient(135deg,#7C3AED 0%,#EC4899 50%,#F59E0B 100%);}.btn-primary{background:linear-gradient(135deg,#7C3AED,#EC4899);color:white;border-radius:12px;padding:8px 18px;font-weight:600;transition:all .3s;border:none;cursor:pointer;font-size:.875rem;display:inline-flex;align-items:center;gap:6px;}.input-field{background:#1E293B;border:1px solid #253045;border-radius:10px;padding:8px 12px;color:white;outline:none;transition:all .3s;font-size:.875rem;}.input-field:focus{border-color:#7C3AED;}.stat-card{background:#121A2E;border:1px solid #1E293B;border-radius:20px;padding:20px;}.badge-pending{background:rgba(245,158,11,.2);color:#FCD34D;border:1px solid rgba(245,158,11,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;}.badge-active{background:rgba(16,185,129,.2);color:#34D399;border:1px solid rgba(16,185,129,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;}.badge-rejected{background:rgba(239,68,68,.2);color:#FCA5A5;border:1px solid rgba(239,68,68,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;}.table-row:hover{background:#15203A;}</style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
<div class="flex min-h-screen">
<!-- Admin Sidebar -->
<aside class="fixed left-0 top-0 bottom-0 w-60 z-40 overflow-y-auto py-4 px-3 flex flex-col"
  style="background:#0D1525;border-right:1px solid #1E293B;"
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  style="transition:transform .3s;">
<div class="flex items-center gap-2 px-2 mb-6">
<div class="w-8 h-8 gradient-bg rounded-xl flex items-center justify-center font-black text-white text-sm">U</div>
<div><p class="font-bold text-white text-sm">UVOZ Admin</p><p class="text-xs text-slate-500">Control Panel</p></div>
</div>
<nav class="flex-1 flex flex-col gap-1">
<a href="{{ route('admin.dashboard') }}" class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/></svg>Dashboard</a>
<a href="{{ route('admin.analytics') }}" class="admin-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>Analytics</a>
<div class="mt-3 mb-1 px-2 text-xs font-semibold text-slate-600 uppercase">Users</div>
<a href="{{ route('admin.users.index') }}" class="admin-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>All Users</a>
<a href="{{ route('admin.kyc.index') }}" class="admin-link {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>KYC Verifications</a>
<div class="mt-3 mb-1 px-2 text-xs font-semibold text-slate-600 uppercase">Content</div>
<a href="{{ route('admin.content.reports') }}" class="admin-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>Reports</a>
<a href="{{ route('admin.campaigns.index') }}" class="admin-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>Campaigns</a>
<div class="mt-3 mb-1 px-2 text-xs font-semibold text-slate-600 uppercase">Finance</div>
<a href="{{ route('admin.wallet.withdrawals') }}" class="admin-link {{ request()->routeIs('admin.wallet.withdrawals*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>Withdrawals</a>
<a href="{{ route('admin.wallet.redemptions') }}" class="admin-link {{ request()->routeIs('admin.wallet.redemptions*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/></svg>Redemptions</a>
<a href="{{ route('admin.wallet.catalog') }}" class="admin-link {{ request()->routeIs('admin.wallet.catalog*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>Reward Catalog</a>
<a href="{{ route('admin.wallet.rates') }}" class="admin-link {{ request()->routeIs('admin.wallet.rates*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>Reward Rates</a>
<a href="{{ route('admin.subscriptions') }}" class="admin-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>Plans</a>
<div class="mt-3 mb-1 px-2 text-xs font-semibold text-slate-600 uppercase">CMS & Settings</div>
<a href="{{ route('admin.blog.index') }}" class="admin-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>Blog</a>
<a href="{{ route('admin.settings.index') }}" class="admin-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Settings</a>
</nav>
<div class="mt-auto pt-4 border-t border-dark-border">
<div class="flex items-center gap-3 px-2">
<img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
<div class="min-w-0"><p class="text-sm font-medium text-white truncate">{{ auth()->user()->display_name }}</p><p class="text-xs text-purple-400">{{ ucfirst(str_replace('_',' ',auth()->user()->role)) }}</p></div>
</div>
<a href="{{ route('dashboard') }}" class="admin-link mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>Back to App</a>
</div>
</aside>
<!-- Main -->
<main class="flex-1 min-h-screen overflow-auto" :class="sidebarOpen ? 'ml-60' : 'ml-0'" style="transition:margin .3s;">
<header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 border-b border-dark-border" style="background:#0D1525;">
<div class="flex items-center gap-3">
<button @click="sidebarOpen=!sidebarOpen" class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-dark-hover"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
<h1 class="text-lg font-bold text-white">@yield('page_title', 'Dashboard')</h1>
</div>
<div class="flex items-center gap-2">
@if(session('success'))<div class="text-sm text-green-400 px-3 py-1 rounded-xl bg-green-500/10 border border-green-500/20">{{ session('success') }}</div>@endif
@if(session('error'))<div class="text-sm text-red-400 px-3 py-1 rounded-xl bg-red-500/10 border border-red-500/20">{{ session('error') }}</div>@endif
</div>
</header>
<div class="p-6">@yield('content')</div>
</main>
</div>
</body>
</html>
