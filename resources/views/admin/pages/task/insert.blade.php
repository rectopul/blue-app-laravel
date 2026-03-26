@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data) ? 'Editar' : 'Nova' }} Tarefa de Video</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.task.insert') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label>Titulo da tarefa</label>
                                        <input type="text" name="title" class="form-control" value="{{ $data->title ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Embed URL do video</label>
                                        <input type="text" name="video_url" class="form-control" value="{{ $data->video_url ?? '' }}" required>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Descricao</label>
                                        <textarea name="description" class="form-control" rows="3">{{ $data->description ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Tempo minimo (segundos)</label>
                                        <input type="number" name="watch_seconds" class="form-control" min="5" value="{{ $data->watch_seconds ?? 30 }}" required>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Ordem</label>
                                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ $data->sort_order ?? 0 }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>Icone Material</label>
                                        <input type="text" name="icon" class="form-control" value="{{ $data->icon ?? 'play_circle' }}" placeholder="play_circle">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Status</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ (isset($data) && $data->is_active) ? 'selected' : '' }}>Ativo</option>
                                            <option value="0" {{ (isset($data) && !$data->is_active) ? 'selected' : '' }}>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                        <a href="{{ route('admin.task.index') }}" class="btn btn-light">Voltar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
