<!-- Fight Card Section -->
<section id="fight-card" class="fight-card-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">FIGHT CARD</h2>
            <p class="section-subtitle">Scheduled Bouts for {{ $event->name }}</p>
        </div>
        
        @if($fights->isNotEmpty())
            <!-- Main Event (if there is one specifically marked) -->
            @php
                $mainEvent = $fights->where('is_main_event', true)->first();
                $undercard = $fights->where('is_main_event', false);
            @endphp
            
            @if($mainEvent)
                <div class="main-event-card">
                    <div class="main-event-content">
                        <div class="event-title-info">
                            @if($mainEvent->title_fight)
                                <div class="championship-title">{{ $mainEvent->title_fight }}</div>
                            @endif
                            <div class="weight-class">{{ $mainEvent->weight_class }} Division</div>
                        </div>
                        
                        <div class="fighters-container">
                            <!-- Fighter 1 -->
                            <div class="fighter">
                                <div class="fighter-image">
                                    <img src="{{ $mainEvent->boxer->image_path ? asset($mainEvent->boxer->image_path) : asset('assets/images/boxers/default.jpg') }}" alt="{{ $mainEvent->boxer->name }}">
                                </div>
                                <div class="fighter-info">
                                    <h3 class="fighter-name">{{ $mainEvent->boxer->name }}</h3>
                                    <div class="fighter-record">{{ $mainEvent->boxer->wins }}-{{ $mainEvent->boxer->losses }}-{{ $mainEvent->boxer->draws }}</div>
                                    <div class="fighter-country">
                                        <img src="{{ asset('assets/images/flags/' . strtolower($mainEvent->boxer->country) . '.png') }}" alt="{{ $mainEvent->boxer->country }}" class="country-flag">
                                        <span class="country-name">{{ $mainEvent->boxer->country }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- VS Badge -->
                            <div class="vs-badge">VS</div>
                            
                            <!-- Fighter 2 -->
                            <div class="fighter">
                                <div class="fighter-image">
                                    <img src="{{ $mainEvent->opponent->image_path ? asset($mainEvent->opponent->image_path) : asset('assets/images/boxers/default.jpg') }}" alt="{{ $mainEvent->opponent->name }}">
                                </div>
                                <div class="fighter-info">
                                    <h3 class="fighter-name">{{ $mainEvent->opponent->name }}</h3>
                                    <div class="fighter-record">{{ $mainEvent->opponent->wins }}-{{ $mainEvent->opponent->losses }}-{{ $mainEvent->opponent->draws }}</div>
                                    <div class="fighter-country">
                                        <img src="{{ asset('assets/images/flags/' . strtolower($mainEvent->opponent->country) . '.png') }}" alt="{{ $mainEvent->opponent->country }}" class="country-flag">
                                        <span class="country-name">{{ $mainEvent->opponent->country }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="fight-details">
                            <div class="detail">
                                <div class="detail-label">Rounds</div>
                                <div class="detail-value">{{ $mainEvent->rounds ?? 12 }}</div>
                            </div>
                            
                            <div class="detail">
                                <div class="detail-label">Weight Class</div>
                                <div class="detail-value">{{ $mainEvent->weight_class }}</div>
                            </div>
                            
                            @if($mainEvent->referee)
                                <div class="detail">
                                    <div class="detail-label">Referee</div>
                                    <div class="detail-value">{{ $mainEvent->referee }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Undercard Bouts -->
            @if($undercard->isNotEmpty())
                <div class="undercard-bouts">
                    <h3 class="text-center mb-4">Undercard</h3>
                    
                    @foreach($undercard as $bout)
                        <div class="bout-card">
                            <div class="bout-header">
                                <div class="bout-type">
                                    @if($bout->title_fight)
                                        {{ $bout->title_fight }}
                                    @else
                                        {{ $bout->is_main_event ? 'Main Event' : 'Undercard Bout' }}
                                    @endif
                                </div>
                                <div class="weight-class">{{ $bout->weight_class }}</div>
                            </div>
                            
                            <div class="bout-content">
                                <!-- Fighter 1 -->
                                <div class="fighter">
                                    <div class="fighter-image">
                                        <img src="{{ $bout->boxer->image_path ? asset($bout->boxer->image_path) : asset('assets/images/boxers/default.jpg') }}" alt="{{ $bout->boxer->name }}">
                                    </div>
                                    <div class="fighter-info">
                                        <div class="fighter-name">{{ $bout->boxer->name }}</div>
                                        <div class="fighter-record">{{ $bout->boxer->wins }}-{{ $bout->boxer->losses }}-{{ $bout->boxer->draws }}</div>
                                    </div>
                                </div>
                                
                                <!-- VS Badge -->
                                <div class="vs-badge">VS</div>
                                
                                <!-- Fighter 2 -->
                                <div class="fighter fighter-right">
                                    <div class="fighter-image">
                                        <img src="{{ $bout->opponent->image_path ? asset($bout->opponent->image_path) : asset('assets/images/boxers/default.jpg') }}" alt="{{ $bout->opponent->name }}">
                                    </div>
                                    <div class="fighter-info">
                                        <div class="fighter-name">{{ $bout->opponent->name }}</div>
                                        <div class="fighter-record">{{ $bout->opponent->wins }}-{{ $bout->opponent->losses }}-{{ $bout->opponent->draws }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bout-footer">
                                <div class="bout-detail">
                                    <span>{{ $bout->rounds ?? 12 }}</span> Rounds
                                </div>
                                
                                @if($bout->referee)
                                    <div class="bout-detail">
                                        Referee: <span>{{ $bout->referee }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-fist-raised"></i>
                    </div>
                    <h3 class="empty-title">Fight Card Coming Soon</h3>
                    <p class="empty-description">The fight card for this event has not been announced yet. Check back later for updates.</p>
                </div>
            </div>
        @endif
    </div>
</section> 