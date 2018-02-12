@foreach($items as $item)
    @php
    $title = explode('/i> ',$item->title,2);
    $title[1] = '<span class="menu-title">'.$title[1].'</span>';
    $title = $title[0].'/i> '.$title[1];
    $item->title = $title;
    @endphp
    @php($render=true)
    @if($item->permission && !Auth::user()->can($item->permission) && !Auth::user()->can('god-mode'))
      @php($render=false)
    @endif
    @if($render)
    <li class='nav-item'>
        @if($item->link) <a  {!! $item->attributes() !!}  @if($item->hasChildren())   @endif href="/{{config("app.admin_url")}}#!{{ $item->url() }}">
            @if($item->hasChildren()) <span class="pull-right text-muted"></span> @endif
            {!! $item->title !!}
        </a>
        @else
            {{$item->title}}
        @endif
        @if($item->hasChildren())
            <ul class="menu-content">
                @foreach($item->children() as $child)
                    @php($render2=true)
                    @if($child->permission && !Auth::user()->can($child->permission) && !Auth::user()->can('god-mode'))
                        @php($render2=false)
                    @endif
                    @if($render2)
                        @if(Auth::user()->provider_id>0 && $child->url() == '/insurance')
                        @php
                        $child->title = 'Profile';
                        $url = '/insurance/detail/'.Auth::user()->provider_id;
                        @endphp
                        @else
                        @php
                        $url = $child->url();
                        @endphp
                        @endif
                        <li><a href="/{{config("app.admin_url")}}#!{{ $url }}">{{ $child->title }}</a></li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
    @endif

@endforeach
