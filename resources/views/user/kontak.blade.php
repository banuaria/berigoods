@extends('user.app')
@section('content')

<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="/">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Contact</strong></div>
    </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(count($errors)>0)
            @foreach ($errors as $error)
                <div class="alert alert-danger" role="alert">
                    {{$error}}
                </div>
            @endforeach
        @endif
        {{-- validate success --}}
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{Session('success')}}
            </div>
        @endif
        <h2 class="h3 mb-3 text-black">Get In Touch</h2>
        </div>
        <div class="col-md-7">

        <form action="{{route('feedback.store')}}" method="POST">
            @csrf
            <div class="p-3 p-lg-5 border">
            <div class="form-group row">
                <div class="col-md-6">
                <label for="name" class="text-black">Name <span class="text-danger"></span></label>
                <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                <label for="subject" class="text-black">Subject </label>
                <input type="text" class="form-control" id="subject" name="subject" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                <label for="text" class="text-black">Message </label>
                <textarea name="text" id="text" cols="30" rows="7" class="form-control" required></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <button type="submit">
                        send message
                      </button>
                </div>
            </div>
            </div>
        </form>
        </div>
        <div class="col-md-5 ml-auto">
        {{-- <div class="p-4 border mb-3">
            <span class="d-block text-primary h6 text-uppercase">New York</span>
            <p class="mb-0">203 Fake St. Mountain View, San Francisco, California, USA</p>
        </div>
        <div class="p-4 border mb-3">
            <span class="d-block text-primary h6 text-uppercase">London</span>
            <p class="mb-0">203 Fake St. Mountain View, San Francisco, California, USA</p>
        </div>
        <div class="p-4 border mb-3">
            <span class="d-block text-primary h6 text-uppercase">Canada</span>
            <p class="mb-0">203 Fake St. Mountain View, San Francisco, California, USA</p>
        </div> --}}

        </div>
    </div>
    </div>
</div>
@endsection
