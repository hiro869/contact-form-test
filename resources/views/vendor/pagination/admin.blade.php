@if ($paginator->hasPages())
<nav class="pager" role="navigation" aria-label="Pagination">
  {{-- 前へ --}}
  @if ($paginator->onFirstPage())
    <span class="page prev" aria-disabled="true">‹</span>
  @else
    <a class="page prev" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
  @endif

  {{-- 数字 --}}
  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="page dots">{{ $element }}</span>
    @endif

    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="page is-active" aria-current="page">{{ $page }}</span>
        @else
          <a class="page" href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  {{-- 次へ --}}
  @if ($paginator->hasMorePages())
    <a class="page next" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
  @else
    <span class="page next" aria-disabled="true">›</span>
  @endif
</nav>
@endif
