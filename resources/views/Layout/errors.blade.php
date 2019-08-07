@if ($errors->any())
    <section id="fh5co-contact" data-section="contact">
        <p>&nbsp;</p>
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
