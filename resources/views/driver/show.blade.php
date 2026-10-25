@extends('layouts.driver')
@section('title', $task->code)

@section('content')
    <a href="{{ route('driver.dashboard') }}" class="back-link"><i class="bi bi-arrow-left"></i> Semua tugas</a>

    <article class="task-card" style="margin-top:12px;">
        <div class="task-top">
            <span class="time-pill">{{ $task->time }}</span>
            <span class="chip chip-{{ $task->type }}">{{ $task->type === 'jemput' ? 'Jemput' : 'Antar' }}</span>
        </div>
        <h1 class="task-name">{{ $task->customer }}</h1>
        <div class="task-meta">{{ $task->code }} · {{ $task->service }}</div>
        <div class="task-meta"><i class="bi bi-geo-alt"></i>{{ $task->address }}</div>

        <div class="action-grid">
            <a href="tel:{{ $task->phone }}" class="btn btn-ghost"><i class="bi bi-telephone"></i>Telepon</a>
            <a href="https://wa.me/62{{ ltrim($task->phone, '0') }}" target="_blank" rel="noopener" class="btn btn-wa"><i class="bi bi-whatsapp"></i>Chat</a>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($task->address) }}" target="_blank" rel="noopener" class="btn btn-primary full"><i class="bi bi-signpost-2"></i>Buka rute di Maps</a>
        </div>
    </article>

    @if ($task->type === 'jemput')
        <article class="task-card">
            <h2 class="card-title">Timbang cucian</h2>
            <form method="POST" action="{{ route('driver.tasks.weight', $task->id) }}">
                @csrf
                <label for="weight" class="field-label">Berat (kg)</label>
                <input type="number" step="0.1" min="0.1" inputmode="decimal" name="weight" id="weight"
                       class="input" placeholder="0.0" value="{{ $task->weight }}" required>

                <div class="total-box">
                    <div>
                        <small>Tarif {{ $task->service }}</small>
                        <span>Rp{{ number_format($task->price_per_kg, 0, ',', '.') }} / kg</span>
                    </div>
                    <div>
                        <small style="text-align:right">Total</small>
                        <div class="total-value" id="total">Rp0</div>
                    </div>
                </div>

                <button class="btn btn-primary">Simpan berat</button>
            </form>
        </article>
    @endif

    <article class="task-card">
        <h2 class="card-title">Update status</h2>
        <form method="POST" action="{{ route('driver.tasks.status', $task->id) }}" class="stack">
            @csrf
            @if ($task->type === 'jemput')
                <button name="status" value="dalam_perjalanan" class="btn btn-ghost">Berangkat ke lokasi</button>
                <button name="status" value="dijemput" class="btn btn-primary">Cucian sudah dijemput</button>
            @else
                <button name="status" value="sedang_diantar" class="btn btn-ghost">Berangkat mengantar</button>
                <button name="status" value="selesai" class="btn btn-primary">Cucian sudah diterima customer</button>
            @endif
        </form>
    </article>
@endsection

@push('scripts')
<script>
    (function () {
        var pricePerKg = {{ (int) $task->price_per_kg }};
        var input = document.getElementById('weight');
        var total = document.getElementById('total');
        if (!input) return;
        function render() {
            var kg = parseFloat(input.value) || 0;
            total.textContent = 'Rp' + Math.round(kg * pricePerKg).toLocaleString('id-ID');
        }
        input.addEventListener('input', render);
        render();
    })();
</script>
@endpush