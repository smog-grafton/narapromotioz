<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Sitemap Statistics Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Sitemap Statistics
                </h3>
                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                        $stats = $this->getSitemapStats();
                    @endphp
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:p-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            Status
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            @if($stats['exists'])
                                <span class="text-green-600">Active</span>
                            @else
                                <span class="text-red-600">Not Generated</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:p-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            Total URLs
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($stats['url_count']) }}
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:p-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            File Size
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            @if($stats['exists'])
                                {{ number_format($stats['size'] / 1024, 1) }} KB
                            @else
                                --
                            @endif
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:p-6 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            Last Updated
                        </dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                            @if($stats['exists'])
                                {{ date('M j, Y g:i A', $stats['last_modified']) }}
                            @else
                                Never
                            @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sitemap Information Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    Sitemap Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Sitemap URL</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                {{ url('/sitemap.xml') }}
                            </a>
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Robots.txt</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <a href="{{ url('/robots.txt') }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                {{ url('/robots.txt') }}
                            </a>
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Included Content Types</h4>
                        <ul class="mt-1 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside space-y-1">
                            <li>Homepage and static pages (About, Contact)</li>
                            <li>Boxing Events (Upcoming and Past)</li>
                            <li>Boxers and Fighter Profiles</li>
                            <li>Boxing Videos</li>
                            <li>News Articles</li>
                            <li>Special Event Pages</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Update Frequency</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            The sitemap should be regenerated regularly to include new content. Consider setting up a scheduled task to automatically update the sitemap daily.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Tips Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                    SEO Best Practices
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Submit your sitemap to Google Search Console and Bing Webmaster Tools
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Update sitemap regularly when new content is added
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Monitor sitemap indexing status in search console
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Ensure all URLs in sitemap return 200 status codes
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page> 