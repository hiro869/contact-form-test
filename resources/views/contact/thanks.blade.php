@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endpush

@section('content')
  <div class="thanks-wrap">
    <p class="thanks-message">お問い合わせありがとうございました</p>
    <a href="{{ route('contact.create') }}" class="home-btn">HOME</a>
  </div>
@endsection
