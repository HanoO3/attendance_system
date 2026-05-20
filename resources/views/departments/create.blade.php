@extends('layouts.app')

@section('content')
<div>
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Add New Department</h2>
            <p class="text-muted mb-0">Create a new department for the system</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('departments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-dark">Department Name</label>
                            <input type="text" name="name" 
                                   class="form-control  " 
                                   placeholder="e.g. BS Computer Science" 
                                   value="{{ old('name') }}" 
                                   required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Department
                            </button>
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Placeholder White Color Fix */
    .::placeholder {
        color: #ffffff !important;
        opacity: 1;
    }
    .::-webkit-input-placeholder { color: #ffffff; }
    .::-moz-placeholder { color: #ffffff; }
    .:-ms-input-placeholder { color: #ffffff; }
</style>
@endsection