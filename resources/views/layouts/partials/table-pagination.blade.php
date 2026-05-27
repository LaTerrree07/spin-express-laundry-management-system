@props(['paginator'])

@if ($paginator->hasPages())
    <div class="border-t border-[#D6DEE8] px-4 py-4">
        {{ $paginator->links() }}
    </div>
@endif