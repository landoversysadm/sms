
@if (null !== $errors &&  count($errors) > 0 )
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <strong>Whoops! Something went wrong!</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li class="danger">{{ $error  }}</li>
            @endforeach

        </ul>
    </div>
@endif
