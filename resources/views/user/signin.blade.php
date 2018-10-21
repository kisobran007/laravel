@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h1>Sign In</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p>{{@error}}</p>
                @endforeach
            </div>
        @endif
        <form action="{{route('postsignin')}}" method="POST">
            <div class="form-group">
              <label for="email"></label>
              <input class="form-control" type="text" id="email" name="email">
              <small id="helpId" class="text-muted">Input your email</small>
            </div>
            <div class="form-group">
                <label for="password"></label>
                <input class="form-control" type="text" id="password" name="password">
                <small id="helpId" class="text-muted">Input your password</small>
              </div>
              <button type="submit" class="btn btn-primary">Sign In</button>
              {{ csrf_field() }}
        </form>
    </div>
</div>
@endsection
