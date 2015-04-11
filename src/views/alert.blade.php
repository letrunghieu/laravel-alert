<div class="alert alert-{{ $type }} alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4>
        {!! $icon !!}
        {!! trans("alert::alert.title.{$type}") !!}
    </h4>
    @foreach($messages as $message)
    <p>
        {!! $message !!}
    </p>
    @endforeach
</div>