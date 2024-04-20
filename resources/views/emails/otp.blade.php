@component('mail::message')
# OTP Verification

Your One-Time Password (OTP) for verification is: **{{ $otp }}**

This OTP is valid for 5 minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent