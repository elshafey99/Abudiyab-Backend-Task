# Paymob Payment Gateway Integration - Laravel

A Laravel-based payment processing system integrated with Paymob payment gateway. This project provides RESTful API endpoints for order creation and payment initiation.

## Features

-   ✅ Order creation and management
-   ✅ Paymob payment gateway integration
-   ✅ Payment iframe generation
-   ✅ Interface-based architecture (easily extensible to other gateways)
-   ✅ RESTful API endpoints
-   ✅ Request validation
-   ✅ Comprehensive error handling

## Tech Stack

-   **Framework**: Laravel 12.x
-   **Database**: mysql (configurable)
-   **Payment Gateway**: Paymob
-   **PHP Version**: 8.2+

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd abudiyab-backend-task
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy `.env.example` to `.env`:

```bash
copy .env.example .env
```

Update the following Paymob credentials in `.env`:

```env
PAYMOB_API_KEY=your_paymob_api_key
PAYMOB_INTEGRATION_ID=your_integration_id
PAYMOB_IFRAME_ID=your_iframe_id
PAYMOB_HMAC_SECRET=your_hmac_secret
PAYMOB_CURRENCY=EGP
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Start Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

### 1. Create Order

**Endpoint**: `POST /api/orders`

**Request Body**:

```json
{
    "amount": 100.5,
    "currency": "EGP",
    "customer_email": "customer@example.com"
}
```

**Response** (201 Created):

```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "order_id": 1,
        "amount": "100.50",
        "currency": "EGP",
        "customer_email": "customer@example.com",
        "status": "pending",
        "created_at": "2025-12-10T15:00:00.000000Z"
    }
}
```

### 2. Initiate Payment

**Endpoint**: `POST /api/payments/initiate`

**Request Body**:

```json
{
    "order_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "phone_number": "01234567890"
}
```

**Response** (200 OK):

```json
{
    "success": true,
    "message": "Payment initiated successfully",
    "data": {
        "payment_id": 1,
        "order_id": 1,
        "payment_url": "https://accept.paymob.com/api/acceptance/iframes/843722?payment_token=...",
        "amount": "100.50",
        "currency": "EGP",
        "status": "pending"
    }
}
```

## Testing with Postman

1. Import the Postman collection: `Paymob_Payment_API.postman_collection.json`
2. Update the `base_url` variable if needed (default: `http://localhost:8000`)
3. Test the endpoints:
    - Create an order first
    - Use the returned `order_id` to initiate payment
    - Open the `payment_url` in a browser to complete the payment

## Architecture

This project uses an **interface-based architecture** for payment gateway integration:

```
PaymentGatewayInterface (Contract)
        ↓
PaymobPaymentService (Implementation)
        ↓
PaymentService (Orchestration)
        ↓
PaymentController (API Layer)
```

### Benefits:

-   Easy to add new payment gateways (Stripe, PayPal, etc.)
-   Follows SOLID principles
-   Clean separation of concerns
-   Testable and maintainable

## Database Schema

### Orders Table

-   `id`: Primary key
-   `amount`: Decimal (10,2)
-   `currency`: String (EGP, USD, EUR)
-   `customer_email`: String
-   `status`: Enum (pending, completed, failed, cancelled)
-   `timestamps`

### Payments Table

-   `id`: Primary key
-   `order_id`: Foreign key → orders
-   `payment_gateway`: String
-   `transaction_id`: String (nullable, unique)
-   `amount`: Decimal (10,2)
-   `status`: Enum (pending, completed, failed, refunded)
-   `metadata`: JSON
-   `timestamps`

## Paymob Setup

### Getting Your Credentials

1. Sign up at [Paymob Accept](https://accept.paymob.com/)
2. Navigate to **Settings → Account Info → API Keys** to get your API Key
3. Navigate to **Settings → Payment Integrations** to get Integration ID and Iframe ID
4. Get HMAC Secret from **Settings → Account Info**

### Callback URLs

For production, update the callback URLs in Paymob Dashboard:

-   **Transaction Processed Callback**: `https://yourdomain.com/api/payments/callback`
-   **Transaction Response Callback**: `https://yourdomain.com/api/payments/response`

## Project Structure

```
app/
├── Contracts/
│   └── PaymentGatewayInterface.php    # Payment gateway contract
├── Http/
│   ├── Controllers/Api/
│   │   ├── OrderController.php        # Order creation endpoint
│   │   └── PaymentController.php      # Payment initiation endpoint
│   └── Requests/Api/
│       ├── CreateOrderRequest.php     # Order validation
│       └── InitiatePaymentRequest.php # Payment validation
├── Models/
│   ├── Order.php                      # Order model
│   └── Payment.php                    # Payment model
└── Services/Api/
    ├── OrderService.php               # Order business logic
    ├── PaymentService.php             # Payment orchestration
    └── PaymobPaymentService.php       # Paymob implementation
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
