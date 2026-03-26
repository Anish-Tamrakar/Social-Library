@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pb-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 font-serif">Admin Dashboard</h1>
            <p class="text-zinc-500 mt-1">Manage the platform and review content.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.books.pending') }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100">Pending Books</a>
            <a href="{{ route('admin.books.manage') }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100">All Books</a>
            <a href="{{ route('admin.ratings.flagged') }}" class="px-4 py-2 bg-rose-50 text-rose-700 rounded-lg text-sm font-medium hover:bg-rose-100">Flagged Reviews</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm">
            <h3 class="text-sm font-medium text-zinc-500">Total Users</h3>
            <p class="text-3xl font-bold text-zinc-900 mt-2">{{ $stats['total_users'] }} <span class="text-sm text-emerald-600 font-medium ml-1">+{{ $stats['daily_users'] }} today</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm">
            <h3 class="text-sm font-medium text-zinc-500">Total Books</h3>
            <p class="text-3xl font-bold text-zinc-900 mt-2">{{ $stats['total_books'] }} <span class="text-sm text-emerald-600 font-medium ml-1">+{{ $stats['daily_books'] }} today</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm">
            <h3 class="text-sm font-medium text-zinc-500">Total Donations</h3>
            <p class="text-3xl font-bold text-zinc-900 mt-2">${{ number_format($stats['total_donations'], 2) }} <span class="text-sm text-emerald-600 font-medium ml-1">+${{ number_format($stats['daily_donations'], 2) }} today</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm">
            <h3 class="text-sm font-medium text-zinc-500">Pending Approvals</h3>
            <p class="text-3xl font-bold text-zinc-900 mt-2">{{ $stats['pending_books'] }}</p>
            @if($stats['pending_books'] > 0)
                <a href="{{ route('admin.books.pending') }}" class="text-sm text-indigo-600 mt-2 block">Review now &rarr;</a>
            @endif
        </div>
    </div>
</div>
@endsection
