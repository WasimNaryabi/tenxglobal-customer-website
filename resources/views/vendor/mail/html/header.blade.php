@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === '10XGLOBAL')
<img src="{{ asset('images/10x-global-logo.png') }}" class="logo" alt="10XGLOBAL">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
