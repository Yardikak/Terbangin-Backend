<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Ticket List</h1>

        <!-- Tampilkan notifikasi status jika ada -->
        @if (session('status'))
            <div class="mb-4 bg-green-100 text-green-700 p-4 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <!-- Tombol untuk menambah ticket baru -->
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-4">Add Ticket</a>

        <!-- Tabel Ticket -->
        <table class="min-w-full bg-white border border-gray-200 table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 text-left font-semibold">Ticket ID</th>
                    <th class="py-2 px-4 text-left font-semibold">Flight ID</th>
                    <th class="py-2 px-4 text-left font-semibold">Status</th>
                    <th class="py-2 px-4 text-left font-semibold">Purchase Date</th>
                    <th class="py-2 px-4 text-left font-semibold">E-Ticket</th>
                    <th class="py-2 px-4 text-left font-semibold">Actions</th> <!-- Kolom untuk Aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $ticket->ticket_id }}</td>
                        <td class="py-2 px-4">{{ $ticket->flight_id }}</td>
                        <td class="py-2 px-4">{{ $ticket->status }}</td>
                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($ticket->purchase_date)->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4">{{ $ticket->e_ticket }}</td>
                        <td class="py-2 px-4 flex space-x-2"> <!-- Tombol Aksi -->
                            <!-- Edit Button -->
                            <a href="{{ route('tickets.edit', $ticket->ticket_id) }}" class="btn btn-warning">Edit</a>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('tickets.destroy', $ticket->ticket_id) }}" method="POST" class="inline">
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
</x-app-layout>
