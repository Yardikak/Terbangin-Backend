<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Ticket List</h1>

        @if (session('status'))
            <div class="mb-4 bg-green-100 text-green-700 p-4 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mb-4 inline-block">Add Ticket</a>

        <table class="min-w-full bg-white border border-gray-200 table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left font-semibold">Ticket ID</th>
                    <th class="py-2 px-4 text-left font-semibold">Flight ID</th>
                    <th class="py-2 px-4 text-left font-semibold">Passenger</th>
                    <th class="py-2 px-4 text-left font-semibold">Status</th>
                    <th class="py-2 px-4 text-left font-semibold">Purchase Date</th>
                    <th class="py-2 px-4 text-left font-semibold">E-Ticket</th>
                    <th class="py-2 px-4 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $ticket->ticket_id }}</td>
                        <td class="py-2 px-4">{{ $ticket->flight_id }}</td>
                        <td class="py-2 px-4">{{ $ticket->passenger ? $ticket->passenger->name : 'N/A' }}</td>
                        <td class="py-2 px-4">{{ $ticket->status }}</td>
                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($ticket->purchase_date)->format('Y-m-d') }}</td>
                        <td class="py-2 px-4">{{ $ticket->e_ticket }}</td>
                        <td class="py-2 px-4 flex space-x-2">
                            <a href="{{ route('tickets.edit', $ticket->ticket_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('tickets.destroy', $ticket->ticket_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
