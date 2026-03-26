@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tarefas de Vídeo</h4>
                        <a href="{{ route('admin.task.create') }}" class="btn btn-primary">Nova Tarefa</a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Vídeo URL</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tasks as $t)
                                            <tr>
                                                <td>{{ $t->title }}</td>
                                                <td><small>{{ $t->video_url }}</small></td>
                                                <td>{{ $t->is_active ? 'Ativo' : 'Inativo' }}</td>
                                                <td>
                                                    <a href="{{ route('admin.task.create', $t->id) }}" class="btn btn-sm btn-info">Editar</a>
                                                    <form action="{{ route('admin.task.delete', $t->id) }}" method="POST" style="display:inline">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
