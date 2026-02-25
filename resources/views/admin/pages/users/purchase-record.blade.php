@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">
                            <div class="d-flex justify-content-between">
                                <div>Customers Invest Lists</div>
                                <div>
                                    <a href="{{ route('admin.search.user') }}" class="btn btn-success"><i
                                            class="bx bx-user"></i> Search A User</a>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>Referral id</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th>Invest Amount</th>
                                            <th>Invest Date</th>
                                            <th>Login</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase as $key => $row)
                                            @if ($row->user)
                                                <?php
                                                $user = $row->user;
                                                ?>
                                                <tr>
                                                    <td>{{ $row->id }}</td>
                                                    <td>{{ $user->ref_id ?? '000000' }}</td>
                                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                                    <td>{{ price($user->balance ?? 0) }}</td>
                                                    <td>{{ price($row->amount) }}</td>
                                                    <td>{{ $row->created_at }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.customer.login', $row->user->id) }}"
                                                            target="_blank" class="btn btn-info"
                                                            style="padding: 3px 7px;font-size: 20px" data-toggle="tooltip"
                                                            title='Login Into User Account'>
                                                            <i class="bx bx-user"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.purchase-delete', $row->id) }}"
                                                            class="btn btn-danger" style="padding: 3px 7px;font-size: 20px"
                                                            data-toggle="tooltip" title='Delete'>
                                                            <i class="bx bx-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                </table>
                                {{ $purchase->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
