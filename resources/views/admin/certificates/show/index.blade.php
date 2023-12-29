<!-- index.blade.php -->
@extends('admin.layouts.main')

@section('blog')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">All Certificates</h3>
                                    <a href="{{ route('admin.certificates.create') }}" class="btn btn-success">+</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($certificates as $certificate)
                                    <div class="certificate-item" style="background-color: #f5f5f5; margin-bottom: 20px; padding: 15px;">
                                        <div class="certificate-content">
                                            <a href="{{ route('admin.certificates.show.show', $certificate->id) }}">
                                                <h4>{{ $certificate->name }}</h4>
                                                <p>Description: {{ $certificate->description }}</p>
                                                <p>Service: {{ $certificate->service }}</p>
                                                <p>Cost: {{ $certificate->cost }}</p>
                                                <p class="{{ $certificate->active ? 'text-danger' : 'text-success' }}">
                                                    {{ $certificate->active ? 'Not Activated' : 'Used' }}
                                                </p>
                                                <a href="{{ route('admin.certificates.edit.edit', $certificate->id) }}" class="btn btn-primary">Edit</a>
                                                <a href="{{ route('admin.certificates.createFromTemplate', $certificate->id) }}" class="btn btn-success">Certificate</a>
                                                <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="post" style="display: inline;">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
