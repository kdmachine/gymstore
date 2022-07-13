@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật newsletter") : "Thêm đăng ký" ) }}</title>
    <meta
        content="{{ hwa_page_title( isset($result) ? ($result['name'] ?? "Cập nhật newsletter") : "Thêm newsletter") }}"
        name="description"/>
@endsection
@section('admin_style')

@endsection

@section('admin_content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><span><i
                                            class="bx bxs-home-circle"></i> Bảng điều khiển</span></a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route("{$path}.index") }}">Đăng ký</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? ($result['full_name'] ?? "Cập nhật") : "Thêm mới" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal"
            action="{{ isset($result) ? route("{$path}.update", $result['id']) : route("{$path}.store") }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($result))
                @method('PUT')
            @endif
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="email">Email: <span class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        name="email" id="email"
                                        placeholder="Nhập tên thương hiệu"
                                        value="{{ old('email', isset($result['email']) ? $result['email'] : '') }}">
                                    @if($errors->has('email'))
                                        <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="active">Trạng thái: <span class="text-danger">*</span></label>
                                    <select name="active" id="active" class="form-control">
                                        <option
                                            value="1" {{ old('active') == '1' ? 'selected' : ((isset($result['active']) && $result['active'] == '1') ? 'selected' : '') }}>
                                            Bật
                                        </option>
                                        <option
                                            value="0" {{ old('active') == '0' ? 'selected' : ((isset($result['active']) && $result['active'] == '0') ? 'selected' : '') }}>
                                            Tắt
                                        </option>
                                    </select>
                                    @if($errors->has('active'))
                                        <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                    @endif
                                </div>
                                <!-- End name -->

                                @include('admin.includes.form_button')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </form>
    </div>
@endsection

@section('admin_script')

@endsection
