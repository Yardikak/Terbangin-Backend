<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Add New Ticket</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tickets.store') }}">
            @csrf

            <!-- Flight Selection -->
            <!-- Flight Selection -->
            <div class="mb-4">
                <label for="flight_id" class="block text-sm font-medium text-gray-700">Flight</label>
                <select name="flight_id" class="form-control" id="flight_id" required>
                    <option value="" disabled selected>Select a Flight</option>
                    @foreach ($flights as $flight)
                        <option value="{{ $flight->id }}" {{ old('flight_id') == $flight->id ? 'selected' : '' }}>
                            {{ $flight->id }} <!-- Display Flight ID instead of Flight Number -->
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Selection -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="Booked" {{ old('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="Checked-in" {{ old('status') == 'Checked-in' ? 'selected' : '' }}>Checked-in</option>
                </select>
            </div>

            <!-- Purchase Date -->
            <div class="mb-4">
                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- E-ticket -->
            <div class="mb-4">
                <label for="e_ticket" class="block text-sm font-medium text-gray-700">E-ticket</label>
                <input type="text" id="e_ticket" name="e_ticket" value="{{ old('e_ticket') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="btn btn-primary mt-4">
                Save Ticket
            </button>
        </form>
    </div>
</x-app-layout>
