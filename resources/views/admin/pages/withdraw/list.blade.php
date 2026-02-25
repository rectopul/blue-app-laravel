@extends('admin.partials.master')
@section('admin_content')
    <style>
        /* Estilos CSS (manter os mesmos da view de payments) */
        /* Isso garante que a UI seja idêntica e consistente */
        .payment-management {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 1.5rem;
            border-radius: 12px 12px 0 0;
            margin: -1.25rem -1.25rem 0 -1.25rem;
        }


        .payment-management {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 1.5rem;
            border-radius: 12px 12px 0 0;
            margin: -1.25rem -1.25rem 0 -1.25rem;
        }

        .payment-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .payment-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .filters-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .filter-item label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .filter-item select:focus,
        .filter-item input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .payments-container {
            display: grid;
            gap: 1rem;
        }

        .payment-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .payment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg,
                    #10b981 0%,
                    #f59e0b 50%,
                    #ef4444 100%);
        }

        .payment-card.status-approved::before {
            background: #10b981;
        }

        .payment-card.status-pending::before {
            background: #f59e0b;
        }

        .payment-card.status-rejected::before {
            background: #ef4444;
        }

        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .payment-id {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }

        .payment-amount {
            font-weight: 700;
            font-size: 1.25rem;
            color: #059669;
        }

        .payment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            font-weight: 500;
            color: #1f2937;
            word-break: break-all;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
            border: 2px solid transparent;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }

        .status-badge.approved {
            background: #d1fae5;
            color: #065f46;
            border-color: #10b981;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
            border-color: #ef4444;
        }

        .status-icon {
            width: 16px;
            height: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s;
        }

        .action-btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-approve:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-reject {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-reject:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            color: white;
        }

        .btn-view {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .loading-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .payment-header {
                padding: 1.5rem 1rem;
            }

            .payment-stats {
                gap: 1rem;
            }

            .stat-item {
                flex: 1;
                min-width: 120px;
                justify-content: center;
            }

            .filters-container {
                padding: 1rem;
            }

            .filter-group {
                grid-template-columns: 1fr;
            }

            .payment-card {
                padding: 1rem;
            }

            .payment-details {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .card-header-row {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-btn {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .payment-header h4 {
                font-size: 1.25rem;
            }

            .payment-amount {
                font-size: 1.1rem;
            }

            .action-btn {
                padding: 0.625rem 1rem;
                font-size: 0.8rem;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-card {
            animation: slideIn 0.5s ease-out forwards;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>

    <div class="payment-management">
        <section id="dashboard-ecommerce">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="payment-header">
                            <h4 style="color: white;">{{ $title }} Withdraw Management</h4>
                            <div class="payment-stats">
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">{{ $withdraws->where('status', 'approved')->count() }}
                                        </div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Approved</div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">
                                            {{ $withdraws->whereIn('status', ['pending', 'under_review', 'blocked'])->count() }}
                                        </div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Pending</div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">{{ $withdraws->where('status', 'rejected')->count() }}
                                        </div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Rejected</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-body" style="padding: 2rem;">
                                <form id="mass-approve-form" method="POST"
                                    action="{{ route('withdraw.approve.selected') }}" class="mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" id="approve-selected">Aprovar
                                        Selecionados</button>
                                </form>

                                <div class="payments-container" id="withdraw-list">
                                    @foreach ($withdraws as $row)
                                        @if ($row->user)
                                            <div class="payment-card status-{{ strtolower($row->status) }}">
                                                <div class="card-header-row">
                                                    <div>
                                                        <div class="payment-id">ID: {{ $row->id }}</div>
                                                        <div class="payment-amount">{{ price($row->final_amount) }}</div>
                                                    </div>
                                                    <span class="status-badge {{ strtolower($row->status) }}">
                                                        {{ ucfirst($row->status) }}
                                                    </span>
                                                </div>

                                                <div class="payment-details">
                                                    <div class="detail-item">
                                                        <div class="detail-label">User</div>
                                                        <div class="detail-value user-info">
                                                            <div class="user-avatar">
                                                                {{ strtoupper(substr($row->user->name ?? '', 0, 1)) }}
                                                            </div>
                                                            <span>{{ $row->user->name ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="detail-value">
                                                            Ref ID: {{ $row->user->ref_id ?? '--' }}
                                                        </div>
                                                        <div class="detail-value">
                                                            User ID: {{ $row->user_id }}
                                                        </div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label">Team Stats</div>
                                                        <div class="detail-value">
                                                            Size: {{ $row->user->team_stats['team_size'] ?? '0' }}
                                                        </div>
                                                        <div class="detail-value">
                                                            Total Deposit:
                                                            {{ price($row->user->team_stats['total_deposit'] ?? 0) }}
                                                        </div>
                                                        <div class="detail-value">
                                                            Total Withdraw:
                                                            {{ price($row->user->team_stats['total_withdraw'] ?? 0) }}
                                                        </div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label">Method</div>
                                                        <div class="detail-value">{{ $row->method_name }}</div>
                                                    </div>
                                                    <div class="detail-item">
                                                        <div class="detail-label">Address</div>
                                                        <div class="detail-value">{{ $row->address }}</div>
                                                    </div>
                                                </div>

                                                <div class="action-buttons">
                                                    @if (in_array($row->status, ['pending', 'under_review']))
                                                        <input type="checkbox" name="values[]" form="mass-approve-form"
                                                            value="{{ $row->id }}" class="select-item"
                                                            style="margin-right: auto; transform: scale(1.5);">

                                                        {{-- Botão de Rejeitar --}}
                                                        <form action="{{ route('withdraw.status.change', $row->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="action-btn btn-reject"
                                                                onclick="return confirm('Tem certeza que deseja rejeitar este saque?');">
                                                                Rejeitar
                                                            </button>
                                                        </form>

                                                        {{-- Botão de Aprovar --}}
                                                        <form action="{{ route('withdraw.status.change', $row->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="action-btn btn-approve"
                                                                onclick="return confirm('Tem certeza que deseja aprovar este saque?');">
                                                                Aprovar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="text-info" style="margin-left: auto;">Already pushed an
                                                            action</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.getElementById('select-all').addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.select-item').forEach(el => {
                    el.checked = checked;
                });
            });

            document.getElementById('mass-approve-form').addEventListener('submit', function(e) {
                if (!confirm('Tem certeza que deseja aprovar os saques selecionados?')) {
                    e.preventDefault();
                }
            });
        </script>
    </div>
@endsection
