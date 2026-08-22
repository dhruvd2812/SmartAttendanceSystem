<div class="mb-3">
    <label for="name" class="form-label">Subject name</label>
    <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $subject->name ?? '') }}" required maxlength="255">
</div>
<div class="mb-3">
    <label for="code" class="form-label">Subject code</label>
    <input id="code" name="code" type="text" class="form-control" value="{{ old('code', $subject->code ?? '') }}" required maxlength="50">
</div>
<div class="mb-3">
    <label for="semester" class="form-label">Semester <span class="text-muted">(optional)</span></label>
    <input id="semester" name="semester" type="number" min="1" max="12" class="form-control" value="{{ old('semester', $subject->semester ?? '') }}">
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
    <textarea id="description" name="description" class="form-control" rows="4" maxlength="2000">{{ old('description', $subject->description ?? '') }}</textarea>
</div>
