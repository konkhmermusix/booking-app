@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-20 text-center">
    <div class="max-w-md mx-auto bg-white p-10 rounded-3xl shadow-lg">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="text-3xl font-bold mb-2">ការកក់ជោគជ័យ!</h1>
        <p class="text-gray-500 mb-8">លេខកូដកក់របស់អ្នកគឺ: <span class="font-bold text-blue-600">{{ $booking->booking_code }}</span></p>

        <div class="space-y-3">
            <a href="{{ route('booking.history') }}" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold">មើលប្រវត្តិកក់</a>
            <a href="/" class="block w-full bg-gray-100 text-gray-600 py-3 rounded-xl font-bold">ត្រឡប់ទៅទំព័រដើម</a>
        </div>
    </div>
</div>
@endsection