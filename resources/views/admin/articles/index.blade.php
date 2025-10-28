@extends('adminlte::page')

@section('title', 'Articles')

@section('content_header')
    <h1>Articles</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Articles list</h3>
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary float-right">+ Add</a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary float-right mr-2">Categories</a>
                </div>
                <div class="card-body">
                    <table id="articles-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="width: 40px">ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th style="width: 90px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>
                                    {{ $article->admin_name }}
                                </td>
                                <td>
                                    @foreach ($article->categories as $category)
                                        <div class="badge badge-info">{{ $category->admin_name ?? '-' }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @if($article->img)
                                        @php($imgSrc = \Illuminate\Support\Str::startsWith($article->img, ['http://', 'https://', '/storage/']) ? $article->img : asset('img/articles/' . $article->img))
                                        <img src="{{ $imgSrc }}"
                                             alt=""
                                             class="mr-1 rounded"
                                             width="36" height="36">
                                    @endif
                                </td>
                                <td>
                                    @if ($article->status === 'published')
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-warning text-dark">Draft</span>
                                    @endif
                                </td>
                                <td data-order="{{ strtotime($article->created_at) }}">
                                    {{ \Carbon\Carbon::parse($article->created_at)->format('Y-m-d H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.articles.edit', $article) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal{{ $article->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="deleteModal{{ $article->id }}" tabindex="-1"
                                         role="dialog" aria-labelledby="deleteModalLabel{{ $article->id }}"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $article->id }}">
                                                        Confirm Deletion</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this article?
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('admin.articles.destroy', $article->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $('#articles-table').DataTable({
                'order': [[0, 'desc']],
                'columnDefs': [
                    { 'orderable': false, 'targets': 6 }
                ]
            });
        });
    </script>
@endsection
