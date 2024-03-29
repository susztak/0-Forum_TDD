@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a New Thread</div>

                    <div class="panel-body">

                        <form method="POST" action="/threads">

                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select class="form-control" id="channel_id" name="channel_id" required>
                                    <option>Choose a thread!</option>
                                    @foreach($channels as $channel)
                                        <option {{ old('channel_id') == $channel->id ? 'selected' : '' }} value="{{ $channel->id }}">{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input class="form-control" id="title" type="text" name="title"
                                       value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Title:</label>
                                <textarea class="form-control" name="body" id="body"
                                          rows="8" required>{{ old('body') }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>
                            @if(count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
