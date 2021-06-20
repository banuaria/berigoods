@extends('admin.layout.app')
@section('content')
<div class="content-wrapper">
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-home"></i>
        </span> Feedback </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <h4 class="card-title">Data Produk</h4>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hovered" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Feedback</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($feedback as $result=>$hasil)
                            <tr>
                                <td>{{$result+$feedback->firstitem()}}</td>
                                <td>{{$hasil->email}}</td>
                                <td>{{$hasil->subject}}</td>
                                <td>{{$hasil->text}}</td>
                                <td>
                                    <form action="{{route('feedback.destroy',$hasil->id)}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            {{$feedback->links()}}
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
