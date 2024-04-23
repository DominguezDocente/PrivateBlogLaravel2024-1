@if ($paginator->hasPages())

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link"><span>Anterior</span></a>
            </li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a></li>
        @endif

        {{-- Páginas --}}
        @foreach ($elements as $element)

            {{-- @if (is_string($element))

                {{ dd($elements) }}
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif --}}

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach


        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Próxima</a></li>
        @else
            <li class="page-item disabled">
                <a class="page-link"><span>Próxima</span></a>
            </li>
        @endif
@endif
