@extends('layouts.baseLayout')

@section('title', 'Kérelmek')

@section('page')
<main class="p-10 space-y-8">

    <h1 class="text-3xl font-bold tracking-wide">Felhasználói <span class="text-accent">kérelmek</span></h1>

    @if (session('success'))
        <div class="bg-green-500/10 border border-green-500/40 text-green-400 rounded-lg p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-panel border border-border rounded-xl p-6 shadow-card">
        @if ($requests->count() > 0)
            <table class="w-full text-left text-gray-300 text-sm">
                <thead class="text-gray-500 border-b border-border">
                    <tr>
                        <th class="py-2">#</th>
                        <th>Felhasználó</th>
                        <th>Típus</th>
                        <th>Payload</th>
                        <th>Státusz</th>
                        <th>Beküldve</th>
                        <th class="text-right">Műveletek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($requests as $r)
                        <tr>
                            <td class="py-3">{{ $r->id }}</td>
                            <td>{{ optional($r->user)->full_name ?? '—' }} ({{ optional($r->user)->username ?? '—' }})</td>
                            <td>
                                @php($t = strtolower($r->type))
                                @if ($t === 'delete_account')
                                    Fióktörlés
                                @elseif ($t === 'missing_brand')
                                    Hiányzó márka
                                @else
                                    {{ $r->type }}
                                @endif
                            </td>
                            <td class="max-w-[260px]">
                                @if (is_array($r->payload))
                                    <code class="text-xs text-gray-400">{{ json_encode($r->payload, JSON_UNESCAPED_UNICODE) }}</code>
                                @elseif (!empty($r->payload))
                                    <code class="text-xs text-gray-400">{{ $r->payload }}</code>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                            <td>{{ ucfirst(strtolower($r->status)) }}</td>
                            <td>{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-right space-x-2">
                                @if (strtolower($r->status) === 'open')
                                    <form method="POST" action="{{ route('admin.requests.approve', $r->id) }}" class="inline">
                                        @csrf
                                        <button class="px-3 py-1 rounded bg-emerald-600 text-white hover:bg-emerald-500 transition">Jóváhagyás</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.requests.reject', $r->id) }}" class="inline">
                                        @csrf
                                        <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-500 transition">Elutasítás</button>
                                    </form>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        @else
            <p class="text-gray-500">Nincsenek kérelmek.</p>
        @endif
    </div>

</main>
@endsection
