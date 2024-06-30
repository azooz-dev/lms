@extends('admin.dashboard')

@section('content')


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Smtp Setting</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">Report By Date : </h5>

            <div class="row">
                <div class="col-md-6">
                    <label for="input1" class="form-label">From :</label>
                    <input type="date" class="form-control" id="from">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">To :</label>
                    <input type="date" class="form-control" id="to">
                </div>

            </div>

        </div>
        </div>
        

        <div class="card radius-10 d-none" id="tableCard">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="bodyTable">
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableCard = document.getElementById('tableCard');
        const toInput = document.getElementById('to');

        toInput.addEventListener('change', function() {
            const selectedTo = this.value;
            const selectedFrom = document.getElementById('from').value;

            fetchReports(selectedTo, selectedFrom);
        });

        async function fetchReports(selectedTo, selectedFrom) {

            const url = '{{ route('admin.date_reports') }}';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'to': selectedTo,
                        'from': selectedFrom
                    })
                });
                const dataFromResponse = await response.json();
                displayReports(dataFromResponse);
            } catch (error) {
                console.log('Error:', error);
            }
        }

        function displayReports(data) {
            if (data.reports.length === 0) {
                tableCard.classList.add('d-none');
            } else {
                const bodyTable = document.getElementById('bodyTable');
                bodyTable.innerHTML = '';
                data.reports.forEach((report, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${report.date}</td>
                        <td class="text-center">${report.payment.name}</td>
                        <td class="text-center">${report.payment.email}</td>
                        <td class="text-center">${report.payment.invoice_number}</td>
                        <td class="text-center">$${report.payment.total_amount}</td>
                        <td class="text-center">${report.payment.payment_type}</td>
                        <td class="text-center"><span class="badge bg-success">${report.payment.status}</span></td>
                    `;
                    bodyTable.appendChild(row);
                });
                tableCard.classList.remove('d-none');
            }
        }
    });
</script>
@endsection