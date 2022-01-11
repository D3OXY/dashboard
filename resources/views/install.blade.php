@extends('layouts.app')

@section('content')

    <body class="hold-transition dark-mode login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <b class="mr-1">Controlpanel.GG</b>
            </div>

            @if(!isset($step))
                <div class="card-body">
                    <p class="login-box-msg">{{__('This installer will lead you through the most crucial Steps of Controlpanel.gg`s setup')}}</p>
                    <form method="GET" enctype="multipart/form-data" class="mb-3"
                          action="{{ route('install') }}">
                    @csrf
                    @method('POST')
                        <input type="text" name="step" value="2" hidden>
                        <button class="btn btn-primary">{{ __('Lets go') }}</button>
                    </form>
                </div>
            @endif

            @if(isset($step) && $step=="2")
                <div class="card-body">
                    <p class="login-box-msg">{{__('First we will check your Database connection')}}</p>
                    <form method="POST" enctype="multipart/form-data" class="mb-3"
                          action="{{ route('install.checkDB') }}">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="database">{{ __('Database Driver') }}</label>
                                        <input x-model="databasedriver" id="databasedriver" name="databasedriver"
                                               type="text" required
                                               value="mysql" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="databasehost">{{ __('Database Host') }}</label>
                                        <input x-model="databasehost" id="databasehost" name="databasehost" type="text"
                                               required
                                               value="127.0.0.1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="databasehost">{{ __('Database Port') }}</label>
                                        <input x-model="databaseport" id="databaseport" name="databaseport"
                                               type="number" required
                                               value="3306" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="databaseuser">{{ __('Database User') }}</label>
                                        <input x-model="databaseuser" id="databaseuser" name="databaseuser" type="text"
                                               required
                                               value="dashboarduser" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="databaseuserpass">{{ __('Database User Password') }}</label>
                                        <input x-model="databaseuserpass" id="databaseuserpass" name="databaseuserpass"
                                               type="text" required
                                               class="form-control ">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control mb-3">
                                        <label for="database">{{ __('Database') }}</label>
                                        <input x-model="database" id="database" name="database" type="text" required
                                               value="dashboard" class="form-control">
                                    </div>
                                </div>
                                <div>@if(isset($message))
                                        {{$message}}
                                @endif</div>
                                <button class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    @endif

                        @if(isset($step) && $step=="3")
                            <div class="card-body">
                                <p class="login-box-msg">{{__('The Database connection was successful!')}}</p>
                                <p class="login-box-msg">{{__('Tell us something about your Host!')}}</p>
                                <form method="POST" enctype="multipart/form-data" class="mb-3"
                                      action="{{ route('install.checkGeneral') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control mb-3">
                                                    <label for="database">{{ __('The URL to your host') }}</label>
                                                    <input x-model="url" id="url" name="url"
                                                           type="text" required
                                                           value="https://dash.example.com" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control mb-3">
                                                    <label for="name">{{ __('Your Hosts Name') }}</label>
                                                    <input x-model="name" id="name" name="name" type="text"
                                                           required
                                                           value="Controlpanel.gg" class="form-control">
                                                </div>
                                            </div>

                                            <button class="btn btn-primary">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                @endif


                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </body>
@endsection
