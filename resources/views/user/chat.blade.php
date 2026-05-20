@extends('layouts.app')
@section('title','Chat')
@section('content')
@php $other = $conversation->participants->where('id','!=',auth()->id())->first(); @endphp
<div class="max-w-2xl mx-auto flex flex-col" style="height:calc(100vh - 140px)">
<!-- Chat Header -->
<div class="card p-4 mb-4 flex items-center gap-3">
<a href="{{ route('message.index') }}" class="text-slate-400 hover:text-white mr-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
<img src="{{ $other?->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
<div><p class="font-semibold text-white">{{ $other?->display_name }}</p><p class="text-xs text-green-400">Online</p></div>
</div>
<!-- Messages -->
<div class="flex-1 overflow-y-auto space-y-3 mb-4" id="chat-messages">
@foreach($messages as $msg)
<div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} items-end gap-2">
@if($msg->sender_id !== auth()->id())<img src="{{ $msg->sender->avatar_url }}" class="w-7 h-7 rounded-full object-cover flex-shrink-0">@endif
<div class="max-w-xs px-4 py-2.5 rounded-2xl text-sm {{ $msg->sender_id === auth()->id() ? 'gradient-bg text-white rounded-br-sm' : 'bg-dark-border text-slate-200 rounded-bl-sm' }}">
{{ $msg->content }}
<p class="text-xs mt-1 opacity-60">{{ $msg->created_at->format('H:i') }}</p>
</div>
</div>
@endforeach
</div>
<!-- Input -->
<div class="card p-3 flex items-center gap-3">
<input type="text" id="msgInput" placeholder="Type a message..." class="input-field flex-1 py-3 text-sm">
<button onclick="sendMessage()" class="w-11 h-11 gradient-bg rounded-xl flex items-center justify-center flex-shrink-0">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
</button>
</div>
</div>
@endsection
@section('scripts')
<script>
document.getElementById('chat-messages').scrollTop = 999999;
document.getElementById('msgInput').addEventListener('keypress', e => { if(e.key==='Enter') sendMessage(); });
function sendMessage() {
    const input = document.getElementById('msgInput');
    const content = input.value.trim();
    if (!content) return;
    input.value = '';
    fetch('{{ route('message.send',$conversation) }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
        body: JSON.stringify({content})
    }).then(r=>r.json()).then(d=>{
        const div = document.createElement('div');
        div.className = 'flex justify-end items-end gap-2';
        div.innerHTML = `<div class="max-w-xs px-4 py-2.5 rounded-2xl text-sm gradient-bg text-white rounded-br-sm">${content}<p class="text-xs mt-1 opacity-60">Just now</p></div>`;
        document.getElementById('chat-messages').appendChild(div);
        document.getElementById('chat-messages').scrollTop = 999999;
    });
}
</script>
@endsection
