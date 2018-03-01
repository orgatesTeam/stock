@if($errors->any())
    @foreach($errors->messages() as $message)
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{$message[0]}}</strong>
        </div>
    @endforeach
@endif