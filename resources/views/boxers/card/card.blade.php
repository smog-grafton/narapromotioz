{{-- Boxer Card Partial --}}
<div class="boxer-card-container">
  <div class="global_container_">
    <div class="col">
      <div class="boxer-name-text">
        <span class="name-text">{{ $boxer->name }}</span>
      </div>
      <div class="weight-class-text">
        <span class="weight-text">{{ strtoupper($boxer->weight_class) }}</span>
      </div>
      <div class="titles match-height group">
        @if($boxer->titles && is_array($boxer->titles) && count($boxer->titles) > 0)
          @foreach($boxer->titles as $index => $title)
            @if($index < 4)
              <div class="rectangle-2{{ $index > 0 ? '-copy' . ($index > 1 ? '-' . $index : '') : '' }}-holder">
                <span class="title-text">{{ $title }}</span>
              </div>
            @endif
          @endforeach
        @else
          <div class="rectangle-2-holder">
            <span class="title-text">N/A</span>
          </div>
        @endif
      </div>
    </div>
    <img class="sulaiman-musalo-copy" 
         src="{{ $boxer->image_path ? asset($boxer->getThumbnailAttribute()) : asset('assets/images/boxer-card/sulaiman_musalo_copy.png') }}" 
         alt="{{ $boxer->name }}" 
         width="280" 
         height="386">
    <div class="col-2">
      <div class="statscards match-height group">
        <div class="rectangle-1-holder">
          <span class="stat-number">{{ str_pad($boxer->wins, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="stats-label">Wins</span>
        </div>
        <div class="rectangle-1-copy-holder">
          <span class="stat-number">{{ str_pad($boxer->losses, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="stats-label">Losses</span>
        </div>
        <div class="rectangle-1-copy-2-holder">
          <span class="stat-number">{{ str_pad($boxer->draws, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="stats-label">Draws</span>
        </div>
        <div class="rectangle-1-copy-3-holder">
          <span class="stat-number">{{ str_pad($boxer->knockouts, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="stats-label">KOs+</span>
        </div>
        <div class="rectangle-1-copy-4-holder">
          <span class="stat-number">{{ str_pad($boxer->kos_lost, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="stats-label">KOs-</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Add click functionality to link to boxer detail page --}}
  <a href="{{ route('boxers.show', $boxer->slug) }}" class="boxer-link-overlay"></a>
</div> 