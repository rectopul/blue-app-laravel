@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data) ? 'Editar' : 'Nova' }} Tarefa de Vídeo</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('admin.task.insert') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label>Título da Tarefa</label>
                                        <input type="text" name="title" class="form-control" value="{{ $data->title ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Embed URL do Vídeo (ex: https://www.youtube.com/embed/...)</label>
                                        <input type="text" name="video_url" class="form-control" value="{{ $data->video_url ?? '' }}" required>
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
