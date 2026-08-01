@extends('layouts.app')

@section('title', 'Comunicados')
@section('page-title', 'Comunicados')

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <section class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm">
            <header class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Ultimos Comunicados</h3>
            </header>
            <div class="space-y-3 p-5">
                @forelse($communications as $comm)
                    <article class="rounded-lg border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="text-sm font-semibold text-slate-900">{{ $comm->title }}</h4>
                            <span class="whitespace-nowrap text-xs text-slate-500">{{ $comm->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $comm->message }}</p>
                    </article>
                @empty
                    <p class="rounded-lg bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">Nenhum comunicado encontrado.</p>
                @endforelse

                <div class="pt-2">{{ $communications->links() }}</div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <header class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Fale Conosco</h3>
            </header>
            <div class="p-5">



                <form action="{{ route('parent.send-message') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="subject" class="mb-1 block text-xs font-semibold text-slate-600">Assunto</label>
                        <input type="text" name="subject" id="subject" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" required>
                    </div>
                    <div>
                        <label for="message" class="mb-1 block text-xs font-semibold text-slate-600">Mensagem</label>
                        <textarea name="message" id="message" rows="6" class="w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" required></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">
                        Enviar mensagem
                    </button>
                </form>
            </div>
        </section>
    </div>
@endsection
