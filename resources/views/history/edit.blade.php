<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit History Entry</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('history.update', $history->history_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $history->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="ticket_id" class="block text-sm font-medium text-gray-700">Select Ticket</label>
                <select name="ticket_id" id="ticket_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select Ticket</option>
                    @foreach($tickets as $ticket)
                        <option value="{{ $ticket->ticket_id }}" {{ old('ticket_id', $history->ticket_id) == $ticket->ticket_id ? 'selected' : '' }}>{{ $ticket->ticket_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="payment_id" class="block text-sm font-medium text-gray-700">Select Payment</label>
                <select name="payment_id" id="payment_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select Payment</option>
                    @foreach($payments as $payment)
                        <option value="{{ $payment->payment_id }}" {{ old('payment_id', $history->payment_id) == $payment->payment_id ? 'selected' : '' }}>{{ $payment->payment_id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="flight_date" class="block text-sm font-medium text-gray-700">Flight Date</label>
                <input type="date" id="flight_date" name="flight_date" value="{{ old('flight_date', $history->flight_date) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

        <button type="submit" class="btn btn-primary">Update History</button>
        </form>
    </div>
</x-app-layout>
