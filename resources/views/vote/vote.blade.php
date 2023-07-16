@include('layouts.app')

@section('content')
<form action="{{ route('vote.cast') }}" method="POST">
    @csrf
    <input type="hidden" name="level" value="{{ $level }}">
    <div class="form-group">
        <label for="candidate">Choose a candidate:</label>
        <select name="candidate_id" id="candidate" class="form-control">
            @foreach ($candidates as $candidate)
                <option value="{{ $candidate->id }}">{{ $candidate->nom }} {{ $candidate->prenom }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Vote</button>
</form>

    
@endsection