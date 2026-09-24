@props(['messages' => []])

@if ($messages)
    <p class="mt-1.5 flex items-start gap-1.5 text-xs text-danger">
        <i data-lucide="circle-alert" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>
        <span>{{ is_array($messages) ? implode(' ', $messages) : $messages }}</span>
    </p>
@endif
