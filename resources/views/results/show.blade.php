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


                  {{-- <img class="img-fluid p-3" src="{{$result->image->path}}" /> --}}

                    @if(isset($result->image))
                   
                      <img src="{{asset($result->image->path)}}"  alt="{{$result->revised_prompt ?? 'result image from open ai'}}" class="card-img-top">
            
              

                   @endif

                  <p><strong>Original prompt: </strong>{{$result->original_prompt}}</p>
                  <p><strong>Revised prompt: </strong>{{$result->revised_prompt}}</p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
