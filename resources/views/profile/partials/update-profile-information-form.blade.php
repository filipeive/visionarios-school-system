{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="mb-3">
        <label for="name" class="form-label">Nome Completo</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
               id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
               placeholder="+258 84 XXX XXXX">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn-school btn-primary-school">
        <i class="fas fa-save"></i>
        Salvar Alterações
    </button>
</form>