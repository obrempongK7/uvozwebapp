<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>UVOZ – Speak. Connect. Earn.</title>
<meta name="description" content="Voice-first social network where you share content, grow your audience, and earn real money from genuine engagement.">
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
*{font-family:'Inter',sans-serif;}h1,h2,h3,h4,h5,h6,.gradient-text{font-family:'Montserrat',sans-serif !important;}
body{background:#0b1326;color:#fff;}
.gradient-text{background:linear-gradient(135deg,#8B5CF6,#06B6D4,#10B981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.gradient-bg{background:linear-gradient(135deg,#8B5CF6 0%,#06B6D4 50%,#10B981 100%);}
.glass{background:rgba(18,26,46,.8);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.05);}
.card{background:#131b2e;border:1px solid #222a3d;border-radius:24px;}
.btn-primary{background:linear-gradient(135deg,#8B5CF6,#06B6D4);color:white;border-radius:16px;padding:14px 28px;font-weight:700;transition:all .3s;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;text-decoration:none;font-size:1rem;}
.btn-primary:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 12px 30px rgba(139,92,246,.4);}
.btn-secondary{background:transparent;border:1px solid #222a3d;color:#CBD5E1;border-radius:16px;padding:14px 28px;font-weight:600;transition:all .3s;cursor:pointer;display:inline-flex;align-items:center;gap:8px;text-decoration:none;}
.btn-secondary:hover{border-color:#8B5CF6;color:white;background:rgba(139,92,246,.1);}
.feature-card{background:#131b2e;border:1px solid #222a3d;border-radius:24px;padding:28px;transition:all .3s;}
.feature-card:hover{border-color:#8B5CF6;transform:translateY(-4px);box-shadow:0 16px 48px rgba(139,92,246,.15);}
.pricing-card{background:#131b2e;border:1px solid #222a3d;border-radius:28px;padding:32px;transition:all .3s;}
.pricing-card.featured{border:2px solid #8B5CF6;background:linear-gradient(145deg,#171f33,#1a1040);}
@keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-12px);}}
.float{animation:float 4s ease-in-out infinite;}
@keyframes pulse-glow{0%,100%{box-shadow:0 0 20px rgba(139,92,246,.3);}50%{box-shadow:0 0 40px rgba(139,92,246,.6);}}
.pulse-glow{animation:pulse-glow 2s ease-in-out infinite;}
::-webkit-scrollbar{width:4px;}
::-webkit-scrollbar-track{background:#0b1326;}
::-webkit-scrollbar-thumb{background:#222a3d;border-radius:4px;}
</style>
</head>
<body>
<!-- Navbar -->
<nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-dark-border" x-data="{ mobileMenu: false }">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<a href="{{ route('home') }}" class="flex items-center gap-2">
<div class="w-9 h-9 gradient-bg rounded-xl flex items-center justify-center font-black text-white">U</div>
<span class="text-xl font-black gradient-text">UVOZ</span>
</a>
<div class="hidden md:flex items-center gap-8">
<a href="{{ route('how-it-works') }}" class="text-sm text-slate-300 hover:text-white transition-colors">How it Works</a>
<a href="{{ route('pricing') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Pricing</a>
<a href="{{ route('features') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Features</a>
<a href="{{ route('blog.index') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Blog</a>
</div>
<div class="flex items-center gap-3">
@auth
<a href="{{ route('dashboard') }}" class="btn-primary py-2.5 px-5 text-sm">Dashboard</a>
@else
<a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Sign In</a>
<a href="{{ route('register') }}" class="btn-primary py-2.5 px-5 text-sm">Get Started Free</a>
@endauth
</div>
</div>
</div>
</nav>

<!-- Hero Section -->
<section class="min-h-screen flex items-center pt-16 relative overflow-hidden">
<div class="absolute inset-0" style="background:radial-gradient(ellipse 80% 60% at 50% -10%,rgba(139,92,246,.25) 0%,transparent 60%),radial-gradient(ellipse 60% 50% at 90% 50%,rgba(6,182,212,.15) 0%,transparent 50%)"></div>
<!-- Floating orbs -->
<div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full blur-3xl opacity-20" style="background:#8B5CF6;animation:float 6s ease-in-out infinite;"></div>
<div class="absolute bottom-1/4 right-1/4 w-48 h-48 rounded-full blur-3xl opacity-15" style="background:#06B6D4;animation:float 8s ease-in-out infinite 2s;"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
<div class="grid lg:grid-cols-2 gap-16 items-center">
<div>
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-8 text-sm font-medium" style="background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.3);">
<div class="w-2 h-2 gradient-bg rounded-full pulse-glow"></div>
<span class="gradient-text">🎙️ Voice-First Social Platform</span>
</div>
<h1 class="text-5xl lg:text-7xl font-black leading-none mb-6">
<span class="text-white">Speak.</span><br>
<span class="gradient-text">Connect.</span><br>
<span class="text-white">Earn.</span>
</h1>
<p class="text-lg text-slate-300 mb-10 leading-relaxed max-w-xl">
Share your voice, grow your audience, connect with businesses, and earn <strong class="text-white">real money</strong> from genuine engagement on UVOZ.
</p>
<div class="flex flex-wrap gap-4">
<a href="{{ route('register') }}" class="btn-primary text-lg">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
Start Earning Free
</a>
<a href="{{ route('how-it-works') }}" class="btn-secondary text-lg">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
How It Works
</a>
</div>
<div class="flex items-center gap-8 mt-10">
<div class="text-center">
<p class="text-2xl font-black gradient-text">1,000</p>
<p class="text-xs text-slate-500 mt-1">Points per day free</p>
</div>
<div class="w-px h-10 bg-dark-border"></div>
<div class="text-center">
<p class="text-2xl font-black gradient-text">$5</p>
<p class="text-xs text-slate-500 mt-1">Min withdrawal</p>
</div>
<div class="w-px h-10 bg-dark-border"></div>
<div class="text-center">
<p class="text-2xl font-black gradient-text">10%</p>
<p class="text-xs text-slate-500 mt-1">Referral commission</p>
</div>
</div>
</div>
<!-- Hero Visual -->
<div class="relative hidden lg:block">
<div class="card p-6 float" style="max-width:360px;margin:auto;">
<div class="flex items-center gap-3 mb-4">
<div class="w-10 h-10 gradient-bg rounded-full"></div>
<div><p class="font-semibold text-white">Sarah K.</p><p class="text-xs text-slate-400">Voice Creator</p></div>
<div class="ml-auto"><span style="background:rgba(16,185,129,.2);color:#4edea3;border:1px solid rgba(16,185,129,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;">+250 pts</span></div>
</div>
<div class="rounded-2xl p-4 mb-4" style="background:#0b1326;">
<div class="flex items-center gap-3">
<button class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></button>
<div class="flex-1">
<p class="text-sm text-white font-medium">My Morning Routine 🌅</p>
<div class="flex items-center gap-1 mt-2">
@for($i=0;$i<20;$i++)<div class="flex-1 rounded-full" style="height:{{ rand(4,20) }}px;background:linear-gradient(180deg,#8B5CF6,#06B6D4);opacity:{{ rand(5,10)/10 }};"></div>@endfor
</div>
</div>
</div>
</div>
<div class="flex items-center justify-between text-sm">
<span class="text-slate-400">🎧 2.4k plays</span><span class="text-slate-400">❤️ 384 likes</span><span class="text-slate-400">💬 67 comments</span>
</div>
</div>
<!-- Floating earnings badge -->
<div class="absolute -top-6 -right-6 card px-4 py-3 float" style="animation-delay:1s;">
<p class="text-xs text-slate-400">Today's earnings</p>
<p class="text-xl font-black gradient-text">+$4.20</p>
<p class="text-xs text-slate-500">4,200 points</p>
</div>
<!-- Live indicator -->
<div class="absolute -bottom-4 -left-4 card px-3 py-2 flex items-center gap-2 float" style="animation-delay:2s;">
<div class="w-2 h-2 rounded-full bg-red-500 pulse-glow"></div>
<p class="text-xs text-white font-medium">3 live rooms now</p>
</div>
</div>
</div>
</div>
</section>

<!-- How It Works -->
<section class="py-24 relative">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-16">
<h2 class="text-4xl font-black text-white mb-4">How <span class="gradient-text">UVOZ</span> Works</h2>
<p class="text-slate-400 text-lg max-w-2xl mx-auto">Three simple steps to start earning from your content</p>
</div>
<div class="grid md:grid-cols-3 gap-8">
@foreach([['1','Create Content','Record voice posts, share statuses, host podcasts or live sessions.','🎙️','purple'],['2','Get Engagement','Followers listen, like, comment and share your authentic content.','❤️','pink'],['3','Earn & Withdraw','Accumulate points and convert them to cash, airtime, or prizes.','💰','amber']] as $step)
<div class="feature-card text-center">
<div class="w-14 h-14 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">{{ $step[4] === 'purple' ? '🎙️' : ($step[4] === 'pink' ? '❤️' : '💰') }}</div>
<div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto -mt-2 mb-4 text-sm font-black text-white" style="background:rgba(139,92,246,.3);border:2px solid #8B5CF6;">{{ $step[0] }}</div>
<h3 class="text-xl font-bold text-white mb-3">{{ $step[1] }}</h3>
<p class="text-slate-400 text-sm leading-relaxed">{{ $step[2] }}</p>
</div>
@endforeach
</div>
</div>
</section>

<!-- Features Grid -->
<section class="py-24 relative">
<div class="absolute inset-0" style="background:radial-gradient(ellipse 60% 40% at 50% 50%,rgba(139,92,246,.08) 0%,transparent 70%)"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
<div class="text-center mb-16">
<h2 class="text-4xl font-black text-white mb-4">Everything You <span class="gradient-text">Need to Earn</span></h2>
<p class="text-slate-400 text-lg">A complete platform built for creators, listeners and businesses</p>
</div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach([
['🎙️','Voice Posts','Record and share your thoughts, stories, and expertise in rich audio format.'],
['📻','Podcast Studio','Create professional podcast series with episodes, show notes and RSS feeds.'],
['🔴','Live Audio Rooms','Host real-time discussions, interviews and events with your audience.'],
['📊','Status Hub','Share text, image, video and link statuses with contact-button monetization.'],
['🏆','Rewards Engine','Earn points for every interaction and redeem for cash, airtime or electronics.'],
['📣','Campaign Marketplace','Complete advertiser tasks and earn extra points on top of your daily income.'],
['🔗','Referral Program','Earn 10% commission on all points earned by users you refer forever.'],
['💳','Wallet & Withdrawals','Withdraw via Mobile Money, Bank Transfer, PayPal or USDT with no hassle.'],
['🎯','Smart Feed','AI-powered personalized feed showing the content most relevant to you.'],
] as $feat)
<div class="feature-card">
<div class="text-3xl mb-4">{{ $feat[0] }}</div>
<h3 class="text-lg font-bold text-white mb-2">{{ $feat[1] }}</h3>
<p class="text-slate-400 text-sm leading-relaxed">{{ $feat[2] }}</p>
</div>
@endforeach
</div>
</div>
</section>

<!-- Pricing -->
<section class="py-24">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-16">
<h2 class="text-4xl font-black text-white mb-4">Choose Your <span class="gradient-text">Earning Power</span></h2>
<p class="text-slate-400 text-lg">Upgrade to multiply your earnings potential</p>
</div>
<div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
@foreach($plans as $plan)
<div class="pricing-card {{ $plan->slug === 'pro' ? 'featured' : '' }}">
@if($plan->slug === 'pro')
<div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold mb-4 gradient-bg text-white">⭐ Most Popular</div>
@endif
<h3 class="text-xl font-bold text-white mb-2">{{ $plan->name }}</h3>
<div class="mb-6">
@if($plan->monthly_price == 0)
<span class="text-4xl font-black text-white">Free</span>
@else
<span class="text-4xl font-black text-white">${{ number_format($plan->monthly_price, 0) }}</span>
<span class="text-slate-400">/month</span>
@endif
</div>
<ul class="space-y-3 mb-8">
@foreach($plan->features ?? [] as $feature)
<li class="flex items-center gap-2 text-sm text-slate-300">
<svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
{{ $feature }}
</li>
@endforeach
</ul>
<a href="{{ route('register') }}" class="{{ $plan->slug === 'pro' ? 'btn-primary' : 'btn-secondary' }} w-full justify-center">
{{ $plan->monthly_price == 0 ? 'Get Started Free' : 'Upgrade to ' . $plan->name }}
</a>
</div>
@endforeach
</div>
</div>
</section>

<!-- Recent Payouts -->
@if($recentPayouts->count() > 0)
<section class="py-20">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-12">
<h2 class="text-3xl font-black text-white mb-4">💸 Real Payouts, Real People</h2>
<p class="text-slate-400">Recent verified withdrawals from UVOZ users</p>
</div>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
@foreach($recentPayouts as $payout)
<div class="card p-4 text-center">
<img src="{{ $payout->user->avatar_url }}" class="w-12 h-12 rounded-full mx-auto mb-3 object-cover">
<p class="text-sm font-semibold text-white">{{ $payout->user->display_name }}</p>
<p class="text-xs text-slate-400 mb-2">{{ $payout->user->country }}</p>
<p class="text-xl font-black text-green-400">${{ number_format($payout->amount, 2) }}</p>
<p class="text-xs text-slate-500">{{ ucfirst(str_replace('_',' ',$payout->method)) }}</p>
</div>
@endforeach
</div>
</div>
</section>
@endif

<!-- CTA Section -->
<section class="py-24 relative overflow-hidden">
<div class="absolute inset-0 gradient-bg opacity-10"></div>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
<h2 class="text-5xl font-black text-white mb-6">Ready to <span class="gradient-text">Start Earning?</span></h2>
<p class="text-xl text-slate-300 mb-10">Join thousands of creators making money from their voice. It's completely free to get started.</p>
<a href="{{ route('register') }}" class="btn-primary text-xl">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
Create Free Account
</a>
<p class="text-slate-500 mt-4 text-sm">No credit card required • Start earning in minutes</p>
</div>
</section>

<!-- Footer -->
<footer class="border-t border-dark-border py-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid md:grid-cols-4 gap-8 mb-10">
<div>
<div class="flex items-center gap-2 mb-4">
<div class="w-8 h-8 gradient-bg rounded-xl flex items-center justify-center font-black text-white text-sm">U</div>
<span class="font-black gradient-text text-lg">UVOZ</span>
</div>
<p class="text-slate-400 text-sm leading-relaxed">Voice-first social network where you earn real money from authentic engagement.</p>
</div>
<div>
<h4 class="font-semibold text-white mb-4">Platform</h4>
<ul class="space-y-2">
@foreach([['how-it-works','How It Works'],['features','Features'],['pricing','Pricing'],['blog.index','Blog']] as $l)
<li><a href="{{ route($l[0]) }}" class="text-slate-400 hover:text-white text-sm transition-colors">{{ $l[1] }}</a></li>
@endforeach
</ul>
</div>
<div>
<h4 class="font-semibold text-white mb-4">Company</h4>
<ul class="space-y-2">
@foreach([['about','About'],['contact','Contact'],['faq','FAQ']] as $l)
<li><a href="{{ route($l[0]) }}" class="text-slate-400 hover:text-white text-sm transition-colors">{{ $l[1] }}</a></li>
@endforeach
</ul>
</div>
<div>
<h4 class="font-semibold text-white mb-4">Legal</h4>
<ul class="space-y-2">
@foreach([['privacy','Privacy Policy'],['terms','Terms of Service']] as $l)
<li><a href="{{ route($l[0]) }}" class="text-slate-400 hover:text-white text-sm transition-colors">{{ $l[1] }}</a></li>
@endforeach
</ul>
</div>
</div>
<div class="border-t border-dark-border pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
<p class="text-slate-500 text-sm">© {{ date('Y') }} UVOZ. All rights reserved.</p>
<p class="text-slate-500 text-sm">Made with ❤️ for creators worldwide</p>
</div>
</div>
</footer>
</body>
</html>
