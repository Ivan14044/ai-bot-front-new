@extends('adminlte::page')

@section('title', 'Mass notification')

@section('content_header')
    <h1>Mass notification</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notification data</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.notifications.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="target">Target Users</label>
                            <select name="target" id="target" class="form-control @error('target') is-invalid @enderror">
                                <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>All users</option>
                                <option value="active_subscribers"
                                        {{ old('target') == 'active_subscribers' ? 'selected' : '' }}>Users with an active subscription</option>
                                <option value="inactive_subscribers"
                                        {{ old('target') == 'inactive_subscribers' ? 'selected' : '' }}>Users with inactive subscriptions (expired or canceled)</option>
                                <option value="never_subscribed"
                                        {{ old('target') == 'never_subscribed' ? 'selected' : '' }}>Users who never had a subscription</option>
                            </select>
                            @error('target')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group d-none" id="service-group">
                            <label for="service_id">Service</label>
                            <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror">
                                <option value="" {{ old('service_id', '') == '' ? 'selected' : '' }}>All services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->code }}</option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card">
                            <div class="card-header no-border border-0 p-0">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    @foreach(config('langs') as $code => $flag)
                                        @php($hasError = $errors->has('title.' . $code) || $errors->has('message.' . $code))
                                        <li class="nav-item">
                                            <a class="nav-link @if($hasError) text-danger @endif {{ $code == 'en' ? 'active' : null }}"
                                               id="tab_{{ $code }}" data-toggle="pill" href="#tab_message_{{ $code }}" role="tab">
                                                <span class="flag-icon flag-icon-{{ $flag }} mr-1"></span> {{ strtoupper($code) }}  @if($hasError)*@endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    @foreach(config('langs') as $code => $flag)
                                        <div class="tab-pane fade show {{ $code == 'en' ? 'active' : null }}" id="tab_message_{{ $code }}" role="tabpanel">
                                            <div class="form-group">
                                                <label for="title_{{ $code }}">Title</label>
                                                <input type="text" name="title[{{ $code }}]" id="title_{{ $code }}"
                                                       class="form-control @error('title.' . $code) is-invalid @enderror"
                                                       value="{{ old('title.' . $code) }}">
                                                @error('title.' . $code)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="message_{{ $code }}">Message</label>
                                                <textarea style="height: 210px"
                                                          name="message[{{ $code }}]"
                                                          class="ckeditor form-control @error('message.' . $code) is-invalid @enderror"
                                                          id="message_{{ $code }}">{!! old('message.' . $code) !!}</textarea>
                                                @error('message.' . $code)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Send notifications</button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('.ckeditor').forEach(function (textarea) {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setStyle('height', '500px', editor.editing.view.document.getRoot());
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const targetSelect = document.getElementById('target');
            const serviceGroup = document.getElementById('service-group');

            function toggleServiceVisibility() {
                const value = targetSelect.value;
                const shouldShow = ['active_subscribers', 'inactive_subscribers'].includes(value);
                serviceGroup.classList.toggle('d-none', !shouldShow);
            }

            targetSelect.addEventListener('change', toggleServiceVisibility);
            toggleServiceVisibility();
        });
    </script>
@endsection

