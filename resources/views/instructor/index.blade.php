@extends('instructor.dashboard')
<link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
<link href="https://fonts.googleapis.com/css?family=Kanit:200" rel="stylesheet">

@section('content')

<div class="page-content">
    @if (Auth::user()->status == \App\Enums\UserStatus::ACTIVE)
    
    {{-- Row 1: Main Revenue & Student Metrics --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Earnings</p>
                            <h4 class="my-1 text-success">${{ number_format($totalRevenue, 2) }}</h4>
                            <p class="mb-0 font-13">{{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}% from last week</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-dollar-circle'></i>
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
                            <p class="mb-0 text-secondary">This Month</p>
                            <h4 class="my-1 text-info">${{ number_format($thisMonthEarnings, 2) }}</h4>
                            <p class="mb-0 font-13">{{ date('F Y') }} earnings</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-calendar-check'></i>
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
                            <p class="mb-0 text-secondary">Total Students</p>
                            <h4 class="my-1 text-warning">{{ number_format($totalStudents) }}</h4>
                            <p class="mb-0 font-13">{{ $studentChange >= 0 ? '+' : '' }}{{ number_format($studentChange, 1) }}% from last week</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-graduation'></i>
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
                            <p class="mb-0 text-secondary">Total Orders</p>
                            <h4 class="my-1 text-danger">{{ number_format($totalOrders) }}</h4>
                            <p class="mb-0 font-13">{{ $completedOrders }} completed</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-shopping-bag'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div><!--end row-->

    {{-- Row 2: Course & Engagement Metrics --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-1">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">My Courses</p>
                            <h4 class="my-1 text-primary">{{ number_format($totalCourses) }}</h4>
                            <p class="mb-0 font-13">
                                <span class="text-success">{{ $publishedCourses }} published</span> · 
                                <span class="text-muted">{{ $draftCourses }} draft</span>
                            </p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto"><i class='bx bxs-book-content'></i>
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
                            <p class="mb-0 text-secondary">Total Lectures</p>
                            <h4 class="my-1 text-success">{{ number_format($totalLectures) }}</h4>
                            <p class="mb-0 font-13">Content created</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto"><i class='bx bxs-video'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 {{ $unansweredQuestions > 0 ? 'border-danger' : 'border-info' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Student Questions</p>
                            <h4 class="my-1 {{ $unansweredQuestions > 0 ? 'text-danger' : 'text-info' }}">{{ number_format($unansweredQuestions) }} <small class="font-13">unanswered</small></h4>
                            <p class="mb-0 font-13">
                                {{ number_format($questionResponseRate, 1) }}% response rate
                            </p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle {{ $unansweredQuestions > 0 ? 'bg-gradient-bloody' : 'bg-gradient-info' }} text-white ms-auto"><i class='bx bxs-message-dots'></i>
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
                            <p class="mb-0 text-secondary">Wishlist Interest</p>
                            <h4 class="my-1 text-warning">{{ number_format($wishlistCount) }}</h4>
                            <p class="mb-0 font-13">Students interested</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-heart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->

    {{-- Row 3: Reviews & Rating Section --}}
    <div class="row mt-3">
        {{-- Rating Overview Card --}}
        <div class="col-12 col-lg-4 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class='bx bxs-star text-warning me-1'></i> Rating Overview</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h1 class="display-4 fw-bold text-warning mb-0">{{ number_format($averageRating, 1) }}</h1>
                        <div class="rating-stars mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($averageRating))
                                    <i class='bx bxs-star text-warning'></i>
                                @elseif ($i - 0.5 <= $averageRating)
                                    <i class='bx bxs-star-half text-warning'></i>
                                @else
                                    <i class='bx bx-star text-warning'></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">Based on {{ number_format($totalReviews) }} reviews</p>
                        <p class="font-13">
                            {{ $reviewChange >= 0 ? '+' : '' }}{{ number_format($reviewChange, 1) }}% new reviews this week
                        </p>
                    </div>
                    
                    {{-- Rating Distribution Bars --}}
                    @foreach ($ratingDistribution as $rating)
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2" style="min-width: 20px;">{{ $rating['rating'] }}</span>
                        <i class='bx bxs-star text-warning me-2'></i>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $rating['percentage'] }}%"></div>
                        </div>
                        <span class="ms-2 text-muted" style="min-width: 35px;">{{ $rating['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sales Overview Chart --}}
        <div class="col-12 col-lg-8 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0"><i class='bx bx-line-chart text-primary me-1'></i> Sales Overview</h6>
                        </div>
                        <div class="ms-auto font-13">
                            <span class="border px-2 rounded"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Revenue</span>
                            <span class="border px-2 rounded ms-1"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Orders</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container-1">
                        <canvas id="chart1"></canvas>
                    </div>
                    
                    <script>
                        const monthlyData = @json($monthlySales);
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        const salesData = new Array(12).fill(0);
                        const orderData = new Array(12).fill(0);
                        
                        monthlyData.forEach(item => {
                            salesData[item.month - 1] = parseFloat(item.total_sales);
                            orderData[item.month - 1] = parseInt(item.order_count);
                        });
                        
                        const ctx = document.getElementById('chart1').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [{
                                    label: 'Revenue ($)',
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
                                interaction: { intersect: false, mode: 'index' },
                                scales: {
                                    y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Revenue ($)' } },
                                    y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Orders' }, grid: { drawOnChartArea: false } }
                                },
                                plugins: { legend: { display: true, position: 'top' } }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Top Courses & Pending Questions --}}
    <div class="row mt-3">
        {{-- Top Performing Courses --}}
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class='bx bxs-trophy text-warning me-1'></i> Top Performing Courses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Course</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCourses as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('backend/assets/images/products/01.png') }}" 
                                                 class="rounded-circle" width="40" height="40" alt="course">
                                            <span class="ms-2">{{ Str::limit($course->title, 30) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary text-primary">{{ $course->total_orders }}</span></td>
                                    <td class="text-success fw-bold">${{ number_format($course->total_revenue, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No sales data yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Questions --}}
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">
                            <i class='bx bxs-message-detail text-info me-1'></i> 
                            Pending Questions
                            @if($unansweredQuestions > 0)
                                <span class="badge bg-danger ms-1">{{ $unansweredQuestions }}</span>
                            @endif
                        </h6>
                        @if($unansweredQuestions > 0)
                        <a href="{{ route('instructor.all_questions', Auth::id()) }}" class="ms-auto btn btn-sm btn-outline-primary">View All</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @forelse($pendingQuestions as $question)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <img src="{{ $question->user->photo ? Storage::url('public/upload/user_images/' . $question->user->photo) : asset('storage/upload/images.jpg') }}" 
                             class="rounded-circle" width="40" height="40" alt="user">
                        <div class="ms-3 flex-grow-1">
                            <p class="mb-1 fw-bold">{{ $question->user->name ?? 'Student' }}</p>
                            <p class="mb-1 text-muted font-13">{{ Str::limit($question->subject, 60) }}</p>
                            <small class="text-muted">{{ $question->created_at->diffForHumans() }} · {{ Str::limit($question->course->title ?? 'Course', 20) }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class='bx bx-check-circle text-success' style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">All questions answered! Great job! 🎉</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Row 5: Recent Reviews & Recent Orders --}}
    <div class="row mt-3">
        {{-- Recent Reviews --}}
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0"><i class='bx bxs-star text-warning me-1'></i> Recent Reviews</h6>
                        <a href="{{ route('instructor.reviews', Auth::id()) }}" class="ms-auto btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($recentReviews as $review)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <img src="{{ $review->user->photo ? Storage::url('public/upload/user_images/' . $review->user->photo) : asset('storage/upload/images.jpg') }}" 
                             class="rounded-circle" width="40" height="40" alt="user">
                        <div class="ms-3 flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1 fw-bold">{{ $review->user->name ?? 'Student' }}</p>
                                <div class="rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class='bx {{ $i <= $review->rating ? 'bxs-star text-warning' : 'bx-star text-muted' }}' style="font-size: 12px;"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="mb-1 text-muted font-13">{{ Str::limit($review->message, 80) }}</p>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }} · {{ Str::limit($review->course->title ?? 'Course', 25) }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class='bx bx-star text-muted' style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">No reviews yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-12 col-lg-6 d-flex">
            <div class="card radius-10 w-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0"><i class='bx bxs-cart text-primary me-1'></i> Recent Orders</h6>
                        <a href="{{ route('instructor.all_orders', Auth::id()) }}" class="ms-auto btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Course</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>{{ Str::limit($order->course_title ?? 'N/A', 20) }}</td>
                                    <td>{{ Str::limit($order->user->name ?? 'N/A', 15) }}</td>
                                    <td>${{ number_format($order->course_price, 2) }}</td>
                                    <td>
                                        @if($order->payment && $order->payment->status == 'completed')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment && $order->payment->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No orders yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Footer --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 border-end">
                            <div class="p-2">
                                <i class='bx bxs-coupon text-primary' style="font-size: 24px;"></i>
                                <h5 class="mb-0 mt-2">{{ $activeCoupons }}</h5>
                                <p class="text-muted mb-0 font-13">Active Coupons</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <div class="p-2">
                                <i class='bx bxs-message-dots text-info' style="font-size: 24px;"></i>
                                <h5 class="mb-0 mt-2">{{ $totalQuestions }}</h5>
                                <p class="text-muted mb-0 font-13">Total Questions</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <div class="p-2">
                                <i class='bx bxs-star text-warning' style="font-size: 24px;"></i>
                                <h5 class="mb-0 mt-2">{{ $totalReviews }}</h5>
                                <p class="text-muted mb-0 font-13">Total Reviews</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-2">
                                <i class='bx bxs-heart text-danger' style="font-size: 24px;"></i>
                                <h5 class="mb-0 mt-2">{{ $wishlistCount }}</h5>
                                <p class="text-muted mb-0 font-13">Wishlist Adds</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else

    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h1>403</h1>
            </div>
            <h2>Oops! Your account is <span class="text-danger">inactive</span></h2>
            <p>Your account is currently inactive. Please contact the administrator to activate your account.</p>
        </div>
    </div>

    @endif
</div>

@endsection