<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Contact Information</h4>
                <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-medium">Name:</span> {{ $record->name }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-medium">Email:</span> 
                        <a href="mailto:{{ $record->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            {{ $record->email }}
                        </a>
                    </p>
                    @if($record->phone)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Phone:</span> 
                            <a href="tel:{{ $record->phone }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                {{ $record->phone }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Message Details</h4>
                <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-medium">Status:</span> 
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($record->status === 'unread') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($record->status === 'read') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                            {{ ucfirst($record->status) }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-medium">Received:</span> {{ $record->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                    @if($record->read_at)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Read:</span> {{ $record->read_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    @endif
                    @if($record->replied_at)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Replied:</span> {{ $record->replied_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Subject</h4>
        <p class="text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
            {{ $record->subject }}
        </p>
    </div>
    
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Message</h4>
        <div class="text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 p-4 rounded-lg whitespace-pre-wrap">
            {{ $record->message }}
        </div>
    </div>
    
    @if($record->admin_notes)
        <div>
            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Admin Notes</h4>
            <div class="text-sm text-gray-800 dark:text-gray-200 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg whitespace-pre-wrap">
                {{ $record->admin_notes }}
            </div>
        </div>
    @endif
    
    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="mailto:{{ $record->email }}?subject=Re: {{ $record->subject }}" 
           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Reply via Email
        </a>
        
        @if($record->phone)
            <a href="tel:{{ $record->phone }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Call
            </a>
        @endif
    </div>
</div> 