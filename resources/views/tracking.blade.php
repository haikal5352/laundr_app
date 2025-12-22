<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking Order - {{ config('app.name') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div class="w-full max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-indigo-600">Track Your Laundry</h1>
                <p class="text-gray-600">Enter your Transaction ID to check status.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('tracking') }}" method="GET" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="id" placeholder="Transaction ID (e.g. 1)"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        value="{{ request('id') }}">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Check</button>
                    <a href="{{ route('home') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">Back</a>
                </div>
            </form>

            @if(isset($transaction))
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-lg">Order #{{ $transaction->id }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $transaction->status === 'process' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $transaction->status === 'done' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $transaction->status === 'taken' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer:</span>
                            <span class="font-medium">{{ $transaction->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Service:</span>
                            <span class="font-medium">{{ $transaction->service->name }} ({{ $transaction->qty }} kg)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span
                                class="font-medium">{{ $transaction->type == 'pickup_delivery' ? 'Pickup & Delivery' : 'Drop-off' }}</span>
                        </div>
                        @if($transaction->address)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-medium text-right">{{ Str::limit($transaction->address, 30) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="text-gray-600 font-bold">Total:</span>
                            <span class="font-bold text-indigo-600">Rp {{ number_format($transaction->total_price) }}</span>
                        </div>
                    </div>
                </div>
            @elseif(request('id'))
                <div class="text-center text-red-500 py-4">Transaction not found.</div>
            @endif
        </div>
    </div>
</body>

</html>