<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoMeta extends Component
{
    public $title;
    public $description;
    public $keywords;
    public $image;
    public $url;
    public $type;
    public $siteName;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $title = null,
        $description = null,
        $keywords = null,
        $image = null,
        $url = null,
        $type = 'website',
        $siteName = 'Nara Promotionz'
    ) {
        $this->title = $title ?: 'Nara Promotionz - Premier Boxing Promotion & Events';
        $this->description = $description ?: 'Experience the thrill of professional boxing with Nara Promotionz. Watch live events, follow your favorite boxers, and stay updated with the latest boxing news and highlights.';
        $this->keywords = $keywords ?: 'boxing, promotion, events, boxers, fights, live streaming, boxing news, professional boxing, combat sports, entertainment';
        $this->image = $image ?: asset('images/meta/narapromotionz_meta.jpg');
        $this->url = $url ?: url()->current();
        $this->type = $type;
        $this->siteName = $siteName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.seo-meta');
    }
}
