@extends('layout.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/css/dataTables.bootstrap5.min.css">

<div class="content text-right" dir="rtl">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">صلاحيات الوصول للمستخدمين</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="permsTable" class="table table-hover table-striped w-100">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الالكتروني</th>
                            <th>عدد الصلاحيات الممنوحة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @php $userGrants = $grants[$user->id] ?? []; @endphp
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary">{{ count($userGrants) }}</span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary manage-access-btn"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-granted="{{ implode(',', $userGrants) }}">
                                        إدارة الصلاحيات
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- One shared modal, filled via JS for whichever user was clicked --}}
<div class="modal fade" id="accessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form id="accessForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">صلاحيات: <span id="modalUserName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-right" dir="rtl">
                    @foreach($modules as $key => $label)
                        <div class="form-check mb-2">
                            <input class="form-check-input module-checkbox" type="checkbox"
                                name="modules[]" value="{{ $key }}" id="mod_{{ $key }}">
                            <label class="form-check-label" for="mod_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function () {
        $('#permsTable').DataTable({
            language: {
                search: "بحث:",
                lengthMenu: "عرض _MENU_ سجل",
                info: "عرض _START_ إلى _END_ من _TOTAL_",
                paginate: { next: "التالي", previous: "السابق" }
            }
        });

        $('.manage-access-btn').on('click', function () {
            var granted = ($(this).data('granted') || '').toString().split(',').filter(Boolean);

            $('#modalUserName').text($(this).data('user-name'));
            $('#accessForm').attr('action', '/access-permissions/' + $(this).data('user-id'));

            $('.module-checkbox').each(function () {
                $(this).prop('checked', granted.indexOf($(this).val()) !== -1);
            });

            new bootstrap.Modal(document.getElementById('accessModal')).show();
        });
    });
</script>
@endsection