<!-- class='form-inline pull-left'でFormを横並びにできる -->
@if (Auth::user()->is_favorite($micropost->id))
    {!! Form::open(['route' => ['user.unfavorite', $micropost->id], 'method' => 'delete', 'class' => 'form-inline pull-left']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-success btn-xs"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['user.favorite', $micropost->id], 'class' => 'form-inline pull-left']) !!}
        {!! Form::submit('Favorite', ['class' => "btn btn-default btn-xs"]) !!}
    {!! Form::close() !!}
@endif
