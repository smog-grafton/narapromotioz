<!-- Event Results Section -->
<section id="results" class="event-results-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">RESULTS</h2>
            <p class="section-subtitle">Fight Outcomes for {{ $event->name }}</p>
        </div>
        
        @if($isPastEvent && $fights->isNotEmpty())
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Bout</th>
                            <th>Fighter 1</th>
                            <th>Fighter 2</th>
                            <th>Result</th>
                            <th>Method</th>
                            <th>Round</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fights as $fight)
                            <tr>
                                <td class="bout-type">
                                    @if($fight->title_fight)
                                        {{ $fight->title_fight }}
                                    @elseif($fight->is_main_event)
                                        Main Event
                                    @else
                                        {{ $fight->weight_class }}
                                    @endif
                                </td>
                                <td class="fighter-name {{ $fight->result === 'win' ? 'winner' : '' }}">
                                    {{ $fight->boxer->name }}
                                </td>
                                <td class="fighter-name {{ $fight->result === 'loss' ? 'winner' : '' }}">
                                    {{ $fight->opponent->name }}
                                </td>
                                <td>
                                    @if($fight->result === 'win')
                                        <span class="result-badge win">Win</span>
                                    @elseif($fight->result === 'loss')
                                        <span class="result-badge loss">Loss</span>
                                    @elseif($fight->result === 'draw')
                                        <span class="result-badge draw">Draw</span>
                                    @elseif($fight->result === 'no_contest')
                                        <span class="result-badge no-contest">No Contest</span>
                                    @else
                                        <span class="result-badge">TBD</span>
                                    @endif
                                </td>
                                <td>{{ $fight->method ?: 'N/A' }}</td>
                                <td>{{ $fight->rounds ?: 'N/A' }}</td>
                                <td>{{ $fight->round_time ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($event->meta_data && isset($event->meta_data['result_notes']))
                <div class="result-notes mt-4">
                    <h4>Notes</h4>
                    <p>{{ $event->meta_data['result_notes'] }}</p>
                </div>
            @endif
        @elseif($isPastEvent)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="empty-title">Results Coming Soon</h3>
                <p class="empty-description">The results for this event have not been posted yet. Check back later for updates.</p>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="empty-title">Event Not Yet Completed</h3>
                <p class="empty-description">This event has not taken place yet. Results will be available after the event concludes.</p>
            </div>
        @endif
    </div>
</section> 