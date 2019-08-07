@if (session()->get('danger',null)!==null)
    <div class="alert alert-danger">
        <span>{!! session()->get('danger') !!}</span>
    </div>
@endif
