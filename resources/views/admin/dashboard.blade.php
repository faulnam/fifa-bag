@extends('layouts.admin')

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('dashboardSalesChart').getContext('2d');
        const labels = @json($chartLabels);
        const revenues = @json($chartRevenue);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: revenues,
                    backgroundColor: '#212121',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value >= 1000000 ? (value / 1000000) + 'jt' : value.toLocaleString('id-ID'));
                            }
                        },
                        grid: {
                            color: '#ece9e2'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-sand pb-6">
        <div>
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Ringkasan Toko</h1>
            <p class="text-body-sm text-iron mt-1">Status operasional, performa penjualan, logistik, dan ulasan produk fifa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-pill text-caption font-medium bg-sand text-charcoal">
                Gateway: {{ strtoupper(\App\Models\SiteSetting::get('payment_gateway_active', 'Midtrans')) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-pill text-caption font-medium bg-sand text-charcoal">
                Logistik: BITESHIP API
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Total Penjualan</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-2 block">
                Rp {{ number_format($stats['total_sales'] ?? 0, 0, ',', '.') }}
            </span>
            <span class="text-caption text-stone mt-1 block">Pesanan berstatus terbayar</span>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Perlu Diproses / Dikirim</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-2 block">
                {{ $stats['pending_shipments'] ?? 0 }}
            </span>
            <a href="{{ route('admin.orders.index') }}" class="text-caption text-charcoal font-bold underline mt-1 block">
                Lihat Antrean Pengiriman →
            </a>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Moderasi Ulasan</span>
            <span class="font-sans font-bold text-2xl {{ ($stats['pending_reviews'] ?? 0) > 0 ? 'text-yellow-700' : 'text-charcoal' }} mt-2 block">
                {{ $stats['pending_reviews'] ?? 0 }} Pending
            </span>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="text-caption text-charcoal font-bold underline mt-1 block">
                Moderasi Ulasan Masuk →
            </a>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Stok Menipis (&le; 5)</span>
            <span class="font-sans font-bold text-2xl {{ ($stats['low_stock_count'] ?? 0) > 0 ? 'text-red-700' : 'text-charcoal' }} mt-2 block">
                {{ $stats['low_stock_count'] ?? 0 }} Varian
            </span>
            <a href="{{ route('admin.reports.index') }}" class="text-caption text-charcoal font-bold underline mt-1 block">
                Lihat Detail Laporan →
            </a>
        </div>
    </div>

    <!-- Sales 7-day Bar Chart -->
    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between border-b border-sand pb-3 mb-6">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                Tren Penjualan 7 Hari Terakhir
            </h2>
            <a href="{{ route('admin.reports.index') }}" class="text-caption font-bold uppercase tracking-wide10 text-stone hover:text-charcoal">
                Buka Laporan Lengkap →
            </a>
        </div>
        <div class="h-64 w-full">
            <canvas id="dashboardSalesChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders & Top Products Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders (2 cols) -->
        <div class="lg:col-span-2 bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-sand pb-3 mb-4">
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal">
                    Pesanan Terbaru
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-caption font-bold text-stone hover:text-charcoal uppercase tracking-wide10">
                    Semua Pesanan →
                </a>
            </div>

            <!-- Desktop Table (Hidden on Mobile) -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-sand text-left text-body-sm">
                    <thead class="text-caption font-bold uppercase text-stone">
                        <tr>
                            <th class="py-2">No. Pesanan</th>
                            <th class="py-2">Customer</th>
                            <th class="py-2">Total</th>
                            <th class="py-2">Status</th>
                            <th class="py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sand">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-3 font-mono font-bold text-charcoal">{{ $order->order_number }}</td>
                                <td class="py-3 text-caption text-stone">{{ $order->user->name ?? $order->guest_name ?? 'Guest' }}</td>
                                <td class="py-3 font-mono">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-pill text-[11px] font-medium {{ in_array($order->status, ['paid', 'processing', 'ready_to_ship', 'shipped', 'delivered', 'completed']) ? 'bg-green-100 text-green-800' : 'bg-sand text-charcoal' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-caption font-bold text-charcoal underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-stone text-caption">Belum ada pesanan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Stacked Cards (Visible on < sm) -->
            <div class="sm:hidden space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="p-3 border border-sand rounded-input space-y-1 text-body-sm">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold">{{ $order->order_number }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-pill bg-sand">{{ $order->status }}</span>
                        </div>
                        <div class="text-caption text-stone flex justify-between">
                            <span>{{ $order->user->name ?? $order->guest_name }}</span>
                            <span class="font-bold text-charcoal">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-right pt-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-caption font-bold text-charcoal underline">Detail Pesanan →</a>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-stone text-caption">Belum ada pesanan.</div>
                @endforelse
            </div>
        </div>

        <!-- Top Selling Products (1 col) -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3 mb-4">
                    Top Produk Terlaris
                </h2>
                <div class="divide-y divide-sand">
                    @forelse ($topProducts as $top)
                        <div class="py-3 flex items-center justify-between text-body-sm">
                            <span class="font-bold text-charcoal text-caption line-clamp-1 max-w-[180px]">{{ $top->product_name_snapshot }}</span>
                            <span class="text-caption text-stone font-bold">{{ $top->total_qty }} pcs</span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-stone text-caption">Belum ada data penjualan.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-sand">
                <a href="{{ route('admin.reports.index') }}" class="btn-pill-light text-caption w-full py-2 block text-center">
                    Lihat Laporan Penjualan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
