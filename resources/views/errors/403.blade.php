@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('additional_message')
Back to <a href="{{ url()->previous() }}" class="btn">Home</a>
@stop()
