@if (session('status'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">
        <i data-lucide="circle-check" class="mt-0.5 h-4 w-4 shrink-0"></i>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-danger/20 bg-danger/5 px-4 py-3 text-sm text-danger">
        <i data-lucide="circle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif
