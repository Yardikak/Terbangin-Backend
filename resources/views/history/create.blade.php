<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Add New History Entry</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('history.store') }}">
            @csrf

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
                <select name="user_id" id="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="ticket_id" class="block text-sm font-medium text-gray-700">Select Ticket</label>
                <select name="ticket_id" id="ticket_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($tickets as $ticket)
                        <option value="{{ $ticket->ticket_id }}">
                            Ticket ID: {{ $ticket->ticket_id }} - Flight ID: {{ $ticket->flight->flight_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="payment_id" class="block text-sm font-medium text-gray-700">Select Payment</label>
                <select name="payment_id" id="payment_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($payments as $payment)
                        <option value="{{ $payment->payment_id }}">
                            Payment ID: {{ $payment->payment_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="flight_date" class="block text-sm font-medium text-gray-700">Flight Date</label>
                <input type="date" id="flight_date" name="flight_date" value="{{ old('flight_date') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="btn btn-primary">Save Flight</button>
        </form>

        <div class="mt-4">
            <a href="{{ route('history.index') }}" class="text-blue-600 hover:text-blue-700">
                < Back to History List
            </a>
        </div>
    </div>
</x-app-layout>
