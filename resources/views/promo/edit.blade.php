<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit Promo</h1>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('promo.update', $promo->promo_id) }}">
            @csrf
            @method('PUT')

            <!-- Promo Code Field -->
            <div class="mb-4">
                <label for="promo_code" class="block text-sm font-medium text-gray-700">Promo Code</label>
                <input type="text" id="promo_code" name="promo_code" value="{{ old('promo_code', $promo->promo_code) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Description Field -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $promo->description) }}</textarea>
            </div>

            <!-- Discount Field -->
            <div class="mb-4">
                <label for="discount" class="block text-sm font-medium text-gray-700">Discount (%)</label>
                <input type="number" id="discount" name="discount" value="{{ old('discount', $promo->discount) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Valid Until Field -->
            <div class="mb-4">
                <label for="valid_until" class="block text-sm font-medium text-gray-700">Valid Until</label>
                <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until', $promo->valid_until) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="btn btn-primary">Update Promo</button>
        </form>
    </div>
</x-app-layout>
