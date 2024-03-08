@extends('partial.main')

@section('content')


<section id='basic-vertical-layouts'>
    <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit User</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" method="post" action='/system/user_update={{$users->id}}'>
                                @CSRF
                                @method('PATCH')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-vertical">Name</label>
                                                <input type="text" id="first-name-vertical" class="form-control" name="name" value="{{ $users->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="first-name-vertical">E-mail</label>
                                                <input type="email" id="first-name-vertical" class="form-control" name="email" value="{{ $users->email }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="first-name-vertical">Password</label>
                                                <input type="password" id="first-name-vertical" class="form-control" name="password"  required>
                                            </div>
                                            <div class="form-group">
                                            <label for="first-name-vertical" class="text-left text-neutral"><h6>Role</h6></label>
                                            <select class="form-select" id="basicSelect" name="role" value="{{ $users->role }}">
                                                @foreach($roles as $role)
                                            <option value="{{$role->name}}">{{$role->name}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection