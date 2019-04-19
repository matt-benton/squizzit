@component('mail::message')
# Reset Password

A password reset has been requested for this email address.  If you did not request for your password to be reset then ignore this email.

Please click the button below to create a new password for your account.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
