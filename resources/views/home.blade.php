@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}

                        <form class="row g-3" method="POST" action="{{route('submit')}}" enctype="multipart/form-data">

                            {{-- include csrf field in all of our forms for authentication --}}
                            @csrf

                            <div class="col-sm-12">


                                <label for="description" class="form-label">describe the image</label><br>

                                <textarea
                                        type="textarea"
                                        rows="5"
                                        cols="12"
                                        class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                        name="description"

                                        required
                                >{{ old('description') }}</textarea>


                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                @endif


                            </div>


                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>


                        </form>
                    </div>
                </div>


            <div class="card mt-3">

                <div class="card-header">{{ __('Results') }}</div>
                <div class="card-body">

                    @foreach($results as $result)
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $result->original_prompt }}</span>
                                @if(isset($result->image))
                                    <img src="{{ $result->image->path }}" alt="Image" class="img-thumbnail"
                                         style="width: 100px; height: auto;">
                                @endif
                            </li>
                        </ul>
                    @endforeach

                </div>
            </div>
            </div>
        </div>






    </div>
@endsection
