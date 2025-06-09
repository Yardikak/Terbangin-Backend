<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit Payment</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('payments.update', $payment->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="payment_id" class="block text-sm font-medium text-gray-700">Payment ID</label>
                <input type="text" id="payment_id" name="payment_id" value="{{ old('payment_id', $payment->payment_id) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="number" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="Paid" {{ $payment->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Pending" {{ $payment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ $payment->status == 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Payment</button>
        </form>
    </div>
</x-app-layout>
