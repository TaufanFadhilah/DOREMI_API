@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #cb202d; color: white">Your Balance: Rp. {{ Auth::user()->balance }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (!Auth::user()->password)
                      <div class="alert alert-danger" role="alert">
                        <b>Your password is unset, please type your password.</b>
                        <form action="{{route('setPassword')}}" method="POST">
                          {{ csrf_field() }}
                          <fieldset class="form-group">
                            <label for="formGroupExampleInput">Password</label>
                            <input type="password" name="password" class="form-control" id="formGroupExampleInput" placeholder="Type your password here">
                          </fieldset>
                          <fieldset class="form-group">
                            <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
                          </fieldset>
                        </form>
                      </div>
                      @else
                      <div id="app">
                        <restaurant-component />
                      </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
