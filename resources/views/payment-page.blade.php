<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Include Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
</head>

<body>
    <h1>Checkout Page</h1>
    <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <input type="hidden" name="json" id="json_callback">

        <!-- Customer Details -->
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>

        <!-- Order Details -->
        <div>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required>
        </div>

        <button type="button" id="pay-button">Pay Now</button>
    </form>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(event) {
            event.preventDefault();
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var phone = document.getElementById('phone').value;
            var amount = document.getElementById('amount').value;

            // Send data to your server to create transaction
            fetch("{{ route('checkout.token') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        phone: phone,
                        amount: amount,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            sendResponseToForm(result);
                        },
                        onPending: function(result) {
                            sendResponseToForm(result);
                        },
                        onError: function(result) {
                            sendResponseToForm(result);
                        },
                        onClose: function() {
                            alert('You closed the popup without finishing the payment');
                        }
                    });
                });
        };

        function sendResponseToForm(result) {
            document.getElementById('json_callback').value = JSON.stringify(result);
            document.getElementById('payment-form').submit();
        }
    </script>
</body>

</html>
