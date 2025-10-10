@if($errors->any())

    <div class="error-panel fill-parent">
        <p><b>Errors</b></p>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <br>

@endif