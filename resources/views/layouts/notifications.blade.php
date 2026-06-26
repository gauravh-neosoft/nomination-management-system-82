@php
    $role = 'nominator';
    $email = auth()->user() ? auth()->user()->email : '';
    
    if (str_contains($email, 'unit_spoc') || str_contains($email, 'unitspoc')) {
        $role = 'unit-spoc';
    } elseif (str_contains($email, 'event_ops') || str_contains($email, 'eventops')) {
        $role = 'event-ops';
    } elseif (str_contains($email, 'admin')) {
        $role = 'admin';
    }
@endphp

@include('layouts.notifications.' . $role . '-notification')
