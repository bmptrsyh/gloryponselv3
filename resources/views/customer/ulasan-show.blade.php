<div>
    @forelse ($ulasan as $ulas)
        <div>{{$ulas->rating}}</div>
    @empty
        <div>0</div>
    @endforelse
</div>
