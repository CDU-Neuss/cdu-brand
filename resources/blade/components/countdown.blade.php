@props([
    'targetDate',
    'event' => null,
    'labels' => [],
])

@php
$defaultLabels = [
    'days' => 'Days',
    'hours' => 'Hours',
    'minutes' => 'Minutes',
    'seconds' => 'Seconds',
];
$mergedLabels = array_merge($defaultLabels, $labels);
@endphp

<figure class="countdown" aria-hidden="true" x-data="countdown(@js($targetDate))" x-cloak>
    <div class="clock">
        <dl x-show="days > 0">
            <dd x-text="days">&nbsp;</dd>
            <dt>{{ $mergedLabels['days'] }}</dt>
        </dl>
        <div class="divider" x-show="days > 0">:</div>
        <dl x-show="hours > 0">
            <dd x-text="hours">&nbsp;</dd>
            <dt>{{ $mergedLabels['hours'] }}</dt>
        </dl>
        <div class="divider" x-show="hours > 0">:</div>
        <dl>
            <dd x-text="minutes">&nbsp;</dd>
            <dt>{{ $mergedLabels['minutes'] }}</dt>
        </dl>
        <div class="divider">:</div>
        <dl>
            <dd x-text="seconds">&nbsp;</dd>
            <dt>{{ $mergedLabels['seconds'] }}</dt>
        </dl>
    </div>
    @if($event)
        <figcaption>{{ $event }}</figcaption>
    @endif
</figure>
