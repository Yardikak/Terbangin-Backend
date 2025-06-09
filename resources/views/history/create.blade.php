<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Add New Payment</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <div class="mb-4">
                <label for="ticket_id" class="block text-sm font-medium text-gray-700">Select Ticket</label>
                <select name="ticket_id" id="ticket_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach($tickets as $ticket)
                        <option value="{{ $ticket->ticket_id }}">
                            Ticket ID: {{ $ticket->ticket_id }} - Flight ID: {{ $ticket->flight->flight_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="promo_id" class="block text-sm font-medium text-gray-700">Select Promo</label>
                <select name="promo_id" id="promo_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    @foreach($promos as $promo)
                        <option value="{{ $promo->promo_id }}">
                            Promo Code: {{ $promo->promo_code }} - Discount: {{ $promo->discount }}%
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                <input type="text" id="total_price" name="total_price" value="{{ old('total_price', $total_price ?? '') }}" readonly
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" id="status" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>

            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">Save Payment</button>
        </form>
    </div>
</x-app-layout>
