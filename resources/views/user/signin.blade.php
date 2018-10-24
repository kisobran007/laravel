@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-4 offset-4">
        <h1>Sign In</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p> {{ $error }} </p>
                @endforeach
            </div>
        @endif
        <form action="{{route('postsignin')}}" method="POST">
            <div class="form-group">
              <label for="email"></label>
              <input class="form-control" type="text" id="email" name="email" value="{{old('email')}}" >
              <small id="helpId" class="text-muted">Input your email</small>
            </div>
            <div class="form-group">
                <label for="password"></label>
                <input class="form-control" type="password" id="password" name="password">
                <small id="helpId" class="text-muted">Input your password</small>
            </div>

            {!! NoCaptcha::display() !!}

            <button type="submit" class="btn btn-primary">Sign In</button>
            {{ csrf_field() }}
        </form>
        <p>Dont have an accout <a href="{{ route('getsignup') }}">Sign Up</a></p>
    </div>
</div>
@endsection
