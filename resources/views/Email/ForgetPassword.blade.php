<x-mail::message>
    <x-mail::panel>
        {{ $user->username }} مرحبا<br>
        اهل بك في برنامج الحجوزات<br>
        الكود :{{ $code }}<br>
    </x-mail::panel>
</x-mail::message>
