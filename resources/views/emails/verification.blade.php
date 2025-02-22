@extends('emails.master')

@section('content')
    <h1>Verify Your Email</h1>
    <p>Thank you for signing up! Please click the button below to verify your email address:</p>
    <a href="{{ $verificationUrl }}" class="button">Verify Email</a>
@endsection
