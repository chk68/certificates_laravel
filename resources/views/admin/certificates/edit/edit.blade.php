<!-- edit.blade.php -->
@extends('admin.layouts.main')

@section('blog')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Certificate</h3>
                            </div>
                            <form role="form" action="{{ route('admin.certificates.update', $certificate->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="active">Activate</label>
                                        <select class="form-control" id="active" name="active">
                                            <option value="1" {{ $certificate->active ? 'selected' : '' }}>Not activated</option>
                                            <option value="0" {{ !$certificate->active ? 'selected' : '' }}>Used</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $certificate->name }}" maxlength="40" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ $certificate->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="cost">Cost</label>
                                        <input type="number" class="form-control" id="cost" name="cost" value="{{ $certificate->cost }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="service">Service</label>
                                        <input type="text" class="form-control" id="service" name="service" value="{{ $certificate->service }}" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
