<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Add New Flight</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('flights.store') }}">
            @csrf
            <div class="mb-4">
                <label for="flight_number" class="block text-sm font-medium text-gray-700">Flight Number</label>
                <input type="text" id="flight_number" name="flight_number" value="{{ old('flight_number') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="airline_name" class="block text-sm font-medium text-gray-700">Airline Name</label>
                <input type="text" id="airline_name" name="airline_name" value="{{ old('airline_name') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="departure" class="block text-sm font-medium text-gray-700">Departure</label>
                <input type="datetime-local" id="departure" name="departure" value="{{ old('departure') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="arrival" class="block text-sm font-medium text-gray-700">Arrival</label>
                <input type="datetime-local" id="arrival" name="arrival" value="{{ old('arrival') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                <input type="text" id="destination" name="destination" value="{{ old('destination') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="from" class="block text-sm font-medium text-gray-700">From</label>
                <input type="text" id="from" name="from" value="{{ old('from') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" step="0.01">
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Flight</button>
        </form>
    </div>
</x-app-layout>
