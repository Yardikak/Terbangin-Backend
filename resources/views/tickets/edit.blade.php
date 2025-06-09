<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit Ticket</h1>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Ticket Form -->
        <form method="POST" action="{{ route('tickets.update', $ticket->ticket_id) }}">
            @csrf
            @method('PUT')

            <!-- Flight Selection -->
            <div class="mb-4">
                <label for="flight_id" class="block text-sm font-medium text-gray-700">Select Flight</label>
                <select name="flight_id" id="flight_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="" disabled>Select a Flight</option>
                    @foreach ($flights as $flight)
                        <option value="{{ $flight->flight_id }}" {{ old('flight_id', $ticket->flight_id) == $flight->flight_id ? 'selected' : '' }}>
                            {{ $flight->flight_number }} - {{ $flight->airline_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- User Selection -->
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="" disabled>Select a User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Passenger Selection -->
            <div class="mb-4">
                <label for="passenger_id" class="block text-sm font-medium text-gray-700">Select Passenger</label>
                <select name="passenger_id" id="passenger_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="" disabled>Select a Passenger</option>
                    @foreach ($passengers as $passenger)
                        <option value="{{ $passenger->passenger_id }}" {{ old('passenger_id', $ticket->passenger_id) == $passenger->passenger_id ? 'selected' : '' }}>
                            {{ $passenger->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status input -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <input type="text" id="status" name="status" value="{{ old('status', $ticket->status) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Purchase Date -->
            <div class="mb-4">
                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                <input type="datetime-local" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', \Carbon\Carbon::parse($ticket->purchase_date)->format('Y-m-d\TH:i')) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- E-ticket input -->
            <div class="mb-4">
                <label for="e_ticket" class="block text-sm font-medium text-gray-700">E-Ticket</label>
                <input type="text" id="e_ticket" name="e_ticket" value="{{ old('e_ticket', $ticket->e_ticket) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Ticket</button>
            </div>
        </form>
    </div>
</x-app-layout>
