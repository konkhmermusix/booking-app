@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary"><i class="fas fa-history me-2"></i>ប្រវត្តិការកក់ (Booking History)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>លេខកូដ</th>
                                    <th>ឈ្មោះបន្ទប់/សេវាកម្ម</th>
                                    <th>ថ្ងៃចូល/ចេញ</th>
                                    <th>តម្លៃសរុប</th>
                                    <th>ស្ថានភាព</th>
                                    <th>សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $booking->room->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $booking->check_in }} ដល់ {{ $booking->check_out }}
                                        </small>
                                    </td>
                                    <td class="text-primary fw-bold">${{ number_format($booking->total_price, 2) }}</td>
                                    <td>
                                        @if($booking->status == 'confirmed')
                                        <span class="badge bg-success">ជោគជ័យ</span>
                                        @elseif($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">រង់ចាំ</span>
                                        @else
                                        <span class="badge bg-danger">បោះបង់</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i> មើលលម្អិត
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" alt="Empty">
                                        <p class="mt-2 text-muted">មិនទាន់មានទិន្នន័យការកក់នៅឡើយទេ។</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (ប្រសិនបើមាន) --}}
                    <div class="mt-3">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection