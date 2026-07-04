@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h4>My Notifications</h4></div>
        <div class="card-body">
            @foreach($notifications as $n)
                <div class="alert alert-info">
                    <h5>{{ $n->title }}</h5>
                    <p>{{ $n->message }}</p>
                    <small>{{ $n->created_at->diffForHumans() }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection