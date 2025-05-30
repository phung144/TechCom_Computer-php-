@extends('admin.layout')
@section('main')
    <div class="content-wrapper">
        <div class="content">
            <!-- Dashboard Header -->
            <div class="page-header">
                <h1 class="page-title">Thống kê</h1>
                <div class="breadcrumb">
                    <span class="me-1">Lần cuối cập nhật: </span>
                    <span class="text-muted">{{ now()->format('F j, Y, g:i a') }}</span>
                </div>
            </div>

            <!-- Top Statistics -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card card-statistic h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">TỔNG ĐƠN HÀNG</h6>
                                    <h2 class="mb-0">{{ number_format($totalOrderValue) }}đ</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">{{ $orderCount }} đơn hàng</span>
                                    </div>
                                </div>
                                <div class="card-icon bg-primary">
                                    <i class="mdi mdi-cart-outline"></i>
                                </div>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card card-statistic h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">ĐƠN HÀNG HOÀN THÀNH </h6>
                                    <h2 class="mb-0">{{ number_format($sumTotal) }}đ</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">{{ $orderCountCompleted }} đơn hàng</span>
                                        <span class="text-success ms-2"><i class="mdi mdi-arrow-up"></i> 100%</span>
                                    </div>
                                </div>
                                <div class="card-icon bg-success">
                                    <i class="mdi mdi-check-circle-outline"></i>
                                </div>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card card-statistic h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1">ĐƠN HÀNG ĐANG CHỜ XỬ LÝ</h6>
                                    <h2 class="mb-0">{{ number_format($totalIncompleteValue) }}đ</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark">{{ $orderCountIncomplete }} đơn hàng</span>
                                        <span class="text-danger ms-2"><i class="mdi mdi-arrow-down"></i> 0%</span>
                                    </div>
                                </div>
                                <div class="card-icon bg-warning">
                                    <i class="mdi mdi-clock-outline"></i>
                                </div>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Phân tích doanh thu</h5>
                            <form method="get" class="d-flex align-items-center">
                                <label class="me-2 mb-0">Theo: </label>
                                <select name="filter" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                    <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Ngày</option>
                                    <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Tuần</option>
                                    <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Tháng</option>
                                </select>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px; position: relative;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <h6 class="mb-0">This {{ $filter }}</h6>
                                    <p class="text-primary fw-bold mb-0">
                                        {{ number_format($revenues->last()->total ?? 0) }}đ
                                    </p>
                                </div>
                                <div class="col-4 border-end">
                                    <h6 class="mb-0">Last {{ $filter }}</h6>
                                    <p class="text-muted fw-bold mb-0">
                                        {{ number_format($revenues->slice(-2, 1)->first()->total ?? 0) }}đ
                                    </p>
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-0">Sự phát triển</h6>
                                    <p class="{{ ($revenues->last()->total ?? 0) > ($revenues->slice(-2, 1)->first()->total ?? 0) ? 'text-success' : 'text-danger' }} fw-bold mb-0">
                                        @php
                                            $current = $revenues->last()->total ?? 0;
                                            $previous = $revenues->slice(-2, 1)->first()->total ?? 1;
                                            $growth = $previous != 0 ? (($current - $previous) / $previous) * 100 : 0;
                                        @endphp
                                        {{ number_format($growth, 1) }}%
                                        <i class="mdi mdi-arrow-{{ $growth >= 0 ? 'up text-success' : 'down text-danger' }}"></i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Sản phẩm bán chạy nhất</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-end">Đã bán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($product->name, 20) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">{{ $product->sold }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả sản phẩm <i class="mdi mdi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Additional Stats Row -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Activity</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-info me-3">
                                            <i class="mdi mdi-cart-plus"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">New order #1234 received</p>
                                            <small class="text-muted">2 minutes ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-success me-3">
                                            <i class="mdi mdi-account-plus"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">New customer registered</p>
                                            <small class="text-muted">15 minutes ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-warning me-3">
                                            <i class="mdi mdi-alert-circle"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Low stock alert for Product X</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Sales Funnel</h5>
                            <span class="badge bg-light text-dark">Last 30 days</span>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="salesFunnelChart"></canvas>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="dot bg-primary"></span>
                                        <span class="ms-2">Visitors: 1,234</span>
                                    </div>
                                    <span class="text-muted">100%</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="dot bg-info"></span>
                                        <span class="ms-2">Added to Cart: 456</span>
                                    </div>
                                    <span class="text-muted">37%</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="dot bg-success"></span>
                                        <span class="ms-2">Completed Orders: 123</span>
                                    </div>
                                    <span class="text-muted">10%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <style>
            .card-statistic {
                border-radius: 10px;
                overflow: hidden;
                transition: all 0.3s ease;
                border: none;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .card-statistic:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .card-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 24px;
            }

            .page-header {
                margin-bottom: 30px;
            }

            .page-header h1 {
                font-weight: 600;
                color: #2c3e50;
            }

            .chart-container {
                position: relative;
            }

            .dot {
                height: 10px;
                width: 10px;
                border-radius: 50%;
                display: inline-block;
            }

            .avatar-title {
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
            }

            .list-group-item {
                border-left: 0;
                border-right: 0;
            }

            .list-group-item:first-child {
                border-top: 0;
            }

            .list-group-item:last-child {
                border-bottom: 0;
            }

            /* Custom style for the filter select */
            .form-select.form-select-sm.w-auto {
                padding: 0.25rem 1.75rem 0.25rem 0.75rem;
                border-radius: 6px;
                border: 1px solid #ced4da;
                background-color: #f8f9fa;
                color: #2c3e50;
                font-weight: 500;
                box-shadow: 0 2px 6px rgba(44,62,80,0.04);
                transition: border-color 0.2s, box-shadow 0.2s;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' fill='gray' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='gray' stroke-width='2' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 16px 16px;
            }
            .form-select.form-select-sm.w-auto:focus {
                border-color: #5bc0de;
                outline: none;
                box-shadow: 0 0 0 2px rgba(91,192,222,0.15);
                background-color: #fff;
            }
            .form-select.form-select-sm.w-auto option {
                font-weight: 400;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Revenue Chart
            const revenueLabels = {!! json_encode($revenues->pluck('date')) !!};
            const revenueData = {!! json_encode($revenues->pluck('total')) !!};
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue (VND)',
                        data: revenueData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toLocaleString('vi-VN') + 'đ';
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + 'đ';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Sales Funnel Chart
            const funnelCtx = document.getElementById('salesFunnelChart').getContext('2d');
            new Chart(funnelCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Visitors', 'Added to Cart', 'Completed Orders'],
                    datasets: [{
                        data: [1234, 456, 123],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(75, 192, 117, 0.8)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(75, 192, 117, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
