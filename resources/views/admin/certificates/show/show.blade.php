<!-- show.blade.php -->
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
                                    <h3 class="card-title" style="font-size: 24px; font-weight: bold;">{{ $certificate->name }}</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('admin.certificates.edit.edit', $certificate->id) }}" class="btn btn-primary">Edit</a>
                                        <a href="{{ route('admin.certificates.createFromTemplate', $certificate->id) }}" class="btn btn-success">Certificate</a>
                                        <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="post" style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p style="font-size: 18px; font-weight: bold;">{{ $certificate->description }}</p>
                                <div style="margin-bottom: 10px;"></div>
                                <p style="margin-bottom: 10px;">Service: {{ $certificate->service }}</p>
                                <p style="margin-bottom: 10px;">Cost: {{ $certificate->cost }}</p>
                                <p style="margin-bottom: 10px;">Status: <span class="{{ $certificate->active ? 'text-danger' : 'text-success' }}">
                                        {{ $certificate->active ? 'Not Activated' : 'Used' }}
                                    </span>
                                </p>
                                <p style="margin-bottom: 10px; color: #888; font-size: 14px; float: right;">
                                    Created At: {{ $certificate->created_at->format('F j, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
