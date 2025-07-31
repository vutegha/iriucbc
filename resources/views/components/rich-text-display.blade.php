{{-- Composant pour afficher le contenu riche CKEditor --}}
@props([
    'content' => '',
    'class' => '',
    'title' => null
])

<div {{ $attributes->merge(['class' => 'rich-text-content ' . $class]) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="h-5 w-5 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ $title }}
        </h3>
    @endif
    
    <div class="prose prose-lg max-w-none prose-slate prose-img:rounded-lg prose-img:shadow-md prose-headings:text-gray-800 prose-a:text-iri-primary prose-a:no-underline hover:prose-a:underline prose-strong:text-gray-800 prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-blockquote:border-l-4 prose-blockquote:border-iri-primary prose-blockquote:bg-gray-50 prose-blockquote:pl-4">
        {!! $content !!}
    </div>
</div>

<style>
/* Styles spécifiques pour le contenu CKEditor */
.rich-text-content {
    /* Tables CKEditor */
    --tw-prose-tables: theme(colors.gray.700);
    --tw-prose-table-heading: theme(colors.gray.900);
    --tw-prose-table-body: theme(colors.gray.700);
    --tw-prose-table-borders: theme(colors.gray.300);
}

.rich-text-content table {
    @apply w-full border-collapse border border-gray-300 mb-4;
}

.rich-text-content table th {
    @apply bg-gray-100 border border-gray-300 px-4 py-2 text-left font-semibold text-gray-800;
}

.rich-text-content table td {
    @apply border border-gray-300 px-4 py-2 text-gray-700;
}

.rich-text-content table tr:nth-child(even) {
    @apply bg-gray-50;
}

/* Listes CKEditor */
.rich-text-content ul {
    @apply list-disc pl-6 mb-4 space-y-1;
}

.rich-text-content ol {
    @apply list-decimal pl-6 mb-4 space-y-1;
}

.rich-text-content li {
    @apply text-gray-700 leading-relaxed;
}

/* Images CKEditor */
.rich-text-content img {
    @apply max-w-full h-auto mx-auto rounded-lg shadow-md mb-4;
}

.rich-text-content figure {
    @apply mb-6 text-center;
}

.rich-text-content figcaption {
    @apply text-sm text-gray-600 mt-2 italic;
}

/* Liens CKEditor */
.rich-text-content a {
    @apply text-iri-primary hover:text-iri-secondary transition-colors duration-200;
}

.rich-text-content a:hover {
    @apply underline;
}

/* Code blocks CKEditor */
.rich-text-content pre {
    @apply bg-gray-100 border rounded-lg p-4 overflow-x-auto mb-4;
}

.rich-text-content code {
    @apply text-sm font-mono;
}

/* Blocs de citation */
.rich-text-content blockquote {
    @apply border-l-4 border-iri-primary bg-gray-50 pl-4 pr-4 py-2 mb-4 italic;
}

/* Titres dans le contenu */
.rich-text-content h1 {
    @apply text-2xl font-bold text-gray-800 mb-4 mt-6;
}

.rich-text-content h2 {
    @apply text-xl font-bold text-gray-800 mb-3 mt-5;
}

.rich-text-content h3 {
    @apply text-lg font-semibold text-gray-800 mb-2 mt-4;
}

.rich-text-content h4 {
    @apply text-base font-semibold text-gray-800 mb-2 mt-3;
}

/* Paragraphes */
.rich-text-content p {
    @apply text-gray-700 leading-relaxed mb-4;
}

/* Styles pour les éléments spéciaux CKEditor */
.rich-text-content .text-big {
    @apply text-lg;
}

.rich-text-content .text-small {
    @apply text-sm;
}

.rich-text-content .text-tiny {
    @apply text-xs;
}

.rich-text-content .text-huge {
    @apply text-xl;
}

/* Alignements */
.rich-text-content .ck-align-left {
    @apply text-left;
}

.rich-text-content .ck-align-center {
    @apply text-center;
}

.rich-text-content .ck-align-right {
    @apply text-right;
}

.rich-text-content .ck-align-justify {
    @apply text-justify;
}

/* Styles pour les mentions et hashtags si utilisés */
.rich-text-content .mention {
    @apply bg-blue-100 text-blue-800 px-2 py-1 rounded;
}

/* Responsive pour mobile */
@media (max-width: 640px) {
    .rich-text-content {
        @apply text-sm;
    }
    
    .rich-text-content table {
        @apply text-xs;
    }
    
    .rich-text-content table th,
    .rich-text-content table td {
        @apply px-2 py-1;
    }
}
</style>
