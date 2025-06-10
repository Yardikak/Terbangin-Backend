<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Flight List</h1>
        <a href="{{ route('flights.create') }}" class="btn btn-primary mb-4">Add Flight</a>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table table-bordered">
                <thead class="bg-gray-100 text-left text-gray-600 font-semibold">
                    <tr>
                        <th class="px-6 py-3">Flight Number</th>
                        <th class="px-6 py-3">Airline Name</th>
                        <th class="px-6 py-3">Departure</th>
                        <th class="px-6 py-3">Arrival</th>
                        <th class="px-6 py-3">Destination</th>
                        <th class="px-6 py-3">From</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($flights as $flight)
                    <tr>
                        <td class="px-6 py-4">{{ $flight->flight_number }}</td>
                        <td class="px-6 py-4">{{ $flight->airline_name }}</td>
                        <td class="px-6 py-4">{{ $flight->departure }}</td>
                        <td class="px-6 py-4">{{ $flight->arrival }}</td>
                        <td class="px-6 py-4">{{ $flight->destination }}</td>
                        <td class="px-6 py-4">{{ $flight->from }}</td>
                        <td class="px-6 py-4">{{ number_format($flight->price, 2) }}</td>
                        <td class="px-6 py-4">{{ $flight->status }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('flights.edit', ['flight' => $flight->flight_id]) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('flights.destroy', ['flight' => $flight->flight_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this flight?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
