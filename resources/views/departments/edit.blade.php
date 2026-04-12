@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card border-light shadow">
        <div class="card-header"><h4>Edit Department</h4></div>
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('departments.update', $department->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label text-light">Department Name</label>
                    <input type="text" name="name" value="{{ $department->name }}" class="form-control bg-transparent text-white border-light" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-pen me-2"></i> Update Department
                </button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection