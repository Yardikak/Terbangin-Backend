<x-app-layout>
    <div class="container mt-10">
        <h1 class="text-2xl font-bold mb-6">Payment List</h1>
        <a href="{{ route('payments.create') }}" class="btn btn-primary mb-4">Add Payment</a>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table table-bordered">
                <thead class="bg-gray-100 text-left text-gray-600 font-semibold">
                    <tr>
                        <th class="px-6 py-3">Payment ID</th>
                        <th class="px-6 py-3">Ticket ID</th>
                        <th class="px-6 py-3">Promo ID</th>
                        <th class="px-6 py-3">Quantity</th>
                        <th class="px-6 py-3">Total Price</th>
                        <th class="px-6 py-3">Payment Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($payments as $payment)
                    <tr>
                        <td class="px-6 py-4">{{ $payment->payment_id }}</td>
                        <td class="px-6 py-4">{{ $payment->ticket_id }}</td>
                        <td class="px-6 py-4">{{ $payment->promo_id }}</td>
                        <td class="px-6 py-4">{{ $payment->quantity }}</td>
                        <td class="px-6 py-4">{{ number_format($payment->total_price, 2) }}</td>
                        <td class="px-6 py-4">{{ $payment->payment_status }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- Edit Button with Correct Route -->
                            <a href="{{ route('payments.edit', ['payment' => $payment->payment_id]) }}" class="btn btn-warning">Edit</a>

                            <!-- Delete Form with Correct Route -->
                            <form action="{{ route('payments.destroy', ['payment' => $payment->payment_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment?')">
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
