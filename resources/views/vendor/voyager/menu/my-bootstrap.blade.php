@php
if(!isset($innerLoop)){
$menus = menu('admin', '_json');
}
if (Voyager::translatable($items)) {
$items = $items->load('translations');
$menus = $menus->load('translations');
}
@endphp
<ul class="nav navbar-nav">
    @foreach ($menus as $item)
    @php
    $originalItem = $item;
    if (Voyager::translatable($item)) {
    $item = $item->translate($options->locale);
    }
    $listItemClass = null;
    $linkAttributes = null;
    $styles = null;
    $icon = null;
    $caret = null;
    $dropdown = null;
    $open = null;
    // Background Color or Color
    if (isset($item->color) && $item->color == true) {
    $styles = 'color:'.$item->color;
    }
    if (isset($item->background) && $item->background == true) {
    $styles = 'background-color:'.$item->color;
    }
    $segments_sub = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
    $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', $item->link())));
    if(url($item->link()) == url()->current()){
    $listItemClass = 'active';
    }else{
    $listItemClass = '';
    }
    if(sizeof($segments_sub) > 0){
    foreach ($segments as $key => $segment) {
    if($segment == $segments_sub[1]){
    $listItemClass = 'active';
    }
    }
    }
    if (!$item->children->isEmpty()) {
    $dropdown = 'dropdown';
    }
    // Set Icon
    if(isset($item->icon_class) && $item->icon_class == true){
    $icons = explode("-", $item->icon_class);
    switch ($icons[0]) {
    case 'voyager':
    $icon = '<span class="icon ' . $item->icon_class . '"></span>';
    break;
    default:
    $icon = '<img class="svg" src="' . asset($item->icon_class) . '">';
    break;
    }
    }
    @endphp
    <li class="{{ $listItemClass }} {{ $dropdown }}">
        <a target="{{ $item->target }}"
            href="{{ !$item->children->isEmpty() ? '#'.$item->id.'-dropdown-element' : url($item->link()) }}" {!!
            $linkAttributes ?? '' !!} {!! !$originalItem->children->isEmpty() ? 'data-toggle="collapse"
            aria-expanded="false" class="collapsed"' : '' !!}>
            {!! $icon !!}
            <span class="title">{{ $item->title }}</span>
        </a>
        @if(!$item->children->isEmpty())
        <div id="{{ $item->id.'-dropdown-element' }}" class="collapse">
            <div class="panel-body">
                @include('voyager::menu.my-bootstrap', ['menus' => $originalItem->children, 'options' => $options,
                'innerLoop' => true ])
            </div>
        </div>
        @endif
    </li>
    @endforeach
</ul>
