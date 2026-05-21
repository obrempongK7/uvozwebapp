@extends('layouts.app')
@section('title', $liveRoom->title)
@section('content')
<div class="max-w-3xl mx-auto" x-data="liveRoom()">
<div class="card p-5 mb-4">
<div class="flex items-start justify-between mb-4">
<div>
@if($liveRoom->status === 'live')<span class="flex items-center gap-1.5 text-xs text-red-400 font-bold mb-2"><span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>LIVE NOW</span>@endif
<h1 class="text-xl font-bold text-white">{{ $liveRoom->title }}</h1>
<p class="text-sm text-slate-400 mt-1">Hosted by {{ $liveRoom->host->display_name }}</p>
</div>
<div class="text-right"><p class="text-2xl font-black text-white" x-text="listenerCount">{{ $liveRoom->listeners_count }}</p><p class="text-xs text-slate-500">listeners</p></div>
</div>
<!-- Stage / Speakers -->
<div class="rounded-2xl p-4 mb-4" style="background:#0b1326;min-height:120px;">
<p class="text-xs text-slate-500 uppercase tracking-wider mb-3">On Stage</p>
<div class="flex flex-wrap gap-3">
@foreach($liveRoom->participants->whereIn('role',['host','co_host','speaker'])->take(10) as $p)
<div class="flex flex-col items-center gap-1">
<div class="relative"><img src="{{ $p->user->avatar_url }}" class="w-14 h-14 rounded-full object-cover border-2 {{ $p->role==='host'?'border-purple-500':'border-dark-border' }}">@if(!$p->is_muted)<div class="absolute -bottom-1 -right-1 w-5 h-5 gradient-bg rounded-full flex items-center justify-center"><svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></div>@endif</div>
<p class="text-xs text-white">{{ $p->user->display_name }}</p>
<p class="text-xs text-slate-500">{{ $p->role }}</p>
</div>
@endforeach
</div>
</div>
<!-- Live Chat -->
<div class="rounded-2xl p-4 mb-4" style="background:#0b1326;height:200px;overflow-y:auto;" id="live-chat">
<template x-for="msg in messages" :key="msg.id">
<div class="flex items-start gap-2 mb-2">
<span class="text-xs font-semibold text-purple-400" x-text="msg.user?.display_name"></span>
<span class="text-xs text-slate-300" x-text="msg.content"></span>
</div>
</template>
</div>
<!-- Controls -->
<div class="flex items-center gap-3">
<input type="text" x-ref="chatInput" placeholder="Say something..." class="input-field flex-1 py-2.5 text-sm" @keypress.enter="sendMessage()">
<button @click="sendMessage()" class="w-10 h-10 gradient-bg rounded-xl flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></button>
@if(!$myRole)
<button @click="joinRoom()" class="btn-primary py-2.5 px-5 text-sm">Join Room</button>
@elseif($myRole !== 'host')
<button @click="raiseHand()" class="btn-secondary py-2 px-4 text-sm">✋ Raise Hand</button>
<button @click="leaveRoom()" class="text-xs text-red-400 hover:text-red-300">Leave</button>
@else
<button @click="endRoom()" class="text-xs px-4 py-2 rounded-xl bg-red-500/15 text-red-400 hover:bg-red-500/25">End Room</button>
@endif
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
function liveRoom() {
    return {
        messages: [],
        listenerCount: {{ $liveRoom->listeners_count }},
        sendMessage() {
            const content = this.$refs.chatInput.value.trim();
            if (!content) return;
            this.$refs.chatInput.value = '';
            fetch('{{ route('live.message',$liveRoom) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({content})})
            .then(r=>r.json()).then(d=>{ this.messages.push(d.message); document.getElementById('live-chat').scrollTop=99999; });
        },
        joinRoom() { fetch('{{ route('live.join',$liveRoom) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload()); },
        leaveRoom() { fetch('{{ route('live.leave',$liveRoom) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>window.location='{{ route('live.index') }}'); },
        raiseHand() { fetch('{{ route('live.raise-hand',$liveRoom) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}); },
        endRoom() { if(confirm('End this room?')) fetch('{{ route('live.end',$liveRoom) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>window.location='{{ route('live.index') }}'); },
    }
}
</script>
@endsection
