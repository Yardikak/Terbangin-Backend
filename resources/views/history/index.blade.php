<x-app-layout>
    <div class="container mt-10">
        <h1 class="text-2xl font-bold mb-6">History List</h1>
        <a href="{{ route('history.create') }}" class="btn btn-primary mb-4">Add History</a>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table table-bordered">
                <thead class="bg-gray-100 text-left text-gray-600 font-semibold">
                    <tr>
                        <th class="px-6 py-3">History ID</th>
                        <th class="px-6 py-3">User ID</th>
                        <th class="px-6 py-3">Ticket ID</th>
                        <th class="px-6 py-3">Payment ID</th>
                        <th class="px-6 py-3">Flight Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($history as $item)
                    <tr>
                        <td class="px-6 py-4">{{ $item->history_id }}</td>
                        <td class="px-6 py-4">{{ $item->user_id }}</td>
                        <td class="px-6 py-4">{{ $item->ticket_id }}</td>
                        <td class="px-6 py-4">{{ $item->payment_id }}</td>
                        <td class="px-6 py-4">{{ $item->flight_date }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('history.edit', ['history' => $item->history_id]) }}" class="btn btn-warning">Edit</a>

                            <!-- Delete Form -->
                            <form action="{{ route('history.destroy', ['history' => $item->history_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?')">
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
