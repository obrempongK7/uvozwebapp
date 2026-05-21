<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'UVOZ') – Speak. Connect. Earn.</title>
<link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|montserrat:500,600,700,800,900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>tailwind.config={darkMode:'class',theme:{extend:{fontFamily:{sans:['Inter','sans-serif']}}}}</script>
<style>*{font-family:'Inter',sans-serif;}h1,h2,h3,h4,h5,h6,.gradient-text{font-family:'Montserrat',sans-serif !important;}body{background:#0b1326;color:#fff;}.input-field{background:#222a3d;border:1px solid #2d3449;border-radius:14px;padding:12px 16px;color:white;width:100%;transition:all .3s;outline:none;}.input-field:focus{border-color:#8B5CF6;box-shadow:0 0 0 3px rgba(139,92,246,.15);}.input-field::placeholder{color:#64748B;}.btn-primary{background:linear-gradient(135deg,#8B5CF6,#06B6D4);color:white;border-radius:16px;padding:12px 24px;font-weight:600;transition:all .3s;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;}.btn-primary:hover{opacity:.9;transform:translateY(-1px);}.card{background:#131b2e;border:1px solid #222a3d;border-radius:24px;}.gradient-text{background:linear-gradient(135deg,#8B5CF6,#06B6D4,#10B981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}.gradient-bg{background:linear-gradient(135deg,#8B5CF6 0%,#06B6D4 50%,#10B981 100%);}</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background:radial-gradient(ellipse at top left,rgba(139,92,246,.15) 0%,transparent 50%),radial-gradient(ellipse at bottom right,rgba(6,182,212,.1) 0%,transparent 50%),#0b1326;">
<div class="w-full max-w-md">
<div class="text-center mb-8">
<a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
<div class="w-10 h-10 gradient-bg rounded-2xl flex items-center justify-center font-black text-white text-lg">U</div>
<span class="text-2xl font-black gradient-text">UVOZ</span>
</a>
<p class="text-slate-400 text-sm">Speak. Connect. Earn.</p>
</div>
@if($errors->any())
<div class="mb-4 p-4 rounded-2xl bg-red-500/10 border border-red-500/20">
@foreach($errors->all() as $e)<p class="text-red-400 text-sm">{{ $e }}</p>@endforeach
</div>
@endif
@if(session('success'))
<div class="mb-4 p-4 rounded-2xl bg-green-500/10 border border-green-500/20">
<p class="text-green-400 text-sm">{{ session('success') }}</p>
</div>
@endif
<div class="card p-8">@yield('content')</div>
</div>
</body>
</html>
