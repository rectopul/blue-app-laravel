@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">
                            <div class="d-flex justify-content-between">
                                <div>Customers Lists</div>
                                <div>
                                    <a href="{{ route('admin.search.user') }}" class="btn btn-success"><i
                                            class="bx bx-user"></i> Search A User</a>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="mes">

                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>Photo</th>
                                            <th>Referred by</th>
                                            <th>Referral id</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Active VIPs</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $row)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="{{ asset($row->photo ? view_image($row->photo) : not_found_img()) }}"
                                                        target="_blank">
                                                        <img width="40"
                                                            src="{{ asset($row->photo ? view_image($row->photo) : not_found_img()) }}"
                                                            alt="Package Photo">
                                                    </a>
                                                </td>
                                                <td>{{ $row->ref_by ?? 'Not Use' }}</td>
                                                <td>{{ $row->ref_id }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->phone }}</td>
                                                <td>
                                                    @foreach (my_vips() as $id)
                                                        <div class="badge badge-secondary">
                                                            {{ \App\Models\Package::find($id)->name ?? '---' }}
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($row->balance, 2) }}</td>
                                                <td>{{ $row->status }}</td>
                                                <td>
                                                    <!-- Ban/Unban -->
                                                    @if ($row->ban_unban == 'unban')
                                                        <a href="{{ route('admin.user.ban', $row->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            style="padding: 3px 7px;font-size: 16px" title='Account Ban'>
                                                            <i class="bx bx-user-minus"></i></a>
                                                        <span style="color: green; font-size: 12px;">UnBan <i
                                                                class="bx bx-check"></i> </span>
                                                    @else
                                                        <a href="{{ route('admin.user.unban', $row->id) }}"
                                                            class="btn btn-success btn-sm"
                                                            style="padding: 3px 7px;font-size: 16px" title='Account UnBan'>
                                                            <i class="bx bx-user-plus"></i></a>
                                                        <span style="color: red; font-size: 12px;">Ban <i
                                                                class="bx bx-x"></i> </span>
                                                    @endif

                                                    <!-- Reset Password -->
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#passwordModal{{ $row->id }}"
                                                        title="Reset Password">
                                                        <i class="bx bx-key"></i>
                                                    </button>

                                                    <!-- Add Balance -->
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#balanceModal{{ $row->id }}"
                                                        title="Add Balance">
                                                        <i class="bx bx-wallet"></i>
                                                    </button>

                                                    <!-- Gift Bonus -->
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#bonusModal{{ $row->id }}" title="Gift Bonus">
                                                        <i class="bx bx-gift"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Reset Password -->
                                            <div class="modal fade" id="passwordModal{{ $row->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reset Password - {{ $row->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form id="passwordForm{{ $row->id }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="password{{ $row->id }}"
                                                                        class="form-label">New Password</label>
                                                                    <input type="password" name="password"
                                                                        id="password{{ $row->id }}"
                                                                        class="form-control"
                                                                        placeholder="Enter new password" required
                                                                        minlength="6">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password_confirmation{{ $row->id }}"
                                                                        class="form-label">Confirm Password</label>
                                                                    <input type="password" name="password_confirmation"
                                                                        id="password_confirmation{{ $row->id }}"
                                                                        class="form-control"
                                                                        placeholder="Confirm new password" required
                                                                        minlength="6">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Password</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Add Balance -->
                                            <div class="modal fade" id="balanceModal{{ $row->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Add Balance - {{ $row->name }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form id="balanceForm{{ $row->id }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="amount{{ $row->id }}"
                                                                        class="form-label">Amount</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">R$</span>
                                                                        <input type="number" name="amount"
                                                                            id="amount{{ $row->id }}"
                                                                            class="form-control" placeholder="0.00"
                                                                            step="0.01" min="0.01" required>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="operation{{ $row->id }}"
                                                                        class="form-label">Operation</label>
                                                                    <select name="operation"
                                                                        id="operation{{ $row->id }}"
                                                                        class="form-select" required>
                                                                        <option value="add">Add (+)</option>
                                                                        <option value="subtract">Subtract (-)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="reason{{ $row->id }}"
                                                                        class="form-label">Reason</label>
                                                                    <input type="text" name="reason"
                                                                        id="reason{{ $row->id }}"
                                                                        class="form-control"
                                                                        placeholder="Reason for balance change" required>
                                                                </div>
                                                                <div class="alert alert-info">
                                                                    <strong>Current Balance:</strong> R$
                                                                    {{ number_format($row->balance, 2) }}
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-info">Update
                                                                    Balance</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Gift Bonus -->
                                            <div class="modal fade" id="bonusModal{{ $row->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Gift Bonus - {{ $row->name }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form id="bonusForm{{ $row->id }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="bonus{{ $row->id }}"
                                                                        class="form-label">Bonus Code</label>
                                                                    <input type="text" name="bonus"
                                                                        id="bonus{{ $row->id }}"
                                                                        class="form-control"
                                                                        placeholder="Enter bonus code" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Apply
                                                                    Bonus</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $users->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Reset Password Function
        $(document).ready(function() {
            $('form[id^="passwordForm"]').on('submit', function(e) {
                e.preventDefault();

                const formId = $(this).attr('id');
                const userId = formId.replace('passwordForm', '');
                const password = $(`#password${userId}`).val();
                const passwordConfirmation = $(`#password_confirmation${userId}`).val();

                // Validate passwords match
                if (password !== passwordConfirmation) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Passwords do not match!'
                    });
                    return;
                }

                $.ajax({
                    url: `/admin/user/${userId}/reset-password`,
                    method: 'POST',
                    data: {
                        password: password,
                        password_confirmation: passwordConfirmation,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(`#passwordForm${userId} button[type="submit"]`).prop('disabled', true)
                            .text('Updating...');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Password updated successfully!'
                        });
                        $(`#passwordModal${userId}`).modal('hide');
                        $(`#passwordForm${userId}`)[0].reset();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error updating password';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error
                        });
                    },
                    complete: function() {
                        $(`#passwordForm${userId} button[type="submit"]`).prop('disabled',
                            false).text('Update Password');
                    }
                });
            });
        });

        // Add/Subtract Balance Function
        $(document).ready(function() {
            $('form[id^="balanceForm"]').on('submit', function(e) {
                e.preventDefault();

                const formId = $(this).attr('id');
                const userId = formId.replace('balanceForm', '');
                const amount = parseFloat($(`#amount${userId}`).val());
                const operation = $(`#operation${userId}`).val();
                const reason = $(`#reason${userId}`).val();

                $.ajax({
                    url: `/admin/user/${userId}/update-balance`,
                    method: 'POST',
                    data: {
                        amount: amount,
                        operation: operation,
                        reason: reason,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(`#balanceForm${userId} button[type="submit"]`).prop('disabled', true)
                            .text('Updating...');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Balance updated successfully!'
                        }).then(() => {
                            location.reload(); // Reload to show updated balance
                        });
                        $(`#balanceModal${userId}`).modal('hide');
                        $(`#balanceForm${userId}`)[0].reset();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error updating balance';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error
                        });
                    },
                    complete: function() {
                        $(`#balanceForm${userId} button[type="submit"]`).prop('disabled', false)
                            .text('Update Balance');
                    }
                });
            });
        });

        // Gift Bonus Function
        $(document).ready(function() {
            $('form[id^="bonusForm"]').on('submit', function(e) {
                e.preventDefault();

                const formId = $(this).attr('id');
                const userId = formId.replace('bonusForm', '');
                const bonus = $(`#bonus${userId}`).val();

                $.ajax({
                    url: `/admin/user/${userId}/gift-bonus`,
                    method: 'POST',
                    data: {
                        bonus: bonus,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $(`#bonusForm${userId} button[type="submit"]`).prop('disabled', true)
                            .text('Applying...');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Bonus applied successfully!'
                        });
                        $(`#bonusModal${userId}`).modal('hide');
                        $(`#bonusForm${userId}`)[0].reset();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Error applying bonus';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error
                        });
                    },
                    complete: function() {
                        $(`#bonusForm${userId} button[type="submit"]`).prop('disabled', false)
                            .text('Apply Bonus');
                    }
                });
            });
        });
    </script>
    <script>
        function submitBonus(id) {
            var bonus = document.getElementById('bonus').value;
            console.log(bonus)
            var data = {
                id: id,
                bonus: bonus
            }
            fetch('{{ route('admin.customer.bonus') }}', {
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === true) {
                        document.querySelector('.mes').innerHTML =
                            `<div class="alert alert-success">${data.message}</div>`
                        document.querySelector('#bonusModal' + id).style.display = 'none'
                        document.querySelector('.modal-backdrop.show').style.display = 'none'
                    } else {
                        document.querySelector('.mes').innerHTML =
                            `<div class="alert alert-success">Something went wrong</div>`
                    }
                }).catch();
        }
    </script>


    <script>
        function resetPassword(id) {
            var password = document.querySelector('input[name="password"]').value;
            var data = {
                id: id,
                password: password
            }
            fetch('{{ route('admin.customer.change-password') }}', {
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        'Content-type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === true) {
                        document.querySelector('.mes').innerHTML =
                            `<div class="alert alert-success">${data.message}</div>`
                        window.location.reload();
                    } else {
                        document.querySelector('.mes').innerHTML =
                            `<div class="alert alert-success">Something went wrong</div>`
                    }
                });
        }
    </script>
@endsection
