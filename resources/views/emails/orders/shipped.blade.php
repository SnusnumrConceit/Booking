@component('mail::message')

Здравствуйте, {{ $customer_name }}!

Вами был совершён заказ номера № {{ $order_number }} на {{ $order_days }} дней.

@component('mail::button', ['url' => 'http://booking.ru/#/admin/orders', 'color' => 'primary'])
Подробнее
@endcomponent

Спасибо, что пользуетесь услугами <br>
{{ config('app.name') }}
@endcomponent
