@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                    <i class="fas fa-chevron-left mr-1"></i>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-white border border-green-300 leading-5 rounded-lg hover:text-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-100 transition ease-in-out duration-200">
                    <i class="fas fa-chevron-left mr-1"></i>
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-green-700 bg-white border border-green-300 leading-5 rounded-lg hover:text-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-100 transition ease-in-out duration-200">
                    Siguiente
                    <i class="fas fa-chevron-right ml-1"></i>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                    Siguiente
                    <i class="fas fa-chevron-right ml-1"></i>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    @if ($paginator->firstItem())
                        <span class="font-medium text-green-600">{{ $paginator->firstItem() }}</span>
                        de
                        <span class="font-medium text-green-600">{{ $paginator->count() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                </p>
            </div>

            <div class="px-6 py-4">
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-lg border border-gray-200 bg-white">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-50 border-r border-gray-200 cursor-default rounded-l-lg leading-5" aria-hidden="true">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-white border-r border-green-200 rounded-l-lg leading-5 hover:text-green-600 hover:bg-green-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-100 transition ease-in-out duration-200" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border-r border-gray-200 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border-r border-green-500 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-white border-r border-green-200 leading-5 hover:text-green-600 hover:bg-green-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-100 transition ease-in-out duration-200" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-white rounded-r-lg leading-5 hover:text-green-600 hover:bg-green-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-300 active:bg-green-100 transition ease-in-out duration-200" aria-label="{{ __('pagination.next') }}">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-50 cursor-default rounded-r-lg leading-5" aria-hidden="true">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
