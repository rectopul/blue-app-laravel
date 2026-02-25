@extends('admin.partials.master')

@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">

                <form action="{{ route('admin.package.insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $data ? $data->id : '' }}">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div>{{ $data ? 'Update' : 'Create New' }} Package</div>
                                    <div>
                                        <a href="{{ route('admin.package.index') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-left-arrow"></i> Package List
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>

                        <div class="card-content">
                            <div class="card-body">

                                {{-- Alerts --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Ops!</strong> Verifique os campos abaixo.
                                        <ul class="mb-0 mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col-sm-6">
                                        <label for="name">Package Name</label>
                                        <input type="text"
                                               class="form-control is-valid"
                                               name="name" id="name"
                                               placeholder="Name"
                                               value="{{ old('name', $data ? $data->name : '') }}"
                                               required>
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This field is required
                                        </div>
                                    </div>

                                    {{-- Title --}}
                                    <div class="col-sm-6">
                                        <label for="title">Package Title</label>
                                        <input type="text"
                                               class="form-control is-valid"
                                               name="title" id="title"
                                               placeholder="Title"
                                               value="{{ old('title', $data ? $data->title : '') }}"
                                               required>
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This field is required
                                        </div>
                                    </div>

                                    {{-- Photo --}}
                                    <div class="col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">
                                                        Upload Photo
                                                        <small>(Suggestion: size 200x200 px)</small>
                                                    </label>

                                                    <div class="custom-file">
                                                        <input type="file"
                                                               name="photo"
                                                               class="custom-file-input is-valid"
                                                               id="inputGroupFile01"
                                                               @if(!$data) required @endif
                                                               onchange="showPreview(event)">
                                                        <label class="custom-file-label" for="inputGroupFile01">
                                                            Choose file
                                                        </label>

                                                        <div class="valid-feedback">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Note: Package image mandatory on create
                                                        </div>
                                                    </div>

                                                    @if($data && $data->photo)
                                                        <small class="text-muted d-block mt-1">
                                                            * Se você não escolher uma nova imagem, a atual será mantida.
                                                        </small>
                                                    @endif
                                                </fieldset>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="image_preview">
                                                    <img
                                                        src="{{ $data && $data->photo ? asset('storage/'.$data->photo) : asset(not_found_img()) }}"
                                                        id="file-ip-1-preview"
                                                        class="rounded"
                                                        alt="Preview Image"
                                                        style="width: 110px;height: 110px;object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Price --}}
                                    <div class="col-sm-6 mt-2">
                                        <label for="price">Price</label>
                                        <input type="number"
                                               class="form-control is-valid"
                                               name="price" id="price"
                                               placeholder="Price"
                                               value="{{ old('price', $data ? $data->price : '') }}"
                                               required
                                               step="0.01"
                                               oninput="calcReturns()">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This field is required
                                        </div>
                                    </div>

                                    {{-- Validity --}}
                                    <div class="col-sm-6 mt-2">
                                        <label for="validity">Validity (days)</label>
                                        <input type="number"
                                               class="form-control is-valid"
                                               name="validity" id="validity"
                                               placeholder="Validity (days)"
                                               value="{{ old('validity', $data ? $data->validity : '') }}"
                                               required
                                               oninput="calcReturns()">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This field is required
                                        </div>
                                    </div>

                                    {{-- Commission --}}
                                    <div class="col-sm-6 mt-2">
                                        <label for="commission_with_avg_amount">Commission with average amount (%)</label>
                                        <input type="number"
                                               class="form-control is-valid"
                                               name="commission_with_avg_amount"
                                               id="commission_with_avg_amount"
                                               placeholder="Ex: 2.5"
                                               value="{{ old('commission_with_avg_amount', $data ? $data->commission_with_avg_amount : '') }}"
                                               required
                                               step="0.01"
                                               oninput="calcReturns()">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This field is required
                                        </div>
                                    </div>

                                    {{-- Daily Return Preview --}}
                                    <div class="col-sm-6 mt-2">
                                        <label>Daily Return (preview)</label>
                                        <input type="text" class="form-control" id="daily_return_preview" value="R$ 0,00" readonly>
                                        <small class="text-muted d-block mt-1">
                                            Calculado: <b>price</b> × (<b>%</b> / 100)
                                        </small>
                                    </div>

                                    {{-- Total Return Preview --}}
                                    <div class="col-sm-6 mt-2">
                                        <label>Total Return (preview)</label>
                                        <input type="text" class="form-control" id="total_return_preview" value="R$ 0,00" readonly>
                                        <small class="text-muted d-block mt-1">
                                            Calculado: <b>Daily Return</b> × <b>validity (days)</b>
                                        </small>
                                    </div>

                                    {{-- End Date Preview --}}
                                    <div class="col-sm-6 mt-2">
                                        <label>End Date (preview)</label>
                                        <input type="text" class="form-control" id="end_date_preview" value="—" readonly>
                                        <small class="text-muted d-block mt-1">
                                            Estimativa: hoje + validity (dias)
                                        </small>
                                    </div>

                                    {{-- Status --}}
                                    @if($data)
                                        <div class="col-sm-6 mt-2">
                                            <label for="status">Package Status</label>
                                            <select name="status" class="form-control" id="status">
                                                <option value="active" @selected($data->status === 'active')>Active</option>
                                                <option value="inactive" @selected($data->status === 'inactive')>In-Active</option>
                                            </select>

                                            <div class="valid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                Note: This field is required
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div style="margin-top: .7rem !important">
                                        Submit Your Package Information
                                    </div>

                                    <div>
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-success">
                                                <i class="bx bx-plus"></i>
                                                {{ $data ? 'Update' : 'Submit' }}
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </h6>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script>
        function showPreview(event) {
            if (event.target.files.length > 0) {
                const src = URL.createObjectURL(event.target.files[0]);
                const preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        function formatBRL(value) {
            const n = Number(value || 0);
            return n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function calcReturns() {
            const price = Number(document.getElementById('price')?.value || 0);
            const days = Number(document.getElementById('validity')?.value || 0);
            const percent = Number(document.getElementById('commission_with_avg_amount')?.value || 0);

            const daily = price * (percent / 100);
            const total = daily * (days > 0 ? days : 0);

            const dailyEl = document.getElementById('daily_return_preview');
            const totalEl = document.getElementById('total_return_preview');
            const endEl = document.getElementById('end_date_preview');

            if (dailyEl) dailyEl.value = formatBRL(daily);
            if (totalEl) totalEl.value = formatBRL(total);

            // End date preview (hoje + dias)
            if (endEl) {
                if (days > 0) {
                    const d = new Date();
                    d.setDate(d.getDate() + days);

                    const dd = String(d.getDate()).padStart(2, '0');
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const yy = d.getFullYear();

                    endEl.value = `${dd}/${mm}/${yy}`;
                } else {
                    endEl.value = '—';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            calcReturns();
        });
    </script>
@endsection
