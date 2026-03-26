@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($setting) ? 'Editar' : 'Nova' }} Configuração de Bônus</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ isset($setting) ? route('admin.gamification.update', $setting->id) : route('admin.gamification.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label>Indicados Necessários (Nível 1)</label>
                                        <input type="number" name="required_referrals" class="form-control" value="{{ $setting->required_referrals ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Página (Nome da Rota)</label>
                                        <input type="text" name="page_name" class="form-control" value="{{ $setting->page_name ?? '' }}" placeholder="ex: dashboard" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Valor da Recompensa (R$)</label>
                                        <input type="number" step="0.01" name="bonus_reward" class="form-control" value="{{ $setting->bonus_reward ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Status</label>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ (isset($setting) && $setting->is_active) ? 'selected' : '' }}>Ativo</option>
                                            <option value="0" {{ (isset($setting) && !$setting->is_active) ? 'selected' : '' }}>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                        <a href="{{ route('admin.gamification.index') }}" class="btn btn-light">Voltar</a>
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
