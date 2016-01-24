<div class="breadcrumbs">
    @foreach($breadcrumbList as $crumb)
        <a class="breadcrumb" href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
    @endforeach
</div>