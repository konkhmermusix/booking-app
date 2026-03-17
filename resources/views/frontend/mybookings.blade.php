 @extends('layouts.app')
 @section('title', 'បន្ទប់')

 @section('content')

 @foreach($bookings as $booking)
 <div class="border p-4 rounded-xl mb-4 flex justify-between items-center">
     <div>
         <h4 class="font-bold">កូដ៖ {{ $booking->booking_code }}</h4>
         <p class="text-sm">ប្រភេទ៖ {{ $booking->room->roomType->name }}</p>
         <p class="text-xs text-gray-500">{{ $booking->check_in }} ដល់ {{ $booking->check_out }}</p>
     </div>
     <div class="text-right">
         <span class="px-3 py-1 rounded-full text-xs 
                {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
             {{ $booking->status }}
         </span>
         <p class="font-bold text-blue-600 mt-1">${{ number_format($booking->total_price, 2) }}</p>
     </div>
 </div>
 @endforeach

 @endsection