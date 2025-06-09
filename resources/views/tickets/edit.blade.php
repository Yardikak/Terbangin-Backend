<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit Ticket</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')

            <!-- Ticket Number -->
            <div class="mb-4">
                <label for="ticket_number" class="block text-sm font-medium text-gray-700">Ticket Number</label>
                <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number', $ticket->ticket_number) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Passenger Name -->
            <div class="mb-4">
                <label for="passenger_name" class="block text-sm font-medium text-gray-700">Passenger Name</label>
                <input type="text" id="passenger_name" name="passenger_name" value="{{ old('passenger_name', $ticket->passenger_name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Flight Selection -->
            <div class="mb-4">
                <label for="flight_id" class="block text-sm font-medium text-gray-700">Flight</label>
                <select name="flight_id" class="form-control" id="flight_id" required>
                    <option value="" disabled>Select a Flight</option>
                    @foreach ($flights as $flight)
                        <option value="{{ $flight->id }}" {{ old('flight_id', $ticket->flight_id) == $flight->id ? 'selected' : '' }}>
                            {{ $flight->id }} - {{ $flight->flight_number }} <!-- Display Flight ID and Flight Number -->
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Selection -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="Booked" {{ old('status', $ticket->status) == 'Booked' ? 'selected' : '' }}>Booked</option>
                    <option value="Cancelled" {{ old('status', $ticket->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="Checked-in" {{ old('status', $ticket->status) == 'Checked-in' ? 'selected' : '' }}>Checked-in</option>
                </select>
            </div>

            <button type="submit" class="inline-block px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                Update Ticket
            </button>
        </form>
    </div>
</x-app-layout>
