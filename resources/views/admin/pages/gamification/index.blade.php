@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Configurações de Ovos Escondidos</h4>
                        <a href="{{ route('admin.gamification.create') }}" class="btn btn-primary">Nova Configuração</a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Indicados Necessários</th>
                                            <th>Página (Rota)</th>
                                            <th>Recompensa</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($settings as $s)
                                            <tr>
                                                <td>{{ $s->required_referrals }}</td>
                                                <td>{{ $s->page_name }}</td>
                                                <td>R$ {{ number_format($s->bonus_reward, 2, ',', '.') }}</td>
                                                <td>{{ $s->is_active ? 'Ativo' : 'Inativo' }}</td>
                                                <td>
                                                    <a href="{{ route('admin.gamification.edit', $s->id) }}" class="btn btn-sm btn-info">Editar</a>
                                                    <form action="{{ route('admin.gamification.delete', $s->id) }}" method="POST" style="display:inline">
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
