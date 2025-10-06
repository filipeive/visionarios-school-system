{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="current_password" class="form-label">Senha Atual</label>
        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
               id="current_password" name="current_password" required>
        @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nova Senha</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
        <input type="password" class="form-control" 
               id="password_confirmation" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn-school btn-warning-school">
        <i class="fas fa-key"></i>
        Alterar Senha
    </button>
</form>