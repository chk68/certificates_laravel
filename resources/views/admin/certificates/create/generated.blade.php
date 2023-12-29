@extends('admin.layouts.main')

@section('blog')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <img src="/certificates/{{ $generatedCertificate }}" alt="Generated Certificate" style="max-width: 80%; max-height: 80%; height: auto; margin-top: 10px;">
                        <div class="mt-3">
                            <div class="mt-3">
                                <a href="{{ route('admin.certificates.download') }}" class="btn btn-success">Скачать в JPEG</a>
                                <form action="{{ route('admin.certificates.download.pdf') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Скачать в PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
