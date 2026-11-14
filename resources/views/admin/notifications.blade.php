
@php
    $notifications = collect($perluTindakan ?? []);
    $notificationCount = $notifications->count();
@endphp

<details class="notification-menu">
    <summary class="icon-button"
             aria-label="Lihat notifikasi"
             title="Notifikasi">

        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M10 21h4"/>
        </svg>

        <span class="notif-count"
              data-notification-count
              @if($notificationCount === 0) hidden @endif>
            {{ $notificationCount }}
        </span>
    </summary>

    <div class="notification-dropdown">

        <div class="notification-heading">
            <strong>Notifikasi</strong>
            <span>
                <span data-notification-label>{{ $notificationCount }}</span>
                perlu tindakan
            </span>
        </div>

        @forelse ($notifications as $p)
            @php
                $status = $statusMap[$p['status']] ?? null;
            @endphp

            @if ($status && $status['aksi'])
                <a class="notification-item"
                   href="{{ url('/admin/pesanan') }}">

                    <span class="notification-dot"></span>

                    <span>
                        <strong>
                            {{ $p['id'] }} · {{ $p['pelanggan'] }}
                        </strong>

                        <small>
                            {{ $status['label'] }} — {{ $status['aksi'] }}
                        </small>
                    </span>
                </a>
            @endif

        @empty
            <p class="notification-empty">
                Tidak ada pesanan yang perlu ditindaklanjuti.
            </p>
        @endforelse

        <a class="notification-footer"
           href="{{ url('/admin/pesanan') }}">
            Lihat semua pesanan
        </a>

    </div>
</details>
