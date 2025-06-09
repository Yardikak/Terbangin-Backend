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

        <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
            @csrf

            <!-- Select Ticket -->
            <div class="mb-4">
                <label for="ticket_id" class="block text-sm font-medium text-gray-700">Select Ticket</label>
                <select name="ticket_id" id="ticket_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" onchange="getTicketPrice()">
                    <option value="">Select Ticket</option>
                    @foreach($tickets as $ticket)
                        <option value="{{ $ticket->ticket_id }}" data-flight-id="{{ $ticket->flight->flight_id }}" data-flight-number="{{ $ticket->flight->flight_number }}" data-price="{{ $ticket->flight->price }}">
                            Ticket ID: {{ $ticket->ticket_id }} - Flight ID: {{ $ticket->flight->flight_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Promo -->
            <div class="mb-4">
                <label for="promo_id" class="block text-sm font-medium text-gray-700">Select Promo</label>
                <select name="promo_id" id="promo_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">Select Promo</option>
                    @foreach($promo as $p)
                        <option value="{{ $p->promo_id }}">
                            Promo Code: {{ $p->promo_code }} - Discount: {{ $p->discount }}%
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" oninput="updateTotalPrice()">
            </div>

            <!-- Total Price -->
            <div class="mb-4">
                <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                <input type="number" id="total_price" name="total_price" step="0.01" value="{{ old('total_price') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>

            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">Save Payment</button>
        </form>
    </div>

    <script>
    // This function is triggered when the ticket is selected from the dropdown
    function getTicketPrice() {
        const ticketId = document.getElementById('ticket_id').value;
        if (!ticketId) return; // Exit if no ticket is selected

        const ticketOption = document.querySelector(`#ticket_id option[value="${ticketId}"]`);

        const price = ticketOption ? parseFloat(ticketOption.getAttribute('data-price')) : 0;

        if (price) {
            window.ticketPrice = price; // Store the ticket price in a global variable
            updateTotalPrice(); // Update the total price with the selected quantity
        } else {
            fetch(`/ticket-flight/${ticketId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.flight_id && data.price) {
                        window.ticketPrice = data.price;
                        updateTotalPrice();
                    } else {
                        console.error('Error: Flight ID or price not found for ticket');
                    }
                })
                .catch(error => console.error('Error fetching flight data:', error));
        }
    }

    // This function updates the total price based on the selected quantity and promo discount
    function updateTotalPrice() {
        const quantity = document.getElementById('quantity').value;
        const promoId = document.getElementById('promo_id').value;

        if (window.ticketPrice) {
            let totalPrice = window.ticketPrice * quantity;

            if (promoId) {
                const discount = @json($promo->pluck('discount', 'promo_id'));
                if (discount[promoId]) {
                    totalPrice -= (totalPrice * (discount[promoId] / 100)); // Apply the discount
                }
            }

            // Update the total price field
            document.getElementById('total_price').value = totalPrice.toFixed(2);
        }
    }

    // This function is triggered just before form submission to ensure the total price is updated
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        updateTotalPrice(); // Ensure total price is updated when form is about to be submitted
    });
    </script>
</x-app-layout>
