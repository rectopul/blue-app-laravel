@extends('admin.partials.master')

@section('admin_content')
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{ $data ? 'Editar' : 'Criar Novo' }} Pacote</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('admin.package.insert') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data ? $data->id : '' }}">

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name">Nome do Pacote</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" id="name" class="form-control" name="name" placeholder="Ex: Pacote Diamante" value="{{ old('name', $data ? $data->name : '') }}" required>
                                                    <div class="form-control-position">
                                                        <i class="bx bx-package"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="title">Título Curto</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" id="title" class="form-control" name="title" placeholder="Ex: Investimento Seguro" value="{{ old('title', $data ? $data->title : '') }}" required>
                                                    <div class="form-control-position">
                                                        <i class="bx bx-tag"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="price">Preço (R$)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="number" id="price" class="form-control" name="price" placeholder="0.00" step="0.01" value="{{ old('price', $data ? $data->price : '') }}" required oninput="autoCalculate()">
                                                    <div class="form-control-position">
                                                        <i class="bx bx-dollar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="validity">Validade (Dias)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="number" id="validity" class="form-control" name="validity" placeholder="30" value="{{ old('validity', $data ? $data->validity : '') }}" required oninput="calcReturns()">
                                                    <div class="form-control-position">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="daily_tasks_limit">Limite de Tarefas Diárias</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="number" id="daily_tasks_limit" class="form-control" name="daily_tasks_limit" placeholder="5" value="{{ old('daily_tasks_limit', $data ? $data->daily_tasks_limit : '0') }}" required oninput="autoCalculate()">
                                                    <div class="form-control-position">
                                                        <i class="bx bx-list-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="daily_income_value">Rendimento Diário Total (R$)</label>
                                                <div class="position-relative has-icon-left">
                                                    @php
                                                        $dailyIncome = 0;
                                                        if($data) {
                                                            $dailyIncome = ($data->price * ($data->commission_with_avg_amount / 100)) + ($data->daily_tasks_limit * $data->daily_reward);
                                                        }
                                                    @endphp
                                                    <input type="number" id="daily_income_value" class="form-control border-primary" name="daily_income_value" placeholder="10.00" step="0.01" value="{{ old('daily_income_value', $dailyIncome > 0 ? number_format($dailyIncome, 2, '.', '') : '') }}" required oninput="autoCalculate()">
                                                    <div class="form-control-position">
                                                        <i class="bx bx-money"></i>
                                                    </div>
                                                </div>
                                                <small class="text-primary">Informe o rendimento diário desejado. O sistema calculará os campos abaixo.</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="daily_reward">Recompensa por Tarefa (R$)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="number" id="daily_reward" class="form-control bg-light" name="daily_reward" placeholder="1.00" step="0.01" value="{{ old('daily_reward', $data ? $data->daily_reward : '0.00') }}" required readonly>
                                                    <div class="form-control-position">
                                                        <i class="bx bx-gift"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Calculado: (Rendimento Diário) / (Limite de Tarefas)</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="commission_with_avg_amount">Comissão de Rendimento Diário (%)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="number" id="commission_with_avg_amount" class="form-control bg-light" name="commission_with_avg_amount" placeholder="2.5" step="0.01" value="{{ old('commission_with_avg_amount', $data ? $data->commission_with_avg_amount : '') }}" required readonly>
                                                    <div class="form-control-position">
                                                        <i class="bx bx-trending-up"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Calculado automaticamente</small>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Imagem do Pacote</label>
                                                <div class="custom-file">
                                                    <input type="file" name="photo" class="custom-file-input" id="packagePhoto" onchange="showPreview(event)">
                                                    <label class="custom-file-label" for="packagePhoto">Escolher arquivo</label>
                                                </div>
                                            </div>
                                        </div>

                                        @if($data)
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="status">Status do Pacote</label>
                                                <select name="status" class="form-control" id="status">
                                                    <option value="active" @selected($data->status === 'active')>Ativo</option>
                                                    <option value="inactive" @selected($data->status === 'inactive')>Inativo</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-12 d-flex justify-content-end mt-2">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1">{{ $data ? 'Atualizar Pacote' : 'Criar Pacote' }}</button>
                                            <a href="{{ route('admin.package.index') }}" class="btn btn-light-secondary mr-1 mb-1">Cancelar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="card bg-primary text-white">
                    <div class="card-header">
                        <h4 class="card-title text-white">Resumo do Plano</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ $data && $data->photo ? asset(view_image($data->photo)) : asset(not_found_img()) }}" id="preview" class="rounded-circle img-border box-shadow-1" style="width: 100px; height: 100px; object-fit: cover; background: white;">
                        </div>
                        <ul class="list-group list-group-flush bg-transparent">
                            <li class="list-group-item bg-transparent d-flex justify-content-between border-white/20">
                                <span>Rendimento Diário:</span>
                                <strong id="res_daily_yield">R$ 0,00</strong>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between border-white/20">
                                <span>Ganhos por Tarefas:</span>
                                <strong id="res_tasks_yield">R$ 0,00</strong>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between border-white/20">
                                <span>Total Diário:</span>
                                <strong id="res_total_daily">R$ 0,00</strong>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between border-white/20">
                                <span>Retorno Total (ROI):</span>
                                <strong id="res_total_roi">R$ 0,00</strong>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between border-white/20">
                                <span>Lucro Líquido:</span>
                                <strong id="res_net_profit">R$ 0,00</strong>
                            </li>
                        </ul>
                        <div class="alert bg-white/10 mt-3 mb-0">
                            <small class="d-block text-white/80">Este resumo é uma estimativa baseada nos valores preenchidos ao lado.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showPreview(event) {
            if (event.target.files.length > 0) {
                const src = URL.createObjectURL(event.target.files[0]);
                const preview = document.getElementById("preview");
                preview.src = src;
            }
        }

        function formatBRL(value) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
        }

        function autoCalculate() {
            const dailyIncome = parseFloat(document.getElementById('daily_income_value').value) || 0;
            const tasksLimit = parseInt(document.getElementById('daily_tasks_limit').value) || 0;
            const price = parseFloat(document.getElementById('price').value) || 0;

            // Calculo Recompensa por Tarefa: (rendimento diário) / (Limite de Tarefas Diárias)
            let dailyReward = 0;
            if (tasksLimit > 0) {
                dailyReward = dailyIncome / tasksLimit;
            }
            document.getElementById('daily_reward').value = dailyReward.toFixed(2);

            // Comissão de Rendimento Diário (%):
            // (rendimento diário / preço) * 100
            let dailyPercent = 0;
            if (price > 0) {
                dailyPercent = (dailyIncome / price) * 100;
            }

            // O usuário pediu para calcular a porcentagem, mas se usarmos ambos (tarefa e porcentagem)
            // o sistema vai pagar dobrado. Por padrão, vamos deixar a porcentagem em 0 no backend
            // ou garantir que o usuário entenda.
            // Re-lendo: "calcule a porcentagem e o Recompensa por Tarefa"
            // Se o script usa os dois, vamos zerar a porcentagem para evitar o pagamento duplo passivo.
            document.getElementById('commission_with_avg_amount').value = "0.00";

            calcReturns();
        }

        function calcReturns() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const days = parseInt(document.getElementById('validity').value) || 0;
            const dailyPercent = parseFloat(document.getElementById('commission_with_avg_amount').value) || 0;
            const tasksLimit = parseInt(document.getElementById('daily_tasks_limit').value) || 0;
            const taskReward = parseFloat(document.getElementById('daily_reward').value) || 0;

            const yieldDaily = price * (dailyPercent / 100);
            const tasksDaily = tasksLimit * taskReward;
            const totalDaily = yieldDaily + tasksDaily;
            const totalROI = totalDaily * days;
            const netProfit = totalROI - price;

            document.getElementById('res_daily_yield').innerText = formatBRL(yieldDaily);
            document.getElementById('res_tasks_yield').innerText = formatBRL(tasksDaily);
            document.getElementById('res_total_daily').innerText = formatBRL(totalDaily);
            document.getElementById('res_total_roi').innerText = formatBRL(totalROI);
            document.getElementById('res_net_profit').innerText = formatBRL(netProfit);
        }

        document.addEventListener('DOMContentLoaded', calcReturns);
    </script>
@endsection
