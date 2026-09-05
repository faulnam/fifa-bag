@extends('layouts.admin')

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesReportChart').getContext('2d');
        const labels = @json($chartLabels);
        const revenues = @json($chartRevenue);
        const orders = @json($chartOrders);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: revenues,
                        borderColor: '#212121',
                        backgroundColor: 'rgba(33, 33, 33, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Pesanan',
                        data: orders,
                        borderColor: '#737373',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: '#ece9e2'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            stepSize: 1
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
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
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
            <h1 class="font-sans font-bold text-2xl uppercase tracking-wide10 text-charcoal">Laporan Penjualan & Analitik</h1>
            <p class="text-body-sm text-iron mt-1">Grafik performa transaksi, produk terlaris, dan pemantauan stok menipis.</p>
        </div>
        
        <!-- Period Filter -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.index', ['days' => 7]) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ $days === 7 ? 'bg-charcoal text-canvas' : 'bg-canvas border border-sand text-charcoal hover:bg-sand/30' }}">
                7 Hari Terakhir
            </a>
            <a href="{{ route('admin.reports.index', ['days' => 30]) }}" class="px-4 py-1.5 rounded-pill text-caption font-medium {{ $days === 30 ? 'bg-charcoal text-canvas' : 'bg-canvas border border-sand text-charcoal hover:bg-sand/30' }}">
                30 Hari Terakhir
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Total Omset Penjualan</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-2 block">
                Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
            </span>
            <span class="text-caption text-stone mt-1 block">Pesanan terbayar & terkirim</span>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Total Transaksi Selesai</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-2 block">
                {{ $summary['paid_orders'] ?? 0 }}
            </span>
            <span class="text-caption text-stone mt-1 block">Dari total {{ $summary['total_orders'] ?? 0 }} pesanan dibuat</span>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Rata-Rata Nilai Pesanan (AOV)</span>
            <span class="font-sans font-bold text-2xl text-charcoal mt-2 block">
                Rp {{ number_format($summary['avg_order_value'] ?? 0, 0, ',', '.') }}
            </span>
            <span class="text-caption text-stone mt-1 block">Average Order Value</span>
        </div>

        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <span class="text-caption font-bold uppercase tracking-wide10 text-stone block">Peringatan Stok Rendah</span>
            <span class="font-sans font-bold text-2xl {{ $lowStockVariants->isNotEmpty() ? 'text-red-700' : 'text-charcoal' }} mt-2 block">
                {{ $lowStockVariants->count() }} Varian
            </span>
            <span class="text-caption text-stone mt-1 block">Stok tersisa $\le 5$ pasang</span>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-canvas border border-sand rounded-card p-6 sm:p-8 shadow-sm">
        <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3 mb-6">
            Tren Penjualan Harian ({{ $days }} Hari Terakhir)
        </h2>
        <div class="h-80 w-full">
            <canvas id="salesReportChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Selling Products -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3 mb-4">
                5 Produk Terlaris (Best Sellers)
            </h2>
            <div class="divide-y divide-sand">
                @forelse ($topProducts as $top)
                    <div class="py-3 flex items-center justify-between text-body-sm">
                        <div>
                            <span class="font-bold text-charcoal block">{{ $top->product_name_snapshot }}</span>
                            <span class="text-caption text-stone">Terjual {{ $top->total_qty }} pcs</span>
                        </div>
                        <span class="font-mono font-bold text-charcoal">
                            Rp {{ number_format($top->total_revenue, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-stone text-caption">
                        Belum ada data penjualan tercatat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Inventory Alerts -->
        <div class="bg-canvas border border-sand rounded-card p-6 shadow-sm">
            <h2 class="text-caption font-bold uppercase tracking-wide10 text-charcoal border-b border-sand pb-3 mb-4 flex items-center justify-between">
                <span>Peringatan Stok Menipis</span>
                <span class="text-caption text-red-600 font-bold">Stok &le; 5</span>
            </h2>
            <div class="divide-y divide-sand">
                @forelse ($lowStockVariants as $variant)
                    <div class="py-3 flex items-center justify-between text-body-sm">
                        <div>
                            <span class="font-bold text-charcoal block">{{ $variant->product->name ?? 'Produk' }}</span>
                            <span class="text-caption text-stone">
                                Warna: {{ $variant->color_name }} | Ukuran: {{ $variant->size }} | SKU: {{ $variant->sku }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-pill text-caption font-bold {{ $variant->stock === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                Sisa {{ $variant->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-stone text-caption">
                        Semua stok varian dalam kondisi aman (> 5).
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
