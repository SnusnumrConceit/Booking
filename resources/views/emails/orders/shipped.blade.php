@component('mail::message')

Здравствуйте, {{ $customer_name }}!

Вами был совершён заказ номера № {{ $order_number }} на {{ $order_days }} дней.
Дата и время заселения: {{ $order_note_date }}

@component('mail::button', ['url' => $url, 'color' => 'primary'])
    Подробнее
@endcomponent

Спасибо, что пользуетесь услугами <br>
{{ config('app.name') }}
@endcomponent
