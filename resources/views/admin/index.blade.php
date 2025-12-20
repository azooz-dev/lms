@extends('admin.dashboard')

@section('content')

<div class="page-content">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Orders</p>
                            <h4 class="my-1 text-info">{{ number_format($totalOrders) }}</h4>
                            <p class="mb-0 font-13">{{ $orderChange >= 0 ? '+' : '' }}{{ number_format($orderChange, 1) }}% from last week</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Revenue</p>
                            <h4 class="my-1 text-danger">${{ number_format($totalRevenue, 2) }}</h4>
                            <p class="mb-0 font-13">{{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}% from last week</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Courses</p>
                            <h4 class="my-1 text-success">{{ number_format($totalCourses) }}</h4>
                            <p class="mb-0 font-13">Active courses in platform</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Customers</p>
                            <h4 class="my-1 text-warning">{{ number_format($totalCustomers) }}</h4>
                            <p class="mb-0 font-13">{{ $customerChange >= 0 ? '+' : '' }}{{ number_format($customerChange, 1) }}% from last week</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div><!--end row-->

    <!-- Additional Statistics Row -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Instructors</p>
                            <h4 class="my-1 text-primary">{{ number_format($totalInstructors) }}</h4>
                            <p class="mb-0 font-13">Active instructors</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto"><i class='bx bxs-user-detail'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Completed Orders</p>
                            <h4 class="my-1 text-success">{{ number_format($completedOrders) }}</h4>
                            <p class="mb-0 font-13">Successful transactions</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto"><i class='bx bxs-check-circle'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pending Orders</p>
                            <h4 class="my-1 text-warning">{{ number_format($pendingOrders) }}</h4>
                            <p class="mb-0 font-13">Awaiting payment</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto"><i class='bx bxs-time'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Reviews</p>
                            <h4 class="my-1 text-info">{{ number_format($totalReviews) }}</h4>
                            <p class="mb-0 font-13">{{ $pendingReviews }} pending approval</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-info text-white ms-auto"><i class='bx bxs-star'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->

    <div class="row">
        <div class="col-12 col-lg-12 d-flex">
            <div class="card radius-10 w-100">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Sales Overview</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                    <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Sales</span>
                    <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Visits</span>
                </div>
                <div class="chart-container-1">
                    <canvas id="chart1"></canvas>
                </div>
                
                <script>
                    // Chart data from backend
                    const monthlyData = @json($monthlySales);
                    
                    // Prepare chart data
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const salesData = new Array(12).fill(0);
                    const orderData = new Array(12).fill(0);
                    
                    monthlyData.forEach(item => {
                        salesData[item.month - 1] = parseFloat(item.total_sales);
                        orderData[item.month - 1] = parseInt(item.order_count);
                    });
                    
                    // Create the chart
                    const ctx = document.getElementById('chart1').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Sales ($)',
                                data: salesData,
                                borderColor: '#14abef',
                                backgroundColor: 'rgba(20, 171, 239, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }, {
                                label: 'Orders',
                                data: orderData,
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y1'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    title: {
                                        display: true,
                                        text: 'Sales ($)'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Orders'
                                    },
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                <div class="col">
                    <div class="p-3">
                        <h5 class="mb-0">{{ number_format($totalRevenue, 0) }}</h5>
                        <small class="mb-0">Total Revenue <span> <i class="bx bx-up-arrow-alt align-middle"></i> {{ number_format($revenueChange, 1) }}%</span></small>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3">
                        <h5 class="mb-0">{{ number_format($totalOrders) }}</h5>
                        <small class="mb-0">Total Orders <span> <i class="bx bx-up-arrow-alt align-middle"></i> {{ number_format($orderChange, 1) }}%</span></small>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3">
                        <h5 class="mb-0">{{ number_format($totalCustomers) }}</h5>
                        <small class="mb-0">Total Customers <span> <i class="bx bx-up-arrow-alt align-middle"></i> {{ number_format($customerChange, 1) }}%</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card radius-10">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div>
                <h6 class="mb-0">Recent Orders</h6>
            </div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                    </li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course</th>
                            <th>Image</th>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>{{ $order->course_title ?? 'N/A' }}</td>
                            <td>
                                @if($order->course && $order->course->image)
                                    <img src="{{ asset('storage/' . $order->course->image) }}" class="product-img-2" alt="course img">
                                @else
                                    <img src="{{ asset('backend/assets/images/products/01.png') }}" class="product-img-2" alt="course img">
                                @endif
                            </td>
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if($order->payment && $order->payment->status == 'completed')
                                    <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Paid</span>
                                @elseif($order->payment && $order->payment->status == 'pending')
                                    <span class="badge bg-gradient-blooker text-white shadow-sm w-100">Pending</span>
                                @else
                                    <span class="badge bg-gradient-bloody text-white shadow-sm w-100">Failed</span>
                                @endif
                            </td>
                            <td>${{ number_format($order->course_price, 2) }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="progress" style="height: 6px;">
                                    @if($order->payment && $order->payment->status == 'completed')
                                        <div class="progress-bar bg-gradient-quepal" role="progressbar" style="width: 100%"></div>
                                    @elseif($order->payment && $order->payment->status == 'pending')
                                        <div class="progress-bar bg-gradient-blooker" role="progressbar" style="width: 60%"></div>
                                    @else
                                        <div class="progress-bar bg-gradient-bloody" role="progressbar" style="width: 40%"></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No recent orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection