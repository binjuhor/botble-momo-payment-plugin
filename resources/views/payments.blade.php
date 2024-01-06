<ul>
    @foreach($payments->payments as $payment)
        <li>
            @include('plugins/momo::detail', compact('payment'))
        </li>
    @endforeach
</ul>
