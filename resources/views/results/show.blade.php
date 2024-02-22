@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Result</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                  <img class="img-fluid p-3" src="{{$result->api_image_url}}" />

                  <p><strong>Original prompt: </strong>{{$result->original_prompt}}</p>
                  <p><strong>Revised prompt: </strong>{{$result->revised_prompt}}</p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
