<form method="POST" action="{{ route('teacher.sendProfileRequest') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" name="teacher_name" class="form-control"
               value="{{ old('teacher_name', auth()->user()->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Contact Number</label>
        <input type="text" name="contact" class="form-control"
               placeholder="03XXXXXXXXX (11 digits)"
               value="{{ old('contact') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Department</label>
        <select name="department_id" class="form-select" required>
            <option value="">-- Select Department --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Semester</label>
        <select name="semester" class="form-select" required>
            <option value="">-- Select Semester --</option>
            @foreach($semesters as $sem)
                <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>
                    {{ $sem }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Session</label>
        <input type="text" name="session" class="form-control"
               placeholder="e.g. 2022-2026"
               value="{{ old('session') }}" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-2">
        <i class="fas fa-paper-plane me-2"></i> Send Request to Admin
    </button>

</form>