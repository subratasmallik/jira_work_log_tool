@extends('layouts.appLogin')
@section('title')
Login
@endsection
@section('content')
<div class="loginDiv center-center w350 pa-3">
    @include('layouts/flash-message') 
    <p  class="logo text-center pa-1"><img src="{{asset('../resources/images/logo.png')}}" alt=""/></p>
    <form method="POST" action="{{url('/login')}}">
        @csrf
        <div class="form-group">
            <label>Jira web server url</label>
            <input type="text" class="form-control" name="jiraUrl" placeholder="" value="<?= (Cookie::get('jiraUrl')) ? Cookie::get('jiraUrl') : '' ?>">
            <small class="form-text text-muted">https://jira.yoursite.net</small>
        </div>
        <div class="form-group">
            <label>Jira Username</label>
            <input type="text" class="form-control" name="username" placeholder="" value="<?= (Cookie::get('username')) ? Cookie::get('username') : '' ?>">
        </div>
        <div class="form-group">
            <label>Jira Password</label>
            <input type="password" class="form-control" name="password" placeholder="" value="<?= (Cookie::get('password')) ? Cookie::get('password') : '' ?>">
            <small class="form-text text-muted">We'll never save your password.</small>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary font-weight-500 submitBtn">Login</button>
        </div>
        <div class="mt-4 fs-13 font-weight-500"><input type="checkbox" name="remember" checked value="1"> Remember this login info for next time login?</div>
    </form>
</div>
@endsection 
@section('script')
@endsection 

