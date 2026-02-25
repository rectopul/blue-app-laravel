@extends('admin.partials.master')
@section('admin_content')
    <style>
        /* Reset e base */
        .payment-management {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Header melhorado */
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

        /* Filtros modernos */
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

        /* Cards de pagamento modernos */
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

        /* Status badges melhorados */
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

        /* Botões de ação modernos */
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

        /* Loading e estados vazios */
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

        /* Responsividade melhorada */
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

        /* Animações */
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

        /* Scroll suave para navegação */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <div class="payment-management">
        <section id="dashboard-ecommerce">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <!-- Header melhorado -->
                        <div class="payment-header">
                            <h4>{{ $title }} Payment Management</h4>
                            <div class="payment-stats">
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">{{ $payments->where('status', 'approved')->count() }}</div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Approved</div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">{{ $payments->where('status', 'pending')->count() }}</div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Pending</div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                    </svg>
                                    <div>
                                        <div class="stat-number">{{ $payments->where('status', 'rejected')->count() }}</div>
                                        <div style="font-size: 0.75rem; opacity: 0.9;">Rejected</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-body" style="padding: 2rem;">

                                <!-- Filtros modernos -->
                                <div class="filters-container">
                                    <div class="filter-group">
                                        <div class="filter-item">
                                            <label>Status</label>
                                            <select id="statusFilter">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <label>Payment Method</label>
                                            <select id="methodFilter">
                                                <option value="">All Methods</option>
                                                @foreach ($payments->pluck('method_name')->unique() as $method)
                                                    <option value="{{ $method }}">{{ $method }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <label>Search</label>
                                            <input type="text" id="searchFilter"
                                                placeholder="Search by user, transaction ID...">
                                        </div>
                                        <div class="filter-item">
                                            <button type="button" class="action-btn btn-view" onclick="clearFilters()"
                                                style="margin-top: 1.5rem;">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 2H4v8h12V6z" />
                                                </svg>
                                                Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Container de pagamentos -->
                                <div class="payments-container">
                                    @forelse ($payments as $key => $row)
                                        <div class="payment-card status-{{ $row->status }}"
                                            data-status="{{ $row->status }}" data-method="{{ $row->method_name }}"
                                            data-search="{{ strtolower($row->user->phone ?? '') }} {{ strtolower($row->transaction_id) }} {{ strtolower($row->address) }}">

                                            <!-- Header do card -->
                                            <div class="card-header-row">
                                                <div>
                                                    <div class="payment-id">Payment
                                                        #{{ str_pad($row->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                    <div class="user-info">
                                                        <div class="user-avatar">
                                                            {{ strtoupper(substr($row->user->phone ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <span
                                                            class="detail-value">{{ $row->user->phone ?? 'Unknown User' }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="payment-amount">{{ price($row->amount) }}</div>
                                                    <div class="status-badge {{ $row->status->value }}">
                                                        @if ($row->status == 'approved')
                                                            <svg class="status-icon" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @elseif($row->status == 'pending')
                                                            <svg class="status-icon" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg class="status-icon" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                        {{ ucfirst($row->status->value) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detalhes do pagamento -->
                                            <div class="payment-details">
                                                <div class="detail-item">
                                                    <div class="detail-label">Payment Method</div>
                                                    <div class="detail-value">{{ $row->method_name }}</div>
                                                </div>
                                                <div class="detail-item">
                                                    <div class="detail-label">Transaction ID</div>
                                                    <div class="detail-value"
                                                        style="font-family: monospace; font-size: 0.8rem;">
                                                        {{ Str::limit($row->transaction_id, 20) }}
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <div class="detail-label">Address</div>
                                                    <div class="detail-value"
                                                        style="font-family: monospace; font-size: 0.8rem;">
                                                        {{ Str::limit($row->address, 25) }}
                                                    </div>
                                                </div>
                                                <div class="detail-item">
                                                    <div class="detail-label">Date</div>
                                                    <div class="detail-value">{{ $row->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botões de ação -->
                                            <div class="action-buttons">
                                                @if ($row->status == 'approved')
                                                    <div class="status-badge approved">
                                                        <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Payment Approved
                                                    </div>
                                                @elseif($row->status == 'pending')
                                                    <a href="{{ route('payment.status.change.approved', $row->id) }}"
                                                        class="action-btn btn-approve">
                                                        <svg width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Approve
                                                    </a>
                                                    <a href="{{ route('payment.status.change.rejected', $row->id) }}"
                                                        class="action-btn btn-reject">
                                                        <svg width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Reject
                                                    </a>
                                                @elseif($row->status == 'rejected')
                                                    <div class="status-badge rejected">
                                                        <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Payment Rejected
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <svg class="empty-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 style="margin-bottom: 0.5rem; color: #374151;">No payments found</h3>
                                            <p>There are no payment records to display.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Filtros interativos
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const methodFilter = document.getElementById('methodFilter');
            const searchFilter = document.getElementById('searchFilter');
            const paymentCards = document.querySelectorAll('.payment-card');

            function filterPayments() {
                const statusValue = statusFilter.value.toLowerCase();
                const methodValue = methodFilter.value.toLowerCase();
                const searchValue = searchFilter.value.toLowerCase();

                paymentCards.forEach(card => {
                    const cardStatus = card.dataset.status;
                    const cardMethod = card.dataset.method.toLowerCase();
                    const cardSearch = card.dataset.search;

                    const matchesStatus = !statusValue || cardStatus === statusValue;
                    const matchesMethod = !methodValue || cardMethod.includes(methodValue);
                    const matchesSearch = !searchValue || cardSearch.includes(searchValue);

                    if (matchesStatus && matchesMethod && matchesSearch) {
                        card.style.display = 'block';
                        card.style.animation = 'slideIn 0.3s ease-out';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Verifica se há resultados
                const visibleCards = document.querySelectorAll(
                    '.payment-card[style*="block"], .payment-card:not([style*="none"])');
                const emptyState = document.querySelector('.empty-state');

                if (visibleCards.length === 0 && paymentCards.length > 0) {
                    if (!document.querySelector('.no-results')) {
                        const noResults = document.createElement('div');
                        noResults.className = 'empty-state no-results';
                        noResults.innerHTML = `
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 style="margin-bottom: 0.5rem; color: #374151;">No results found</h3>
                            <p>Try adjusting your filters or search terms.</p>
                        `;
                        document.querySelector('.payments-container').appendChild(noResults);
                    }
                } else {
                    const noResults = document.querySelector('.no-results');
                    if (noResults) {
                        noResults.remove();
                    }
                }
            }

            statusFilter.addEventListener('change', filterPayments);
            methodFilter.addEventListener('change', filterPayments);
            searchFilter.addEventListener('input', filterPayments);

            // Função para limpar filtros
            window.clearFilters = function() {
                statusFilter.value = '';
                methodFilter.value = '';
                searchFilter.value = '';
                filterPayments();
            };

            // Confirmação para ações
            document.querySelectorAll('.btn-approve, .btn-reject').forEach(button => {
                button.addEventListener('click', function(e) {
                    const action = this.classList.contains('btn-approve') ? 'approve' : 'reject';
                    const paymentId = this.href.split('/').pop();

                    if (!confirm(`Are you sure you want to ${action} this payment?`)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
